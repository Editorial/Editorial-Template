<?php
/**
 * Index
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// id depends on the type of the first posts image
$EditorialId = 'home';
$posts = get_posts(array('numberposts' => 5));
if (count($posts))
{
	$Article = $posts[0];
	if (has_post_thumbnail($Article->ID))
	{
		$thumbId = get_post_thumbnail_id($Article->ID);
		$data = wp_get_attachment_image_src($thumbId, 'file');
		if ($data[1] < $data[2])
		{
			// portrait
			$EditorialId = 'home-portrait';
		}
	}
}

$EditorialClass = 'clear';
@include('header.php');

?>

<div class="content clear" role="main">
	<?php get_template_part( 'loop', 'posts' ); ?>
</div>
<?php @include('footer.php'); ?>