<?php

/**
 * Editorial Admin class
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Editorial_Admin
{
    /**
     * Look & Feel page
     */
    const PAGE_LOOK = 'look';

    /**
     * Auhthors page
     */
    const PAGE_AUTHORS = 'authors';

    /**
     * Pages users are allowed to include
     *
     * @var array
     */
    private $_pages = array(
        self::PAGE_LOOK,
        self::PAGE_AUTHORS,
    );

    /**
     * Valid options
     *
     * @var array
     */
    private $_options = array(
        'logo-big',
        'logo-small',
        'typekit',
        'black-and-white',
        'disable-admin-notices'
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
        add_action('admin_menu', array($this, 'menus'));
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
            array($this, 'lookAndFeel')
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
            'Authors',
            'Authors',
            'administrator',
            'editorial-'.self::PAGE_AUTHORS,
            array($this, 'authors')
        );
        add_option(EDITORIAL_OPTIONS, '', '', 'yes');

        // add font notice
        if (!Editorial::getOption('typekit'))
        {
            add_action('admin_notices', array($this, 'fontNotice'));
        }
        // check for update
        $this->checkVersion();
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
    public function authors()
    {
        $this->_display(self::PAGE_AUTHORS);
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
        $this->_page = $page;
        if (count($_POST))
        {
            $this->_save();
        }
        // include template settings page
        include 'settings.php';
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
                Editorial::setOption($key, $value);
            }
        }

        // on/off values are special
        switch ($this->_page)
        {
            case self::PAGE_LOOK:
                if (!isset($_POST['typekit']))
                {
                    Editorial::setOption('typekit', false);
                }
                if (!isset($_POST['black-and-white']))
                {
                    Editorial::setOption('black-and-white', false);
                }
                if (!isset($_POST['disable-admin-notices']))
                {
                    Editorial::setOption('disable-admin-notices', false);
                }
                break;
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
        echo "<div class='updated fade'>
            <p><strong>".__('Editorial Typekit fonts are currently disabled.')."</strong> "
            .__('<a href="admin.php?page=editorial">Enable them</a> to get the most out of the Editorial theme.')."</p>
        </div>";
    }

    /**
     * Check if an update is available
     *
     * @return void
     * @author Miha Hribar
     */
    public function checkVersion()
    {
        // notices can be disabled
        if (Editorial::getOption('disable-admin-notices')) return;
        $data = file_get_contents(EDITORIAL_UPDATE_CHECK);
        if ($data !== false)
        {
            $data = json_decode($data, true);
            $version = $data['version'];
            if (EDITORIAL_VERSION != $version)
            {
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
        echo "<div class='updated fade'>
            <p><strong>".__('Editorial theme update is available.')."</strong> "
            .__('Log in to your account at <a href="http://editorialtemplate.com">editorialtemplate.com</a> to get the update.')."</p>
        </div>";
    }
}

// add admin capabilites
$Editorial = new Editorial_Admin();

?>