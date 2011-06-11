<?php
/**
 * Single featured article
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */
?>
<article class="f<?php echo $i; ?> hentry">
    <?php Editorial::postFigure($thumbId, array(214, 214)); ?>
    <div class="info">
        <?php Editorial::postFooter(); ?>
        <?php Editorial::postHeader(false); ?>
    </div>
    <?php Editorial::postExcerpt(); ?>
</article>