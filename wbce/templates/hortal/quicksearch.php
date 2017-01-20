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

$starttime = array_sum(explode(" ",microtime()));

// Include config file
require_once(dirname(__FILE__).'/../../config.php');

// Check if the config file has been set-up
if(!defined('WB_PATH')) {
	header("Location: install/index.php");
	exit(0);
}
define('PAGE_ID',0);
define('PARENT',0);
require_once(WB_PATH.'/framework/class.frontend.php');
// Create new frontend object
$wb = new frontend();

// Load functions available to templates, modules and code sections
// also, set some aliases for backward compatibility
require(WB_PATH.'/framework/frontend.functions.php');

if ( isset($_REQUEST['q']) ) { $q = $_REQUEST['q']; } else {$q = '';}
//$q = preg_replace("/[^\\0-9a-zA-Z_\-\.\/]/", "", $q);  // only allow valid chars 
$q = addslashes($q);

//echo $q ;
$counter = 0;
if (strlen($q) > 0) {	
	$ergtext = '';
	
	
	if ($counter < 10) {	
		$theq = "SELECT title, link, page_id, topic_id FROM `".TABLE_PREFIX."mod_topics` WHERE CONCAT(`title`, `description`,`keywords`) LIKE '%".$q."%' AND  active > '3' ORDER BY `topic_id` DESC LIMIT 0 , 30";
		$res = $database->query($theq);
		
		
		if( $res AND $res->numRows() > 0) {			
			while($row = $res->fetchRow()) {
				$title = $row['title'];
				$counter ++;
				$link = WB_URL.PAGES_DIRECTORY.'/topics/'.$row['link'].PAGE_EXTENSION;
				
				//$p_id = $row['page_id'];							
				$ergtext .= '<li><a href="'.$link.'">'.$title.'</a></li>
				';
				if ($counter > 19) { break; }
			}
		}
		
	}
	
	
	if ($counter < 10) {	
		$theq = "SELECT * FROM `".TABLE_PREFIX."pages` WHERE visibility='public' AND searching=1 AND CONCAT(`page_title`, `menu_title`,`description`, `keywords`) LIKE '%".$q."%' LIMIT 0 , 30";
		$res = $database->query($theq);
		if($res->numRows() > 0) {			
			while($row = $res->fetchRow()) {
				$menu_title = $row['menu_title'];				
				$counter ++;
				$link = WB_URL.PAGES_DIRECTORY.$row['link'].PAGE_EXTENSION;
				
				//$p_id = $row['page_id'];							
				$ergtext .= '<li><a href="'.$link.'">'.$menu_title.'</a></li>
				';
				if ($counter > 19) { break; }
			}
		}
	}
	
	
	if ($ergtext !='') {
		echo '<ul><li class="suggesttop">&nbsp;</li>
		'.$ergtext.'
		<li class="suggestbottom">&nbsp;</li>
  		</ul><div style="height:1px; clear:both;"></div>';	
	}

}


?>

