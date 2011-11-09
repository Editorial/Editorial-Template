<!DOCTYPE html>
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
<?php $htmlParams = 'lang="'.get_bloginfo('language').'"'.(isset($htmlClass) ? ' class="'.$htmlClass.'"' : ''); ?>
<!--[if IEMobile 7 ]><html class="iem7" manifest="default.appcache?v=1"><![endif]-->
<!--[if lt IE 7 ]><html class="ie6" <?php echo $htmlParams; ?>><![endif]-->
<!--[if IE 7 ]><html class="ie7" <?php echo $htmlParams; ?>><![endif]-->
<!--[if IE 8 ]><html class="ie8" <?php echo $htmlParams; ?>><![endif]--><!-- add below to html!! manifest="default.appcache?v=1" -->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php echo $htmlParams; ?>><!--<![endif]-->
<head>
<meta charset="utf-8">
<title><?php bloginfo('name'); ?><?php wp_title('&ndash;'); ?></title>
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta name="author" content="<?php bloginfo('name'); ?>">
<meta name="keywords" content="<?php echo Editorial::getOption('meta-keywords'); ?>">
<!-- invalid / enable if needed
<meta name="handheldfriendly" content="true">
<meta name="mobileoptimized" content="320">
<meta http-equiv="cleartype" content="on">
<meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
-->
<meta name="viewport" content="width=device-width,target-densitydpi=160dpi,initial-scale=1">
<!-- For iPhone 4 with high-resolution Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo Editorial::getOption('touch-icon'); ?>">
<!-- For first-generation iPad: -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo Editorial::getOption('touch-icon'); ?>">
<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
<link rel="apple-touch-icon-precomposed" href="<?php echo Editorial::getOption('touch-icon'); ?>">
<link rel="shortcut icon" href="<?php echo Editorial::getOption('favicon'); ?>">
<meta name="apple-mobile-web-app-capable" content="no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/assets/css/style.min.css?v=1">
<?php if ($needsHTML5player) { ?>
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/assets/css/libs/mediaelementplayer.min.css">
<?php } ?>
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/libs/modernizr-2.0.6.min.js"></script>
<?php add_theme_support('automatic-feed-links'); ?>
<?php wp_head(); ?>
<!--<script>
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
s.parentNode.insertBefore(g,s)}(document,'script'));
</script>-->
</head>

<body class="<?php echo $EditorialId; ?> <?php echo $EditorialClass; ?>">

<header id="header" class="clear" role="banner">
	<h1 id="brand" class="vcard">
		<a href="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>" class="url">
			<img class="fn org logo" src="<?php echo is_home() ? Editorial::getOption('logo-big') : Editorial::getOption('logo-small').'" width="133" height="19' ?>" alt="<?php bloginfo('name'); ?>">
		</a>
	</h1>
	<form id="search" role="search" method="get" action="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>">
		<fieldset>
			<legend class="v-hidden"><?php _e('Search', 'Editorial'); ?></legend>
			<label for="query" class="v-hidden"><?php _e('Query', 'Editorial'); ?></label>
			<input type="search" id="query" name="s" placeholder="<?php _e('Search...', 'Editorial'); ?>" value="<?php echo get_search_query(); ?>">
			<input type="submit" id="find" class="ir" value="<?php _e('Search', 'Editorial'); ?>">
		</fieldset>
	</form>
<?php
	// show main navigation if not on 404 page
	if ($EditorialId != 'notfound')
	{
		$settings = array(
			'theme_location' => 'main-nav',
			'container'      => false,
			'menu_class'     => '',
			'menu_id'        => '',
			'depth'          => 1,
			'walker'         => new EditorialNav(),
			'echo'           => false,
		);
		$menu = wp_nav_menu($settings);
		// looks weird, but this is the only way to count them bastards.
		$menuItems = substr_count($menu,'<li');
		if ($menuItems > 5)
		{
			// we're hoarding
			$settings['menu_class'] = 'hoarding';
			$menu = wp_nav_menu($settings);
		}
		echo '	<nav id="primary" role="navigation">
		'.$menu.'
	</nav>
';
	}
?>
</header>
