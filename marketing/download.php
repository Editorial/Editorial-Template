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
$update  = (bool)strstr($_SERVER['REQUEST_URI'], '/update');

// how did you get here?
if ( false === array_key_exists('hash', $_GET) && !isset($_GET['debug']) )
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
		<?php
			if ( $update )
			{
				echo '<h1><em>Up</em>date</h1>';
			}
			else
			{
		?>
			<ol class="step3">
				<li id="step1">Place order</li>
				<li id="step2">Transaction</li>
				<li id="step3" class="selected">Download</li>
			</ol>
			<h1><em>Down</em>load</h1>
		<?php
			}
		?>
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
				<a href="/documentation/">FAQ section</a>. We are also more than happy to answer your
				questions on twitter.
			</p>
		<?php
			}
			else
			{
				if ( $update )
				{
		?>
			<h2>Boy do we have a shiny update for you.</h2>
			<p class="leading">Please download and install this update. The update process is fairly simple
				but we recommend following our <a href="/documentation/">step by step guide</a> just in case.</p>
			<p class="help">
				You can read more about updates in our <a href="/documentation/">FAQ section</a>. You can also follow us on twitter
				for help and latest news on updates.
			</p>
		<?php
				} // if ( $update )
				else
				{
		?>
			<h2>Thank you for your purchase.</h2>
			<p class="leading">We wish you all the best with your project &amp; happy publishing with
			<a href="/" class="brand"><em>EDIT</em>ORIAL</a>.</p>
			<p class="help">If you need any help with instalation please see our <a href="/documentation/">FAQ section</a>.<br>
			And don’t forget to follow us on Twitter and tell us about your project.</p>
		<?php
				} // if ( $update)
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
			if ( !$update )
			{
			?>
			<p>Your download link will be active for 24 hours.</p>
			<?php
			} // if ( !$update )
			?>
		</div>
	</section>
<?php
	} // if ( in_array('404', $errors) )
?>
</div>

<?php get_footer(); ?>