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
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');
require_once(WB_PATH.'/framework/class.order.php');

// create admin object depending on platform (admin tools were moved out of settings with WB 2.7)
if(file_exists(ADMIN_PATH .'/admintools/tool.php')) {
	// since Website Baker 2.7
	$admin = new admin('admintools', 'admintools');
} else {
	// Website Baker prior to 2.7
	$admin = new admin('Settings', 'settings_advanced');
}

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
		$filename = WB_PATH.PAGES_DIRECTORY.'/'.page_filename($title).'.php';
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
           . mysqli_real_escape_string($database->db_handle,$page_title)."','".mysqli_real_escape_string($database->db_handle,$title)."','$parent','$template','_top','$position','$visibility','1','1','".DEFAULT_LANGUAGE."','$admin_groups','$viewing_groups','".time()."','".$admin->get_user_id()."')";

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
	
		// Include the selected modules add file if it exists
		if(file_exists(WB_PATH.'/modules/'.$module.'/add.php')) {
			require(WB_PATH.'/modules/'.$module.'/add.php');
		}
		// copy module settings per section
		if ($module=='wysiwyg') {
			$query = "SELECT * FROM `".TABLE_PREFIX."mod_wysiwyg` WHERE `section_id` = '$from_section'";
			$get_wysiwyg = $database->query($query);	 
			while ( false != ($is_wysiwyg=$get_wysiwyg->fetchRow( MYSQL_ASSOC ))) {
					// Update wysiwyg section with cloned data
					$content = addslashes($is_wysiwyg['content']);
				$text = addslashes($is_wysiwyg['text']);
				$query = "UPDATE `".TABLE_PREFIX."mod_wysiwyg` SET `content` = '$content', `text` = '$text' WHERE `section_id` = '$section_id'";
				$database->query($query);	
			}	
        } elseif ($module=='miniform') {
            $query = "SELECT * FROM `".TABLE_PREFIX."mod_miniform` WHERE `section_id` = '$from_section'";
            $get_formsettings = $database->query($query);
            $row = $get_formsettings->fetchRow(MYSQL_ASSOC);
            $database->query("UPDATE `".TABLE_PREFIX."mod_miniform` SET `email`='".$row['email']."', `subject`='".$row['subject']."', `template`='".$row['template']."', `successpage`='".$row['successpage']."'");
		} elseif ($module=='form') {
			$query = "SELECT * FROM `".TABLE_PREFIX."mod_form_settings` WHERE `section_id` = '$from_section'";
			$get_formsettings = $database->query($query);	 
			while ($is_formsettings=$get_formsettings->fetchRow(MYSQL_ASSOC)) {
					// Update formsettings section with cloned data
					$header = addslashes($is_formsettings['header']);
					$field_loop = addslashes($is_formsettings['field_loop']);
					$footer = addslashes($is_formsettings['footer']);
					$email_to = addslashes($is_formsettings['email_to']);
					$email_from = addslashes($is_formsettings['email_from']);
					$email_subject = addslashes($is_formsettings['email_subject']);
					$stored_submissions = $is_formsettings['stored_submissions'];
					$max_submissions = $is_formsettings['max_submissions'];
					$use_captcha = $is_formsettings['use_captcha'];
				    // *****************************************************************
				    // Changed by LEPTON Dev Team, 2012-01-25 / webbird
					// The table structure of the form module changed between the
					// versions of the form module, but it's not documented there;
					// so let's see which fields we have to handle
					// *****************************************************************
					$query  = "SHOW COLUMNS FROM `".TABLE_PREFIX."mod_form_settings`";
					$result = $database->query($query);
					if ( $result->numRows() > 0 ) {
					    while ( false !== ($row = $result->fetchRow(MYSQL_ASSOC) ) ) {
					    	$cols[$row['Field']] = 1;
						}
					    if ( array_key_exists( 'success_message', $cols ) ) {
					        $success_message = addslashes($is_formsettings['success_message']);
					        $database->query("UPDATE ".TABLE_PREFIX."mod_form_settings SET header = '$header', field_loop = '$field_loop', footer = '$footer', email_to = '$email_to', email_from = '$email_from', email_subject = '$email_subject', success_message = '$success_message', max_submissions = '$max_submissions', stored_submissions = '$stored_submissions', use_captcha = '$use_captcha' WHERE section_id = '$section_id'");
						}
						elseif ( array_key_exists( 'success_page', $cols ) ) {
					        $success_page = $is_formsettings['success_page'];
					        $success_email_to = addslashes($is_formsettings['success_email_to']);
					        $success_email_from = addslashes($is_formsettings['success_email_from']);
					        $success_email_fromname = addslashes($is_formsettings['success_email_fromname']);
					        $success_email_text = addslashes($is_formsettings['success_email_text']);
					        $success_email_subject = addslashes($is_formsettings['success_email_subject']);
					        $database->query("UPDATE ".TABLE_PREFIX."mod_form_settings SET header = '$header', field_loop = '$field_loop', footer = '$footer', email_to = '$email_to', email_from = '$email_from', email_subject = '$email_subject', success_page = '$success_page', success_email_to = '$success_email_to', success_email_from = '$success_email_from', success_email_fromname = '$success_email_fromname', success_email_text = '$success_email_text', success_email_subject = '$success_email_subject', max_submissions = '$max_submissions', stored_submissions = '$stored_submissions', use_captcha = '$use_captcha' WHERE section_id = '$section_id'");
						}
					}
			}
	 		$query = "SELECT * FROM `".TABLE_PREFIX."mod_form_fields` WHERE `section_id` = '$from_section'";
			$get_formfield = $database->query($query);	 
			while (false != ($is_formfield=$get_formfield->fetchRow( MYSQL_ASSOC ))) {
					// Insert formfields with cloned data
					$position = $is_formfield['position'];
					$title = addslashes($is_formfield['title']);
					$type = $is_formfield['type'];
					$required = $is_formfield['required'];
					$value = $is_formfield['value'];
					$extra = addslashes($is_formfield['extra']);
					$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_fields (section_id, page_id, position, title, type, required, value, extra) VALUES ('$section_id','$page_id','$position','$title','$type','$required','$value','$extra')");
			}	
		} elseif ($module=='mpform') {
        	/**
        	*	@version	0.5.2
        	*	@date		2010-08-08
        	*	@author		Stephan Kuehn (vBoedefeld)
        	*	@package	Websitebaker - Modules: page-cloner
        	*	@state		RC
        	*	@notice		Just add type "mpform" for MPForm-module
        	*/
        	$query = "SELECT * FROM ".TABLE_PREFIX."mod_mpform_settings WHERE section_id = '$from_section'";
        	$get_formsettings = $database->query($query);	 
        	while ( false != ($is_formsettings=$get_formsettings->fetchRow( MYSQL_ASSOC ))) {
        			// Update formsettings section with cloned data
        			$header = addslashes($is_formsettings['header']);
        			$field_loop = addslashes($is_formsettings['field_loop']);
        			$footer = addslashes($is_formsettings['footer']);
        			$email_to = addslashes($is_formsettings['email_to']);
        			$email_from = addslashes($is_formsettings['email_from']);
        			$email_fromname = addslashes($is_formsettings['email_fromname']);
        			$email_subject = addslashes($is_formsettings['email_subject']);
        			$email_text = addslashes($is_formsettings['email_text']);
        			$success_page = addslashes($is_formsettings['success_page']);
        			$success_text = addslashes($is_formsettings['success_text']);
        			$submissions_text = addslashes($is_formsettings['submissions_text']);
        			$success_email_to= addslashes($is_formsettings['success_email_to']);
        			$success_email_from = addslashes($is_formsettings['success_email_from']);
        			$success_email_fromname = addslashes($is_formsettings['success_email_fromname']);
        			$success_email_text = addslashes($is_formsettings['success_email_text']);
        			$success_email_subject = addslashes($is_formsettings['success_email_subject']);
        			$stored_submissions = $is_formsettings['stored_submissions'];
        			$max_submissions = $is_formsettings['max_submissions'];
        			$heading_html = addslashes($is_formsettings['heading_html']);
        			$short_html = addslashes($is_formsettings['short_html']);
        			$long_html = addslashes($is_formsettings['long_html']);
        			$email_html = addslashes($is_formsettings['email_html']);
        			$uploadfile_html = addslashes($is_formsettings['uploadfile_html']);
        			$use_captcha = $is_formsettings['use_captcha'];
        			$upload_files_folder = addslashes($is_formsettings['upload_files_folder']);
        			$date_format = addslashes($is_formsettings['date_format']);
        			$max_file_size_kb= $is_formsettings['max_file_size_kb'];
        			$attach_file = $is_formsettings['attach_file'];
        			$upload_file_mask = addslashes($is_formsettings['upload_file_mask']);
        			$upload_dir_mask = addslashes($is_formsettings['upload_dir_mask']);
        			$upload_only_exts = addslashes($is_formsettings['upload_only_exts']);
        			$is_following = $is_formsettings['is_following'];
        			$tbl_suffix = addslashes($is_formsettings['tbl_suffix']);
        			$enum_start = addslashes($is_formsettings['enum_start']);
                    if($tbl_suffix == $from_section) {
                        $tbl_suffix = $section_id;
                    }
                    else {
                        $tbl_suffix .= $section_id;
                    }
              $database->query("UPDATE ".TABLE_PREFIX."mod_mpform_settings SET header = '$header', field_loop = '$field_loop', footer = '$footer', email_to = '$email_to', email_from = '$email_from', email_fromname = '$email_fromname', email_subject = '$email_subject', email_text = '$email_text', success_page = '$success_page', success_text = '$success_text', submissions_text = '$submissions_text', success_email_to = '$success_email_to', success_email_from = '$success_email_from', success_email_fromname = '$success_email_fromname', success_email_text = '$success_email_text', success_email_subject = '$success_email_subject', stored_submissions = '$stored_submissions', max_submissions = '$max_submissions', heading_html = '$heading_html', short_html = '$short_html', long_html = '$long_html', email_html = '$email_html', uploadfile_html = '$uploadfile_html', use_captcha = '$use_captcha', upload_files_folder = '$upload_files_folder', date_format = '$date_format', max_file_size_kb = '$max_file_size_kb', attach_file = '$attach_file', upload_file_mask = '$upload_file_mask', upload_dir_mask = '$upload_dir_mask', upload_only_exts = '$upload_only_exts', is_following = '$is_following', tbl_suffix = '$tbl_suffix', enum_start = '$enum_start' WHERE section_id = '$section_id'");
        	}	
         	$query = "SELECT * FROM ".TABLE_PREFIX."mod_mpform_fields WHERE section_id = '$from_section'";
        	$get_formfield = $database->query($query);	 
            $new_form_fields = array();
        	while (false != ($is_formfield=$get_formfield->fetchRow( MYSQL_ASSOC ))) {
        			// Insert formfields with cloned data
        			$position = $is_formfield['position'];
        			$title = addslashes($is_formfield['title']);
        			$type = $is_formfield['type'];
        			$required = $is_formfield['required'];
        			$value = $is_formfield['value'];
        			$extra = addslashes($is_formfield['extra']);
        			$help = addslashes($is_formfield['help']);
        			$database->query("INSERT INTO ".TABLE_PREFIX."mod_mpform_fields (section_id, page_id, position, title, type, required, value, extra, help) VALUES ('$section_id','$page_id','$position','$title','$type','$required','$value','$extra', '$help')");
                    $new_form_fields[] = $database->get_one("SELECT LAST_INSERT_ID()");
        	}	

            // clone results table
            $results = TABLE_PREFIX."mod_mpform_results_".$tbl_suffix;
            $s = "CREATE TABLE `$results` ( `session_id` VARCHAR(20) NOT NULL,"
        		. ' `started_when` INT NOT NULL DEFAULT \'0\' ,'     	// time when first form was sent to browser
        		. ' `submitted_when` INT NOT NULL DEFAULT \'0\' ,'   	// time when last form was sent back to server
        		. ' `referer` VARCHAR( 255 ) NOT NULL, '				// referer page
        		. ' PRIMARY KEY ( `session_id` ) '
        		. ' )';
        	$database->query($s);
            foreach($new_form_fields as $f) {
                // Insert new column into database
                $s = "ALTER TABLE `$results` add `field" . $f . "` TEXT NOT NULL";
                $database->query($s);
            }

        } elseif ($module=='code') {
			$query = "SELECT * FROM `".TABLE_PREFIX."mod_code` WHERE `section_id` = ".$from_section;
			$get_code = $database->query($query);	 
			while (false != ($is_code=$get_code->fetchRow( MYSQL_ASSOC ))) {
					// Update new section with cloned data
					$content = addslashes($is_code['content']);
					$database->query("UPDATE `".TABLE_PREFIX."mod_code` SET `content` = '".$content."' WHERE `section_id` =".$section_id );
				}
		} elseif ($module=='code2') {
			/**
			*	@version	0.5.1
			*	@date		2008-09-04
			*	@author		Dietrich Roland Pehlke (aldus)
			*	@package	Websitebaker - Modules: page-cloner
			*	@state		RC
			*	@notice		Just add type "code2" for code2-modules
			*/
			$query = "SELECT `content`,`whatis` FROM `".TABLE_PREFIX."mod_code2` WHERE `section_id` =".$from_section;
			$get_code = $database->query($query);	 
			while (false != ($is_code = $get_code->fetchRow( MYSQL_ASSOC ))) {
				// Update new section with cloned data
				$content = addslashes($is_code['content']);
				$database->query("UPDATE `".TABLE_PREFIX."mod_code2` SET `content` =\"".$content."\", `whatis`=".$is_code['whatis']." WHERE `section_id` ='".$section_id."'" );
			}
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