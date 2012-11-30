<?php
/**
 * Loop posts
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @version    1.0
 */

if (have_posts())
{
	global $EditorialId;
	$exposed = false;
	$i = 1;
	// enter the Loop
	echo '	<section id="exposed">
';
	while ( have_posts() )
	{
		the_post();
		// skip strange posts
		if (!$post) continue;
		$thumbId = get_post_thumbnail_id();
		if (!$exposed)
		{
			// show exposed
			$exposed = true;
?>
		<article class="hentry">
			<div class="detail">
<?php
				Editorial::postFooter();
?>
<?php
				Editorial::postHeader();
?>
				<?php Editorial::postExcerpt();?>
			</div>
<?php
			//Editorial::postFigure($thumbId, $EditorialId == 'home' ? 'landscape' : 'portrait', true);
			//$thumbnailUrl = Editorial::getResponsiveImageUrl ( $thumbId, 'full' );
?>
			<figure>
				<a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo Editorial::getResponsiveImageUrl ( $thumbId, 'full', (bool)Editorial::getOption('black-and-white') ) ?>" alt="<?php the_title(); ?>"></a>
			</figure>
		</article>
	</section>
	<section class="featured">
<?php
		}
		else
		{
			// show featured
			include('featured-article.php');
			$i++;
		}
	}
	echo '
	</section>';
}
else
{
	// no posts -> error page?
	dump('no posts');
}