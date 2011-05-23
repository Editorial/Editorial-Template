<?php

// for debuggin purposes
function dump($object = '')
{
    echo '<pre style="border: 1px solid #ccc; background: #eee; padding: 15px; margin: 15px; font-family: "Courier New", Courier, monospace">'.print_r($object, true).'</pre>';
}

define ('EDITORIAL_VERSION', '1.0b');
define ('EDITORIAL_UPDATE_CHECK', 'http://editorial.local/version.json');
define ('EDITORIAL_OPTIONS', 'editorial_options');

/**
 * Editorial
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Editorial
{
    /**
     * Setup theme
     *
     * @return void
     * @author Miha Hribar
     */
    public static function setup()
    {
        add_action('init', array('Editorial', 'menus'));
    }

    /**
     * Defines menus users can build
     *
     * @return void
     * @author Miha Hribar
     */
    public static function menus()
    {
        // we have main navigation and footer navigation
        register_nav_menu('main-nav', __( 'Main menu' ));
        register_nav_menu('footer-nav', __( 'Footer menu' ));
    }

    /**
     * Get editorial option
     *
     * @param  string $option
     * @return mixed|false
     * @author Miha Hribar
     */
    public static function getOption($option)
    {
        $options = get_option(EDITORIAL_OPTIONS);
        return (is_array($options) && isset($options[$option]))? $options[$option]: false;
    }

    /**
     * Set editorial option
     *
     * @param  string $option
     * @param  mixed  $value
     * @return void
     * @author Miha Hribar
     */
    public static function setOption($option, $value)
    {
        $options = get_option(EDITORIAL_OPTIONS);
        if (!is_array($options))
        {
            $options = array();
        }
        $options[$option] = $value;
        update_option(EDITORIAL_OPTIONS, $options);
    }
}

Editorial::setup();

include 'admin/admin.php';

//add_action('publish_page', 'add_custom_field_automatically');
add_action('publish_post', 'add_custom_field_automatically');
function add_custom_field_automatically($post_ID) {
    global $wpdb;
    if(!wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, 'field-name', 'custom value', true);
    }
}

?>