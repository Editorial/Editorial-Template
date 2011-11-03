<?php
/**
 * Category
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// id depends on the type of the first posts image
// @todo set cookie to save the change
$EditorialId = array_key_exists('list', $_GET) ? 'layout-list' : 'layout-grid';
//$posts = get_posts(array('numberposts' => 8));
$EditorialClass = 'clear';
@include('header.php');
$switchType = $EditorialId == 'layout-list' ? 'grid' : 'list';

?>

<div class="content clear" role="main">
	<article id="single">
		<h1><?php single_cat_title(); ?></h1>
		<section id="layout" class="clear">
			<p><?php _e('Select layout option', 'Editorial'); ?></p>
			<ul class="switch">
				<li<?php echo $EditorialId == 'layout-list' ? ' class="selected"' : ''; ?>><a href="?<?php echo $switchType; ?>" class="list"><?php _e('List', 'Editorial'); ?></a></li>
				<li<?php echo $EditorialId == 'layout-grid' ? ' class="selected"' : ''; ?>><a href="?<?php echo $switchType; ?>" class="grid"><?php _e('Grid', 'Editorial'); ?></a></li>
			</ul>
		</section>
	</article>
	<?php

	if (have_posts())
	{
		echo '<section class="featured">';
		$i = 1;
		while (have_posts())
		{
			the_post();
			$thumbId = get_post_thumbnail_id();
			include('featured-article.php');
			$i++;
		}
		echo '</section>';
	}
	else
	{
		//dump('No posts');
	}

	?>
</div>
<?php @include('footer.php'); ?>