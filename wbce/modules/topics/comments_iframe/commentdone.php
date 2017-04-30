<?php

// Include config file
require('../../../config.php');
// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

require('../info.php');
$mod_dir = $module_directory;
$tablename = $mod_dir;

require_once(WB_PATH.'/framework/class.wb.php');
$wb = new wb;


// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Danke</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET :'utf-8';?>" />
<link href="comment_frame.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript" type="text/JavaScript">
<!--
function doresize() {
	h = document.getElementById('wraptable').offsetHeight;
	
	if (h > 0 && h < 800) {parent.resizeframe(h); }
}
//-->
</script>
</head><body> <table id="wraptable"><tr><td>
<?php
// Get ids
if(!isset($_GET['cid']) OR !is_numeric($_GET['cid'])) { $cid = 0; } else {$cid = $_GET['cid'];}
if(!isset($_GET['tid']) OR !is_numeric($_GET['tid'])) { $topic_id = 0;} else { $topic_id = $_GET['tid']; }

if (($topic_id * $cid) == 0)  {exit ('&nbsp;</td></tr></table><script language="JavaScript" type="text/JavaScript">
<!--
doresize();	
//-->
</script></body></html>');} //no topic_id, no comment_id;
//echo "hier";

$query_topic = $database->query("SELECT section_id,commenting FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '".$topic_id."'");
$commenting = 0;
if($query_topic->numRows() > 0) { 
	$fetch_topic = $query_topic->fetchRow();
	$section_id  = $fetch_topic['section_id'];
	$commenting = $fetch_topic['commenting'];	
}


// Get settings

$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '".$section_id."'");
if($query_settings->numRows() > 0) { 
	$settings_fetch = $query_settings->fetchRow();
	$setting_comments_loop = $settings_fetch['comments_loop']; //} else {$section_id = 0;}
	
	//various values
		$use_commenting_settings = 0;
		if ($settings_fetch['various_values'] != '') {
			$vv = explode(',',$settings_fetch['various_values']);		
			$use_commenting_settings = (int) $vv[3];
			$emailsettings = (int) $vv[4]; if ($emailsettings < 0) {$emailsettings = 2;} //Wie bisher: Pflichtfeld
		}
		
					

		$query_topic = $database->query("SELECT link, commenting, posted_by,title  FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$topic_id'");
		if($query_topic->numRows() != 1) { die('no topic!'); }
		$topicfetch = $query_topic->fetchRow();		
		$link = $topicfetch['link'];		
		$commenting = (int) $topicfetch['commenting'];
		$topicauthornr = $topicfetch['posted_by'];
		
	
		
		if( $commenting < -1) {$use_commenting_settings = 1;} //Defaultwert verenden
		//Wenn: angekreuzt: Individielle EInstellungen ignorieren, dann die Settings-Einstellungen verwenden.
		if ($use_commenting_settings == 1) { $commenting = (int) $settings_fetch['commenting'];}

}


if ($commenting < 1 ) {exit ('</body></html>');}

if (($section_id * $cid) > 0) {


// Query for comments
		$query_comments = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE comment_id = '".$cid."'");
		if($query_comments->numRows() == 1) {
		
		if ($commenting < 2 ) 
			{echo '<h4>'.$MOD_TOPICS['COMMENT_MODERATE'].'</h4>';
			}
		else {
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
			
			$outputjs = str_replace($vars, $values, $setting_comments_loop);
			$outputjs = preg_replace('/\s+/', ' ', $outputjs); //einzeilig für Javascript	
			$outputjs = addslashes($outputjs);
			
			echo '<script type="text/javascript">
			<!--
			parent.addcomment(\''.$cid.'\',\''.$outputjs.'\'); 
			// -->
			</script>
			';
		
			echo $output;
			}
	} else { 
		echo "Error ".$query_comments->numRows(); 
	}
}


?>
</td></tr></table>
<script language="JavaScript" type="text/JavaScript">
<!--
doresize();
	//h = document.getElementById('wraptable').offsetHeight;
	//alert(h);
	//if (h > 30 && h < 800) {parent.resizeframe(h); }
//-->
</script>
</body></html>