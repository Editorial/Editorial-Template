<?php
/**
 * Page
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// id depends on the type of the first posts image
$EditorialId = 'colophon';
$EditorialClass = 'clear';
@include('header.php');

?>

<div class="content clear" role="main">
    <article id="common" class="hentry">
        <header>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <section class="entry-content">
            <?php the_content(); ?>
        </section>
    </article>
</div>
<?php @include('footer.php'); ?>