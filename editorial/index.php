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
    <?php

    if (have_posts())
    {
        $exposed = false;
        $i = 1;
        // enter the Loop
        echo '<section id="exposed">';
        while ( have_posts() )
        {
            the_post();
            // skip posts without a thumb
            if (!has_post_thumbnail()) continue;
            $thumbId = get_post_thumbnail_id();
            if (!$exposed)
            {
                // show exposed
                $exposed = true;
                ?>
                <article class="hentry">
                    <div class="detail">
                        <?php Editorial::postFooter(); ?>
                        <?php Editorial::postHeader(); ?>
                        <?php Editorial::postExcerpt(); ?>
                    </div>
                    <?php Editorial::postFigure($thumbId, $EditorialId == 'home' ? 'landscape' : 'portrait'); ?>
                </article>
                </section><section class="featured">
                <?php
            }
            else
            {
                // show featured
                ?>
                <article class="f<?php echo $i; ?> hentry">
                    <?php Editorial::postFigure($thumbId, array(214, 214)); ?>
                    <div class="info">
                        <?php Editorial::postFooter(); ?>
                        <?php Editorial::postHeader(false); ?>
                    </div>
                    <?php Editorial::postExcerpt(); ?>
                </article>
                <?php
                $i++;
            }
        }
        echo '</section>';
    }
    else
    {
        // no posts -> error page?
        dump('no posts');
    }

    ?>
</div>
<?php @include('footer.php'); ?>