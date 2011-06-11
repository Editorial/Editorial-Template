<?php
/**
 * Comments page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

session_start();

// catpcha translations
$translations = array(
    __('first',  'Editorial'),
    __('second', 'Editorial'),
    __('third',  'Editorial'),
    __('forth',  'Editorial'),
    __('fifth',  'Editorial'),
    __('sixth',  'Editorial'),
);
// captcha settings
$captcha = strtoupper(substr(md5(microtime()),0,6));
// select two random characters
$all = array(0,1,2,3,4,5);
$selected  = array_rand($all, 2);
$_SESSION['riddle'] = array(
    'captcha'  => $captcha,
    'chars'    => array(
        $selected[0] => $captcha[$selected[0]],
        $selected[1] => $captcha[$selected[1]]
    ),
);

// header settings
$EditorialId = 'feedback';
$EditorialClass = 'clear';
@include('header.php');
if (comments_open()) {
?>
<div class="content clear" role="main">
    <article id="single">
        <header>
            <h1><a href="<?php the_permalink(); ?>" rel="prev"><?php the_title(); ?></a></h1>
        </header>
        <?php
        // show comments
        if (get_comments_number() > 0)
        {
            // show comments
            ?>
            <p class="notice"><?php _e('<strong>Got something to add?</strong> You can just <a href="#comments-form"><em>leave a comment</em></a>.', 'Editorial'); ?></p>
            <section id="comments">
            <?php
            $comments = get_comments('post_id=' . $post->ID . '&status=approve');
            $i = count($comments);
            foreach ($comments as $comment)
            {
                ?>
                    <article class="hentry" id="comment-<?php echo $comment->comment_ID; ?>">
                        <section>
                            <footer>
                                <cite class="author vcard">
                                    <?php if ($comment->comment_author_url) { ?>
                                    <a href="<?php echo $comment->comment_author_url; ?>" rel="nofollow" class="fn n url" target="_blank"><?php echo $comment->comment_author; ?></a>
                                    <?php } else { echo $comment->comment_author; }?>
                                </cite>
                                <time class="published" pubdate datetime="<?php echo date('Y-m-dTH:i', strtotime($comment->comment_date)); ?>">
                                    <span class="value-title" title="<?php echo date('Y-m-dTH:i', strtotime($comment->comment_date)); ?>"> </span>
                                    <?php echo date(get_option('date_format'), strtotime($comment->comment_date)); ?>
                                </time>
                            </footer>
                            <aside role="complementary" class="favorize">
                                <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/images/_temp/favorize.png" alt="Temp replacement">
                            </aside>
                        </section>
                        <header>
                            <h2 class="entry-title"><span class="v-hidden"><?php _e('Feedback no.', 'Editorial'); ?></span> <?php echo $i--; ?>.</h2>
                        </header>
                        <blockquote class="entry-content">
                            <p><?php echo $comment->comment_content; ?></p>
                        </blockquote>
                    </article>
                <?php
            }
            echo '</section>';
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
        <form id="comments-form" action="<?php echo get_bloginfo('url'); ?>/comment-post.php" method="post">
            <fieldset class="feedback">
                <legend class="v-hidden"><?php _e('Feedback', 'Editorial'); ?></legend>
                <ol>
                    <li class="area<?php echo in_array('comment', $errors) ? ' error' : ''; ?>">
                        <label for="comment"><?php _e('Comment', 'Editorial'); ?></label>
                        <textarea id="comment" name="comment" cols="60" rows="9"><?php echo esc_attr($comment_content); ?></textarea>
                    </li>
                </ol>
            </fieldset>
            <fieldset class="author">
                <legend class="v-hidden"><?php _e('Author', 'Editorial'); ?></legend>
                <ol>
                    <li class="text<?php echo in_array('name', $errors) ? ' error' : ''; ?>">
                        <label for="name"><?php _e('Your name', 'Editorial'); ?></label>
                        <input type="text" id="name" name="name" value="<?php echo esc_attr($comment_name); ?>">
                    </li>
                    <li class="text<?php echo in_array('email', $errors) ? ' error' : ''; ?>">
                        <label for="email"><?php _e('Your e-mail address', 'Editorial'); ?></label>
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
                        <label for="riddle"><?php printf(__('Please enter the <strong>%s</strong> and <strong>%s</strong> character', 'Editorial'), $translations[$selected[0]], $translations[$selected[1]]); ?></label>
                        <div class="qa">
                            <span><?php echo $captcha; ?></span>
                            <input type="text" name="riddle" id="riddle">
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
    <?php $postId = $post->ID; get_template_part( 'loop', 'featured' ); ?>
</div>
<?php } @include('footer.php'); ?>
