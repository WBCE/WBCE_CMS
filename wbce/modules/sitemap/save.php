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

require('../../config.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

if ($admin->checkFTAN() == false) {
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);		
} 
//
// Update the mod_sitemap table with the contents
//
if(isset($_POST['header'])) {
	$header       = addslashes($_POST['header']);
	$sitemaploop  = addslashes($_POST['sitemaploop']);
	$footer       = addslashes($_POST['footer']);
	$level_header = addslashes($_POST['level_header']);
	$level_footer = addslashes($_POST['level_footer']);
	
	$static = 0;
	if(isset($_POST['static']) AND $_POST['static'] == 'true') {
		$static = 1;
	} 
	$startatroot = addslashes($_POST['startatroot']);
	$show_hidden = (isset($_POST['show_hidden'])? addslashes($_POST['show_hidden']) : '0');

	//
	// check the depth value	
	//
	$postdepth = trim(addslashes($_POST['depth']));
	$depth = $postdepth;
	if( ($postdepth == "") || ($postdepth <= 0) ) {
		$depth = 0;
	} 
	
	//
	// Work out what menus to use
	//
	$menus = "0";
	$post_menus   = $admin->get_post('menus');
	if($post_menus == ""){
		$post_menus = "0";
	}
	if($post_menus != 0){
		$menus = implode(",", array_keys($post_menus));
	}
	if(isset($_POST['all_menus']) && $_POST['all_menus'] == 0){
		$menus = "0";
	}
	
	$query = "UPDATE ".TABLE_PREFIX."mod_sitemap "
					. " SET header = '$header',"
					. " sitemaploop = '$sitemaploop',"
					. " footer = '$footer',"
					. " static = '$static',"
					. " level_header = '$level_header',"
					. " level_footer = '$level_footer',"
					. " startatroot = '$startatroot',"
					. " depth = '$depth',"
					. " menus = '$menus',"
					. " show_hidden = '$show_hidden'";
	$query .=  " WHERE section_id = '$section_id'";
	$database->query($query);
}

//
// Check if there is a database error, otherwise say successful
//
$goto = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], $goto);
}

// Print admin footer
$admin->print_footer();