<?php
/**
 *
 * @category        frontend
 * @package         search
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: search_convert.php 1442 2011-04-15 19:44:20Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/search/search_convert.php $
 * @lastmodified    $Date: 2011-04-15 21:44:20 +0200 (Fr, 15. Apr 2011) $
 *
 */
/*
	ATTN: to include your local changes DO NOT alter this file!
	Instead, create your own local file search_convert_local.php
	which will stay intact even after upgrading Website Baker.

	--Example search_convert_local.php --------------------------
	// allows the user to enter Krasic to find Krašić
	$t["s"]  = array("š","s");
	$t["S"]  = array("Š","S");
	$t["c"]  = array("ć","c");
	$t["C"]  = array("Ć","C");
	...
	--END -------------------------------------------------------
*/
if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}
if(!isset($search_lang)) $search_lang = LANGUAGE;
$t = array();

/*
	ATTN:
	This file MUST be UTF-8-encoded
*/
// test encoding
if('á'!="\xc3\xa1") {
	trigger_error('Wrong encoding for file search_convert.php!', E_USER_NOTICE);
	return;
}

// local german settings
if($search_lang=='DE') { // add special handling for german umlauts (ä==ae, ...)
	// in german the character 'ß' may be written as 'ss', too. So for each 'ß' look for ('ß' OR 'ss')
	$t["ß"]  = array("ß" ,"ss"); // german SZ-Ligatur
	$t["ä"]  = array("ä" ,"ae"); // german ae
	$t["ö"]  = array("ö" ,"oe"); // german oe
	$t["ü"]  = array("ü" ,"ue"); // german ue
	// the search itself is case-insensitiv, but strtr() (which is used to convert the search-string) isn't,
	// so we have to supply upper-case characters, too!
	$t["Ä"]  = array("Ä" ,"Ae"); // german Ae
	$t["Ö"]  = array("Ö" ,"Oe"); // german Oe
	$t["Ü"]  = array("Ü" ,"Ue"); // german Ue
	// and for each 'ss' look for ('ß' OR 'ss'), too
	$t["ss"] = array("ß" ,"ss"); // german SZ-Ligatur
	$t["ae"] = array("ä" ,"ae"); // german ae
	$t["oe"] = array("ö" ,"oe"); // german oe
	$t["ue"] = array("ü" ,"ue"); // german ue
	$t["Ae"] = array("Ä" ,"Ae"); // german Ae
	$t["Oe"] = array("Ö" ,"Oe"); // german Oe
	$t["Ue"] = array("Ü" ,"Ue"); // german Ue
}

// local Turkish settings
if($search_lang=='TR') { // add special i/I-handling for Turkish
	$t["i"] = array("i", "İ");
	$t["I"] = array("I", "ı");
}

// include user-supplied file
if(file_exists(WB_PATH.'/search/search_convert_local.php'))
	include(WB_PATH.'/search/search_convert_local.php');

// create arrays
global $search_table_umlauts_local;
$search_table_umlauts_local = array();
foreach($t as $o=>$a) {
	$alt = '';
	if(empty($o) || empty($a) || !is_array($a)) continue;
	foreach($a as $c) {
		if(empty($c)) continue;
		$alt .= preg_quote($c,'/').'|';
	}
	$alt = rtrim($alt, '|');
	$search_table_umlauts_local[$o] = "($alt)";
}
// create array for use with frontent.functions.php (highlighting)
$string_ul_umlaut = array_keys($search_table_umlauts_local);
$string_ul_regex = array_values($search_table_umlauts_local);


global $search_table_sql_local;
$search_table_sql_local = array();
foreach($t as $o=>$a) {
	if(empty($o) || empty($a) || !is_array($a)) continue;
	$i = 0;
	foreach($a as $c) {
		if(empty($c)) continue;
		if($o==$c) { $i++; continue; }
		$search_table_sql_local[$i++][$o] = $c;
	}
}

