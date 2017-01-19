<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }
$update_when_modified = true; // Tells script to update when this page was last updated
require('permissioncheck.php');

// Include WB functions file
require_once(WB_PATH.'/framework/functions.php');

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

//Get old settings
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id' AND page_id = '$page_id'");
if($query_settings->numRows() != 1) { die('Wahh!'); }
$settings_fetch = $query_settings->fetchRow();


// This code removes any <?php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php',$serializedelimiter);
$raw = array('<', '>', '',' ');

$get_settings_from = 0; //Preserved for future
$section_title = $admin->add_slashes(str_replace($friendly, $raw, trim($_POST['section_title'])));
$section_description = $admin->add_slashes(str_replace($friendly, $raw, trim($_POST['section_description'])));

$sort_topics = (int) $_POST['sort_topics'];
$topics_per_page = (int) $_POST['topics_per_page'];
$use_timebased_publishing = (int) $_POST['use_timebased_publishing'];

$autoarchive = '0,0,0';
if (isset($_POST['autoarchive_action'])) {$autoarchive_action = (int) $_POST['autoarchive_action'];} else {$autoarchive_action = 0;}
if (isset($_POST['autoarchive_section'])) {$autoarchive_section = (int) $_POST['autoarchive_section'];} else {$autoarchive_section = 0;}
if ($autoarchive_section > 0) {
	$query_others = $database->query("SELECT page_id FROM ".TABLE_PREFIX."mod_topics_settings WHERE section_id = '".$autoarchive_section."'");
	if($query_others->numRows() == 1) { 
		$others = $query_others->fetchRow();			
		$autoarchive_page_id =  $others['page_id'];
		$autoarchive = $autoarchive_action.','.$autoarchive_section.','.$autoarchive_page_id;
	}
}
//Stupid settings:
if ($autoarchive_action == 3 AND $topics_per_page == 0) {$topics_per_page = 100;}
if ($topics_per_page == 1) {$autoarchive = '0,0,0';}



$picture_dir = $admin->add_slashes(trim($_POST['picture_dir']));

$header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['header']));
$topics_loop = $admin->add_slashes(str_replace($friendly, $raw, $_POST['topics_loop']));
$footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['footer']));


$topic_header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['topic_header']));
$topic_footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['topic_footer']));
$topic_block2 = $admin->add_slashes(str_replace($friendly, $raw, $_POST['topic_block2']));


//Previous, NExt, See also:
$pnsa_string = 	str_replace($friendly, $raw, $_POST['pnsa_string']);
$sa_string = str_replace($friendly, $raw, $_POST['sa_string']);
/*
Sorry, this doesnt work proper*/
$see_also_link_title = str_replace($friendly, $raw, $_POST['see_also_link_title']);
$next_link_title = 	str_replace($friendly, $raw, $_POST['next_link_title']);
$previous_link_title = str_replace($friendly, $raw, $_POST['previous_link_title']);

//additionalpics_string
$additionalpics_string = str_replace($friendly, $raw, $_POST['additionalpics_string']);

$pnsa_array = array(stripslashes($see_also_link_title), stripslashes($next_link_title), stripslashes($previous_link_title), stripslashes($pnsa_string), stripslashes($sa_string), stripslashes($additionalpics_string), '','','','');
$pnsa_string =  $admin->add_slashes(implode($serializedelimiter,$pnsa_array));

//ADD new field to database if not exists:
//if (!isset($settings_fetch['pnsa_array'])) { $database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `pnsa_array` BLOB NOT NULL DEFAULT ''"); }
//$pnsa_array_string = base64_encode(serialize($pnsa_array));
//echo $pnsa_array_string;
//die();

//Test:
//$pnsa_array = array($_POST['see_also_link_title'], $_POST['next_link_title'], $_POST['previous_link_title'], $_POST['pnsa_string'], $_POST['sa_string'], $_POST['additionalpics_string'], '','','','');
//$pnsa_string = serialize($pnsa_array);
//if (4==4 OR !unserialize ($pnsa_string)) {

	//$pnsa_string = serialize($pnsa_array);
//}	




$pnsa_max = (int) $_POST['pnsa_max'];


$commenting = (int) ($_POST['commenting']);
$default_link = $admin->add_slashes($_POST['default_link']);
$use_captcha = $admin->add_slashes($_POST['use_captcha']);
$sort_comments = $admin->add_slashes($_POST['sort_comments']);

$comments_header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_header']));
$comments_loop = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_loop']));
$comments_footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_footer']));

$gototopicslist =  (int) $_POST['gototopicslist'];


$short_textareaheight = (int) $_POST['short_textareaheight'];
$long_textareaheight = (int) intval($_POST['long_textareaheight']);
$extra_textareaheight = (int) intval($_POST['extra_textareaheight']);
$emailsettings = (int) $_POST['emailsettings']; if ($emailsettings < 0) {$emailsettings = 0;}
$maxcommentsperpage = (int) $_POST['maxcommentsperpage']; if ($maxcommentsperpage < 0) {$maxcommentsperpage = 0;}
$commentstyle = (int) $_POST['commentstyle']; if ($commentstyle < 0) {$commentstyle = 0;}

$use_commenting_settings = 0;
if (isset($_POST['use_commenting_settings']) AND $_POST['use_commenting_settings'] == '1') {$use_commenting_settings = 1;}
$various_values = ''.$short_textareaheight.','.$long_textareaheight.','.$extra_textareaheight.','.$use_commenting_settings.','.$emailsettings.','.$maxcommentsperpage.','.$commentstyle;



$w_zoom = (int) $_POST['w_zoom'];
$h_zoom = (int) $_POST['h_zoom'];
$w_view = (int) $_POST['w_view'];
$h_view = (int) $_POST['h_view'];
$w_thumb = (int) $_POST['w_thumb'];
$h_thumb = (int) $_POST['h_thumb'];
$zoomclass = page_filename($_POST['zoomclass']);
$zoomrel = page_filename($_POST['zoomrel']);

$w_zoom2 = (int) $_POST['w_zoom2'];
$h_zoom2 = (int) $_POST['h_zoom2'];
$w_view2 = (int) $_POST['w_view2'];
$h_view2 = (int) $_POST['h_view2'];
$w_thumb2 = (int) $_POST['w_thumb2'];
$h_thumb2 = (int) $_POST['h_thumb2'];
$zoomclass2 = page_filename($_POST['zoomclass2']);
$zoomrel2 = page_filename($_POST['zoomrel2']);

$picture_values = $w_zoom.','.$h_zoom.','.$w_view.','.$h_view.','.$w_thumb.','.$h_thumb.','.$zoomclass.','.$zoomrel.','.$w_zoom2.','.$h_zoom2.','.$w_view2.','.$h_view2.','.$w_thumb2.','.$h_thumb2.','.$zoomclass2.','.$zoomrel2;
$picture_values = str_replace(' ','',$picture_values);
$picture_values = str_replace('-','_',$picture_values);

$is_master_for = $admin->add_slashes(trim($_POST['is_master_for']));

if ($is_master_for == '' AND isset($_POST['is_master_for_check']) ) {
	$is_master_for_check = $admin->add_slashes(trim($_POST['is_master_for_check']));
	//die($is_master_for_check);
	$is_master_for = 'same picture dir';
}




$is_master_for = str_replace(' ','', $is_master_for);

if ($is_master_for != '') {
	$autoarchive = 0;
	$use_timebased_publishing = 0;
	$allow_global_settings_change = 0;
	
	
	$is_master_Arr = explode(',', $is_master_for);
	if (is_numeric(trim($is_master_Arr[0]))) {
		$theq = "SELECT section_id FROM ".TABLE_PREFIX."mod_topics_settings WHERE section_id IN (".$is_master_for.")";
		$query_others = $database->query($theq);
		if(!$database->is_error()) {
			$is_master_for = '';
			if($query_others->numRows() > 0) { 		
				$secArr = array();
				while($sec = $query_others->fetchRow()) {		
					$secArr[] = $sec['section_id'];
				}
				
				$is_master_for = implode(',',$secArr);
			}
		}
		//echo '<h2>No fitting sections found!</h2>';	
	} else {
		$is_master_for = 'same picture dir';
	}
}
if ($is_master_for == '') {
	//$is_master_for = $settings_fetch['is_master_for']; //Use old is_master_for if empty
}



$saveforall = 0;
if (isset($_POST['saveforall']) AND $_POST['saveforall'] == '1') {$saveforall = 1;}
//if ($allow_global_settings_change == 2) {$saveforall = 1;} //Outdated 2016


// Update settings
$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename."_settings SET 	
	picture_dir='$picture_dir', 	
	sort_topics = '$sort_topics', 
	topics_per_page = '$topics_per_page', 
	use_timebased_publishing = '$use_timebased_publishing', 
	header = '$header', 
	topics_loop = '$topics_loop', 
	footer = '$footer', 
	topic_header = '$topic_header', 
	topic_footer = '$topic_footer', 
	topic_block2 = '$topic_block2', 
	pnsa_string = '$pnsa_string', 
	pnsa_max = '$pnsa_max', 
	various_values = '$various_values', 
	picture_values = '$picture_values', 
	autoarchive = '$autoarchive', 
	
	comments_header = '$comments_header', 
	comments_loop = '$comments_loop', 
	comments_footer = '$comments_footer', 
	default_link = '$default_link', 
	commenting = '$commenting', 
	sort_comments = '$sort_comments', 
	use_captcha = '$use_captcha'";
	
	if ($saveforall == 1) {
		//same picture dir
		$theq .= " WHERE picture_dir = '$picture_dir'";
	} else {
		$theq .= ", is_master_for='$is_master_for'"; //Master nur speichern, wenn DIESE EInstellungen
		$theq .= " WHERE section_id = '$section_id' AND page_id = '$page_id'";
	}

	$database->query($theq);
	
	
	// Update settings title and description
	$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename."_settings SET 
	section_title = '$section_title', 
	section_description = '$section_description' 
	WHERE section_id = '$section_id' AND page_id = '$page_id'";
	$database->query($theq);
	

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	if ($gototopicslist == 1) {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/'.$mod_dir.'/modify_settings.php?page_id='.$page_id.'&section_id='.$section_id);
	}
}

// Print footer
if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>
