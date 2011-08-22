<?php
/**
 * Comment vote (Karma)
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// allow post only
Editorial::postOnly();

if (isset($_POST) && count($_POST) == 1)
{
    // load comment
    foreach ($_POST as $key => $value)
    {
        // key is vote-id
        list($tmp, $id) = explode('-', $key);
        $comment = get_comment($id);

        // value is either +1 or -1
        $value = $value >= 1 ? 1 : -1;
        $json = array();
        if ($comment && $comment->comment_ID == $id)
        {
            // check cookie
            $cookie = '';
            if (isset($_COOKIE['vote']))
            {
                // explode value
                $cookie = $_COOKIE['vote'];
                $votes = explode(',', $_COOKIE['vote']);
                if (in_array($id, $votes))
                {
                    // already voted
                    $json['error'] = 'already_voted';
                    echo json_encode($json);
                }
            }

            // increment count
            $values = array(
                'comment_ID' => $comment->comment_ID,
                'comment_karma' => $comment->comment_karma + $value,
            );
            wp_update_comment($values);
            // set cookie
            $cookie = explode(',', $cookie);
            $cookie[] = $id;
            $cookie = implode(',', $cookie);
            setcookie(
                'vote',
                $cookie,
                time()+3600*24*31,
                '/',
                (defined('WP_SITEURL'))? WP_SITEURL : get_bloginfo('url')
            );
            // return json
            $json['ok'] = true;
            $json['votes'] = $values['comment_karma'];
            echo json_encode($json);
        }
    }
}

exit();