<?php

/** ACKNOWLEDGMENTS ON BASED CODE AUTHOR AND MODS
 * Google Site Map
 * @author Karelkin Vladislav
 * @copyright 2007/2013 GPL

 Version 1.8.10 20200421 (Colinax)
 - replaced gmdate with date

 Version 1.8.9 20190712 (Florian)
 - Add News with images

 Version 1.8.8 20181204 (Ruud)
 - fixed multiple sections not seen after pages scan

 Version 1.8.7 20180529 (Ruud)
 - added the $show_hidden variable. When set true hidden pages will be included.

 Version 1.8.6 20160215 (Ruud)
 - set Shorturl default to false. (was true by accident)
 - xsl style set to relative path

 Version 1.8.5 20160210 (Ruud)
 - added oneforall support (multiple named modules possible)
 - added optional xsl for browser viewing.

 Version 1.8.4 20131004 (Ruud)
 - few small bugfixes for Topics module

 Version 1.8.3 20130814 (Ruud)
 - small bugix on using an undeclared static variable

 Version 1.8.2 20130814 (Ruud)
 - Added support when shorturl is used
 - Prevent multiple listings when pages have multiple sections
 - Prevent "empty links" to be listed.

 Version 1.8.1 20130426 (Christoph Marti)
 - Relies on section_id instead of page_id to check module page visibility

 Version 1.8.0 (Christoph Marti)
 - Reworked some parts of the code
 - Only list pages and module pages if they have visiblity active or hidden
 - Only list sections if they are in-between publication dates
 - Fixed various small bugs
 - Added Showcase module

 Version 1.7.0 (Ruud)
 - List only post on newspages that are public

 Version 1.6.0.20100917 (Ruud)
 - Added Topics module

 Version 1.5.4.20100520 (MurgtalNet on WB Forum)
 - Debugs on admin path

 Version 1.5.3.20100514 (Olivier Labbe (a.k.a. VotreEspace))
 - Debugs on sql commands

 Version 1.5.2.20091015 (Olivier Labbe (a.k.a. VotreEspace))
 - Debug on menulinks problems

 Version 1.5.2.20090918 (Olivier Labbe (a.k.a. VotreEspace))
 - Add portfolio module possibilities

 Version 1.5.1.20090725 (Olivier Labbe (a.k.a. VotreEspace))
 - Will not show outside links from menu link

 Version 1.5 (Ruud, thanks to "Mike")
 - The homepage is now listed as the WB_URL (without /pages/home.php)

 Version 1.4 (Christoph Marti)
 - Replaced the hardcoded page directory name /pages by the wb constant PAGES_DIRECTORY
 - Added feature to hide urls which contain unwanted words (eg. for web pages which are blocked by robots.txt)

 Version 1.3 (Christoph Marti)
 - Added module auto detection.
 - Removed page title comment (Google ERROR with non utf-8 chars).

 Version 1.21 (Ruud Eisinga)
 - Added htmlencoding to the url's listed.
 - Fixed external url's to be not included in the sitemap.

*/

// -------------------------------------------------------------------------
// CONFIGURATION
// -------------------------------------------------------------------------


// Set configuration values

$sitemap_version = '1.8.9';

// Debug information on / off
$debug               = false;
if(isset($_GET['debug'])) $debug = true;

// If shorturl is used, no pages and .php
// For use with shorturl V3: http://www.dev4me.nl/modules-snippets/opensource/shortlinks/
$shorturl			 = false;

// Ban urls with unwanted words
$exclude             = array();		// Array of unwanted words in the url
									// eg. array("privat", "do-not-enter", "keep-away")
// Show hidden pages
$show_hidden         = false;


// Priorities in sitemap should be 0.5 if not set. Google is happy when different priorities are set.

// Normal Pages
$page_priority       = "0.5";		// Default priority for normal pages
$page_home_priority  = "1.0";  		// Homepage priority
$page_root_priority  = "0.6";  		// Toplevel menu pages priority
$page_frequency      = "weekly";	// Update frequency of your pages.
									// Allowed: always, hourly, daily, weekly, monthly, yearly, never

// News Module
$news_priority       = "0.7";		// News posts of the last 4 weeks
$news_old_priority   = "0.5";		// News posts older than 4 weeks
$news_frequency      = "weekly";  	// News posts update frequency

// NWI Module
$nwi_priority       = "0.7";		// News posts of the last 4 weeks
$nwi_old_priority   = "0.5";		// News posts older than 4 weeks
$nwi_frequency      = "weekly";  	// News posts update frequency

// Bakery Module
$bakery_priority     = "0.5";		//
$bakery_frequency    = "weekly";  	//

// Catalog Module
$catalogs_priority    = "0.5";		//
$catalogs_frequency   = "weekly";  	// 

// Portfolio Module
$portfolio_priority  = "0.5";		//
$portfolio_frequency = "weekly";	//

// Topics Module
$topics_mod_name     = "topics";	// Name of the module
$topics_priority     = "0.5";		//
$topics_frequency    = "weekly";  	//

// Showcase Module
$showcase_priority   = "0.5";		//
$showcase_frequency  = "weekly";  	//

// OneForAll Module
$oneforall_mod_names	= "oneforall";	// Names of the oneforall modules. Seperated by a comma.
//$oneforall_mod_names	= "oneforall,projects,portfolio";	// Names of the oneforall modules. Seperated by a comma.
$oneforall_priority    	= "0.5";		//
$oneforall_frequency  	= "weekly";  	//


// -------------------------------------------------------------------------
// END OF CONFIGURATION
// -------------------------------------------------------------------------


// Include config file
require_once(dirname(__FILE__)."/config.php");

// Check if the config file has been set-up
if(!defined("WB_PATH")) {
	die("Website not configured");
}

// Include class frontend
require_once(WB_PATH."/framework/class.frontend.php");
// Create new frontend object
$wb = new frontend();
// Collect general website settings
$wb->get_website_settings();


// Vars
$counter    = 0;
$ts         = time();
$public     = array();
$modules    = array();
$debug_info = array();


// Functions
// *********

// Function check_link
function check_link($link, $exclude) {
	static $listed = array();

	// Check for unwanted words in the url
	foreach ($exclude as $value) {
		if (strpos($link, $value)) {
			$unwanted = "&quot;$link&quot; contains &quot;$value&quot; and will not show up in the google sitemap\n";
			return $unwanted;
		}
	}

	// External links should not belong to the google sitemap
	if (strpos($link, '://')) {
		$unwanted = "$link is an external link and will not show up in the google sitemap\n";
		return $unwanted;
	}
	if (in_array($link , $listed)) {
		$unwanted = "$link is already listed\n";
		return $unwanted;
	}
	if (trim($link) === '') {
		$unwanted = "Skipped empty link\n";
		return $unwanted;
	}

	$listed[] = $link;
	return true;
}

// Function output_xml
function output_xml($link, $lastmod, $freq, $pri) {
	global $shorturl;
	if($shorturl) {
		$linkstart = strlen(WB_URL.PAGES_DIRECTORY);
		$linkend = strlen(PAGE_EXTENSION);
		$link = WB_URL.substr( $link , $linkstart );
		if(substr( $link , 0, -$linkend ) == PAGE_EXTENSION) {
			$link = substr( $link , 0, -$linkend ).'/';
		} else {
			$link = str_replace( PAGE_EXTENSION , '/', $link);
		}
	}
echo '
  <url>
    <loc>'.$link.'</loc>
    <lastmod>'.$lastmod.'</lastmod>
	<changefreq>'.$freq.'</changefreq>
    <priority>'.$pri.'</priority>
  </url>';
}


// Start with xml header output
// ****************************

if ($debug) {
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8" />
	<style>
		url {
			 display: block;
		}
		loc {
			font-weight: bold;
			line-height: 25px;
		}
	</style>
</head>
<?php
} else {
	@header("Content-Type: application/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
	if(file_exists('./google_sitemap.xsl')) echo '<?xml-stylesheet type="text/xsl" href="google_sitemap.xsl"?>'.PHP_EOL;
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
}


// Get public WB pages
// *******************
$visibility = $show_hidden ? "OR p.`visibility` = 'hidden'":"";
// Get all pages from db except of the module menu_link
$sql = "SELECT p.`link`, p.`modified_when`, p.`parent`, p.`position`, s.`section_id`, s.`module`
		FROM `".TABLE_PREFIX."pages` p
		JOIN `".TABLE_PREFIX."sections` s
		ON p.`page_id` = s.`page_id`
		WHERE (p.`visibility` = 'public' $visibility)
			AND s.`module` != 'menu_link'
			AND (s.`publ_start` = '0' OR s.`publ_start` <= $ts)
			AND (s.`publ_end` = '0' OR s.`publ_end` >= $ts)
		ORDER BY p.`parent`, p.`position` ASC";
$result = $database->query($sql);

// Loop through the pages
if ($result && $result->numRows() > 0) {
	while ($page = $result->fetchRow()) {

		$checked = check_link($page['link'], $exclude);
		$public[]  = $page['section_id'];
		$modules[] = $page['module'];

		if ($checked === true) {
			$link    = htmlspecialchars($wb->page_link($page['link']));
			$lastmod = date("Y-m-d", $page['modified_when']+TIMEZONE);
			$freq    = $page_frequency;
			$pri     = $page_priority;
			if ($page['parent'] == 0) {
				if ($page['position'] == 1) {
					$pri  = $page_home_priority;   // Should be the homepage
					$link = WB_URL.'/';
				} else {
					$pri = $page_root_priority;    // Root level pages
				}
			}
			output_xml($link, $lastmod, $freq, $pri);
			$counter++;
		}
		else {
			$debug_info[] = $checked;
		}
	}
}

// All by WB sections currently used modules
$modules = array_unique($modules);

// Count pages excluding module pages
$page_counter = $counter;


// Get module pages of previously set modules
// ******************************************

// News
if (in_array('news', $modules)) {
	$sql = "SELECT `section_id`, `link`, `posted_when`, `published_when`
			FROM `".TABLE_PREFIX."mod_news_posts`
			WHERE `active` = '1'
				AND (`published_when` = '0' OR `published_when` <= $ts)
				AND (`published_until` = '0' OR `published_until` >= $ts)";
	$rs_news = $database->query($sql);
	if ($rs_news->numRows() > 0) {
		while ($news = $rs_news->fetchRow()) {
			if (!in_array($news['section_id'], $public)) continue;
			$checked = check_link($news['link'], $exclude);
			if ($checked === true) {
				$link     = htmlspecialchars($wb->page_link($news['link']));
				$lastweek = time() - (4 * 7 * 24 * 60 * 60);
				if ($news['posted_when'] < $lastweek) {
					$news_priority = $news_old_priority;
				}
				if ((version_compare(WB_VERSION, '2.7.0') <= 0) && $news['published_when'] > 0){
					$lastmod = date("Y-m-d", $news['published_when']+TIMEZONE);
				} else {
					$lastmod = date("Y-m-d", $news['posted_when']+TIMEZONE);
				}
				output_xml($link, $lastmod, $news_frequency, $news_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}

// News with images (NWI)
if (in_array('news_img', $modules)) {
	$sql = "SELECT `section_id`, `link`, `posted_when`, `published_when`
			FROM `".TABLE_PREFIX."mod_news_img_posts`
			WHERE `active` = '1'
				AND (`published_when` = '0' OR `published_when` <= $ts)
				AND (`published_until` = '0' OR `published_until` >= $ts)";
	$rs_nwi = $database->query($sql);
	if ($rs_nwi->numRows() > 0) {
		while ($nwi = $rs_nwi->fetchRow()) {
			if (!in_array($nwi['section_id'], $public)) continue;
			$checked = check_link($nwi['link'], $exclude);
			if ($checked === true) {
				$link     = htmlspecialchars($wb->page_link($nwi['link']));
				$lastweek = time() - (4 * 7 * 24 * 60 * 60);
				if ($nwi['posted_when'] < $lastweek) {
					$nwi_priority = $nwi_old_priority;
				}
				if ((version_compare(WB_VERSION, '2.7.0') <= 0) && $nwi['published_when'] > 0){
					$lastmod = date("Y-m-d", $nwi['published_when']+TIMEZONE);
				} else {
					$lastmod = date("Y-m-d", $nwi['posted_when']+TIMEZONE);
				}
				output_xml($link, $lastmod, $nwi_frequency, $nwi_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}

// Bakery
if (in_array('bakery', $modules)) {
	$sql = "SELECT `section_id`, `link`, `modified_when`
			FROM `".TABLE_PREFIX."mod_bakery_items`
			WHERE `active` = '1'";
	$rs_bakery = $database->query($sql);
	if ($rs_bakery->numRows() > 0) {
		while ($bakery = $rs_bakery->fetchRow()) {
			if (!in_array($bakery['section_id'], $public)) continue;
			$checked = check_link($bakery['link'], $exclude);
			if ($checked === true) {
				$link    = htmlspecialchars($wb->page_link($bakery['link']));
				$lastmod = date("Y-m-d", $bakery['modified_when']+TIMEZONE);
				output_xml($link, $lastmod, $bakery_frequency, $bakery_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}

// Catalog
if (in_array('catalogs', $modules)) {
	$sql = "SELECT `section_id`, `link`, `modified_when`
			FROM `".TABLE_PREFIX."mod_catalogs_list`
			WHERE `active` = '1'";
	$rs_catalogs = $database->query($sql);
	if ($rs_catalogs->numRows() > 0) {
		while ($catalogs = $rs_catalogs->fetchRow()) {
			if (!in_array($catalogs['section_id'], $public)) continue;
			$checked = check_link($catalogs['link'], $exclude);
			if ($checked === true) {
				$link    = htmlspecialchars($wb->page_link($catalogs['link']));
				$lastmod = date("Y-m-d", $catalogs['modified_when']+TIMEZONE);
				output_xml($link, $lastmod, $catalogs_frequency, $catalogs_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}

// Portfolio
if (in_array('portfolio', $modules)) {
	$sql = "SELECT p.`link`, p.`position`, p.`modified_when`, s.`section_id`
			FROM `".TABLE_PREFIX."sections` s
			JOIN `".TABLE_PREFIX."pages` p
			ON s.`page_id` = p.`page_id`
			WHERE s.`module` = 'portfolio_detail'
			AND p.`position` > '1'
			ORDER BY p.`parent`, p.`position` ASC;";

	$rs_portfolio = $database->query($sql);
	if ($rs_portfolio->numRows() > 0) {
		while ($portfolio = $rs_portfolio->fetchRow()) {
			$checked = check_link($portfolio['link'], $exclude);
			if ($checked === true) {
				$length  = strrpos($portfolio['link'], '/');
				$link    = substr($portfolio['link'], 0, $length);
				$link    = htmlspecialchars($wb->page_link($link)).'?item='.$portfolio['position'];
				$lastmod = date("Y-m-d", $portfolio['modified_when']+TIMEZONE);
				output_xml($link, $lastmod, $portfolio_frequency, $portfolio_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}

// Topics
if (in_array($topics_mod_name, $modules)) {
	require(WB_PATH.'/modules/'.$topics_mod_name.'/module_settings.php');
	$t = mktime ( (int) date("H"), (int) date("i"), (int) date("s"), (int) date("n"), (int) date("j"), (int) date("Y")) + DEFAULT_TIMEZONE;
	$sql = "SELECT `section_id`, `link`, `posted_modified`
			FROM `".TABLE_PREFIX."mod_".$topics_mod_name."`
			WHERE (`active` > '3' OR `active` = '1')
				AND (`published_when` = '0' OR `published_when` < ".$t.")
				AND (`published_until` = '0' OR `published_until` > ".$t.")
			ORDER BY `position` DESC";
	$rs_topics = $database->query($sql);
	if($rs_topics->numRows() > 0) {
		while($topics = $rs_topics->fetchRow()) {
			if (!in_array($topics['section_id'], $public)) continue;
			$checked = check_link($topics['link'], $exclude);
			if ($checked === true) {
				$link    = htmlspecialchars(WB_URL.$topics_directory.$topics['link'].PAGE_EXTENSION);
				$lastmod = date("Y-m-d", $topics['posted_modified']+TIMEZONE);
				output_xml($link, $lastmod, $topics_frequency, $topics_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}

// Showcase
if (in_array('showcase', $modules)) {
	$sql = "SELECT `section_id`, `link`, `modified_when`
			FROM `".TABLE_PREFIX."mod_showcase_items`
			WHERE `active` = '1'";
	$rs_showcase = $database->query($sql);
	if($rs_showcase->numRows() > 0) {
		while($showcase = $rs_showcase->fetchRow()) {
			if (!in_array($showcase['section_id'], $public)) continue;
			$checked = check_link($showcase['link'], $exclude);
			if ($checked === true) {
				if (!empty($showcase['link'])) {
					$path = $database->get_one("SELECT `link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = (SELECT `page_id` FROM `".TABLE_PREFIX."mod_showcase_items` WHERE `link` = '".$showcase['link']."' LIMIT 1);");
					$showcase['link'] = $path.$showcase['link'];
			 	}
				$link    = htmlspecialchars($wb->page_link($showcase['link']));
				$lastmod = date("Y-m-d", $showcase['modified_when']+TIMEZONE);
				output_xml($link, $lastmod, $showcase_frequency, $showcase_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}

$oneforall_mods = explode(',',$oneforall_mod_names);
foreach ( $oneforall_mods as $oneforall_mod_name) {
	$oneforall_mod_name = trim($oneforall_mod_name);
	if (in_array($oneforall_mod_name, $modules)) {
		$sql = "SELECT `section_id`,  `page_id`, `link`, `modified_when`
				FROM `".TABLE_PREFIX."mod_".$oneforall_mod_name."_items`
				WHERE `active` = '1'";
		$rs_oneforall = $database->query($sql);
		if($rs_oneforall->numRows() > 0) {
			while($oneforall = $rs_oneforall->fetchRow()) {
				if (!in_array($oneforall['section_id'], $public)) continue;
				$page = $database->get_one("SELECT `link` FROM `".TABLE_PREFIX."pages` WHERE `page_id`='".$oneforall['page_id']."'");
				$checked = check_link($page.$oneforall['link'], $exclude);
				if ($checked === true) {
					$link    = htmlspecialchars($wb->page_link($page.$oneforall['link']));
					$lastmod = date("Y-m-d", $oneforall['modified_when']+TIMEZONE);
					output_xml($link, $lastmod, $oneforall_frequency, $oneforall_priority);
					$counter++;
				}
				else {
					$debug_info[] = $checked;
				}
			}
		}
	}
}

// Add another module here...
// Example code
/*
if (in_array('xxxxxx', $modules)) {
	$sql = "SELECT `section_id`, `link`, `modified_when`
			FROM `".TABLE_PREFIX."mod_xxxxxx_items`
			WHERE `active` = '1'";
	$rs_xxxxxx = $database->query($sql);
	if($rs_xxxxxx->numRows() > 0) {
		while(xxxxxx = $rs_xxxxxx->fetchRow()) {
			if (!in_array($xxxxxx['section_id'], $public)) continue;
			$checked = check_link(xxxxxx['link'], $exclude);
			if ($checked === true) {
				$link    = htmlspecialchars($wb->page_link($xxxxxx['link']));
				$lastmod = date("Y-m-d", $xxxxxx['modified_when']+TIMEZONE);
				output_xml($link, $lastmod, $xxxxxx_frequency, $xxxxxx_priority);
				$counter++;
			}
			else {
				$debug_info[] = $checked;
			}
		}
	}
}
*/


// Debug
if ($debug) {
	echo '<div style="display: block; white-space: pre; border: 2px solid #c77; padding: 0 1em 1em 1em; margin: 1em; line-height: 18px; background-color: #fdd; color: black">';
	echo '<h3>DEBUG</h3>';
	echo '<h3>Number of Pages</h3>';
	echo '<div style="font-family:monospace;font-size:12px">Number of Pages excluding module pages: '.$page_counter.'<br>';
	echo 'Number of all Pages including module pages: '.$counter.'</div>';
	if (count($debug_info > 0)) {
		echo '<h3>Banned Pages</h3><div style="font-family:monospace;font-size:12px">'.implode('', $debug_info).'</div>';
	}
	echo '</div>';
}
else {
	// End xml output
	echo "\n".'</urlset>';
}
