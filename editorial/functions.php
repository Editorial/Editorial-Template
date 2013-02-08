<?php

// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

// for debuggin purposes
function dump($object = '')
{
    echo '<pre style="border: 1px solid #ccc; background: #eee; padding: 15px; margin: 15px; font-family: "Courier New", Courier, monospace">'.print_r($object, true).'</pre>';
}

// also for debugging purposes
function error($message)
{
    error_log(sprintf('[Editorial] %s', $message));
}

function debug($message)
{
    error($message);
}

define ('EDITORIAL_VERSION', '2.0');
define ('EDITORIAL_UPDATE_API', 'http://editorialtemplate.com/new-moon/');
//define ('EDITORIAL_UPDATE_API', 'http://localhost:8888/editorial-marketing/new-moon/');
define ('EDITORIAL_OPTIONS', 'editorial_options');
// social networks
define ('EDITORIAL_FACEBOOK',    'facebook-share');
define ('EDITORIAL_TWITTER',     'twitter-share');
define ('EDITORIAL_GOOGLE',      'google-share');
define ('EDITORIAL_READABILITY', 'readability-share');
// number of footer widgets
define ('EDITORIAL_WIDGET', 'footer-widgets');

// Pre-2.6 compatibility
if (!defined('WP_CONTENT_URL')) define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if (!defined('WP_CONTENT_DIR')) define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_CACHE_DIR')) define('WP_CACHE_DIR', WP_CONTENT_DIR . '/uploads/cache');
if (!defined('WP_CACHE_URL')) define('WP_CACHE_URL', WP_CONTENT_URL . '/uploads/cache');

/**
 * Editorial
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, Editorial
 * @link        http://www.editorialtemplate.com
 * @version     1.0
 */
class Editorial
{
    /**
     * Widget counter
     *
     * @var int
     * @static
     */
    public static $widgetCounter = 0;
    
    /**
     * Number of active widgets
     *
     * @var int
     */
    public static $widgetCount = 0;
    
    /**
     * Comment counter
     *
     * @var int
     */
    public static $commentCounter = 0;


    /**
    * default translations
    * 
    */

    public static $translations = array(
        //search
        'search' => array(
            'search' => 'Search',
            'Query' => 'Query',
            'articles displayed' => 'articles displayed',
        ),
        //categories
        'categories' => array(
            'Select layout option' => 'Select layout option',
            'List' => 'List',
            'Grid' => 'Grid',
            'Display older articles ...' => 'Display older articles ...',
        ),
        //single article
        'single_article' => array(
            'Article' => 'Article',
            'Gallery' => 'Gallery',
            'Feedback' => 'Feedback',
            'by;' => 'by:',
            'You might also enjoy' => 'You might also enjoy',
        ),
        //footer
        'footer' => array(
            'Subscribe' => 'Subscribe',
            'Masthead' => 'Masthead',
            'All categories' => 'All categories',
        ),
        //gallery
        'gallery' => array(
            'Previous' => 'Previous',
            'Slideshow' => 'Slideshow',
            'Next' => 'Next',
            'Back to article' => 'Back to article',
            'Embed code' => 'Embed code',
            "There is no need for downloading and uploading it to your blog/website when you can easily embed it." => "There's no need for downloading and uploading it to your blog/website when you can easily embed it.",
            ),
        //comments
        'comments' => array(
            'comments displayed' => 'comments displayed',
            'Comment' => 'Comment',
            'Author' => 'Author',
            'Your name' => 'Your name',
            "Your e-mail address" => "Your e-mail address",
            'Display older comments ...' => 'Display older comments ...',
            "<strong>There are no comments yet.</strong> Be first to leave your footprint here ..." => "<strong>There are no comments yet.</strong> Be first to leave your footprint here ...",
            "Link" => "Link",
            "Captcha" => "Captcha",
            "Publish" => "Publish",
        ),
        //page
        'single_page' => array (
            'Written by:' => 'Written by:',
        ),

        );

    public static function getTranslations() {
        return self::$translations;
   }
    

    public static $TwitterApiCallCounter = 0; //limit 150 per hour
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
            add_image_size('landscape', 614, 9999);         // landscape image
            add_image_size('portrait', 446, 9999);          // portrait image
            add_image_size('media-thumb', 116, 116, true);  // media thumb
        }
        // spam prevention
        add_action('check_comment_flood', array('Editorial', 'checkReferrer'));
        // add comment redirect filter
        add_filter('comment_post_redirect', array('Editorial', 'commentRedirect'));
        // settings after theme setup
        add_action('admin_init', array('Editorial','adminInit'));
        // prevent publishing of a post without a thumbnail
        add_action('publish_post', array('Editorial', 'checkForThumbnail'), 1);

        if (function_exists('register_sidebar'))
        {
            // widget ready sidebar
            register_sidebar(array(
                'name'          => __('Footer widget area', 'editorial'),
                'id'            => 'footer-widgets',
                'description'   => __('Footer widget area', 'editorial'),
                //'before_widget' => '<section class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h4>',
                'after_title'   => '</h4>',
            ));
        }

        // add excerpt to pages @todo move into admin?
        add_post_type_support('page', 'excerpt');

        // unhide post excerpt by default
        add_filter('default_hidden_meta_boxes', array('Editorial', 'unhideExcerpt'), 10, 2);
        
        // widget customizations
        add_filter('dynamic_sidebar_params', array('Editorial', 'widgets'));
        
        // theme options
        add_option(EDITORIAL_OPTIONS, '', '', 'yes');
        
        // add default options
        $assets = WP_CONTENT_URL.'/themes/editorial/';
        $assets = substr($assets, strlen(get_bloginfo('url')));
        if (!Editorial::getOption('logo-big')) Editorial::setOption('logo-big', get_bloginfo('url').$assets.'images/editorial-logo.png');
        if (!Editorial::getOption('logo-small')) Editorial::setOption('logo-small', get_bloginfo('url').$assets.'images/editorial-logo-small.png');
        if (!Editorial::getOption('logo-gallery')) Editorial::setOption('logo-gallery', get_bloginfo('url').$assets.'images/editorial-logo-white2.png');
        if (!Editorial::getOption('touch-icon')) Editorial::setOption('touch-icon', get_bloginfo('url').$assets.'images/touch/apple-touch-icon.png');
        if (!Editorial::getOption('favicon')) Editorial::setOption('favicon', get_bloginfo('url').$assets.'favicon.ico');
        
        // number of active widgets?
        $widgets = wp_get_sidebars_widgets();
        self::$widgetCount = isset($widgets[EDITORIAL_WIDGET]) && is_array($widgets[EDITORIAL_WIDGET]) ? count($widgets[EDITORIAL_WIDGET]) : 0;
        
        add_filter('attachment_fields_to_edit', array('Editorial','hide_some_attachment_fields'), 11, 2 );
        add_filter('media_upload_tabs', array('Editorial','remove_media_library_tab'));
        add_filter('admin_head_media_upload_gallery_form', array('Editorial','hide_galery_settings_div'));
        add_filter('type_url_form_media', array('Editorial','hide_type_url_fields'));
        add_action('admin_init', array('Editorial','set_user_metaboxes'));
        add_action('after_setup_theme', array('Editorial','theme_setup'));

		// filter content (clenaup images, iframes etc.)
		add_filter('the_content', array('Editorial', 'filterContentCleanup'));
    }
    
    /**
     * Content cleanup filter
     *
     * @param string $content
     * @return void
     */
    public static function filterContentCleanup($content)
    {
        // detect images and remove class and width&height params
        preg_match_all('/(<img[^>]+>)/i', $content, $matches, PREG_SET_ORDER);
        $toReplace = array();
        foreach ($matches as $img)
        {
            // match all the things we don't want and remove them
            $patterns = array(
                '/class="[^"]*"/',
                "/class='[^']*'/",
                '/width="[^"]*"/',
                "/width='[^']*'/",
                "/width=\d*/",
                '/height="[^"]*"/',
                "/height='[^']*'/",
                "/height=\d*/",
            );
            $replacements = array('', '', '', '', '', '', '', '');
            $replaced = preg_replace($patterns, $replacements, $img[0]);
            // save what we just replaced for later
            if ($replaced != $img[0])
            {
                $toReplace[$img[0]] = $replaced;
            }
        }
        
        // remove all in one go
        if (count($toReplace))
        {
            $content = str_replace(array_keys($toReplace), array_values($toReplace), $content);
        }
        
        return $content;
    }

    public static function get_page_by_post_name($name) {
      $pages = get_pages();
      foreach ($pages as $page) {
        if ($page->post_name == $name)
          return($page);
      }
      return(false);
    }

    public static function create_colophon_page() {
      if (!Editorial::get_page_by_post_name('colophon')) {
        $page["post_type"] = 'page';
        $page["post_name"] = 'colophon';
        $page["post_title"] = 'Masthead';
        $page["post_content"] = '<h2>About</h2><p>Our colophon page</p>';
        $page["post_status"] = 'publish';
        $page["comment_status"] = 'closed';
        $page_id = wp_insert_post($page);

        update_post_meta( $page_id, '_wp_page_template', 'colophon.php' );
      }

    }

    public static function theme_setup() {
      if (false == get_option('theme_was_installed')) {
        Editorial::create_colophon_page();
        Editorial::setOption('colophon-enabled', true);
        add_action('admin_notices', array('Editorial', 'show_welcome_notice'));
        update_option('theme_was_installed', '1');
        return;
      }
    }

    public static function show_welcome_notice() {
        echo "<div class='updated fade'><p>Editorial Theme was installed successfully. Please take a moment to <a href='".admin_url('admin.php?page=editorial')."'>configure it through the admin pages</a>.</p></div>";
    }

    //wp-admin/admin.php?page=editorial


    //hide/unhide some default boxes on post page in admin
    public function set_user_metaboxes($user_id=NULL) {

    // These are the metakeys we will need to update
    $meta_key['hidden'] = 'metaboxhidden_post';

    // So this can be used without hooking into user_register
    if ( ! $user_id)
        $user_id = get_current_user_id(); 


    // Set the default hiddens if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['hidden'], true) ) {
      $meta_value = array('slugdiv', 'trackbacksdiv', 'postcustom', 'commentsdiv','authordiv', 'revisionsdiv', 'tagsdiv-post_tag', 'formatdiv');
        update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
    }
    }


    public function hide_type_url_fields($html){
        if ( !apply_filters( 'disable_captions', '' ) ) {
            $caption = '
            <tr>
                <th valign="top" scope="row" class="label">
                    <span class="alignleft"><label for="caption">' . __('Description') . '</label></span>
                </th>
                <td class="field"><input id="caption" name="caption" value="" type="text" /></td>
            </tr>
    ';
        } else {
            $caption = '';
        }
        return '
        <p class="media-types"><label><input type="radio" name="media_type" value="image" id="image-only"' . checked( 'image-only', $view, false ) . ' /> ' . __( 'Image' ) . '</label> &nbsp; &nbsp; <label><input type="radio" name="media_type" value="generic" id="not-image"' . checked( 'not-image', $view, false ) . ' /> ' . __( 'Audio, Video, or Other File' ) . '</label></p>
        <table class="describe ' . $table_class . '"><tbody>
            <tr>
                <th valign="top" scope="row" class="label" style="width:130px;">
                    <span class="alignleft"><label for="src">' . __('URL') . '</label></span>
                    <span class="alignright"><abbr id="status_img" title="required" class="required">*</abbr></span>
                </th>
                <td class="field"><input id="src" name="src" value="" type="text" aria-required="true" onblur="addExtImage.getImageData()" /></td>
            </tr>

            <tr>
                <th valign="top" scope="row" class="label">
                    <span class="alignleft"><label for="title">' . __('Title') . '</label></span>
                    <span class="alignright"><abbr title="required" class="required">*</abbr></span>
                </th>
                <td class="field"><input id="title" name="title" value="" type="text" aria-required="true" /></td>
            </tr>
            ' . $caption . '
            <tr class="image-only">
                <td></td>
                <td>
                    <input type="button" class="button" id="go_button" style="color:#bbb;" onclick="addExtImage.insert()" value="' . esc_attr__('Use as featured image') . '" />
                </td>
            </tr>
            <tr class="not-image">
                <td></td>
                <td>
                    ' . get_submit_button( __( 'Use as featured' ), 'button', 'insertonlybutton', false ) . '
                </td>
            </tr>
        </tbody></table>
        ';
    }
    public function remove_media_library_tab($tabs) {
        unset($tabs['type_url']);
            // print_r($tabs);
        return $tabs;
    }
    
    public function hide_galery_settings_div(){
        print <<<EOF
                <style type="text/css">
                    #gallery-settings *{
                        display:none;
                        }
                </style>
EOF;
    }
    
    public function hide_some_attachment_fields($form_fields, $post) {
            
        // remove unnecessary fields
    unset( $form_fields['image-size'] );
    unset( $form_fields['post_excerpt'] );
    unset( $form_fields['url'] );
    unset( $form_fields['image_url'] );
    unset( $form_fields['align'] );
        unset( $form_fields['image_alt'] );

        $delete = '';
        $thumbnail = '';
        $filename = basename( $post->guid );
        $attachment_id = $post->ID;
        //delete button
    if ( current_user_can( 'delete_post', $attachment_id ) ) {
        if ( !EMPTY_TRASH_DAYS ) {
            $delete = "<a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Delete Permanently' ) . '</a>';
        } elseif ( !MEDIA_TRASH ) {
            $delete = "<a href='#' class='del-link' onclick=\"document.getElementById('del_attachment_$attachment_id').style.display='block';return false;\">" . __( 'Delete' ) . "</a>
                     <div id='del_attachment_$attachment_id' class='del-attachment' style='display:none;'>" . sprintf( __( 'You are about to delete <strong>%s</strong>.' ), $filename ) . "
                     <a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='button'>" . __( 'Continue' ) . "</a>
                     <a href='#' class='button' onclick=\"this.parentNode.style.display='none';return false;\">" . __( 'Cancel' ) . "</a>
                     </div>";
        } else {
            $delete = "<a href='" . wp_nonce_url( "post.php?action=trash&amp;post=$attachment_id", 'trash-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Move to Trash' ) . "</a><a href='" . wp_nonce_url( "post.php?action=untrash&amp;post=$attachment_id", 'untrash-attachment_' . $attachment_id ) . "' id='undo[$attachment_id]' class='undo hidden'>" . __( 'Undo' ) . "</a>";
        }
    }
    else {
        $delete = '';
    }

        $calling_post_id = 0;
        if ( isset( $_GET['post_id'] ) )
            $calling_post_id = absint( $_GET['post_id'] );
        elseif ( isset( $_POST ) && count( $_POST ) ) // Like for async-upload where $_GET['post_id'] isn't set
            $calling_post_id = $post->post_parent;
        if ( $calling_post_id && current_theme_supports( 'post-thumbnails', get_post_type( $calling_post_id ) )
            && post_type_supports( get_post_type( $calling_post_id ), 'thumbnail' ) && get_post_thumbnail_id( $calling_post_id ) != $attachment_id ) {
            $ajax_nonce = wp_create_nonce( "set_post_thumbnail-$calling_post_id" );
            $thumbnail = "<a class='wp-post-thumbnail' id='wp-post-thumbnail-" . $attachment_id . "' href='#' onclick='WPSetAsThumbnail(\"$attachment_id\", \"$ajax_nonce\");return false;'>" . esc_html__( "Use as featured image" ) . "</a>";
        }

        $form_fields['buttons'] = array( 'tr' => "<tr class='submit'><td></td><td class='savesend'>$thumbnail $delete</td></tr>\n" );
//	}
        return $form_fields;
    }
    
    /**
     * Widget customization
     *
     * @return void
     * @author Miha Hribar
     */
    public function widgets($params)
    {
        // html to insert before widget
        $before = sprintf('<section class="widget w%d">', self::$widgetCounter % 2 + 1);
        // group
        if (self::$widgetCounter % 2 == 0)
        {
            $before = '<div class="group">'.$before; 
        }
        // widget row
        if (self::$widgetCounter % 4 == 0)
        {
            $remaining = self::$widgetCount - self::$widgetCounter;
            $remaining = $remaining/4 >= 1 ? 4 : $remaining % 4; 
            $before = sprintf('<div class="adapt widgets-%d">', $remaining).$before;
        }
        $params[0]['before_widget'] = $before;
        
        // html to insert after widget
        $after = '</section>';
        // group end
        if (self::$widgetCounter % 2 == 1 || self::$widgetCounter+1 == self::$widgetCount)
        {
            $after .= '<!-- end group --></div>'; 
        }
        // widget row end
        if (self::$widgetCounter % 4 == 3 || self::$widgetCounter+1 == self::$widgetCount)
        {
            $after .= '<!-- end row '.self::$widgetCounter.' --></div>';
        }
        $params[0]['after_widget'] = $after;
        
        self::$widgetCounter++;
        return $params;
    }

    /**
     * Unhide excerpt from posts by default (hidden in wp 3.1)
     *
     * @return array
     * @author Miha Hribar
     */
    public static function unhideExcerpt($hidden, $screen)
    {
        if ('post' == $screen->base || 'page' == $screen->base)
        {
            $hidden = array(
                'slugdiv',
                'trackbacksdiv',
                'postexcerpt',
                'commentstatusdiv',
                'commentsdiv',
                'authordiv',
                'revisionsdiv',
				'postcustom',
            );
        }
        // removed 'postcustom',
        return $hidden;
    }

    /**
     * Check for thubnail in the just published post
     *
     * @param  int $postID
     * @return void
     * @author Miha Hribar
     */
    public static function checkForThumbnail($postID)
    {
        if(!has_post_thumbnail($postID))
        {
            // find attachments for post, if there are any, set as featured image
            $attachments = get_children(array('post_parent' => $postID));
            if (count($attachments))
            {
                set_post_thumbnail($postID, current($attachments)->ID);
            }
        }
    }

    /**
     * Runs after theme setup so we can setup default values etc.
     *
     * @return void
     * @author Miha Hribar
     */
    public static function adminInit()
    {
        if ((get_current_theme() != 'Editorial') && (get_current_theme() != 'Editorial Custom') )
        {
            // not using editorial theme
            return;
        }
        
        // couldn't get anything else to run on theme start
        if (self::getOption('editorial-install') === false)
        {
            // setup default values
            // karma
            if (self::getOption('karma') === false)
            {
                // enable karma by default
                self::setOption('karma', true);
            }
            
            if (self::getOption('karma-treshold') === false)
            {
                // hide comments after 5 downvotes
                self::setOption('karma-treshold', 5);
            }

            // default translations
            if (self::getOption('translations') === false)
            {
                //add default translations
                self::setOption('translations', self::$translations);
            }

            // enable black and white images by default
            if (self::getOption('black-and-white') === false)
            {
                //add default translations
                self::setOption('black-and-white', true);
            }

            // set that editorial was installed
            self::setOption('editorial-install', true);
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
        register_nav_menus(array(
            'main-nav'   => __( 'Main menu' ),
            'footer-nav' => __( 'Footer menu' )
        ));

        if(function_exists("add_post_type_support")) //support 3.1 and greater
        {
            add_post_type_support('page', 'excerpt');
        }

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
        global $post;
 ?>
                <footer>
                    <?php
                    
                    $list = array();
                    foreach((get_the_category()) as $category) {
                        $list[] = sprintf(
                            '<a href="%s" title="View all posts in %s" rel="tag">%s</a>',
                            get_category_link($category->cat_ID),
                            $category->cat_name,
                            $category->cat_name
                        );
                    }
                    
                    echo implode(', ', $list);
                    
                    ?>

                    <time class="published" datetime="<?php echo date('Y-m-d\TH:i:s-01:00', strtotime($post->post_date)); ?>">
                        <span class="value-title" title="<?php echo date('Y-m-d\TH:i:s-01:00', strtotime($post->post_date)); ?>"> </span>
                        <?php //the_time(get_option('date_format'));
                                    the_time('M j, Y');
?>

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
                <<?php echo $heading; ?> class="entry-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                </<?php echo $heading; ?>>
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
        $excerpt = get_the_excerpt();
        if (mb_strlen($excerpt) > 240)
        {
            $excerpt = mb_substr($excerpt, 0, 240).' [...]';
        }
?>
<p class="entry-summary"><?php echo $excerpt; ?></p>
<?php
    }

    /**
     * Post figure
     *
     * @param  int $thumbId post thumbnail id
     * @param  mixed $args thumbnail args (e.g 'landscape'|'portrait' or array(214,214))
     * @param  bool $featured set to true to include larger image
     * @return void
     * @author Miha Hribar
     */
    public static function postFigure($thumbId, $args, $featured = false)
    {
        $url = Editorial::getBlankImage($featured);
        $imageData = wp_get_attachment_image_src($thumbId, $args);
        if (count($imageData) > 1)
        {
            $url = $imageData[0];
            // black and white images?
            if (Editorial::getOption('black-and-white'))
            {
                $url = Editorial::getBWImage($thumbId, $args, $featured);
                //$url = get_bloginfo('template_directory').'/bw-photo.php?photo='.$thumbId.'&amp;type='.$args[0];
            }
        }

?>
            <figure>
                <a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo $url; ?>" alt="<?php the_title(); ?>"></a>
            </figure>
<?php
    }

    /**
     * Comments link - comments are situated on a separate page.
     *
     * @param  int $postId
     * @return string
     * @author Miha Hribar
     */
    public static function commentsLink($postId)
    {
        $link = get_permalink($postId);
        if (strpos($link, '?') === false)
        {
            $link .= '?comments';
        }
        else
        {
            $link .= '&comments';
        }
        return $link;
    }

    /**
     * Comment
     *
     * @return void
     * @author Miha Hribar
     */
    public static function comment($comment, $args, $depth, $return = false)
    {
        $trackback = $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback';
        if ($return) ob_start();
        $complementary = '';
        if (self::getOption('karma'))
        {

            $complementary = sprintf('<aside role="complementary">
                        <form class="favorize" method="post" action="%2$s">
                            <fieldset%4$s>
                                <input type="radio" id="vote-for-%1$d" name="vote-%1$d" value="1"%3$s>
                                <label class="vote-for" for="vote-for-%1$d"><em>+1</em></label>
                                <input type="radio" id="vote-against-%1$d" name="vote-%1$d" value="-1"%3$s>
                                <label class="vote-against" for="vote-against-%1$d"><em>-1</em></label>
                            </fieldset>
                            <fieldset>
                                <input type="hidden" name="comment_id" value="%1$d">
                                <input type="submit" name="submit-%1$d" value="Go">
                                <strong id="score-%1$d" class="score%6$s">%5$s</strong>
                            </fieldset>
                        </form>
                    </aside>',
                $comment->comment_ID,
                get_bloginfo('template_url').'/comment-vote.php',
                Editorial::alreadyVoted($comment->comment_ID) ? ' disabled' : '',
                Editorial::alreadyVoted($comment->comment_ID) ? ' class="disabled"' : '',
                (int)$comment->comment_karma == 0
                    ? '0'
                    : ($comment->comment_karma < 0 ? $comment->comment_karma : '+'.$comment->comment_karma),
                $comment->comment_karma < 0 ? ' negative' : ''
            );
        }
        printf('<article class="hentry" id="comment-%1$d">
                <section>
                    <footer>
                        <cite class="author vcard">
                            %2$s
                        </cite>
                        <time class="published" datetime="%3$s">
                            <span class="value-title" title="%3$s"> </span>
                            %4$s
                        </time>
                    </footer>
                    %11$s
                </section>
                <h2 class="entry-title"><span class="v-hidden">%5$s</span> %6$d.</h2>
                <blockquote class="%14$s%9$sentry-content">
                    %10$s
                    <p>%7$s</p>
                </blockquote>
                %15$s
            </article>',
            $comment->comment_ID,
            $comment->comment_author_url ?
                sprintf(
                    '<a href="%s" rel="nofollow" class="fn n url" target="_blank">%s</a>',
                    $comment->comment_author_url,
                    $comment->comment_author
                ) : $comment->comment_author,
            date('Y-m-dTH:i', strtotime($comment->comment_date)),
            date(get_option('date_format'), strtotime($comment->comment_date)),
            __('Feedback no.', 'Editorial'),
            Editorial::$commentCounter,
            $comment->comment_content,
            get_bloginfo('url').'/comment-vote.php',
            $trackback ? 'trackback ' : '',
            $trackback ? sprintf(
                '<h4><a href="%s" rel="nofollow" target="_blank">%s</a></h4>',
                $comment->comment_author_url,
                $comment->comment_author) : '',
            $complementary,
            Editorial::alreadyVoted($comment->comment_ID) ? ' class="disabled"' : '',
            Editorial::alreadyVoted($comment->comment_ID) ? ' disabled' : '',
            Editorial::getOption('karma')
                ? ($comment->comment_karma <= -Editorial::getOption('karma-treshold') ? 'bad-comment ' : '')
                : '',
            Editorial::getOption('karma')
                ? ($comment->comment_karma <= -Editorial::getOption('karma-treshold') ? '<p class="show"><a href="#comment-'.$comment->comment_ID.'"><span>'.__('Show hidden', 'Editorial').'</span> '.__(' comment ...', 'Editorial').'</a></p>' : '')
                : ''
        );
        
        self::$commentCounter += $args['reverse_top_level'] ? -1 : 1;

        if ($return)
        {
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    /**
     * Post comment notice
     *
     * @return string
     * @author Miha Hribar
     */
    public static function commentNotice()
    {
        return __('<strong>Got something to add?</strong> You can just <a href="#comments-form"><em>leave a comment</em></a>.', 'Editorial');
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
        $attachmentCount = count(get_children(array('post_parent' => $postId)));
        $translations = self::getOption('translations');
?>
    <nav id="tabs" role="navigation">
        <ul <?php echo comments_open() ? "" : "class='no-feedback'" ?>>
            <li<?php echo $selected == 'article' ?  ' class="selected"' : '' ?>>
                <a href="<?php echo get_permalink($postId); ?>"><?php echo $translations['single_article']['Article']; ?></a>
            </li>
            <li<?php echo $selected == 'gallery' ?  ' class="selected"' : '' ?>>
                <a href="<?php echo get_attachment_link($thumbId); ?>"><?php echo $translations['single_article']['Gallery'];  echo $attachmentCount ? ' <em>'.$attachmentCount.'</em>' : ''; ?></a>
            </li>
            <?php if (comments_open()) { ?>
            <li<?php echo $selected == 'comments' ? ' class="selected"' : '' ?>>
                <a href="<?php echo self::commentsLink($postId); ?>"><?php echo $translations['single_article']['Feedback'];  echo $commentCount ? ' <em>'.$commentCount.'</em>' : ''; ?></a>
            </li>
            <?php } ?>
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

    /**
     * Comment redirect filter
     *
     * @param  string $location
     * @return string
     * @author Miha Hribar
     */
    public static function commentRedirect($location)
    {
        // @todo http://editorial.local/2011/05/caron-butler/#comment-6
        return $location;
    }

    /**
     * Show form errors
     *
     * @param  array $errors
     * @return string
     * @author Miha Hribar
     */
    public static function formErrors(Array $errors)
    {
        // show form errors
        $return = '<section id="errors" class="message">
        <h3><span class="v-hidden">Warning</span>!</h3>
        <p class="lead">Please correct following problems:</p>
        <ol>';
        foreach ($errors as $error)
        {
            $return .= sprintf('<li>%s</li>', $error);
        }
        $return .= '</ol>
        </section>';
        return $return;
    }

    /**
     * Show form notice
     *
     * @param  bool $ok
     * @return string
     * @author Miha Hribar
     */
    public static function formNotice($ok)
    {
        $return = sprintf('<section id="success" class="message">
                <h3>%s</h3>
                <p class="lead">%s</p>
                <p>%s</p>
            </section>',
            __('OK', 'Editorial'),
            $ok ? __('Your comment has been successfully published.', 'Editorial')
                : __('Your comment has been saved and is waiting for confirmation.', 'Editorial'),
            __('Thanks!', 'Editorial')
        );

        return $return;
    }

    /**
     * Make new riddle as captcha
     *
     * @return array
     * @author Miha Hribar
     */
    public static function riddle()
    {
        // catpcha translations
        $translations = array(
            __('first',  'Editorial'),
            __('second', 'Editorial'),
            __('third',  'Editorial'),
            __('forth',  'Editorial'),
            __('fifth',  'Editorial'),
            __('sixth',  'Editorial'),
        );
        // captcha settings
        $captcha = strtoupper(substr(md5(microtime()),0,6));
        // select two random characters
        $all = array(0,1,2,3,4,5);
        $selected = array_rand($all, 2);
        $_SESSION['riddle'] = array(
            'captcha'  => $captcha,
            'chars'    => array(
                $selected[0] => $captcha[$selected[0]],
                $selected[1] => $captcha[$selected[1]]
            ),
        );
        return array(
            'notice' => sprintf(__('Please enter the <strong>%s</strong> and <strong>%s</strong> character', 'Editorial'), $translations[$selected[0]], $translations[$selected[1]]),
            'riddle' => $captcha
        );
    }

    /**
     * Is provided user agent a mobile browser? If user agent is not given the
     * servers user agent is used - if set.
     *
     * @return boolean
     * @param  string $useragent
     * @author Miha Hribar
     * @see    http://detectmobilebrowser.com/
     * @static
     */
    public static function isMobileDevice($useragent = null)
    {
        //if we are debugging
        if(isset($_GET['debugmobile']))
        {
            return true;
        }
        if (!$useragent)
        {
            // take from server
            $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        return preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)
            ||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
    }
    
    public static function isIpad( $useragent = null ){
        if (!$useragent)
        {
            // take from server
            $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        return preg_match('/ipad/i',$useragent);
    }

    /**
     * Determines (based on cookie set with JS) if client has a high DPI display (retina)
     *
     * @return boolean
     * @author Miha Hribar
     */
    public static function isRetina ()
    {
        return isset($_GET['debugretina']) || isset( $_COOKIE['retina']);
    }

    /**
     * Allow post only to certain page
     *
     * @return void
     * @author Miha Hribar
     * @see comment-vote.php
     * @see comment-post.php
     */
    public function postOnly()
    {
        // allow only post
        if (!array_key_exists('REQUEST_METHOD', $_SERVER) || 'POST' != $_SERVER['REQUEST_METHOD']) {
            header('Allow: POST');
            header('HTTP/1.1 405 Method Not Allowed');
            header('Content-Type: text/plain');
            exit;
        }
    }

    /**
     * Is a particular social network share enabled
     *
     * @param  string $network set any if you don't care which
     * @return bool
     * @author Miha Hribar
     */
    public static function isShareEnabled($network = 'any')
    {
        $isEnabled = false;
        switch ($network)
        {
            case 'any':
                $isEnabled = self::isShareEnabled(EDITORIAL_TWITTER)
                            || self::isShareEnabled(EDITORIAL_FACEBOOK)
                            || self::isShareEnabled(EDITORIAL_GOOGLE)
                            || self::isShareEnabled(EDITORIAL_READABILITY);
                break;

            case EDITORIAL_TWITTER:
                // for twitter to work we need more info
                $isEnabled = self::getOption('twitter-share') && self::getOption('twitter-account');
                break;

            default:
                $isEnabled = self::getOption($network) != false;
        }

        return $isEnabled;
    }

    /**
     * Get share HTML for a particular network
     *
     * @param  string $network
     * @param  array  $params additional params (optional)
     * @return string
     * @author Miha Hribar
     */
    public static function shareHTML($network, $params = array())
    {
        $html = '';
        $status = get_bloginfo('name');
        switch ($network)
        {
            case EDITORIAL_TWITTER:
                $status .=":%20".esc_attr($params['text'])."%20".urlencode($params['url'])."%20via%20".self::getOption('twitter-account')."";
                $html = sprintf(
                            '<a href="http://twitter.com/home?status=%s" class="resizing">Share this article: Tweet</a>',
                            $status
                            );
                break;

            case EDITORIAL_GOOGLE:
                //$status .=":%20".esc_attr($params['text'])."%20".urlencode($params['url']);
                $html = sprintf(
                        '<a href="https://plus.google.com/share?url=%s" class="resizing">Share this article: Google+</a>',
                        urlencode($params['url'])
                        );
            break;
        
            case EDITORIAL_FACEBOOK:
                //$status = esc_attr($params['text'])." - ".urlencode($params['url']);
                $html = sprintf(
                        '<a href="http://www.facebook.com/sharer.php?u=%s&t=%s" class="resizing">Share this article: Like</a>',
                        urlencode($params['url']),
                        esc_attr($params['text'])
                );
            break;
            
        }

        return $html;
    }

    /**
     * Black & white images enabled?
     *
     * @return bool
     * @author Miha Hribar
     */
    public static function blackAndWhiteImages()
    {
        return self::getOption('black-and-white') != false;
    }

    /**
     * Already karma voted for comment?
     *
     * @param  int $commentId
     * @return bool
     * @author Miha Hribar
     */
    public static function alreadyVoted($commentId)
    {
        if (isset($_COOKIE['vote']))
        {
            // explode value
            $cookie = explode(',', $_COOKIE['vote']);
            if (in_array($commentId, $cookie))
            {
                // already voted
                return true;
            }
        }
        return false;
    }

    /**
     * Ajax?
     *
     * @return bool
     * @author Miha Hribar
     */
    public static function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * No cache headers
     *
     * @return void
     * @author Miha Hribar
     */
    public static function noCacheHeader()
    {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    }
    
    /**
     * Cache folder exists and is writable?
     *
     * @return bool
     * @author Miha Hribar
     */
    public static function canCache()
    {
        return is_dir(WP_CACHE_DIR) && is_writable(WP_CACHE_DIR);
    }
    
    /**
     * Creates path with all necessary subfolders.
     *
     * @param  string   $path
     * @param  integer  $permission
     * @return void
     * @author Miha Hribar
     */
    public static function createPath($path, $permission = null)
    {
        // path already exists
        if ( is_dir($path) || is_file($path) )
        {
            return;
        }
        // fullpath
        $fullpath = false !== strstr($path, '/') ? explode('/', $path) : array($path);
        // slice it!
        for ( $i=1; $i<count($fullpath); $i++ )
        {
            // you can do it!
            $path = implode('/', array_slice($fullpath, 0, $i+1));
            // folder does not exist, create it
            if ( false === is_dir($path) )
            {
                // create path
                if ( false === @mkdir($path) )
                {
                    throw new Exception('path not created: '.$path);
                }
            }
        }
        // chmod it?
        if ( $permission )
        {
            if ( false === @chmod($path, $permission) )
            {
                throw new Exception('permission not set: ' . $permission . ' on ' . $path);
            }
        }
    }
    
    /**
     * Prepare query string with additional parameter
     *
     * @param  string $key
     * @param  string $value
     * @param  string $unset key to unset (optional)
     * @return string
     * @author Miha Hribar
     */
    public static function prepareQuery($key, $value, $unset = null)
    {
        // add the key and value and build query
        $params = $_GET;
        if (isset($unset) && isset($params[$unset]))
        {
            unset($params[$unset]);
        }
        $params[$key] = $value;
        return http_build_query($params);
    }
    
    /**
     * No results for category/search/tag ...
     *
     * @return void
     * @author Miha Hribar
     */
    public static function noResults()
    {
        echo '<section class="featured featured-empty"></section><section id="paging"><p class="more">No articles to display ...</p></section>';
    }

    /**
     * Check if we have all the libraries we need to create black and white images.
     *
     * @return bool
     */
    public static function canCreateBWImages()
    {
        // we need GD for this with the following functions
        return function_exists('imagecreatefromjpeg')
               && function_exists('imagefilter')
               && function_exists('imagejpeg');
    }
    
    /**
     * Blank image for cover photo. Set featured to true if you want the big image
     *
     * @param  bool $featured
     * @return string
     */
    public static function getBlankImage($featured = false)
    {
        $blank = sprintf(
                '%s%s.png',
                get_bloginfo('template_directory').'/images/no_image',
                $featured ? '_big' : ''
        );
        return self::isRetina() ? self::retinaString($blank) : $blank;
    }
    
    /**
     * Retina image -> insert @2x before extension
     * 
     * @param  string $path
     * @return string
     */
    public static function retinaString($path)
    {
        $parts = explode('.', $path);
        $ext = array_pop($parts);
        $parts[count($parts)-1] .= '@2x';
        $parts[] = $ext;
        return implode('.', $parts);
    }
    
    /**
     * Insert .bw before extension
     *
     * @param  string $path
     * @return string
     */
    public static function bwString($path)
    {
        $parts = explode('.', $path);
        $ext = array_pop($parts);
        $parts[] = 'bw';
        $parts[] = $ext;
        return implode('.', $parts);
    }
    
    /**
     * Get image path on server
     *
     * @param  string $webPath
     * @return string
     */
    public static function getImagePath($webPath)
    {
        $image = strstr($webPath, '/uploads/');
        return WP_CONTENT_DIR.$image;
    }
    
    /**
     * Get image for photo, type and feature. Retina images are automaticall created and cached.
     *
     * @return string
     */
    public static function getImage($photoId, $type, $feature = false)
    {
        $imageData = wp_get_attachment_image_src((int)$photoId, $type);
        if (!is_array($imageData) || !isset($imageData[0]))
        {
            // return blank image
            return self::getBlankImage($featured);
        }
        
        $originalImage = $imageData[0];
        $originalPath = self::getImagePath($originalImage);
        $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
        
        // check that we have an image we can convert
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($extension, $allowed))
        {
            return self::getBlankImage($featured);
        }
        
        if (Editorial::isRetina())
        {
            // check if we have retina, if not create it
            $retinaPath = Editorial::retinaString($originalPath);
            if (!is_file($retinaPath))
            {
                // get full image
                $fullData = wp_get_attachment_image_src((int)$photoId, 'full');
                if (!is_array($fullData) || !isset($fullData[0]))
                {
                    return $originalImage; 
                }
                $fullPath  = Editorial::getImagePath($fullData[0]);
                
                // and resize to @2x
                $image = wp_get_image_editor($fullPath);
                $image->resize($imageData[1]*2, $imageData[2]*2, $imageData[3]);
                $image->save($retinaPath);
            }
            $originalImage = Editorial::retinaString($originalImage);
        }
        
        return $originalImage;
    }
    
    /**
     * Get black and white image for photoId, creates it if doesn't exist.
     * If we cannot create BW images the color image is returned instead.
     * Retina BW images are created on the spot, no additional parameters needed.
     *
     * @param  int $photoId
     * @param  string $type
     * @param  bool $featured
     * @return string
     */
    public static function getBWImage($photoId, $type, $featured = false)
    {
        // load image -> already retina if required
        $originalImage = self::getImage($photoId, $type, $featured);
        // check if we don't have an image
        if ($originalImage == Editorial::getBlankImage($featured))
        {
            return $originalImage;
        }
        
        // we have the image
        $originalPath = Editorial::getImagePath($originalImage);
        $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
        
        // check that we have an image we can convert
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($extension, $allowed)) 
        {
            return self::getBlankImage($featured);
        }
        
        // create grayscale path -> inject .bw before extension
        $grayscalePath = self::bwString($originalPath);
        
        // the image does not exists at the moment -> create it
        if (!is_file($grayscalePath))
        {
            switch ($extension)
            {
                case 'jpg':
                case 'jpeg':
                    $im = imagecreatefromjpeg($originalPath);
                    break;
            
                case 'png':
                    $im = imagecreatefrompng($originalPath);
                    break;
            
                case 'gif':
                    $im = imagecreatefromgif($originalPath);
                    break;
            }
            
            imagefilter($im, IMG_FILTER_GRAYSCALE);
            
            switch ($extension)
            {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($im, $grayscalePath);
                    break;
            
                case 'png':
                    imagepng($im, $grayscalePath);
                    break;
            
                case 'gif':
                    imagegif($im, $grayscalePath);
                    break;
            }
            unset($im);
        }
        
        // server bw image
        return self::bwString($originalImage);
    }

	/**
	 * Get vimeo video id from url. If id is not found 0 is returned.
	 *
	 * @param  string $url
	 * @return int
	 */
	static function getVimeoId($url)
	{
		$result = preg_match('/(\d+)/', $url, $matches);
		$id = 0;
		if ($result)
		{
		    $id = $matches[0];
		}
		return $id;
	}
	
	/**
	 * Get youtube video id from url.
	 *
	 * @param  string $url
	 * @return int
	 */
	static function getYoutubeId($url)
	{
		parse_str(parse_url( $url, PHP_URL_QUERY ), $vars);
		return $vars['v'];
	}
    
    /*************************************/
    /************ Twitter as comments ****/


    public static function getTwitterMentions($postID)
    {
        //ATTENTION, this is using deprected v1 api. it will be discontinued on march 2013
        /*
        Unauthenticated calls are permitted 150 requests per hour
        */

		debug(sprintf('getTwitterMentions(%d)', $postID));

        self::$TwitterApiCallCounter++;

        //if we are over the limit, kick
            if ( self::$TwitterApiCallCounter > 150 ) {
                return;
            }

        $permalink = get_permalink( $postID );

        $last_tweet_id = get_post_meta($postID, 'twitter_last_comment_id', true);
        $url = "http://search.twitter.com/search.json?rpp=100&since_id=".$last_tweet_id."&q=".urlencode( $permalink );
		debug($url);
        $response = wp_remote_retrieve_body( wp_remote_get( $url ) );

        $data = json_decode( $response );

        if ( empty( $data->results ) ) {
				debug('no commments found');
                update_post_meta( $postID, 'twitter_last_comment_id', $data->max_id_str );
                return; //$data;
            }

          foreach ( $data->results as $tweet )
          {
              //build comment from tweet
                $comment = array(
                    'comment_post_ID'      => $postID,
                    'comment_author'       => $tweet->from_user,
                    'comment_author_email' => $tweet->from_user . '@twitter.com',
                    'comment_author_url'   => 'http://twitter.com/' . $tweet->from_user . '/status/' . $tweet->id_str . '/',
                    'comment_content'      => $tweet->text,
                    'comment_date_gmt'     => date('Y-m-d H:i:s', strtotime( $tweet->created_at ) ),
                    'comment_type'         => 'tweet'
                );
				debug(sprintf('Found new comment for post %d:', $postID, json_encode($comment)));

                wp_insert_comment( $comment );
          }

          //update post meat with the latest tweet id
          update_post_meta( $postID, 'twitter_last_comment_id', $data->max_id_str );

    }

    public static function getFacebookMentions( $postID )
    {
		debug(sprintf('getFacebookMentions(%d)', $postID));
        $permalink = urlencode( get_permalink( $postID ) );
        //$permalink = "http://facebook.com";
        //$permalink = 'http://techcrunch.com/2012/10/02/google-announces-new-lightbox-ad-format-advertisers-only-pay-when-users-expand-the-ad/';
            
            $last_fb_time = get_post_meta($postID, 'fb_last_comment_time', true);

        $url = "https://graph.facebook.com/search?q=". $permalink ."&type=POST&&since=".$last_fb_time;
		debug($url);
        $response = wp_remote_retrieve_body( wp_remote_get( $url ) );
        $data = json_decode( $response );

        if ( empty( $data ) ) {
				debug('no commments found');
                update_post_meta( $postID, 'fb_last_comment_time', time() );
                return; //$data;
            }

            foreach ( $data->data as $fb_post )
          {
              //build comment from post
                $comment = array(
                    'comment_post_ID'      => $postID,
                    'comment_author'       => $fb_post->from->name,
                    'comment_author_email' => $fb_post->from->id . '@facebook.com',
                    'comment_author_url'   => 'http://facebook.com/' . $fb_post->id,
                    'comment_content'      => (isset($fb_post->message)) ? $fb_post->message : (isset($fb_post->description) ? $fb_post->description : $fb_post->caption ),
                    'comment_date_gmt'     => date('Y-m-d H:i:s', strtotime( $fb_post->created_time ) ),
                    'comment_type'         => 'facebook'
                );
				debug(sprintf('Found new comment for post %d:', $postID, json_encode($comment)));

                wp_insert_comment( $comment );
          }

          //update post meat with the latest tweet id
          update_post_meta( $postID, 'fb_last_comment_time', time() );
    
    }

    /*************************************/
    /*************************************/
}

/**
 * Custom Walker_Nav_Menu
 * ----------------------
 * Used to generate a custom navigation used to output the editorial template
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, Editorial
 * @link        http://www.editorialtemplate.com
 * @see         http://www.mattvarone.com/wordpress/cleaner-output-for-wp_nav_menu/
 * @version     1.0
 */
class EditorialNav extends Walker_Nav_Menu
{
    var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
    
    function start_lvl(&$output, $depth) 
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }
    
    function end_lvl(&$output, $depth) 
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    function start_el(&$output, $item, $depth, $args)
    {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = "";
        $classes = array();
        
        // add selected class
        if ($item->current) $classes[] = 'selected';
        
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        
        if ($class_names) $class_names = ' class="' . esc_attr( $class_names ) . '"';
        
        $id = apply_filters( 'nav_menu_item_id', '', $item, $args );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
        
        $output .= $indent . '			<li' . $id  . $class_names .'>';
        
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    
    function end_el(&$output, $item, $depth) 
    {
            $output .= "</li>\n";
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

add_action( 'widgets_init', 'my_remove_recent_comments_style' );
function my_remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
}

/*************************************
colophon page template custom meta boxes
**************************************/


function colophon_page_add_meta_boxes() {
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
  $page_template = get_post_meta( $post_id, '_wp_page_template', true );
  // var_dump($page_template);
    if ( 'colophon.php' == $page_template ) {
        add_meta_box(
          'colophon-custom-metabox-about', // Metabox HTML ID attribute
          'General', // Metabox title
          'colophon_about_page_template_metabox', // callback name
          'page', // post type
          'side', // context (advanced, normal, or side)
          'high' // priority
      );
      add_meta_box(
          'colophon-custom-metabox-authors', // Metabox HTML ID attribute
          'Team members', // Metabox title
          'colophon_authors_page_template_metabox', // callback name
          'page', // post type
          'normal', // context (advanced, normal, or side)
          'high' // priority
      );
    }
}
// add_action( 'add_meta_boxes-page', 'colophon_page_add_meta_boxes' );
add_action('admin_init','colophon_page_add_meta_boxes');

function colophon_about_page_template_metabox( $post ) {
    // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'editorial_colophon_enabled_noncename' );

    $checked = !Editorial::getOption('colophon-enabled') ? '' : ' checked="checked"';
    $return = '
    <label>Enable Masthead 
        <input type="checkbox" name="colophon-enabled" '. $checked .' />
    </label>';
    echo $return;
}

function colophon_authors_page_template_metabox( $post ) {
    // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'editorial_colophon_authors_noncename' );

  $users = get_users(array('who' => 'author',));

  $users_string ='
  <style>
#authors li {
    padding: 15px;
    background: #efefef;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border: 1px solid #bbb;
}

#authors li img {
    float: left;
    margin-right: 10px;
}

#authors .handle {
    display: block;
    float: left;
    cursor: move;
    width: 15px;
    height: 17px;
    background: url('.get_bloginfo("template_directory").'/images/handle.png) no-repeat;
    text-indent: -99999px;
    outline: none;
    margin-right: 10px;
}

#authors input {
    float: left;
    margin: 4px 10px 0 0;
}

#authors input[type="text"] {
    margin-top: -3px;
    width: 150px;
}
</style>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function() {
        // set up sorting
        jQuery("#authors").sortable({
            handle: ".handle",
        });
        // if checkbox is disabled disable the input field
        jQuery("#authors input[type=\"checkbox\"]").click(function() {
            jQuery(this).parent().find("input[type=\"text\"]").attr("disabled", !jQuery(this).attr("checked"));
        })
        jQuery("#authors input[type=\"checkbox\"]").each(function(){
            if(!jQuery(this).attr("checked")){
                jQuery(this).parent().find("input[type=\"text\"]").attr("disabled", true);
            }
        });
    });
</script>';

    $users_string .= '<ul id="authors">';

  if (count($users))
    {
        $authors = Editorial::getOption('authors');

        $alreadyShown = array();
        if (is_array($authors) && count($authors))
        {
            foreach ($authors as $id => $title)
            {
                $data = get_userdata($id);
                if (!$data)
                {
                    // user data not loaded
                    continue;
                }
                $users_string .= Editorial_Admin::displayUser($data, $title);
                $alreadyShown[] = $id;
            }
        }
        foreach ($users as $user)
        {
            if (in_array($user->ID, $alreadyShown))
            {
                // skip already shown users
                continue;
            }
            $users_string .= Editorial_Admin::displayUser($user, '', !(bool)$authors);
        }

    }
    $users_string .= '</ul>';

  echo $users_string;
}


function colophon_save_custom_post_meta( $post_id) {
    // Sanitize/validate post meta here, before calling update_post_meta()
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    $page_template = get_post_meta( $post_id, '_wp_page_template', true );

    // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !wp_verify_nonce( $_POST['editorial_colophon_authors_noncename'], plugin_basename( __FILE__ ) ) ||
          !wp_verify_nonce( $_POST['editorial_colophon_enabled_noncename'], plugin_basename( __FILE__ ) )
      )
      return;

  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data
  $post_ID = $_POST['post_ID'];

  Editorial::setOption('colophon-enabled', isset($_POST['colophon-enabled']));
 //  $page_template = get_post_meta( $post_ID, '_wp_page_template', true );
    // if ( 'colophon.php' == $page_template ) {

    // }

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

}

add_action( 'publish_page', 'colophon_save_custom_post_meta' );
add_action( 'draft_page', 'colophon_save_custom_post_meta' );
add_action( 'future_page', 'colophon_save_custom_post_meta' );


  /*************************************/
  /********* schedule social network parsing ********/

  add_action('social_network_mining', 'find_mentions_for_posts');

  function social_network_mining_activation()
  {
      if ( !wp_next_scheduled( 'social_network_mining' ) ) {
                wp_schedule_event( time(), 'hourly', 'social_network_mining');
            }
  }

  add_action('wp', 'social_network_mining_activation');

  function find_mentions_for_posts() {
            // do something every hour
      Editorial::$TwitterApiCallCounter = 0; //reset the counter
      //dump("called find_mentions_for_posts");
      $posts_array = get_posts( array('numberposts'=>20) );
      foreach ($posts_array as $post) {
          if( comments_open( $post->ID ) ) {
              Editorial::getTwitterMentions( $post->ID );
              Editorial::getFacebookMentions( $post->ID );
          }
      }
    }

    //move this into admin later

    function editorial_comment_columns( $columns )
    {
        $columns['comment_type'] = __( 'Type' );
        return $columns;
    }
    add_filter( 'manage_edit-comments_columns', 'editorial_comment_columns' );

    function editorial_comment_column( $column, $comment_ID )
    {
        if ( 'comment_type' == $column ) {
            if ( $meta = get_comment( $comment_ID, $column , true ) ) {
                //echo $meta;
                echo $meta->comment_type;
                // var_dump($meta);
            }
        }
    }
    add_filter( 'manage_comments_custom_column', 'editorial_comment_column', 10, 2 );

    function add_comment_type_filter($filters)
    {
        $f = array(
            'comment' => __( 'Comments' ),
            'tweet' => __( 'Tweets' ),
            'facebook' => __( 'Facebook' ),
            'pings' => __( 'Pings' ),
            );
        return $f;
    }
    add_filter('admin_comment_types_dropdown', "add_comment_type_filter", 10, 2);

if (is_admin()) {
    $current = get_transient('update_themes');
}

?>