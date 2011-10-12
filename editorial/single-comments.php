<?php
/**
 * Single post comments - just calls loop
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || count($_POST))
{
    echo 'ajax single comments!';exit();
}

comments_template();

?>