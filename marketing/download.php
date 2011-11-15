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

$errors  = array();
$expired = false;

// how did you get here?
if ( false === array_key_exists('hash', $_GET) )
{
	$errors[] = '404';
}
else
{
	$Purchase = new Purchase();
	$purchase = $Purchase->findByHash($_GET['hash']);
	// there is no purchase with this hash
	if ( null === $purchase )
	{
		$errors[] = '404';
	}
	// sorry, try next time
	elseif ( strtotime($purchase['date']) - time() < 0 )
	{
		$errors[] = 'expired';
		$expired  = true;
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
		readfile(EDITORIAL_ZIP);
		exit;
	}
}

get_header(); ?>

<div class="content" role="main">
<?php
	if ( in_array('404', $errors) )
	{
?>
    <article class="main default hentry">
        <h1 class="entry-title"><em>Error</em> 404</h1>
        <p class="lead entry-summary">You seem to have lost you way around here.</p>
    </article>
<?php
	}
	else
	{
?>

<section class="process">
		<header>
			<ol class="step3">
				<li id="step1">Place order</li>
				<li id="step2">Transaction</li>
				<li id="step3" class="selected">Download</li>
			</ol>
			<h1><em>Down</em>load</h1>
		</header>
		<figure class="tablets">
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/tablets.png" alt="Tablets">
		</figure>
	</section>
	<section class="order">
		<div class="info">
		<?php
			if ( $expired )
			{
		?>
			<h2>Your purchase download has expired.</h2>
			<p class="leading">
				We sincerely apologise for the inconvenience. Please
				<a href="mailto:support@editorialtemplate.com?subject=Help!%20My%20download%20link%20has%20expired.%20My%20purchase%20ID%20is%20#">contact our support</a>
				to help you resolve this issue.
			</p>
			<p>
				To provide the highest level of security the download time is active only for 24
				hours from the transaction competition. You can read more about this issue in our
				<a href="/faq/">FAQ section</a>. We are also more than happy to answer your
				questions on twitter.
			</p>
		<?php
			}
			else
			{
		?>
			<h2>Thank you for your purchase.</h2>
			<p class="leading">We wish you all the best with your project &amp; happy publishing with
			<a href="/" class="brand"><em>EDIT</em>ORIAL</a>.</p>
			<p class="help">If you need any help with instalation please see our <a href="/faq/">FAQ section</a>.<br>
			And don’t forget to follow us on Twitter and tell us about your project.</p>
		<?php
			}
		?>
			<p class="follow">
				<a href="http://twitter.com/editorialtheme" class="twitter-follow-button" data-show-count="false">Follow @editorialtheme</a>
				<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
			</p>
		</div>
		<div class="action">
			<?php
			if ( $expired )
			{
				echo '<span class="download download-expired"><em>Download</em></span>';
			}
			else
			{
				echo '<a href="/download/?hash=' . (array_key_exists('hash', $_GET) ? $_GET['hash'] : '') .'&amp;start" class="download"><em>Download</em></a>';
			}
			?>
			<p>Your download link will be active for 24 hours.</p>
		</div>
	</section>
<?php
	} // if ( in_array('404', $errors) )
?>
</div>

<?php get_footer(); ?>