<?php

// $Id: delete.php 563 2008-01-18 23:13:42Z Ruebenwurzel $

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
require_once(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
require_once(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');

//get and remove all php files created for the topics section
$query_details = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE section_id = '$section_id' AND page_id = '$page_id'");
if($query_details->numRows() > 0) {
	while($row = $query_details->fetchRow()) {
		// Unlink topic access file anyway
		if(is_writable(WB_PATH.$topics_directory.$row['link'].PAGE_EXTENSION)) {
			unlink(WB_PATH.$topics_directory.$row['link'].PAGE_EXTENSION);
		}
		
		$t_id = $row['topic_id'];
		if ($row['hascontent'] < 2) {
			$database->query("DELETE FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$t_id'");		
		}	
	}
} 

$hide_it = 0 - $section_id;
$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename." SET section_id = '$hide_it' WHERE section_id = '$section_id' AND page_id = '$page_id'";
$database->query($theq);
$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename."_settings SET section_id = '$hide_it' WHERE section_id = '$section_id' AND page_id = '$page_id'";
$database->query($theq);



//check to see if any other sections are part of the topics page, if only 1 topics is there delete it
$query_details = $database->query("SELECT * FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id'");
if($query_details->numRows() == 1) {
	$query_details2 = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
	$link = $query_details2->fetchRow();
	if(is_writable(WB_PATH.PAGES_DIRECTORY.$link['link'].PAGE_EXTENSION)) {
		unlink(WB_PATH.PAGES_DIRECTORY.$link['link'].PAGE_EXTENSION);
	}
}

?>