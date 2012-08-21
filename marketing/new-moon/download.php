<?php
/*
*
* One Time Download
* Jacob Wyke
* jacob@frozensheep.com
*
*/

//The directory where the download files are kept - random folder names are best
$strDownloadFolder = "./update/";

//If you can download a file more than once
//$boolAllowMultipleDownload = 0;

//connect to the DB

/***********************
DATABASE INFO
************************/
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
// define('DB_NAME', 'editorial-marketing');
// define('DB_USER', 'editorial-market');
// define('DB_PASSWORD', 'editorial-market');
// define('DB_HOST', 'localhost');

define('DB_NAME', 'web_editorialtemplate_marketing');

/** MySQL database username */
define('DB_USER', 'sql_editorial');

/** MySQL database password */
define('DB_PASSWORD', 'xQyuz4vzJZSGC8Bv');

/** MySQL hostname */
define('DB_HOST', 'localhost');

  $resDB = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  mysql_select_db(DB_NAME, $resDB);

if(!empty($_GET['key'])){
  //check the DB for the key
  $resCheck = mysql_query("SELECT * FROM wp_autoupdate_downloads WHERE downloadkey = '".mysql_escape_string($_GET['key'])."' LIMIT 1");
  $arrCheck = mysql_fetch_assoc($resCheck);

//var_dump($arrCheck);

  if(!empty($arrCheck['file'])){
    //check that the download time hasnt expired
    if($arrCheck['expires']>=time()){
      if(!$arrCheck['downloads']){
        //everything is hunky dory - check the file exists and then let the user download it
        $strDownload = $strDownloadFolder.$arrCheck['file'];
        
        if(file_exists($strDownload)){
          
          //get the file content
          $strFile = file_get_contents($strDownload);
          
          //set the headers to force a download
          header("Content-type: application/force-download");
          header("Content-Disposition: attachment; filename=\"".str_replace(" ", "_", $arrCheck['file'])."\"");
          
          //echo the file to the user
          echo $strFile;
         
					//update the DB to say this file has been downloaded
					mysql_query("DELETE FROM wp_autoupdate_downloads WHERE downloadkey = '".mysql_escape_string($_GET['key'])."'");
          //mysql_query("UPDATE wp_autoupdate_downloads SET downloads = downloads + 1 WHERE downloadkey = '".mysql_escape_string($_GET['key'])."' LIMIT 1");
          
          exit;
          
        }else{
          echo "We couldn't find the file to download.";
        }
      }else{
        //this file has already been downloaded and multiple downloads are not allowed
        echo "This file has already been downloaded.";
      }
    }else{
      //this download has passed its expiry date
      echo "This download has expired.";
    }
  }else{
    //the download key given didnt match anything in the DB
    echo "No file was found to download.";
  }
}else{
  //No download key wa provided to this script
  echo "No download key was provided. Please return to the previous page and try again.";
}

?>