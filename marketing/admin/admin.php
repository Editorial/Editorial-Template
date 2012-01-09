<?php
/**
 * Admin
 *
 * @category   Editorial
 * @package    Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */
 
/**
 * Editorial Admin class
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, Editorial
 * @link        http://www.editorialtemplate.com
 * @version     1.0
 */
class Marketing_Admin
{
    /**
     * Payments page
     */
    const PAGE_PAYMENTS = 'payments';

    /**
     * Promo code page
     */
    const PAGE_PROMO = 'promo';

    /**
     * Pages users are allowed to include
     *
     * @var array
     */
    private $_pages = array(
        self::PAGE_PAYMENTS,
        self::PAGE_PROMO,
    );

    /**
     * Valid options
     *
     * @var array
     */
    private $_options = array(
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
            'Marketing',
            'Marketing',
            'administrator',
            'marketing',
            array($this, 'payments'),
            get_bloginfo('template_directory').'/assets/favicon.ico'
        );
        add_submenu_page(
            'marketing',
            'Payments',
            'Payments',
            'administrator',
            'marketing',
            array($this, 'payments')
        );
        add_submenu_page(
            'marketing',
            'Promo codes',
            'Promo codes',
            'administrator',
            'marketing-'.self::PAGE_PROMO,
            array($this, 'promo')
        );
    }
    
    /**
     * Display page
     *
     * @return void
     * @author Miha Hribar
     */
    private function _displayPage($page)
    {
    	$this->_page = $page;
    	include 'content.php';
    }
    
    /**
     * Payments
     *
     * @return void
     * @author Miha Hribar
     */
    public function payments()
    {
    	$this->_displayPage(self::PAGE_PAYMENTS);
    }
    
    /**
     * Promo codes
     *
     * @return void
     * @author Miha Hribar
     */
    public function promo()
    {
    	$this->_displayPage(self::PAGE_PROMO);
    }
}

// add admin capabilites
$Marketing = new Marketing_Admin();