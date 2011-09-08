<?php
/**
 * Search
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// id depends on the type of the first posts image
// @todo set cookie to save the change
$EditorialId = array_key_exists('list', $_GET) ? 'layout-list' : 'layout-grid';
//$posts = get_posts(array('numberposts' => 8));
$EditorialClass = 'clear';
@include('header.php');

?>

<div class="content clear" role="main">
    <article id="single">
        <header>
            <h1><em>“</em><?php echo get_search_query(); ?><em>”</em></h1>
        </header>
        <section id="layout" class="clear">
            <p><?php _e('Select layout option', 'Editorial'); ?></p>
            <menu>
                <li<?php echo $EditorialId == 'layout-list' ? ' class="selected"' : ''; ?>><a href="?list&s=<?php echo get_search_query(); ?>" class="list"><?php _e('List', 'Editorial'); ?></a></li>
                <li<?php echo $EditorialId == 'layout-grid' ? ' class="selected"' : ''; ?>><a href="?grid&s=<?php echo get_search_query(); ?>" class="grid"><?php _e('Grid', 'Editorial'); ?></a></li>
            </menu>
        </section>
    </article>
    <?php

    if (have_posts())
    {
        echo '<section class="featured">';
        $i = 1;
        while (have_posts())
        {
            the_post();
            $thumbId = get_post_thumbnail_id();
            include('featured-article.php');
            $i++;
        }
        echo '</section>';
    }
    else
    {
        //dump('No posts');
    }

    ?>
</div>
<?php @include('footer.php'); ?>