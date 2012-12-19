<?php
/**
 * Single post page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

if (array_key_exists('comments', $_GET))
{
	@include('single-comments.php');
	exit();
}

the_post();

// id depends on the type of the first posts image
$EditorialId = 'inside';

if (has_post_thumbnail())
{
	$thumbId = get_post_thumbnail_id($post->ID);
	$data = wp_get_attachment_image_src($thumbId, 'file');
	if ($data[1] < $data[2])
	{
		// portrait
		$EditorialId = 'inside-portrait';
	}

	$thumbId = get_post_thumbnail_id();
	$imageData = wp_get_attachment_image_src($thumbId, $EditorialId == 'inside' ? 'landscape' : 'portrait');
	$thumbnailUrl = $imageData[0];
	$imageMeta = get_post($thumbId);
	$imageMeta->alt = get_post_meta($thumbId, '_wp_attachment_image_alt', true);
	$attachmentsCount = count(get_children(array('post_parent'=>$post->ID)));
	$attachmentUrl = get_attachment_link($thumbId);

	//$thumbnailUrl = Editorial::getResponsiveImageUrl ( $thumbId, 'full' );
}
else
{
	$thumbnailUrl = get_bloginfo('template_directory').'/images/no_image_big.png';
	$attachmentUrl = '#';
	$attachmentsCount = 0;
}

$EditorialClass = 'clear';
@include('header.php');

?>

<div class="content clear" role="main">
	<article id="single" class="hentry">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<section id="intro">
			<p class="entry-summary"><?php echo get_the_excerpt(); ?></p>
			<footer>
				<?php the_category(', ');
?>

				<time class="published" datetime="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>">
					<span class="value-title" title="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>"> </span>
					<?php the_time(get_option('date_format')); ?>

				</time>
				<em class="author vcard"><?php _e('by:', 'Editorial'); ?> <?php Editorial::authorLink(); ?></em>
<?php
				if (Editorial::isShareEnabled()) {
?>
				<ul class="social">
<?php

					if (Editorial::isShareEnabled(EDITORIAL_TWITTER))
					{
						echo '					<li class="twitter">'.Editorial::shareHTML(EDITORIAL_TWITTER, array(
							'text'   => get_the_title(),
							'url'    => get_permalink(),
							'width'  => 100,
							'height' => 20
						)).'</li>
';
					}
					if (Editorial::isShareEnabled(EDITORIAL_GOOGLE))
					{
						echo '					<li class="gplus">'.Editorial::shareHTML(EDITORIAL_GOOGLE, array(
							'text'   => get_the_title(),
							'url'    => get_permalink(),
						)).'</li>
';
					}
					if (Editorial::isShareEnabled(EDITORIAL_FACEBOOK))
					{
						echo '					<li class="facebook">'.Editorial::shareHTML(EDITORIAL_FACEBOOK, array(
							'text'   => get_the_title(),
							'url'    => get_permalink(),
							'width'  => 80,
							'height' => 20
						)).'</li>';
					}
					if (Editorial::isShareEnabled(EDITORIAL_READABILITY))
					{
						echo '					<li class="redability">'.Editorial::shareHTML(EDITORIAL_READABILITY).'</li>
';
					}

?>
				</ul>
<?php
				}
?>
			</footer>
		</section>
		
		<section id="media">
			<figure>
				<a href="<?php echo $attachmentUrl ?>" id="to-gallery">
					<img src="<?php echo $thumbnailUrl; ?>" alt="<?php echo $imageMeta->alt ?  $imageMeta->alt : $imageMeta->title; ?>" class="photo">
					<?php             if ($attachmentsCount > 1) {
					?>
					<em id="media-count">1/<?php echo $attachmentsCount; ?></em>
					<?php
					}
					?>
				</a>
				<?php if (isset($imageMeta)) { ?>
				<figcaption>
					<h3><?php echo $imageMeta->post_title; ?></h3>
					<p><?php echo $imageMeta->post_content; ?></p>
				</figcaption>
				<?php } ?>
			</figure>
		</section>
		
		<section class="entry-content">
			<?php the_content(); ?>
		</section>
	</article>
<?php
	Editorial::tabNavigation($post->ID, 'article');
?>
<?php
	global $postId; $postId = $post->ID; get_template_part( 'loop', 'featured' );
?>
</div>
<?php @include('footer.php'); ?>