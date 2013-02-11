<!DOCTYPE html>
<!--
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /~~\ |_

 Version: 1.1 (02/2012)

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
<?php if ($isMobileGallery) { ?>
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0" />
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/touchgallery/lib/gallery.css">
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/touchgallery/lib/skin.css">
<script src="<?php echo get_bloginfo('template_directory'); ?>/touchgallery/gallery.min.js"></script>
<script src="//www.youtube.com/iframe_api"></script>
<?php } else { ?>
<script>if(('devicePixelRatio' in window)&&devicePixelRatio > 1){document.cookie = 'retina=1; path=/';}</script>
<?php
/*
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta name="author" content="<?php bloginfo('name'); ?>">
<meta name="keywords" content="<?php echo Editorial::getOption('meta-keywords'); ?>">
*/
?>
<meta name="description" content="The ultimate WordPress theme designed specially for digital magazines.">
<meta name="author" content="Programming: Miha Hribar, Front-end: Matjaž Korošec, Design: Natan Nikolič">
<meta name="keywords" content="Editorial, wordpress, theme, template, magazine, wordpress, widgets, responsive, adaptive, design, photography, HTML5, CSS3, readability, typography, SEO, widgets, admin, panel, colophon">
<!-- invalid / enable if needed
<meta name="handheldfriendly" content="true">
<meta name="mobileoptimized" content="320">
<meta http-equiv="cleartype" content="on">
<meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
-->
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<!-- For iPhone 4 with high-resolution Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo Editorial::getOption('touch-icon'); ?>">
<!-- For first-generation iPad: -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo Editorial::getOption('touch-icon'); ?>">
<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
<link rel="apple-touch-icon-precomposed" href="<?php echo Editorial::getOption('touch-icon'); ?>">
<link rel="shortcut icon" href="<?php echo get_bloginfo('url') . Editorial::getOption('favicon'); ?>">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_url'); ?>">
<?php
if (isset($extraCSS))
{
    foreach ($extraCSS as $css)
    {
        printf('<link rel="stylesheet" href="%s/%s">', get_bloginfo('template_directory'), $css);
    }
}
?>
<?php if ($needsHTML5player) { ?>
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/js/libs/mediaelement/mediaelementplayer.min.css">
<?php } ?>
<script src="<?php echo get_bloginfo('template_directory'); ?>/js/libs/modernizr-2.0.6.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo get_bloginfo('template_directory'); ?>/js/libs/jquery-1.7.2.min.js">\x3C/script>')</script>
<script src="<?php echo get_bloginfo('template_directory'); ?>/js/libs/mediaelement/mediaelement-and-player.js"></script>
<?php add_theme_support('automatic-feed-links'); ?>
<?php wp_head(); ?>
<?php
$translations = Editorial::getOption('translations');
?>
<?php if (Editorial::getOption('typekit-kit')) { ?>
<script src="//use.typekit.com/<?php echo Editorial::getOption('typekit-kit');?>.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
<?php } ?>
<?php } ?>
</head>

<body class="<?php echo $EditorialId; ?> <?php echo $EditorialClass; ?>">

<?php if (!$isMobileGallery) { ?>
<header id="header" class="clear" role="banner">
    <h1 id="brand" class="vcard">
        <a href="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>" class="url">
            <img class="fn org logo" src="<?php echo is_home() ? Editorial::getOption('logo-big') : Editorial::getOption('logo-small') ?>" alt="<?php bloginfo('name'); ?>">
        </a>
    </h1>
    <form id="search" role="search" method="get" action="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>">
        <fieldset>
            <legend class="v-hidden"><?php echo $translations['search']['search']; ?></legend>
            <label for="query" class="v-hidden"><?php echo $translations['search']['query']; ?></label>
            <input type="search" id="query" name="s" placeholder="<?php echo $translations['search']['search']; ?>" value="<?php echo get_search_query(); ?>">
            <input type="submit" id="find" class="ir" value="<?php echo $translations['search']['search']; ?>">
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
<?php } ?>