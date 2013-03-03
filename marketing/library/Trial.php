<?php
/**
 * Trial model
 *
 * @category   Editorial
 * @package    Marketing
 * @author     Miha Hribar
 */

/**
 * Trial model
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @version     1.0
 */
class Trial
{
	/**
	 * Trial constants
	 */
	const TRIAL_INSERTED = 0;    // trial inserted and waiting for setup
	const TRIAL_STARTED  = 1;    // trial is in progress
	const TRIAL_REMINDED = 2;    // user was reminded of the trial after 7 days
	const TRIAL_ENDED    = 3;    // trial has ended, but we keep the record around

	/**
	 * Insert a new entry into the trial system
	 *
	 * @param  string $email
	 * @param  bool   $subscribe
	 * @return void
	 */
	public function insert($email, $subscribe = false)
	{
		global $wpdb;

		// @todo should probably limit to a sensible amount of retrys
		do
		{
			// generate a new id until we find one that isn't already used
			$id = $this->_generateRandomString();
		}
		while ($this->getTrial($id));

		// validate email
		if (!Util::validateEmail($email))
		{
			throw new Exception('Invalid email.');
		}
		
		// is currently in trial
		if ($this->isCurrentlyInTrial($email))
		{
			throw new Exception('Email is currently already in trial.');
		}

		// insert into mailchimp newsletter list
		if ($subscribe)
		{
			$api = new MCAPI(MAILCHIMP_API_KEY);
			$api->listSubscribe(MAILCHIMP_LIST_ID, $email);
		}

		return $wpdb->insert(
			'trial',
			array(
				'trial' => $id,
				'email' => $email,
				'date_created' => date('Y-m-d H:i:s'),
				'status' => self::TRIAL_INSERTED,
			)
		);
	}

	/**
	 * Get trial by id
	 *
	 * @return array|false
	 */
	public function getTrial($id)
	{
		global $wpdb;
		// get trial by id
		return $wpdb->get_row(sprintf(
			'SELECT * FROM trial WHERE trial = "%s"',
			$wpdb->escape($id)
		));
	}

	/**
	 * Has trial ended
	 *
	 * @return bool
	 */
	public function hasEnded($status)
	{
		return $status === self::TRIAL_ENDED;
	}

	/**
	 * Has trial ended
	 *
	 * @return bool
	 */
	public function wasInserted($status)
	{
		return $status === self::TRIAL_INSERTED;
	}

	/**
	 * Is trial in progress
	 *
	 * @return bool
	 */
	public function isInProgress($status)
	{
		return $status === self::TRIAL_STARTED || $status === self::TRIAL_REMINDED;
	}

	/**
	 * Check if email is currently already in trial, or waiting to start trial
	 *
	 * @param string $email
	 * @return bool
	 */
	public function isCurrentlyInTrial($email)
	{
		global $wpdb;
		// find email in trial mode
		$trial = $wpdb->get_row(sprintf(
			'SELECT * FROM trial WHERE email = "%s" AND status < %d',
			$wpdb->escape($email),
			self::TRIAL_ENDED
		));
		return (bool)$trial;
	}
	
	/**
	 * Find active trials
	 *
	 * @return array
	 */
	public function findInTrial()
	{
		global $wpdb;
		return $wpdb->get_results(
		    'SELECT * FROM `trial` WHERE status = 1 OR status = 2',
            ARRAY_A
		);
	}
	
	/**
	 * Generate a random string, starting with a character and not a number
	 * so we don't have any problems with legacy browsers
	 *
	 * @return string
	 * @author Miha Hribar
	 */
	private function _generateRandomString()
	{
		return Util::randomString(1, 'abcdefghijklmnopqrstuvwxyz').Util::randomString(4);
	}
}