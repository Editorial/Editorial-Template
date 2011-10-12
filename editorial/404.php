<?php
/**
 * 404
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// id depends on the type of the first posts image
$EditorialId = 'notfound';
$EditorialClass = 'clear';
@include('header.php');

?>

<div class="content clear" role="main">
	<article>
		<header>
			<h1><?php _e('Oops!', 'Editorial'); ?></h1>
		</header>
		<h2>404</h2>
		<p id="try"><?php _e('The page you were looking for does not seem to exist. You might want to try search instead.', 'Editorial'); ?></p>
	</article>
</div>

<?php @include('footer.php'); ?>