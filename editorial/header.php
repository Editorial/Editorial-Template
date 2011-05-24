<!DOCTYPE html>
<!--
Editorial theme
Version: 0.1
Based on: 320 and Up boilerplate extension
-->
<!--[if IEMobile 7 ]><html class="iem7" manifest="default.appcache?v=1"><![endif]-->
<!--[if lt IE 7 ]><html class="ie6" lang="<?php bloginfo('language'); ?>"><![endif]-->
<!--[if IE 7 ]><html class="ie7" lang="<?php bloginfo('language'); ?>"><![endif]-->
<!--[if IE 8 ]><html class="ie8" lang="<?php bloginfo('language'); ?>"><![endif]--><!-- add below to html!! manifest="default.appcache?v=1" -->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html lang="<?php bloginfo('language'); ?>"><!--<![endif]-->
<head>
<meta charset="utf-8">
<title><?php bloginfo('name'); ?><?php wp_title('&ndash;'); ?></title>
<meta name="description" content="<?php echo get_bloginfo('description'); ?>">
<meta name="author" content="<?php bloginfo('name'); ?>">
<meta name="handheldfriendly" content="true">
<meta name="mobileoptimized" content="320">
<!-- invalid / enable if needed
<meta http-equiv="cleartype" content="on">
<meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
-->
<meta name="viewport" content="width=device-width,target-densitydpi=160dpi,initial-scale=1">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/h/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/m/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" href="images/l/apple-touch-icon-precomposed.png">
<link rel="shortcut icon" href="<?php echo get_bloginfo('template_directory'); ?>/assets/images/l/apple-touch-icon.png">
<link rel="shortcut icon" href="<?php echo get_bloginfo('template_directory'); ?>/assets/images/ico/favicon.ico">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="apple-touch-startup-image" href="<?php echo get_bloginfo('template_directory'); ?>/assets/images/h/splash.png">
<link rel="stylesheet" media="handheld" href="<?php echo get_bloginfo('template_directory'); ?>/assets/css/handheld.css?v=1">
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/assets/css/style.css?v=1">
<script src="<?php echo get_bloginfo('template_directory'); ?>/assets/js/libs/modernizr-1.7.min.js"></script>
</head>

<?php add_theme_support('automatic-feed-links'); ?>
<?php wp_head(); ?>

<body id="<?php echo $EditorialId; ?>" class="<?php echo $EditorialClass; ?>">

<header id="header" class="clear" role="banner">
    <figure id="brand" class="vcard">
        <a href="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>" rel="home" class="url">
            <img class="fn org logo" src="<?php echo Editorial::getOption('logo-big'); ?>" alt="Editorial">
        </a>
        <figcaption class="v-hidden"><?php bloginfo('name'); ?></figcaption>
    </figure>
    <form id="search" role="search" method="get" action="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>">
        <fieldset>
            <legend class="v-hidden">Search</legend>
            <label for="query" class="v-hidden">Query</label>
            <input type="search" id="query" name="s" placeholder="Search...">
            <input type="submit" id="find" class="ir" value="Search">
        </fieldset>
    </form>
    <nav id="primary" role="navigation">
    <?php

    wp_nav_menu(array(
        'menu' => 'main-nav',
        'container' => false,
        'menu_class' => '',
        'menu_id' => '',
        'depth' => 1,
        'walker' => new EditorialNav(),
    ));

    ?>
    </nav>
</header>