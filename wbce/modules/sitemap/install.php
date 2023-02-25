<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

if(defined('WB_PATH')) {
	
	// Create table
	$mod_sitemap = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_sitemap` (
		`section_id` INT NOT NULL DEFAULT '0', 
		`page_id` INT NOT NULL DEFAULT '0', 
		`header` TEXT NOT NULL,
		`sitemaploop` TEXT NOT NULL,
		`footer` TEXT NOT NULL,
		`level_header` TEXT NOT NULL,
		`level_footer` TEXT NOT NULL,
		`static` INT NOT NULL DEFAULT '0', 
		`startatroot` INT NOT NULL DEFAULT '0', 
		`depth` INT NOT NULL DEFAULT '0', 
		`show_hidden` INT NOT NULL DEFAULT '0', 
		`show_settings` INT(1) NOT NULL DEFAULT '1', 
		`menus` VARCHAR(30) NOT NULL DEFAULT '0',
		`layout` varchar(128) NOT NULL DEFAULT '0',
		PRIMARY KEY ( `section_id` ) 
	)";
	$database->query($mod_sitemap);
}