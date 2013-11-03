<?php
	session_start();
	$typeImage = "Content-type: image/png";
	$session = "captcha";	
	$font = "comicbd.ttf";
	
	$numChars = 6; //Number of characters in the picture
	$imageHeight = 30;
	$imageWidth = 120;	
	$WaveStrengthY = 2; // Sets the strength of the waves in y-axis.
	$WaveStrengthX = 2; // Sets the strength of the waves in x-axis.
	
	if (isset($_SESSION[$session])) {
		unset($_SESSION[$session]);
	}
	
	$characters = array_merge(range(0, 9), range('A', 'Z'), range('a', 'z')); //Combination of numbers and alphabets.
	shuffle($characters); //Shuffle the characters.
	
	$text = NULL;
	for ($i=0; $i<$numChars;$i++) {
		$text .= $characters[rand(0, count($characters) - 1)]; // Set text to $numChars random characters.
	}
	
	$_SESSION[$session] = $text; // Put the randomized text into a session.
	
	header($typeImage); // Set contenttype as PNG, so that the browser reads it as a image.
	$image = imagecreatetruecolor($imageWidth, $imageHeight);
	$imageBackground = imagecolorallocate($image, 68, 68, 68); // Set the background color.
	$imageTextColor = imagecolorallocate($image, 255, 255, 255); // Set the text color.
	
	imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $imageBackground); // The box containing the text.
	
	// imagefttext($image, $fontsize, $angle, $MarginLeft, $MarginTop, $color, $fontfile, $text)
	imagettftext($image, 17, 0, 13, 25, $imageTextColor, $font, $text);
	$image = makeWave($image, $imageWidth, $imageHeight, $WaveStrengthY, $WaveStrengthX); // Make the image wavey.
	imagepng($image); // Make image file.
	imagedestroy($image);

	/**
	 * Makes a image to look wavey~.
	 * @param $image truecolorimage
	 * @param int $width width of the image
	 * @param int $height height of the image
	 * @param int $y_amplitude Strengthlevel of the waves in y-axis.
	 * @param int $x_amplitude Strengthlevel of the waves in x-axis.
	 * @return truecolorimage
	 * 
	 */
	function makeWave($image, $width, $height, $y_amplitude, $x_amplitude) {
		$x_period = 10;
		$y_period = 10;
		
		$xp = $x_period*rand(1,3);
		$k = rand(0, 100);
		
		for ($a = 0; $a < $width; $a++) {
			imagecopy($image, $image, $a-1, sin($k+$a/$xp)*$x_amplitude, $a, 0, 1, $height);
		}
		
		$yp = $y_period*rand(1, 2);
		$k = rand(0, 100);
		for ($a = 0; $a < $height; $a++) {
			imagecopy($image, $image, sin($k+$a/$yp)*$y_amplitude, $a-1, 0, $a, $width, 1);			
		}		
		return $image;	
	}
?>