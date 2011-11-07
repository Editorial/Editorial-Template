<?php
/**
 * Category
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
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
		$i = 1;
		$section = false;
		while (have_posts())
		{
		    // start section
		    if (($i-1) % 4 == 0)
		    {
		        $section = true;
		        echo '<section class="featured">';
		    }
			the_post();
			$thumbId = get_post_thumbnail_id();
			include('featured-article.php');
			// end section
			if ($i % 4 == 0)
			{
			    $section = false;
			    echo '</section>';
			}
			$i++;
		}
		if ($section)
		{
		    // close a previously opened section
		    echo '</section>';
		}
	}

	?>
</div>
<?php @include('footer.php'); ?>