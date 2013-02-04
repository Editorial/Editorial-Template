<?php get_header(); ?>

<div class="content" role="main">
	<article class="main">
		<h1><em>Help</em> &amp; support</h1>

		<?php
			// get FAQ categories
			$options = get_option('em_theme_options');
			if(isset($options['faqcats']) && !empty($options['faqcats'])) {
				$faqcatslugs = explode(',', $options['faqcats']);
				foreach($faqcatslugs as $faqcatslug) {
					$t = get_term_by('slug', trim($faqcatslug), 'faqcat');
					$faqcats[] = $t->term_id;
				}
			} else {
				$faqcats = wp_cache_get('faqcats');
				if($faqcats === false) {
					$faqcats = get_terms('faqcat', array(
						'orderby' => 'id',
						'fields'  => 'ids'
					));
					wp_cache_set('faqcats', $faqcats);
				}
			}


			// get posts from each category
			$faq_grouped_posts = wp_cache_get('faq_posts');
			if($faq_grouped_posts === false) {
				foreach($faqcats as $faqcat_id) {
					$faq_grouped_posts[$faqcat_id]['entries'] = get_posts(array(
						'posts_per_page' => -1,
						'post_type' => 'faq',
						'tax_query' => array(array(
							'taxonomy' => 'faqcat',
							'field' => 'id',
							'terms' => $faqcat_id
						)),
						'order' => 'ASC'
					));
					$term_title = get_term($faqcat_id, 'faqcat');
					$faq_grouped_posts[$faqcat_id]['title'] = $term_title->name;
				}
				wp_cache_set('faq_posts', $faq_grouped_posts);
			}
		?>
		<section class="level">
			<h2><em>Frequently asked questions about Editorial</em></h2>
			<?php foreach($faq_grouped_posts as $group) : ?>
				<div class="group">
					<h3><?php echo $group['title']; ?></h3>
					<ol class="questions">
						<?php foreach($group['entries'] as $entry) : ?>
							<li><a href="<?php echo get_permalink($entry->ID); ?>"><?php echo $entry->post_title; ?></a></li>
						<?php endforeach; ?>
					</ol>
				</div>

			<?php endforeach; ?>
		</section>

		<section class="level">
			<h4>None of the above answers your question?</h4>
			<p class="notice">Then please feel free to use our <a href="https://editorialtemplate.uservoice.com/">Support forum</a>.</p>
		</section>

	</article>
</div>

<?php get_footer(); ?>
