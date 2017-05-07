<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$topic_id = $_GET['topic_id'];
}

require('permissioncheck.php');
$t = topics_localtime();

//Gleich hier abfragen:
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$topic_id'");
$fetch_content = $query_content->fetchRow();
if ($section_id != $fetch_content['section_id']) {die();} //zu Sicherheit

//Ueberpruefung:
if ($authoronly) {
	$authors = $fetch_content['authors'];
	$pos = strpos ($authors,','.$user_id.',');
	if ($pos === false){die("Nix da");}

	//If Author: Only the owner can invite other authors to edit
	if ($user_id != $fetch_content['posted_by']) {$author_invited = false;}
}


$topics_use_wysiwyg = 1;
//if ($topics_use_plain_text > 0) {$topics_use_wysiwyg = 0;}

if ($fredit == 1) ($showoptions = false);

// Get Settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();
$use_timebased_publishing = $settings_fetch['use_timebased_publishing'];
if ($authoronly AND $author_can_change_position == false) {$use_timebased_publishing = 0;}
//if usage as eventkalender always show both date fields:
if ($settings_fetch['sort_topics'] == 3) {$use_timebased_publishing = 2;}

$setting_pnsa_string = $settings_fetch['pnsa_string'];
$showmax_prev_next_links = (int) $settings_fetch['pnsa_max'];

$short_textareaheight = 150;
$long_textareaheight = 400;
$extra_textareaheight = 0;
//if ($use_extra_wysiwyg > 0) {$extra_textareaheight = 300;}
$use_commenting_settings = 0;
if(!isset($settings_fetch['various_values'])){
	$database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `various_values` VARCHAR(255) NOT NULL DEFAULT '150,450,0,0'");
	echo '<h2>Database Field "various_values" added</h2>';
} else {
	if ($settings_fetch['various_values'] != '') {
		$vv = explode(',',$settings_fetch['various_values'].',-2,-2,-2,-2,-2,-2,-2');
		$short_textareaheight = (int) $vv[0]; //if ($short_textareaheight < 0) {$short_textareaheight = 150;}
		$long_textareaheight = (int) $vv[1]; //if ($long_textareaheight == -2) {$long_textareaheight = 400;}
		$extra_textareaheight = (int) $vv[2]; //if ($extra_textareaheight == -2) {$extra_textareaheight = 300;}
		$use_commenting_settings = (int) $vv[3]; if ($use_commenting_settings < 0) {$use_commenting_settings = 0;}
	}
}


// Ist das richtig? brauchen wir das?
if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" rows="30" cols="3" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("short","long","extra");
	require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

//---------

// include jscalendar-setup
$jscal_use_time = true; // whether to use a clock, too
require_once(WB_PATH."/include/jscalendar/wb-setup.php");

$hascontent = $fetch_content['hascontent'];
if ($hascontent  > 0 AND $fetch_content['active'] > 0) {
	$topic_link = WB_URL.$topics_directory.$fetch_content['link'].PAGE_EXTENSION;
	echo '<div style="float:right; width:50px;"><a href="'.$topic_link.'" target="_blank" ><img src="'.THEME_URL.'/images/view_16.png" class="viewbutton" alt="View" /></a></div>';
} ?>

<h2><?php echo $TEXT['ADD'].'/'.$TEXT['MODIFY'].' '.$MOD_TOPICS['TOPIC']; ?></h2>

<?php
$leptoken = (defined('LEPTON_VERSION') && isset($_GET['leptoken'])) ? sprintf('?leptoken=%s', $_GET['leptoken']) : '';
?>
<form name="modify" action="<?php echo WB_URL.'/modules/'.$mod_dir; ?>/save_topic.php<?php echo $leptoken; ?>" method="post" style="margin: 0;">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>" />
<input type="hidden" name="link" value="<?php echo $fetch_content['link']; ?>" />
<input type="hidden" name="fredit" value="<?php echo $fredit; ?>" />
<input type="hidden" name="copytopic" id="copytopic" value="0" />
<input type="hidden" name="posted_first" value="<?php echo $fetch_content['posted_first']; ?>" />
<?php
$leptoken = (defined('LEPTON_VERSION') && isset($_GET['leptoken'])) ? $_GET['leptoken'] : '';
if (!empty($leptoken)) {
?>
<input type="hidden" name="leptoken" value="<?php echo $leptoken; ?>" />
<?php } ?>

<!--input type="hidden" name="topicchangedfields" id="topicchangedfields" value="-1" /-->



<table class="modifytopic-toptable row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
<tr><td>
<div class="modifytopic1"><?php echo $TEXT['TITLE']; ?>:<br/>
<input type="text" name="title" value="<?php echo ($fetch_content['title']); ?>" class="tpfw90" maxlength="255" /></div>
<div class="modifytopic1"><?php echo $MOD_TOPICS['SHORT_DESCRIPTION']; ?>:<br/>
<textarea name="short_description" class="tpfw90 tpfh30"><?php echo (htmlspecialchars($fetch_content['short_description'])); ?></textarea></div>
<?php 
/*=================================================================================================================
New in Topics 2.8: groups_id */
if (isset($fetch_content['groups_id']) AND isset($topics_groups)) {

	$ingroups = $fetch_content['groups_id'];
	echo '<div class="modifytopic1" id="modifygroups">'.$TEXT['GROUP'].':<br/>';
	foreach($topics_groups as $groupname => $groupnr) {		
		$checked = '';
		if (strpos($ingroups, ','.$groupnr.',') !== false) {$checked = ' checked="checked"';} 		
		echo '<input name="groups_id[]" type="checkbox" value="'.$groupnr.'"'.$checked.' />'.$groupname.'| ';	
	}
	echo '</div>';
}
/*=================================================================================================================*/

?>




<div class="modifytopic1"><?php echo $TEXT['ACTIVE']; ?>:<br/>
	<select name="active" class="tpfw90">
			<option value="0" <?php if($fetch_content['active'] == '0') { echo 'selected="selected"'; } echo '>'.$MOD_TOPICS['ACTIVE_0']; ?></option>
			<option value="1" <?php if($fetch_content['active'] == '1') { echo 'selected="selected"'; } echo '>'.$MOD_TOPICS['ACTIVE_1']; ?></option>
			<option class="tp_ne1" value="2" <?php if($fetch_content['active'] == '2') { echo 'selected="selected"'; } echo '>'.$MOD_TOPICS['ACTIVE_2']; ?></option>
			<option class="tp_ne1" value="3" <?php if($fetch_content['active'] == '3') { echo 'selected="selected"'; } echo '>'.$MOD_TOPICS['ACTIVE_3']; ?></option>
			<option value="4" <?php if($fetch_content['active'] == '4') { echo 'selected="selected"'; } echo '>'.$MOD_TOPICS['ACTIVE_4']; ?></option>
			<?php if ($authoronly == false OR $author_can_change_position) { ?>
			<option class="tp_ne1" value="5" <?php if($fetch_content['active'] == '5') { echo 'selected'; } echo '>'.$MOD_TOPICS['ACTIVE_5']; ?></option>
			<option class="tp_ne1" value="6" <?php if($fetch_content['active'] == '6') { echo 'selected'; } echo '>'.$MOD_TOPICS['ACTIVE_6']; ?></option>
			<?php } ?>
		</select>
</div>

<?php
//Kommentare:
$commenting = (int) $fetch_content['commenting']; //echo '<p>commenting: '.$commenting .'</p>';
// if use_commenting_settings == 1: die Box gar nicht zeigen:

if ($use_commenting_settings == 1) {
	echo '<input type="hidden" name="commenting" value="'.$commenting.'" />';
} else {
	$kArr = array(-2 => 'Default', -1 => $MOD_TOPICS['ALLDISABLED'], 0 => $TEXT['DISABLED'], 1 => $MOD_TOPICS['MODERATED'], 2 => $MOD_TOPICS['DELAY'], 3 => $MOD_TOPICS['IMMEDIATELY'] );
	//var_dump ($kArr);
	if ( $commenting < -1) {
		$settingscommenting = (int) $settings_fetch['commenting'];
		if (array_key_exists ($settingscommenting, $kArr) ) {
			$kArr[-2] = 'Default ('.$kArr[$settingscommenting].')';
		}
	} 	
	
	//Aufbauen:
	echo '<div class="modifytopic1">'.$TEXT['COMMENTING'].':<br/><select name="commenting" class="tpfw90">
	';	
	foreach ($kArr as $key => $value) {
		echo '<option value="'.$key.'" '; if($commenting == $key) { echo 'selected="selected"';} echo '>'.$value.'</option>
		';
	}
	echo '</select></div>
	';
}

?>


<!--div class="modifytopic1" style="display:<?php if( $use_commenting < 0 OR $use_commenting_settings == 1) {echo 'none';} else {echo 'block';} ?>;"><?php echo $TEXT['COMMENTING']; ?>:<br/>
	<select name="commenting" class="tpfw90">
		<option value="-2" <?php if($commenting == -2) { echo 'selected="selected"'; } ?>>Default</option>
		<option value="-1" <?php if($commenting == -1) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['ALLDISABLED']; ?></option>
		<option value="0" <?php if($commenting == 0) { echo 'selected="selected"'; } ?>><?php echo  $TEXT['DISABLED']; ?></option>
		<option value="1" <?php if($commenting == 1) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['MODERATED']; ?></option>
		<option value="2" <?php if($commenting == 2) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['DELAY']; ?></option>
		<option value="3" <?php if($commenting == 3) { echo 'selected="selected"'; } ?>><?php echo $MOD_TOPICS['IMMEDIATELY']; ?></option>
	</select>
</div-->
<div class="modifytopic1" style="display:<?php if ($use_timebased_publishing > 0) {echo 'block';} else {echo 'none';} ?>;">
<table class="tpbreaktable" >
<tr><td><?php 
	if ($use_timebased_publishing < 2) { echo $MOD_TOPICS['TOPIC_DATE']; } else { echo $TEXT['PUBL_START_DATE']; }

?>:</td><td>
	<?php
	$published_when = 0;
	if ($fetch_content['posted_first'] == 0) { // is new

		$published_when = gmdate($jscal_format, $t);
	} else {

	}

	?>

	<input type="text" id="publishdate" name="publishdate" value="<?php if($fetch_content['published_when']==0) echo $published_when; else print date($jscal_format, $fetch_content['published_when']);?>" style="width: 120px;" />
	<img src="<?php echo THEME_URL ?>/images/clock_16.png" id="publishdate_trigger" style="cursor: pointer;" title="<?php echo $TEXT['CALENDAR']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" alt="" />
	<img src="<?php echo THEME_URL ?>/images/clock_del_16.png" style="cursor: pointer;" title="<?php echo $TEXT['DELETE_DATE']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" onclick="document.modify.publishdate.value=''" alt=""/>
	</td></tr>
<tr<?php if ($use_timebased_publishing < 2) { echo  ' style="display:none;"';} echo '><td>'.$TEXT['PUBL_END_DATE']; ?>:</td><td>
	<input type="text" id="enddate" name="enddate" value="<?php if($fetch_content['published_until']==0) print ""; else print gmdate($jscal_format, $fetch_content['published_until'])?>" style="width: 120px;" />
	<img src="<?php echo THEME_URL ?>/images/clock_16.png" id="enddate_trigger" style="cursor: pointer;" title="<?php echo $TEXT['CALENDAR']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" alt=""/>
	<img src="<?php echo THEME_URL ?>/images/clock_del_16.png" style="cursor: pointer;" title="<?php echo $TEXT['DELETE_DATE']; ?>" onmouseover="this.style.background='lightgrey';" onmouseout="this.style.background=''" onclick="document.modify.enddate.value=''" alt=""/>
	</td></tr>
</table></div>

</td>

<!-- rechte Seite -->


<?php
$use_pictures = 1; //Standard now
$picture_dir = ''.$settings_fetch['picture_dir']; //Auch wenn es leer ist
if ($use_pictures > 0) {

	$picfile = $fetch_content['picture'];


	echo '<td width="250" id="topicpic_chooser">';

	$helppage = 'help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'topic_id='.$topic_id.$paramdelimiter.'fredit='.$fredit;
	if ($picture_dir != '') {

		$suggest_id = $topic_id;
		if ($use_pictures > 1 AND $topic_id > $use_pictures ) {
			$suggest_id = $topic_id % $use_pictures;
			if ($suggest_id == 0) {$suggest_id = $use_pictures;}
		}

		$file_dir= WB_PATH.''.$picture_dir;
		if ($picfile == '') {
			if(file_exists(WB_PATH.$picture_dir.'/'.$suggest_id.'.jpg')) {
				$picfile = ''.$suggest_id.'.jpg';
				$previewpic = WB_URL.$picture_dir.'/'.$suggest_id.'.jpg';
			} else {
				$previewpic =  WB_URL . '/modules/'.$mod_dir.'/img/nopic.jpg';
			}
		} else {
			$previewpic =  WB_URL.$picture_dir.'/'.$picfile.'?t='.time();

		}
		if (substr($picfile, 0, 7) == 'http://') {$previewpic = $picfile; }
		//echo $previewpic;

		//======= Picture:
		echo $TEXT['IMAGE'].":<br/>";
		echo '<input class="tp_ne1" type="text" style="width:300px;" value="'.$picfile.'" name="picture" id="picture" onchange="javascript:changepic(1)" />';
		echo '<div class="topicpic_container"><img src="'.$previewpic.'" name="topicpic" id="topicpic" alt="" /></div>';
		
		
		echo '<div class="switchbox">
		<a href="javascript:openpicturepreviews();"><img src="img/picselect.gif" width="40" height="26" title="Select" alt="Select" /></a>
		<a class="showpictureupload" href="javascript:showpictureupload('.$topic_id.');"><img src="img/picupload.gif" width="40" height="26" title="Upload" alt="Upload" /></a>
		<a class="tp_ne1" id="openpicturemodifytext" href="javascript:openpicturemodify();"><img src="img/picmodify.gif" width="40" height="26" title="Modify" alt="Modify" /></a>
		&nbsp; <a class="tp_ne1" id="showadditionalpictures" href="javascript:showadditionalpictures('.$topic_id.');"><img src="img/picadditional.gif" width="40" height="26" title="Modify" alt="Additional" /></a>
		</div>';
		
		//echo '<a href="javascript:openpicturepreviews();">'.$MOD_TOPICS['SHOW_PREVIEWS'].'</a>';
		//echo '<span id="openpicturemodifytext"> | <a id="openpicturemodifytext" href="javascript:openpicturemodify();">Modify</a></span>';		
		//echo '<a class="showpictureupload" href="javascript:showpictureupload();">upload</a>
		
		echo '<div id="pictureupload" style="display:none;" ></div>';
		
		
		//Anderes Bild hochladen:<br/><input type="file" class="inputfield" name="uploadpic" size="50" style="max-width:150px; "/></div>';
		

	} else {
		echo '<p>'.$MOD_TOPICS['NO_PICTUREDIR'].'<br/><b>'.$picture_dir.'</b></p><a href="'.$helppage.'#pictures" target="_blank" class="modifytopichelp">'.$MOD_TOPICS['SEE_HELP_FILE'].'</a>';
	}

	

	echo '</td>';
}

?>


</tr>
</table>

<div id="additionalpictures" style="display:none;"></div>
<hr/>
<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
<?php
//======================================================================================================
// Up to 3 Editors: 
//Editor short
if ($short_textareaheight < 1) {
	if ($short_textareaheight < -10) {
		echo '<tr><td>'.$TEXT['SHORT'].':</td></tr>
		<tr><td>';
		echo '<tr><td><textarea name="short" style="width: 98%; height:'.(0 - $short_textareaheight).'px">'.$fetch_content['content_short'].'</textarea></td></tr>';
	} else {	
		echo '<tr><td><textarea name="short" style="display:none">'.$fetch_content['content_short'].'</textarea></td></tr>';
	}
} else {
	echo '<tr><td>'.$TEXT['SHORT'].':</td></tr>
	<tr><td>';
	if ($topics_use_wysiwyg == 0) {
		echo '<textarea name="short" rows="30" cols="3" style="width: 98%; height: '.$short_textareaheight.'px;">'.$fetch_content['content_short'].'</textarea>';
	} else {
		show_wysiwyg_editor("short","short",htmlspecialchars($fetch_content['content_short']),"100%","".(75 + $short_textareaheight)."px");
	}
	echo "\n</td></tr>\n";
}

//Editor Long
if ($long_textareaheight < 1) {
	if ($short_textareaheight < -10) {
		echo '<tr><td>'.$TEXT['LONG'].':</td></tr>
		<tr><td>';
		echo '<tr><td><textarea name="long" style="width: 98%; height:'.(0 - $long_textareaheight).'px">'.$fetch_content['content_long'].'</textarea></td></tr>';
	} else {
		echo '<textarea name="long" rows="30" cols="3" style="display:none">'.$fetch_content['content_long'].'</textarea>';
	}
} else {
	echo '<tr><td>'.$TEXT['LONG'].':</td></tr>
	<tr><td>';
	if ($topics_use_wysiwyg == 0) {
		echo '<textarea name="long" style="width: 98%; height: '.$long_textareaheight.'px;">'.$fetch_content['content_long'].'</textarea>';
	} else {
		show_wysiwyg_editor("long","long",htmlspecialchars($fetch_content['content_long']),"100%","".(75 + $long_textareaheight)."px");
	}
	echo "\n</td></tr>\n";
}


//Editor EXTRA
if ($extra_textareaheight < 10) {
	if ($short_textareaheight < -10) {
		echo '<tr><td>'.$MOD_TOPICS['EXTRA'].':</td></tr>
		<tr><td>';
		echo '<tr><td><textarea name="extra" style="width: 98%; height:'.(0 - $extra_textareaheight).'px">'.$fetch_content['content_extra'].'</textarea></td></tr>';
	} else {
		echo '<tr><td><textarea name="extra" rows="30" cols="3" style="display:none">'.$fetch_content['content_extra'].'</textarea></td></tr>';
	}
} else {
	echo '<tr><td>'.$MOD_TOPICS['EXTRA'].':</td></tr>
	<tr><td>';
	if ($topics_use_wysiwyg == 0) {
		echo '<textarea name="extra" rows="30" cols="3" style="width: 98%; height: '.$extra_textareaheight.'px;">'.$fetch_content['content_extra'].'</textarea>';
	} else {
		show_wysiwyg_editor("extra","extra",htmlspecialchars($fetch_content['content_extra']),"100%","".(75 + $extra_textareaheight)."px");
	}
	echo "\n</td></tr>\n";
}
//End Editors
//======================================================================================================
?>
</table>


<?php //======================================================================================================
//Modify Extrafields, Metatags, filename, move topic
?>

<hr class="modify_header_and_file" />
<?php $diff = time() - $fetch_content['posted_first']; ?>
<table class="modify_header_and_file row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
<tr><td style="width:70%; padding-right:10px;">
<div <?php if ($fetch_content['content_long'] == '') {echo ' style="display:none;"';} ?>>
<div class="modifytopic1">Meta-Description:<br/>
<textarea onchange="tp_changedmetafield(this);" name="description" rows="30" cols="3" style="width: 98%; height: 50px;"><?php echo $fetch_content['description']; ?></textarea></div>
<div class="modifytopic1">Meta-Keywords:<br/>
<input onchange="tp_changedmetafield(this);" type="text" name="keywords" value="<?php echo (htmlspecialchars($fetch_content['keywords'])); ?>" style="width: 98%;" maxlength="255" /></div>
</div>
<div class="modifytopictxtr" <?php if ($extrafield_1_name == '') {echo ' style="display:none;"';} echo '>'.$extrafield_1_name; ?><br/>
<input type="text" name="txtr1" value="<?php echo (htmlspecialchars($fetch_content['txtr1'])); ?>" style="width: 98%;" maxlength="255" /></div>
<div class="modifytopictxtr" <?php if ($extrafield_2_name == '') {echo ' style="display:none;"';} echo '>'.$extrafield_2_name; ?><br/>
<input type="text" name="txtr2" value="<?php echo (htmlspecialchars($fetch_content['txtr2'])); ?>" style="width: 98%;" maxlength="255" /></div>
<div class="modifytopictxtr" <?php if ($extrafield_3_name == '') {echo ' style="display:none;"';} echo '>'.$extrafield_3_name; ?><br/>
<input type="text" name="txtr3" value="<?php echo (htmlspecialchars($fetch_content['txtr3'])); ?>" style="width: 98%;" maxlength="255" /></div>
<!--input type="checkbox" name="resizepics" value="1">Bilder neu berechnen-->
</td><td style="padding-left:10px;">


<?php
//$posted_first = $fetch_content['posted_first'];
$t = time() - $fetch_content['posted_first']; $t = $t / 3600;
if ( $t < 4)   {$allow_change_link = 1;}
if($hascontent < 1) {$allow_change_link = 0;}
//echo $t;

$allow_change_link = 1; //Replace outdated option, 2016
if($allow_change_link AND $author_trust_rating < 3) { echo '<div class="modifytopic1">'.$MOD_TOPICS['CHANGE_URL'].':<br/><input type="text" name="user_link" value="'.$fetch_content['link'].'" style="width: 98%;" maxlength="255" /><br/>'.$MOD_TOPICS['CHANGE_URL_HINT'].'</div>'; } ?>

<?php
//Wenn $restrict2picdir > 0 (modulesettings) dann k�nnen Topics nur zu sections verschoben werden, die das gleiche picture_dir haben.

if ($restrict2picdir > 0) {
	$theq = "SELECT section_id FROM ".TABLE_PREFIX."mod_".$mod_dir."_settings WHERE section_id > '0' AND is_master_for = '' AND picture_dir = '".$picture_dir."'";
	$query = $database->query($theq);
	if($query->numRows() > 0) {
		$restricttosections = array();
		while($thesection = $query->fetchRow()) {
			$restricttosections[] = $thesection['section_id'];
		}
		$restricttosectionsstring = implode(',',$restricttosections);
	}
	$query_others = $database->query("SELECT section_title, section_id, page_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE is_master_for = '' AND section_id IN (".$restricttosectionsstring.") ORDER BY section_id ASC");
} else {
	$query_others = $database->query("SELECT section_title, section_id, page_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE is_master_for = '' AND section_id > '0' ORDER BY section_id ASC");
}



if($query_others->numRows() > 1 AND $author_trust_rating < 2) {

	$out = '';
	$nowis = '';
	while($others = $query_others->fetchRow()) {
		$s_id = (int)$others['section_id'];
		$stitle = $others['section_title'];
		if ($s_id == $section_id) {$nowis = $stitle; continue;}
		if ($user_id > 1) {if (!$admin->get_page_permission($others['page_id']))  {continue; } }
		if ($stitle == '') {$stitle = 'Section '.$s_id;}
		$out .= '<option value="'.$s_id.'">'.$stitle.'</option>';
	}

	echo '<div class="modifytopic1">'.$MOD_TOPICS['MOVE_TOPIC_TO'].'<br/>
	Is: '.$nowis.'
	<select name="movetopic" id="movetopic" style="width: 98%;">
	<option value="0"></option>'.$out.'</select>
	</div>';
}

$modifyurl = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if ($fredit == 1) {$modifyurl = WB_URL.'/modules/'.$mod_dir.'/modify_fe.php?page_id='.$page_id.'&section_id='.$section_id.'&fredit=1';}
?>
</td>
</tr></table>
<?php //======================================================================================================
//End Modify Extrafields, Metatags, filename, move topic

//Start: Save, Cancel
?>

<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input type="hidden" name="gototopicslist" id="gototopicslist" value="" />
		<input type="hidden" name="transfer_ownership" id="transfer_ownership" value="" />
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>"  /> <input type="submit" onclick="document.getElementById('gototopicslist').value = '1';" value="<?php echo $MOD_TOPICS['SAVE_FINISH']; ?>" />
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo $modifyurl; ?>';" />
	</td>
</tr>
</table>
</form>

<script type="text/javascript">
	Calendar.setup(
		{
			inputField  : "publishdate",
			ifFormat    : "<?php echo $jscal_ifformat ?>",
			button      : "publishdate_trigger",
			firstDay    : <?php echo $jscal_firstday ?>,
			<?php if(isset($jscal_use_time) && $jscal_use_time==TRUE) { ?>
				showsTime   : "true",
				timeFormat  : "24",
			<?php } ?>
			date        : "<?php echo $jscal_today ?>",
			range       : [1970, 2037],
			step        : 1
		}
	);
	Calendar.setup(
		{
			inputField  : "enddate",
			ifFormat    : "<?php echo $jscal_ifformat ?>",
			button      : "enddate_trigger",
			firstDay    : <?php echo $jscal_firstday ?>,
			<?php if(isset($jscal_use_time) && $jscal_use_time==TRUE) { ?>
				showsTime   : "true",
				timeFormat  : "24",
			<?php } ?>
			date        : "<?php echo $jscal_today ?>",
			range       : [1970, 2037],
			step        : 1
		}
	);
</script>

<?php if ($picture_dir <> "") { echo '
<script type="text/javascript">
	var topicpicloc = "'.WB_URL.''.$picture_dir.'/";
</script>';
} ?>





<div <?php //if ($fetch_content['content_long'] == '') {echo ' style="display:none;"';} ?>>
<hr/>
<a name="pnsa_links" id="pnsa_links"></a>
<?php
//Start Block: Edit other pages:
echo '<div class="modify_other_pages">';

$query_topics = $database->query("SELECT topic_id FROM ".TABLE_PREFIX."mod_".$tablename);
if($query_topics->numRows() > 0) { //Shit, cant find the bug, should be > 1
	//echo '<table cellpadding="2" cellspacing="0" border="0" width="100%"><tr><td>';
 	if ($authoronly == false) { echo '<h2>'.$MOD_TOPICS['PNSA_LINKS'].'</h2>';}

	// Look for see_also and previous, next topics
	$sort_topics = (int) $settings_fetch['sort_topics'];
	$sort_topics_by = get_sort_topics_by($sort_topics);

	$sort_topics_by = ' position DESC';
	$position = $fetch_content['position'];
	$thetp =  $fetch_content['published_when'];
	if ($thetp == 0) {$thetp =  $fetch_content['posted_first'];}

	$sortkrit =  "published_when > (".$fetch_content['published_when'].")";

	switch ($sort_topics) {
		case 0: $sortkrit =  " position > (".$position.")"; break;
		case 1: $sortkrit =  " published_when > (".$thetp.")"; break;
		case 2: $sortkrit =  " topic_score  > (".$fetch_content['topic_score'].")"; break;
		case 3: $sortkrit =  " published_when > (".$thetp.")"; break;
		case 4: $sortkrit =  " title < '".addslashes($fetch_content['title'])."'"; break;

		case -1: $sortkrit =  " position < (".$position.")"; break;
		case -2: $sortkrit =  " published_when < (".$thetp.")"; break;
		case -3: $sortkrit =  " topic_score  < (".$fetch_content['topic_score'].")"; break;
		case -4: $sortkrit =  " published_when < (".$thetp.")"; break;
		case -5: $sortkrit =  " title > '".addslashes($fetch_content['title'])."'"; break;
	}




	$query_extra = ' AND title <> "" ';

	$modifylink = 'modify_topic.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'fredit='.$fredit.$paramdelimiter.'topic_id=';
	$see_also_text = $fetch_content['see_also'];
	$see_also_output = '';
	$see_prevnext_output = '';
	$show_prevnext_links = 1;
	$singletopic_id = 0;
	$singletopic_link = '';
	//if ($show_prevnext_links == 1 OR  $see_also_text != '') {
	
	
	
	//Parse one string into 5 fields:
	$setting_pnsa_string = stripslashes($settings_fetch['pnsa_string']);
	$setting_pnsa_array = explode($serializedelimiter,$setting_pnsa_string);		
	
	if (is_array($setting_pnsa_array) AND count($setting_pnsa_array) > 4 ) {
		$see_also_link_title = $setting_pnsa_array[0];
		$next_link_title = $setting_pnsa_array[1];
		$previous_link_title = $setting_pnsa_array[2];		
		$setting_pnsa_string = $setting_pnsa_array[3];
		$setting_sa_string = $setting_pnsa_array[4];
	} 

	echo '<table class="pnsa_links"><tr><td class="modify_pnsa_links pn_links">';
	$frombackend = true;
	if (!defined('TOPIC_ID')) {define('TOPIC_ID', $topic_id); }
	$picture_dir = WB_URL.$settings_fetch['picture_dir'];
	if ($authoronly == false) {
		$refreshstring = '?t='.$t; //forces loading of files
		include('inc/find_pnsa_links.inc.php');
		if ($see_prevnext_output != '') {echo $see_prevnext_output;} else {echo $TEXT['NONE_FOUND'];}
		echo '</td><td class="sa_links">';
		if ($see_also_output != '') {echo $see_also_output;} else {echo '<b>'.$MOD_TOPICS['SEE_ALSO_FRONTEND']. '</b><br/>'.$TEXT['NONE_FOUND'];}
	}
	echo '</td></tr></table>';
	//End Table PN_SA
	
	echo '</div>'; //end class="modify_other_pages"
	
	//=======================================================================================
	
	//Start Block: Buttons:
	echo '<div class="modify_buttons"><h2>&nbsp;</h2>';
	
	$params = 'page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'topic_id='.$topic_id.$paramdelimiter.'fredit='.$fredit;
	echo '<div class="topic-modifytopic">';
	if ($fetch_content['content_long'] != '' AND $author_trust_rating < 3) {
		if ($topic_seealso_support == 'bakery') {
			echo '<a class="topic-modifytopic-bakery" href="modify_seealso-bakery.php?'.$params.'">'.$MOD_TOPICS['SEE_ALSO_CHANGE']."</a>\n";
		} else {
			echo '<a class="topic-modifytopic-see-also" href="modify_seealso.php?'.$params.'">'.$MOD_TOPICS['SEE_ALSO_CHANGE']."</a>\n";
		}
		if ($authorsgroup > 0 AND ($author_invited OR $authoronly == false OR $user_id == $fetch_content['posted_by']) ) {
			echo '<a class="topic-modifytopic-authors" href="modify_authors.php?'.$params.'">'.$MOD_TOPICS['EDITAUTHORS']."</a>\n";
		}
	}
	echo '<a class="topic-modifytopic-new" href="add_topic.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'fredit='.$fredit.'">'.$MOD_TOPICS['NEWTOPIC']."</a>\n";
	echo '<a class="topic-modifytopic-duplicate" href="javascript:copythistopic()">'.$MOD_TOPICS['COPYTOPIC']."</a>\n";

	
	if ($showoptions) {echo '<a class="topic-modifytopic-settings" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_settings.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'">'.$TEXT['SETTINGS']."</a>\n";}
	if ($showoptions) {echo '<a class="topic-modifytopic-help" href="'.WB_URL.'/modules/'.$mod_dir.'/help.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.'">'.$MENU['HELP']."</a>\n";}

	if ($author_trust_rating < 3) {
		echo '<a class="topic-modifytopic-delete" href="javascript: confirm_link(\''.$TEXT['ARE_YOU_SURE']. '\', \''.WB_URL.'/modules/'.$mod_dir.'/delete_topic.php?'.$params.'\');" title="'.$TEXT['DELETE'].'">'.$TEXT['DELETE'].'</a>';
	}


	echo "</div></div>"; //END Block: Buttons:

}

//alternative delete button
if ($author_trust_rating < 3) {
	echo '<a class="topic-modifytopic-delete-alternative" href="javascript: confirm_link(\''.$TEXT['ARE_YOU_SURE']. '\', \''.WB_URL.'/modules/'.$mod_dir.'/delete_topic.php?'.$params.'\');" title="'.$TEXT['DELETE'].'">'.$TEXT['DELETE'].'</a>';
}

?>
<hr style="clear:both" />
</div><!-- end block pnsa and modify buttons -->

<a name="comments" id="comments"></a>


<?php
// Loop through existing Comments
$query_comments = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_".$tablename."_comments` WHERE topic_id = '$topic_id' ORDER BY commented_when DESC");
if($query_comments->numRows() > 0) {
	echo '<h2>'.$TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$TEXT['COMMENT'].'</h2>';
	$row = 'a';
	?>

<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<?php
	while($comment = $query_comments->fetchRow( MYSQL_ASSOC )) {
		$editcommentlink = WB_URL.'/modules/'.$mod_dir.'/modify_comment.php?page_id='.$page_id.$paramdelimiter.'section_id='.$section_id.$paramdelimiter.'fredit='.$fredit.$paramdelimiter.'comment_id='.$comment['comment_id'].'" title="'.$TEXT['MODIFY'].'"';
		?><tr class="row_<?php echo $row; ?>">
			<td width="20" style="padding-left: 5px;"><a href="<?php echo $editcommentlink; ?>"><img src="<?php echo THEME_URL; ?>/images/modify_16.png" border="0" alt="" title="edit" /></a></td>
			<td><a href="<?php echo $editcommentlink.'"><b>'.$comment['name'].'</b></a><br/><small>'.$comment['comment'].'</small>'; ?></td>
			<td width="20"><img src="img/comactive<?php echo $comment['active']; ?>.gif" /></td>
			<td width="20"><img src="img/comlink<?php if ($comment['website'] =='') {echo 'none';} else {echo $comment['show_link'];} ?>.gif" /></td>


			<td width="20">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL.'/modules/'.$mod_dir; ?>/delete_comment.php?page_id=<?php echo $page_id.$paramdelimiter; ?>section_id=<?php echo $section_id.$paramdelimiter; ?>topic_id=<?php echo $topic_id.$paramdelimiter.'fredit='.$fredit.$paramdelimiter; ?>comment_id=<?php echo $comment['comment_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
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


<?php
}

echo '<div class="authorinfo tp_ne1">';
//Who is the owner of this topic? Who gets emails about new comments?
$commenting = (int) $fetch_content['commenting'];
if( $commenting < -1) {$use_commenting_settings = 1;} //Defaultwert verenden
//Wenn: angekreuzt: Individuelle Einstellungen ignorieren, dann die Settings-Einstellungen verwenden.
if ($use_commenting_settings == 1) { $commenting = (int) $settings_fetch['commenting'];}

//echo $commenting; //Puh!
if ($commenting > 0) { 
	$topicauthornr = $fetch_content['posted_by'];
	$query_topicauthor = $database->query("SELECT email FROM ".TABLE_PREFIX."users WHERE user_id = '".$topicauthornr."'");
	if ($query_topicauthor->numRows() > 0) {
		$authorfetch = $query_topicauthor->fetchRow();
		$this_admin_email = $authorfetch['email'];		
	} else {
		$this_admin_email = 'unknown';
	}
		
	if ($user_id === $topicauthornr) {
		echo '<p>You are the owner of this topic. Notifications about new comments will be sent to '.$this_admin_email.'</p>';
	} else {		
		echo '<div>The owner of this topic is: '.$this_admin_email.'</div>';
		if ($this_admin_email != 'unknown') {
			echo '<div>On save: <a id="transfer_ownership_id" href="#" onclick="document.getElementById(\'transfer_ownership\').value = \'1\'; return false;">Transfer ownership to me</a></div>';
		}
	}
}
echo '</div>'; //End authorinfo
?>

<div class="buttonfloater"><div class="inner">
<a href="<?php echo $modifyurl; ?>" class="back"></a>
<a href="javascript:document.modify.submit();" class="save"></a>
<a href="javascript:document.getElementById('gototopicslist').value = '1'; document.modify.submit();" class="save-back"></a>
<a href="#pnsa_links" class="down"></a>
</div></div>

<!--
<div id="topics_chooser_overlay">
	<div class="topics_chooser_outer">
		<div class="topicpic_preview_close"><a href="javascript:choosethispicture(0);"><img src="img/closebox.png" alt="close" /></a></div>
		<div id="topics_picturechooser"></div>
		
	</div>
</div>
-->

<script type="text/javascript">
	var section_id = <?php echo $section_id; ?>;
	var page_id = <?php echo $page_id; ?>;
	var topic_id = <?php echo $topic_id; ?>;
</script>

<?php
if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}
?>