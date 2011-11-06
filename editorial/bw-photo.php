<?php
/**
 * Black and White cache page
 * --------------------------
 * This is where we cache black and white photos if they are enabled. They are
 * cached in the wp-content/cache folder.
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

//require_once('./../../../wp-load.php');
//require_once('/Users/miha/Projects/Editorial/wordpress/wp-load.php');
require_once('/Users/matjazk/Webpages/Personal/editorial/wordpress/wp-load.php');

if (!isset($_GET['photo']) || !isset($_GET['type']) || !Editorial::getOption('black-and-white') || !Editorial::canCache())
{
    bw_error();
}

// sanitize type
$type = array(214,214);
if ($_GET['type'] == 'l')
{
    $type = 'landscape'; 
}
else if ($_GET['type'] == 'p')
{
    $type = 'portrait';
}

// check if the photo is already cached
$imageData = wp_get_attachment_image_src((int)$_GET['photo'], $type);
if (!is_array($imageData) || !isset($imageData[0]))
{
    bw_error();
}

$image = strstr($imageData[0], '/uploads/');
$originalPath = WP_CONTENT_DIR.$image;
$grayscalePath = str_replace('/uploads/', '/editorial/', WP_CACHE_DIR.$image);
$extension = pathinfo($originalPath, PATHINFO_EXTENSION);

$allowed = array('jpg', 'jpeg', 'png', 'gif');

if (!in_array($extension, $allowed)) 
{
    bw_error();
}

// check if we already cached the image
if (!is_file($grayscalePath))
{
    // create path
    Editorial::createPath(dirname($grayscalePath));
    
    // grayscale image
    switch ($extension)
    {
        case 'jpg':
        case 'jpeg':
            $im = imagecreatefromjpeg($originalPath);
            break;
            
        case 'png':
            $im = imagecreatefrompng($originalPath);
            break;
            
        case 'gif':
            $im = imagecreatefromgif($originalPath);
            break;
    }
    
    imagefilter($im, IMG_FILTER_GRAYSCALE);
    
    switch ($extension)
    {
        case 'jpg':
        case 'jpeg':
            imagejpeg($im, $grayscalePath);
            break;
            
        case 'png':
            imagepng($im, $grayscalePath);
            break;
            
        case 'gif':
            imagegif($im, $grayscalePath);
            break;
    }
    unset($im);
}

// output file
if ($extension == 'jpg') $extension = 'jpeg';
header('Content-type: image/'.$extension);
readfile($grayscalePath);

function bw_error()
{
    // return 404
    header("HTTP/1.0 404 Not Found");
    exit();
}