<?php
/**
 * Template Name: Download
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

require_once 'library/Purchase.php';

$errors = array();

// how did you get here?
if ( false === array_key_exists('hash', $_GET) )
{
	$errors[] = 'no-hash';
}
else
{
	$Purchase = new Purchase();
	$purchase = $Purchase->findByHash($_GET['hash']);
	// there is no purchase with this hash
	if ( null === $purchase )
	{
		$errors[] = 'invalid-hash';
	}
	// sorry, try next time
	elseif ( strtotime($purchase['date']) - time() < 0 )
	{
		$errors[] = 'invalid-date';
	}
	// download it
	elseif ( array_key_exists('start', $_GET) )
	{
		// required for some browsers
		if ( ini_get('zlib.output_compression') )
		{
			ini_set('zlib.output_compression', 'Off');
		}

		header('Pragma: public'); // required
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false); // required for certain browsers
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="' . basename(EDITORIAL_ZIP) . '";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '. filesize(EDITORIAL_ZIP));

		ob_clean();
		flush();
		readfile($zipfile);
		exit;
	}
}

get_header(); ?>

<div class="content" role="main">
<section class="process">
		<header>
			<ol class="step3">
				<li id="step1">Place order</li>
				<li id="step2">Transaction</li>
				<li id="step3" class="selected">Download</li>
			</ol>
			<h1><em>Down</em>load</h1>
		</header>
		<figure>
			<img src="<?php bloginfo('template_directory'); ?>/assets/images/tablets.png" alt="Tablets">
		</figure>
	</section>
	<section class="order">
		<?php
		if ( count($errors) )
		{
			echo '<section class="message errors">
			<h3><span class="v-hidden">Warning</span>!</h3>
			<p class="lead">Please correct following errors:</p>
			<ol>';

			echo '<li>hash: There be no hash</li>';
			echo '<li>invalid-hash: hash cannot be found in the database</li>';
			echo '<li>invalid-date: 24 hours access is gone</li>';

			echo '</ol></section>';
		}
		?>
		<div class="info">
			<h2>Thank you for your purchase.</h2>
			<p class="leading">We wish you all the best with your project &amp; happy publishing with
			<a href="/" class="brand"><em>EDIT</em>ORIAL</a>.</p>
			<p class="help">If you need any help with instalation please see our <a href="/help/">FAQ section</a>.<br>
			And don’t forget to follow us on Twitter and tell us about your project.</p>
			<p class="follow">
				<a href="http://twitter.com/editorialtheme" class="twitter-follow-button" data-show-count="false">Follow @editorialtheme</a>
				<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
			</p>
		</div>
		<div class="action">
			<a href="/download/?hash=<?php echo array_key_exists('hash', $_GET) ? $_GET['hash'] : ''; ?>&amp;start" class="download"><em>Download</em></a>
			<p>Your download link will be active for 24 hours.</p>
		</div>
	</section>
</div>

<?php get_footer(); ?>