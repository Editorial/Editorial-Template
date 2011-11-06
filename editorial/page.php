<?php
/**
 * Page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

// id depends on the type of the first posts image
$EditorialId = 'inside';
$EditorialClass = 'clear';
@include('header.php');
the_post();

?>

<div class="content clear" role="main">
	<article id="single" class="hentry">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<section id="intro">
			<p class="entry-summary"><?php echo get_the_excerpt(); ?></p>
			<footer>
				<?php the_category(', '); ?>
				<time class="published" pubdate datetime="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>">
					<span class="value-title" title="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>"> </span>
					<?php the_time(get_option('date_format')); ?>
				</time>
				<em class="author vcard"><?php _e('Written by.', 'Editorial'); ?> <?php Editorial::authorLink(); ?></em>
				<?php if (Editorial::isShareEnabled()) { ?>
				<ul class="social">
					<?php

					if (Editorial::isShareEnabled(EDITORIAL_TWITTER))
					{
						echo '<li>'.Editorial::shareHTML(EDITORIAL_TWITTER).'</li>';
					}
					if (Editorial::isShareEnabled(EDITORIAL_FACEBOOK))
					{
						echo '<li>'.Editorial::shareHTML(EDITORIAL_FACEBOOK, array(
							'url'    => '',
							'width'  => 100,
							'height' => 20
						)).'</li>';
					}
					if (Editorial::isShareEnabled(EDITORIAL_GOOGLE))
					{
						echo '<li>'.Editorial::shareHTML(EDITORIAL_GOOGLE).'</li>';
					}
					if (Editorial::isShareEnabled(EDITORIAL_READABILITY))
					{
						echo '<li>'.Editorial::shareHTML(EDITORIAL_READABILITY).'</li>';
					}

					?>
				</ul>
				<?php } ?>
			</footer>
		</section>
		<section class="entry-content">
			<?php the_content(); ?>
		</section>
	</article>
</div>
<?php @include('footer.php'); ?>