<?php
/**
 * Custom comment post
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

session_start();

if (isset($_POST) && count($_POST))
{
    // validate comment post
    $errors = array();
    $add = ''; // get appended at the end or url
    // validate name
    if (!array_key_exists('name', $_POST) || !strlen($_POST['name']))
    {
        $errors[] = 'name';
    }
    // validate email
    if (!array_key_exists('email', $_POST) || !is_email($_POST['email']))
    {
        $errors[] = 'email';
    }
    // validate url
    if (array_key_exists('url', $_POST) && strlen($_POST['url']) &&  !filter_var($_POST['url'], FILTER_VALIDATE_URL))
    {
        $errors[] = 'url';
    }
    // validate comment
    if (!array_key_exists('comment', $_POST) || !strlen($_POST['comment']))
    {
        $errors[] = 'comment';
    }
    // validate riddle
    if (!array_key_exists('comment', $_POST) || !strlen($_POST['riddle']) == 2)
    {
        $errors[] = 'riddle';
    }
    else
    {
        $riddle = $_SESSION['riddle']['chars'];
        $first  = strtoupper($_POST['riddle'][0]) == current($riddle);
        $second = strtoupper($_POST['riddle'][1]) == next($riddle);

        if (!$first || !$second)
        {
            $errors[] = 'riddle';
        }
    }
    // let wp have a go as well
    if (!count($errors) && !check_comment(
        $_POST['name'],
        $_POST['email'],
        $_POST['url'],
        $_POST['comment'],
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT'],
        'comment'
    ))
    {
        // wordpress doesn't like it
        $errors[] = 'wp';
    }

    if (count($errors))
    {
        // if we have any errors save to session
        $_SESSION['comment_errors'] = $errors;
        $_SESSION['post'] = $_POST;
        $add = '#errors';
    }
    else
    {
        $data = array(
            'comment_post_ID' => $_POST['comment_post_ID'],
            'comment_author' => $_POST['name'],
            'comment_author_email' => $_POST['email'],
            'comment_author_url' => $_POST['url'],
            'comment_content' => $_POST['comment'],
            'comment_type' => 'comment',
            'comment_parent' => 0,
            //'user_id' => 1,
            'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
            'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
            'comment_date' => current_time('mysql'),
            'comment_approved' => 1,
        );

        $id = wp_insert_comment($data);
        $add = sprintf('#comment-%d', $id);
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
            $data['comment'] = array(
                'name'    => $_POST['name'],
                'email'   => $_POST['email'],
                'url'     => $_POST['url'],
                'comment' => $_POST['comment'],
            );
        }
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