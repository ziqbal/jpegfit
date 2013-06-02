
JpegFit
=======

A PHP class that tries to encode an input image to the jpeg file format with a given file size.

It is similar to the JPEG Reducer Class (http://www.phpclasses.org/jpegreducer) but more advanced.

This class uses a binary search algorithm to rapidly locate the jpeg quality setting which is close to the target file size.

Also, the input image can be of JPEG, PNG, GIF, WBMP, and GD2 formats.

If target file size is lower than the minimum possible then lowest quality is used.

If target file size is greater than the maximum possible then the highest quality is used.

If target file size is inbetween then the closest file size will be found, making sure it does not exceed target file size.

View sample images.
First ever jpeg image to show file size in the actual image? Probably...

2007-04-12	Added Image Resource getters and setters with examples
2007-03-29	First version

Zafar Iqbal 
