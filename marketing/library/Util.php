<?php
/**
 * Util
 *
 * @package    Editorial
 * @subpackage Marketing
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */
class Util
{
    /**
     * Redirect
     *
     * @return void
     * @author Miha Hribar
     * @static
     */
    public static function redirect($url)
    {
        header('Location: '.$url);
        exit;
    }

    /**
     * Generates random string.
     *
     * @param      integer   $length
     * @param      string    $list
     * @return     string
     * @static
     * @author     Miha Hribar
     */
    public static function randomString($length, $list = 'abcdefghijklmnopqrstuvwxyz1234567890')
    {
        mt_srand((double)microtime()*1000000);
        $rv = '';
        while( strlen($rv) < $length )
        {
            $rv .= $list[mt_rand(0, strlen($list)-1)];
        }
        return $rv;
    }

    /**
     * Validate email
     *
     * @param string $email
     * @return bool
     */
    public static function validateEmail($email)
    {
        return preg_match('/^([^@]+)@([^\s@.]([^\s@.]*\.[^\s@.]+)+)$/', $email);
    }
}

?>