<?php

// $Id: calc_image.php 1371 2011-01-09 15:12:09Z Luisehahne $

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

require_once("../../../config.php");
require_once(WB_PATH.'/include/captcha/captcha.php');

if(!isset($_SESSION['captcha_time']))
	exit;
//unset($_SESSION['captcha_time']);

// Captcha
$sec_id = '';
if(isset($_GET['s'])) $sec_id = $_GET['s'];
$_SESSION['captcha'.$sec_id] = '';
mt_srand((double)microtime()*1000000);
$n = mt_rand(1,3);
switch ($n) {
	case 1:
		$x = mt_rand(1,9);
		$y = mt_rand(1,9);
		$_SESSION['captcha'.$sec_id] = $x + $y;
		$cap = "$x+$y"; 
		break; 
	case 2:
		$x = mt_rand(10,20);
		$y = mt_rand(1,9);
		$_SESSION['captcha'.$sec_id] = $x - $y; 
		$cap = "$x-$y"; 
		break;
	case 3:
		$x = mt_rand(2,10);
		$y = mt_rand(2,5);
		$_SESSION['captcha'.$sec_id] = $x * $y; 
		$cap = "$x*$y"; 
		break;
}

// create reload-image
$reload = ImageCreateFromPNG(WB_PATH.'/include/captcha/reload_120_30.png'); // reload-overlay

$image = imagecreate(120, 30);

$white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
$gray = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
$darkgray = imagecolorallocate($image, 0x30, 0x30, 0x30);

for($i = 0; $i < 30; $i++) {
	$x1 = mt_rand(0,120);
	$y1 = mt_rand(0,30);
	$x2 = mt_rand(0,120);
	$y2 = mt_rand(0,30);
	imageline($image, $x1, $y1, $x2, $y2 , $gray);  
}

$x = 10;
$l = strlen($cap);
for($i = 0; $i < $l; $i++) {
	$fnt = mt_rand(3,5);
	$x = $x + mt_rand(12 , 20);
	$y = mt_rand(7 , 12); 
	imagestring($image, $fnt, $x, $y, substr($cap, $i, 1), $darkgray); 
}

imagealphablending($reload, TRUE);
imagesavealpha($reload, TRUE);

// overlay
imagecopy($reload, $image, 0,0,0,0, 120,30);
imagedestroy($image);
$image = $reload;

captcha_header();
imagepng($image);
imagedestroy($image);

?>