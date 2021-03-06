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

 Version: 2.3 (04/2013)

 Design: Natan Nikolic (twitter.com/natannikolic)
 Programming: Miha Hribar (twitter.com/mihahribar)
 Front-end: Matjaz Korosec (twitter.com/matjazkorosec)

 Based on: 320 and Up boilerplate extension

-->
<!--[if IEMobile 7 ]><html class="iem7<?php if (is_home()) { echo 'home-bgr'; } ?>" manifest="default.appcache?v=1"><![endif]-->
<!--[if IE 7 ]><html class="ie7<?php if (is_home()) { echo 'home-bgr'; } ?>" lang="en"><![endif]-->
<!--[if IE 8 ]><html class="ie8<?php if (is_home()) { echo 'home-bgr'; } ?>" lang="en"><![endif]--><!-- add below to html!! manifest="default.appcache?v=1" -->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html class="<?php if (is_home()) { echo 'home-bgr'; } ?>" lang="en"><!--<![endif]-->
<head>
<meta charset="utf-8">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	//if (!is_home()) { bloginfo( 'name' ); }

	// Add the blog description for the home/front page.
	//$site_description = get_bloginfo( 'description', 'display' );
	//if ( $site_description && ( is_home() || is_front_page() ) )
		//echo " | $site_description";

	// Add a page number if necessary:
	//if ( $paged >= 2 || $page >= 2 )
		//echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );
/*
 *
<meta name="description" content="<?php bloginfo('description'); ?>">
<link rel="image_src" href="http://editorialtemplate.com/wp-content/themes/marketing/assets/images/dsg/sheets.png">
 *
 */


?></title>
<meta name="author" content="Programming: Miha Hribar, Front-end: Matjaž Korošec, Design: Natan Nikolič">
<meta name="keywords" content="Editorial, wordpress, theme, template, magazine, widgets, responsive, adaptive, design, photography, HTML5, CSS3, readability, typography, SEO, widgets, admin, panel, colophon">
<meta name="viewport" content="width=device-width,target-densitydpi=160dpi,initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/touch/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/touch/apple-touch-icon.png">
<link rel="apple-touch-icon-precomposed" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/touch/apple-touch-icon.png">
<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/assets/css/style.css?20130302">
<script src="<?php bloginfo( 'template_directory' ); ?>/assets/js/libs/modernizr-2.0.6.min.js"></script>
<script type="text/javascript" src="//use.typekit.net/sue6gqc.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
	wp_head();
?>
<?php
	if (is_home()) {
?>
<meta property="og:image" content="http://editorialtemplate.com/images/editorial-02.jpg">
<meta property="og:image" content="http://editorialtemplate.com/images/editorial-03.jpg">
<?php
	}
?>
<script>var _gaq = _gaq || [];_gaq.push(['_setAccount', 'UA-23356248-1']);_gaq.push(['_trackPageview']);(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();</script>
<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', 'UA-39541867-1', 'editorialtemplate.com');ga('send', 'pageview');</script>
<!-- start Mixpanel --><script>(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);mixpanel.init("d04e67a4322d93f3dc1969b7d79e9c39");</script><!-- end Mixpanel -->
<script>setTimeout(function(){var a=document.createElement("script");var b=document.getElementsByTagName("script")[0];a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0015/4604.js?"+Math.floor(new Date().getTime()/3600000);a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);</script>
</head>

<body <?php body_class(); ?>>
<?php if (!is_home() && !is_404()) { ?>
<header id="header" role="banner">
	<div class="adapt">
		<?php /*
		<h2 id="brand" class="vcard">
			<a href="http://editorialtemplate.com/" class="ir fn org url">Editorial</a>
		</h2>
		*/ ?>
		<nav class="primary" role="navigation">
<?php
/*

// To bi blo treba uporabit
wp_nav_menu(array(
	'theme_location' => 'main-nav',
	'container' => false
));
*/

/*
 * @frontend, če lahko IDje zamenjaš s classi v CSSu, lahko uporabimo WP default meni.
 * Če ne, bo hardcoded tako kot spodaj, ker nočem hackat core funkcionalnosti.
 */
$s = ' class="selected"';
?>

<ul>
	<li id="home"<?php     if(is_front_page()) echo $s; ?>><a href="/">Home</a></li>
	<li id="features"<?php if(is_page_template('custom_features.php')) echo $s; ?>><a href="/features/">Features</a></li>
	<li id="help"<?php     if(is_post_type_archive('faq') || is_singular('faq')) echo $s; ?>><a href="/documentation/"><span>Documentation</span></a></li>
	<li id="about"<?php    if(is_page('masthead')) echo $s; ?>><a href="/masthead/">About</a></li>
	<?php if (!is_page('purchase') && !is_page('transaction') && !is_page('download')) { ?>
	<li id="purchase"><a href="/purchase/">Purchase</a></li>
	<li id="demo"><a href="http://demo.editorialtemplate.com/">View demo</a>, <a href="http://editorialtemplate.com/trial/">start free trial</a> or</li>
	<?php } ?>
</ul>

		</nav>
	</div>
</header>
<?php } ?>