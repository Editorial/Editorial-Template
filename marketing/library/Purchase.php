<?php
/**
 * Purchase model
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

/**
 * Purchase model
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Purchase
{

	/**
	 * Type PayPal
	 */
	const TYPE_PAYPAL = 0;

	/**
	 * Status started
	 */
	const STATUS_STARTED   = 0;

	/**
	 * Status confirmed
	 */
	const STATUS_CONFIRMED = 1;

	/**
	 * Status completed
	 */
	const STATUS_COMPLETED = 2;

	/**
	 * Status cancelled
	 */
	const STATUS_CANCELLED = 3;

	/**
	 * Insert.
	 *
	 * @param  array $data
	 * @return void
	 */
	public function insert(array $data)
	{
		global $wpdb;
		return $wpdb->insert(
			'purchase',
			$data
		);
	}

	/**
	 * Find payment.
	 *
	 * @param  string  $id
	 * @return array|null
	 */
	public function findById($id)
	{
		return $this->_find('purchase_id', $id);
	}

	/**
	 * Find payment.
	 *
	 * @param  string  $ext_id
	 * @return array|null
	 */
	public function findByExtId($ext_id)
	{
		return $this->_find('ext_id', $ext_id);
	}

	/**
	 * Find purchase by hash.
	 *
	 * @param  string  $hash
	 * @return array|null
	 */
	public function findByHash($hash)
	{
		return $this->_find('hash', $hash);
	}

	/**
	 * Update a purchase.
	 *
	 * @param  integer $purchase_id
	 * @param  array   $data
	 * @return void
	 */
	public function update($purchase_id, array $data)
	{
		global $wpdb;
		// fingers crossed
		$wpdb->update(
			'purchase',
			$data,
			array(
				'purchase_id' => (int)$purchase_id,
			)
		);
	}

	/**
	 * Cancel purchase by ext_id.
	 *
	 * @param  string  $ext_id
	 * @return void
	 */
	public function cancel($ext_id)
	{
		global $wpdb;
		// fingers crosse
		$wpdb->update(
			'purchase',
			array(
				'status' => self::STATUS_CANCELLED,
			),
			array(
				'ext_id' => $wpdb->escape($ext_id),
			)
		);
	}

	/**
	 * Complete purchase.
	 *
	 * @param  integer $purchase_id
	 * @return void
	 */
	public function complete($purchase_id)
	{
		debug('purchase complete.');
		$this->update($purchase_id, array(
			'date'   => date('Y-m-d H:i:s', strtotime('+1 day')),
			'status' => self::STATUS_COMPLETED,
		));

		$purchase = $this->_find('purchase_id', $purchase_id);
		debug('purchase complete: ' . print_r($purchase, true));
		if ( isset($purchase['domains']) && null !== $domains = json_decode($purchase['domains'], true) )
		{
			debug('purchase complete domains: ' . print_r($domains, true));
			if ( is_array($domains) )
			{
				$Domain = new Domain();
				$Domain->manageForAccount($purchase['account_id'], $domains);
			}
		}
	}

	/**
	 * Send download mail.
	 *
	 * @param  integer $purchase_id
	 * @return void
	 */
	public function sendDownloadMail($purchase_id)
	{
		debug('preparing to send an email.');
		$purchase = $this->_find('purchase_id', $purchase_id);
		if ( isset($purchase['account_id']) )
		{
			$Account = new Account();
			$account = $Account->findById($purchase['account_id']);
			debug('account: ' . print_r($account, true));
			if ( isset($account['email']))
			{
				$subject = 'Your Editorial Wordpress theme is ready for download.';
				$message = 'We have great news. Your transaction has been completed and your Editorial theme is ready for download.' . PHP_EOL
						 . 'Thank you for your patience. ' . PHP_EOL
						 . PHP_EOL
						 . site_url('/download/?hash=' . $purchase['hash']) . PHP_EOL
						 . PHP_EOL
						 . 'You can edit your licenses by using the domain manager. '
						 . 'Just make sure you store this e-mail safely and privately to avoid loosing this uniquely generated link to it: '
						 . site_url('/manager/?hash=' . $account['hash'])
				;
				// send it
				$sent = wp_mail($account['email'], $subject, $message);
				debug('email sent: ' . (int)$sent);
			}
		}
	}

	/**
	 * Find row.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 */
	private function _find($key, $value)
	{
		global $wpdb;
		return $wpdb->get_row(
			sprintf(
				'SELECT * FROM `purchase` WHERE `%s` = \'%s\'',
				$key,
				$wpdb->escape($value)
			),
			ARRAY_A
		);
	}

}