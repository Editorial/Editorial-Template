<?php
/**
 * Template Name: Help
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

get_header(); ?>

<div class="content" role="main">
	<article class="main">
		<h1><em>Help</em> &amp; support</h1>

<?php

if ( isset($_GET['question']) )
{
	$question = $wpdb->get_row(sprintf(
		'SELECT * FROM %sot_faq_questions WHERE id = %d',
		$wpdb->prefix,
		$_GET['question']
	));

	if ( null === $question )
	{
		require_once 'library/Util.php';
		Util::redirect('/faq');
	}
?>
<div class="read">
			<section class="hentry">
			<?php
				echo $question->answer;
			?>
			</section>
			<section>
				<h4>None of the above answers your question?</h4>
				<p class="notice">
					Then please feel free to use our
					<a href="https://editorialtemplate.uservoice.com/">Support forum</a>.
				</p>
			</section>
		</div>
		<aside class="related" role="complementary">
				<h4><em>Related questions</em></h4>
				<?php
					$category = $wpdb->get_row(sprintf(
						'SELECT * FROM %sot_faq_categories WHERE id = %d',
						$wpdb->prefix,
						$question->category
					));
				?>
				<p>This question is about “<?php echo $category->category; ?>”. You might want to take a look
				at some more questions from the same category.</p>
				<ol class="questions">
				<?php
					$questions = $wpdb->get_results(sprintf(
						'SELECT * FROM %sot_faq_questions WHERE category = %d',
						$wpdb->prefix,
						$category->id
					));
					foreach ( $questions as $question )
					{
						echo sprintf(
							'<li><a href="/faq/%d/%s">%s</a></li>',
							$question->id,
							sanitize_title($question->question),
							htmlspecialchars($question->question)
						);
					}
				?>
				</ol>
			</aside>
<?php
}
else
{
?>
		<section class="level">
			<h2><em>Frequently asked questions about Editorial</em></h2>
            <?php
                global $wpdb;

				$categories = $wpdb->get_results(sprintf(
					'SELECT * FROM %sot_faq_categories ORDER BY id',
					$wpdb->prefix
				));

				foreach ( $categories as $category )
				{
					$questions = $wpdb->get_results(sprintf(
						'SELECT * FROM %sot_faq_questions WHERE category = %d ORDER BY id',
						$wpdb->prefix,
						$category->id
					));

					if ( !count($questions) )
					{
						continue;
					}

					echo '
						<div class="group">
							<h3>' . $category->category . '</h3>
							<ol class="questions">
					';

					foreach ( $questions as $question )
					{
						echo sprintf(
							'<li><a href="/faq/%d/%s">%s</a></li>',
							$question->id,
							sanitize_title($question->question),
							htmlspecialchars($question->question)
						);
						// free it
						$question = null; unset($question);
					}

					echo '</ol></div>';

					// free it
					$category = null; unset($category);
				}

				// free it
				$categories = null; unset($categories);

            ?>
		</section>
		<section class="level">
			<h4>None of the above answers your question?</h4>
			<p class="notice">
				Then please feel free to use our
				<a href="https://editorialtemplate.uservoice.com/">Support forum</a>.
			</p>
		</section>
<?php
	}
?>
	</article>
</div>

<?php get_footer(); ?>