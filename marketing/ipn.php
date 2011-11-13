<?php
/**
 * Template Name: IPN
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

require_once 'library/Account.php';
require_once 'library/Domain.php';
require_once 'library/Paypal.php';
require_once 'library/Purchase.php';

if ( isset($_POST) && count($_POST) )
{
	debug(print_r($_POST, true));
	try
	{
		$Paypal = new Paypal(PAYPAL_USER, PAYPAL_PASSWORD, PAYPAL_SIGNATURE, PAYPAL_ENDPOINT);
		$Paypal->validateIPN($_POST, PAYPAL_IPN);
		// check the payment_status is Completed
		if ( $_POST['payment_status'] != 'Completed' )
		{
			throw new Paypal_Exception('Payment not completed');
		}
		// check that txn_id has not been previously processed
		$Purchase = new Purchase();
		$purchase = $Purchase->findByExtId($_POST['txn_id']);
		if (
			false === is_array($purchase)
		  ||
			!count($purchase)
		  ||
			$purchase['status'] != Purchase::STATUS_CONFIRMED
		)
		{
			throw new Paypal_Exception(sprintf(
				'Payment already completed %s',
				print_r($purchase, true)
			));
		}
		// check that receiver_email is your Primary PayPal email
		if ( $_POST['receiver_email'] != PAYPAL_EMAIL )
		{
			throw new Paypal_Exception('Payment not for this merchant');
		}
		// process purchase
		$Purchase->complete($purchase['purchase_id']);
		// send email with download link
		$Purchase->sendDownloadMail($purchase['purchase_id']);
	}
	catch ( Paypal_Exception $e )
	{
		debug(print_r($e->getMessage(),true));
	}
}