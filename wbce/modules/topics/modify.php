<?php

// Must include code to stop this file being access directly
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }
require('permissioncheck.php');

if (isset($_GET['hl'])) {$hltopic = 0 + (int)$_GET['hl'];} else {$hltopic = 0;}

// Get settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() != 1) {
	exit("So what happend with the settings?");
}
$settings_fetch = $query_settings->fetchRow();
$use_timebased_publishing = $settings_fetch['use_timebased_publishing'];
$sort_topics = $settings_fetch['sort_topics'];
$sort_topics_default = $sort_topics;

if(!isset($settings_fetch['picture_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `picture_values` VARCHAR(255) NOT NULL DEFAULT ''");
	echo '<h2>Database Field "picture_values" added</h2>';
}

$is_master = '';
if(!isset($settings_fetch['is_master_for'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `is_master_for` VARCHAR(255) NOT NULL DEFAULT ''");
	echo '<h2>Database Field "is_master_for" added</h2>';
} else {
	if ($settings_fetch['is_master_for'] != '') {$is_master = $settings_fetch['is_master_for'];}

}

echo '<div class="topic-modify">';
if ($is_master == '') {
	echo '<a class="topic-modify-add" href="'.WB_URL.'/modules/'.$mod_dir.'/add_topic.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'fredit='.$fredit.'">'.$TEXT['ADD'].'</a>'."\n";
}
if ($showoptions) {
	echo '<a class="topic-modify-settings" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_settings.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'">'.$TEXT['SETTINGS']."</a>\n";
	echo '<a class="topic-modify-help" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'">'.$MENU['HELP']."</a>\n";
}
echo "\n</div>\n";
?>

<hr style="clear:left;" />

<!--h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$MOD_TOPICS['TOPIC']; ?></h2-->

<?php



/*$sort_topics_by = ' position DESC';
if ($sort_topics == 1) {$sort_topics_by =  ' published_when DESC, posted_first DESC';}
if ($sort_topics == 2) {$sort_topics_by =   ' topic_score DESC';}*/

// Different sorting?
if(isset($_GET['sort']) AND is_numeric($_GET['sort'])) {
	$sort_topics = $_GET['sort'];
}


$sort_topics_by = ' position DESC';
$sort_topics_by = get_sort_topics_by($sort_topics);

/*if ($sort_topics == 1) {$sort_topics_by = ' published_when DESC, posted_first DESC';}
if ($sort_topics == 2) {$sort_topics_by = ' topic_score DESC';}
if ($sort_topics == 3) {$sort_topics_by = ' published_when ASC';}
if ($sort_topics == 4) {$sort_topics_by = ' title ASC';}

//Reversed?
if ($sort_topics < 0) {
	$sort_topics_by = ' position ASC';
	if ($sort_topics == -2) {$sort_topics_by = ' published_when ASC, posted_first ASC';}
	if ($sort_topics == -3) {$sort_topics_by = ' topic_score ASC';}
	if ($sort_topics == -4) {$sort_topics_by = ' published_when DESC';}
	if ($sort_topics == -5) {$sort_topics_by = ' title DESC';}
}
*/

$picsurl = '../../modules/'.$mod_dir.'/img/';
$params = 'page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'fredit='.$fredit.$paramdelimiter.'sort=';
//echo '<a href="?'.$params. (-1 - $sort_topics).'"><img src="'.$picsurl.'reverse.gif" alt="reverse" title="reverse" /></a>';

$t = time();
$t2 = $t - (60 * 60 * 24 * 30); //1 Monat zurück
// Loop through existing topics
$query_topics = "SELECT * FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE section_id = '$section_id' ORDER BY $sort_topics_by";

$query_extra = '';
//Usage as event calendar:
//if ($sort_topics == 3) {$query_extra = ' AND published_when >= '.$t2.' ';}


$showsortarrows = false;
if ($sort_topics == 0 OR $sort_topics == -1) { $showsortarrows = true;}
if ($authoronly == true AND $author_can_change_position == false) { $showsortarrows = false;}

$query_topics = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE section_id = '$section_id' ".$query_extra." ORDER BY $sort_topics_by");

if($query_topics->numRows() > 0) {
	$num_topics = $query_topics->numRows();
	$row = 'a';
	$counter = 0;
	$public = 0;
	$allcommentscount = 0;
	
	$activerowcontent = '';
	$passedrowcontent = '';
	
	while($topic = $query_topics->fetchRow()) {
		$counter++;
		$rowcontent = '';

		$t_id = $topic['topic_id'];
		$active = $topic['active']; if ($active > 2) $public += 1;
		$params = 'page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'fredit='.$fredit.$paramdelimiter.'topic_id='.$t_id;
		$trclass = $row;
		if ($t_id == $hltopic) {$trclass .= ' hilite';}

		$modifylink = '<a title="'.$TEXT['MODIFY'].'" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?'.$params.'" >';
		if ($authoronly) {
			$authors = $topic['authors'];
			$pos = strpos ($authors,','.$user_id.',');
			if ($pos === false){$modifylink = ''; $trclass .= ' noedit';}
		}

		$rowcontent .= '<tr class="row_'.$trclass.'"><td width="40" align="right">';
		$rowcontent .= '<a name="tpid'.$t_id.'" id="tpid'.$t_id.'">';

		// Get number of comments
		$comments_count = 0;
		$commentsclass = 0;
		$dorefresch = 1;
		if(isset($topic['comments_count'])) {
			$comments_count = $topic['comments_count'];
			if ($comments_count < 0) { $dorefresch = 2;} else { $dorefresch = 0;}
		} else {
			require_once(WB_PATH.'/modules/'.$mod_dir.'/inc/upgrade.inc.php');
			exit('<h2>Please refresh the page now</h2>');
		}
		if ($dorefresch > 0) {
			$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id = '".$t_id."' AND active>'0'";
			$query_comments = $database->query($theq);
			$comments_count = $query_comments->numRows();
		}
		if ($dorefresch > 1) {
			$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename." SET comments_count = '".$comments_count."' WHERE topic_id = '".$t_id."'";
			$database->query($theq);
		}

		$allcommentscount += $comments_count;
		$commentsclass = topics_commentsclass ($comments_count);
		if ($comments_count > 0) {
			$modifycommentslink = '<a title="'.$TEXT['MODIFY'].'" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?'.$params.'#comments" ><img src="'.$picsurl.'comments'.$commentsclass.'.gif" alt="'.$comments_count.'" title="'.$comments_count.' comments" /></a>';
		} else {
			$modifycommentslink = '<img src="'.$picsurl.'comments0.gif" alt="0" title="no comments" />';
		}
		//$query_comments = $database->query("SELECT name FROM ".TABLE_PREFIX."mod_topics_comments WHERE topic_id = '".$topic['topic_id']."'");
		//$cc = $query_comments->numRows();
		//$commentscount += $cc;
		//$cp = 0; if ($cc > 0) {$cp = 1;} if ($cc > 2) {$cp = 2;} if ($cc > 5) {$cp = 3;}  if ($cc > 8) {$cp = 4;}
		$rowcontent .= $modifycommentslink;
		$rowcontent .= $modifylink;
		$alt='ACTIVE_'.$active; 
		$rowcontent .= '<img src="'.$picsurl.'active'.$active.'.gif" alt="'.$MOD_TOPICS[$alt].'" /></a>';
		
		$rowcontent .= '</td><td>';
		
		$title = stripslashes($topic['title']);
		if ($title == '') {$title = 'Untitled';}
		$rowcontent .= '<strong>'.$modifylink.$title.'</a></strong>'; if ($topic['short_description'] !='') {$rowcontent .= '<div class="shortdesc">'.$topic['short_description'].'</div>';}
		$rowcontent .= '</td><td class="topicprops"></td>';
	

		
		//if ($use_timebased_publishing > 1) {
			$start = $topic['published_when'];
			$end = $topic['published_until'];
			$t = time();
			$icon = '';
			if($start<=$t && $end==0)
				{ $icon=THEME_URL.'/images/noclock_16.png';  }
			elseif(($start<=$t || $start==0) && $end>=$t)
				$icon=THEME_URL.'/images/clock_16.png';
			else
				{$icon=THEME_URL.'/images/clock_red_16.png'; if ($active > 2) $public -= 1;}
			$rowcontent .= '<td width="20">'.$modifylink.'<img src="'.$icon.'" border="0" alt="" /></a></td>';
		//} 
		
		if ($showsortarrows == true) {
			$rowcontent .= '<td width="20">'; if($counter > 1) { $rowcontent .= '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move.php?'.$params.$paramdelimiter.'move=up" title="'.$TEXT['MOVE_UP'].'"><img src="'.THEME_URL.'/images/up_16.png" border="0" alt="^" /></a>';} $rowcontent .= '</td>';
			$rowcontent .= '<td width="20">'; if($counter < $num_topics) { $rowcontent .= '<a href="'.WB_URL.'/modules/'.$mod_dir.'/move.php?'.$params.$paramdelimiter.'move=down" title="'.$TEXT['MOVE_DOWN'].'"><img src="'.THEME_URL.'/images/down_16.png" border="0" alt="v" /></a>';} $rowcontent .= '</td>';

		} else { //End Position Arrows
			$thet =  $topic['published_when']; //+TIMEZONE;
			if ($thet == 0) {$thet =  $topic['posted_first']; } //+TIMEZONE;}
			$posted_publ_date = gmdate(DATE_FORMAT, $thet);
			$posted_publ_time = gmdate(TIME_FORMAT, $thet);
			$rowcontent .= '<td width="180">'. $posted_publ_date .' '.$posted_publ_time.'</td>';

		}  
		$rowcontent .= '<td width="40">';
		
		
		if ($topic['hascontent'] > 0 AND $active > 0) {
			$topic_link = WB_URL.$topics_directory.$topic['link'].PAGE_EXTENSION;
		 	$rowcontent .= '<a href="'.$topic_link.'" target="_blank" ><img src="'.THEME_URL.'/images/view_16.png" class="viewbutton" alt="View" /></a>';
		} 
		
		$rowcontent .= '</td></tr>';
		
		//Eventkalender:
		if ($sort_topics == 3 AND $end < $t) {
			$passedrowcontent = $rowcontent.$passedrowcontent;
		} else {
			$activerowcontent .= $rowcontent;
		}
		
		
		
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	
	echo '<table class="topiclist-maintable">'.$activerowcontent.'</table>';
	if ($passedrowcontent != '') {
		echo '<h3>Past:</h3><table class="topiclist-maintable eventspassed">'.$passedrowcontent.'</table>';
	
	}
	
	
	?>
	
	<p style="font-size:10px;"><b>Infos:</b><br/>
Count: <?php echo $counter; ?><br/>
Public: <?php echo $public; ?><br/>
Comments: <?php echo $allcommentscount; ?><br/>
Sorted by: <?php echo $sort_topics_by; ?>
</p><hr />
	<?php
} else {

	if ($is_master == '') {
		echo $TEXT['NONE_FOUND'].'<hr/>';

		if ($showoptions) {
			echo '<a class="topic-modify-settings" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_settings.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'do=setmaster">Set as TopicsMaster</a>';
		}
	} else {
		echo '<p>This section is a master-section for: '.$is_master.'</p>';
	}


	/*

	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/starthelp'.LANGUAGE.'.php')) {
		include(WB_PATH.'/modules/'.$mod_dir.'/languages/starthelpEN.php');
	} else {
		include(WB_PATH.'/modules/'.$mod_dir.'/languages/starthelp'.LANGUAGE.'.php');
	}

	*/
}


if ($fredit == 1) {
	topics_frontendfooter();
}


?>

