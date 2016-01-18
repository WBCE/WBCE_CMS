<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$id = $_GET['topic_id'];
	$topic_id = $id;
}

require('permissioncheck.php');


//require_once(WB_PATH."/include/jscalendar/jscalendar-functions.php");


// Include WB functions file
//require(WB_PATH.'/framework/functions.php');


//get This Topic Params
$query_topics = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE topic_id = '$topic_id'");
if (($query_topics->numRows()) != 1) {
	exit("So what happend with the topic?"); 
}
$thistopic = $query_topics->fetchRow();
//ueberpruefung:
if ($authoronly) {
	$authors = $thistopic['authors'];
	$pos = strpos ($authors,','.$user_id.',');
	if ($pos === false){die("Nix da");}
}

// Get settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() != 1) {
	exit("So what happend with the settings?"); 
}
$settings_fetch = $query_settings->fetchRow();	
$sort_topics = $settings_fetch['sort_topics'];
// Different sorting?
if(isset($_GET['sort']) AND is_numeric($_GET['sort'])) {
	$sort_topics = $_GET['sort'];	
}

//$sort_topics_by = ' position DESC';
/*if ($sort_topics == 1) {$sort_topics_by =  ' published_when DESC, posted_first DESC';}
if ($sort_topics == 2) {$sort_topics_by =   ' topic_score DESC';}
if ($sort_topics == 3) {$sort_topics_by =   ' published_when DESC';}
//Reversed?
if ($sort_topics < 0) {
	$sort_topics_by = ' position ASC';
	if ($sort_topics == -2) {$sort_topics_by =  ' published_when ASC, posted_first ASC';}
	if ($sort_topics == -3) {$sort_topics_by =   ' topic_score ASC';}
	if ($sort_topics == -4) {$sort_topics_by =   ' published_when DESC';}
}
*/
$sort_topics_by = get_sort_topics_by($sort_topics);
echo $sort_topics_by;
//Find Links IN
$topic_idstr = '%,'.$topic_id.',%';
$tquery = "SELECT topic_id, see_also FROM `".TABLE_PREFIX."mod_".$tablename."` WHERE see_also LIKE '".$topic_idstr."'";
//echo $tquery;
$query_topics = $database->query($tquery);
$linkin_array = array();
if(($query_topics->numRows()) > 0) {
	while($topic = $query_topics->fetchRow()) { 
		$linkin_array[] = $topic['topic_id']; 
	}	
}



$see_also_text = substr($thistopic['see_also'], 1, -1); 
if ($see_also_text == '') {$see_also_arr = array();} else $see_also_arr = explode(',',$see_also_text);
echo '<table class="topiclist-toptable"><tr><td><h3>'. $thistopic['title'].'</h3>';
if ($thistopic['short_description'] != '') {echo '<div class="short_description">'. $thistopic['short_description'].'</div>';}	
echo '<div class="short_content">'. $thistopic['content_short'].'</div>';
echo '</td><td class="parambox">';	
echo 'Links out: ' . count($see_also_arr) . '<br/>Links in: ' . count($linkin_array);
echo '</td></tr></table><hr/>';


$t = time();
$t2 = $t - (60 * 60 * 24 * 30); //1 Monat zurueck
$query_extra = '';
if ($sort_topics == 3) {$query_extra = ' AND published_when >= '.$t2.' ';}


if ($restrict2picdir > 1) {
	$picture_dir = $settings_fetch['picture_dir'];	
	$theq = "SELECT section_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id > '0' AND picture_dir = '".$picture_dir."'";	
	$query = $database->query($theq);				
	if($query->numRows() > 0) {		
		$restricttosections = array();
		while($thesection = $query->fetchRow()) {
			$restricttosections[] = $thesection['section_id'];
		}
		$restricttosectionsstring = implode(',',$restricttosections);
		$query_extra .= "AND section_id IN (".$restricttosectionsstring.") ";		
	}
	
}


$limit_sql = ' LIMIT 100';
$theq = "SELECT published_when, section_id, page_id, topic_id, link, title, short_description, hascontent, active, authors FROM ".TABLE_PREFIX."mod_".$tablename." where hascontent > '0' AND section_id > '0' ".$query_extra." ORDER BY ".$sort_topics_by.$limit_sql;
	$query_topics = $database->query($theq);

// Loop through existing topics
//$query_topics = $database->query("SELECT published_when, section_id, page_id, topic_id, link, title, short_description, hascontent, active FROM `".TABLE_PREFIX."mod_topics` WHERE title <> '' ORDER BY published_when DESC");
if($query_topics->numRows() > 0) {
	$num_topics = $query_topics->numRows();
	$row = 'a';
	
	?>
	<form name="seealsolinks" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_seealso.php" method="post" style="margin: 0;">
     <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
	<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>" />
	<input type="hidden" name="fredit" value="<?php echo $fredit; ?>" />
   
	<table class="topiclist-maintable">	
	<tr><td class="linkin">&nbsp;</td><td class="see_also"><img src="img/link_to.gif" class="viewbutton" alt="" title="link TO" /></td><td class="anleitung"><?php echo $MOD_TOPICS['LINK_TO']; ?></td><td>&nbsp;</td></tr>
	<?php
	$counter=0;
	while($topic = $query_topics->fetchRow()) {
		$counter++;
		$t_id = $topic['topic_id'];
		if ($t_id == $topic_id) {continue;}
		$params = 'page_id='.$topic['page_id'].$paramdelimiter.'section_id='.$topic['section_id'].$paramdelimiter.'topic_id='.$t_id; 
		$active = $topic['active'];
		
		$modifylink = '<a title="'.$TEXT['MODIFY'].'" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?'.$params.'" >';
		$modifylinkto = '<a href="'.WB_URL.'/modules/'.$mod_dir.'/topicslist.php?' . $params.'" title="'.$MOD_TOPICS['SWITCHTO'].'"><img src="img/totopiclist.gif" alt="" title="Switchto - " /></a>';
		if ($authoronly) {
			$authors = $topic['authors'];
			$pos = strpos ($authors,','.$user_id.',');
			if ($pos === false){$modifylink = ''; $modifylinkto = '';}
		}
		
	
		echo '<tr class="row_'.$row.' mtr'.$active.'">';	
		?>		
		<td class="linkin"><?php if(in_array($t_id,$linkin_array))  {echo '<img src="img/linking_in.gif" class="viewbutton" alt="" title="Linking IN" />';} else {echo '&nbsp;';} ?></td>			
		<td width="30" class="see_also">&nbsp;<?php if ($topic['hascontent']  > 0) {
			echo '<input type="checkbox" name="topiclinks[]" value="'.$t_id.'"'; if(in_array($t_id,$see_also_arr))  {echo ' checked="checked"';} echo '/>';
		}?></td>
		<td class="topictd">
		
		
		
		<?php $topic_link = WB_URL.$topics_directory.$topic['link'].PAGE_EXTENSION;
		
		if ($topic['hascontent'] > 0) { 
			if ($modifylinkto != '') {echo $modifylinkto;} else {echo '<img src="img/none.gif" alt="" title="" />';}
		 	echo '<strong>'.stripslashes($topic['title']).'</strong>'; if ($counter < 20 AND $topic['short_description'] !='') {echo '<div class="shortdesc">'.$topic['short_description'].'</div>';}
		} else {
			echo '<img src="img/none.gif" alt="Switchto - " />'.stripslashes($topic['title']);
		}
		
		?></td>
		<td class="topicprops">
		<?php // Get number of comments
				$query_comments = $database->query("SELECT name FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id = '".$topic['topic_id']."'");
				$cc = $query_comments->numRows();
				$cp = 0; if ($cc > 0) {$cp = 1;} if ($cc > 3) {$cp = 2;} if ($cc > 7) {$cp = 3;}  if ($cc > 20) {$cp = 4;} echo '<img src="img/comments'.$cp.'.gif" alt="" title="'.$cc.' comments" />';
				?>
			<img src="img/active<?php $alt='ACTIVE_'.$active; echo $active.'.gif" alt="'.$MOD_TOPICS[$alt]; ?>" />
		<?php if ($modifylink != '')  {echo $modifylink. '<img src="img/modifytopic.gif" alt="" title="Modify - " /></a>';} else {echo '<img src="img/none.gif" alt="" title="" />';} ?>
		<?php if ($topic['hascontent'] > 0 AND $active > 0) { 
		 	echo '<a href="'.$topic_link.'" target="_blank" ><img src="'.THEME_URL.'/images/view_16.png" class="viewbutton" alt="" title="View" /></a>';
		} ?>
		
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
				<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
			</td>
			<td align="right">
				<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fredit='.$fredit; ?>';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
	 </form>
	<?php
} else {
	echo $TEXT['NONE_FOUND'];
}

if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}
?>