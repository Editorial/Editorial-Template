<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header();
the_post();

?>

<div class="content" role="main">
	<article class="main default hentry">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<p class="lead entry-summary"><?php echo strip_tags(get_the_excerpt(), '<p>'); ?></p>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
		<footer class="v-hidden">
			<time class="published" datetime="2011-10-20T20:00:00+01:00">10/20/2011</time>
			<a class="author include" href="#editorial">Editorial</a>
		</footer>
	</article>
</div>

<?php get_footer(); ?>