<?php get_header(); ?>

<div class="content" role="main">
	<article class="main">
		<h1><em>Help</em> &amp; support</h1>

		<div class="read">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			<?php
				$post_cats = get_the_terms($post->ID, 'faqcat');
				foreach($post_cats as $term) {
					$this_cat_id = $term->term_id;
					$this_cat_name = $term->name;
				}
			?>
			<section class="hentry">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</section>
			<?php endwhile; endif; ?>
		</div>

		<aside class="related" role="complementary">
			<h4><em>Related questions</em></h4>
			<p>This question is about “<?php echo $this_cat_name; ?>”. You might want to take a look
			at some more questions from the same category.</p>
			<?php
				$related = get_posts(array(
					'posts_per_page' => 10,
					'post_type' => 'faq',
					'tax_query' => array(array(
						'taxonomy' => 'faqcat',
						'field' => 'id',
						'terms' => $this_cat_id
					)),
					'order' => 'ASC'
				));
			?>
			<ol class="questions">
				<?php foreach($related as $rel) : ?>
					<li><a href="<?php echo get_permalink($rel->ID); ?>"><?php echo $rel->post_title; ?></a></li>
				<?php endforeach; ?>
			</ol>
		</aside>

	</article>
</div>

<?php get_footer(); ?>
