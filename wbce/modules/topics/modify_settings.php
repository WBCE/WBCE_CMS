<?php

require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }
require('permissioncheck.php');

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

$user_id = $admin->get_user_id();
$user_in_groups = $admin->get_groups_id();

$showoptions = true;
if (!in_array(1, $user_in_groups)) {	
	if ($noadmin_nooptions > 0) { $showoptions = false; }
} 
if ($showoptions != true) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit( 0 );
}

if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/info-'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/info-EN.php');
} else {
	require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/info-'.LANGUAGE.'.php');
}
	


$use_getfrom = true; $use_presets = true; $use_pictures = 1; $allow_global_settings_change = 1; //outdated options

if ($use_getfrom) { echo '<a href="#" onclick="makevisible(\'getfromtable\');" >Get from</a>'; }
if ($use_getfrom && $use_presets) echo " | ";
if ($use_presets) { echo '<a href="#" onclick="makevisible(\'presetstable\');" >Presets</a>'; }


echo ' | <a href="#" onclick="makevisible(\'assistant\');" >New: Assistant</a> (partial presets)';

$query_others = $database->query("SELECT page_id, section_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id <> '$section_id' AND  section_id > '0' ORDER BY page_id ASC");
$othersectionspresent = $query_others->numRows();
if ($use_getfrom) { 
	echo '<script type="text/javascript"> var theurl = "' .WB_URL.'/modules/'.$mod_dir.'/getsettings.php?"; </script>';
	echo '<table cellpadding="2" cellspacing="0" border="0" width="100%" id="getfromtable" style="display:none;">
	<tr><td width="30%" valign="top">Get from:<br/>
	<form name="getsettings" action="#" method="get" style="margin: 0;">
	<select name="choosesettings" id="choosesettings" onchange="changesettings(this.options[this.selectedIndex].value);">'; 

	echo '<option value="page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'">This one (reload)</option>';
			
	//Get other settings:
	
	if($othersectionspresent > 0) { 	
		while($others = $query_others->fetchRow()) {			
			$p_id = (int)$others['page_id'];
			$s_id = (int)$others['section_id'];
			$query_page = $database->query("SELECT menu_title, link FROM ".TABLE_PREFIX."pages WHERE page_id = '$p_id'");
			$fetch_menu = $query_page->fetchRow();
			$menutitle = $fetch_menu['menu_title'];
			$the_link = $fetch_menu['link'];
			echo '<option value="page_id='.$p_id.$paramdelimiter.'section_id='.$s_id.'">'.$menutitle .' (sid'.$s_id.')</option>';		
		}
	}
	
	echo '</select></form></td><td><div id="getfromdescription">NOTE: the get-from option will change the field contents. If you dont want to keep the changes, do NOT save!</div></td></tr></table>';
	
}



if ($use_presets) { 
	//get presets	
	$thelanguage = strtolower(LANGUAGE);	
	$presets_files = WB_PATH.'/modules/'.$mod_dir.'/presets-en';
	echo '<script type="text/javascript"> var thelanguage = "' .$thelanguage. '"; </script>';

	echo '<table cellpadding="2" cellspacing="0" border="0" width="100%" id="presetstable" style="display:none;">
<tr><td width="30%" valign="top">Presets:<br/>

<form name="presets" action="#" method="get" style="margin: 0;">
<select name="getpresets" id="getpresets" onchange="changepresets(this.options[this.selectedIndex].value, \'\');">  
     <option disabled="disabled" value="--">------------------------------</option> <option value="--">none</option>';
	 
	//check if theres a default file in the template folder:
	$theq = "SELECT template FROM ".TABLE_PREFIX."pages WHERE page_id = ".$page_id;
	$result = $database->query($theq);
	$result_fetch = $result->fetchRow();
	$thistemplate = $result_fetch['template'];
	if ($thistemplate == '') {
		$theq = "SELECT value FROM ".TABLE_PREFIX."settings WHERE name = 'default_template'";
		$result = $database->query($theq);
		$result_fetch = $result->fetchRow();
		$thistemplate = $result_fetch['value'];	
	}
	
	if (file_exists(WB_PATH.'/templates/'.$thistemplate.'/topics-preset.js')) {
		echo '<option value="../../templates/'.$thistemplate.'/topics-preset.js">Template default</option><option disabled="disabled" value="--">------------------------------</option>'; 	
	}
	
	 
	$presets_dir = opendir($presets_files);
	$fileArr = array();	
	while ($file=readdir($presets_dir)) {
		if ($file != "." && $file != "..") {									
			$filename = substr($file, 0, -3);
			$fileArr[] = $filename;
		}
	}
	sort($fileArr);
	foreach ($fileArr as $filename) {
		echo '<option value="'.$filename.'">'.str_replace('_', ' ',$filename).'</option>'; 
    }
	echo '</select></form>
	</td><td><div id="presetsdescription">NOTE: the presets-option will change the field contents. If you dont want to keep the changes, do NOT save!</div></td></tr></table>';


 } 
echo '<div id="assistant" style="display:none">';
include(WB_PATH.'/modules/'.$mod_dir.'/assistant/assistant.inc.php');
echo '</div>';
echo '<hr/>';



// Get header and footer
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id' AND page_id = '$page_id'");
if($query_settings->numRows() != 1) { die('Wahh!'); }
$settings_fetch = $query_settings->fetchRow();


$is_master = '';
if ($settings_fetch['is_master_for'] != '') {
	$is_master = $settings_fetch['is_master_for'];
	$allow_global_settings_change = 0;
} else {
	if (isset($_GET['do']) AND $_GET['do'] == 'setmaster' ) { $is_master = 'same picture dir'; $allow_global_settings_change =0;}
}

//various values

if(!isset($settings_fetch['various_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `various_values` VARCHAR(255) NOT NULL DEFAULT '150,450,0,0'");
	echo '<h2>Database Field "various_values" added</h2>';
	$vv = explode(',','-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
} else {
	$vv = explode(',',$settings_fetch['various_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
} 


$use_commenting_settings = (int) $vv[3];
if ($use_commenting_settings < 0) {$use_commenting_settings = 0;}

$short_textareaheight = (int) $vv[0]; if ($short_textareaheight < 0 AND $short_textareaheight > -10) {$short_textareaheight = 0;}
$long_textareaheight = (int) $vv[1]; if ($long_textareaheight < 0 AND $long_textareaheight > -10) {$long_textareaheight = 400;}
$extra_textareaheight = (int) $vv[2]; if ($extra_textareaheight < 0 AND $extra_textareaheight > -10) {$extra_textareaheight = 300;}
$use_commenting_settings = (int) $vv[3]; if ($use_commenting_settings < 0) {$use_commenting_settings = 0;}

$emailsettings = (int) $vv[4]; if ($emailsettings < 0) {$emailsettings = 2;} //Wie bisher: Pflichtfeld
$maxcommentsperpage = (int) $vv[5];
$commentstyle = (int) $vv[6];


//Picture values
if(!isset($settings_fetch['picture_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `picture_values` VARCHAR(255) NOT NULL DEFAULT ''");
	echo '<h2>Database Field "picture_values" added</h2>';
	$pv = explode(',','-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
} else {
	$pv = explode(',',$settings_fetch['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
} 

//echo $settings_fetch['picture_values'];

$w_zoom = (int) $pv[0]; if ($w_zoom == -2) {$w_zoom = 0;}
$h_zoom = (int) $pv[1]; if ($h_zoom == -2) {$h_zoom = 1000;}
$w_view = (int) $pv[2]; if ($w_view == -2) {$w_view = 380;}
$h_view = (int) $pv[3]; if ($h_view == -2) {$h_view = 0;}
$w_thumb = (int) $pv[4]; if ($w_thumb == -2) {$w_thumb = 100;}
$h_thumb = (int) $pv[5]; if ($h_thumb == -2) {$h_thumb = 100;}
$zoomclass = $pv[6]; if ($zoomclass == "-2") {$zoomclass = "fbx";}
$zoomrel= $pv[7]; if ($zoomrel == "-2") {$zoomrel = "fbx";}

$w_zoom2 = (int) $pv[8]; if ($w_zoom2 == -2) {$w_zoom2 = 0;}
$h_zoom2 = (int) $pv[9]; if ($h_zoom2 == -2) {$h_zoom2 = 1000;}
$w_view2 = (int) $pv[10]; if ($w_view2 == -2) {$w_view2 = 380;}
$h_view2 = (int) $pv[11]; if ($h_view2 == -2) {$h_view2 = 0;}
$w_thumb2 = (int) $pv[12]; if ($w_thumb2 == -2) {$w_thumb2 = 100;}
$h_thumb2 = (int) $pv[13]; if ($h_thumb2 == -2) {$h_thumb2 = 100;}
$zoomclass2 = $pv[14]; if ($zoomclass2 == "-2") {$zoomclass2 = "fbx";}
$zoomrel2= $pv[15]; if ($zoomrel2 == "-2") {$zoomrel2 = "fbx";}

$nameArr = array('w_zoom', 'w_view', 'w_thumb', 'h_zoom', 'h_view', 'h_thumb');
foreach ($nameArr as $n) {
	if ($$n == 0) {$$n = 'prop.';}
	$n.='2';
	if ($$n == 0) {$$n = 'prop.';}
}



//Parse one string into 5 fields:
$setting_pnsa_string = stripslashes($settings_fetch['pnsa_string']);
//$test = unserialize ($setting_pnsa_string);
//var_dump($test);

/*
//if (isset($settings_fetch['pnsa_array']) AND unserialize($settings_fetch['pnsa_array']) == true) {
if ( isset($settings_fetch['pnsa_array']) ) {
	//neue Methode:
	$setting_pnsa_array = unserialize(base64_decode($settings_fetch['pnsa_array']));
} else {	
	//Alte Methode:	
		
}
*/
$setting_pnsa_array = explode($serializedelimiter,$setting_pnsa_string);
if (is_array($setting_pnsa_array) AND count($setting_pnsa_array) > 4 ) {
	$see_also_link_title = $setting_pnsa_array[0];
	$next_link_title = $setting_pnsa_array[1];
	$previous_link_title = $setting_pnsa_array[2];		
	$setting_pnsa_string = $setting_pnsa_array[3];
	$setting_sa_string = $setting_pnsa_array[4];				
} else {
/*
	//OLD Fallback
	include(WB_PATH.'/modules/'.$mod_dir.'/defaults/add_settings.default.php');
	$setting_pnsa_string = stripslashes($pnsa_string_raw);
	$setting_sa_string = $setting_pnsa_string;
	$see_also_link_title = '<h4>'.$MOD_TOPICS['SEE_ALSO_FRONTEND'].'</h4>';
	$next_link_title = '<h4>'.$MOD_TOPICS['SEE_NEXT_POST'].'</h4>';
	$previous_link_title = '<h4>'.$MOD_TOPICS['SEE_PREV_POST'].'</h4>';
	*/
	
}	

//OLD Fallback
//Enthaelt auch die Daten zu den additional pictures
$setting_additionalpics_string = '{THUMB}';
if (is_array($setting_pnsa_array) AND count($setting_pnsa_array) > 5 ) {
	$setting_additionalpics_string = $setting_pnsa_array[5];
} 


// Set raw html <'s and >'s to be replace by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');

// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/form/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/form/backend.css');
	echo "\n</style>\n";
}

?>
<h2><?php echo $MOD_TOPICS['SETTINGS']; ?></h2>


<form class="settingsform" name="modify" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_settings.php" method="post" style="margin: 0;">

	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />


<div class="tablinks">	
<a id="linktabarea1" href="javascript:showtabarea(1);"><?php echo $MOD_TOPICS['SECTIONSETTINGS']; ?></a>
<a id="linktabarea2" href="javascript:showtabarea(2);"><?php echo $MOD_TOPICS['PICTURES']; ?></a>
<a id="linktabarea3" href="javascript:showtabarea(3);"><?php echo $MOD_TOPICS['TOPICSPAGE']; ?></a>
<?php if ($is_master == '') { ?>
<a id="linktabarea4" href="javascript:showtabarea(4);"><?php echo $MOD_TOPICS['TOPIC']; ?></a>
<a id="linktabarea5" href="javascript:showtabarea(5);"><?php echo $MOD_TOPICS['PNSA_STRING']; ?></a>
<?php if ($use_commenting > -1) {echo '<a id="linktabarea6" href="javascript:showtabarea(6);">'.$TEXT['COMMENTS'].'</a>';} ?>
<a id="linktabarea7" href="javascript:showtabarea(7);"><?php echo $MOD_TOPICS['VARIOUS']; ?></a>
<?php } ?>

</div>
<br style="clear:both;" />	
	
<div class="tabarea" id="tabarea1">
	<h3><?php echo $MOD_TOPICS['SECTIONSETTINGS']; ?></h3>
	<table class="settingsleft" cellpadding="2" cellspacing="0">		
		<tr>
			<td class="setting_name" width="20%"><?php echo $TEXT['TITLE']; ?>:</td>
			<td class="setting_fields"><input class="tpfw98" type="text" name="section_title" value="<?php echo htmlspecialchars($settings_fetch['section_title']); ?>"  maxlength="255" /></td>
		</tr>
		<tr>
			<td class="setting_name" width="20%"><?php echo $TEXT['DESCRIPTION']; ?>:</td>
			<td class="setting_fields"><textarea name="section_description" rows="10" cols="1" class="tpfw98" style="height: 50px;"><?php echo htmlspecialchars($settings_fetch['section_description']); ?></textarea></td>
		</tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
		<?php 		
		$use_timebased_publishing = (int) $settings_fetch['use_timebased_publishing'];
		if ($use_timebased_publishing == 3 AND $othersectionspresent < 1) { $use_timebased_publishing = 0; }
		
		
		
		
		if ($is_master != '') { ?>
			<tr><td class="setting_name"><?php echo $MOD_TOPICS['TOPICSMASTER_SELECT']; ?>:</td>
			<td class="setting_fields">
				<input class="inputf tpfw98" type="text" name="is_master_for" value="<?php echo $is_master; ?>"  maxlength="255" />
				<input type="hidden" name="use_timebased_publishing" value="0" />
			</td>
		<?php } else { ?>
		
			<td class="setting_name"><?php echo $MOD_TOPICS['TIMEBASED_PUBL']; ?>:</td>
			<td class="setting_fields">
				<select name="use_timebased_publishing" class="tpfw98"  id="use_timebased_publishing" onchange="topictimefieldchanged(1,this.options[this.selectedIndex].value);">					
					<option value="0" <?php if($use_timebased_publishing == 0) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['DATE_USAGE_0']; ?></option>
					<option value="1" <?php if($use_timebased_publishing == 1) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['DATE_USAGE_1']; ?></option>
					<option value="2" <?php if($use_timebased_publishing == 2) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['DATE_USAGE_2']; ?></option>
					<?php if ($othersectionspresent > 0) {
						echo '<option value="3" '; 
						if($use_timebased_publishing == 3) { echo 'selected="selected"'; }
						echo '>'.$MOD_TOPICS['DATE_USAGE_3'].'</option>';
					}  ?>					
				</select>
				<input type="hidden" name="is_master_for" value="" />
				</td>
		<?php }  ?>
		</tr>
		
		<?php 
		//Auto-Archive Options
		$autoarchiveArr = array(0,0,0);
		if(!isset($settings_fetch['autoarchive'])){
			$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `autoarchive` VARCHAR(255) NOT NULL DEFAULT ''");
			echo '<h2>Database Field "autoarchive" added</h2>';
		} else {
			$autoarchiveArr = explode(',',$settings_fetch['autoarchive']);
		}
		$autoarchive_action = (int) $autoarchiveArr[0];
		if (count($autoarchiveArr) > 1) {$autoarchive_section = (int) $autoarchiveArr[1];} else {$autoarchive_section = 0;}
		if ($autoarchive_section == 0) $autoarchive_action = 0;
		?>
		
		<tr id="autoarchivetr" style="display: <?php if($use_timebased_publishing == 3) {echo "";} else {echo "none";} ?>" >
			<td class="setting_name"> <?php echo $MOD_TOPICS['AUTO_ARCHIVE'] ?></td>
			<td>
			<select name="autoarchive_action" class="tpfw30" style="width: 30%;"  onchange="topictimefieldchanged(2,this.options[this.selectedIndex].value);">					
				<option value="0" <?php if($autoarchive_action == 0) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['AUTO_ARCHIVE_0']; ?></option>
				<option value="1" <?php if($autoarchive_action == 1) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['AUTO_ARCHIVE_1']; ?></option>
				<option value="2" <?php if($autoarchive_action == 2) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['AUTO_ARCHIVE_2']; ?></option>
				<option value="3" <?php if($autoarchive_action == 3) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['AUTO_ARCHIVE_3']; ?></option>
			</select>
			
			<span id="autoarchive_sectionspan" style="display: <?php  if($autoarchive_action > 0) {echo "inline";} else {echo "none";} ?>">
			
			<span style="padding-left:20px;"><?php echo $MOD_TOPICS['MOVE_TO'] ?></span>			
			<?php 
			
			//The same like in modify_topic.php - should we make a function?
			$picture_dir = ''.$settings_fetch['picture_dir']; //Auch wenn es leer ist
			$theothersq = "SELECT section_title, section_id, page_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id > '0' ORDER BY section_id ASC";
			if ($restrict2picdir > 0) {
				$theq = "SELECT section_id FROM ".TABLE_PREFIX."mod_".$mod_dir."_settings WHERE section_id > '0' AND picture_dir = '".$picture_dir."'";	
				$query = $database->query($theq);				
				if($query->numRows() > 0) {		
					$restricttosections = array();
					while($thesection = $query->fetchRow()) {
						$restricttosections[] = $thesection['section_id'];
					}
					$restricttosectionsstring = implode(',',$restricttosections);
					$theothersq = "SELECT section_title, section_id, page_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id IN (".$restricttosectionsstring.") ORDER BY section_id ASC";
				}				
			} 
			
			$query_others = $database->query($theothersq);
			//$query_others = $database->query("SELECT section_title, section_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id > '0' ORDER BY section_id ASC");
			if($query_others->numRows() > 1) { 			
				$out = '';
				while($others = $query_others->fetchRow()) {
					$s_id = (int)$others['section_id'];
					$stitle = $others['section_title'];
					if ($s_id == $section_id) {$nowis = $stitle; continue;}
					if ($stitle == '') {$stitle = 'Section '.$s_id;}		
					$out .= '<option value="'.$s_id.'"';
					if ($s_id == $autoarchive_section) {$out .= ' selected="selected"';}
					$out .= '>'.$stitle.'</option>';		
				}
			
				
				echo '<select name="autoarchive_section" style="width: 30%;">
				<option value="0">--</option>'.$out.'</select>
				</div>';
			} else {
				echo "<b>no other sections found</b>";
			
			}?>

			
			</span>			
			</td>
		</tr>
		<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['SORT_TOPICS']; ?>:</td>
			<td class="setting_fields">
				<select name="sort_topics" class="tpfw98">
					<option value="0" <?php if($settings_fetch['sort_topics'] == '0') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_POSITION']; ?></option>
					<option value="1" <?php if($settings_fetch['sort_topics'] == '1') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_PUBL']; ?></option>
					<!--option value="2" <?php if($settings_fetch['sort_topics'] == '2') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_SCORE']; ?></option-->
					<option value="3" <?php if($settings_fetch['sort_topics'] == '3') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_EVENT']; ?></option>
					<option value="4" <?php if($settings_fetch['sort_topics'] == '4') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_TITLE']; ?></option>
					<option value="--" disabled="disabled">Reversed:</option>
					<option value="-1" <?php if($settings_fetch['sort_topics'] == '-1') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_POSITION']; ?> (asc)</option>
					<option value="-2" <?php if($settings_fetch['sort_topics'] == '-2') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_PUBL']; ?> (asc)</option>
					<!--option value="-3" <?php if($settings_fetch['sort_topics'] == '-3') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_SCORE']; ?> (asc)</option-->
					<!--option value="-4" <?php if($settings_fetch['sort_topics'] == '-4') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_EVENT']; ?> (asc)</option-->
					<option value="-5" <?php if($settings_fetch['sort_topics'] == '-5') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['SORT_TITLE']; ?> (asc)</option>										
				</select>
			</td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['TOPICS_PER_PAGE']; ?>:</td>
			<td class="setting_fields">
			<?php 				
			$topics_per_page = (int) $settings_fetch['topics_per_page'];
			if ($topics_per_page < 0) {$topics_per_page = 0;}
			echo '<input type="text" name="topics_per_page" style="width: 60px;" value="'.$topics_per_page.'" />';
			echo ' (0: '.$TEXT['UNLIMITED'].', 1: '.$MOD_TOPICS['SINGLETOPIC'].')';
			
			?>
			</td>
		</tr>
		<?php 
		//========================================================================//
		//is_master Option:
		if ($is_master == '') {
			$theq = "SELECT topic_id FROM ".TABLE_PREFIX."mod_".$mod_dir." WHERE section_id > '".$section_id."'";	
			$query = $database->query($theq);				
			if($query->numRows() == 0) { //OK, no topics in this section
				$theq = "SELECT section_id FROM ".TABLE_PREFIX."mod_".$mod_dir."_settings WHERE section_id > '0' AND section_id <> '".$section_id."' AND picture_dir = '".$settings_fetch['picture_dir']."' AND is_master_for = '' ";	
				$query = $database->query($theq);
				if($query->numRows() > 1) { //OK, more than 1 section which is NOT master
					echo '<tr>
				<td class="setting_name">Master Section:</td>
				<td class="setting_fields">
				<input type="checkbox" name="is_master_for_check" value="same picture dir" /> Use as aggregator for other topic-sections.
				</td>
				</tr>
				';
				}
			}
		}
		?>
	</table>

	<div class="infodiv"><?php echo $MOD_TOPICS_INFO['SECTIONSETTINGS']; ?>
	<?php echo '<div style="float:right"><a target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'#seiten">'.$MENU['HELP'].'</a></div>'; ?>
	</div><br style="clear:both;" />
</div>
	
	
<div <?php if ($use_pictures == 0) { echo ' style="display:none;"';}  ?>>	
<div class="tabarea" id="tabarea2">
<h3><?php echo $MOD_TOPICS['PICTURES']; ?></h3>
	<table class="settingsleft" cellpadding="2" cellspacing="0">
	<tr>
	<td class="setting_name"><?php echo $MOD_TOPICS['PICTURE_DIR']; ?></td>
	<td><input class="inputf" type="text" name="picture_dir" value="<?php echo htmlspecialchars($settings_fetch['picture_dir']); ?>" style="width: 50%;" maxlength="255" /></td>
	</tr>
	<tr <?php if ($is_master != '') {echo ' style="display:none;"';} ?> >
	<td class="setting_name">Upload / Handling</td>
	<td class="setting_fields">	
		
		<table class="picture_values">
		<tr><td>&nbsp;</td><td colspan=2>Standard:</td><td colspan=2>Additional:</td></tr>
		<tr><td>&nbsp;</td><td>width:</td><td>height:</td><td>width*:</td><td>height*:</td></tr>
		<tr><td>Zoom:</td>
		<td><input type="text" name="w_zoom" id="w_zoom" size="4" value="<?php echo $w_zoom; ?>" style="width:40px; "></td><td><input type="text" name="h_zoom" id="h_zoom" size="4" value="<?php echo $h_zoom; ?>" style="width:40px; "></td>
		<td><input type="text" name="w_zoom2" id="w_zoom2" size="4" value="<?php echo $w_zoom2; ?>" style="width:40px; "></td><td><input type="text" name="h_zoom2" id="h_zoom2" size="4" value="<?php echo $h_zoom2; ?>" style="width:40px; "></td>
		</tr>
		<tr><td>View:</td>
		<td><input type="text" name="w_view" id="w_view" size="4" value="<?php echo $w_view; ?>" style="width:40px; "></td><td><input type="text" name="h_view" id="h_view" size="4" value="<?php echo $h_view; ?>" style="width:40px; "></td>
		<td><input type="text" name="w_view2" id="w_view2" size="4" value="<?php echo $w_view2; ?>" style="width:40px; "></td><td><input type="text" name="h_view2" id="h_view2" size="4" value="<?php echo $h_view2; ?>" style="width:40px; "></td>
		</tr>
		<tr><td>Thumb:</td>
		<td><input type="text" name="w_thumb" id="w_thumb" size="4" value="<?php echo $w_thumb; ?>" style="width:40px; "></td><td><input type="text" name="h_thumb" id="h_thumb" size="4" value="<?php echo $h_thumb; ?>" style="width:40px; "></td>
		<td><input type="text" name="w_thumb2" id="w_thumb2" size="4" value="<?php echo $w_thumb2; ?>" style="width:40px; "></td><td><input type="text" name="h_thumb2" id="h_thumb2" size="4" value="<?php echo $h_thumb2; ?>" style="width:40px; "></td>
		</tr>
		<tr><td>Zoom Class:</td>
		<td colspan="2"><input type="text" name="zoomclass" id="zoomclass" size="20" value="<?php echo $zoomclass; ?>" style="width:100px; "></td>
		<td colspan="2"><input type="text" name="zoomclass2" id="zoomclass2" size="20" value="<?php echo $zoomclass2; ?>" style="width:100px; "></td>
		</tr>
		<tr><td>Zoom Rel:</td>
		<td colspan="2"><input type="text" name="zoomrel" id="zoomrel" size="20" value="<?php echo $zoomrel; ?>" style="width:100px; "></td>
		<td colspan="2"><input type="text" name="zoomrel2" id="zoomrel2" size="20" value="<?php echo $zoomrel2; ?>" style="width:100px; "></td>
		</tr>
		<tr><td colspan="5">
	[ADDITIONAL_PICTURES] Loop:<br/>
	<textarea name="additionalpics_string" rows="3" cols="40" class="tpfw98 tpfh30"><?php echo str_replace($raw, $friendly, $setting_additionalpics_string); ?></textarea>
	</td></tr>
		
		</table>
	</tr></td>
	
	</table>
	<div class="infodiv"><?php echo $MOD_TOPICS_INFO['PICTURES']; ?>
	<?php echo '<div style="float:right"><a target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'#pictures">'.$MENU['HELP'].'</a></div>'; ?>
	</div>
	<br style="clear:both;" />

</div>
</div>


<div class="tabarea" id="tabarea3">
<h3><?php echo $MOD_TOPICS['TOPICSPAGE']; ?></h3>
	<table class="settingsleft" cellpadding="2" cellspacing="0">		
		<tr>
			<td class="setting_name" width="20%"><?php echo $TEXT['HEADER']; ?>:</td>
			<td class="setting_fields"><textarea name="header" rows="10" cols="1" class="tpfw98" style="height: 50px;"><?php echo str_replace($raw, $friendly, ($settings_fetch['header'])); ?></textarea></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['LOOP']; ?>:</td>
			<td class="setting_fields"><textarea name="topics_loop" rows="10" cols="1" class="tpfw98" style="height: 80px;"><?php echo str_replace($raw, $friendly, ($settings_fetch['topics_loop'])); ?></textarea></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['FOOTER']; ?>:</td>
			<td class="setting_fields"><textarea name="footer" rows="10" cols="1" class="tpfw98" style="height: 50px;"><?php echo str_replace($raw, $friendly, ($settings_fetch['footer'])); ?></textarea></td>
		</tr>
	</td></tr>
	</table>
		<div class="infodiv"><?php echo $MOD_TOPICS_INFO['TOPICSPAGE']; ?>
		<?php echo '<div style="float:right"><a target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'#loop">'.$MENU['HELP'].'</a></div>'; ?>
		</div><br style="clear:both;" />
		</div>


<div class="tabarea" id="tabarea4">
<h3><?php echo $MOD_TOPICS['TOPIC']; ?></h3>
	<table class="settingsleft" cellpadding="2" cellspacing="0">
		<tr>
			<td class="setting_name"><?php echo $TEXT['HEADER']; ?>:</td>
			<td class="setting_fields"><textarea name="topic_header" rows="10" cols="1" class="tpfw98" style="height: 60px;"><?php echo str_replace($raw, $friendly, ($settings_fetch['topic_header'])); ?></textarea></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="setting_fields"><?php echo $MOD_TOPICS['TOPICLONG']; ?></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['FOOTER']; ?>:</td>
			<td class="setting_fields"><textarea name="topic_footer" rows="10" cols="1" class="tpfw98" style="height: 60px;"><?php echo str_replace($raw, $friendly, ($settings_fetch['topic_footer'])); ?></textarea></td>
		</tr>		
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['TOPICBLOCK2']; ?></td>
			<td class="setting_fields"><textarea name="topic_block2" rows="10" cols="1" class="tpfw98" style="height: 80px;"><?php echo str_replace($raw, $friendly, ($settings_fetch['topic_block2'])); ?></textarea></td>
		</tr>
	</table>
	<div class="infodiv"><?php echo $MOD_TOPICS_INFO['TOPIC']; ?>
	<?php echo '<div style="float:right"><a target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'#topic">'.$MENU['HELP'].'</a></div>'; ?>
	</div><br style="clear:both;" />
</div>
		
		
<div class="tabarea" id="tabarea5">		
<h3><?php echo $MOD_TOPICS['PNSA_STRING']; ?></h3>		
		<table class="settingsleft" cellpadding="2" cellspacing="0">
		<tr>
			<td class="setting_name"><br/><?php echo $TEXT['TITLE']; ?></td>
			<td class="setting_fields">
			<?php 
			/*
			$see_also_link_title = '<h4>'.$MOD_TOPICS['SEE_ALSO_FRONTEND'].'</h4>';
			$next_link_title = '<h4>'.$MOD_TOPICS['SEE_NEXT_POST'].'</h4>';
			$previous_link_title = '<h4>'.$MOD_TOPICS['SEE_PREV_POST'].'</h4>';
			*/
			?>
			
			
				<table width="98%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				    <td width="33%"><?php echo $MOD_TOPICS['SEE_ALSO_FRONTEND']; ?><br/><input class="tpfw90" name="see_also_link_title" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($see_also_link_title)); ?>" /></td>
				     <td width="33%"><?php echo $MOD_TOPICS['SEE_NEXT_POST']; ?><br/><input class="tpfw90" name="next_link_title" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($next_link_title)); ?>" /></td>
				     <td width="33%"><?php echo $MOD_TOPICS['SEE_PREV_POST']; ?><br/><input class="tpfw90" name="previous_link_title" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($previous_link_title)); ?>" /></td>
			      </tr>
			  </table>
			</td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['SEE_ALSO_LOOP']; ?></td>			 
			<td class="setting_fields"><textarea name="sa_string" rows="3" cols="40" class="tpfw98 tpfh30" style="height: 30px;"><?php echo str_replace($raw, $friendly, stripslashes($setting_sa_string)); ?></textarea></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['PREVNEXT_LOOP']; ?></td>
			<td class="setting_fields"><textarea name="pnsa_string" rows="3" cols="40" class="tpfw98" style="height: 30px;"><?php echo str_replace($raw, $friendly, stripslashes($setting_pnsa_string)); ?></textarea></td>		
		</tr>
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['PNSA_MAX']; ?>:</td>
			<td class="setting_fields">
				<select name="pnsa_max" class="tpfw98">
					<option value="0"><?php echo $TEXT['NONE']; ?></option>
					<?php
					for($i = 1; $i <= 8; $i++) {					
						if($settings_fetch['pnsa_max'] == $i) { $selected = ' selected="selected"'; } else { $selected = ''; }
						echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
					}
					?>
				</select>
			</td>
		</tr>		
	</table>
	<div class="infodiv"><?php echo $MOD_TOPICS_INFO['PNSA_STRING']; ?>
	<?php echo '<div style="float:right"><a target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'#pnsa">'.$MENU['HELP'].'</a></div>'; ?>
	</div><br style="clear:both;" />
</div>





<div class="tabarea" id="tabarea6">
<h3><?php echo $TEXT['COMMENTS']; ?></h3>
	<table class="settingsleft" cellpadding="2" cellspacing="0" <?php if ($use_commenting < 0) echo ' style="display:none;"' ?>>		
		<tr>
			<td class="setting_name"><?php echo $TEXT['COMMENTING']; ?>:</td>
			<td class="setting_fields">
				<select name="commenting" class="tpfw98">
					<option value="-1" <?php if($settings_fetch['commenting'] == '-1') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['ALLDISABLED']; ?></option>
					<option value="0" <?php if($settings_fetch['commenting'] == '0') { echo 'selected="selected"'; } ?>><?php echo$TEXT['DISABLED']; ?></option>					
					<option value="1" <?php if($settings_fetch['commenting'] == '1') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['MODERATED']; ?></option>
					<option value="2" <?php if($settings_fetch['commenting'] == '2') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['DELAY']; ?></option>
					<option value="3" <?php if($settings_fetch['commenting'] == '3') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['IMMEDIATELY']; ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="setting_name">&nbsp;</td>
			<td class="setting_fields">
			<input type="checkbox" name="use_commenting_settings" value="1" <?php if($use_commenting_settings > 0) echo ' checked="checked"'; ?>/><?php echo $MOD_TOPICS['COMMENTS_HINT']; ?>
			
			
			</td>
		</tr>
		<tr>
			<td  width="20%"><?php echo $MOD_TOPICS['DEFAULT_HP_LINK']; ?>:</td>
			<td>
				<select name="default_link" class="tpfw98">
					<option value="-1" <?php if($settings_fetch['default_link'] == '-1') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['HP_LINK_DISABLED']; ?></option>					
					<option value="0" <?php if($settings_fetch['default_link'] == '0') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['HP_LINK_OFF']; ?></option>
					<option value="1" <?php if($settings_fetch['default_link'] == '1') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['HP_LINK_MASKED']; ?></option>
					<option value="2" <?php if($settings_fetch['default_link'] == '2') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['HP_LINK_NOFOLLOW']; ?></option>
					<option value="3" <?php if($settings_fetch['default_link'] == '3') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['HP_LINK_SHOW']; ?></option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td  width="20%"><?php echo $TEXT['EMAIL']; ?>:</td>
			<td>
				<select name="emailsettings" class="tpfw98">					
					<option value="0" <?php if($emailsettings == '0') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['EMAIL_NONE']; ?></option>
					<option value="1" <?php if($emailsettings == '1') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['EMAIL_OPTIONAL']; ?></option>
					<option value="2" <?php if($emailsettings == '2') { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['EMAIL_REQUIRED']; ?></option>				
				</select>
			</td>
		</tr>
		
		
		
		
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['SORT_COMMENTS']; ?>:</td>
			<td class="setting_fields">
				<input type="radio" name="sort_comments" id="sort_comments_asc" value="1"<?php if($settings_fetch['sort_comments'] == true) { echo ' checked="checked"'; } ?> />
				<label for="sort_comments_asc"><?php echo $MOD_TOPICS['SORT_COMMENTS_ASC']; ?></label>
				<input type="radio" name="sort_comments" id="sort_comments_desc" value="0"<?php if($settings_fetch['sort_comments'] == false) { echo ' checked="checked"'; } ?> />
				<label for="sort_comments_desc"><?php echo $MOD_TOPICS['SORT_COMMENTS_DESC']; ?></label>
			</td>
		</tr>
		
		
		
		
		<tr>
			<td class="setting_name"><?php echo $TEXT['CAPTCHA_VERIFICATION']; ?>:</td>
			<td class="setting_fields">
				<input type="radio" name="use_captcha" id="use_captcha_true" value="1"<?php if($settings_fetch['use_captcha'] == true) { echo ' checked="checked"'; } ?> />
				<label for="use_captcha_true"><?php echo $TEXT['ENABLED']; ?></label>
				<input type="radio" name="use_captcha" id="use_captcha_false" value="0"<?php if($settings_fetch['use_captcha'] == false) { echo ' checked="checked"'; } ?> />
				<label for="use_captcha_false"><?php echo $TEXT['DISABLED']; ?></label>
			</td>
		</tr>
		
		<tr>
			<td class="setting_name"><?php echo $TEXT['COMMENTS'].' '.$TEXT['HEADER']; ?>:</td>
			<td class="setting_fields"><textarea name="comments_header" rows="10" cols="1" class="tpfw98 tpfh60"><?php echo str_replace($raw, $friendly, ($settings_fetch['comments_header'])); ?></textarea></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['COMMENTS'].' '.$TEXT['LOOP']; ?>:</td>
			<td class="setting_fields"><textarea name="comments_loop" rows="10" cols="1" class="tpfw98 tpfh60"><?php echo str_replace($raw, $friendly, ($settings_fetch['comments_loop'])); ?></textarea></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['COMMENTS'].' '.$TEXT['FOOTER']; ?>:</td>
			<td class="setting_fields"><textarea name="comments_footer" rows="10" cols="1" class="tpfw98 tpfh60"><?php echo str_replace($raw, $friendly, ($settings_fetch['comments_footer'])); ?></textarea></td>
		</tr>
		
		<tr>
			<td class="setting_name"><?php echo $MOD_TOPICS['MAXCOMMENTS']; ?>:</td>
			<td class="setting_fields"><input class="inputf" type="text" name="maxcommentsperpage" value="<?php echo $maxcommentsperpage; ?>" style="width: 10%;" maxlength="3" />
			
			&nbsp;&nbsp;&nbsp;Style:
			<select name="commentstyle" style="width: 70px">					
				<option value="0" <?php if($commentstyle == '0') { echo 'selected="selected"'; } ?>>iFrame</option>
				<option value="1" <?php if($commentstyle == '1') { echo 'selected="selected"'; } ?>>AJAX</option>								
			</select>
			
			</td>
		</tr>
		
		
	</table>
	<div class="infodiv"><?php echo $MOD_TOPICS_INFO['COMMENTS']; ?>
	<?php echo '<div style="float:right"><a target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'#comments">'.$MENU['HELP'].'</a></div>'; ?>
	</div><br style="clear:both;" />
</div>
	
	
	
<div class="tabarea" id="tabarea7">
<h3>Various</h3>
	<table class="settingsleft" cellpadding="2" cellspacing="0">
	<tr>
		<td class="setting_name"><?php echo $MOD_TOPICS['EDITOR_HEIGHTS']; ?>:</td>
		<td class="setting_fields">
			<?php
			
			echo $TEXT['SHORT'].': <input class="inputf tpf-small" name="short_textareaheight" type="text" maxlength="4" value="'.$short_textareaheight.'" /> ';
			echo $TEXT['LONG'].': <input class="inputf tpf-small" name="long_textareaheight" type="text" maxlength="4" value="'.$long_textareaheight.'" /> ';
			echo $MOD_TOPICS['EXTRA'].': <input class="inputf tpf-small" name="extra_textareaheight" type="text" maxlength="4" value="'.$extra_textareaheight.'" /> ';
			echo '<p>'.$MOD_TOPICS['EDITOR_HEIGHTS_HINT'].'</p><hr/>';
			
			
 			if ($use_presets == true) { echo '<p><a href="getsettings.php?section_id='.$section_id.$paramdelimiter.'preset=1" target="_blank">'.$MOD_TOPICS['OPEN_PRESETS_FILE'].'</a></p><hr/>'; } 
			
			if ($admin->get_group_id() == 1 AND $allow_global_settings_change == 1) { echo '<p><input type="checkbox" name="saveforall" value="1" />'.$MOD_TOPICS['SAVEFORALL'].'</p>'; }  
	
	
			

			?>
				
		</td>
	</tr>
	</table>
	<div class="infodiv"><?php echo $MOD_TOPICS_INFO['VARIOUS']; ?>
	<?php echo '<div style="float:right"><a target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'#various">'.$MENU['HELP'].'</a></div>'; ?>
	</div><br style="clear:both;" />
</div>

<script type="text/javascript">
	section_id = <?php echo $section_id; ?>;
	showtabarea(0);
</script>

		
	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="left">
			<input type="hidden" name="gototopicslist" id="gototopicslist" value="" />
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" /> <input type="submit" onclick="document.getElementById('gototopicslist').value = '1';" value="<?php echo $MOD_TOPICS['SAVE_FINISH']; ?>" />
			</td>
			<td align="right">
				<?php $backurl = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id;
				if ($fredit == 1) {$backurl = WB_URL.'/modules/'.$mod_dir.'/modify_fe.php?page_id='.$page_id.'&section_id='.$section_id.'&fredit=1';} ?>
				<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo $backurl; ?>';"  />
			</td>
		</tr>
	</table>
	</form>

<?php

// include the button to edit the optional module CSS files (function added with WB 2.7)
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!

/*			
if(function_exists('edit_module_css')) {
	echo '<br/>';
	edit_module_css($mod_dir);
}
*/

// Print admin footer
if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>

