<?php 
// define('DB_NAME', 'editorial-marketing');
// define('DB_USER', 'editorial-market');
// define('DB_PASSWORD', 'editorial-market');
// define('DB_HOST', 'localhost');

define('DB_NAME', 'web_editorialtemplate_marketing');
define('DB_USER', 'sql_editorial');
define('DB_PASSWORD', 'xQyuz4vzJZSGC8Bv');
define('DB_HOST', 'localhost');

// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

define('PACKAGE_URL', 'http://editorialtemplate.com/new-moon/');
//define('PACKAGE_URL', 'http://localhost:8888/editorial-marketing/new-moon/');
define('FILENAME', 'editorial.zip');

//TODO - if the chekc for domain is not valid, do not even kreate a tmp key!!

//Database Info
$db = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Can\'t connect do database');
@mysql_select_db(DB_NAME) or die('The database selected does not exists');

/*******
 Original Plugin & Theme API by Kaspars Dambis (kaspars@konstruktors.com)
 Modified by Jeremy Clark http://clark-technet.com
 Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SE9ZVJUS324UC
*******/



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



class VersionCheck
{

	private $user_agent;
	private $action;
	private $args;
	private $acess_domain;
	

	public function __construct()
	{


		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->action = $_POST['action'];
		$this->args = unserialize($_POST['request']);
		$parsed = parse_url($_POST['blog-url']);
		$url = sprintf('%s://%s', $parsed['scheme'] ? $parsed['scheme'] : 'http', $parsed['host']); 
		$this->acess_domain = $url;



		$this->deleteExpiredKeys();

		if (stristr($this->user_agent, 'WordPress') == TRUE)
		{
			
			$this->proceedWithCheck();
		}
		else
		{
			echo 'Whoops, this page doesn\'t exist <br><br>';
		}

	}

	public function proceedWithCheck()
	{
		if (is_array($this->args)) $this->args = $this->array_to_object($this->args);
		//get a unique download key
		$domain_valid = $this->check_if_domain_valid( $this->acess_domain );

		//if domain is not valid stop right here and go home
		if ( !$domain_valid )
		{
			print serialize( array('valid'=> 0));
		}
		else
		{

			$json = json_decode(file_get_contents('version.json'));
			$latest_package = array_shift($json->versions);

			$strKey = $this->createKey();

			$latest_package->package = PACKAGE_URL.'download.php?key=' . $strKey;
			$latest_package->file_name = FILENAME;	//File name of theme zip file
			$latest_package->valid = 1;	

			switch ($this->action) {
				case 'basic_check':
					$latest_package->slug = $this->args->slug;

					if (version_compare($this->args->version, $latest_package->version, '<')){
						$latest_package->new_version = $update_info->version;
						print serialize($latest_package);
					}	
					break;

				case 'theme_update':
					$update_data = array();
					$update_data['package'] = $latest_package->package;	
					$update_data['valid'] = $latest_package->valid;	
					$update_data['new_version'] = $latest_package->version;
					$update_data['url'] = $json->info->url;

					$update_data['from_domain'] = $this->acess_domain;
					mysql_query("INSERT INTO wp_autoupdate_downloads (downloadkey, file, expires) VALUES ('{$strKey}', '{$latest_package->file_name}', '".(time()+(60*60*24*7))."')");		
					if (version_compare($this->args->version, $latest_package->version, '<'))
						print serialize($update_data);
					break;
				
				case 'theme_information':
					$data = new stdClass;
					$data->slug = $this->args->slug;
					$data->name = $latest_package->name;	
					$data->version = $latest_package->version;
					$data->last_updated = $latest_package->date;
					$data->download_link = $latest_package->package;
					$data->author = $latest_package->author;
					$data->requires = $latest_package->requires;
					$data->tested = $latest_package->tested;
					$data->screenshot_url = $latest_package->screenshot_url;
					//insert the download record into the database
					//Uncomment if using url masking
					mysql_query("INSERT INTO wp_autoupdate_downloads (downloadkey, file, expires) VALUES ('{$strKey}', '{$latest_package->file_name}', '".(time()+(60*60*24*7))."')");
					print serialize($data);
					break;
			}


		}

	}

	public function deleteExpiredKeys()
	{
		// Deletes records over two weeks old
		mysql_query("DELETE FROM wp_autoupdate_downloads WHERE expires > '" .(time()+(60*60*24*14))."' ");
	}

	public function createKey(){
	//create a random key

	//maybe even better, use the blogurl md5 as a key
		$strKey = md5(microtime());

		//check to make sure this key isnt already in use
		$resCheck = mysql_query("SELECT count(*) FROM wp_autoupdate_downloads WHERE downloadkey = '{$strKey}' LIMIT 1");
		$arrCheck = mysql_fetch_assoc($resCheck);
		if($arrCheck['count(*)']){
			//key already in use
			return $this->createKey();
		}else{
			//key is OK
			return $strKey;
		}
	}

	public function array_to_object($array = array()) 
	{
		if (empty($array) || !is_array($array))
			return false;

		$data = new stdClass;
		foreach ($array as $akey => $aval)
				$data->{$akey} = $aval;
		return $data;
	}

	public function check_if_domain_valid( $domain )
	{

		$url = parse_url( $domain );
		if( $url['host'] == 'localhost') return 1;

		$query = "SELECT COUNT(name) AS num FROM domain WHERE name = '".mysql_real_escape_string($domain)."'";
		$rs = mysql_query($query);
		$row = mysql_fetch_assoc($rs);

		return $row['num'];
	}



}


$check = new VersionCheck();



mysql_close($db);

?>