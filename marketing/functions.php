<?php
/**
 * Editorial Marketing functions and definitions
 *
 * @package Editorial
 * @subpackage Marketing
 */

/**
 * Adds classes to the array of body classes.
 */
function editorial_body_classes( $classes )
{
	if (is_home())
	{
		$classes[] = 'home';
	}

	if (is_page_template('about.php'))
	{
		$classes[] = 'about';
	}

	if (is_page_template('purchase.php'))
	{
		$classes[] = 'cart';
	}

	if (is_page_template('help.php'))
	{
		$classes[] = 'help-list';
	}

	if (is_page())
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