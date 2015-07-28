<?php

if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }


if (($topic_id * $cid) == 0)  {exit ('no topic_id, no comment_id');} //no topic_id, no comment_id;


$query_topic = $database->query("SELECT section_id,commenting FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '".$topic_id."'");
$commenting = 0;
if($query_topic->numRows() > 0) { 
	$fetch_topic = $query_topic->fetchRow();
	$section_id  = $fetch_topic['section_id'];
	$commenting = $fetch_topic['commenting'];	
}

//var_dump($settings_fetch);
$setting_comments_loop = $settings_fetch['comments_loop']; //} else {$section_id = 0;}

//various values
$use_commenting_settings = 0;
if ($settings_fetch['various_values'] != '') {
	$vv = explode(',',$settings_fetch['various_values']);		
	if (count($vv) > 3) {$use_commenting_settings = (int) $vv[3];}
}
if ($use_commenting_settings > 0) { $commenting = $settings_fetch['commenting']; }


if ($commenting < 1 ) {exit ('No Commenting');}

if (($section_id * $cid) > 0) {


// Query for comments
	$query_comments = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE comment_id = '".$cid."'");
	if($query_comments->numRows() == 1) {
	
		if ($commenting < 2 ) {
			echo '<h4>'.$MOD_TOPICS['COMMENT_MODERATE'].'</h4>';		
		} else {
		
			$comment = $query_comments->fetchRow();			
			$thecomment = nl2br($comment['comment']);
			$name = $comment['name'];
			
			$nameLink = $name;
			$show_link = $comment['show_link']; //what to do with this? Dont know yet.
			if ($comment['website'] != '') {$nameLink = '<a href="'.$comment['website'].'" target="_blank">'.$name.'</a>';}
			
			
			// Display Comments without slashes, but with new-line characters
			$output = '<h4>'.$MOD_TOPICS['COMMENT_SAVED'].'</h4><hr/>';
			
			
			$vars = array('[NAME]','[EMAIL]','[WEBSITE]','[COMMENT]','[DATE]','[TIME]','[USER_ID]','[USERNAME]','[DISPLAY_NAME]', '{NAME}');
			$values = array($name, $comment['email'], $comment['website'], $thecomment, '', '', '0', '', '',  $nameLink);
			
			$outputshow = str_replace($vars, $values, $setting_comments_loop);
			
			echo $outputshow;
			echo $output;
			
		}
	} else { 
		echo "Error ".$query_comments->numRows(); 
	}
}


?>