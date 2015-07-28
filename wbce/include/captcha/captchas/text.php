<?php

// $Id: text.php 1371 2011-01-09 15:12:09Z Luisehahne $

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

global $database;
$name = 'text';
$file = WB_PATH."/temp/.captcha_$name.php";

srand((double)microtime()*100000);
$_SESSION['captcha'.$sec_id] = rand(0,99999);

// get questions and answers
$text_qa='';
$table = TABLE_PREFIX.'mod_captcha_control';
if($query = $database->query("SELECT ct_text FROM $table")) {
	$data = $query->fetchRow();
	$text_qa = $data['ct_text'];
}
$content = explode("\n", $text_qa);

reset($content);
while($s = current($content)) {
	// get question
	$s=trim(rtrim(rtrim($s,"\n"),"\r")); // remove newline
	if($s=='' OR $s{0}!='?') {
		next($content);
		continue;
	}
	if(isset($s{3}) && $s{3}==':') {
		$lang=substr($s,1,2);
		$q=substr($s,4);
	}	else {
		$lang='XX';
		$q=substr($s,1);
		if($q=='') {
			next($content);
			continue;
		}
	}
	// get answer
	$s=next($content);
	$s=trim(rtrim(rtrim($s,"\n"),"\r")); // remove newline
	if(isset($s{0}) && $s{0}=='!') {
		$a=substr($s,1);
		$qa[$lang][$q]=$a;
		next($content);
	}
}
if(!isset($qa) || $qa == array()) {
	echo '<b>Error</b>: no text defined! Enter <b>0</b> to solve this captcha';
	$_SESSION['captcha'] = '0';
	return;
}

// choose language to use
if(defined('LANGUAGE') && isset($qa[LANGUAGE]))
	$lang = LANGUAGE;
else
	$lang = 'XX';
if(!isset($qa[$lang])) {
	echo '<b>Error</b>: no text defined! Enter <b>0</b> to solve this captcha';
	$_SESSION['captcha'] = '0';
	return;
}

// choose random question
$k = array_rand($qa[$lang]);

$_SESSION['captcha'.$sec_id] = $qa[$lang][$k];

echo $k;

?>
