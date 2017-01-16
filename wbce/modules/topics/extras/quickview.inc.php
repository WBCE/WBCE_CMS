<?php

// $Id: modify.php 600 2008-01-26 11:26:54Z thorn $

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
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

if (!$wb->is_authenticated()) { 
	echo '<h1>Hi!</h1><a href="'.WB_URL.'/account/login.php">Login</a>' ;

} else {

	if (!isset($settings_section_id)) exit('No $settings_section_id');
	$pA = explode(DIRECTORY_SEPARATOR,dirname(__FILE__));
	array_pop ($pA);
	$mod_dir = array_pop ($pA );
	$tablename = $mod_dir;

	
	// Load Language file
	if(LANGUAGE_LOADED) {
		if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
			require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
		} else {
			require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
		}
	}



	// include module_settings
	require_once(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
	require_once(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');
	require_once(WB_PATH.'/modules/'.$mod_dir.'/functions.php');


	$user_id = $wb->get_user_id();
	$user_in_groups = $wb->get_groups_id();
	
	$makeeditlink = false;
	$authoronly = false;
	$fredit = $fredit_default;
	if ($authorsgroup > 0) { //Care about users	
		if (in_array($authorsgroup, $user_in_groups)) {$authoronly = true; $fredit = 1;} //Ist nur Autor
	}
	
	if (in_array(1, $user_in_groups)) {	
		$authoronly = false; //An admin cannot be autor only
		$makeeditlink = true;
	}
	
	
	

	$showoptions = true;
	$authoronly = false;
	
	//Feststellen, welche Berechtigung der User innherlb von Topics hat:
	if (4==5) {
	if ($noadmin_nooptions > 0  OR $authorsgroup > 0) { //Care about users	
		$users_lowest_groupid = users_lowest_groupid($user_id);
		if ($noadmin_nooptions  > 0 AND $users_lowest_groupid > 1) {$showoptions = false;}
		if ($authorsgroup  > 0 AND $users_lowest_groupid == $authorsgroup) {$authoronly = true;}
	}
	}	

	//if (!$authoronly) {echo '<a href="'.ADMIN_URL.'/">ADMIN</a>'; return 0;}
	// Get settings
	$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$settings_section_id'");
	if($query_settings->numRows() != 1) {
		exit("So what happend with the settings?"); 
	}
	$settings_fetch = $query_settings->fetchRow();
	$use_timebased_publishing = $settings_fetch['use_timebased_publishing'];
	$sort_topics = $settings_fetch['sort_topics'];
	$sort_topics_by = ' position DESC';
	if ($sort_topics == 1) {$sort_topics_by =  ' published_when DESC, posted_first DESC';}
	if ($sort_topics == 2) {$sort_topics_by =   ' topic_score DESC';}

	$commentssearch = '';
	$mycommentsArr = array();
	$allcommentsArr = array();

	$user_idstr = '%,'.$user_id.',%';
	$query_topics = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE authors LIKE '".$user_idstr."' ORDER BY $sort_topics_by");
	if($query_topics->numRows() > 0) {
		$num_topics = $query_topics->numRows();	
		$counter = 0;
		$public = 0;
		$commentscount = 0;
		$picsurl = WB_URL.'/modules/'.$mod_dir.'/img/';
	
		$output1 = '';
		$output2 = '';
	
		while($topic = $query_topics->fetchRow()) {
	
			$posted_by  = $topic['posted_by']; //Owner
			$p_id = $topic['page_id'];
			$s_id = $topic['section_id'];
			$t_id = $topic['topic_id'];
		
			if ($commentssearch == '') {$commentssearch .= $t_id;} else {$commentssearch .= ','.$t_id;}
			$allcommentsArr[$t_id] = array($p_id, $s_id);
			$counter++;
		
			$active = $topic['active']; if ($active > 2) $public += 1;
			$params = 'page_id='.$p_id.$paramdelimiter.'section_id='.$s_id.$paramdelimiter.'topic_id='.$t_id; 
			$trclass = '';
		
			$modifylink = '<a title="'.$TEXT['MODIFY'].'" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?'.$params.'" >';
			if ($authoronly) {
				$authors = $topic['authors'];
				$pos = strpos ($authors,','.$user_id.',');
				if ($pos === false){$modifylink = ''; $trclass .= ' noedit';}
			}
			
			$tr = '<tr class="'.$trclass.'" valign="top">
			<td width="40" align="right">'.$modifylink;
			// Get number of comments
			$query_comments = $database->query("SELECT name FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id = '".$topic['topic_id']."'");
			$cc = $query_comments->numRows();
			$commentscount += $cc;
			$cp = 0; if ($cc > 0) {$cp = 1;} if ($cc > 2) {$cp = 2;} if ($cc > 5) {$cp = 3;}  if ($cc > 8) {$cp = 4;} 
			$tr .= '<img src="'.$picsurl.'comments'.$cp.'.gif" alt="'.$cc.' comments" /> ';
		
			$alt='ACTIVE_'.$active; $tr .=  '<img src="'.$picsurl.'active'.$active.'.gif" alt="'.$MOD_TOPICS[$alt].'" /></a>';
			$tr .= '</td><td>';
		
			$title = stripslashes($topic['title']);
			if ($title == '') {$title = 'Untitled';}
			$tr .= '<strong>'.$modifylink.$title.'</a></strong>'; if ($topic['short_description'] !='') {$tr .= '<div class="shortdesc">'.$topic['short_description'].'</div>';}
			
			$tr .= '</td><td class="topicprops" style="width:50px;">';
			
		
			if ($topic['hascontent'] > 0 AND $active > 0) { 
				$topic_link = WB_URL.$topics_directory.$topic['link'].PAGE_EXTENSION;
		 		$tr .=  '<a href="'.$topic_link.'" target="_blank" ><img src="'.THEME_URL.'/images/view_16.png" class="viewbutton" alt="View" /></a>';
			} 
		
			$tr .= '</td></tr>';
		
			if ($posted_by == $user_id) {
				$output1 .= $tr;
				$mycommentsArr[] = $t_id;
			} else {
				$output2 .= $tr;
			}
		}
		if ($output1 != '') {echo '<h2>You are owner of:</h2><table width="100%" border="0" cellspacing="0" cellpadding="3">'.$output1.'</table>';}
		if ($output2 != '') {echo '<h2>You can edit also:</h2><table width="100%" border="0" cellspacing="0" cellpadding="3">'.$output2.'</table>';}
	
		//-------------------------------------------------------------------------------------------------------------------------------------------------
		//Find the comments	
		$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id IN (".$commentssearch.") ORDER BY comment_id DESC";
	
	
		//echo '<h2>Comments</h2>';
	
		$output1 = '';
		$output2 = '';
		$query_comment = $database->query($theq); // 
		while($comment = $query_comment->fetchRow()) {
			$t_id = $comment['topic_id'];
			$editlink = WB_URL.'/modules/'.$mod_dir.'/modify_comment.php?page_id='.$allcommentsArr[$t_id][0].$paramdelimiter.'section_id='.$allcommentsArr[$t_id][1].$paramdelimiter.'comment_id='.$comment['comment_id'];
			$cwebsite = ($comment['website']);
			$nameLink = $comment['name'];
			if ($cwebsite != '') { $nameLink = '<a href="'.$cwebsite.'" target="_blank">'.$nameLink.'</a>';}
			$cout = '<p><strong>'.$nameLink. '</strong> ('.$comment['email'].') <strong><a href="'.$editlink.'">EDIT</a></strong><br/>';
			$cout .= nl2br($comment['comment']).'</p>';
		
			if (in_array($t_id, $mycommentsArr)) {
				$output1 .= $cout;			
			} else {
				$output2 .= $cout;
			}
		}
	
		if ($output1 != '') {echo '<h3>Comments about your topics:</h3><table width="100%" border="0" cellspacing="0" cellpadding="3">'.$output1.'</table>';}
		if ($output2 != '') {echo '<h3>You can edit also:</h3><table width="100%" border="0" cellspacing="0" cellpadding="3">'.$output2.'</table>';}
		
	
	} else {
		echo $TEXT['NONE_FOUND'].'<hr/>';
	}
	
}
?>

