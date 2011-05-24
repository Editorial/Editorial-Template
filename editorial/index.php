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

/*$posts = get_posts(array('numberposts' => 5));
foreach ($posts as $Article)
{
    if (has_post_thumbnail($Article->ID))
    {
        // get thumbnail
        $thumbId = get_post_thumbnail_id($Article->ID);
        dump(wp_get_attachment_image_src($thumbId, array(300, 300)));
        dump(the_excerpt());
    }
}*/

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
                $imageData = wp_get_attachment_image_src($thumbId, $EditorialId == 'home' ? 'landscape' : 'portrait');
                ?>
                <article class="hentry">
                    <div class="detail">
                        <footer>
                            <?php the_category(', '); ?>
                            <time class="published" pubdate datetime="<?php the_date('Y-m-dTH:i'); ?>">
                                <span class="value-title" title="<?php the_date('Y-m-dTH:i'); ?>"> </span>
                                <?php the_time(get_option('date_format')); ?>
                            </time>
                            <em class="v-hidden author vcard"><?php _e('Written by.', 'Editorial'); ?> <?php Editorial::authorLink(); ?></em>
                        </footer>
                        <header>
                            <h1 class="entry-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                            </h1>
                        </header>
                        <p class="entry-summary"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <figure>
                        <a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo $imageData[0]; ?>" alt="<?php the_title(); ?>"></a>
                    </figure>
                </article>
                </section><section class="featured">
                <?php
            }
            else
            {
                // show featured
                $imageData = wp_get_attachment_image_src($thumbId, array(214, 214));
                ?>
                <article class="f<?php echo $i; ?> hentry">
                    <figure>
                        <a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo $imageData[0]; ?>" alt="<?php the_title(); ?>"></a>
                    </figure>
                    <div class="info">
                        <footer>
                            <?php the_category(', '); ?>
                            <time class="published" pubdate datetime="<?php the_date('Y-m-dTH:i'); ?>">
                                <span class="value-title" title="<?php the_date('Y-m-dTH:i'); ?>"> </span>
                                <?php the_time(get_option('date_format')); ?>
                            </time>
                            <em class="v-hidden author vcard"><?php _e('Written by.', 'Editorial'); ?> <?php Editorial::authorLink(); ?></em>
                        </footer>
                        <header>
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                            </h2>
                        </header>
                    </div>
                    <p class="entry-summary"><?php echo get_the_excerpt(); ?></p>
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