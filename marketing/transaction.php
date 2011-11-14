<?php
/**
 * Template Name: Transaction
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

require_once 'library/Account.php';
require_once 'library/Paypal.php';
require_once 'library/Purchase.php';

session_start();

$errors = array();

$Purchase = new Purchase();
$purchase = $Purchase->findByExtId($_GET['token']);
if ( false === is_array($purchase) )
{
	$errors[] = '404';
}
else
{

try
{
	$Paypal      = new Paypal(PAYPAL_USER, PAYPAL_PASSWORD, PAYPAL_SIGNATURE, PAYPAL_ENDPOINT);
	$details     = $Paypal->getExpressCheckout($_GET['token']);
	debug(print_r($details, true));

	$transaction = $Paypal->doExpressCheckout($_GET['token'], $_GET['PayerID'], $purchase['amount']);
	debug(print_r($transaction, true));

	// create an account. or update it if it exists
	$Account = new Account();
	$account = $Account->createOrUpdate(array(
		'name'       => urldecode($details['FIRSTNAME'].' '.$details['LASTNAME']),
		'email'      => urldecode($details['EMAIL']),
		'address'    => urldecode($details['SHIPTOSTREET'] .' '.$details['SHIPTOCITY'].' '.$details['SHIPTOCOUNTRYNAME']),
		'country'    => urldecode($details['COUNTRYCODE']),
		'newsletter' => $purchase['newsletter'],
	));

	// update purchase
	$Purchase->update($purchase['purchase_id'], array(
		'ext_id'     => $transaction['TRANSACTIONID'],
		'date'       => date('Y-m-d H:i:s'),
		'status'     => Purchase::STATUS_CONFIRMED,
		'payer_id'   => $_GET['PayerID'],
		'account_id' => $account['account_id'],
		'hash'       => Util::randomString(32),
	));

	// try to see if payment is processed
	$id = $purchase['purchase_id'];
	$i  = 0;
	do
	{
		$Purchase = new Purchase();
		$purchase = $Purchase->findById($id);
		if ( $purchase['status'] == Purchase::STATUS_COMPLETED )
		{
			Util::redirect('/download/?hash=' . $purchase['hash']);
		}
		sleep(1);
		++$i;
	}
	while ( $i < 5 );
}
	catch ( Paypal_Exception $e )
	{
		Util::redirect('/purchase/?paypal&' . $_SERVER['QUERY_STRING']);
	}
}

// remove temp session
unset(
	$_SESSION['licences'],
	$_SESSION['domains'],
	$_SESSION['errors'],
	$_SESSION['agree'],
	$_SESSION['newsletter']
);

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
			<ol class="step1">
				<li id="step1">Place order</li>
				<li id="step2" class="selected">Transaction</li>
				<li id="step3">Download</li>
			</ol>
			<h1><em>Place</em> Order</h1>
		</header>
		<figure class="tablets">
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/tablets.png" alt="Tablets">
		</figure>
	</section>
	<section class="order">
		<div class="info">
			<h2>Your order has been successfully confirmed.</h2>
			<p class="leading">Sit back and relax while we wait for the transaction to be completed. After we receive
			your payment you will receive a download link to the e-mail associated with your PayPal account.</p>
			<p class="help">In the meanwhile you might want to take a look at <a href="/">frequently asked questions</a>
			section or drop us a line on twitter.</p>
			<p class="follow">
				<a href="http://twitter.com/editorialtheme" class="twitter-follow-button" data-show-count="false">Follow @editorialtheme</a>
				<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
			</p>
		</div>
		<div class="action">
			<figure>
				<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/dsg/transaction.png" width="160" height="205" alt="Transaction">
			</figure>
		</div>
	</section>
<?php
	} // if ( in_array('404', $errors) )
?>
</div>

<?php get_footer(); ?>
