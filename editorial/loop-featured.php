<?php
/**
 * Loop featured articles
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @version    1.0
 */

// find featured
global $postId;
$categories = get_the_category($postId);
$translations = Editorial::getOption('translations');
if ($categories)
{
	$categoryIds = array();
	foreach($categories as $individual_category)
	{
		$category_ids[] = $individual_category->term_id;
	}

	$args=array(
		'category__in' => $category_ids,
		'post__not_in' => array($postId),
		'showposts' => 4,
		'ignore_sticky_posts' => 1,
	);
	$query = new wp_query($args);
	if( $query->have_posts() )
	{
?>
	<section class="featured">
		<h3><?php echo $translations['single_article']['You might also enjoy'];  ?></h3>
<?php
		$i = 1;
		while ($query->have_posts())
		{
			$query->the_post();
			$thumbId = get_post_thumbnail_id();
			include('featured-article.php');
			$i++;
}
		echo '	</section>
';
	}
}