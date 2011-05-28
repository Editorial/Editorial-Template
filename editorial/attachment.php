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
the_post();
$parentId = $post->post_parent;
$attachmentsCount = count(get_children(array('post_parent'=>$parentId)));
$imageMeta = wp_get_attachment_image_src($post->ID);
if ($imageMeta[1] < $imageMeta[2])
{
    // portrait
    $EditorialId = 'gallery-portrait';
}
$imageMeta = wp_get_attachment_image_src($post->ID, $EditorialId == 'gallery' ? 'landscape' : 'portrait');
$imageMeta['alt'] = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
$attachments = get_children(array('post_parent'=>$parentId));
// sort attachments
function sortAttachments($a, $b)
{
    if ($a->menu_order == $b->menu_order) return 0;
    return ($a->menu_order < $b->menu_order) ? -1 : 1;
}
uasort($attachments, 'sortAttachments');
// find current attachment in list
$position = 0;
$previous = 0;
$next = 0;
foreach ($attachments as $key => $attachment)
{
    if ($position != 0)
    {
        $next = $key;
        break;
    }
    if ($post->ID == $attachment->ID)
    {
        $position = $attachment->menu_order;
    }
    if ($position == 0)
    {
        // save previous key
        $previous = $key;
    }
}

@include('header.php');
?>
<div class="content clear" role="main">
    <article id="single" class="hentry">
        <header>
            <h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="prev"><?php the_title(); ?></a></h1>
        </header>
        <section id="media">
            <figure>
                <span><img src="<?php echo $imageMeta[0]; ?>" class="photo" alt="<?php echo $imageMeta['alt']; ?>"></span>
                <figcaption>
                    <h3><?php the_title(); ?></h3>
                    <p><?php the_content(); ?></p>
                </figcaption>
            </figure>
        </section>
        <aside role="complementary">
            <header>
                <h2><?php printf('%d/%d', $position, count($attachments)); ?></h2>
            </header>
            <?php
            $previous = $attachments[$previous]->ID == $post->ID ? false : $attachments[$previous];
            $next     = $next ? $attachments[$next] : false;
            if ($previous || $next) {
            ?>
            <nav id="navigate" role="navigation">
                <ul>
                    <?php
                    if ($previous)
                    {
                        $imageMeta = wp_get_attachment_image_src($previous->ID, 'media-thumb');
                        ?>
                        <li class="previous">
                            <a href="<?php echo get_permalink($previous->ID); ?>" rel="prev">
                                <img src="<?php echo $imageMeta[0]; ?>" alt="Media thumbnail">
                                <?php _e('Previous image', 'Editorial'); ?>
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
                                <?php _e('Next image', 'Editorial'); ?>
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
                <h4><label for="embed-code">Embed code</label></h4>
                <p>There’s no need for downloading and uploading it to your blog/website when you can easily embed it.</p>
                <input id="embed-code" value="&lt;script type=&quot;text/javascript&quot; src=&quot;http://use.typekit.c&quot;&gt;">
            </fieldset>
        </aside>
    </article>
    <?php Editorial::tabNavigation('gallery'); ?>
    <?php Editorial::featured($parentId); ?>
</div>
<?php @include('footer.php'); ?>