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
     * Get discount
     *
     * @return void
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
}