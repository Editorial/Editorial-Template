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
		<hgroup id="brand">
			<h1><em>Edit</em>orial</h1>
			<h2 class="note">is the ultimate WordPress theme designed specially for digital magazines.</h2>
		</hgroup>
		<p id="cta"><a href="http://demo.editorialtemplate.com/">view live demo</a>.</p>
	</div>
</header>

<div class="content" role="main">
	<article class="features hentry">
		<h3 class="entry-title"><em>You can focus on the content. We have taken care of the rest.</em></h3>
		<div class="goodies entry-content">
			<section id="platforms">
				<h4>Optimized for desktop, tablet & mobile devices</h4>
				<p>Editorial's layout seamlessly adapts and tailors your content to any device capable displaying it. As long as it runs a modern web browser. In other words we went a long way to help you reach and satisfy your broad and diverse audience.</p>
				<p><a href="http://demo.editorialtemplate.com">See it in action ...</a></p>
			</section>
			<section id="photography">
				<h4>Landscape & portrait images</h4>
				<p>The article and home page layouts adapt to the orientation of the cover image. Editorial handles all sizes and ratios and automagically creates all required thumbnails. So you will always see the big picture.</p>
				<p><a href="http://demo.editorialtemplate.com">See it in action ...</a></p>
			</section>
			<section id="reading">
				<h4>Optimized for long comfortable reading</h4>
				<p>Typography is the interface for reading. Ours was designed to offer a pleasurable experience on all supported devices. So that your readers can dive into the story like into a cozy armchair.</p>
				<p><a href="http://demo.editorialtemplate.com">See it in action ...</a></p>
			</section>
			<section id="gallery">
				<h4>HTML5 Media Gallery</h4>
				<p>When it comes to the world of mobile devices, an image can be worth thousands of words or just three: Flash Plugin Required. Integrated HTML5 player will deliver your audio/video media to all supported devices with a spectacular touch browsing for the smarter ones.</p>
				<p><a href="http://demo.editorialtemplate.com">See it in action ...</a></p>
			</section>
			<section id="feedback">
				<h4>Feedback control</h4>
				<p>When you get the attention from your readers you better be in the mood for a lot of talking. Our integrated tools make it easy to stay in touch and moderate not only blog comments but also feedback in social media. Grow and nurture your relationships.</p>
				<p><a href="http://demo.editorialtemplate.com">See it in action ...</a></p>
			</section>
			<section id="machine">
				<h4>Machine friendly</h4>
				<p>Not all your readers are human, so we made sure our cyber-robot friends would also be able to comprehend your content. Everything you publish is semantically structured to offer best possible SEO, painless social-media sharing and RSS/XML data portability.</p>
				<p><a href="http://demo.editorialtemplate.com">See it in action ...</a></p>
			</section>
			<ul class="abilities">
				<li id="wp3ready">WordPress 3 ready</li>
				<li id="updates">Free compatibility updates</li>
				<li id="localization">Complete localization</li>
				<li id="customizable">Visually customizable</li>
				<li id="documentation">Excellent documentation</li>
				<li id="admin">Custom admin panel</li>
				<li id="compatibility">Unlimited widgets</li>
				<li id="colophon">Automated colophon</li>
				<li id="rss">Category based RSS</li>
			</ul>
		</div>
		<footer class="v-hidden">
			<time class="published" datetime="2011-11-17T10:00:00+01:00">11/17/2011</time>
			<a class="author include" href="#editorial">Editorial</a>
		</footer>
	</article>
	<aside class="pricing" role="complementary">
		<h3><em>Price <span>&</span> licencing</em></h3>
		<figure>
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/sheets.png" alt="Editorial sheets">
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
				<strong><em>&euro;</em>150.<sup>00</sup></strong>
				<a href="/purchase/" class="go">Purchase</a>
			</p>
		</div>
	</aside>
</div>

<?php get_footer(); ?>