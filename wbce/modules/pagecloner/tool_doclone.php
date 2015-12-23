<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	02111-1307	USA

*/

// tool_doclone.php
// Where the actual cloning will take place
require('../../config.php');

// create admin object depending on platform (admin tools were moved out of settings with WB 2.7)
$admin = new admin('admintools', 'admintools');

// First get the selected page
$title = isset($_REQUEST["title"]) ?$admin->add_slashes($_REQUEST["title"]) : '';
$parent = isset($_REQUEST["parent"]) ?$_REQUEST["parent"] : '';
$pagetoclone = isset($_REQUEST["pagetoclone"]) ? (int)$_REQUEST["pagetoclone"] : 0;
$include_subs = isset($_REQUEST["include_subs"]) ? '1' : '0';
$copy_title = isset($_REQUEST['include_title']) ? true : false;
$visibility = isset($_REQUEST['visibility']) ? $_REQUEST['visibility'] : 'public';

// Validate data
if($title == '') {
	$admin->print_error($MESSAGE['PAGES']['BLANK_PAGE_TITLE']);
}

// The actual pagecloning
function clone_page($title,$parent,$pagetoclone,$copy_title,$visibility) {
	// Get objects and vars from outside this function
	global $admin, $template, $database, $TEXT, $PCTEXT, $MESSAGE;
	global $page_id, $section_id;
	// Get page list from database
	$query = "SELECT * FROM `".TABLE_PREFIX."pages` WHERE `page_id` = ".$pagetoclone;
	$get_page = $database->query($query);	 
	$is_page = $get_page->fetchRow( MYSQL_ASSOC ); 

	// Work-out what the link and page filename should be
	if($parent == '0') {
		$link = '/'.page_filename($title);
		$filename = WB_PATH.PAGES_DIRECTORY.$link.'.php';
	} else {
		$parent_section = '';
		$parent_titles = array_reverse(get_parent_titles($parent));
		foreach($parent_titles AS $parent_title) {
			$parent_section .= page_filename($parent_title).'/';
		}
		if($parent_section == '/') { $parent_section = ''; }
		$link = '/'.$parent_section.page_filename($title);
		$filename = WB_PATH.PAGES_DIRECTORY.'/'.$parent_section.page_filename($title).'.php';
		make_dir(WB_PATH.PAGES_DIRECTORY.'/'.$parent_section);
	}
	
	// Check if a page with same page filename exists
	$get_same_page = $database->query("SELECT `page_id` FROM `".TABLE_PREFIX."pages` WHERE `link` = '$link'");
	if($get_same_page->numRows() > 0 OR file_exists(WB_PATH.PAGES_DIRECTORY.$link.'.php') OR file_exists(WB_PATH.PAGES_DIRECTORY.$link.'/')) {
		$admin->print_error($MESSAGE['PAGES']['PAGE_EXISTS'],'tool_clone.php?pagetoclone='.$pagetoclone);
	}

  // check the title
  if($copy_title) {
      $page_title = $is_page['page_title'];
  } else {
      $page_title = $title;
  }

	// Include the ordering class
	$order = new order(TABLE_PREFIX.'pages', 'position', 'page_id', 'parent');
	// First clean order
	$order->clean($parent);
	// Get new order
	$position = $order->get_new($parent);
	
	// Insert page into pages table
	$template = $is_page['template'];
	$visibility = $visibility;
	$admin_groups = $is_page['admin_groups'];
	$viewing_groups = $is_page['viewing_groups'];
	$query = "INSERT INTO `".TABLE_PREFIX."pages` "
           . "(`page_title`,`menu_title`,`parent`,`template`,`target`,`position`,`visibility`,`searching`,`menu`,`language`,`admin_groups`,`viewing_groups`,`modified_when`,`modified_by`) VALUES ('"
           . ($database->escapeString($page_title))."','".($database->escapeString($title))."','$parent','$template','_top','$position','$visibility','1','1','".DEFAULT_LANGUAGE."','$admin_groups','$viewing_groups','".time()."','".$admin->get_user_id()."')";

	$database->query($query);
	if($database->is_error()) {
		$admin->print_error($database->get_error());
	}
	// Get the page id
	$page_id = $database->get_one("SELECT LAST_INSERT_ID()");
	
	// Work out level
	$level = level_count($page_id);
	// Work out root parent
	$root_parent = root_parent($page_id);
	// Work out page trail
	$page_trail = get_page_trail($page_id);
	
	// Update page with new level and link
	$database->query("UPDATE `".TABLE_PREFIX."pages` SET `link` = '$link', `level` = '$level', `root_parent` = '$root_parent', `page_trail` = '$page_trail' WHERE `page_id` = '$page_id'");
	
	// Create a new file in the /pages dir
	create_access_file($filename, $page_id, $level);
	
	// Make new sections, database
	$query = "SELECT * FROM `".TABLE_PREFIX."sections` WHERE `page_id` = '$pagetoclone'";
	$get_section = $database->query($query);	 
	while (false != ($is_section=$get_section->fetchRow( MYSQL_ASSOC ))) {
		// Add new record into the sections table
		$from_section = $is_section['section_id'];
		$position = $is_section['position'];
		$module = $is_section['module'];
		$block = $is_section['block'];
		$publ_start = $is_section['publ_start'];
		$publ_end = $is_section['publ_end'];
		$database->query("INSERT INTO `".TABLE_PREFIX."sections` (`page_id`,`position`,`module`,`block`,`publ_start`,`publ_end`) VALUES ('$page_id','$position', '$module','$block','$publ_start','$publ_end')");
	
		// Get the section id
		$section_id = $database->get_one("SELECT LAST_INSERT_ID()");
	
	  require(WB_PATH.'/modules/'.$module.'/info.php');	
		// Include the selected modules add file if it exists
		if(file_exists(WB_PATH.'/modules/'.$module.'/add.php')) {
			require(WB_PATH.'/modules/'.$module.'/add.php');
		}
		
		// copy module settings per section
  	$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE '%mod_".$module."%'";
  	$res = $database->query($query);
  	while ($row = $res->fetchRow()) {
  		// there must be a section_id column at least
      if ($database->query("DESCRIBE $row[0] section_id")) {
  		  clone_lines($row[0], $pagetoclone, $page_id, $from_section, $section_id, $database);
  		}
  	}
	  // some manual corrections that can not be automatically detected
		if ($module=='miniform') {
			// delete the form submissions which are also copied
			$query = "DELETE FROM ".TABLE_PREFIX."mod_miniform_data WHERE `section_id` = ".$section_id;
			$database->query($query);
		} elseif ($module=='mpform') {
			// delete the form submissions which are also copied
			$query = "DELETE FROM ".TABLE_PREFIX."mod_mpform_submissions WHERE `section_id` = ".$section_id;
			$database->query($query);
			// update refererence to result table
			$query = "UPDATE ".TABLE_PREFIX."mod_mpform_settings SET `tbl_suffix` = ".$section_id." WHERE `section_id` = ".$section_id;
			$database->query($query);
	    // new results table
      $results = TABLE_PREFIX."mod_mpform_results_".$section_id;
      $s = "CREATE TABLE `$results` ( `session_id` VARCHAR(20) NOT NULL,"
  		. ' `started_when` INT NOT NULL DEFAULT \'0\' ,'     	// time when first form was sent to browser
  		. ' `submitted_when` INT NOT NULL DEFAULT \'0\' ,'   	// time when last form was sent back to server
  		. ' `referer` VARCHAR( 255 ) NOT NULL, '				// referer page
  		. ' PRIMARY KEY ( `session_id` ) '
  		. ' )';
  		$database->query($s);
  		$query = "SELECT field_id FROM ".TABLE_PREFIX."mod_mpform_fields WHERE `section_id` = ".$section_id;
  		$ids = $database->query($query);
      while ($fid = $ids->fetchRow()) {
        // Insert new column into database
        $s = "ALTER TABLE `$results` add `field" . $fid[0] . "` TEXT NOT NULL";
        $database->query($s);
      }
    } elseif ($module=='form') {
			// delete the form submissions which are also copied
			$query = "DELETE FROM ".TABLE_PREFIX."mod_form_submissions WHERE `section_id` = ".$section_id;
			$database->query($query);
    } elseif ($module=='minigallery') {
			// copy images
			$mediaDir = WB_PATH.MEDIA_DIRECTORY;
			$src = $mediaDir."/minigallery/$from_section";
			$dst = $mediaDir."/minigallery/$section_id";
			recurse_copy($src, $dst);
    }
					        
	}
	return $page_id;
}

function clone_subs($pagetoclone,$parent,$copy_title,$visibility) {
	global $admin, $database;
	
	// Get page list from database
	$query = "SELECT * FROM `".TABLE_PREFIX."pages` WHERE `parent` = ".$pagetoclone." AND `page_id` <> ".$parent;
	$get_subpages = $database->query($query);
	
	if($get_subpages AND $get_subpages->numRows() > 0)	{
		while(false != ($page = $get_subpages->fetchRow( MYSQL_ASSOC ))) {
			#echo 'clonepage('.$page['page_title'].','.$parent.','.$page['page_id'].')<br>';
			$newnew_page = clone_page($page['menu_title'],$parent,$page['page_id'],$copy_title,$visibility);
			#echo 'clonesubs('.$page['page_id'].','.$newnew_page.','.$copy_title.','.$visibility.')<hr />';
			clone_subs($page['page_id'],$newnew_page,$copy_title,$visibility);
		}
	}
}

function clone_lines($tablename, $pagetoclone, $page_id, $from_section, $section_id, $database) {
	// we want to copy settings too, so delete default entries made by add function
	$query = "DELETE FROM $tablename WHERE `section_id` = ".$section_id;
	$database->query($query);
	// is there a page_id ?
  $query = $database->query("DESCRIBE $tablename `page_id`");
	$setPageId = $query->numRows() ? "`page_id` = $page_id," : "";

	$query = "SHOW COLUMNS FROM $tablename WHERE extra = 'auto_increment'";
	$colautoinc = $database->get_one($query);
	$autoinc = $colautoinc ? ", `$colautoinc` = NULL" : "";
	
  $query1 = "CREATE TEMPORARY TABLE tmp SELECT * FROM `$tablename` WHERE `section_id` = ".$from_section;
  $query2 = "UPDATE tmp SET $setPageId `section_id` = $section_id $autoinc";
  $query3 = "INSERT INTO `$tablename` SELECT * FROM tmp";	
	$query4 = "DROP TEMPORARY TABLE IF EXISTS tmp";

	for ($i = 1; $i < 5; $i++) {
		$database->query(${"query".$i});
	}
}

function recurse_copy($src,$dst) { 
  $dir = opendir($src); 
  mkdir($dst); 
  while( $file = readdir($dir)) { 
    if (( $file != '.' ) && ( $file != '..' )) { 
      if ( is_dir($src . '/' . $file) ) { 
        recurse_copy($src . '/' . $file,$dst . '/' . $file); 
      } else { 
        copy($src . '/' . $file,$dst . '/' . $file); 
      } 
    } 
  } 
  closedir($dir); 
} 

// Clone selected page
$new_page = clone_page($title,$parent,$pagetoclone,$copy_title,$visibility);

// Check if we need to clone subpages?
if ($include_subs == '1') {
	clone_subs($pagetoclone,$new_page,$copy_title,$visibility);
}
	
// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(),'tool_clone.php?pagetoclone='.$pagetoclone);
} else {
	$admin->print_success($MESSAGE['PAGES']['ADDED'], ADMIN_URL.'/pages/modify.php?page_id='.$new_page);
}
$admin->print_footer();
?>