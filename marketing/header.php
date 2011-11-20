<?php
/**
 * The Header for our theme.
 *
 * @package Editorial
 * @subpackage Marketing
 */
?><!DOCTYPE html>
<!--
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /~~\ |_

 Version: 1.0 (11/2011)

 Design: Natan Nikolic (twitter.com/natannikolic)
 Programming: Miha Hribar (twitter.com/mihahribar)
 Front-end: Matjaz Korosec (twitter.com/matjazkorosec)

 Based on: 320 and Up boilerplate extension

-->
<!--[if IEMobile 7 ]><html class="iem7" manifest="default.appcache?v=1"><![endif]-->
<!--[if IE 7 ]><html class="ie7" lang="en"><![endif]-->
<!--[if IE 8 ]><html class="ie8" lang="en"><![endif]--><!-- add below to html!! manifest="default.appcache?v=1" -->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html lang="en"><!--<![endif]-->
<head>
<meta charset="utf-8">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

?></title>
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta name="author" content="Editorial">
<meta name="viewport" content="width=device-width,target-densitydpi=160dpi,initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/touch/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/touch/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/touch/apple-touch-icon.png">
<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/assets/css/style.min.css?20111120">
<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/libs/modernizr-2.0.6.min.js"></script>
<script src="http://use.typekit.com/sue6gqc.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
<?php
	wp_head();
?>
<script>
var _gaq = _gaq || [];_gaq.push(['_setAccount', 'UA-23356248-1']);_gaq.push(['_trackPageview']);
(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
</script>
</head>

<body <?php body_class(); ?>>
<?php if (!is_home()) { ?>
<header id="header" role="banner">
	<div class="adapt">
		<h2 id="brand" class="vcard">
			<a href="http://editorialtemplate.com/" class="ir fn org url">Editorial</a>
		</h2>
		<nav class="primary" role="navigation">
<?php

		$settings = array(
			'theme_location' => 'main-nav',
			'container'      => false,
			'menu_class'     => '',
			'menu_id'        => '',
			'depth'          => 1,
			'walker'         => new EditorialNav(),
		);
		wp_nav_menu($settings);

?>
		</nav>
	</div>
</header>
<?php } ?>