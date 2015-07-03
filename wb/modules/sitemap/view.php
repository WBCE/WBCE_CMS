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

// Get settings
$get_settings = $database->query("SELECT header,sitemaploop,footer,level_header,level_footer,static,startatroot,depth,show_hidden FROM ".TABLE_PREFIX."mod_sitemap WHERE section_id = '$section_id'");
$fetch_settings = $get_settings->fetchRow();
$header = stripslashes($fetch_settings['header']);
$sitemaploop = stripslashes($fetch_settings['sitemaploop']);
$footer = stripslashes($fetch_settings['footer']);
$level_header = stripslashes($fetch_settings['level_header']);
$level_footer = stripslashes($fetch_settings['level_footer']);
$static = $fetch_settings['static'];
$startatroot = $fetch_settings['startatroot'];
$depth = $fetch_settings['depth'];
$show_hidden = $fetch_settings['show_hidden'];


// Set Show Hidden to false
echo $header;

if($static == true OR $static == false) {
	
	// Set private sql extra code
	if(FRONTEND_LOGIN == 'enabled' AND is_numeric($admin->get_session('USER_ID'))) {
		$private_sql = ",viewing_groups,viewing_users";
		$private_where_sql = "visibility != 'none'";
	} else {
		$private_sql = "";
		$private_where_sql = "visibility = 'public'";
	}
	
	// determine sitemap starting point
	global $page_id;
	switch($startatroot) {
		case 2:
			// value 2 means start at current page
			$parent = $page_id;
			break;

		case 3:
			// value 3 means start at parent of current page
			global $database;
			$query_parent_id = $database->query("SELECT parent FROM ".TABLE_PREFIX."pages WHERE page_id='$page_id'");			
			if($query_parent_id->numRows() > 0) {
				$parentrow = $query_parent_id->fetchRow();
				$parent = $parentrow['parent'];
			}
			break;
	
		case 1:
		default:
			// start at site root
			$parent = 0;
			break;
	}	

	// sanity check on parent, check for smaller than 1 because of string cast
	if( (trim($parent) == "") || ($parent < 1) ) {
		$parent = 0;
	}
	
	// build the sitemap
	sitemap($level_header, $sitemaploop, $level_footer,$show_hidden,$parent, $depth);
}

echo $footer;

// Function to build sitemap
function sitemap($level_header, $sitemaploop, $level_footer, $show_hidden, $parent = 0, $to_depth = 0, $current_depth = 0) {
	global $database;
	
	if( $show_hidden==1){
		$where_sql="visibility != 'deleted'";
	} else {
		$where_sql="visibility = 'public'";
	}
	// Query pages
	$query_menu = $database->query("SELECT `page_id`,`parent`,`link`,`page_title`,`menu_title`,`description`,`keywords`,`modified_when`,`modified_by`,`link`,`target`,`visibility` FROM `".TABLE_PREFIX."pages` WHERE $where_sql AND parent = '$parent' ORDER BY position ASC");
	
	// Check if there are any pages to show
	if($query_menu->numRows() > 0) {
		// Print level header
		echo $level_header;
		
		// Loop through pages
		$new_depth = $current_depth + 1;
		while($page = $query_menu->fetchRow()) {
			
			//get username from user id
			$userquery="SELECT display_name FROM ".TABLE_PREFIX."users WHERE user_id = ".$page['modified_by'];
			$query_user=$database->query($userquery);
			$user=$query_user->fetchRow();
			
			$vars = array('[PAGE_ID]', '[PARENT]', '[LINK]', '[PAGE_TITLE]', '[MENU_TITLE]', '[DESCRIPTION]', '[KEYWORDS]', '[TARGET]', '[MODIFIED_WHEN]', '[MODIFIED_BY]', '[MODIFIED_DATE]','[MODIFIED_TIME]' );
			$values = array($page['page_id'], $page['parent'], page_link($page['link']), stripslashes($page['page_title']), stripslashes($page['menu_title']), stripslashes($page['description']), stripslashes($page['keywords']), $page['target'],"[MODIFIED_DATE] [MODIFIED_TIME]",$user['display_name'],date(DATE_FORMAT, $page['modified_when']),date(TIME_FORMAT, $page['modified_when']));
			echo str_replace($vars, $values, $sitemaploop);
			 //gmdate(DATE_FORMAT, $page['modified_when'] +TIMEZONE)." - ". gmdate(TIME_FORMAT, $page['modified_when'] +TIMEZONE)
			// determine if we may descend further in the subpages
			if( $new_depth != $to_depth ) {
				// pass on the maximum and current depth
				sitemap($level_header, $sitemaploop, $level_footer,$show_hidden, $page['page_id'], $to_depth, $new_depth);
			}
		}
		
		// Print level footer
		echo $level_footer;
	}
}

?>