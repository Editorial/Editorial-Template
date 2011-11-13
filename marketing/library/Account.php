<?php
require_once 'Util.php';

/**
 * Account model
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

/**
 * Account model
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Account
{

	/**
	 * Try finding an account by email, if it exists, update values we have on
	 * record, if it doesn't, create a new one.
	 *
	 * @param  array $data
	 * @return array
	 */
	public function createOrUpdate(array $data = array())
	{
		// try finding eisting one
		$account = $this->find($data['email']);
		// yey, we have a winner
		if ( is_array($account) )
		{
			$this->update($account['account_id'], $data);
		}
		else
		{
			$this->insert($data);
		}
		// must ... not ... fail ...
		return $this->find($data['email']);
	}

	/**
	 * Find and account by email
	 *
	 * @param  string $email
	 * @return array|null
	 */
	public function find($email)
	{
		global $wpdb;
		return $wpdb->get_row(
			sprintf(
				'SELECT * FROM `account` WHERE `email` = \'%s\'',
				$wpdb->escape($email)
			),
			ARRAY_A
		);
	}

	/**
	 * Find and account by account ID
	 *
	 * @param  integer $account_id
	 * @return array|null
	 */
	public function findById($account_id)
	{
		global $wpdb;
		return $wpdb->get_row(
			sprintf(
				'SELECT * FROM `account` WHERE `account_id` = %d',
				$account_id
			),
			ARRAY_A
		);
	}

	/**
	 * Create an account.
	 *
	 * @param  array $data
	 * @return void
	 */
	public function insert(array $data)
	{
		global $wpdb;
		// make sure there's hash
		$data['hash'] = Util::randomString(32);
		// fingers crossed
		$wpdb->insert(
			'account',
			$data
		);
	}

	/**
	 * Update an account.
	 *
	 * @param  integer $account_id
	 * @param  array   $data
	 * @return void
	 */
	public function update($account_id, array $data)
	{
		global $wpdb;
		// hash must not change
		unset($data['hash']);
		// fingers crossed
		$wpdb->update(
			'account',
			$data,
			array(
				'account_id' => (int)$account_id,
			)
		);
	}

}