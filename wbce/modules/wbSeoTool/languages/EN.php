<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * languages/EN.php
 * This file provides the multilingual array for this Tool
 * 
 * 
 * @platform       WBCE CMS 1.7.x
 * @package        wbSeoTool
 * @author         Christian M. Stefan (https://www.wbEasy.de/)
 * @contributions  Colinax, Bianka Martinovic ("WebBird"), BerndJM
 * @copyright      Christian M. Stefan
 * @license        http://www.gnu.org/licenses/gpl-2.0.html
 */


$module_name          = 'SEO Meta Tree';
$module_description   = 'Admin-Tool that enables you to change META Tags in a PageTree view.';

$TXT['NOSCRIPT_MESSAGE']  = "The browser you're using has JavaScript turn off. You'll need to turn it on, in order to work with this Admin-Tool";
$TXT['CLICK2EDIT']        = 'Click to edit inline';
$TXT['COUNTER_STRING']    = '<span><strong>[%2]</strong>{COUNTER_REMAINING}</span>';
$TXT['COUNTER_REMAINING'] = '<br /> <nobr><small>(</small>%1 <small>chars left)</small></nobr>';
$TXT['ADVANCED_SETTINGS'] = 'advanced Settings';
$TXT['TOOL_CONFIG']       = 'Admin-Tool Configuration';
$TXT['USE']               = 'use';
$TXT['USED_DB_FIELD']     = 'used DB field';
$TXT['USE_FLAGS']         = 'Language Flags';
$TXT['USE_FLAGS_HINT']    = 'Use Language Flags in Page-Tree. Useful for multilingual sites.';
$TXT['CUSTOM_KEYWORDS_STRING']      = 'set custom string';
$TXT['CUSTOM_KEYWORDS_STRING_HINT'] = '"Keywords" is default, you may rename it to whatever you like (e.g.<i> "my custom field"</i>)';
$TXT['USE_REMAINING_CHARS']         = 'show remaining characters';
$TXT['USE_REMAINING_CHARS_HINT']    = 'Will show how many characters left (based on "optimum" settings)';
$TXT['USE_REWRITE_URL_HINT']        = 'This is a special setting that needs Database and Core changes in WebsiteBaker - Do not use this if you don\'t know what it does.';
$TXT['REWRITE_URL_WARNING_HINT']    = '<b>CAUTION</b>, the DB Field <i>(%s)</i> does not exist. Please be sure to use an existing DB-Field or do not use this function at all.';
$TXT['NO_PAGES_FOUND']              = 'no pages found!';
$TXT['RESTORE']                     = 'Restore';
$TXT['MENU_TITLE']                  = 'Menu Title';
$TXT['USE_MENU_TITLE_HINT']         = 'Makes the menu title editable in the page tree (double-click to edit).';
$TXT['DOUBLE_CLICK_TO_EDIT']        = 'Double-click to edit';
$TXT['STATUS_OFF']                  = 'Not checked (counter disabled in settings)';
$TXT['STATUS_EMPTY']                = 'Empty';
$TXT['STATUS_SHORT']                = 'Too short';
$TXT['STATUS_OPTIMAL']              = 'Optimal length';
$TXT['STATUS_LONG']                 = 'Too long — may get truncated in search results';
$TXT['STATUS_DUPLICATE']            = 'Duplicate — same text used on another page';