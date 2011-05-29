<?php
/**
 * Single post page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// comments are part on the same page but on a different url (for now)
if (array_key_exists('comments', $_GET))
{
    @include('comments.php');
    exit();
}

// id depends on the type of the first posts image
$EditorialId = 'inside';

the_post();
if (has_post_thumbnail())
{
    $thumbId = get_post_thumbnail_id($Article->ID);
    $data = wp_get_attachment_image_src($thumbId, 'file');
    if ($data[1] < $data[2])
    {
        // portrait
        $EditorialId = 'inside-portrait';
    }
}
$EditorialClass = 'clear';
@include('header.php');
$thumbId = get_post_thumbnail_id();
$imageData = wp_get_attachment_image_src($thumbId, $EditorialId == 'inside' ? 'landscape' : 'portrait');
$imageMeta = get_post($thumbId);
$imageMeta->alt = get_post_meta($thumbId, '_wp_attachment_image_alt', true);
$attachmentsCount = count(get_children(array('post_parent'=>$post->ID)));

?>
<div class="content clear" role="main">
    <article id="single" class="hentry">
        <header>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <section id="intro">
            <p class="entry-summary"><?php echo get_the_excerpt(); ?> </p>
            <footer>
                <?php the_category(', '); ?>
                <time class="published" pubdate datetime="<?php the_date('Y-m-dTH:i'); ?>">
                    <span class="value-title" title="<?php the_date('Y-m-dTH:i'); ?>"> </span>
                    <?php the_time(get_option('date_format')); ?>
                </time>
                <em class="author vcard"><?php _e('Written by.', 'Editorial'); ?> <?php Editorial::authorLink(); ?></em>
                <ul class="social">
                    <li>
                        <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/twitter.png" width="92" height="20" alt="Twitter sample">
                    </li>
                    <li>
                        <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/facebook.png" width="92" height="20" alt="Facebook sample">
                    </li>
                </ul>
            </footer>
        </section>
        <section id="media">
            <figure>
                <a href="<?php echo get_attachment_link($thumbId); ?>" id="to-gallery">
                    <img src="<?php echo $imageData[0]; ?>" alt="<?php echo $imageMeta->alt ?  $imageMeta->alt : $imageMeta->title; ?>" class="photo">
                    <?php if ($attachmentsCount > 1) {?>
                    <em id="media-count">1/<?php echo $attachmentsCount; ?></em>
                    <?php } ?>
                </a>
                <figcaption>
                    <h3><?php echo $imageMeta->post_title; ?></h3>
                    <p><?php echo $imageMeta->post_content; ?></p>
                </figcaption>
            </figure>
        </section>
        <section class="entry-content">
            <?php the_content(); ?>
        </section>
    </article>
    <?php Editorial::tabNavigation($post->ID, 'article'); ?>
    <?php $postId = $post->ID; get_template_part( 'loop', 'featured' ); ?>
</div>
<?php @include('footer.php'); ?>