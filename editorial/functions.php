<?php

error_reporting(0);

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

define ('EDITORIAL_VERSION', '1.0');
//je to samo KAO ali zares cekira?
//define ('EDITORIAL_UPDATE_CHECK', 'http://editorialtemplate.com/version.json');
define ('EDITORIAL_UPDATE_API', 'http://editorialtemplate.com/new-moon/');
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
if (!defined('WP_CACHE_DIR')) define('WP_CACHE_DIR', WP_CONTENT_DIR . '/cache');
if (!defined('WP_CACHE_URL')) define('WP_CACHE_URL', WP_CONTENT_URL . '/cache');

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
		if (!Editorial::getOption('logo-big')) Editorial::setOption('logo-big', $assets.'images/editorial-logo.png');
		if (!Editorial::getOption('logo-small')) Editorial::setOption('logo-small', $assets.'images/editorial-logo-small.png');
		if (!Editorial::getOption('logo-gallery')) Editorial::setOption('logo-gallery', $assets.'images/editorial-logo-white2.png');
		if (!Editorial::getOption('touch-icon')) Editorial::setOption('touch-icon', $assets.'images/touch/apple-touch-icon.png');
		if (!Editorial::getOption('favicon')) Editorial::setOption('favicon', $assets.'favicon.ico');
		
		// number of active widgets?
		$widgets = wp_get_sidebars_widgets();
		self::$widgetCount = isset($widgets[EDITORIAL_WIDGET]) && is_array($widgets[EDITORIAL_WIDGET]) ? count($widgets[EDITORIAL_WIDGET]) : 0;
		
		add_filter('attachment_fields_to_edit', array('Editorial','hide_some_attachment_fields'), 11, 2 );
		add_filter('media_upload_tabs', array('Editorial','remove_media_library_tab'));
		add_filter('admin_head_media_upload_gallery_form', array('Editorial','hide_galery_settings_div'));
		add_filter('type_url_form_media', array('Editorial','hide_type_url_fields'));
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
	
	public function hide_galery_settings_div($form_action_url, $type){
		print <<<EOF
		        <style type="text/css">
		            #gallery-settings *{
		                display:none;
		                }
		        </style>
EOF;
	}
	
	public function hide_some_attachment_fields($form_fields, $post) {
		
		//print_r($form_fields);
		
	//	if ( self::is_image( $post->post_mime_type ) ) {
			
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
				'revisionsdiv'
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
	public function adminInit()
	{
		if (get_current_theme() != 'Editorial')
		{
			return;
		}

		// couldn't get anything else to run on theme start
		if (self::getOption('editorial-install') === false)
		{
			debug('editorial-install');
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
						<?php the_time(get_option('date_format'));
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
		$url = sprintf(
				'%s%s.png',
				get_bloginfo('template_directory').'/images/no_image',
				$featured ? '_big' : ''
		);
		$imageData = wp_get_attachment_image_src($thumbId, $args);
		if (count($imageData) > 1)
		{
			$url = $imageData[0];
			// black and white images?
			if (Editorial::getOption('black-and-white'))
			{
			    $url = get_bloginfo('template_directory').'/bw-photo.php?photo='.$thumbId.'&amp;type='.$args[0];
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
?>
	<nav id="tabs" role="navigation">
		<ul <?php echo comments_open() ? "" : "class='no-feedback'" ?>>
			<li<?php echo $selected == 'article' ?  ' class="selected"' : '' ?>>
				<a href="<?php echo get_permalink($postId); ?>"><?php _e('Article', 'Editorial'); ?></a>
			</li>
			<li<?php echo $selected == 'gallery' ?  ' class="selected"' : '' ?>>
				<a href="<?php echo get_attachment_link($thumbId); ?>"><?php _e('Gallery', 'Editorial'); echo $attachmentCount ? ' <em>'.$attachmentCount.'</em>' : ''; ?></a>
			</li>
			<?php if (comments_open()) { ?>
			<li<?php echo $selected == 'comments' ? ' class="selected"' : '' ?>>
				<a href="<?php echo self::commentsLink($postId); ?>"><?php _e('Feedback', 'Editorial'); echo $commentCount ? ' <em>'.$commentCount.'</em>' : ''; ?></a>
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
		if($_GET['debugmobile'])
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

			/*
			case EDITORIAL_TWITTER:
				$html = sprintf(
					'<iframe allowtransparency="true" frameborder="0" scrolling="no"
						src="http://platform.twitter.com/widgets/tweet_button.html?via=%s&text=%s%s"
						style="width:%dpx; height:%dpx;"></iframe>',
					self::getOption('twitter-account'),
					esc_attr($params['text']),
					self::getOption('twitter-related') ? sprintf('&related=%s', self::getOption('twitter-related')) : '',
					$params['width'],
					$params['height']
				);
				break;
			*/

			case EDITORIAL_GOOGLE:
				//$status .=":%20".esc_attr($params['text'])."%20".urlencode($params['url']);
				$html = sprintf(
						'<a href="https://plus.google.com/share?url=%s" class="resizing">Share this article: Google+</a>',
						urlencode($params['url'])
						);
			break;
			/*
			case EDITORIAL_GOOGLE:
				$html = "<g:plusone size=\"medium\" width=\"65\"></g:plusone>
				<script type=\"text/javascript\">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
				</script>";
				break;
			*/

			case EDITORIAL_FACEBOOK:
				//$status = esc_attr($params['text'])." - ".urlencode($params['url']);
				$html = sprintf(
						'<a href="http://www.facebook.com/sharer.php?u=%s&t=%s" class="resizing">Share this article: Like</a>',
						urlencode($params['url']),
						esc_attr($params['text'])
				);
			break;
			/*
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
			*/

//			case EDITORIAL_READABILITY:
//				$html = '<div class="rdbWrapper" data-show-read="1" data-show-send-to-kindle="1" data-show-print="0" data-show-email="0" data-orientation="0" data-version="1" data-bg-color="transparent"></div><script type="text/javascript">(function() {var s = document.getElementsByTagName("script")[0],rdb = document.createElement("script"); rdb.type = "text/javascript"; rdb.async = true; rdb.src = document.location.protocol + "//www.readability.com/embed.js"; s.parentNode.insertBefore(rdb, s); })();</script>';
//				break;
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
    	echo '<section class="featured featured-empty"></section><section id="paging"><p class="more">Na articles to display ...</p></section>';
    }
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

?>
