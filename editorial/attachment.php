<?php
/**
 * Attachment page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// id depends on the type of the first posts image
$EditorialId = 'gallery';
$EditorialClass = 'clear';
$needsHTML5player = false;

// mobile devices are shown a different slideshow
if (Editorial::isMobileDevice())
{
    $htmlClass = "slideshow";
    $needsHTML5Player = true; // need html5 player by default for mobile content
}

the_post();
$parentId = $post->post_parent;
$attachmentsCount = count(get_children(array('post_parent'=>$parentId)));

// what kind of a attachment is it?
if (Editorial::is_image($post->post_mime_type))
{
    $imageMeta = wp_get_attachment_image_src($post->ID);
    if ($imageMeta[1] < $imageMeta[2])
    {
        // portrait
        $EditorialId = 'gallery-portrait';
    }
    $imageMeta = wp_get_attachment_image_src($post->ID, $EditorialId == 'gallery' ? 'landscape' : 'portrait');
    $imageMeta['alt'] = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
}
else
{
    $needsHTML5player = true;
    $attachmentUrl = wp_get_attachment_url();
}
$attachments = get_children(array('post_parent'=>$parentId));

// sort attachments
function sortAttachments($a, $b)
{
    if ($a->menu_order == $b->menu_order) return 0;
    return ($a->menu_order < $b->menu_order) ? -1 : 1;
}
uasort($attachments, 'sortAttachments');

// find current attachment in list
$previous = $next = $tmp = 0;
$currentPosition = $i = 1;
$found = false;
foreach ($attachments as $key => $attachment)
{
    if ($found)
    {
        $next = $attachment->ID;
        break;
    }

    if ($post->ID == $attachment->ID)
    {
        $previous = $tmp;
        $found = true;
        $currentPosition = $i;
    }

    $tmp = $attachment->ID;

    $i++;
}

@include('header.php');

// show mobile version of gallery
if (Editorial::isMobileDevice())
{
    ?>
    <section id="media-gallery">
        <header role="banner">
            <a href="<?php echo (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url'); ?>" id="logo-white"><img src="<?php echo Editorial::getOption('logo-gallery'); ?>" width="99" height="13" alt="<?php bloginfo('name'); ?>"></a>
            <nav id="remote" role="navigation">
                <ul>
                    <li><a href="#" id="m-prev" class="m-button"><span><?php _e('Previous', 'Editorial'); ?></span></a></li>
                    <li><a href="#" id="m-slide" class="m-button"><span><?php _e('Slideshow', 'Editorial'); ?></span></a></li>
                    <li><a href="#" id="m-next" class="m-button"><span><?php _e('Next', 'Editorial'); ?></span></a></li>
                </ul>
            </nav>
        </header>
        <img id="loading" src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/bgr/loading.gif" width="48" height="48" alt="<?php _e('Loading', 'Editorial'); ?>">
        <div id="media-elements">
            <?php

            $count = count($attachments);
            if ($count)
            {
                $i = 1;
                foreach ($attachments as $attachment)
                {
                    // handle video/audio
                    $media = '';
                    $src = wp_get_attachment_image_src($attachment->ID, 'full');
                    if (Editorial::is_image($attachment->post_mime_type))
                    {
                        $media = sprintf(
                            '<img src="%s" alt="%s">',
                            isset($src[0]) ? $src[0] : '',
                            $attachment->post_title
                        );
                    }
                    else if (Editorial::is_video($attachment->post_mime_type))
                    {
                        $src = wp_get_attachment_url($attachment->ID);
                        $media = sprintf('<video
                            width="612"
                            height="459"
                            src="%s"
                            type="%s"
                            id="player"
                            poster="@todo"
                            controls="controls"
                            preload="none"></video>',
                            $src,
                            $attachment->post_mime_type
                        );
                    }
                    else if (Editorial::is_audio($attachment->post_mime_type))
                    {
                        $src = wp_get_attachment_url($attachment->ID);
                        $media = sprintf('<audio
                            id="player"
                            src="%s"
                            type="%s"
                            controls="controls"></audio>',
                            $src,
                            $attachment->post_mime_type
                        );
                    }
                    printf('<figure id="element_%d"%s>
                            %s
                            <figcaption>
                                <h2>%d/%d</h2>
                                <h3>%s</h3>
                                <p>%s</p>
                                <a href="#" class="m-toggle m-button"><span>%s</span></a>
                                <a href="#" class="m-embed m-button"><span>%s</span></a>
                            </figcaption>
                        </figure>',
                        $i,
                        $i == 1 ? ' class="active"' : '',
                        $media,
                        $i,
                        $count,
                        $attachment->post_title,
                        $attachment->post_content,
                        __('Toggle', 'Editorial'),
                        __('Embed', 'Editorial')
                    );
                    $i++;
                }
            }

            ?>
        </div>
        <a href="<?php echo get_permalink($parentId); ?>" id="m-back" class="m-button"><span><?php _e('Back to article', 'Editorial'); ?></span></a>
    </section>
    <?php
} else {
    // show desktop version of gallery
    ?>
    <div class="content clear" role="main">
        <article id="single" class="hentry">
            <header>
                <h1 class="entry-title"><a href="<?php echo get_permalink($parentId); ?>" rel="prev"><?php echo get_the_title($parentId); ?></a></h1>
            </header>
            <section id="media">
                <figure>
                    <?php if (Editorial::is_image($post->post_mime_type)) { ?>
                    <span><img src="<?php echo $imageMeta[0]; ?>" class="photo" alt="<?php echo $imageMeta['alt']; ?>"></span>
                    <?php } else if (Editorial::is_audio($post->post_mime_type)) { ?>
                    <audio
                        id="player"
                        src="<?php echo $attachmentUrl ?>"
                        type="<?php echo $post->post_mime_type; ?>"
                        controls="controls"></audio>
                    <?php } else if (Editorial::is_video($post->post_mime_type)) { ?>
                    <video
                        width="612"
                        height="459"
                        src="<?php echo $attachmentUrl ?>"
                        type="<?php echo $post->post_mime_type; ?>"
                        id="player"
                        poster="@todo"
                        controls="controls"
                        preload="none"></video>
                    <?php } ?>
                    <figcaption<?php echo Editorial::is_video($post->post_mime_type) ? ' id="video-fc"' : ''; ?>>
                        <h3><?php the_title(); ?></h3>
                        <p><?php the_content(); ?></p>
                    </figcaption>
                </figure>
            </section>
            <aside role="complementary">
                <?php
                $previous = $attachments[$previous]->ID == $post->ID ? false : $attachments[$previous];
                $next     = $next ? $attachments[$next] : false;
                if ($previous || $next) {
                ?>
                <nav id="navigate" role="navigation">
                    <?php
                    if (count($attachments) > 1) { ?>
                        <h2><?php printf('%d/%d', $currentPosition, count($attachments)); ?></h2>
                        <?php
                    }
                    ?>
                    <ul>
                        <?php
                        if ($previous)
                        {
                            $imageMeta = wp_get_attachment_image_src($previous->ID, 'media-thumb');
                            ?>
                            <li class="previous">
                                <a href="<?php echo get_permalink($previous->ID); ?>" rel="prev">
                                    <img src="<?php echo $imageMeta[0]; ?>" alt="Media thumbnail">
                                    <?php _e('Previous', 'Editorial'); ?>
                                </a>
                            </li>
                            <?php
                        }
                        if ($next)
                        {
                            $imageMeta = wp_get_attachment_image_src($next->ID, 'media-thumb');
                            ?>
                            <li class="next">
                                <a href="<?php echo get_permalink($next->ID); ?>" rel="next">
                                    <img src="<?php echo $imageMeta[0]; ?>" alt="Media thumbnail">
                                    <?php _e('Next', 'Editorial'); ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </nav>
                <?php
                }
                ?>
                <fieldset id="embed">
                    <h4><label for="embed-code"><?php _e('Embed code', 'Editorial'); ?></label></h4>
                    <p><?php _e('There’s no need for downloading and uploading it to your blog/website when you can easily embed it.', 'Editorial'); ?></p>
                    <input id="embed-code" value="&lt;script type=&quot;text/javascript&quot; src=&quot;http://use.typekit.c&quot;&gt;">
                </fieldset>
            </aside>
        </article>
        <?php Editorial::tabNavigation($parentId, 'gallery'); ?>
        <?php $postId = $parentId; get_template_part( 'loop', 'featured' ); ?>
    </div>
    <?php
}
@include('footer.php');
?>