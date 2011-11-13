<?php
/**
 * Domain model
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

/**
 * Domain model
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Domain
{

	/**
	 * Manage domains for account.
	 *
	 * @param  integer $account_id
	 * @param  array   $domains
	 * @return void
	 */
	public function manageForAccount($account_id, array $domains)
	{
		global $wpdb;
		// check them out
		foreach ( $domains as $domain )
		{
			$data = $wpdb->get_row(
				sprintf(
					'SELECT * FROM `domain` WHERE `account_id` = %d AND `name` = \'%s\'',
					$account_id,
					$wpdb->escape($domain)
				),
				ARRAY_A
			);
			// found, update last check
			if ( is_array($data) && count($data) )
			{
				// fingers crosse
				$wpdb->update(
					'domain',
					array(
						'last_check' => date('Y-m-d H:i:s'),
					),
					array(
						'domain_id' => $data['domain_id'],
					)
				);
			}
			// new one, add it
			else
			{
				$wpdb->insert(
					'domain',
					array(
						'account_id' => (int)$account_id,
						'name'       => $wpdb->escape($domain),
						'last_check' => date('Y-m-d H:i:s'),
					)
				);
			}
		}
    }

}