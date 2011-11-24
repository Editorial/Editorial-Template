<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Editorial
 * @subpackage Marketing
 */

get_header(); ?>

<article class="content" role="main">
	<section class="lost">
		<figure>
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/blank-page.png" alt="Blank page">
		</figure>
		<h1><em>Blank</em> page</h1>
		<h2>What you seem to be looking for is either no longer here <span>or never was in the first place.</span></h2>
		<p><a href="/">Start from scratch</a></p>
	</section>
</article>

<?php get_footer(); ?>