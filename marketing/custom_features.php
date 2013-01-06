<?php
/*
Template Name: Features
*/

get_header();

/**
 * Build menu dynamicly from hierarchical pages
 */

$this_parent = $post->post_parent;
$this_id     = $post->ID;

/*
 * Check hierarchy and initialize approprietly
 */

if($this_parent == 0) // it's the overview page
{
	$icon_att      = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
	$current_icon  = $icon_att[0];
	$current_label = esc_html($post->post_title);

	// build nav array
	$features_nav[0]['url']   = get_permalink($post->ID);
	$features_nav[0]['icon']  = $current_icon;
	$features_nav[0]['label'] = $current_label;
	$features_nav[0]['class'] = 'selected';

	// page is parent - query for children
	$qargs['post_parent'] = $post->ID;
}
else // it's a subpage
{
	// first get parent
	$parent_post = get_post($this_parent);
	$icon_att = wp_get_attachment_image_src(get_post_thumbnail_id($this_parent), 'full');

	$features_nav[0]['url']   = get_permalink($this_parent);
	$features_nav[0]['icon']  = $icon_att[0];
	$features_nav[0]['label'] = esc_html($parent_post->post_title);
	$features_nav[0]['class'] = '';

	// page is child - query for siblings
	$qargs['post_parent'] = $this_parent;
}

/*
 * Now that we've built the first menu item, let's fetch children/siblings
 */

$qargs['post_type']      = 'page';
$qargs['posts_per_page'] = -1;
$qargs['orderby']        = 'menu_order';
$qargs['order']          = 'ASC';

$subitems = new WP_Query($qargs);

// Loop through and build the rest of the menu
$i1 = 1;
while($subitems->have_posts()) {
	$subitems->the_post();

	$icon_att = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

	// continue building nav array
	$features_nav[$i1]['url'] = get_permalink($post->ID);
	$features_nav[$i1]['icon'] = $icon_att[0];
	$features_nav[$i1]['label'] = esc_html($post->post_title);
	if($post->ID == $this_id) {
		$features_nav[$i1]['class'] = 'selected';
		$current_icon  = $icon_att[0];
		$current_label = esc_html($post->post_title);
	} else {
		$features_nav[$i1]['class'] = '';
	}

	$i1++;
}
wp_reset_query();
wp_reset_postdata(); // reset custom query and data to initial values
?>

<div class="content features-home-bg" role="main">
	<div class="adapt">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		<article class="main hentry">
			<nav class="features-links">
				<a id="show-features" href="#features-bar">
					<figure>
						<img src="<?php echo $current_icon; ?>" alt="<?php echo $current_label; ?>">
					</figure>
					<h4><em><?php echo $current_label; ?></em></h4>
				</a>
			</nav>
			<?php the_content(); ?>
			<footer class="v-hidden">
				<time class="published" pubdate datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
				<a class="author include" href="#brand">Editorial</a>
			</footer>
		</article>
		<?php endwhile; endif; ?>
		<aside id="features-bar">
			<?php if(isset($features_nav)) : ?>
				<nav class="features-nav" role="navigation">
					<ul>
						<?php foreach($features_nav as $item) : ?>
							<li class="<?php echo $item['class']; ?>">
								<a href="<?php echo $item['url']; ?>">
									<figure>
										<img src="<?php echo $item['icon']; ?>" alt="<?php echo $item['label']; ?>">
									</figure>
									<h4><?php echo $item['label']; ?></h4>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</aside>
	</div>
</div>

<?php get_footer(); ?>
