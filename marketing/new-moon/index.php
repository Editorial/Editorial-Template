<?php 
define('DB_NAME', 'editorial-marketing');
define('DB_USER', 'editorial-market');
define('DB_PASSWORD', 'editorial-market');
define('DB_HOST', 'localhost');


//define('PACKAGE_URL', 'http://editorialtemplate.com/new-moon/');
define('PACKAGE_URL', 'http://localhost:8888/editorial-marketing/new-moon/');
define('FILENAME', 'editorial.zip');


//Database Info
$db = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Can\'t connect do database');
@mysql_select_db(DB_NAME) or die('The database selected does not exists');

/*******
 Original Plugin & Theme API by Kaspars Dambis (kaspars@konstruktors.com)
 Modified by Jeremy Clark http://clark-technet.com
 Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SE9ZVJUS324UC
*******/

// Pull user agent  
$user_agent = $_SERVER['HTTP_USER_AGENT'];


//Kill magic quotes.  Can't unserialize POST variable otherwise
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}



//Create one time download link to secure zip file location
if (stristr($user_agent, 'WordPress') == TRUE){
	/*
	*
	* Create Download Link
	* Jaocb Wyke
	* jacob@frozensheep.com
	*
	*/

/**********************************************
Uncomment Below Section to enable url masking
**********************************************/

	function createKey(){
	//create a random key
		$strKey = md5(microtime());

		//check to make sure this key isnt already in use
		$resCheck = mysql_query("SELECT count(*) FROM wp_autoupdate_downloads WHERE downloadkey = '{$strKey}' LIMIT 1");
		$arrCheck = mysql_fetch_assoc($resCheck);
		if($arrCheck['count(*)']){
			//key already in use
			return createKey();
		}else{
			//key is OK
			return $strKey;
		}
	}

	//get a unique download key
	$strKey = createKey();

	// Deletes records over two weeks old
	mysql_query("DELETE FROM wp_autoupdate_downloads WHERE expires > '" .(time()+(60*60*24*14))."' ");


//}

// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

// if (stristr($user_agent, 'WordPress') == TRUE){
	
	// Process API requests
	$action = $_POST['action'];
	$args = unserialize($_POST['request']);
	$acess_domain = $_POST['blog-url'];

	if (is_array($args))
		$args = array_to_object($args);
	
	$domain_valid = check_if_domain_valid( $acess_domain );
	
	// Theme with update info
	$packages['editorial'] = array(			//Replace theme with theme stylesheet slug that the update is for
		'versions' => array(
			'1.1' => array(				//Array name should be set to current version of update
				'valid' => $domain_valid, //check validity of the domain of the theme !!!TODO
				'version' => '1.1', 	//Current version available
				'date' => '2012-07-17',	//Date version was released
				/*
				Remove line below if using one time download link 
				*/
				//'package' => 'http://editorialtemplate.com/new-moon/editorial.zip',  // The zip file of the theme update
							/*
				Use below value if using the one time download link.  Point to location of download.php file on your server.
				*/
				'package' => PACKAGE_URL.'download.php?key=' . $strKey,
				'file_name' => FILENAME,	//File name of theme zip file
				'author'  =>	'Editorial',		//Author of theme
				'name' =>		'Editorial Template',		//Name of theme
				'requires'=>	'3.1',				//Wordpress version required
				'tested' =>		'3.1',				//WordPress version tested up to
				'screenshot_url'=>	'http://editorialtemplate.com/screenshot.png'	//url of screenshot of theme
			)
		),
		'info' => array(
			'url' => 'http://editorialtemplate.com'  // Website devoted to theme if available
		)
	);


	$latest_package = array_shift($packages[$args->slug]['versions']);
	
	
	
	
	// basic_check

	if ($action == 'basic_check') {	
		$update_info = array_to_object($latest_package);
		$update_info->slug = $args->slug;

		if (version_compare($args->version, $latest_package['version'], '<')){
			$update_info->new_version = $update_info->version;
			print serialize($update_info);
		}	
	}



	// theme_update

	if ($action == 'theme_update') {
		$update_info = array_to_object($latest_package);
		$update_data = array();
		$update_data['package'] = $update_info->package;	
		$update_data['valid'] = $update_info->valid;	
		$update_data['new_version'] = $update_info->version;
		$update_data['url'] = $packages[$args->slug]['info']['url'];

		$update_data['from_domain'] = $acess_domain;
		//insert the download record into the database
		//Uncomment if using url masking
		mysql_query("INSERT INTO wp_autoupdate_downloads (downloadkey, file, expires) VALUES ('{$strKey}', '{$update_info->file_name}', '".(time()+(60*60*24*7))."')");		
		if (version_compare($args->version, $latest_package['version'], '<'))
			print serialize($update_data);	
	}

	if ($action == 'theme_information') {	
		$data = new stdClass;
		$data->slug = $args->slug;
		$data->name = $latest_package['name'];	
		$data->version = $latest_package['version'];
		$data->last_updated = $latest_package['date'];
		$data->download_link = $latest_package['package'];
		$data->author = $latest_package['author'];
		$data->requires = $latest_package['requires'];
		$data->tested = $latest_package['tested'];
		$data->screenshot_url = $latest_package['screenshot_url'];
		//insert the download record into the database
		//Uncomment if using url masking
		mysql_query("INSERT INTO wp_autoupdate_downloads (downloadkey, file, expires) VALUES ('{$strKey}', '{$latest_package['file_name']}', '".(time()+(60*60*24*7))."')");
		print serialize($data);
	}
		
	
	
	
} else {
	/*
	An error message can be displayed to users who go directly to the update url
	*/

	echo 'Whoops, this page doesn\'t exist <br><br>';
	
	// $res = check_if_domain_valid(  "http://localhost:8888/dummy-editorial" );
	// 		
	// 	var_dump($res);
	
	
}
function array_to_object($array = array()) {
	if (empty($array) || !is_array($array))
		return false;

	$data = new stdClass;
	foreach ($array as $akey => $aval)
			$data->{$akey} = $aval;
	return $data;
}

function check_if_domain_valid( $domain )
{

	$query = "SELECT COUNT(name) AS num FROM domain WHERE name = '".mysql_escape_string($domain)."'";
	$rs = mysql_query($query);
	$row = mysql_fetch_assoc($rs);
	//var_dump(mysql_escape_string($domain));

	return $row['num'];
}


mysql_free_result($rs);
mysql_close($db);

?>