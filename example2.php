<?php
/**
* JpegFit Example 2
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

// Set filename of image - you MUST change this!
$obj->setImageFilePath('lena.png');

// Set target size in bytes
$obj->setTargetSize(25600);

// Run function
$obj->fit();

// Send image to client with HTTP headers
$obj->clientOutput();

?>
