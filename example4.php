<?php
/**
* JpegFit Example
*
* IMPORTANT NOTE
* there is no warranty, implied or otherwise with this software.
*
* LICENCE
* This code has been placed in the Public Domain for all to enjoy.
*
* @author    Zafar Iqbal
* @package   JpegFit
*/

// Load class file
require_once('class-jpegfit.php');

// Create jpegfit object
$obj=new JpegFit();


// Set Image Resource - you MUST change this!
$imageResource = imagecreatefromstring( file_get_contents( 'test.jpg' ) );
$obj->setImageResource($imageResource);

// Set target size in bytes
$obj->setTargetSize(51200);

// Run function
$obj->fit();

// Get image resource and calculated quality and then save image
imagejpeg( $obj->getImageResource(), "out.jpg", $obj->getQ() );

// OPTIONAL printing of debug
$obj->dprint();

?>
