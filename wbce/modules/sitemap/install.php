<?php
/*
 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2010, Ryan Djurovich

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