<?php
/**
 * Editorial Marketing functions and definitions
 *
 * @package Editorial
 * @subpackage Marketing
 */

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

// Paypal config
define('PAYPAL_URL',         'https://www.paypal.com/webscr?cmd=_express-checkout&token=%s');
define('PAYPAL_IPN',         'https://www.paypal.com/webscr');
define('PAYPAL_USER',        'natan_api1.editorialtemplate.com');
define('PAYPAL_EMAIL',       'natan@editorialtemplate.com');
define('PAYPAL_PASSWORD',    'VRTWSN2UZ24JMSFV');
define('PAYPAL_SIGNATURE',   'AFcWxV21C7fd0v3bYYYRCpSSRl31ASTVr99HvDh6qnPOHxQRPgZnEWfo');
define('PAYPAL_ENDPOINT',    'https://api-3t.paypal.com/nvp');
define('PAYPAL_VERSION',     '56.0');
define('PAYPAL_CONFIRM_URL', 'http://marketing.local/transaction/');
define('PAYPAL_CANCEL_URL',  'http://marketing.local/purchase/?cancel');

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
		$classes[] = 'help-list';
	}

	if (  is_page_template('download.php') )
	{
		$classes[] = 'cart';
		$classes[] = 'save';
	}

	if ( is_page('terms-of-use') )
	{
		$classes[] = 'tearms';
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
	'legal-nav' => __('Legal footer menu'),
));

// add excerpt to pages
add_post_type_support('page', 'excerpt');

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
		global $wp_query;
		$output .= '<li'.($item->current ? ' class="selected"' : '').'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', strip_tags($item->title), $item->ID ).$append;
		$item_output .= $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}