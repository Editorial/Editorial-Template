<?php
/**
 * Loop posts
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

if (have_posts())
{
    global $EditorialId;
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
            include('featured-article.php');
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