<?php

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

define ('EDITORIAL_VERSION', '1.0b');
define ('EDITORIAL_UPDATE_CHECK', 'http://editorialtemplate.com/version.json');
define ('EDITORIAL_OPTIONS', 'editorial_options');
define ('EDITORIAL_KARMA_TRESHOLD', 'karma-treshold');
// social networks
define ('EDITORIAL_FACEBOOK',    'facebook-share');
define ('EDITORIAL_TWITTER',     'twitter-share');
define ('EDITORIAL_GOOGLE',      'google-share');
define ('EDITORIAL_READABILITY', 'readability-share');

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
            add_image_size('landscape', 614, 9999);         // landscape image
            add_image_size('portrait', 446, 9999);          // portrait image
            add_image_size('media-thumb', 116, 116, true);  // media thumb
        }
        // spam prevention
        add_action('check_comment_flood', array('Editorial', 'checkReferrer'));
        // add comment redirect filter
        add_filter('comment_post_redirect', array('Editorial', 'commentRedirect'));
        // custom routing
        add_action('template_redirect', array('Editorial', 'customRouting'));
        // settings after theme setup
        add_action('after_setup_theme', array('Editorial', 'afterThemeSetup'));
    }

    /**
     * Runs after theme setup so we can setup default values etc.
     *
     * @return void
     * @author Miha Hribar
     */
    public static function afterThemeSetup()
    {
        if (get_current_theme() != 'Editorial')
        {
            return;
        }

        // setup default values
        // karma
        if (self::getOption(EDITORIAL_KARMA_TRESHOLD) === false)
        {
            // hide comments after 5 downvotes
            self::setOption(EDITORIAL_KARMA_TRESHOLD, 5);
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
        printf('<article class="hentry" id="comment-%1$d">
                <section>
                    <footer>
                        <cite class="author vcard">
                            %2$s
                        </cite>
                        <time class="published" pubdate datetime="%3$s">
                            <span class="value-title" title="%3$s"> </span>
                            %4$s
                        </time>
                    </footer>
                    <aside role="complementary">
                        <form class="favorize" method="post" action="%8$s">
                            <fieldset%12$s>
                                <input type="radio" id="vote-for-%1$d" name="vote-%1$d" value="1"%13$s>
                                <label class="vote-for" for="vote-for-%1$d"><em>+1</em></label>
                                <input type="radio" id="vote-against-%1$d" name="vote-%1$d" value="-1"%13$s>
                                <label class="vote-against" for="vote-against-%1$d"><em>-1</em></label>
                            </fieldset>
                            <fieldset>
                                <input type="hidden" name="comment_id" value="%1$d">
                                <input type="submit" name="submit-%1$d" value="Go">
                                <strong id="score-%1$d" class="score">%11$s</strong>
                            </fieldset>
                        </form>
                    </aside>
                </section>
                <header>
                    <h2 class="entry-title"><span class="v-hidden">%5$s</span> %6$d.</h2>
                </header>
                <blockquote class="%9$sentry-content">
                    %10$s
                    <p>%7$s</p>
                </blockquote>
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
            $comment->comment_ID, // @todo add correct comment count
            $comment->comment_content,
            get_bloginfo('url').'/comment-vote.php',
            $trackback ? 'trackback ' : '',
            $trackback ? sprintf(
                '<h4><a href="%s" rel="nofollow" target="_blank">%s</a></h4>',
                $comment->comment_author_url,
                $comment->comment_author) : '',
            (int)$comment->comment_karma == 0
                ? '0'
                : ($comment->comment_karma < 0 ? '-'.$comment->comment_karma : '+'.$comment->comment_karma),
            Editorial::alreadyVoted($comment->comment_ID) ? ' class="disabled"' : '',
            Editorial::alreadyVoted($comment->comment_ID) ? ' disabled' : ''
        );

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
        ?>
        <nav id="tabs" role="navigation">
            <ul>
                <li<?php echo $selected == 'article' ?  ' class="selected"' : '' ?>>
                    <a href="<?php echo get_permalink($postId); ?>"><?php _e('Article', 'Editorial'); ?></a>
                </li>
                <li<?php echo $selected == 'gallery' ?  ' class="selected"' : '' ?>>
                    <a href="<?php echo get_attachment_link($thumbId); ?>"><?php _e('Gallery', 'Editorial'); ?></a>
                </li>
                <li<?php echo $selected == 'comments' ? ' class="selected"' : '' ?>>
                    <a href="<?php echo self::commentsLink($postId); ?>"><?php _e('Feedback', 'Editorial'); echo $commentCount ? ' <em>'.$commentCount.'</em>' : ''; ?></a>
                </li>
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
     * Custom routing plugin (for comments etc.)
     *
     * @return void
     * @author Miha Hribar
     */
    public function customRouting()
    {
        global $wp_query;
        // custom comments route
        if ($wp_query->is_singular && array_key_exists('comments', $_GET))
        {
            // make sure it's not 404
            $wp_query->is_404 = false;

            // include comments
            include(TEMPLATEPATH.'/single-comments.php');
            exit();
        }

        if ($wp_query->is_404)
        {
            // check if this is comment post
            $parts = explode('/', $_SERVER['REQUEST_URI']);
            $last = array_pop($parts);
            if ($last && $last == 'comment-post.php')
            {
                // make sure it's not 404
                header('HTTP/1.1 200 OK');
                include(TEMPLATEPATH.'/comment-post.php');
                exit();
            }
            else if ($last && $last == 'comment-vote.php')
            {
                // make sure it's not 404
                header('HTTP/1.1 200 OK');
                include(TEMPLATEPATH.'/comment-vote.php');
                exit();
            }
        }
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
            $return .= sprintf('<li>%s</li>', __('comment_error_'.$error, 'Editorial'));
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
        if (!$useragent)
        {
            // take from server
            $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        return preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)
               ||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
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
        switch ($network)
        {
            case EDITORIAL_TWITTER:
                $html = sprintf(
                   '<a href="http://twitter.com/share"
                       class="twitter-share-button"
                       data-count="horizontal"
                       data-via="%s"
                       %s>Tweet</a>
                       <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>',
                   self::getOption('twitter-account'),
                   self::getOption('twitter-related')
                       ? sprintf('data-related="%s:%s"', self::getOption('twitter-related'), self::getOption('twitter-related-desc'))
                       : ''
                );
                break;

            case EDITORIAL_FACEBOOK:
                $html = sprintf(
                   '<iframe src="http://www.facebook.com/plugins/like.php?href=%1$s&amp;send=false&amp;layout=button_count&amp;width=%2$d&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=%3$d"
                     scrolling="no"
                     frameborder="0"
                     style="border:none; overflow:hidden; width:%2$dpx; height:%3$dpx;"
                     allowTransparency="true"></iframe>',
                   urlencode($params['url']),
                   $params['width'],
                   $params['height']
                );
                break;

            case EDITORIAL_GOOGLE:
                $html = "<g:plusone size=\"medium\"></g:plusone>
                <script type=\"text/javascript\">
                  (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                  })();
                </script>";
                break;

            case EDITORIAL_READABILITY:
                $html = '<div class="rdbWrapper" data-show-read="1" data-show-send-to-kindle="1" data-show-print="0" data-show-email="0" data-orientation="0" data-version="1" data-bg-color="transparent"></div><script type="text/javascript">(function() {var s = document.getElementsByTagName("script")[0],rdb = document.createElement("script"); rdb.type = "text/javascript"; rdb.async = true; rdb.src = document.location.protocol + "//www.readability.com/embed.js"; s.parentNode.insertBefore(rdb, s); })();</script>';
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
