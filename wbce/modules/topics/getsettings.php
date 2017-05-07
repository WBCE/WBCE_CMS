<?php require('../../config.php');
header('Content-Type: application/javascript');

// Get id
if(!isset($_GET['section_id']) OR !is_numeric($_GET['section_id'])) {
  die("Location: ".ADMIN_URL."/pages/index.php");
} else {
  $section_id = $_GET['section_id'];  
}

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;
require_once(WB_PATH.'/modules/'.$mod_dir.'/defaults/module_settings.default.php');
require_once(WB_PATH.'/modules/'.$mod_dir.'/module_settings.php');

require_once(WB_PATH.'/framework/class.wb.php');
$wb = new wb;
if ($wb->is_authenticated()) {echo "//Starting Javascript\n";} else {die("Sorry, no access");}


//global $wb;


// Get header and footer
	$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
	$settings_fetch = $query_content->fetchRow();
	
	//various values
	$vv = explode(',',$settings_fetch['various_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
	
	$use_commenting_settings = (int) $vv[3];
	if ($use_commenting_settings < 0) {$use_commenting_settings = 0;}
	
	$short_textareaheight = (int) $vv[0]; if ($short_textareaheight < 0) {$short_textareaheight = 0;}
	$long_textareaheight = (int) $vv[1]; if ($long_textareaheight == -2) {$long_textareaheight = 400;}
	$extra_textareaheight = (int) $vv[2]; if ($extra_textareaheight == -2) {$extra_textareaheight = 300;}
	$use_commenting_settings = (int) $vv[3]; if ($use_commenting_settings < 0) {$use_commenting_settings = 0;}
	
	$emailsettings = (int) $vv[4]; if ($emailsettings < 0) {$emailsettings = 2;} //Wie bisher: Pflichtfeld
	$maxcommentsperpage = (int) $vv[5];
	$commentstyle = (int) $vv[6];


	
	//1======================================================================================
	echo 'selectDropdownOption (document.modify.use_timebased_publishing, '.$settings_fetch['use_timebased_publishing'].");\n";
	echo 'selectDropdownOption (document.modify.sort_topics, '.$settings_fetch['sort_topics'].");\n";	
	echo "document.modify.topics_per_page.value = '".$settings_fetch['topics_per_page']."';\n";
	
	
	//2======================================================================================

	echo "document.modify.picture_dir.value = '".$settings_fetch['picture_dir']."';\n";
	
	
	//Picture Values
	$pv = explode(',',$settings_fetch['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');

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
	
	echo "document.modify.w_zoom.value = '".$w_zoom."';\n";
	echo "document.modify.h_zoom.value = '".$h_zoom."';\n";
	echo "document.modify.w_view.value = '".$w_view."';\n";
	echo "document.modify.h_view.value = '".$h_view."';\n";
	echo "document.modify.w_thumb.value = '".$w_thumb."';\n";
	echo "document.modify.h_thumb.value = '".$h_thumb."';\n";
	echo "document.modify.zoomclass.value = '".$zoomclass."';\n";
	echo "document.modify.zoomrel.value = '".$zoomrel."';\n";
	
	echo "document.modify.w_zoom2.value = '".$w_zoom2."';\n";
	echo "document.modify.h_zoom2.value = '".$h_zoom2."';\n";
	echo "document.modify.w_view2.value = '".$w_view2."';\n";
	echo "document.modify.h_view2.value = '".$h_view2."';\n";
	echo "document.modify.w_thumb2.value = '".$w_thumb2."';\n";
	echo "document.modify.h_thumb2.value = '".$h_thumb2."';\n";
	echo "document.modify.zoomclass2.value = '".$zoomclass2."';\n";
	echo "document.modify.zoomrel2.value = '".$zoomrel2."';\n";
	
	
	
	
	if ( isset($settings_fetch['pnsa_array']) ) {
		//echo '<h2>neue Methode</h2>';
		$setting_pnsa_array = unserialize(base64_decode($settings_fetch['pnsa_array']));
	} else {	
		//echo '<h2>alte Methode</h2>';
		$setting_pnsa_string = stripslashes($settings_fetch['pnsa_string']);	
		$setting_pnsa_array = explode($serializedelimiter,$setting_pnsa_string);	
	}
		
	
	
	//3======================================================================================
	
	echo '
//ov:
';
	//Topics Overview
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['header']);
	echo "document.modify.header.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['topics_loop']);
	echo "document.modify.topics_loop.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['footer']);
	echo "document.modify.footer.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";

	echo 'selectDropdownOption (document.modify.pnsa_max, '.$settings_fetch['pnsa_max'].");\n";
	
	//4======================================================================================

	
	echo '
//tp:
';
	//Topics Single View
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['topic_header']);
	echo "document.modify.topic_header.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
		
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['topic_footer']);
	echo "document.modify.topic_footer.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['topic_block2']);
	echo "document.modify.topic_block2.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	
	
	//5======================================================================================
	
	echo '
//pnsa:
';
	
	if (is_array($setting_pnsa_array) AND count($setting_pnsa_array) > 4 ) {
		echo "document.modify.see_also_link_title.value = '".$setting_pnsa_array[0]."';\n";
		echo "document.modify.next_link_title.value = '".$setting_pnsa_array[1]."';\n";
		echo "document.modify.previous_link_title.value = '".$setting_pnsa_array[2]."';\n";
			
		$output = preg_replace("/\r|\n/s", "\\n", $setting_pnsa_array[3]);
		echo "document.modify.pnsa_string.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
			
		$output = preg_replace("/\r|\n/s", "\\n", $setting_pnsa_array[4]);
		echo "document.modify.sa_string.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	}
	
	//6======================================================================================
	
	
	echo '
//comments:
';	
	//comments:
	echo 'selectDropdownOption (document.modify.commenting, '.$settings_fetch['commenting'].");\n";
	echo 'selectDropdownOption (document.modify.default_link, '.$settings_fetch['default_link'].");\n";
	echo 'selectDropdownOption (document.modify.emailsettings, '.$emailsettings.");\n";
	
		
	echo 'selectRadioButtons (document.modify.sort_comments, '.$settings_fetch['sort_comments'].");\n";
	echo 'selectRadioButtons (document.modify.use_captcha, '.$settings_fetch['use_captcha'].");\n";
	echo "document.modify.maxcommentsperpage.value = '".$maxcommentsperpage."';\n";
	
	echo 'selectDropdownOption (document.modify.commentstyle, '.$commentstyle.");\n";

	
	
	//Comments
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['comments_header']);
	echo "document.modify.comments_header.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
		
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['comments_loop']);
	echo "document.modify.comments_loop.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $settings_fetch['comments_footer']);
	echo "document.modify.comments_footer.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	//7======================================================================================
	echo "document.modify.short_textareaheight.value = '".$short_textareaheight ."';\n";
	echo "document.modify.long_textareaheight.value = '".$long_textareaheight."';\n";
	echo "document.modify.extra_textareaheight.value = '".$extra_textareaheight."';\n";
	
	
	//Finishing ======================================================================================
	echo "\n\n// To save as a preset, change this line with your description:\n";
	
	if(isset($_GET['preset']) AND $_GET['preset'] == 1) {
		echo "document.getElementById('presetsdescription').innerHTML = 'Check changed fields';\n\n";
	} else {
		echo "document.getElementById('getfromdescription').innerHTML = 'Check changed fields';\n\n";
	}
	echo 'alert("Done");';
	
	?>