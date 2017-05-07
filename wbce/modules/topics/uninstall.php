<?php

// $Id: uninstall.php 563 2008-01-18 23:13:42Z Ruebenwurzel $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;
// include module_settings
include(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
if (file_exists(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php')) { include(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php'); }

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_rss_count`");
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_rss_statistic`");

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_obsolete`");
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_comments_obsolete`");
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_settings_obsolete`");

$database->query("RENAME TABLE `".TABLE_PREFIX."mod_".$tablename."` TO `".TABLE_PREFIX."mod_".$tablename."_obsolete`");
$database->query("RENAME TABLE `".TABLE_PREFIX."mod_".$tablename."_comments` TO `".TABLE_PREFIX."mod_".$tablename."_comments_obsolete`");
$database->query("RENAME TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` TO `".TABLE_PREFIX."mod_".$tablename."_settings_obsolete`");

//require_once(WB_PATH.'/framework/functions.php');
//rm_full_dir(WB_PATH.$topics_directory);