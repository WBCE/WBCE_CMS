<?php

// $Id: captcha.php 915 2009-01-21 19:27:01Z Ruebenwurzel $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2009, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/*

Captcha generator

This file generates a captcha image.
Credits to http://php.webmaster-kit.com/ for the original code.

*/

require_once("../config.php");

if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg') AND isset($_SESSION['captcha'])) {
	
	$image = imagecreate(120, 30);
	
	$white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
	$gray = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
	$darkgray = imagecolorallocate($image, 0x50, 0x50, 0x50);
	
	srand((double)microtime()*1000000);
	
	for($i = 0; $i < 30; $i++) {
		$x1 = rand(0,120);
		$y1 = rand(0,30);
		$x2 = rand(0,120);
		$y2 = rand(0,30);
		imageline($image, $x1, $y1, $x2, $y2 , $gray);  
	}
	
	$x = 0;
	for($i = 0; $i < 5; $i++) {
		$fnt = rand(3,5);
		$x = $x + rand(12 , 20);
		$y = rand(7 , 12); 
		imagestring($image, $fnt, $x, $y, substr($_SESSION['captcha'], $i, 1), $darkgray); 
	}
	
	// start buffering for size determination
	ob_start();
	// add no cache headers
	header("Expires: Mon, 1 Jan 1990 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-type: image/png");
	// Make image
	imagepng($image);
	// Fetch length
	header("Content-Length: " . ob_get_length());
	// send image and turn off buffering
	ob_end_flush();
	// clear memory
	imagedestroy($image);

}

?>