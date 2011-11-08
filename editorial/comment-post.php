<?php
/**
 * Custom comment post
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

//require_once('./../../../wp-load.php');
require_once('/Users/miha/Projects/Editorial/wordpress/wp-load.php');
//require_once('/Users/matjazk/Webpages/Personal/editorial/wordpress/wp-load.php');

session_start();

// allow post only
Editorial::postOnly();

if (isset($_POST) && count($_POST))
{
	debug('has post');
	// validate comment post
	$errors = array();
	$errorFields = array();
	$add = ''; // get appended at the end or url
	$wpRejected = false;

	// make sure comment is for a valid blogpost
	$comment_post_ID = isset($_POST['comment_post_ID']) ? (int)$_POST['comment_post_ID'] : 0;
	$post = get_post($comment_post_ID);
	if (empty($post->comment_status))
	{
		// inform json of the error
		do_action('comment_id_not_found', $comment_post_ID);
		$errors[] = __('The requested comment was not found.', 'Editorial');
	}

	// get_post_status() will get the parent status for attachments.
	$status = get_post_status($post);
	$status_obj = get_post_status_object($status);
	if (!comments_open($comment_post_ID))
	{
		do_action('comment_closed', $comment_post_ID);
		$errors[] = __('Comments are closed. Sorry.', 'Editorial');
	}
	else if ('trash' == $status)
	{
		do_action('comment_on_trash', $comment_post_ID);
		$errors[] = __('Looks like you commented on an article that was trashed. Bad luck.', 'Editorial');
	}
	else if (!$status_obj->public && !$status_obj->private)
	{
		do_action('comment_on_draft', $comment_post_ID);
		$errors[] = __('Article is still a draft. No comments allowed.', 'Editorial');
	}
	else if (post_password_required($comment_post_ID))
	{
		do_action('comment_on_password_protected', $comment_post_ID);
		$errors[] = __('Commenting is password protected.', 'Editorial');
	}
	else
	{
		do_action('pre_comment_on_post', $comment_post_ID);
	}

	// set up empty vars
	$comment_author = $comment_author_email = $comment_author_url = $comment_content = $comment_type = $riddle = '';

	// load user
	$user = wp_get_current_user();

	// if user is loadeed preload default values
	if ($user->ID)
	{
		if (empty( $user->display_name ))
		{
			$user->display_name=$user->user_login;
		}
		global $wpdb, $user_ID;
		$comment_author       = $wpdb->escape($user->display_name);
		$comment_author_email = $wpdb->escape($user->user_email);
		$comment_author_url   = $wpdb->escape($user->user_url);
		if (current_user_can('unfiltered_html'))
		{
			if (isset($_POST['_wp_unfiltered_html_comment']) && wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'])
			{
				kses_remove_filters(); // start with a clean slate
				kses_init_filters(); // set up the filters
			}
		}
		else
		{
			if ( get_option('comment_registration') || 'private' == $status )
			{
				$errors[] = __('You have to login to comment on this article.', 'Editorial');
			}
		}
	}

	// validate name
	if (!$comment_author && (!array_key_exists('name', $_POST) || !strlen($_POST['name'])))
	{
		$errors[] = __('Please enter your name.', 'Editorial');
		$errorFields[] = 'name';
	}
	else
	{
		$comment_author = $comment_author ? $comment_author : trim(strip_tags($_POST['name']));
	}
	// validate email
	if (!$comment_author_email && (!array_key_exists('email', $_POST) || !is_email($_POST['email'])))
	{
		$errors[] = __('Please enter a valid email address.', 'Editorial');
		$errorFields[] = 'email';
	}
	else
	{
		$comment_author_email = $comment_author_email ? $comment_author_email : trim(strip_tags($_POST['email']));
	}
	// validate url
	if (!$comment_author_url && array_key_exists('url', $_POST) && strlen($_POST['url']) &&  !filter_var($_POST['url'], FILTER_VALIDATE_URL))
	{
		$errors[] = __('Please enter a valid link.', 'Editorial');
		$errorFields[] = 'url';
	}
	else
	{
		$comment_author_url = $comment_author_url ? $comment_author_url : trim(strip_tags($_POST['url']));
	}
	// validate comment
	if (!array_key_exists('comment', $_POST) || !strlen($_POST['comment']))
	{
		$errors[] = __('Please enter a comment', 'Editorial');
		$errorFields[] = 'comment';
	}
	else
	{
		$comment_content = trim($_POST['comment']);
	}
	// validate riddle
	if (!array_key_exists('comment', $_POST) || !strlen($_POST['riddle']) == 2)
	{
		$errors[] = __('Please enter correct riddle.', 'Editorial');
		$errorFields[] = 'riddle';
	}
	else
	{
		debug('validate riddle');
		$riddle = $_SESSION['riddle']['chars'];
		$first  = strtoupper($_POST['riddle'][0]) == current($riddle);
		$second = strtoupper($_POST['riddle'][1]) == next($riddle);

		if (!$first || !$second)
		{
			$errors[] = __('Please enter correct riddle.', 'Editorial');
			$errorFields[] = 'riddle';
		}
	}

	$comment; $i = 0;
	if (count($errors))
	{
		// if we have any errors save to session
		$_SESSION['comment_errors'] = $errors;
		$_SESSION['comment_error_fields'] = $errorFields;
		$_SESSION['post'] = $_POST;
		$add = '#errors';
	}
	else
	{
		$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
		$commentdata = compact(
			'comment_post_ID',
			'comment_author',
			'comment_author_email',
			'comment_author_url',
			'comment_content',
			'comment_type',
			'comment_parent',
			'user_ID'
		);
		debug(print_r($commentdata, true));
		$comment_id = wp_new_comment($commentdata);
		$comment = get_comment($comment_id);
		if ( !$user->ID ) {
			$comment_cookie_lifetime = apply_filters('comment_cookie_lifetime', 30000000);
			setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
			setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
			setcookie('comment_author_url_' . COOKIEHASH, esc_url($comment->comment_author_url), time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
		}
		$add = sprintf('#comment-%d', 12);
	}

	// ajax request?
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
	{
		// return json
		$data = array();
		if (count($errors))
		{
			// unset sessions
			unset($_SESSION['comment_errors']);
			unset($_SESSION['comment_error_fields']);
			unset($_SESSION['post']);
			$data['errors'] = $errors;
			$data['error_fields'] = $errorFields;
			$data['html'] = Editorial::formErrors($errors);
		}
		else
		{
			$data['html']    = Editorial::comment($comment, null, null, true);
			$data['notice']  = Editorial::commentNotice();
			$data['success'] = Editorial::formNotice(!$wpRejected);
		}
		// set new riddle
		$data['riddle'] = Editorial::riddle();
		echo json_encode($data);
		exit();
	}
	else
	{
		// redirect to same url
		header(sprintf('Location: %s%s', Editorial::commentsLink($_POST['comment_post_ID']), $add));
	}
}

exit();