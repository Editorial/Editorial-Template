<?php
/**
 * Single post page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

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
                <a href="/" id="to-gallery">
                    <img src="<?php echo $imageData[0]; ?>" alt="<?php the_title(); ?>" class="photo">
                    <em id="media-count">1/14</em>
                </a>
                <figcaption>
                    <h3>Futuristic bicycle concept</h3>
                    <p>Curabitur sit amet ligula non elit consectetur faucibus non et nulla. Duis eleifend, leo vel suscipit tempus,
                    justo massa sollicitudin felis, vel mollis lorem magna id dolor.</p>
                </figcaption>
            </figure>
        </section>
        <section class="entry-content">
            <?php the_content(); ?>
        </section>
    </article>
    <nav id="tabs" role="navigation">
        <ul>
            <li class="selected"><a href="/">Article</a></li>
            <li><a href="/">Image gallery</a></li>
            <li><a href="/">Feedback <em>128</em></a></li>
        </ul>
    </nav>
    <section class="featured">
        <header>
            <h3>You might also enjoy</h3>
        </header>
        <article class="f1 hentry">
            <figure>
                <a href="/" rel="bookmark"><img src="images/_temp/article-thumb-01.jpg" alt="Image description"></a>
            </figure>
            <div class="info">
                <footer>
                    <a href="/styling/" rel="tag">Styling</a>
                    <time class="published" pubdate datetime="2011-06-01T00:00">
                        <span class="value-title" title="2011-06-01T00:00"> </span>
                        1ST June
                    </time>
                    <em class="v-hidden author vcard">Written by <a href="/" class="fn n url">Natan Nikolič</a></em>
                </footer>
                <header>
                    <h2 class="entry-title">
                        <a href="/" rel="bookmark">Tilt-Shift Photography (Miniature Faking)</a>
                    </h2>
                </header>
            </div>
            <p class="entry-summary">Few months ago, I got a Kindle. It's a fascinating
            device, unlike almost any other launched by a significant tech company.</p>
        </article>
    </section>
</div>
<?php @include('footer.php'); ?>