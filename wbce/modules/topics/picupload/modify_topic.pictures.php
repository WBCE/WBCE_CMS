<?php
require_once(dirname(__FILE__).'/../../../config.php');
if(!defined('WB_PATH')) { 	die("sorry, no access..");}

// Get id
//Hier ist topic_id nicht nötig
if(isset($_REQUEST['section_id']) AND is_numeric($_REQUEST['section_id']) AND isset($_REQUEST['page_id']) AND is_numeric($_REQUEST['page_id'])  ) {
	$section_id = (int) $_REQUEST['section_id']; 
	$page_id = (int) $_REQUEST['page_id']; 
	//$topic_id = (int) $_REQUEST['topic_id']; 
} else {
   die('missing parameters');
}
//$p = "page_id=$page_id&amp;section_id=$section_id&amp;topic_id=$topic_id";

//include('getbasics.inc.php');


$theauto_header = false;
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', $theauto_header, TRUE);
if(!$admin->is_authenticated()) { die(); }

//Das muss hier so gemacht werden:
require_once('../info.php');
$mod_dir = $module_directory;
$tablename = $module_directory;


// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/'.$mod_dir.'/languages/'.LANGUAGE.'.php');
	}
}

$helppage = 'help.php?section_id='.$section_id.'&page_id='.$page_id;

// Get Settings

$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();
$picture_dir = ''.$settings_fetch['picture_dir'];
echo '';




if ($picture_dir != '') {
	
	
	$file_dir= WB_PATH.$picture_dir;
	//$picture_dir = str_replace(WB_PATH,'', $picture_dir);		
	$check_pic_dir=is_dir("$file_dir");
	$allpreviews = '';
	if ($check_pic_dir=='1') {
		$pic_dir=opendir($file_dir);
	
		while ($file=readdir($pic_dir)) {
			if ($file != "." && $file != "..") {			
				if (preg_match('/.+\.(jpeg|jpg|gif|png|JPG|GIF|PNG)$/',$file)) {
					$thepreview = '<div class="topicpic_preview"><a href="javascript:choosethispicture(\''.$file.'\');"><img src="'.WB_URL.$picture_dir.'/'.$file.'" alt="" title="'.$file.'" /></a></div> 
					';
					$allpreviews = $thepreview.$allpreviews; //reversed sorting						
				} 
			}
		}
		$thepreview = ''; //'<div class="topicpic_preview"><a href="javascript:showuploader(0);"><img src="img/upload.png" alt="upload" /></a></div> ';
		$allpreviews = $thepreview.$allpreviews; //reversed sorting
		
		echo $allpreviews;			
	} else {
		echo '<p>'.$MOD_TOPICS['NO_PICTUREDIR_FOUND'].'<br/><b>'.$picture_dir.'</b></p><a href="'.$helppage.'#pictures" target="_blank" class="modifytopichelp">'.$MOD_TOPICS['SEE_HELP_FILE'].'</a>';
	}

} else { 
	echo '<p>'.$MOD_TOPICS['NO_PICTUREDIR'].'<br/><b>'.$picture_dir.'</b></p><a href="'.$helppage.'#pictures" target="_blank" class="modifytopichelp">'.$MOD_TOPICS['SEE_HELP_FILE'].'</a>'; 
}
	

?>