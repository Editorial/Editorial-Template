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
	echo 'HereBe error?';
	exit;
}

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
		'name'    => urldecode($details['FIRSTNAME'].' '.$details['LASTNAME']),
		'email'   => urldecode($details['EMAIL']),
		'address' => urldecode($details['SHIPTOSTREET'] .' '.$details['SHIPTOCITY'].' '.$details['SHIPTOCOUNTRYNAME']),
		'country' => urldecode($details['COUNTRYCODE']),
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
	var_dump($e->getMessage());
	var_dump($e);
}

get_header(); ?>

<div class="content" role="main">
	<section class="process">
		<header>
			<ol class="step1">
				<li id="step1">Place order</li>
				<li id="step2" class="selected">Transaction</li>
				<li id="step3">Download</li>
			</ol>
			<h1><em>Place</em> Order</h1>
		</header>
		<figure>
			<img src="<?php bloginfo( 'template_directory' ); ?>/assets/images/tablets.png" alt="Tablets">
		</figure>
	</section>
	<section class="order">
		HereBe transaction
	</section>
</div>

<?php get_footer(); ?>
