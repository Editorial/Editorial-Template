<?php
/**
 * Loop featured articles
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// find featured
global $postId;
$categories = get_the_category($postId);
if ($categories)
{
    ?>
    <section class="featured">
        <header>
            <h3><?php _e('You might also enjoy', 'Editorial'); ?></h3>
        </header>
        <?php
        $categoryIds = array();
        foreach($categories as $individual_category)
        {
            $category_ids[] = $individual_category->term_id;
        }

        $args=array(
            'category__in' => $category_ids,
            'post__not_in' => array($postId),
            'showposts'=>4,
            'caller_get_posts'=>1
        );
        $query = new wp_query($args);
        if( $query->have_posts() )
        {
            $i = 1;
            while ($query->have_posts())
            {
                $query->the_post();
                $thumbId = get_post_thumbnail_id();
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

        ?>
    </section>
    <?php
}