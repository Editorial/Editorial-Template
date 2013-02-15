<?php
/**
 * Attachment page
 *
 * @package	Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link	   http://www.editorialtemplate.com
 * @version	1.0
 */

// id depends on the type of the first posts image
$EditorialId = 'gallery';
$EditorialClass = 'clear';
$needsHTML5player = false;
$isMobileGallery = isset($_GET['mobile']) ? true : false;

$translations = Editorial::getOption('translations');

// mobile devices are shown a different slideshow
if (Editorial::isMobileDevice() || Editorial::isIpad())
{
    $htmlClass = "slideshow";
    $needsHTML5Player = true; // need html5 player by default for mobile content
    $isMobileGallery = true;
}

the_post();
$parentId = $post->post_parent;
$attachments = get_children(array('post_parent'=>$parentId));
$attachmentsCount = count($attachments);

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

    $imageUrl = Editorial::getImage($post->ID, $EditorialId == 'gallery' ? 'landscape' : 'portrait', true);
}
else
{
    $needsHTML5player = true;
    $attachmentUrl = wp_get_attachment_url();
}

// which image is featured
$featuredId = get_post_thumbnail_id($parentId);
if (isset($attachments[$featuredId]))
{
    $attachments[$featuredId]->menu_order = -1;
}

// sort attachments
function sortAttachments($a, $b)
{
    if ($a->menu_order == $b->menu_order) return 0;
    return ($a->menu_order < $b->menu_order) ? -1 : 1;
}
uasort($attachments, 'sortAttachments');

// find current attachment in list
$previous = $next = $tmp = $found = false;
$currentPosition = $i = 1;
foreach (array_keys($attachments) as $key => $value)
{
    if ($found)
    {
        $next = $value;
        break;
    }
    if ($value == $post->ID)
    {
        $previous = $tmp;
        $currentPosition = $i;
        $found = true;
    }
    $tmp = $value;
    $i++;
}

@include('header.php');

// show mobile version of gallery
if ($isMobileGallery)
{
?>
<div id="viewporter"></div>
<script>
    var t = null;
    function onYouTubeIframeAPIReady() {
        t = new TouchGallery({
            container : '#viewporter',
            items	 : <?php
            $items = array();
            foreach ($attachments as $attachment)
            {
                $item   = array(
                    'src' => Editorial::getImage($attachment->ID, 'landscape'),
                    'type' => 'image',
                    'description' => $attachment->post_content,
                    'title' => $attachment->post_title,
                );
                if (Editorial::is_image($attachment->post_mime_type))
                {
                    $metadata = wp_get_attachment_metadata($attachment->ID);
                    if (isset($metadata['embed_type']) && isset($metadata['provider_name']) && isset($metadata['_wp_attachment_url']))
                    {
                        $url = $metadata['_wp_attachment_url'];
                        $provider = strtolower($metadata['provider_name']);
                        if ($provider == 'vimeo')
                        {
                            // extract id from url
                            $id = Editorial::getVimeoId($url);
                        }
                        else if ($provider == 'youtube')
                        {
                            $id = Editorial::getYoutubeId($url);
                        }
                        else
                        {
                            // skip it
                            continue;
                        }
                        unset($item['src']);
                        $item['type'] = $provider;
                        $item['id']   = $id;
                        $item['title'] = $attachment->post_title;
                        $item['description'] = $attachment->post_excerpt;
                    }
                }
                else if (Editorial::is_video($attachment->post_mime_type))
                {
                    $item['src']	   = wp_get_attachment_url($attachment->ID);
                    $item['posterImg'] = get_bloginfo('template_directory')."/images/mgallery_video1.png";
                    $item['type']	  = 'video';
                }
                else if (Editorial::is_audio($attachment->post_mime_type))
                {
                    $item['src']	   = wp_get_attachment_url($attachment->ID);
                    $item['posterImg'] = get_bloginfo('template_directory')."/images/mgallery_video1.png";
                    $item['type']	  = 'audio';
                }
                $items[] = $item;
            }
            echo json_encode($items);
            ?>,
            <?php printf("logo: '%s',", Editorial::getOption('logo-gallery')); ?>
            <?php printf("preloader: '%s/touchgallery/preloader.gif',", get_bloginfo('template_directory')); ?>
            <?php printf("backLink: '%s',", get_permalink($parentId)); ?>
            readyHandler: function() {
                // do nothing
            }
        });
    }
</script>
<?php
} else {
    // show desktop version of gallery
?>

<div class="content clear" role="main">
    <article id="single" class="hentry">
        <h1 class="entry-title"><a href="<?php echo get_permalink($parentId); ?>" rel="prev"><?php echo get_the_title($parentId); ?></a></h1>
        <section id="media">
            <figure>
<?php
                    $metadata = false;
                    if (Editorial::is_image($post->post_mime_type))
                    {
                        // check for metadata
                        $metadata = wp_get_attachment_metadata($attachment->ID);
                        if (isset($metadata['embed_type']) && isset($metadata['provider_name']) && isset($metadata['_wp_attachment_url']))
                        {
                            ob_start();
                            the_content();
                            $content = ob_get_contents();
                            preg_match('/width="(\d+)"/', $content, $matches);
                            if (count($matches) > 1)
                            {
                                $width = 628;
                                $ratio = $width/$matches[1];
                                $content = str_replace($matches[0], 'width="'.$width.'"', $content);

                                // find height
                                preg_match('/height="(\d+)"/', $content, $matches);
                                if (count($matches) > 1)
                                {
                                    $content = str_replace($matches[0], 'height="'.ceil($matches[1]*$ratio).'"', $content);
                                }
                            }
                            ob_end_clean();
                            echo $content;
                        }
                        else
                        {
                            printf(
                                '<span class="photo-adapt"><img src="%s" class="photo" alt="%s"></span>',
                                $imageUrl,
                                $imageMeta['alt']
                            );
                        }
                    }
                    else if (Editorial::is_audio($post->post_mime_type))
                    {
?>
                <audio id="player" src="<?php echo $attachmentUrl ?>" type="<?php echo $post->post_mime_type; ?>" controls="controls"></audio>
<?php
                    } else if (Editorial::is_video($post->post_mime_type)) {
?>
                <video width="100%" height="100%" src="<?php echo $attachmentUrl ?>" type="<?php echo $post->post_mime_type; ?>" id="player" controls="controls" preload="none"></video>
<?php
                    }
?>
                <figcaption<?php echo Editorial::is_video($post->post_mime_type) ? ' id="video-fc"' : ''; ?>>
                    <h3><?php the_title(); ?></h3>
                    <?php echo isset($metadata['embed_type']) ? get_the_excerpt() : get_the_content(); ?>
                </figcaption>
            </figure>
        </section>
        <aside role="complementary">
<?php
                $previous = $previous ? $attachments[$previous] : false;
                $next	 = $next ? $attachments[$next] : false;
                if ($previous || $next) {
?>
            <nav id="navigate" role="navigation">
<?php
                    if (count($attachments) > 1) {
?>
                <h2><?php printf('%d/%d', $currentPosition, count($attachments)); ?></h2>
<?php
                    }
?>
                <ul>
<?php
                if ($next && !$previous)
                        {
?>
                    <li class="previous disabled">
                        <strong><?php echo $translations['gallery']['FirstItem']; ?>This is the <em>first item</em></strong>
                        <span><?php echo $translations['gallery']['Previous']; ?></span>
                    </li>
<?php
                        }
                        if ($previous)
                        {
                            $attchMimeType = get_post_mime_type($previous->ID);
                            $thumb = Editorial::getImage($previous->ID, 'media-thumb');

?>
                    <li class="previous<?php if( Editorial::is_video($attchMimeType)){ echo " is-video"; } ?>">
                        <a href="<?php echo get_permalink($previous->ID); ?>" rel="prev">
<?php
                            if(!Editorial::is_video($attchMimeType)){
?>
                            <img src="<?php echo $thumb; ?>" alt="Media thumbnail">
<?php
                            }
?>
                            <?php if(Editorial::is_video($attchMimeType)){ ?><span><?php } ?><?php echo $translations['gallery']['Previous']; ?><?php if(Editorial::is_video($attchMimeType)){ ?></span><?php } ?>
                        </a>
                    </li>
<?php
                        }
                        if ($next)
                        {
                            $attchMimeType = get_post_mime_type($next->ID);
                            $thumb = Editorial::getImage($next->ID, 'media-thumb');

?>
                    <li class="next<?php if( Editorial::is_video($attchMimeType)){ echo " is-video"; } ?>">
                        <a href="<?php echo get_permalink($next->ID); ?>" rel="next">
<?php
                            if(!Editorial::is_video($attchMimeType)){
?>
                            <img src="<?php echo $thumb; ?>" alt="Media thumbnail">
<?php
                            }
?>
                            <?php if(Editorial::is_video($attchMimeType)){ ?><span><?php } ?><?php echo $translations['gallery']['Next']; ?><?php if(Editorial::is_video($attchMimeType)){ ?></span><?php } ?>
                        </a>
                    </li>
<?php
                        }
                                    if ($previous && !$next)
                        {
?>
                    <li class="next disabled">
                        <strong><?php echo $translations['gallery']['LastItem']; ?>This is the <em>last item</em></strong>
                        <span><?php echo $translations['gallery']['Next'];?></span>
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
                <h4><label for="embed-code"><?php echo $translations['gallery']['Embed code']; ?></label></h4>
                <p><?php echo $translations['gallery']['There is no need for downloading and uploading it to your blog/website when you can easily embed it.']; ?></p>
                <input id="embed-code" value="<?php echo isset($metadata['embed_type']) ? htmlspecialchars(get_the_content()) : get_permalink($post->ID); ?>">
            </fieldset>
        </aside>
    </article>
<?php
        Editorial::tabNavigation($parentId, 'gallery');
?>
<?php
        $postId = $parentId; get_template_part( 'loop', 'featured' );
?>
</div>

<script>var parentPageID = "<?php echo get_permalink($parentId); ?>";</script>
<?php
}
@include('footer.php');
?>