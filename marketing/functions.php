<?php
/**
 * Editorial Marketing functions and definitions
 *
 * @package Editorial
 * @subpackage Marketing
 */

require_once 'library/Purchase.php';
require_once 'library/Account.php';
require_once 'library/Domain.php';
require_once 'library/Promo.php';

// for debuggin purposes
function dump($object = '')
{
    echo '<pre style="border: 1px solid #ccc; background: #eee; padding: 15px; margin: 15px; font-family: "Courier New", Courier, monospace">'.print_r($object, true).'</pre>';
}

function error($message)
{
    error_log(sprintf('[Marketing] %s', $message));
}

function debug($message)
{
    error($message);
}

// zip file location
define('EDITORIAL_ZIP', __DIR__ . '/editorial.zip');

// mail settings
define('EDITORIAL_MAIL_FROM',      'no-reply@editorialtemplate.com');
define('EDITORIAL_MAIL_FROM_NAME', 'Editorial');

// Paypal config
define('PAYPAL_URL',         'https://www.paypal.com/webscr?cmd=_express-checkout&token=%s');
define('PAYPAL_IPN',         'https://www.paypal.com/webscr');
define('PAYPAL_USER',        'natan_api1.editorialtemplate.com');
define('PAYPAL_EMAIL',       'natan@editorialtemplate.com');
define('PAYPAL_PASSWORD',    'VRTWSN2UZ24JMSFV');
define('PAYPAL_SIGNATURE',   'AFcWxV21C7fd0v3bYYYRCpSSRl31ASTVr99HvDh6qnPOHxQRPgZnEWfo');
define('PAYPAL_ENDPOINT',    'https://api-3t.paypal.com/nvp');
define('PAYPAL_VERSION',     '56.0');
define('PAYPAL_CONFIRM_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/transaction/');
define('PAYPAL_CANCEL_URL',  'http://' . $_SERVER['SERVER_NAME'] . '/purchase/?cancel');

// licence pricing
define('LICENCE_COST', 150.00);

// david sandbox
//define('PAYPAL_URL',         'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=%s');
//define('PAYPAL_IPN',         'https://www.sandbox.paypal.com/cgi-bin/webscr');
//define('PAYPAL_USER',        'david_1321039203_biz_api1.kuridza.si');
//define('PAYPAL_EMAIL',       'david_1321039203_biz@kuridza.si');
//define('PAYPAL_PASSWORD',    '1321039259');
//define('PAYPAL_SIGNATURE',   'AFcWxV21C7fd0v3bYYYRCpSSRl31AVts2sEdwWw1DK5C2EnxeyGcfv8E');
//define('PAYPAL_ENDPOINT',    'https://api-3t.sandbox.paypal.com/nvp');

// miha sandbox
//define('PAYPAL_URL',         'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=%s');
//define('PAYPAL_IPN',         'https://www.sandbox.paypal.com/cgi-bin/webscr');
//define('PAYPAL_USER',        'shop_1291577744_biz_api1.hribar.info');
//define('PAYPAL_EMAIL',       'shop_1291577744_biz@hribar.info');
//define('PAYPAL_PASSWORD',    '1291577754');
//define('PAYPAL_SIGNATURE',   'AzWvEuogApa37pmV5w.Qo7jcZb-jArsE790LFPjmJsXXGikSjH4TCIIg');
//define('PAYPAL_ENDPOINT',    'https://api-3t.sandbox.paypal.com/nvp');

/**
 * Adds classes to the array of body classes.
 */
function editorial_body_classes( $classes )
{
	// remove existing wp classes
	$classes = array();

	if (is_home())
	{
		$classes[] = 'home';
	}

	if (is_page_template('about.php'))
	{
		$classes[] = 'about';
	}

	if (is_page_template('cart.php'))
	{
		$classes[] = 'cart';
	}

	if (is_page_template('transaction.php'))
	{
		$classes[] = 'cart';
		$classes[] = 'save';
		$classes[] = 'transaction';
	}

	if (is_page_template('help.php'))
	{
		if ( isset($_GET['question']) )
		{
			$classes[] = 'help-single';
		}
		else
		{
			$classes[] = 'help-list';
		}
	}

	if (  is_page_template('download.php') )
	{
		$classes[] = 'cart';
		$classes[] = 'save';

		if ( strstr($_SERVER['REQUEST_URI'], '/update') )
		{
			$classes[] = 'update';
		}
	}

	if ( is_page_template('manager.php') )
	{
		$classes[] = 'domains';
	}

	if ( is_page('terms-of-use') )
	{
		$classes[] = 'tearms';
	}

	if (is_page_template('custom_features.php')) {
		$classes[] = 'off-canvas';
	}


	/*if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';
*/
	return $classes;
}

add_filter( 'body_class', 'editorial_body_classes' );

register_nav_menus(array(
	'main-nav'  => __('Main menu'),
	'help-nav'  => __('Help & Support footer menu'),
	'about-nav' => __('About footer menu'),
	'legal-nav' => __('Legal footer menu')
));

// add excerpt to pages
add_post_type_support('page', 'excerpt');

// needed for featured images
add_theme_support('post-thumbnails');

// mail hooks
function editorial_wp_mail_from($content_type)
{
	return EDITORIAL_MAIL_FROM;
}

function editorial_wp_mail_from_name($name)
{
	return EDITORIAL_MAIL_FROM_NAME;
}

add_filter('wp_mail_from',      'editorial_wp_mail_from');
add_filter('wp_mail_from_name', 'editorial_wp_mail_from_name');

// error body class
function custom_body_classes($classes)
{
    // add '404' to the $classes array
    if (is_404())
    {
        $classes[] = 'blank';
    }
    // return the $classes array
    return $classes;
}

add_filter('body_class','custom_body_classes');

/**
 * Custom Walker_Nav_Menu
 * ----------------------
 * Used to generate a custom navigation used to output the editorial template
 *
 * @package     Editorial
 * @subpackage  Marketing
 * @author      Miha Hribar
 * @version     1.0
 */
class EditorialNav extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth, $args)
	{
		$ids = array(
			'Features'       => 'features',
			'Purchase'       => 'purchase',
			'Help & Support' => 'help',
			'About'          => 'about',
		);

		$id = isset($ids[$item->title]) ? ' id="'. $ids[$item->title] .'"' : '';

		global $wp_query;
		$output .= '
						<li'.$id.($item->current ? ' class="selected"' : '').'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		// ugly hack
		$item->title = strip_tags($item->title);
		if ( strstr($id, 'help') )
		{
			$item->title = '<span>Help &</span> Support';
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Theme Options
 * - enquiry form id
 */
add_action('admin_init', 'theme_options_init');
add_action('admin_menu', 'theme_options_add_page');

function theme_options_init(){
	register_setting('em_options', 'em_theme_options');
}
function theme_options_add_page() {
	add_theme_page(__('Theme Options'), __('Theme Options'), 'edit_theme_options', 'theme_options', 'theme_options_do_page');
}

function theme_options_do_page() {
	global $select_options;
	if(!isset($_REQUEST['settings-updated'])) $_REQUEST['settings-updated'] = false; ?>
	<div>
		<?php screen_icon(); echo "<h2>". __('Custom Theme Options') . "</h2>"; ?>
		<?php if(false !== $_REQUEST['settings-updated']) : ?>
			<div><p><strong><?php _e('Options saved'); ?></strong></p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields('em_options'); ?>
			<?php $options = get_option('em_theme_options'); ?>
			<table>
				<tr>
					<th><?php _e('Enquiry form ID'); ?></th>
					<td>
						<input id="em_theme_options[enquiry]" type="text" name="em_theme_options[enquiry]" value="<?php esc_attr_e($options['enquiry']); ?>" />
					</td>
				</tr>
			</table>
			<p>
				<input type="submit" value="<?php _e('Save Options'); ?>" />
			</p>
		</form>
	</div>
<?php }

include 'admin/admin.php';