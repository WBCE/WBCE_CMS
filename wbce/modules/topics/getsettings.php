<?php require('../../config.php');
header('Content-Type: text/plain'); // plain text file

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

$wb = new wb;
if ($wb->is_authenticated()) {echo "//Starting Javascript\n";} else {die("Sorry, no access");}


//global $wb;


// Get header and footer
	$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
	$fetch_content = $query_content->fetchRow();
	
	
	
	$vv = explode(',',$fetch_content['picture_values'].',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2');
	$w_zoom = (int) $vv[0]; if ($w_zoom == -2) {$w_zoom = 1000;}
	$h_zoom = (int) $vv[1]; if ($h_zoom == -2) {$h_zoom = 0;}
	$w_view = (int) $vv[2]; if ($w_view == -2) {$w_view = 200;}
	$h_view = (int) $vv[3]; if ($h_view == -2) {$h_view = 0;}
	$w_thumb = (int) $vv[4]; if ($w_thumb == -2) {$w_thumb = 100;}
	$h_thumb = (int) $vv[5]; if ($h_thumb == -2) {$h_thumb = 100;}
	$zoomclass = $vv[6]; if ($zoomclass == "-2") {$zoomclass = "fbx";}
	
	echo "document.modify.w_zoom.value = '".$w_zoom."';\n";
	echo "document.modify.h_zoom.value = '".$h_zoom."';\n";
	echo "document.modify.w_view.value = '".$w_view."';\n";
	echo "document.modify.h_view.value = '".$h_view."';\n";
	echo "document.modify.w_thumb.value = '".$w_thumb."';\n";
	echo "document.modify.h_thumb.value = '".$h_thumb."';\n";
	echo "document.modify.zoomclass.value = '".$zoomclass."';\n";
	
	
	
	
	echo 'selectDropdownOption (document.modify.sort_topics, '.$fetch_content['sort_topics'].");\n";
	//echo 'selectDropdownOption (document.modify.topics_per_page, '.$fetch_content['topics_per_page'].");\n";
	echo "document.modify.topics_per_page.value = '".$fetch_content['topics_per_page']."';\n";
	echo "document.modify.picture_dir.value = '".$fetch_content['picture_dir']."';\n";

	
	//Topics Overview
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['header']);
	echo "document.modify.header.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['topics_loop']);
	echo "document.modify.topics_loop.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['footer']);
	echo "document.modify.footer.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";

	


	
	//Topics Single View
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['topic_header']);
	echo "document.modify.topic_header.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
		
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['topic_footer']);
	echo "document.modify.topic_footer.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['topic_block2']);
	echo "document.modify.topic_block2.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	//pnsa String
	$setting_pnsa_string = $fetch_content['pnsa_string'];
	$setting_pnsa_array = explode($serializedelimiter,$setting_pnsa_string);
	if (is_array($setting_pnsa_array) AND count($setting_pnsa_array) > 4 ) {
		echo "document.modify.see_also_link_title.value = '".$setting_pnsa_array[0]."';\n";
		echo "document.modify.next_link_title.value = '".$setting_pnsa_array[1]."';\n";
		echo "document.modify.previous_link_title.value = '".$setting_pnsa_array[2]."';\n";
			
		$output = preg_replace("/\r|\n/s", "\\n", $setting_pnsa_array[3]);
		echo "document.modify.pnsa_string.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
			
		$output = preg_replace("/\r|\n/s", "\\n", $setting_pnsa_array[4]);
		echo "document.modify.sa_string.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	}
	
	//comments:	
	echo 'selectRadioButtons (document.modify.sort_comments, '.$fetch_content['sort_comments'].");\n";
	echo 'selectRadioButtons (document.modify.use_captcha, '.$fetch_content['use_captcha'].");\n";
	
	echo 'selectDropdownOption (document.modify.commenting, '.$fetch_content['commenting'].");\n";
	echo 'selectDropdownOption (document.modify.default_link, '.$fetch_content['default_link'].");\n";
	
	//Comments
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['comments_header']);
	echo "document.modify.comments_header.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
		
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['comments_loop']);
	echo "document.modify.comments_loop.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['comments_footer']);
	echo "document.modify.comments_footer.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	
	echo "\n\n// To save as a preset, change this line with your description:\n";
	
	if(isset($_GET['preset']) AND $_GET['preset'] == 1) {
		echo "document.getElementById('presetsdescription').innerHTML = 'Check changed fields';\n\n";
	} else {
		echo "document.getElementById('getfromdescription').innerHTML = 'Check changed fields';\n\n";
	}
	echo 'alert("Done");';
	
	?>