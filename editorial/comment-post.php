<?php
/**
 * Custom comment post
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

session_start();

// allow only post
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

if (isset($_POST) && count($_POST))
{
    debug('has post');
    // validate comment post
    $errors = array();
    $add = ''; // get appended at the end or url
    $wpRejected = false;

    // make sure comment is for a valid blogpost
    $comment_post_ID = isset($_POST['comment_post_ID']) ? (int)$_POST['comment_post_ID'] : 0;
    $post = get_post($comment_post_ID);
    if (empty($post->comment_status))
    {
        // inform json of the error
        do_action('comment_id_not_found', $comment_post_ID);
        $errors[] = 'comment_id_not_found';
    }

    // get_post_status() will get the parent status for attachments.
    $status = get_post_status($post);
    $status_obj = get_post_status_object($status);
    if (!comments_open($comment_post_ID))
    {
        do_action('comment_closed', $comment_post_ID);
        $errors[] = 'comment_closed';
    }
    else if ('trash' == $status)
    {
        do_action('comment_on_trash', $comment_post_ID);
        $errors[] = 'comment_on_trash';
    }
    else if (!$status_obj->public && !$status_obj->private)
    {
        do_action('comment_on_draft', $comment_post_ID);
        $errors[] = 'comment_on_draft';
    }
    else if (post_password_required($comment_post_ID))
    {
        do_action('comment_on_password_protected', $comment_post_ID);
        $errors[] = 'comment_on_password_protected';
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
        debug('User loaded '.$user->ID);
        if (empty( $user->display_name ))
        {
            $user->display_name=$user->user_login;
        }
        global $wpdb, $user_ID;
        $comment_author       = $wpdb->escape($user->display_name);
        $comment_author_email = $wpdb->escape($user->user_email);
        $comment_author_url   = $wpdb->escape($user->user_url);
        debug('Name:'.$comment_author.', email:'.$comment_author_email);
        if (current_user_can('unfiltered_html'))
        {
            if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] )
            {
                kses_remove_filters(); // start with a clean slate
                kses_init_filters(); // set up the filters
            }
        }
        else
        {
            if ( get_option('comment_registration') || 'private' == $status )
            {
                $errors[] = 'login_to_comment';
            }
        }
    }

    // validate name
    if (!$comment_author && (!array_key_exists('name', $_POST) || !strlen($_POST['name'])))
    {
        $errors[] = 'name';
    }
    else
    {
        $comment_author = $comment_author ? $comment_author : trim(strip_tags($_POST['name']));
    }
    // validate email
    if (!$comment_author_email && (!array_key_exists('email', $_POST) || !is_email($_POST['email'])))
    {
        $errors[] = 'email';
    }
    else
    {
        $comment_author_email = $comment_author_email ? $comment_author_email : trim(strip_tags($_POST['email']));
    }
    // validate url
    if (!$comment_author_url && array_key_exists('url', $_POST) && strlen($_POST['url']) &&  !filter_var($_POST['url'], FILTER_VALIDATE_URL))
    {
        $errors[] = 'url';
    }
    else
    {
        $comment_author_url = $comment_author_url ? $comment_author_url : trim(strip_tags($_POST['url']));
    }
    // validate comment
    if (!array_key_exists('comment', $_POST) || !strlen($_POST['comment']))
    {
        $errors[] = 'comment';
    }
    else
    {
        $comment_content = trim($_POST['comment']);
    }
    // validate riddle
    if (!array_key_exists('comment', $_POST) || !strlen($_POST['riddle']) == 2)
    {
        $errors[] = 'riddle';
    }
    else
    {
        debug('validate riddle');
        $riddle = $_SESSION['riddle']['chars'];
        $first  = strtoupper($_POST['riddle'][0]) == current($riddle);
        $second = strtoupper($_POST['riddle'][1]) == next($riddle);

        if (!$first || !$second)
        {
            $errors[] = 'riddle';
        }
    }

    $comment; $i = 0;
    if (count($errors))
    {
        // if we have any errors save to session
        $_SESSION['comment_errors'] = $errors;
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
            unset($_SESSION['post']);
            $data['errors'] = $errors;
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