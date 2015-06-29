<?php

// $Id: captcha.php 1444 2011-04-21 23:46:56Z Luisehahne $

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

// displays the image or text inside an <iframe>
if(!function_exists('display_captcha_real')) {
	function display_captcha_real($kind='image') {
		$t = time();
		$output  = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" ";
		$output .= "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
		$output .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"";
		$output .= strtolower(LANGUAGE)."\" lang=\"".strtolower(LANGUAGE)."\">\n";
		$output .= "\t<head>\n\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>\n";
		$output .= "\t\t<title>captcha</title>\n\t</head>\n\t<body>\n";
		$_SESSION['captcha_time'] = $t;
		if($kind=='image') {
			$output .= "\t\t<a title=\"reload\" href=\"".WB_URL."/include/captcha/captcha.php?display_captcha_X986E21=2\">";
			$output .= "<img style=\"border: none;\" src=\"".WB_URL."/include/captcha/captchas/";
			$output .= CAPTCHA_TYPE.".php?t=".$t."\" alt=\"Captcha\" /></a>\n";
		} else {
			$output .= "\t\t<h2>error</h2>";
		}
		$output .= "\t</body>\n</html>";
		echo $output;
	}
}

// called from an <iframe>
if(isset($_GET['display_captcha_X986E21'])) {
	require('../../config.php');
	switch(CAPTCHA_TYPE) {
	case 'calc_image':
	case 'calc_ttf_image':
	case 'ttf_image':
	case 'old_image':
		display_captcha_real('image');
		break;
	}
	exit(0);
}


// Make sure page cannot be accessed directly
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

// check if module language file exists for the language set by the user (e.g. DE, EN)
global $MOD_CAPTCHA;
if(!file_exists(WB_PATH.'/modules/captcha_control/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH.'/modules/captcha_control/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH.'/modules/captcha_control/languages/'.LANGUAGE .'.php');
}

// output-handler for image-captchas to determine size of image
if(!function_exists('captcha_header')) {
	function captcha_header() {
		header("Expires: Mon, 1 Jan 1990 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, proxy-revalidate");
		header("Pragma: no-cache");
		header("Content-type: image/png");
		return;
	}
}

// get list of available CAPTCHAS for the dropdown-listbox in admin-tools
if(extension_loaded('gd') && function_exists('imagepng') && function_exists('imagettftext')) {
	$useable_captchas = array(
		'calc_text'=>$MOD_CAPTCHA_CONTROL['CALC_TEXT'],
		'calc_image'=>$MOD_CAPTCHA_CONTROL['CALC_IMAGE'],
		'calc_ttf_image'=>$MOD_CAPTCHA_CONTROL['CALC_TTF_IMAGE'],
		'ttf_image'=>$MOD_CAPTCHA_CONTROL['TTF_IMAGE'],
		'old_image'=>$MOD_CAPTCHA_CONTROL['OLD_IMAGE'],
		'text'=>$MOD_CAPTCHA_CONTROL['TEXT']
	);
} elseif(extension_loaded('gd') && function_exists('imagepng')) {
	$useable_captchas = array(
		'calc_text'=>$MOD_CAPTCHA_CONTROL['CALC_TEXT'],
		'calc_image'=>$MOD_CAPTCHA_CONTROL['CALC_IMAGE'],
		'old_image'=>$MOD_CAPTCHA_CONTROL['OLD_IMAGE'],
		'text'=>$MOD_CAPTCHA_CONTROL['TEXT']
	);
} else {
	$useable_captchas = array(
		'calc_text'=>$MOD_CAPTCHA_CONTROL['CALC_TEXT'],
		'text'=>$MOD_CAPTCHA_CONTROL['TEXT']
	);
}

if(!function_exists('call_captcha')) {
	function call_captcha($action='all', $style='', $sec_id='') {
		global $MOD_CAPTCHA;
		$t = time();
		$_SESSION['captcha_time'] = $t;

		// get width and height of captcha image for use in <iframe>
		switch(CAPTCHA_TYPE) {
		case 'calc_image':
			$captcha_width = 142;
			$captcha_height = 30;
			break;
		case 'calc_ttf_image':
			$captcha_width = 162;
			$captcha_height = 40;
			break;
		case 'ttf_image':
			$captcha_width = 162;
			$captcha_height = 40;
			break;
		case 'old_image':
			$captcha_width = 142;
			$captcha_height = 30;
			break;
		default:
			$captcha_width = 250;
			$captcha_height = 100;
		}

		if($action=='all') {
			switch(CAPTCHA_TYPE) {
				case 'text': // text-captcha
					?><table class="captcha_table" summary="captcha control"><tr>
					<td class="text_captcha">
						<?php include(WB_PATH.'/include/captcha/captchas/'.CAPTCHA_TYPE.'.php'); ?>
					</td>
					<td></td>
					<td><input type="text" name="captcha" maxlength="50"  style="width:150px;" /></td>
					<td class="captcha_expl"><?php echo $MOD_CAPTCHA['VERIFICATION_INFO_QUEST']; ?></td>
					</tr></table><?php
					break;
				case 'calc_text': // calculation as text
					?><table class="captcha_table" summary="captcha control"><tr>
					<td class="text_captcha">
						<?php include(WB_PATH.'/include/captcha/captchas/'.CAPTCHA_TYPE.'.php'); ?>
					</td>
					<td>&nbsp;=&nbsp;</td>
					<td><input type="text" name="captcha" maxlength="10"  style="width:20px;" /></td>
					<td class="captcha_expl"><?php echo $MOD_CAPTCHA['VERIFICATION_INFO_RES']; ?></td>
					</tr></table><?php
					break;
				case 'calc_image': // calculation with image (old captcha)
				case 'calc_ttf_image': // calculation with varying background and ttf-font
				  ?><table class="captcha_table" summary="captcha control"><tr>
					<td class="image_captcha">
						<?php echo "<iframe class=\"captcha_iframe\" width=\"$captcha_width\" height=\"$captcha_height\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" frameborder=\"0\" name=\"captcha_iframe_$sec_id\" src=\"". WB_URL ."/include/captcha/captcha.php?display_captcha_X986E21=1&amp;s=$sec_id"; ?>">
						<img src="<?php echo WB_URL.'/include/captcha/captchas/'.CAPTCHA_TYPE.".php?t=$t&amp;s=$sec_id"; ?>" alt="Captcha" />
						</iframe>
					</td>
					<td>&nbsp;=&nbsp;</td>
					<td><input type="text" name="captcha" maxlength="10" style="width:20px;" /></td>
					<td class="captcha_expl"><?php echo $MOD_CAPTCHA['VERIFICATION_INFO_RES']; ?></td>
					</tr></table><?php
					break;
				// normal images
				case 'ttf_image': // captcha with varying background and ttf-font
				case 'old_image': // old captcha
					?><table class="captcha_table" summary="captcha control"><tr>
					<td class="image_captcha">
						<?php echo "<iframe class=\"captcha_iframe\" width=\"$captcha_width\" height=\"$captcha_height\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" frameborder=\"0\" name=\"captcha_iframe_$sec_id\" src=\"". WB_URL ."/include/captcha/captcha.php?display_captcha_X986E21=1&amp;s=$sec_id"; ?>">
						<img src="<?php echo WB_URL.'/include/captcha/captchas/'.CAPTCHA_TYPE.".php?t=$t&amp;s=$sec_id"; ?>" alt="Captcha" />
						</iframe>
					</td>
					<td></td>
					<td><input type="text" name="captcha" maxlength="10" style="width:50px;" /></td>
					<td class="captcha_expl"><?php echo $MOD_CAPTCHA['VERIFICATION_INFO_TEXT']; ?></td>
					</tr></table><?php
					break;
			}
		} elseif($action=='image') {
			switch(CAPTCHA_TYPE) {
				case 'text': // text-captcha
				case 'calc_text': // calculation as text
					echo ($style?"<span $style>":'');
					include(WB_PATH.'/include/captcha/captchas/'.CAPTCHA_TYPE.'.php');
					echo ($style?'</span>':'');
					break;
				case 'calc_image': // calculation with image (old captcha)
				case 'calc_ttf_image': // calculation with varying background and ttf-font
				case 'ttf_image': // captcha with varying background and ttf-font
				case 'old_image': // old captcha
					echo "<img $style src=\"".WB_URL.'/include/captcha/captchas/'.CAPTCHA_TYPE.".php?t=$t&amp;s=$sec_id\" />";
					break;
			}
		} elseif($action=='image_iframe') {
			switch(CAPTCHA_TYPE) {
				case 'text': // text-captcha
					echo ($style?"<span $style>":'');
					include(WB_PATH.'/include/captcha/captchas/'.CAPTCHA_TYPE.'.php');
					echo ($style?'</span>':'');
					break;
				case 'calc_text': // calculation as text
					include(WB_PATH.'/include/captcha/captchas/'.CAPTCHA_TYPE.'.php');
					break;
				case 'calc_image': // calculation with image (old captcha)
				case 'calc_ttf_image': // calculation with varying background and ttf-font
				case 'ttf_image': // captcha with varying background and ttf-font
				case 'old_image': // old captcha
					?>
					<?php echo "<iframe class=\"captcha_iframe\" width=\"$captcha_width\" height=\"$captcha_height\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" frameborder=\"0\" name=\"captcha_iframe_$sec_id\" src=\"". WB_URL ."/include/captcha/captcha.php?display_captcha_X986E21=1&amp;s=$sec_id"; ?>">
					<?php
					echo "<img $style alt=\"Captcha\" src=\"".WB_URL.'/include/captcha/captchas/'.CAPTCHA_TYPE.".php?t=$t\" />";
					?></iframe><?php
					break;
			}
		} elseif($action=='input') {
			switch(CAPTCHA_TYPE) {
				case 'text': // text-captcha
					echo '<input type="text" name="captcha" '.($style?$style:'style="width:150px;" maxlength="50"').' />';
					break;
				case 'calc_text': // calculation as text
				case 'calc_image': // calculation with image (old captcha)
				case 'calc_ttf_image': // calculation with varying background and ttf-font
					echo '<input type="text" name="captcha" '.($style?$style:'style="width:20px;" maxlength="10"').' />';
					break;
				case 'ttf_image': // captcha with varying background and ttf-font
				case 'old_image': // old captcha
					echo '<input type="text" name="captcha" '.($style?$style:'style="width:50px;" maxlength="10"').' />';
					break;
			}
		} elseif($action=='text') {
			echo ($style?"<span $style>":'');
			switch(CAPTCHA_TYPE) {
				case 'text': // text-captcha
					echo $MOD_CAPTCHA['VERIFICATION_INFO_QUEST'];
					break;
				case 'calc_text': // calculation as text
				case 'calc_image': // calculation with image (old captcha)
				case 'calc_ttf_image': // calculation with varying background and ttf-font
					echo $MOD_CAPTCHA['VERIFICATION_INFO_RES'];
					break;
				case 'ttf_image': // captcha with varying background and ttf-font
				case 'old_image': // old captcha
					echo $MOD_CAPTCHA['VERIFICATION_INFO_TEXT'];
					break;
			}
			echo ($style?'</span>':'');
		}
	}
}

