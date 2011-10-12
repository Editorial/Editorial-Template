<?php
/**
 * Comments page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

session_start();

if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || count($_POST))
{
	echo 'ajax comments!';exit();
}

$riddle = Editorial::riddle();

// header settings
$EditorialId = 'feedback';
$EditorialClass = 'clear';
@include('header.php');
the_post(); global $post;
//update_option('comments_per_page', 0);
if (comments_open() || !post_password_required()) {
?>
<div class="content clear" role="main">
	<article id="single">
		<header>
			<h1><a href="<?php the_permalink(); ?>" rel="prev"><?php the_title(); ?></a></h1>
		</header>
		<?php
		// show comments
		if (have_comments())
		{
			// show notice & start section for comments
			echo '<p class="notice">'.Editorial::commentNotice().'</p><section id="comments">';

			// show comment
			wp_list_comments(array(
				'callback'          => 'Editorial::comment',
				'reverse_top_level' => true
			));

			echo '</section>';

			// show more link if we have paging enabled
			if (get_comment_pages_count() > 1 && get_option('page_comments') && get_next_comments_link())
			{
				printf('<section id="paging">
						<p><strong>%d / %d</strong> - %s</p>
						<p class="more"><a href="">%s</a></p>
					</section>',
					//$page+1 * $num,
					42,
					get_comments_number(),
					__('comments displayed', 'Editorial'),
					__('Display older comments ...', 'Editorial')
				);
			}
		}
		else
		{
			// show notice
			?>
			<p class="notice"><?php _e('<strong>There are no comments yet.</strong> Be first to leave your footprint here ...', 'Editorial'); ?></p>
			<?php
		}

		// has errors?
		$comment_name = $comment_email = $comment_url = $comment_content = '';
		$errors = array();
		if (isset($_SESSION['comment_errors']) && count($_SESSION['comment_errors']))
		{
			$errors = $_SESSION['comment_errors'];
			echo Editorial::formErrors($errors);

			// show originaly entered data
			$comment_name    = $_SESSION['post']['name'];
			$comment_email   = $_SESSION['post']['email'];
			$comment_url     = $_SESSION['post']['url'];
			$comment_content = $_SESSION['post']['comment'];

			// remove errors from session
			unset($_SESSION['comment_errors']);
			unset($_SESSION['post']);
		}

		?>
		<form id="comments-form" action="<?php echo Editorial::commentsLink(); ?>" method="post">
			<fieldset class="feedback">
				<legend class="v-hidden"><?php _e('Feedback', 'Editorial'); ?></legend>
				<ol>
					<li class="area<?php echo in_array('comment', $errors) ? ' error' : ''; ?>">
						<label for="comment"><?php _e('Comment', 'Editorial'); ?> <em>* <?php _e('required field', 'Editorial'); ?></em></label>
						<textarea id="comment" name="comment" cols="60" rows="9"><?php echo esc_attr($comment_content); ?></textarea>
					</li>
				</ol>
			</fieldset>
			<fieldset class="author">
				<legend class="v-hidden"><?php _e('Author', 'Editorial'); ?></legend>
				<ol>
					<li class="text<?php echo in_array('name', $errors) ? ' error' : ''; ?>">
						<label for="name"><?php _e('Your name', 'Editorial'); ?> <em>* <?php _e('required field', 'Editorial'); ?></em></label>
						<input type="text" id="name" name="name" value="<?php echo esc_attr($comment_name); ?>">
					</li>
					<li class="text<?php echo in_array('email', $errors) ? ' error' : ''; ?>">
						<label for="email"><?php _e('Your e-mail address', 'Editorial'); ?> <em>* <?php _e('required field', 'Editorial'); ?></em></label>
						<input type="email" id="email" name="email" value="<?php echo esc_attr($comment_email); ?>">
					</li>
					<li class="text<?php echo in_array('url', $errors) ? ' error' : ''; ?>">
						<label for="url"><?php _e('Link', 'Editorial'); ?></label>
						<input type="text" id="url" name="url" value="<?php echo esc_attr($comment_url); ?>">
					</li>
				</ol>
			</fieldset>
			<fieldset class="captcha">
				<legend class="v-hidden"><?php _e('Captcha', 'Editorial'); ?></legend>
				<ol>
					<li class="riddle<?php echo in_array('riddle', $errors) ? ' error' : ''; ?>">
						<label for="riddle"><?php echo $riddle['notice']; ?></label>
						<div class="qa">
							<span><?php echo $riddle['riddle']; ?></span>
							<input type="text" name="riddle" id="riddle">
							<em>* <?php _e('required field', 'Editorial'); ?></em>
						</div>
					</li>
				</ol>
			</fieldset>
			<fieldset class="submit">
				<?php comment_id_fields(); ?>
				<input type="submit" value="<?php _e('Publish', 'Editorial'); ?>">
			</fieldset>
		</form>
	</article>
	<?php Editorial::tabNavigation($post->ID, 'comments'); ?>
	<?php global $postId; $postId = $post->ID; get_template_part( 'loop', 'featured' ); ?>
</div>
<?php } @include('footer.php'); ?>
