<?php

// $Id: ttf_image.php 1371 2011-01-09 15:12:09Z Luisehahne $

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

// get lists of fonts and backgrounds
require_once(WB_PATH.'/framework/functions.php');
$t_fonts = file_list(WB_PATH.'/include/captcha/fonts');
$t_bgs = file_list(WB_PATH.'/include/captcha/backgrounds');
$fonts = array();
$bgs = array();
foreach($t_fonts as $file) { if(preg_match('/\.ttf/',$file)) { $fonts[]=$file; } }
foreach($t_bgs as $file) { if(preg_match('/\.png/',$file)) { $bgs[]=$file; } }

// make random string
if(!function_exists('randomString')) {
    function randomString($len) {
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float)$sec + ((float)$usec * 100000));
        //$possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghkmnpqrstuvwxyz23456789";
        $possible="abdfhkrsvwxz23456789";
        $str="";
        while(strlen($str)<$len) {
            $str.=substr($possible,(mt_rand()%(strlen($possible))),1);
        }
        return($str);
    }
}
$text = randomString(5); // number of characters

$sec_id = '';
if(isset($_GET['s'])) $sec_id = $_GET['s'];
$_SESSION['captcha'.$sec_id] = $text;

// choose a font and background
$font = $fonts[array_rand($fonts)];
$bg = $bgs[array_rand($bgs)];
// get image-dimensions
list($width, $height, $type, $attr) = getimagesize($bg);

// create reload-image
$reload = ImageCreateFromPNG(WB_PATH.'/include/captcha/reload_140_40.png'); // reload-overlay

if(mt_rand(0,2)==0) { // 1 out of 3

    // draw each character individualy
    $image = ImageCreateFromPNG($bg); // background image
    $grey = mt_rand(0,50);
    $color = ImageColorAllocate($image, $grey, $grey, $grey); // font-color
    $ttf = $font;
    $ttfsize = 25; // fontsize
    $count = 0;
    $image_failed = true;
    $angle = mt_rand(-15,15);
    $x = mt_rand(10,25);
    $y = mt_rand($height-10,$height-2);
    do {
        for($i=0;$i<strlen($text);$i++) {
            $res = imagettftext($image, $ttfsize, $angle, $x, $y, $color, $ttf, $text{$i});
            $angle = mt_rand(-15,15);
            $x = mt_rand($res[4],$res[4]+10);
            $y = mt_rand($height-15,$height-5);
        }
        if($res[4] > $width) {
            $image_failed = true;
        } else {
            $image_failed = false;
        }
        if(++$count > 4) // too many tries! Use the image
            break;
    } while($image_failed);
    
} else {
    
    // draw whole string at once
    $image_failed = true;
    $count=0;
    do {
        $image = ImageCreateFromPNG($bg); // background image
        $grey = mt_rand(0,50);
        $color = ImageColorAllocate($image, $grey, $grey, $grey); // font-color
        $ttf = $font;
        $ttfsize = 25; // fontsize
        $angle = mt_rand(0,5);
        $x = mt_rand(5,30);
        $y = mt_rand($height-10,$height-2);
        $res = imagettftext($image, $ttfsize, $angle, $x, $y, $color, $ttf, $text);
        // check if text fits into the image
        if(($res[0]>0 && $res[0]<$width) && ($res[1]>0 && $res[1]<$height) && 
             ($res[2]>0 && $res[2]<$width) && ($res[3]>0 && $res[3]<$height) && 
             ($res[4]>0 && $res[4]<$width) && ($res[5]>0 && $res[5]<$height) && 
             ($res[6]>0 && $res[6]<$width) && ($res[7]>0 && $res[7]<$height)
        ) {
            $image_failed = false;
        }
        if(++$count > 4) // too many tries! Use the image
            break;
    } while($image_failed);
    
}

imagealphablending($reload, TRUE);
imagesavealpha($reload, TRUE);

// overlay
imagecopy($reload, $image, 0,0,0,0, 140,40);
imagedestroy($image);
$image = $reload;

captcha_header();
ob_start();
imagepng($image);
header("Content-Length: ".ob_get_length()); 
ob_end_flush();
imagedestroy($image);
