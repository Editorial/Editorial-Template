<?php
/**
 * Comments page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @version    1.0
 */

session_start();
the_post(); global $post;

if (Editorial::isAjax())
{
    if (comments_open() || !post_password_required())
    {
        Editorial::noCacheHeader();
        output();
    }
    exit();
}

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
        if (Editorial::getCommentSystem() == Editorial::COMMENT_FACEBOOK)
        {
            // add fb comments (hacked to appear on this page)
            echo Facebook_Comments::the_content_comments_box('');
        }
        else
        {
            comments_template();
        }
        ?>
    </article>
<?php
    Editorial::tabNavigation($post->ID, 'comments');
?>
<?php
    global $postId; $postId = $post->ID; get_template_part( 'loop', 'featured' );
?>
</div>
<?php } @include('footer.php'); ?>