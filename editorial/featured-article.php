<?php
/**
 * Single featured article
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */
?>
		<article class="f<?php echo $i % 4 ? $i % 4 : 4; ?> hentry">
<?php
		Editorial::postFigure($thumbId, array(214, 214));
?>
			<div class="info">
<?php
				Editorial::postFooter();
?>
<?php
				Editorial::postHeader(false);
?>
			</div>
<?php
			Editorial::postExcerpt();
?>
		</article>
