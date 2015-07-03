<?php
/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * install.php
 * This file will CREATE TABLE in the DB while installation
 * The TABLE will provide configuration settings needed for the Admin-Tool
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if(!defined('WB_PATH')) exit("Cannot access this file directly ".__FILE__);

// Prepare Array for JSON Encode
$aSettings = array(
	// set counter for Titles
	'iTitleCount' => array(
		'use' => TRUE,
		'optimum' => 50,
		'minimum' => 30
	),
	// set counters for Description
	'iDescriptionCount' => array(
		'use' => TRUE,
		'optimum' => 150,
		'minimum' => 90
	),
	// use keywords?
	'keywordsConfig' => array(
		'use' => TRUE,		
		// If you want to replace the String "Keywords", you can do it here
		// This is convenient if you use the Keywords field for different purposes
		'wordReplace' => 'keywords'
	),
	// use rewrite_url?
	'rewriteUrl' => array(
		'use' => FALSE,
		// string for the corresponding column in the `pages` table
		'dbString' => ''
	),
	// set whether or not you want to use Remaining Characters in counter
	'bUseRemainingChars' => TRUE
);


$sTable = TABLE_PREFIX . 'mod_page_seo_tool';
// create new module table
$sSqlCreate = "CREATE TABLE IF NOT EXISTS `".$sTable."` (
  `settings_json` text NOT NULL
)";
$database->query($sSqlCreate);

// add default values to $sTable
$sSqlInsert = "INSERT INTO `".$sTable."` (`settings_json`)	VALUES ('". json_encode($aSettings) ."')";
$database->query($sSqlInsert);