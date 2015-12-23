<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

require('permissioncheck.php');
$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
require_once($mpath.'defaults/module_settings.default.php');
require_once($mpath.'module_settings.php');

// Get new order
$order = new order(TABLE_PREFIX.'mod_'.$tablename, 'position', 'topic_id', 'section_id');
$position = $order->get_new($section_id);

// Get default commenting
$query_settings = $database->query("SELECT commenting FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();
$commenting = $settings_fetch['commenting'];

// Insert new row into database
$t = 0; //= topic is just startet, begin time is when first saved    //topics_localtime();
$theuser = $admin->get_user_id();
$database->query("INSERT INTO ".TABLE_PREFIX."mod_".$tablename." (section_id,page_id,position,commenting,active,posted_by,authors,posted_first) VALUES ('$section_id','$page_id','$position','$commenting','$activedefault','$theuser',',$theuser,','$t')");

// Get the id
$topic_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Say that a new record has been added, then redirect to modify page
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fredit='.$fredit);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fredit='.$fredit);
}

// Print admin footer
if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>