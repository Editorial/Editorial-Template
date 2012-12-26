<?php
/**
 * responsive image cache page
 * --------------------------
 * This script serves a requested image in requested dimensions.
 * They are cached in the wp-content/cache folder.
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2012, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Jan Hancic
 * @version    1.0
 */

//require_once ( '/Users/tanjapislar/Sites/wordpress/wp-load.php' );
//require_once ( rtrim ( $_SERVER['DOCUMENT_ROOT'], '/\\' ) . DIRECTORY_SEPARATOR . 'wp-load.php' );
require_once('./../../../wp-load.php');

// allowed dimensions 
$dimensions = Array ( '480', '768', '1024', '2048' );

if ( IsSet ( $_GET['photo'] ) === false || IsSet ( $_GET['d'] ) === false || IsSet ( $_GET['t'] ) === false )
	bw_error ();

$photoId = UrlDecode ( $_GET['photo'] ); // WP attachment ID
$targetDimension = $_GET['d']; // requested dimension
$type = $_GET['t']; // thumbnail, medium, large, full

$blackAndWhite = ( IsSet ( $_GET['bw'] ) === true ? '1' : '' ); // return B&W photo

if ( In_Array ( $targetDimension, $dimensions ) === false )
	bw_error ();

if ( Editorial::isRetina () === true )
	$targetDimension = $targetDimension * 2; // if the device is a HDPI device then double the dimension of returned image

if ( In_Array ( $type, Array ( 'thumbnail', 'medium', 'large', 'full' ) ) === false )
	bw_error ();

$imageData = wp_get_attachment_image_src ( (int)$photoId, $type ); // get WP attachment info
if ( !Is_Array ( $imageData ) || !IsSet ( $imageData[0] ) )
	bw_error();

// determine orientation
$portrait = false;
if ( $imageData[1] < $imageData[2] )
	$portrait = true;

$image = StrStr ( $imageData[0], '/uploads/' );
$originalPath = WP_CONTENT_DIR . $image;
$resizedPath = Str_Replace ( '/cache/uploads/', '/cache/editorial-r/', WP_CACHE_DIR . $image );
$extension = PathInfo ( $originalPath, PATHINFO_EXTENSION );

$allowed = Array ( 'jpg', 'jpeg', 'png', 'gif' );
if ( !In_Array ( $extension, $allowed ) )
	bw_error ();

$resizedPath = Str_Replace ( '.' . $extension, '__x' . $blackAndWhite . $type . '_' . $targetDimension . '.' . $extension, $resizedPath ); // image will be cached in this location

if ( Is_File ( $resizedPath ) === false /*/|| 1 == 1/**/ ) // check if image is already cached
{
	// create cache folder (does nothing if it already exists)
	Editorial::createPath ( DirName ( $resizedPath ) );

	if ( ( $portrait === false && $imageData[1] <= (int)$targetDimension ) || ( $portrait === true && $imageData[2] <= (int)$targetDimension ) )
	{
		// original image is smaller then requested image, so no resizing takes place
		if ( $blackAndWhite === '1' )
		{
			// convert to B&W
			$image = getImageFromFile ( $originalPath );

			imagefilter ( $image, IMG_FILTER_GRAYSCALE ); // convert to B&W

			saveImageToFile ( $image, $resizedPath ); // save to cache folder

			UnSet ( $image ); // cleanup
		}
		else
			Copy ( $originalPath, $resizedPath ); // just copy original to cache folder
	}
	else
	{
		// calculate ratio and new dimensions
		$ratio = $imageData[1] / $imageData[2];
		$newWidth = $newHeight = null;
		if ( $portrait === false )
		{
			$newWidth = $targetDimension;
			$newHeight = $targetDimension / $ratio;
		}
		else
		{
			$newHeight = $targetDimension;
			$newWidth = $targetDimension * $ratio;
		}

		$imageResized = imagecreatetruecolor ( $newWidth, $newHeight );
		$image = getImageFromFile ( $originalPath );
		imagecopyresampled ( $imageResized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageData[1], $imageData[2] ); // resize it

		if ( $blackAndWhite === '1' )
			imagefilter ( $imageResized, IMG_FILTER_GRAYSCALE ); // convert to B&W

		saveImageToFile ( $imageResized, $resizedPath ); // save to cache folder

		UnSet ( $image ); // cleanup
	}
}

// output file
if ( $extension == 'jpg' )
	$extension = 'jpeg';

// set appropriate HTTP headers (type, size, cache control)
Header ( 'Content-type: image/' . $extension );
$browserCache = 60 * 60 * 24 * 30;
Header ( 'Cache-Control: private, max-age=' . $browserCache );
Header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s', time() + $browserCache ).' GMT' );
ReadFile ( $resizedPath );

function getImageFromFile ( $FileName )
{
	$image = null;
	// load image into memory
	switch ( PathInfo ( $FileName, PATHINFO_EXTENSION ) )
	{
		case 'jpg':
		case 'jpeg':
			$image = imagecreatefromjpeg ( $FileName );
		break;

		case 'png':
			$image = imagecreatefrompng ( $FileName );
		break;

		case 'gif':
			$image = imagecreatefromgif ( $FileName );
		break;
	}

	return $image;
}

function saveImageToFile ( $Image, $FileName )
{
	switch ( PathInfo ( $FileName, PATHINFO_EXTENSION ) )
	{
		case 'jpg':
		case 'jpeg':
			imagejpeg ( $Image, $FileName, 100 );
		break;

		case 'png':
			imagepng ( $Image, $FileName, 100 );
		break;

		case 'gif':
			imagegif ( $Image, $FileName, 100 );
		break;
	}
}

function bw_error ()
{
	// return 404
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}