// Editorial Trial system
// Looks for new trial entries in the database, creates a folder with the same name
// and sets up wordpress in that folder with the editorial theme.

var config     = require('./config');
var _mysql     = require('mysql');
var crypto     = require('crypto');
var fs         = require('fs');
var ncp        = require('ncp').ncp;
var Browser    = require('zombie');
var assert     = require("assert");
var nodemailer = require("nodemailer");

// mailer settings
var mailerSettings = {
  host: config.mail.host,
  port: config.mail.port,
  secureConnection: config.mail.secure,
  auth: config.mail.auth
}

// mysql settings
var mysqlSettings = {
  host: config.db.host,
  post: config.db.port,
  user: config.db.user,
  password: config.db.pass
}

// create mysql client
var mysql = _mysql.createConnection(mysqlSettings);
mysql.connect();

// find rows ready for setup
mysql.query('USE ' + config.db.db_marketing);
mysql.query('SELECT * FROM `trial` WHERE `status` = 0 ORDER BY `date_created` LIMIT 2', function(err, results, fields) {
  if (err) throw err;
  console.log('found '+results.length+' new trials');
  //for (var i=0; i < results.length; i++) {
  results.forEach(function (trial) {
    console.log(trial.trial);
    // create password for user, it is stored in plaintext because boobs
    var password = crypto.createHash('md5').update(trial.email+Date.now()).digest("hex").substring(0,10);

    // copy wordpress into place
    console.log(trial.trial+' copy files into place');
    ncp('files/wordpress', 'trial/'+trial.trial, function (err) {
      if (err) throw err;
      var configFile = 'trial/'+trial.trial+'/wp-config.php';

      // update table prefix for install
      console.log(trial.trial+' update config file');
      fs.readFile(configFile, 'utf8', function (err, data) {
        console.log(trial.trial+' replace prefix');
        var conf = data.replace("$table_prefix  = 'wp_';", "$table_prefix  = '"+trial.trial+"_';");

        // update config file
        console.log(trial.trial+' write config file');
        fs.writeFile(configFile, conf, 'utf8', function (err) {
          if (err) throw err;

          // input user data with zombie
          var browser = new Browser();
          var trialPath = 'http://'+config.domain+'/'+trial.trial;
          console.log(trial.trial+ ' visit domain');
          browser.visit(trialPath, function() {
            console.log(trial.trial+' opened');
            assert.ok(browser.success);
            assert.equal(browser.text("title"), "WordPress › Installation");

            // fill in data
            console.log(trial.trial+' fill in data');
            browser.
              fill('weblog_title', 'Editorial Trial').
              fill('admin_password', password).
              fill('admin_password2', password).
              fill('admin_email', trial.email).
              check('blog_public').
              pressButton('Install WordPress', function () {
                console.log(trial.trial+' button pressed');
                // activate editorial theme
                // login does not work (cookies missing) so go the mysql way
                // connect to mysql again
                var mysql = _mysql.createConnection(mysqlSettings);
                mysql.connect();
                
                // update default theme settings
                mysql.query('USE '+config.db.db_trial);
                console.log(trial.trial+' update trial');
                mysql.query('UPDATE '+trial.trial+'_options SET `option_value` = "editorial" WHERE `option_name` = "template" OR `option_name` = "stylesheet"');
                
                // update password
                mysql.query('USE ' + config.db.db_marketing);
                console.log(trial.trial + ' update marketing');
                mysql.query('UPDATE trial SET `password` = "'+password+'", `status` = 1 WHERE trial = "'+trial.trial+'"', function (err) {
                  if (err) throw err;
                });
                mysql.end();
                
                // activate theme by visiting trial
                browser.visit(trialPath, function() {
                  // check everything is ok
                  console.log(trial.trial+ ' activated theme');
                  assert.equal(browser.text("title"), "Editorial Trial");

                  // send email
                  console.log(trial.trial+ ' send email');
                  var smtpTransport = nodemailer.createTransport(config.mail.type, mailerSettings);

                  var mailOptions = {
                    from: config.mail.from,
                    to: trial.email,
                    cc: config.mail.cc,
                    subject: 'Your Editorial Trial Is Ready',
                    text: "Hello!\n\nYour Editorial trial is ready. You can access it at "+trialPath+"/wp-admin with username admin and password "+password+".\n\nEditorial team."
                  };

                  // send mail with defined transport object
                  smtpTransport.sendMail(mailOptions, function(err, response){
                    if(err) throw err;
                    console.log(trial.trial+' email sent');
                    smtpTransport.close();
                  });
                });
              });
          });
        });
      });
    });
  });
});

mysql.end();