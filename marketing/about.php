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
					<h2 class="fn n">Matjaž Korošec</h2>
					<p><strong class="title">“browser assasin”</strong>
					<a href="http://twitter.com/matjazkorosec" target="_blank">@matjazkorosec</a></p>
				</li>
				<li class="natan vcard">
					<figure>
						<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/natan-nikolic.jpg" class="photo" width="134" height="134" alt="Natan Nikolič">
					</figure>
					<h2 class="fn n">Natan Nikolič</h2>
					<p><strong class="title">“indivisual”</strong>
					<a href="http://twitter.com/natannikolic" target="_blank">@natannikolic</a></p>
				</li>
			</ul>
			<h3>Feel free to drop a line or two</h3>
			<p id="mailto">hello <span>at</span> editorialtemplate <span>dot</span> com</p>
		</aside>
		<footer class="v-hidden">
			<time class="published" pubdate datetime="2011-10-20T20:00:00+01:00">10/20/2011</time>
			<a class="author include" href="#brand">Editorial</a>
		</footer>
	</article>
</div>

<?php get_footer(); ?>