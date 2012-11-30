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

require_once ( rtrim ( $_SERVER['DOCUMENT_ROOT'], '/\\' ) . DIRECTORY_SEPARATOR . 'wp-load.php' );

$dimensions = Array ( '480', '768', '1024' );

if ( IsSet ( $_GET['photo'] ) === false || IsSet ( $_GET['d'] ) === false || IsSet ( $_GET['t'] ) === false || !Editorial::canCache () )
	bw_error ();

$photoId = UrlDecode ( $_GET['photo'] );
$targetDimension = $_GET['d'];
$type = $_GET['t'];

$blackAndWhite = ( IsSet ( $_GET['bw'] ) === true ? '1' : '' );

if ( In_Array ( $targetDimension, $dimensions ) === false )
	bw_error ();

if ( Editorial::isRetina () === true )
	$targetDimension = $targetDimension * 2;

if ( In_Array ( $type, Array ( 'thumbnail', 'medium', 'large', 'full' ) ) === false )
	bw_error ();

$imageData = wp_get_attachment_image_src ( (int)$photoId, $type );
if ( !Is_Array ( $imageData ) || !IsSet ( $imageData[0] ) )
	bw_error();

$portrait = false;
if ( $imageData[1] < $imageData[2] )
	$portrait = true;

$image = StrStr ( $imageData[0], '/uploads/' );
$originalPath = WP_CONTENT_DIR . $image;
$resizedPath = Str_Replace ( '/uploads/', '/editorial-r/', WP_CACHE_DIR . $image );
$extension = PathInfo ( $originalPath, PATHINFO_EXTENSION );

$allowed = Array ( 'jpg', 'jpeg', 'png', 'gif' );
if ( !In_Array ( $extension, $allowed ) )
	bw_error ();

$resizedPath = Str_Replace ( '.' . $extension, '__x' . $blackAndWhite . $type . '_' . $targetDimension . '.' . $extension, $resizedPath );

if ( Is_File ( $resizedPath ) === false )
{
	// create path
	Editorial::createPath ( DirName ( $resizedPath ) );

	if ( ( $portrait === false && $imageData[1] <= (int)$targetDimension ) || ( $portrait === true && $imageData[2] <= (int)$targetDimension ) )
		Copy ( $originalPath, $resizedPath );
	else
	{
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
		$image = null;
		switch ($extension)
		{
			case 'jpg':
			case 'jpeg':
				$image = imagecreatefromjpeg ( $originalPath );
			break;

			case 'png':
				$image = imagecreatefrompng ( $originalPath );
			break;

			case 'gif':
				$image = imagecreatefromgif ( $originalPath );
			break;
		}

		imagecopyresampled ( $imageResized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageData[1], $imageData[2] );

		if ( $blackAndWhite === '1' )
			imagefilter ( $imageResized, IMG_FILTER_GRAYSCALE );

		switch ($extension)
		{
			case 'jpg':
			case 'jpeg':
				imagejpeg ( $imageResized, $resizedPath, 100 );
			break;

			case 'png':
				imagepng ( $imageResized, $resizedPath, 100 );
			break;

			case 'gif':
				imagegif ( $imageResized, $resizedPath, 100 );
			break;
		}

		UnSet ( $image );
	}
}

// output file
if ( $extension == 'jpg' )
	$extension = 'jpeg';

Header ( 'Content-type: image/' . $extension );
$browserCache = 60 * 60 * 24 * 30;
Header ( 'Cache-Control: private, max-age=' . $browserCache );
Header ( 'Expires: ' . gmdate ( 'D, d M Y H:i:s', time() + $browserCache ).' GMT' );
ReadFile ( $resizedPath );

function bw_error()
{
    // return 404
    header("HTTP/1.0 404 Not Found");
    exit();
}