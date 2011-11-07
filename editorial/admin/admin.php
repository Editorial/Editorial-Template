<?php

/**
 * Editorial Admin class
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, Editorial
 * @link        http://www.editorialtemplate.com
 * @version     1.0
 */
class Editorial_Admin
{
	/**
	 * Look & Feel page
	 */
	const PAGE_LOOK = 'look';

	/**
	 * Share page
	 */
	const PAGE_SHARE = 'sharing';

	/**
	 * Colopho page
	 */
	const PAGE_COLOPHON = 'colophon';

	/**
	 * Pages users are allowed to include
	 *
	 * @var array
	 */
	private $_pages = array(
		self::PAGE_LOOK,
		self::PAGE_COLOPHON,
		self::PAGE_SHARE,
	);

	/**
	 * Valid options
	 *
	 * @var array
	 */
	private $_options = array(
		'logo-big',
		'logo-small',
		'logo-gallery',
	    'favicon',
	    'touch-icon',
	    'typekit-token',
	    'typekit-kit',
		'black-and-white',
		'disable-admin-notices',
		'karma',
		'karma-treshold',
		'twitter-share',
		'twitter-account',
		'twitter-related',
		'facebook-share',
		'google-share',
		//'readability-share',
		'copyright',
	);

	/**
	 * Current page
	 *
	 * @var string
	 */
	private $_page;

	/**
	 * Constructor
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function __construct()
	{
		// setup admin menu
		add_action('admin_menu', array($this, 'menus'));
		
        // check for update and if the version is valid
        $this->_checkVersion();
	}
	/**
	 * Add menu to wordpress administration
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function menus()
	{
		add_menu_page(
			'Editorial',
			'<strong>Edit</strong>orial',
			'administrator',
			'editorial',
			array($this, 'lookAndFeel'),
			get_bloginfo('template_directory').'/assets/favicon.ico'
		);
		add_submenu_page(
			'editorial',
			'Look & Feel',
			'Look & Feel',
			'administrator',
			'editorial',
			array($this, 'lookAndFeel')
		);
		add_submenu_page(
			'editorial',
			'Sharing',
			'Sharing',
			'administrator',
			'editorial-'.self::PAGE_SHARE,
			array($this, 'sharing')
		);
		add_submenu_page(
			'editorial',
			'Colophon',
			'Colophon',
			'administrator',
			'editorial-'.self::PAGE_COLOPHON,
			array($this, 'colophon')
		);
	}

	/**
	 * Look &Feel
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function lookAndFeel()
	{
		// show look & feel page
		$this->_display(self::PAGE_LOOK);
	}

	/**
	 * Edit authors
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function colophon()
	{
		$this->_display(self::PAGE_COLOPHON);
	}

	/**
	 * Sharing settings
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function sharing()
	{
		$this->_display(self::PAGE_SHARE);
	}

	/**
	 * Display admin page
	 *
	 * @param  string $page page to display
	 * @return void
	 * @author Miha Hribar
	 */
	private function _display($page = '')
	{
		if (!in_array($page, $this->_pages) || !current_user_can('administrator'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.'));
		}
		// display intended page
		$this->_page = $page;
		
		// force typekit?
		if (array_key_exists('typekit', $_GET))
		{
		    $this->typekit();
		}
		
	    // handle posts
		$this->_handlePost();
        
        // add font notice
        if (!Editorial::getOption('typekit-kit'))
        {
            add_action('admin_notices', array($this, 'fontNotice'));
        }
        
        // if black and white option is selected we need writable cache
        if (Editorial::getOption('black-and-white'))
        {
            if (!is_dir(WP_CACHE_DIR))
            {
                try
                {
                	Editorial::createPath(WP_CACHE_DIR, 0755);
                } 
                catch (Exception $e)
                {}
            }
            // can we cache now?
            if (!Editorial::canCache())
            {
                add_action('admin_notices', array($this, 'cacheNotice'));
            }
        }
		
		// include template settings page
		include 'settings.php';
	}
	
	/**
	 * Handle post
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	private function _handlePost()
	{
	    // handle posts
        if (is_array($_POST) && count($_POST))
        {
            $this->_save();
        }
	}

	/**
	 * Save form data
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	private function _save()
	{
		foreach ($_POST as $key => $value)
		{
			// make sure only allowed settings get in
			if (in_array($key, $this->_options))
			{
			    $typekit = false;
			    if ($key == 'typekit-token' && $value != Editorial::getOption('typekit-token'))
			    {
			        // run typekit
			        $typekit = true;
			    }
			    // save
				Editorial::setOption($key, $value);
				// run typekit setup
				if ($typekit)
				{
				    $this->typekit();
				}
			}
		}

		// on/off values are special
		switch ($this->_page)
		{
			case self::PAGE_LOOK:
				// checkboxes are special
				$checkboxes = array(
					'black-and-white',
					'disable-admin-notices',
					'karma',
				);
				$this->_handleCheckboxes($checkboxes);
				break;
			case self::PAGE_SHARE:
			    $checkboxes = array(
                    'twitter-share',
                    'facebook-share',
                    'google-share',
                    //'readability-share',
                );
                $this->_handleCheckboxes($checkboxes);
			    break;
			case self::PAGE_COLOPHON:
				// save current value for author ordering and titles
				if (!count($_POST['author']) || !count($_POST['title']) || count($_POST['title']) != count($_POST['author']))
				{
					// go away
					Editorial::setOption('authors', false);
					return;
				}
				$authors = array();
				foreach ($_POST['author'] as $order => $id)
				{
					$authors[$id] = $_POST['title'][$order];
				}
				Editorial::setOption('authors', $authors);
				break;
		}
	}
	
	/**
	 * Handle checkboxes
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	private function _handleCheckboxes($checkboxes)
	{
        foreach ($checkboxes as $check)
        {
            if (!isset($_POST[$check]))
            {
                Editorial::setOption($check, false);
            }
        }
	}

	/**
	 * Add notice that fonts are not enabled
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function fontNotice()
	{
		// notices can be disabled
		if (Editorial::getOption('disable-admin-notices')) return;
		$this->_showNotice(__('<strong>Editorial Typekit fonts are currently disabled.</strong> <a href="admin.php?page=editorial">Enable them</a> to get the most out of the Editorial theme.'));
	}
	
	/**
	 * Show cache notice
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function cacheNotice()
	{
		$this->_showNotice(__('<strong>Cache folder is missing or not writable</strong>. Please make sure the cache folder, located in <code>/wp-content/cache</code> exists and is writable by the server.')." ");
	}

	/**
	 * Check if an update is available
	 * -------------------------------
	 * DISCLAMER: By changing any of the code in this method you are voiding the agreement
	 * you have entered in with editorialtemplate.com and are liable for legal actions.
	 * And further more you are stealing money from honest developers trying to make
	 * a living with something awesome. Shame on you.
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	private function _checkVersion()
	{
		// notices can be disabled
		$data = file_get_contents(EDITORIAL_UPDATE_CHECK);
		if ($data !== false)
		{
			$data = json_decode($data, true);
			// version valid?
			if (!is_array($data) || !isset($data['valid']) || !$data['valid'])
			{
				add_action('admin_notices', array($this, 'invalidNotice'));
			}
			// update available?
			if (is_array($data) && isset($data['version']) && EDITORIAL_VERSION != $data['version'])
			{
			    if (Editorial::getOption('disable-admin-notices')) return;
				add_action('admin_notices', array($this, 'updateNotice'));
			}
		}
	}

	/**
	 * Add notice that an update is available
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function updateNotice()
	{
	    if (Editorial::getOption('disable-admin-notices')) return;
		$this->_showNotice(__('<strong>Editorial theme update is available.</strong> Log in to your account at <a href="http://editorialtemplate.com">editorialtemplate.com</a> to get the update.', 'Editorial'));
	}

	/**
	 * Add notice that an update is available
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	public function invalidNotice()
	{
		$this->_showNotice(__('<strong>You are using an ilegal copy of the Editorial theme</strong>. You can purchase additional licences on <a href="http://editorialtemplate.com/purchase">editorialtemplate.com</a>. Your domain has been logged in our system for investigation.', 'Editorial'));
	}
	
	/**
	 * Show notice
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	private function _showNotice($notice)
	{
		echo "<div class='updated fade'><p>".$notice."</p></div>";
	}

	/**
	 * Display user in administration. Outputs a list item.
	 *
	 * @param  Object $user
	 * @param  string $title
	 * @param  bool   $checked
	 * @return void
	 * @author Miha Hribar
	 */
	public static function displayUser($user, $title = '', $checked = true)
	{
		printf('<li id="user_%1$d">
					<span class="handle">handle</span>
					<input type="checkbox" name="author[]" value="%1$d"%4$s />
					<strong>%2$s</strong>
					<input type="text" name="title[]" value="%3$s" placeholder="Author title" />
				</li>', $user->ID, $user->display_name, $title, $checked ? ' checked="checked"' : '');
	}
	
	/**
	 * Generate a new editorial kit on typekit
	 *
	 * @return bool true on success, false on error
	 * @author Miha Hribar
	 */
	public function typekit()
	{
		// make sure we have token
		if (!Editorial::getOption('typekit-token') || strlen(Editorial::getOption('typekit-token')) < 20)
		{
		    return false;
		}
		// check if kit already exists
		if (Editorial::getOption('typekit-kit'))
		{
		    // fetch info about kit
		    $this->_typekitCheckKit();
		}
	    // create & publish new kit for domain with MinionPro
		else
		{
            $this->_typekitCreateKit();
		}
	}
	
    /**
     * Fetch typekit info
     *
     * @return bool
     * @author Miha Hribar
     */
    private function _typekitCheckKit()
    {
    	list($code, $response) = $this->_typekitAPICall(sprintf('kits/%s/', Editorial::getOption('typekit-kit')));
    	if ($code != 200)
    	{
    	    // remove kit
    	    Editorial::setOption('typekit-kit', false);
    	}
    	else
    	{
    	    $data = json_decode($response, true);
    	    if (is_array($data) && isset($data['kit']) && !isset($data['kit']['published']))
    	    {
    	        // publish kit
    	        $this->_typekitPublish();
    	    }
    	}
    }
	
	/**
	 * Create typekit kit with MinionPro
	 *
	 * @param  string $call API call path
	 * @return void
	 * @author Miha Hribar
	 */
	private function _typekitCreateKit()
	{
	    $params = array(
	       'name' => sprintf('%s (Editorial)', get_bloginfo('name')),
	       'domains' => sprintf('%s, 127.0.0.1', home_url()),
	       'badge' => false,
	       'families' => array(
	           array(
    	           'id' => 'nljb' // Minion Pro
    	           //'id' => 'gkmg' // Droid Sans
	           ),
	       ),
	    );
	    
	    list($code, $response) = $this->_typekitAPICall('kits', $params);
	    $data = json_decode($response, true);
	    if ($code != 200)
	    {
	        $this->_showNotice(sprintf(__('<strong>Error!</strong> Typekit fonts were not enabled. Reason: %s.', 'Editorial'), implode(' ', $data['errors'])));
	        return;
	    }
	    // success?
	    if (is_array($data) && isset($data['kit']) && isset($data['kit']['id']))
	    {
	        // save id
	        Editorial::setOption('typekit-kit', $data['kit']['id']);
	        // publish
	        $this->_typekitPublish();
	    }
	}
	
	/**
	 * Publish kit
	 *
	 * @return void
	 * @author Miha Hribar
	 */
	private function _typekitPublish()
	{
		list($code, $response) = $this->_typekitAPICall(sprintf('kits/%s/publish', Editorial::getOption('typekit-kit')), true);
		$data = json_decode($response, true);
		if ($code != 200)
		{
		    $this->_showNotice(sprintf(__('<strong>Error!</strong> Typekit kit was created but not published. Reason: %s.', 'Editorial'), implode(' ', $data['errors'])));
            return;
		}
		else
		{
		    $this->_showNotice(sprintf(__('<strong>Success!</strong> Typekit font has been created and is being published as we speak. Should take a couple of minutes to see the difference on your website so keep your pants on.')));
		}
	}
	
	/**
	 * Typekit API call
	 *
	 * @param  string $call
	 * @param  Array $params Call params
	 * @return array array of $code and $body
	 * @author Miha Hribar
	 */
	private function _typekitAPICall($call, $params = false)
	{
	    $url = sprintf(
           'https://typekit.com/api/v1/json/%s',
           $call
        );
        
	    // setup curl
		$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(sprintf('X-Typekit-Token:%s', Editorial::getOption('typekit-token'))));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // if we have params create post request
        if ($params)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            if (is_array($params) && count($params))
            {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            }
        }
        // execute request
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($code, $result);
	}
}

// add admin capabilites
$Editorial = new Editorial_Admin();

?>