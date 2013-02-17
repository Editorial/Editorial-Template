<?php
/**
 * Template Name: About
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

get_header(); ?>

<div class="content" role="main">
	<article class="main hentry">
		<h1 class="entry-title"><em>About</em> us</h1>
		<figure id="dust">
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/creative-fairy-dust.png" width="600" height="390" alt="Creative fairy dust">
		</figure>
		<div class="info entry-summary">
			<?php the_post(); ?>
			<?php the_content(); ?>
		</div>
		<aside class="makers entry-content" role="complementary">
			<ul>
				<li class="miha vcard">
					<figure>
						<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/miha-hribar.jpg" class="photo" width="134" height="134" alt="Miha Hribar">
					</figure>
					<h2 class="fn n">Miha Hribar</h2>
					<p><strong class="title">“code ninja”</strong>
					<a href="http://twitter.com/mihahribar" class="url" target="_blank">@mihahribar</a></p>
				</li>
				<li class="matjaz vcard">
					<figure>
						<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/matjaz-korosec.jpg" class="photo" width="134" height="134" alt="Matjaž Korošec">
					</figure>
					<h2 class="fn n">Matjaz Korosec</h2>
					<p><strong class="title">“browser assasin”</strong>
					<a href="http://twitter.com/matjazkorosec" target="_blank">@matjazkorosec</a></p>
				</li>
				<li class="natan vcard">
					<figure>
						<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/natan-nikolic.jpg" class="photo" width="134" height="134" alt="Natan Nikolič">
					</figure>
					<h2 class="fn n">Natan Nikolic</h2>
					<p><strong class="title">“indivisual”</strong>
					<a href="http://twitter.com/natannikolic" target="_blank">@natannikolic</a></p>
				</li>
			</ul>
			<h3>Brought to you with the help of</h3>
			<p>
				<a href="https://twitter.com/tanjapislar">Tanja Pislar</a>'s long term GENERAL CODE CONTRIBUTION, 
				<a href="https://github.com/KrofDrakula">Klemen Slavic</a>'s custom written MULTIMEDIA GALLERY interface for touch screens, 
				<a href="https://twitter.com/ladushki">Larissa Bobkova</a>'s solution for a simpler GALLERY ADMINISTRATION, 
				<a href="https://twitter.com/janhancic">Jan Hancic</a>'s solution for RESPONSIVE IMAGES, 
				<a href="http://twistedpoly.com/">Nejc Polovsak</a>'s pixelicious 3D RENDER showing off our home page, 
				<a href="http://www.jakavinsek.com/">Jaka Vinsek</a>'s PHOTOGRAPHY used for pseudo magazine covers, 
				<a href="http://dribbble.com/benedik">Rok Benedik</a>'s ICONS we use through our website, and finally 
				<a href="https://twitter.com/malarkey">Andy Clarke</a>'s 320-up BOILERPLATE which kickstarted our CSS. 
				Our words are (mostly) set in <a href="http://en.wikipedia.org/wiki/Robert_Slimbach">Robert Slimbach</a>'s gorgeous Minion Pro FONT, 
				served with <a href="https://typekit.com/fonts/minion-pro">Adobe Typekit</a>.
			</p>
		</aside>
		<footer class="v-hidden">
			<time class="published" datetime="2011-10-20T20:00:00+01:00">10/20/2011</time>
			<a class="author include" href="#editorial">Editorial</a>
		</footer>
	</article>
</div>

<?php get_footer(); ?>