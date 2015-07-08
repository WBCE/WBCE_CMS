<?php

/** ACKNOWLEDGMENTS ON BASED CODE AUTHOR AND MODS
 * Google Site Map
 * @author Karelkin Vladislav
 * @copyright 2007/2010 GPL
 
 version 1.6.0.20100917 (Ruud)
 - Added Topics module 
 
 version 1.5.4.20100520 (MurgtalNet on WB Forum)
 - Debugs on admin path

 version 1.5.3.20100514 (Olivier Labbe (a.k.a. VotreEspace))
 - Debugs on sql commands

 version 1.5.2.20091015 (Olivier Labbe (a.k.a. VotreEspace))
 - Debug on menulinks problems

 version 1.5.2.20090918 (Olivier Labbe (a.k.a. VotreEspace))
 - Add portfolio module possibilities
 
 version 1.5.1.20090725 (Olivier Labbe (a.k.a. VotreEspace))
 - Will not show outside links from menu link
 
 version 1.5 (Ruud, thanks to "Mike")
 - The homepage is now listed as the WB_URL (without /pages/home.php)
 
 version 1.4 (Christoph Marti)
 - Replaced the hardcoded page directory name /pages by the wb constant PAGES_DIRECTORY
 - Added feature to hide urls which contain unwanted words (eg. for web pages which are blocked by robots.txt)

 version 1.3 (Christoph Marti)
 - Added module auto detection.
 - Removed page title comment (Google ERROR with non utf-8 chars).

 version 1.21 (Ruud Eisinga)
 - Added htmlencoding to the url's listed.
 - Fixed external url's to be not included in the sitemap.

*/

// ------------------------------------------------------------------------------------------------------------------------------------------------

// Set configuration values. Priorities in sitemap should be 0.5 if not set. Google is happy when different priorities are set.

// Normal Pages
$page_priority      = "0.5";		// Default priority for normal pages
$page_home_priority = "1.0";  		// Homepage priority
$page_root_priority = "0.6";  		// Toplevel menu pages priority
$page_frequency     = "weekly";		// Update frequency of your pages. Allowed: always, hourly, daily, weekly, monthly, yearly, never
$exclude            = array();      // Array of unwanted words in the url, eg. array("privat", "do-not-enter", "keep-away")

// News Module
$module_names[]     = "news";		// Name of the module
$news_priority      = "0.7";		// News posts of the last 4 weeks
$news_old_priority  = "0.5";		// News posts older than 4 weeks
$news_frequency     = "weekly";  	// News posts update frequency

// Bakery Module
$module_names[]     = "bakery";		// Name of the module
$bakery_priority    = "0.5";		//
$bakery_frequency   = "weekly";  	//

// Catalog Module
$module_names[]     = "catalogs";	// Name of the module
$catalog_priority   = "0.5";		//
$catalog_frequency  = "weekly";  	// 

// Portfolio Module
$module_names[]     = "portfolio";		// Name of the module
$portfolio_priority    = "0.5";		//
$portfolio_frequency   = "weekly";  	//

// Topics Module
$module_names[]     = "topics";		// Name of the module
$topics_priority    = "0.5";		//
$topics_frequency   = "weekly";  	//

// ------------------------------------------------------------------------------------------------------------------------------------------------



// Include config file
require_once(dirname(__FILE__)."/config.php");

// Check if the config file has been set-up
if(!defined("WB_PATH")) {
	header("Location: install/index.php");
	exit(0);
}
require_once(WB_PATH."/framework/class.frontend.php");
if(!defined("VERSION")) {
	require_once(ADMIN_PATH."/interface/version.php");
}

$v = explode(".",VERSION);
if ($v[0] < 2 || $v[1] < 7){
	$wb27 = false;   					// To know if news uses published properties. Set false when using < WB 2.7
}else{
	$wb27 = true;   					// To know if news uses published properties. Set false when using < WB 2.7
}		

// Auto detect modules
$query_addons = $database->query("SELECT type, directory FROM ".TABLE_PREFIX."addons WHERE type = 'module'");
if($query_addons->numRows() > 0) {
	while($addons = $query_addons->fetchRow()) {
		$use[$addons['directory']] = true;
	}
}

// Create new frontend object
$wb = new frontend();
// Collect general website settings
$wb->get_website_settings();

// check if there is menu links not to be shown
$checklink = mysql_query("SELECT 1 FROM `" . TABLE_PREFIX . "mod_menu_link` LIMIT 1");
// Pages
if (mysql_num_rows($checklink)) {
	$sql = "SELECT `link`, `modified_when`, `parent`, `position` FROM `" . TABLE_PREFIX . "pages` WHERE `visibility` = 'public' AND `page_id` NOT IN (SELECT `page_id` FROM `" . TABLE_PREFIX . "mod_menu_link` WHERE `target_page_id` = '-1') ORDER BY `position` ASC";
} else {
	$sql = "SELECT `link`, `modified_when`, `parent`, `position` FROM `" . TABLE_PREFIX . "pages` WHERE `visibility` = 'public' ORDER BY `position` ASC";
}
$rs = $database->query($sql);

// News
if (isset($use['news'])) {
	if ($wb27) {
		$t = time();
		$sql = "SELECT link,posted_when,published_when FROM " . TABLE_PREFIX . "mod_news_posts WHERE active=1 AND (published_when = '0' OR published_when <= $t) AND (published_until = 0 OR published_until >= $t)";
	} else {
		$sql = "SELECT link,posted_when,title FROM " . TABLE_PREFIX . "mod_news_posts WHERE active=1";
	}
$rs_news = $database->query($sql);
}

// Bakery
if (isset($use['bakery'])) {
	$sql = "SELECT link,modified_when FROM " . TABLE_PREFIX . "mod_bakery_items WHERE active=1";
	$rs_bakery = $database->query($sql);
}
// Catalog
if (isset($use['catalogs'])) {
	$sql = "SELECT link,modified_when FROM " . TABLE_PREFIX . "mod_catalogs_list WHERE active=1";
	$rs_catalog = $database->query($sql);
}

// Portfolio
if (isset($use['portfolio'])) {
	$sql = "SELECT section_id,alt FROM " . TABLE_PREFIX . "mod_portfolio_detail";
	$rs_portfolio = $database->query($sql);
}

// Topics
if (isset($use['topics'])) {
	$mod = 'topics';  //Change this if your topics module is renamed to a different directory
	require(WB_PATH.'/modules/'.$mod.'/defaults/module_settings.default.php');
	require(WB_PATH.'/modules/'.$mod.'/module_settings.php');
	$t = mktime ( (int) gmdate("H"), (int) gmdate("i"), (int) gmdate("s"), (int) gmdate("n"), (int) gmdate("j"), (int) gmdate("Y")) + DEFAULT_TIMEZONE;
	$sql = "SELECT link, posted_modified FROM " . TABLE_PREFIX . "mod_" . $mod . " WHERE (active > '3' OR active = '1') AND (published_when = '0' OR published_when < ".$t.") AND (published_until = '0' OR published_until > ".$t.") ORDER BY position DESC";
	$rs_topics = $database->query($sql);
}

############ output start ####################
@header("Content-Type: application/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
 	if ($rs && $rs->numRows() > 0) {
 	 	// Pages
		$freq = $page_frequency;
		$unwanted = "\n";
		while($page = $rs->fetchRow()) {
			$thePage = $page['link'];
			// Check for unwanted words in the url
			$wanted = true;
			foreach($exclude as $value) {
				if(strpos($thePage, $value)) {
					$wanted = false;
					$unwanted .= "{$thePage} contains $value and will not show up in the sitemap!\n";
				}
			}
			// External links should not be added to a google_sitemap
			if(strstr($thePage, '://') == '' && $wanted) {
				$pri = $page_priority;
				if ($page['parent'] == 0) {
					if ($page['position'] == 1) {  
						$pri = $page_home_priority;   // Should be the homepage
						$thePage = WB_URL.'/';
					} else {
						$pri = $page_root_priority;   // Root level pages
					}
				}
			
?>
<url>
	<loc><?php echo htmlspecialchars(page_shorturl($wb->page_link($thePage)));  ?></loc>
	<lastmod><?php echo gmdate("Y-m-d", $page['modified_when']+TIMEZONE); ?></lastmod>
	<changefreq><?php echo $freq; ?></changefreq>
	<priority><?php echo $pri; ?></priority>
</url>
<?php
			}
		}
	}

	// News, uses published_when field when used
	if(isset($rs_news) && $rs_news->numRows() > 0) {
		$freq = $news_frequency;
		while($news = $rs_news->fetchRow()){
			$pri = $news_priority;
			if (@$news['link']) {
				$lastweek = time() - (4 * 7 * 24 * 60 * 60);
				if ($news['posted_when'] < $lastweek) { 
					$pri = $news_old_priority;
				}
				if ($wb27 && $news['published_when'] > 0){
					$lastmod = gmdate("Y-m-d", $news['published_when']+TIMEZONE);
				} else {
					$lastmod = gmdate("Y-m-d", $news['posted_when']+TIMEZONE);
				}
?>
  <url>
    <loc><?php echo htmlspecialchars(page_shorturl($wb->page_link($news['link'])));  ?></loc>
    <lastmod><?php echo $lastmod; ?></lastmod>
	<changefreq><?php echo $freq; ?></changefreq>
    <priority><?php echo $pri; ?></priority>
  </url>
<?php
		 	}
		}
	}

	// Bakery
	if(isset($rs_bakery) && $rs_bakery->numRows() > 0) {
		$freq = $bakery_frequency;
		while($bakery = $rs_bakery->fetchRow()){
			$pri = $bakery_priority;
			if (@$bakery['link']) {
				// Removes the leading PAGES_DIRECTORY
				$bakery['link'] = preg_replace('/^\\'.PAGES_DIRECTORY.'/', '', $bakery['link'], 1);
?>
<url>
	<loc><?php echo htmlspecialchars(page_shorturl($wb->page_link($bakery['link'])));  ?></loc>
	<lastmod><?php echo gmdate("Y-m-d", $bakery['modified_when']+TIMEZONE); ?></lastmod>
	<changefreq><?php echo $freq; ?></changefreq>
	<priority><?php echo $pri; ?></priority>
</url>
<?php
		 	}
		}
	}

	// Catalog
	if (isset($rs_catalog) && $rs_catalog->numRows() > 0) {
		$freq = $catalog_frequency;
		while($cat = $rs_catalog->fetchRow()){
			$pri = $catalog_priority;
			if (@$cat['link']) {
?>
<url>
	<loc><?php echo htmlspecialchars(page_shorturl($wb->page_link($cat['link'])));  ?></loc>
	<lastmod><?php echo gmdate("Y-m-d", $cat['modified_when']+TIMEZONE); ?></lastmod>
	<changefreq><?php echo $freq; ?></changefreq>
	<priority><?php echo $pri; ?></priority>
</url>
<?php
			}
		}
	}
	// Show excluded urls set in the config var $exclude (Uncomment next line for testing)
	// echo "<!-- \n Hidden page URLs of the Google sitemap set in the config var \$exclude:\n".$unwanted." --> \n";
	
	// Portfolio
	if(isset($rs_portfolio) && $rs_portfolio->numRows() > 0) {
		/*SELECT  `link` FROM  `wb_pages` WHERE  `page_id` = (SELECT `page_id` FROM `wb_mod_portfolio_settings` LIMIT 1);*/
		$sql = "SELECT  `link` FROM  `" . TABLE_PREFIX . "pages` WHERE  `page_id` = (SELECT `page_id` FROM `" . TABLE_PREFIX . "mod_portfolio_settings` LIMIT 1);";
		if(!$setting_portfolio = $database->query($sql)) { die(mysql_error()); }
		if($settings_p = $setting_portfolio->fetchRow()) {
			$freq = $portfolio_frequency;
			while($portfolio = $rs_portfolio->fetchRow()){
				$pri = $portfolio_priority;
				if (@$portfolio['section_id']) {
					// Removes the leading PAGES_DIRECTORY
					$portfolio['link'] = htmlspecialchars($wb->page_link(PAGES_DIRECTORY.$settings_p['link'])).'?item='.$portfolio['section_id'];
	?>
	<url>
		<loc><?php echo page_shorturl($portfolio['link']);  ?></loc>
		<lastmod><?php echo gmdate("Y-m-d", $bakery['modified_when']+TIMEZONE); ?></lastmod>
		<changefreq><?php echo $freq; ?></changefreq>
		<priority><?php echo $pri; ?></priority>
	</url>
	<?php
			 	}
			}
		}
	}
	
	// Topics
	if (isset($rs_topics) && $rs_topics->numRows() > 0) {
		$freq = $topics_frequency;
		while($topic = $rs_topics->fetchRow()){
			$pri = $topics_priority;
			if (@$topic['link']) {
?>
<url>
	<loc><?php echo htmlspecialchars(page_shorturl(WB_URL.$topics_directory.$topic['link'].PAGE_EXTENSION));  ?></loc>
	<lastmod><?php echo gmdate("Y-m-d", $topic['posted_modified']+TIMEZONE); ?></lastmod>
	<changefreq><?php echo $freq; ?></changefreq>
	<priority><?php echo $pri; ?></priority>
</url>
<?php
			}
		}
	}

?>
</urlset>
<?php
function page_shorturl($page) {
	return str_replace('.php','',str_replace('/pages','',$page));
}
?>