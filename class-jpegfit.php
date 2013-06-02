<?php
/**
* JpegFit Class
* 2007-04-12	Image Resource getters and setters with examples
* 2007-03-29    First Version
*
* IMPORTANT NOTE
* There is no warranty, implied or otherwise with this software.
*
* LICENCE
* This code has been placed in the Public Domain for all to enjoy.
*
* @author    Zafar Iqbal
* @package   JpegFit
*/
class JpegFit
{
    	// Image file path
    	private $imageFilePath;

	// Image handle
	private $im;

	// Quality range
	private $low, $high;
	private $q, $cq;

	// Target image size
	private $targetSize;

	// Target resolution
	private $width,$height,$resizeFlag;

	// Precision
	private $precision;

	// Max Iterations
	private $maxIterations;

	// Debug Array
	private $debug;

	// Class Constructor
	public function __construct()
	{
		$this->init();
	}

	// Class Destructor
	public function __destruct()
	{
		imagedestroy($this->im);
	}

	// Init variables
	public function init()
	{
		// Image filename and resource handle
		$this->imageFilePath = NULL;
		$this->im = NULL;

		// Quality range
		$this->low = 0;
		$this->high = 100;
		$this->q = 50;
		$this->cq = 50;

		// Difference in high/low to break loop
		$this->precision = 0.1;

		// Total iterations to break loop
		$this->maxIterations = 16;

		// Default resize flag
		$this->resizeFlag=false;
	}

	// Set function for path to image - can be URL
	public function setImageFilePath( $path )
	{
		$this->imageFilePath = $path;

	}

	// Set function for target size - in bytes
        public function setTargetSize( $size )
        {
                $this->targetSize = $size;

        }


	// Set width and height for output image (see example 5)
	public function setResolution()
	{
		$args=func_get_args();

		if(count($args)==0 or count($args)>2) return;

		$this->resizeFlag=true;

		$this->width=$args[0];
		$this->height=$args[1];

	}

	// Set Image Resource
	public function setImageResource( $ir )
	{
		$this->im = $ir;
	}

	// Get Image Resource
	public function getImageResource()
	{
		return $this->im;
	}


	// Function to compute jpeg quality setting restricted to image size
	public function fit()
	{
		// Load image
		if( $this->im == NULL) $this->im = imagecreatefromstring( file_get_contents( $this->imageFilePath ) );

		if($this->resizeFlag)
		{
			$newWidth=$this->width;
			$newHeight=$this->height;
	
			$t=imagecreatetruecolor($newWidth,$newHeight);
			imagecopyresampled($t,$this->im,0,0,0,0,$newWidth,$newHeight,imagesx($this->im),imagesy($this->im));
			imagedestroy($this->im);
			$this->im=$t;
			//imagedestroy($t);	
			$this->debug[]="[RESIZE] TRUE -> $newWidth $newHeight";
		}

		// Counter to keep track of iterations
		$cc = 0;

		// Use the buffer NOT the filesystem to compute intermediate image size
		ob_start();

		// Loop forever
		while( true )
		{
			// Empty buffer
			ob_clean();

			// Keep track of previous quality setting
			$this->cq = $this->q;

			// Create and fill buffer with image with current quality setting
			imagejpeg( $this->im, NULL, $this->cq );

			// Compute current image size from size of buffer
			$currentSize = strlen( ob_get_contents() );

			// Some debug
			$this->debug[] = " " . $this->low . " >>> " . $this->cq . " <<< " . $this->high . " [ $currentSize / " . $this->targetSize . " ]";

			// Break loop if target size is reached - very rare!
			if ( $currentSize == $this->targetSize )
				break;

			// If size > target then change quality range
			if ( $currentSize > $this->targetSize )
			{
				$this->high = $this->q;
				$this->q = ( $this->q + $this->low ) / 2;
			}

			// If size < target then change quality range
			if ( $currentSize < $this->targetSize )
			{
				$this->low = $this->q;
				$this->q = ( $this->q + $this->high ) / 2;
			}

			// Break loop if high/low gap below precision AND size is < target size
			if ( ( ( $this->high - $this->low ) < $this->precision ) && ( $currentSize <= $this->targetSize ) )
				break;

			// Break loop of counter has reached maximum iterations - target size either to low/high
			if ( $cc == $this->maxIterations )
				break;

			// Continue loop incrementing counter
			$cc++;
		}

		// Final debug
		$this->debug[] = "Final Quality Setting = " . $this->cq;

		// Disable buffer
		ob_end_clean();
	}

	// Save image to filename
	public function saveImage($filename)
	{
		imagejpeg( $this->im, "$filename", $this->cq );
	}

	// Send image to client - including http header
        public function clientOutput()
        {
		header( "Content-type: image/jpeg" );
                imagejpeg( $this->im, NULL, $this->cq);
        }

	// Get the final quality setting
	public function getQ()
	{
		return $this->cq;
	}

	// Ouput debug that has been generated
	public function dprint()
	{
		print_r( $this->debug );
	}

}

?>
