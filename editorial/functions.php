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
        // theme has custom menus
        add_action('init', array('Editorial', 'menus'));
        if (function_exists('add_theme_support'))
        {
            // add post formats
            add_theme_support('post-formats');
            // allow post thumnails
            add_theme_support('post-thumbnails');
            // change default Post Thumbnail dimensions
            set_post_thumbnail_size(214, 214, true);
        }
        // add special image sizes
        if (function_exists('add_image_size'))
        {
            add_image_size('landscape', 614, 459); // landscape image
            add_image_size('portrait', 446, 595);  // portrait image
        }
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
        //register_nav_menu('footer-nav', __( 'Footer menu' ));
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

    /**
     * Editorial author links
     *
     * @return void
     * @author Miha Hribar
     */
    public static function authorLink()
    {
        global $authordata;
        $link = sprintf(
            '<a href="%1$s" title="%2$s" class="fn n url">%3$s</a>',
            get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
            esc_attr( sprintf( __( 'Posts by %s' ), get_the_author() ) ),
            get_the_author()
        );
        echo apply_filters('the_author_posts_link', $link);
    }
}

/**
 * Custom Walker_Nav_Menu
 * ----------------------
 * Used to generate a custom navigation used to output the editorial template
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class EditorialNav extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth, $args)
    {
        global $wp_query;

        $output .= '<li'.($item->current ? ' class="selected"' : '').'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
        $item_output .= $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
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