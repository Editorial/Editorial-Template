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
     * Pages users are allowed to include
     *
     * @var array
     */
    private $_pages = array(
        'look',
        'authors',
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
            'editorial-authors',
            array($this, 'authors')
        );
        add_option('editorial_options', '', '', 'yes');

        // add font notice
        add_action('admin_notices', array($this, 'fontNotice'));
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
        $this->_display('look');
    }

    /**
     * Edit authors
     *
     * @return void
     * @author Miha Hribar
     */
    public function authors()
    {
        $this->_display('authors');
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
        dump($_POST);
    }

    /**
     * Add notice that fonts are not enabled
     *
     * @return void
     * @author Miha Hribar
     */
    public function fontNotice()
    {
        echo "<div class='updated fade'>
            <p><strong>".__('Typekit fonts are currently disabled.')."</strong> "
            .__('Enable them now ... or else.')."</p>
        </div>";
    }
}

// add admin capabilites
$Editorial = new Editorial_Admin();

?>