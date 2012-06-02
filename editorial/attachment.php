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
if (Editorial::isMobileDevice() || Editorial::isIpad())
{
?>

	<section id="media-gallery">
	<ul id="Gallery" class="gallery" style="display:none;" >
<?php
	$count = count($attachments);
	if($count) {
		$i = 1;
		foreach ($attachments as $attachment)
		{
			$media = '';
			$src = wp_get_attachment_image_src($attachment->ID, 'landscape');
			if (Editorial::is_image($attachment->post_mime_type))
			{
				$src = isset($src[0]) ? $src[0] : '';
				$media = sprintf(
					'<img src="%s" alt="%s">',
					$src,
					$attachment->post_title
				);
			}
			else if (Editorial::is_video($attachment->post_mime_type))
			{
				$src = wp_get_attachment_url($attachment->ID);
				$media = sprintf('<video
					src="%s"
					type="%s"
					id="player"
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
			printf('<li>
			<a href="%s" data-pos="%d" data-total="%d" data-title="%s" data-content="%s" data-permalink="%s">%s</a>
			</li>',
				$src,
				$i,
				$count,
				$attachment->post_title,
				$attachment->post_content,
				get_permalink($attachment->ID),
				$media
			);
			$i++;
		}
	}
?>
	</ul>
	</section>
	<script>
	(function(window, $, PhotoSwipe){
		$(function(){
				
				var options = {
					captionAndToolbarFlipPosition: true,
					allowUserZoom: false,
					loop: false,
					captionAndToolbarAutoHideDelay: 0,
					getToolbar: function(){
						return '<a href="<?php echo get_bloginfo('url'); ?>" id="logo-white"><img src="<?php echo Editorial::getOption('logo-gallery'); ?>" width="99" height="13" alt="<?php bloginfo('name'); ?>"></a>' +
						'<nav id="remote" role="navigation">' +
						'<div class="ps-toolbar-previous"><div id="m-prev" class="m-button "><span><?php _e('Previous', 'Editorial'); ?></span></div></div>' +
						'<div class="ps-toolbar-play"><div id="m-slide" class="m-button "><span><?php _e('Slideshow', 'Editorial'); ?></span></div></div>' +
						'<div class="ps-toolbar-next"><div id="m-next" class="m-button "><span><?php _e('Next', 'Editorial'); ?></span></div></div>' +
						'</nav>' +
						'<div class="ps-toolbar-close"><div class=" m-button" id="m-back"><span>Back</span> <b><?php _e('Back to article', 'Editorial'); ?></b><em><?php echo get_the_title($parentId); ?></em></div></div>';
					},
					getImageMetaData: function(el){
						return {
							href: el.getAttribute('href'),
							title: el.getAttribute('data-title'),
							content: el.getAttribute('data-content'),
							permalink: el.getAttribute('data-permalink'),
							position: el.getAttribute('data-pos'),
							total: el.getAttribute('data-total')
						}
					},
					getImageCaption: function(el) {
						meta_data = this.getImageMetaData(el);
						
						var cap_el, foo = '<figcaption>' +
							'<h2><span>' + meta_data.position + '</span>/<span>' + meta_data.total + '</span></h2>' +
							'<h3>' + meta_data.title + '</h3>' +
							'<p>' + meta_data.content + '</p>' +
							'<a  class="m-toggle m-button"><span>' + '<?php echo __('Toggle', 'Editorial'); ?>' + '</span></a>' +
							'<div class="mobile-embed"><input type="text" value="' + meta_data.permalink + '"></div>' +
							'<a class="m-embed m-button"><span>' + '<?php echo __('Toggle', 'Editorial'); ?>' + '</span></a>' +
						'</figcaption>';
						
						cap_el = $(foo);
						
						return cap_el;
					}
				};
				
				instance = PhotoSwipe.attach($("#media-gallery ul#Gallery a"), options);
				instance.show(0);
							
					// onToolbarTap
					instance.addEventHandler(PhotoSwipe.EventTypes.onToolbarTap, function(e){
						if(e.toolbarAction === 'close'){
							window.location.href = "<?php echo get_permalink($parentId); ?>";
						}
					});
				
			
			});
	}(window, window.jQuery, window.Code.PhotoSwipe));
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
				<span class="photo-adapt"><img src="<?php echo $imageMeta[0]; ?>" class="photo" alt="<?php echo $imageMeta['alt']; ?>"></span>
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
						<span><?php _e('Previous', 'Editorial'); ?></span>
					</li>
<?php
						}
						if ($previous)
						{
							$imageMeta = wp_get_attachment_image_src($previous->ID, 'media-thumb');
							$attchMimeType = get_post_mime_type($previous->ID);
							
							$thumb = $imageMeta[0];
							
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
							<?php if(Editorial::is_video($attchMimeType)){ ?><span><?php } ?><?php _e('Previous', 'Editorial'); ?><?php if(Editorial::is_video($attchMimeType)){ ?></span><?php } ?>
						</a>
					</li>
<?php
						}
						if ($next)
						{
							$imageMeta = wp_get_attachment_image_src($next->ID, 'media-thumb');
							$attchMimeType = get_post_mime_type($next->ID);
							
							$thumb = $imageMeta[0];

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
							<?php if(Editorial::is_video($attchMimeType)){ ?><span><?php } ?><?php _e('Next', 'Editorial'); ?><?php if(Editorial::is_video($attchMimeType)){ ?></span><?php } ?>
						</a>
					</li>
<?php
						}
									if ($previous && !$next)
						{
?>
					<li class="next disabled">
						<span><?php _e('Next', 'Editorial'); ?></span>
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