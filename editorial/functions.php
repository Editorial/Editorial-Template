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
            add_image_size('landscape', 614, 459);    // landscape image
            add_image_size('portrait', 446, 595);     // portrait image
            add_image_size('media-thumb', 116, 115, true);  // media thumb
        }
        // spam prevention
        add_action('check_comment_flood', array('Editorial', 'checkReferrer'));
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
     * Simple spam prevention
     *
     * @return void
     * @author Miha Hribar
     * @see http://www.smashingmagazine.com/2009/07/23/10-wordpress-comments-hacks/
     */
    public static function checkReferrer()
    {
        if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == "")
        {
            wp_die( __('Please enable referrers in your browser, or, if you\'re a spammer, bugger off!') );
        }
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

    /**
     * Post footer
     *
     * @return void
     * @author Miha Hribar
     */
    public static function postFooter()
    {
        ?>
        <footer>
            <?php the_category(', '); ?>
            <time class="published" pubdate datetime="<?php the_date('Y-m-dTH:i'); ?>">
                <span class="value-title" title="<?php the_date('Y-m-dTH:i'); ?>"> </span>
                <?php the_time(get_option('date_format')); ?>
            </time>
            <em class="v-hidden author vcard"><?php _e('Written by.', 'Editorial'); ?> <?php Editorial::authorLink(); ?></em>
        </footer>
        <?php
    }

    /**
     * Post header
     *
     * @param  bool $h1 set to true if h1 is to be used
     * @return void
     * @author Miha Hribar
     */
    public static function postHeader($h1 = true)
    {
        $heading = $h1 ? 'h1' : 'h2'
        ?>
        <header>
            <<?php echo $heading; ?> class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </<?php echo $heading; ?>>
        </header>
        <?php
    }

    /**
     * Post excerpt
     *
     * @return void
     * @author Miha Hribar
     */
    public static function postExcerpt()
    {
        ?>
        <p class="entry-summary"><?php echo get_the_excerpt(); ?></p>
        <?php
    }

    /**
     * Post figure
     *
     * @param  int $thumbId post thumbnail id
     * @param  mixed $args thumbnail args (e.g 'landscape'|'portrait' or array(214,214))
     * @return void
     * @author Miha Hribar
     */
    public static function postFigure($thumbId, $args)
    {
        $imageData = wp_get_attachment_image_src($thumbId, $args);
        ?>
        <figure>
            <a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo $imageData[0]; ?>" alt="<?php the_title(); ?>"></a>
        </figure>
        <?php
    }

    /**
     * Tab navigation
     *
     * @return void
     * @author Miha Hribar
     */
    public static function tabNavigation($postId, $selected = 'article')
    {
        $thumbId = get_post_thumbnail_id($postId);
        $commentCount = get_comments_number($postId);
        ?>
        <nav id="tabs" role="navigation">
            <ul>
                <li<?php echo $selected == 'article' ?  ' class="selected"' : '' ?>><a href="<?php echo get_permalink($postId); ?>"><?php _e('Article', 'Editorial'); ?></a></li>
                <li<?php echo $selected == 'gallery' ?  ' class="selected"' : '' ?>><a href="<?php echo get_attachment_link($thumbId); ?>"><?php _e('Image gallery', 'Editorial'); ?></a></li>
                <li<?php echo $selected == 'comments' ? ' class="selected"' : '' ?>><a href="<?php echo get_comments_link($postId); ?>"><?php _e('Feedback', 'Editorial'); ?> <?php echo $commentCount ? '<em>'.$commentCount.'</em>' : ''; ?></a></li>
            </ul>
        </nav>
        <?php
    }

    /**
     * Is a mime type o a specific type
     *
     * @return bool
     * @author Miha Hribar
     */
    public static function is_a($mime, $type)
    {
        return strstr($mime, $type) !== false;
    }

    /**
     * Is mime type a movie?
     *
     * @return bool
     * @author Miha Hribar
     */
    public static function is_video($mime)
    {
        return self::is_a($mime, 'video');
    }

    /**
     * Is mime type an audio?
     *
     * @return bool
     * @author Miha Hribar
     */
    public static function is_audio($mime)
    {
        return self::is_a($mime, 'audio');
    }

    /**
     * Is mime type an image?
     *
     * @return bool
     * @author Miha Hribar
     */
    public static function is_image($mime)
    {
        return self::is_a($mime, 'image');
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