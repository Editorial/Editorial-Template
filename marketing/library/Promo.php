<?php
/**
 * Promo model
 *
 * @category   Editorial
 * @package    Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */
 
/**
 * Promo model
 *
 * @package     Editorial
 * @author      Miha Hribar
 * @copyright   Copyright (c) 2011, ThirdFrameStudios
 * @link        http://www.thirdframestudios.com
 * @version     1.0
 */
class Promo
{
    /**
     * Insert.
     *
     * @param  array $data
     * @return void
     */
    public function insert(array $data)
    {
        global $wpdb;
        
        if (!array_key_exists('code', $data) || strlen(trim($data['code'])) == 0)
        {
            // generate code
            do
            {
                $code = $this->_generateCode();
            }
            while ($this->getDiscount($code) != 0);
            
            // set code
            $data['code'] = $code;
        }
        else
        {
            // validate code
            if ($this->getDiscount($data['code']) == 0)
            {
                throw new Exception('Code already exists or is invalid.');
            }
        }
        
        // validate discount amount
        if (!array_key_exists('discount', $data) || $data['discount'] <= 0 || $data['discount'] >= 100 || !ctype_digit($data['discount']))
        {
            throw new Exception('Discount amount invalid - must be between 0 and 100');
        }
        
        // validate count
        if (!array_key_exists('count', $data) || $data['count'] <= 0 || !ctype_digit($data['count']))
        {
            throw new Exception('Count invalid - must be larger than 0');
        }
        
        // validate date
        if (!array_key_exists('date_valid', $data) || strlen(trim($data['date_valid'])) == 0 || strtotime($data['date_valid']) == 0)
        {
            throw new Exception('Date invalid.');
        }
        
        
        return $wpdb->insert(
            'promo',
            $data
        );
    }
    
    /**
     * Get discount amount
     *
     * @return int
     * @author Miha Hribar
     */
    public function getDiscount($code)
    {
        global $wpdb;
        // check if promo code is valid
        $promo = $wpdb->get_row(sprintf(
            'SELECT * FROM promo WHERE code = "%s"',
            $wpdb->escape($code)
        ));
        // return the discount amount
        return $promo != false && strtotime($promo->date_valid) >= time() && $promo->count > 0 ? $promo->discount : 0;
    }
    
    /**
     * Use discount
     *
     * @return bool
     * @author Miha Hribar
     */
    public function useDiscount($code)
    {
        global $wpdb;
        // check if promo code is valid
        $promo = $wpdb->get_row(sprintf(
            'SELECT * FROM promo WHERE code = "%s"',
            $wpdb->escape($code)
        ));
        // if we have promo check if it is still available
        if ($promo != false && strtotime($promo->date_valid) >= time() && $promo->count > 0)
        {
            $wpdb->query(sprintf(
                'UPDATE promo SET used=used+1, count=count-1 WHERE promo_id = %d',
                $promo->promo_id
            ));
            return true;
        }
        return false;
    }
    
    /**
     * Find active promo codes
     *
     * @return array
     * @author Miha Hribar
     */
    public function findActive()
    {
    	global $wpdb;
        return $wpdb->get_results(
            'SELECT * FROM `promo` WHERE count > 0',
            ARRAY_A
        );
    }
    
    /**
     * Generate code
     *
     * @return string
     * @author Miha Hribar
     */
    private function _generateCode()
    {
        $code = uuid_create();
        return substr(str_replace('-', '', $code), 0, 10);
    }
}