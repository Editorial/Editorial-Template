<?php
/**
 * The main template file.
 *
 * @package Editorial
 * @subpackage Marketing
 */

get_header(); ?>

<header id="header" role="banner">
	<div class="adapt">
		<!--<video controls poster="media/ozadje.jpg" width="950" height="420">-->
			<!--<source type="video/mp4" src="media/video.mp4">-->
			<!--<source type="video/ogg" src="media/video.ogg">-->
		<!--</video>-->
		<hgroup id="brand" class="vcard">
			<h1><a href="/" class="fn org url"><em>Edit</em>orial</a></h1>
			<h2 class="note">is the ultimate WordPress theme designed specially for requirements of digital magazines.</h2>
		</hgroup>
		<p><a href="/purchase/" class="go">Purchase</a> or <a href="/demo/">view live demo</a>.</p>
	</div>
</header>

<div class="content" role="main">
	<article class="features hentry">
		<h3 class="entry-title"><em>Everything you need, nothing you don’t</em></h3>
		<div class="goodies entry-content">
			<section id="platforms">
				<h4>Desktop, tablets & mobile</h4>
				<p>Reach and communicate with your audience across plethora of different platforms and devices.
				Editorial is adapt and offers best possible user experience on any device with HTML capable
				browser. We went all the way to ensure all you have to worry about is only the quality of your
				content.</p>
				<p><a href="/">See it in action ...</a></p>
			</section>
			<section id="photography">
				<h4>Landscape & portrait photography</h4>
				<p>Editorial has been designed to properly accommodate and compliment both landscape & portrait
				images. One of the single most important features is the flexible layout which adapts to the
				orientation of the cover image.</p>
				<p><a href="/">See it in action ...</a></p>
			</section>
			<section id="reading">
				<h4>Optimized for comfortable long reading</h4>
				<p>We want to make sure countless hours you spent on writing an article result in people actually
				reading it. Our obsession with typography led us to design Editorial so that readers get sucked
				into text like into a cosy armchair and forget about everything else. Even if they end up reading
				Divine Comedy by Dante Alighieri.</p>
				<p><a href="/">See it in action ...</a></p>
			</section>
			<section id="gallery">
				<h4>Media Gallery</h4>
				<p>They say an image is worth a thousand words. What about a whole gallery of them? With video and
				audio. Whether you are revealing shocking video reportage or photo series for this seasons new
				fashion collection the media gallery will deliver your multimedia content flawlessly.</p>
				<p><a href="/">See it in action ...</a></p>
			</section>
			<section id="feedback">
				<h4>Feedback control</h4>
				<p>You have more in common with your audience than you might realize. They care about same topics
				and love to talk about it with other people. They write comments, share your content through social
				media, blog and tweet about it etc. Editorial’s integrated tools make it easy to track and govern
				these conversations.</p>
				<p><a href="/">See it in action ...</a></p>
			</section>
			<section id="machine">
				<h4>Machine friendly</h4>
				<p>We want to make sure countless hours you spent on writing an article result in people actually
				reading it. Our obsession with typography led us to design Editorial so that readers get sucked into
				text like into a cosy armchair and forget about everything else. Even if they end up reading Divine
				Comedy by Dante Alighieri.</p>
				<p><a href="/">See it in action ...</a></p>
			</section>
			<ul class="abilities">
				<li id="wp3ready">WordPress 3 ready</li>
				<li id="updates">Free compatibility updates</li>
				<li id="localization">Complete localization</li>
				<li id="customizable">Visually customizable</li>
				<li id="documentation">Excellent documentation</li>
				<li id="admin">Custom admin panel</li>
				<li id="compatibility">Cross-browser compatibility</li>
				<li id="colophon">Automated colophon</li>
				<li id="rss">Category based RSS</li>
			</ul>
		</div>
		<footer class="v-hidden">
			<time class="published" pubdate datetime="2011-10-20T20:00:00+01:00">10/20/2011</time>
			<a class="author include" href="#brand">Editorial</a>
		</footer>
	</article>
	<aside class="pricing" role="complementary">
		<h3><em>Price <span>&</span> licencing</em></h3>
		<figure>
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/sheets.jpg" alt="Editorial sheets">
		</figure>
		<div class="price-tag">
			<h4>Editorial theme</h4>
			<ul class="included">
				<li>All listed features</li>
				<li>Free compatibility updates</li>
				<li>Access to support forums</li>
				<li>Complete code documentation</li>
				<li class="licence"><strong>1</strong> Domain licence</li>
			</ul>
			<p class="price">
				<span class="label">Total</span>
				<strong><em>$</em>150.<sup>00</sup></strong>
				<a href="/purchase/" class="go">Purchase</a>
			</p>
		</div>
	</aside>
</div>

<?php get_footer(); ?>