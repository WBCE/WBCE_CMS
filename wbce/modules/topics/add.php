<?php

// $Id: add.php 563 2008-01-18 23:13:42Z Ruebenwurzel $

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
// obtain module directory
$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}

$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
if (!file_exists($mpath.'module_settings.php')) { copy($mpath.'defaults/module_settings.default.php', $mpath.'module_settings.php') ; }
if (!file_exists($mpath.'frontend.css')) { copy($mpath.'defaults/frontend.default.css', $mpath.'frontend.css') ; }
//if (!file_exists($mpath.'comment_frame.css')) { copy($mpath.'defaults/comment_frame.default.css', $mpath.'comment_frame.css') ; }
if (!file_exists($mpath.'frontend.js')) { copy($mpath.'defaults/frontend.default.js', $mpath.'frontend.js') ; }


require_once($mpath.'defaults/module_settings.default.php');
require_once($mpath.'module_settings.php');
require_once($mpath.'functions_small.php');
require_once($mpath.'defaults/add_settings.default.php');

$query_page = $database->query("SELECT menu_title, link FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$fetch_menu = $query_page->fetchRow();
$section_title = $fetch_menu['menu_title'];
$picture_dir = $picture_dir = MEDIA_DIRECTORY.'/'.$mod_dir.'-pictures'; 
$commenting = '2';
$default_link = '3';
$use_captcha = 1;
$sort_comments = '0';

$addstring = '';
$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$mod_dir."_settings WHERE section_id > 0 AND is_master_for = '' ORDER BY section_id DESC LIMIT 1");
if($query->numRows() == 1) {
	$sets = $query->fetchRow();	
	foreach ($sets as $key => $value) {
		if (is_numeric($key)) {continue;}
		if ($key == 'section_id' OR $key == 'page_id' OR $key == 'section_title')  {continue;}
		if ($value == '') {continue;}
		
		$addstring .= ', '.$key."='".addslashes($value)."'";
	}
	
	$theq = "INSERT INTO ".TABLE_PREFIX."mod_".$mod_dir."_settings SET ";
	$theq .= "section_id='".$section_id."', page_id='".$page_id."', section_title='".$section_title."' ". $addstring;	
	$database->query($theq);
} else {
	$theq = "INSERT INTO ".TABLE_PREFIX."mod_".$tablename."_settings (section_id,page_id,section_title,section_description,sort_topics,use_timebased_publishing,picture_dir,header,topics_loop,footer,topics_per_page,topic_header,topic_footer,topic_block2,pnsa_string,pnsa_max,comments_header,comments_loop,comments_footer,commenting,default_link,use_captcha,sort_comments) VALUES ('$section_id','$page_id','$section_title','','$sort_topics','$use_timebased_publishing','$picture_dir','$header','$topics_loop','$footer','$topics_per_page','$topic_header','$topic_footer','$topic_block2','$pnsa_string','$pnsa_max','$comments_header','$comments_loop','$comments_footer','$commenting','$default_link','$use_captcha','$sort_comments')";
	$database->query($theq);	
	include('defaults/first-topics.php');
}


?>
