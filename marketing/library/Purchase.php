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
	 * Find confirmed or completed payments
	 *
	 * @return array
	 * @author Miha Hribar
	 */
	public function findConfirmed()
	{
		global $wpdb;
		return $wpdb->get_results(
		    'SELECT * FROM `purchase` WHERE status = 1 OR status = 2',
            ARRAY_A
		);
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
				// send to team as well
				$sent = wp_mail("hello@editorialtemplate.com", $subject, $message);
			}
		}
	}
	
	/**
	 * Get number of templates sold.
	 *
	 * @return int
	 */
	public function getCount()
	{
		global $wpdb;
		$result = $wpdb->get_row(
			sprintf('SELECT SUM(domain_count) as c FROM purchase WHERE status = %d', self::STATUS_COMPLETED),
			ARRAY_A
		);
		return (int)current($result);
	}
	
	/**
	 * Get current price based on pricing system.
	 *
	 * @return int
	 */
	public function getPrice()
	{
		return $this->getPricingForCount($this->getCount());
	}
	
	/**
	 * Get pricing for count
	 *
	 * @param int $count
	 */
	public function getPricingForCount($count)
	{
		if ($count >= 0 && $count < 100)
		{
			return 10;
		}
		else if ($count >= 100 && $count < 300)
		{
			return 20;
		}
		else if ($count >= 300 && $count < 600)
		{
			return 30;
		}
		else if ($count >= 600 && $count < 1000)
		{
			return 40;
		}
		return 50;
	}
	
	/**
	 * Is there a pricing step at count -> see footer.php for graph display.
	 *
	 * @param int $count
	 * @return bool
	 */
	public function hasStepAtCount($count)
	{
		return $this->getPricingForCount($count) != $this->getPricingForCount($count-1);
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