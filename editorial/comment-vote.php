<?php
/**
 * Comment vote (Karma)
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @version    1.0
 */

require_once('./../../../wp-load.php');

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
            $cookie = array();
            if (isset($_COOKIE['vote']))
            {
                // explode value
                $cookie = explode(',', $_COOKIE['vote']);
                if (Editorial::alreadyVoted($id))
                {
                    // already voted
                    $json['error'] = 'already_voted';
                    echo json_encode($json);
                    exit();
                }
            }

            // increment count
            $values = array(
                'comment_ID' => $comment->comment_ID,
                'comment_karma' => $comment->comment_karma + $value,
            );
            wp_update_comment($values);

            // set cookie
            $cookie[] = $id;
            $cookie = implode(',', $cookie);
            debug($cookie);
            setcookie(
                'vote',
                $cookie,
                time()+3600*24*31,
                COOKIEPATH,
                COOKIE_DOMAIN
            );

            // return json
            $json = array(
                'ok'    => true,
                'votes' => $values['comment_karma'] > 0 ? '+'.$values['comment_karma'] : $values['comment_karma'],
                'id'    => $comment->comment_ID,
            );
            echo json_encode($json);
        }
    }
}

exit();