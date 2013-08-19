<?php
/**
 * Template Name: Full Width
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @version    1.0
 */

// id depends on the type of the first posts image
$EditorialId = 'inside';
$EditorialClass = 'clear';
@include( get_template_directory(). '/header.php');
the_post();

?>

<div class="content clear" role="main">
    <article id="single" class="hentry full-width">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <section id="intro">
            
            <footer>
                <?php the_category(', '); ?>
                <time class="published" datetime="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>">
                    <span class="value-title" title="<?php echo date('Y-m-dTH:i', strtotime($post->post_date)); ?>"> </span>
                    <?php the_time(get_option('date_format')); ?>
                </time>
                <em class="author vcard"><?php _e('Written by:', 'Editorial'); ?> <?php Editorial::authorLink(); ?></em>
<?php
                 if (Editorial::isShareEnabled()) {
?>
                <ul class="social">
<?php

                    if (Editorial::isShareEnabled(EDITORIAL_TWITTER))
                    {
                        echo '					<li class="twitter">'.Editorial::shareHTML(EDITORIAL_TWITTER).'</li>
';
                    }
                    if (Editorial::isShareEnabled(EDITORIAL_FACEBOOK))
                    {
                        echo '					<li class="facebook">'.Editorial::shareHTML(EDITORIAL_FACEBOOK, array(
                            'url'    => '',
                            'width'  => 100,
                            'height' => 20
                        )).'</li>';
                    }
                    if (Editorial::isShareEnabled(EDITORIAL_GOOGLE))
                    {
                        echo '					<li class="gplus">'.Editorial::shareHTML(EDITORIAL_GOOGLE).'</li>
';
                    }
                    if (Editorial::isShareEnabled(EDITORIAL_READABILITY))
                    {
                        echo '					<li class="redability">'.Editorial::shareHTML(EDITORIAL_READABILITY).'</li>
';
                    }

?>
                </ul>
<?php
                }

?>
            </footer>
        </section>
        <section class="entry-content">
            <?php the_content(); ?>
        </section>
    </article>
</div>
<?php @include('footer.php'); ?>