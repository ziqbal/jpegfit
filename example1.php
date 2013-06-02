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

// Set filename of image - you MUST change this!
$obj->setImageFilePath('lena.png');

// Set target size in bytes
$obj->setTargetSize(10240);

// Run function
$obj->fit();

// Save image with filename
$obj->saveImage('out.jpg');

// OPTIONAL printing of debug
$obj->dprint();

?>
