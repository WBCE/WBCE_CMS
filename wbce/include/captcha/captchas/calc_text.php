<?php

// $Id: calc_text.php 1371 2011-01-09 15:12:09Z Luisehahne $

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

// Must include code to stop this file being accessed directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

if(!file_exists(WB_PATH.'/modules/captcha_control/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH.'/modules/captcha_control/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH.'/modules/captcha_control/languages/'.LANGUAGE .'.php');
}

$_SESSION['captcha'.$sec_id] = '';
mt_srand((double)microtime()*1000000);
$n = mt_rand(1,3);
switch ($n) {
	case 1:
		$x = mt_rand(1,9);
		$y = mt_rand(1,9);
		$_SESSION['captcha'.$sec_id] = $x + $y;
		$cap = "$x {$MOD_CAPTCHA['ADDITION']} $y"; 
		break; 
	case 2:
		$x = mt_rand(10,20);
		$y = mt_rand(1,9);
		$_SESSION['captcha'.$sec_id] = $x - $y; 
		$cap = "$x {$MOD_CAPTCHA['SUBTRAKTION']} $y"; 
		break;
	case 3:
		$x = mt_rand(2,10);
		$y = mt_rand(2,5);
		$_SESSION['captcha'.$sec_id] = $x * $y; 
		$cap = "$x {$MOD_CAPTCHA['MULTIPLIKATION']} $y"; 
		break;
}
echo $cap;
?>