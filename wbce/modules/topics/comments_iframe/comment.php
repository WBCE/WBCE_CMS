<?php

// $Id: comment.php 606 2008-01-26 20:54:42Z thorn $

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

// Include config file
require('../../../config.php');
require('../info.php');
$mod_dir = $module_directory;
$tablename = $mod_dir;

// Check if there is a post id
if(!isset($_GET['id']) OR !is_numeric($_GET['id']) OR !isset($_GET['sid']) OR !is_numeric($_GET['sid'])) {
	header("Location: ".WB_URL.'/modules/'.$mod_dir.'/comments_iframe/nopage.php?err=1');
	exit(0);
}
$topic_id = (int) $_GET['id'];
$section_id = (int) $_GET['sid'];
if (($topic_id  * $section_id) == 0) {
	header("Location: ".WB_URL.'/modules/'.$mod_dir.'/comments_iframe/nopage.php?err=2');
	exit(0);
}


if (isset($_GET['nok'])) {$nok = $_GET['nok'];} else {$nok=0;}
if (isset($_COOKIE['comment'.$topic_id])) {
	$cArr = explode(',', $_COOKIE['comment'.$topic_id]);	
	$the_comment = (int) $cArr[0];
	$ct = time() - (int) $cArr[1];
	
	if ($ct > 300 OR $ct < 0) {$the_comment=0;} //schon lange abgelaufen
} else {
	$the_comment=0;
}

if ($nok <> 1) {	
	if ($the_comment > 0) {
		header("Location: ".WB_URL.'/modules/'.$mod_dir.'/comments_iframe/commentdone.php?cid='.$the_comment.'&tid='.$topic_id );
		exit(0);
	}
}

// Query post for page id
$res = $database->query("SELECT topic_id,title,section_id,page_id FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$topic_id'");
if($res->numRows() == 0) {
	header("Location: ".WB_URL.'/modules/'.$mod_dir.'/comments_iframe/nopage.php?err=3');
	exit(0);
} else {
	$fetch_topic = $res->fetchRow();
	$page_id = $fetch_topic['page_id'];	
	$section_id = $fetch_topic['section_id'];
	//$topic_id = $fetch_topic['topic_id'];
	$topic_title = $fetch_topic['title'];
	define('PAGE_ID', $page_id);
	define('SECTION_ID', $section_id);
	define('TOPIC_ID', $topic_id);
	define('POST_TITLE', $topic_title);
	
	
	
		
	
	// don't allow commenting if its disabled, or if post or group is inactive
	//--------------------------------------------------------
	//ausgeschaltet:
	//Topics können auch kommentiert werden, wenn Seiten nicht aktiv sind.
	
	/*
	$t = time();
	$table_posts = TABLE_PREFIX."mod_topics";	
	$query = $database->query("
		SELECT p.topic_id
		FROM $table_posts AS p LEFT OUTER JOIN $table_groups AS g ON p.group_id = g.group_id
		WHERE p.topic_id='$topic_id' AND p.commenting != 'none' AND p.active = '1' AND ( g.active IS NULL OR g.active = '1' )
		AND (p.published_when = '0' OR p.published_when <= $t) AND (p.published_until = 0 OR p.published_until >= $t)
	");
	if($query->numRows() == 0) {
		header("Location: ".WB_URL.PAGES_DIRECTORY."");
		exit(0);
	}
//--------------------------------------------------
*/
	// don't allow commenting if ASP enabled and user doesn't comes from the right view.php
	if(ENABLED_ASP && (!isset($_SESSION['comes_from_view']) OR $_SESSION['comes_from_view']!=TOPIC_ID)) {
		header("Location: ".WB_URL.'/modules/'.$mod_dir.'/comments_iframe/nopage.php?err=4');
		exit(0);
	}

	// Get page details
	$query_page = $database->query("SELECT parent,page_title,menu_title,keywords,description,visibility FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
	if($query_page->numRows() == 0) {
		header("Location: ".WB_URL.'/modules/'.$mod_dir.'/comments_iframe/nopage.php?err=5');
		exit(0);
	} else {
		
		
		$page = $query_page->fetchRow();
		define('PARENT', $page['parent']);
		

		// Required page details
		define('PAGE_CONTENT', WB_PATH.'/modules/'.$mod_dir.'/comments_iframe/comment_page.php');
		// Include index (wrapper) file
		//require(WB_PATH.'/index.php');
		
		//von Chio eingefuegt
		require(WB_PATH.'/modules/'.$mod_dir.'/comments_iframe/commentframe.php');
		
		
	}
}

?>