<?php
/**
 * Attachment page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

// id depends on the type of the first posts image
$EditorialId = 'gallery';
$EditorialClass = 'clear';
$needsHTML5player = false;
$isMobileGallery = true;

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
            items     : [
			<?php 
			$items = array();
			foreach ($attachments as $attachment)
			{
				$src    = Editorial::getImage($attachment->ID, 'landscape');
				$poster = $src;
				$type   = 'image';
				if (Editorial::is_image($attachment->post_mime_type))
				{
					$poster = $src;
					$type   = 'image';
				}
				else if (Editorial::is_video($attachment->post_mime_type))
				{
					$src    = wp_get_attachment_url($attachment->ID);
					$poster = get_bloginfo('template_directory')."/images/mgallery_video1.png";
					$type   = 'video';
				}
				else if (Editorial::is_audio($attachment->post_mime_type))
				{
					$src    = wp_get_attachment_url($attachment->ID);
					$poster = get_bloginfo('template_directory')."/images/mgallery_video1.png";
					$type   = 'video';
				}
				// @todo check attachments
				$items[] = sprintf("{ src: '%s', type: '%s', description: '' }", $src, $type);
			}
			echo implode(', ', $items);
			?>
            ],
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
                    if (Editorial::is_image($post->post_mime_type)) {
?>
                <span class="photo-adapt"><img src="<?php echo $imageUrl ?>" class="photo" alt="<?php echo $imageMeta['alt']; ?>"></span>
<?php
                    } else if (Editorial::is_audio($post->post_mime_type)) {
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
                    <?php the_content(); ?>
                </figcaption>
            </figure>
        </section>
        <aside role="complementary">
<?php
                $previous = $previous ? $attachments[$previous] : false;
                $next     = $next ? $attachments[$next] : false;
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
                <input id="embed-code" value="<?php echo get_permalink($post->ID); ?>">
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