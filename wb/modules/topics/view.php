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
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Load Language file
$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}

require(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
require(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');

if(!defined('TOPICS_DIRECTORY')) {define('TOPICS_DIRECTORY', $topics_directory);}
if(!defined('TOPICS_DIRECTORY_DEPTH')) {define('TOPICS_DIRECTORY_DEPTH', $topics_directory_depth);}

require_once(WB_PATH.'/modules/'.$mod_dir.'/functions_small.php');

//$topics_use_wysiwyg = 1;

// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&  file_exists(WB_PATH .'/modules/'.$mod_dir.'/frontend.css')) {
   echo '<style type="text/css">';
   include(WB_PATH .'/modules/'.$mod_dir.'/frontend.css');
   echo "\n</style>\n";
} 



// Check if there is a start point defined
if(isset($_GET['p']) AND is_numeric($_GET['p']) AND $_GET['p'] >= 0) {
	$showoffset = $_GET['p'];
	if ($showoffset < 1) {$showoffset = 0;}
} else {
	$showoffset = 0;
}


$t = topics_localtime();


$makeeditlink = false;
$authoronly = false;
$fredit = $fredit_default;
if($wb->is_authenticated()) {	
	$user_id = $admin->get_user_id();
	$user_in_groups = $admin->get_groups_id();
	if ($authorsgroup > 0) { //Care about users	
		if (in_array($authorsgroup, $user_in_groups)) {$authoronly = true; $fredit = 1;} //Ist nur Autor
	}
	
	if (in_array(1, $user_in_groups)) {	
		$authoronly = false; //An admin cannot be autor only
		$makeeditlink = true;
	}
}

//Check if there is an error in the access-file
/*if (defined('TOPIC_ID')) {
	$t_id = TOPIC_ID;
	$query_topic = $database->query("SELECT section_id FROM ".TABLE_PREFIX."mod_topics WHERE topic_id = '$t_id'");
	$fetch_section= $query_topic->fetchRow();
	if ($section_id != $fetch_section['section_id'])  {echo '<h2>Section_id mismatch!</h2><h3>Accessfile section_id '.$section_id.'<br/>Database section_id '.$fetch_section['section_id'].'</h3><hr/>';}
	$section_id = $fetch_section['section_id'];
}*/


// Get settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() != 1) {
	exit("So what happend with the settings?"); 
}
$settings_fetch = $query_settings->fetchRow();

$is_master = '';
if (isset($settings_fetch['is_master_for']) AND $settings_fetch['is_master_for'] != '') {
	$is_master = $settings_fetch['is_master_for'];
} 

//-------------------------------------------------------------------------------------------------------------------------------------------
//Sorting things:	
$sort_topics = $settings_fetch['sort_topics'];
$sort_topics_default = $sort_topics;

// Different sorting by GET?
if(isset($_GET['sort']) AND is_numeric($_GET['sort'])) {
	$sort_topics = $_GET['sort'];	
}
// Different sorting by accessFile or Code-Section?
if(defined("TOPICS_SORTBY") AND is_numeric(TOPICS_SORTBY)) {
	$sort_topics = TOPICS_SORTBY;	
}

/*
$sort_topics_by = ' position DESC';
if ($sort_topics >= 0) {
	if ($sort_topics == 1) {$sort_topics_by = ' published_when DESC, posted_first DESC';}
	if ($sort_topics == 2) {$sort_topics_by = ' topic_score DESC';}
	if ($sort_topics == 3) {$sort_topics_by = ' published_when ASC';}
	if ($sort_topics == 4) {$sort_topics_by = ' title ASC';}
} else {
	//Reversed sorting:
	$sort_topics_by = ' position ASC';
	if ($sort_topics == -2) {$sort_topics_by = ' published_when ASC, posted_first ASC';}
	if ($sort_topics == -3) {$sort_topics_by = ' topic_score ASC';}
	if ($sort_topics == -4) {$sort_topics_by = ' published_when DESC';}
	if ($sort_topics == -5) {$sort_topics_by = ' title DESC';}
}
*/
$sort_topics_by = get_sort_topics_by($sort_topics);
//echo $sort_topics;
//-------------------------------------------------------------------------------------------------------------------------------------------
//Query things:
$use_timebased_publishing = $settings_fetch['use_timebased_publishing'];
//0, 1: none, only date fields visible
//2: yes, like news module
//3: autoarchive
		

$query_extra = '';
$autoarchiveArr = 0;

if ($is_master == '') {
	//autoarchive
	if ($use_timebased_publishing > 2) {
		$autoarchiveArr = explode(',',$settings_fetch['autoarchive']);
		//check values:
		if ($autoarchiveArr[0] == 0 OR $autoarchiveArr[1] == 0) {
			$autoarchiveArr = 0;
		} else {
			$use_timebased_publishing = 0; //switch to off, check this later;
		} 
	}
	
	$sectionquery = "section_id = '".$section_id."'"; 
		
} else {
	$use_timebased_publishing = 2; //Topic Master: IMMER Timebased Publishing
	
	$is_master_Arr = explode(',', $is_master);
	if (is_numeric(trim($is_master_Arr[0]))) {
		$sectionquery = "section_id IN (".$is_master.")";
	} else {		
		if (!is_numeric(trim($is_master_Arr[0]))) {
			$theq = "SELECT section_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE picture_dir = '".$settings_fetch['picture_dir']."'";
			$query_others = $database->query($theq);
			if(!$database->is_error()) {		
				if($query_others->numRows() > 0) { 		
					$secArr = array();
					while($sec = $query_others->fetchRow()) {		
						$secArr[] = $sec['section_id'];
					}				
					$sectionquery = "section_id IN (".implode(',',$secArr).")";
				}
			}
		}
	}	
}


if ($use_timebased_publishing == 2) { $query_extra = " AND (published_when = '0' OR published_when < $t) AND (published_until = '0' OR published_until > $t) ";}
//Usage as event calendar:
if ($autoarchiveArr == 0) {
	if ($sort_topics == 3) {$query_extra = ' AND published_when >= '.$t.' ';}
	if ($sort_topics == -4) {$query_extra = ' AND published_when < '.$t.' ';} //Reversed = Archive
}

//-------------------------------------------------------------------------------------------------------------------------------------------
//various things:
$minimum_commentedtime = $t - $topics_comment_cookie; //Seconds

$picture_dir = WB_URL.$settings_fetch['picture_dir'];
$section_title = $settings_fetch['section_title'];
$section_description = $settings_fetch['section_description'];

if (is_numeric($section_description)) { //Get a section content
	$s = (int) $section_description;
	$section_description = get_any_sections ($s);
}

//various values

if(!isset($settings_fetch['various_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `various_values` VARCHAR(255) NOT NULL DEFAULT '150,450,0,0'");
	echo '<h2>Database Field "various_values" added</h2>';
	$vv = explode(',','-2,-2,-2,-2,-2');
} else {
	$vv = explode(',',$settings_fetch['various_values'].',-2,-2,-2,-2,-2');
} 

$use_commenting_settings = (int) $vv[3];
if ($use_commenting_settings < 0) {$use_commenting_settings = 0;}

$short_textareaheight = (int) $vv[0]; if ($short_textareaheight == -2) {$short_textareaheight = 150;}
$short_textareaheight = 150; //always 150 in this version, compatibility problems

$long_textareaheight = (int) $vv[1]; if ($long_textareaheight == -2) {$long_textareaheight = 400;}
$extra_textareaheight = (int) $vv[2]; if ($extra_textareaheight == -2) {$extra_textareaheight = 300;}
$use_commenting_settings = (int) $vv[3]; if ($use_commenting_settings < 0) {$use_commenting_settings = 0;}
$maxcommentsperpage = (int) $vv[5]; if ($maxcommentsperpage < 0) {$maxcommentsperpage = 0;}
$commentstyle = (int) $vv[6];


if(!isset($settings_fetch['picture_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `picture_values` VARCHAR(255) NOT NULL DEFAULT ''");
	echo '<h2>Database Field "picture_values" added</h2>';
	$pv = explode(',','-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
} else {
	$pv = explode(',',$settings_fetch['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
} 

$w_zoom = (int) $pv[0]; if ($w_zoom == -2) {$w_zoom = 1000;}
$h_zoom = (int) $pv[1]; if ($h_zoom == -2) {$h_zoom = 0;}
$w_view = (int) $pv[2]; if ($w_view == -2) {$w_view = 200;}
$h_view = (int) $pv[3]; if ($h_view == -2) {$h_view = 0;}
$w_thumb = (int) $pv[4]; if ($w_thumb == -2) {$w_thumb = 100;}
$h_thumb = (int) $pv[5]; if ($h_thumb == -2) {$h_thumb = 100;}
$zoomclass = $pv[6]; if ($zoomclass == "-2") {$zoomclass = "fbx";}
$zoomrel = $pv[7]; if ($zoomrel == "-2") {$zoomclass = "fancybox";}

$zoomclass2 = $pv[14]; if ($zoomclass2 == "-2") {$zoomclass2 = "fbx";}
$zoomrel2 = $pv[15]; if ($zoomrel2 == "-2") {$zoomclass2 = "fancybox";}




	
//Prepare Query:
$qactive = " active > '3' ";	
if ($wb->is_authenticated()) {$qactive = " (active > '3' OR active = '1') ";}

// Check if there is a specific topic defined
if (isset($_GET['topic_id']) AND is_numeric($_GET['topic_id']) AND $_GET['topic_id'] >= 0) {
	if(!defined('TOPIC_ID')) {define('TOPIC_ID',$_GET['topic_id']); }	
}


$singletopic_id = 0;
$singletopic_link = '';

$setting_topics_per_page = $settings_fetch['topics_per_page'];
$limit_sql = '';
if($setting_topics_per_page == 1) { 
	//This is a Single Topics Page, figure out the TOPIC ID
	
	//TODO: Das könnte bei TopicMasters Ärger machen.
	$theq = "SELECT topic_id, link, page_id FROM ".TABLE_PREFIX."mod_".$tablename." WHERE ".$sectionquery." AND ". $qactive.$query_extra." ORDER BY ".$sort_topics_by." LIMIT 1";
	
	$query_topics = $database->query($theq);
	$num_topics = $query_topics->numRows();
	if ($num_topics == 1) {
		$topic = $query_topics->fetchRow();
		$topic_link = WB_URL.$topics_virtual_directory.$topic['link'].PAGE_EXTENSION;
		$singletopic_id = $topic['topic_id'];
		
		// If THIS is a single-topic page, we need to change all topiclinks to the main page 
		$query_page = $database->query("SELECT link FROM ".TABLE_PREFIX."pages WHERE page_id = '".$topic['page_id']."'");
		if($query_page->numRows() < 1) { exit('Page not found'); }	
		$page = $query_page->fetchRow();
		$singletopic_link = page_link($page['link']);
	}
}

							
// Check if we should show the list page or a topic itself
if(!defined('TOPIC_ID') OR !is_numeric(TOPIC_ID)) {
	

	//--------------------------------------------------------------------------------------------------------------------------	
	//Main Page (List)
	
	// Work-out if we need to add limit code to sql OR if this is a single topic	
	$setting_topics_per_page = $settings_fetch['topics_per_page'];
	$limit_sql = '';
	if($setting_topics_per_page == 1) { 	
		//This is a Single Topics Page; Define the TOPIC ID
		define('TOPIC_ID',$singletopic_id);
		
	} else {
		//This is the Main Page
		if($setting_topics_per_page > 1) {  $limit_sql = " LIMIT $showoffset,$setting_topics_per_page"; }		
		// Show the main page (Overview)
		include('view.list.php');			
	}
	
} 

if(defined('TOPIC_ID') AND is_numeric(TOPIC_ID)) {
	include('view.topic.php');
}

?>