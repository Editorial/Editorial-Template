<?php
/**
 * Comments page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

session_start();
the_post(); global $post;

if (Editorial::isAjax())
{
    if (comments_open() || !post_password_required()) 
    {
        dump('TODO!');
    	//Editorial::noCacheHeader();
    	//output();
    }
	exit();
}

$riddle = Editorial::riddle();

// header settings
$EditorialId = 'feedback';
$EditorialClass = 'clear';
@include('header.php');
if (comments_open() || !post_password_required()) {
?>

<div class="content clear" role="main">
	<article id="single">
		<h1><a href="<?php the_permalink(); ?>" rel="prev"><?php the_title(); ?></a></h1>
<?php

		// show comments
		if (have_comments())
		{
			// show notice & start section for comments
			echo '<p class="notice">'.Editorial::commentNotice().'</p>
			<section id="comments">';
			
			$page = isset($_GET['page']) ? $_GET['page'] : 1;

			if (get_option('comment_order') == 'asc')
			{
			    Editorial::$commentCounter = 1;
			}
			else
			{
    			Editorial::$commentCounter = $post->comment_count - get_option('comments_per_page') * ($page-1);
			}
			
			// comment settings
			$settings = array(
				'callback'          => 'Editorial::comment',
				'end-callback'      => 'Editorial::endComment',
				//'reverse_top_level' => true,
			    'page'              => $page,
			);

			// show comment
			wp_list_comments($settings);
			
			$commentPages = get_comment_pages_count(); 

			echo '
		</section>
		';

			// show more link if we have paging enabled
			if ($commentPages > 1 && $commentPages > $page && get_option('page_comments'))
			{
				$comments = get_comments_number();
				printf('<section id="paging">
						<p><strong>%d / %d</strong> - %s</p>
						<p class="more"><a href="?comments&page=%d">%s</a></p>
					</section>',
					$page * get_option('comments_per_page') > $comments ? $comments : $page * get_option('comments_per_page'),
					$comments,
					__('comments displayed', 'Editorial'),
					$page+1,
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
			$error_fields = $_SESSION['comment_error_fields'];
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
		
		if (comments_open())
		{
		
?>
		<form id="comments-form" action="<?php echo get_bloginfo('template_url'); ?>/comment-post.php" method="post">
			<fieldset class="feedback">
				<legend class="v-hidden"><?php _e('Feedback', 'Editorial'); ?></legend>
				<ol>
					<li class="area<?php echo in_array('comment', $error_fields) ? ' error' : ''; ?>">
						<label for="comment"><?php _e('Comment', 'Editorial'); ?> <em>* <?php _e('required field', 'Editorial'); ?></em></label>
						<textarea id="comment" name="comment" cols="60" rows="9"><?php echo esc_attr($comment_content); ?></textarea>
					</li>
				</ol>
			</fieldset>
			<fieldset class="author">
				<legend class="v-hidden"><?php _e('Author', 'Editorial'); ?></legend>
				<ol>
					<li class="text<?php echo in_array('name', $error_fields) ? ' error' : ''; ?>">
						<label for="name"><?php _e('Your name', 'Editorial'); ?> <em>*</em></label>
						<input type="text" id="name" name="name" value="<?php echo esc_attr($comment_name); ?>">
					</li>
					<li class="text second<?php echo in_array('email', $error_fields) ? ' error' : ''; ?>">
						<label for="email"><?php _e('Your e-mail address', 'Editorial'); ?> <em>*</em></label>
						<input type="email" id="email" name="email" value="<?php echo esc_attr($comment_email); ?>">
					</li>
					<li class="text<?php echo in_array('url', $error_fields) ? ' error' : ''; ?>">
						<label for="url"><?php _e('Link', 'Editorial'); ?></label>
						<input type="text" id="url" name="url" value="<?php echo esc_attr($comment_url); ?>">
					</li>
				</ol>
			</fieldset>
			<fieldset class="captcha">
				<legend class="v-hidden"><?php _e('Captcha', 'Editorial'); ?></legend>
				<ol>
					<li class="riddle<?php echo in_array('riddle', $error_fields) ? ' error' : ''; ?>">
						<label for="riddle"><?php echo $riddle['notice']; ?> <em>*</em></label>
						<div class="qa">
							<span><?php echo $riddle['riddle']; ?></span>
							<input type="text" name="riddle" id="riddle">
						</div>
					</li>
				</ol>
			</fieldset>
			<fieldset class="submit">
<?php
				comment_id_fields();
?>
				<input type="submit" value="<?php _e('Publish', 'Editorial'); ?>">
			</fieldset>
		</form>
    <?php } ?>
	</article>
<?php
	Editorial::tabNavigation($post->ID, 'comments');
?>
<?php
	global $postId; $postId = $post->ID; get_template_part( 'loop', 'featured' );
?>
</div>
<?php } @include('footer.php'); ?>
