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
     * Admin page
     */
    const PAGE_PAYMENTS = 'payments';
    const PAGE_PROMO    = 'promo';
    const PAGE_TRIAL    = 'trial';
    const PAGE_DOMAINS  = 'domains';

    /**
     * Pages users are allowed to include
     *
     * @var array
     */
    private $_pages = array(
        self::PAGE_PAYMENTS,
        self::PAGE_PROMO,
        self::PAGE_TRIAL,
        self::PAGE_DOMAINS,
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
        add_submenu_page(
            'marketing',
            'Trial',
            'Trial',
            'administrator',
            'marketing-'.self::PAGE_TRIAL,
            array($this, 'trial')
        );
        add_submenu_page(
            'marketing',
            'Domains',
            'Domains',
            'administrator',
            'marketing-'.self::PAGE_DOMAINS,
            array($this, 'domains')
        );
    }
    
    /**
     * Display page
     *
     * @return void
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
     */
    public function payments()
    {
    	$this->_displayPage(self::PAGE_PAYMENTS);
    }
    
    /**
     * Promo codes
     *
     * @return void
     */
    public function promo()
    {
    	$this->_displayPage(self::PAGE_PROMO);
    }
    
    /**
     * Trial
     *
     * @return void
     */
    public function trial()
    {
    	$this->_displayPage(self::PAGE_TRIAL);
    }
    
    /**
     * Domains
     *
     * @return void
     */
    public function domains()
    {
    	$this->_displayPage(self::PAGE_DOMAINS);
    }
}

// add admin capabilites
$Marketing = new Marketing_Admin();