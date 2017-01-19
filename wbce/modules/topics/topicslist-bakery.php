<?php

// $Id: save_topic.php 656 2008-11-30 22:53:02Z chio $

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

require('../../config.php');

require_once(WB_PATH."/include/jscalendar/jscalendar-functions.php");

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$id = $_GET['topic_id'];
	$topic_id = $id;
}

require('permissioncheck.php');


// Include WB functions file
require(WB_PATH.'/framework/functions.php');
// Include the ordering class
require(WB_PATH.'/framework/class.order.php');




// Get settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() != 1) {
	exit("So what happend with the settings?"); 
}
$settings_fetch = $query_settings->fetchRow();	
$sort_topics = $settings_fetch['sort_topics'];
$sort_topics_by = ' position DESC';
if ($sort_topics == 1) {$sort_topics_by =  ' published_when DESC';}
if ($sort_topics == 2) {$sort_topics_by =   ' topic_score DESC';}

//Find Links IN
$topic_idstr = '%,'.$topic_id.',%';
$tquery = "SELECT topic_id, see_also FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE see_also LIKE '".$topic_idstr."'";
//echo $tquery;
$query_bakery = $database->query($tquery);
$linkin_array = array();
if(($query_bakery->numRows()) > 0) {
	while($topic = $query_bakery->fetchRow()) { 
		$linkin_array[] = $topic['topic_id']; 
	}	
}

//get This Topic Params
$query_bakery = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE topic_id = '$topic_id'");
if (($query_bakery->numRows()) != 1) {
	exit("So what happend with the topic?"); 
}
$thistopic = $query_bakery->fetchRow();
$see_also_text = substr($thistopic['see_also'], 1, -1); 
$see_also_arr = explode(',',$see_also_text);
echo '<table class="topiclist-toptable"><tr><td><h3>'. $thistopic['title'].'</h3>';
if ($thistopic['short_description'] != '') {echo '<div class="short_description">'. $thistopic['short_description'].'</div>';}	
echo '<div class="short_content">'. $thistopic['content_short'].'</div>';
echo '</td><td class="parambox">';	
echo 'See Also Links: ' . count($see_also_arr) . '<br/>Topics linking in: ' . count($linkin_array);
echo '</td></tr></table><hr/>';



$limit_sql = ' LIMIT 100';
$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." ORDER BY ".$sort_topics_by.$limit_sql;
	//$query_bakery = $database->query($theq);

// Loop through existing topics
$query_bakery = $database->query("SELECT section_id, page_id, item_id, link, title, description, active FROM `".TABLE_PREFIX."mod_bakery_items` ORDER BY modified_when DESC");
if($query_bakery->numRows() > 0) {
	$num_topics = $query_bakery->numRows();
	$row = 'a';
	
	?>
	<form name="seealsolinks" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_seealso.php" method="post" style="margin: 0;">
     <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
	<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>" />
   
	<table class="topiclist-maintable">	
	<tr><td class="linkin">&nbsp;</td><td class="see_also"><img src="img/link_to.gif" class="viewbutton" alt="Linking TO" alt="link TO" /></td><td class="anleitung"><?php echo $MOD_TOPICS['LINK_TO']; ?></td><td>&nbsp;</td></tr>
	<?php
	$counter=0;
	while($bakery = $query_bakery->fetchRow()) {
		$counter++;
		$t_id = $bakery['item_id'];		
		$params = 'page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$t_id; 
		$active = $bakery['active'];
	
		echo '<tr class="mtr'.$active.'">';	
		?>		
		<td class="linkin">&nbsp;</td>			
		<td width="30" class="see_also">&nbsp;<?php  {	echo '<input type="checkbox" name="topiclinks[]" value="'.$t_id.'"'; if(in_array($t_id,$see_also_arr))  {echo " checked";} echo '/>';}?></td>
		<td class="topictd">
		
		
		
		<?php $topic_link = WB_URL.$bakery['link'].PAGE_EXTENSION;
			echo '<strong>'.stripslashes($bakery['title']).'</strong>';		
		?></td>
		<td class="topicprops">
		
		
		</td>
		</tr>
		<?php
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="left">
				<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"></form>
			</td>
			<td align="right">
				<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
	 </form>
	<?php
} else {
	echo $TEXT['NONE_FOUND'];
}


  
 //Print admin footer
  $admin->print_footer();
  
?>