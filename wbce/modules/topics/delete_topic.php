<?php
require('../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$topic_id = $_GET['topic_id'];
}

$update_when_modified = true; // Tells script to update when this page was last updated
require('permissioncheck.php');

$modifyurl = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if ($fredit == 1) {$modifyurl = WB_URL.'/modules/'.$mod_dir.'/modify_fe.php?page_id='.$page_id.'&section_id='.$section_id.'&fredit=1';}

// Get post details
$query_details = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$topic_id'");
if($query_details->numRows() > 0) {
	$row = $query_details->fetchRow();	
} else {
	$admin->print_error($TEXT['NOT_FOUND'], $modifyurl);
}

// Unlink topic access file anyway
if(is_writable(WB_PATH.$topics_directory.$row['link'].PAGE_EXTENSION)) {
	unlink(WB_PATH.$topics_directory.$row['link'].PAGE_EXTENSION);
}

$hascontent = $row['hascontent'];
if ($hascontent < 2) {
	// Delete topic
	$database->query("DELETE FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id = '$topic_id' LIMIT 1");
	$database->query("DELETE FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id = '$topic_id'");
} else {
	//hide topic
	$hide_it = 0 - $section_id;
	$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename." SET section_id = '$hide_it' WHERE topic_id = '$topic_id'";
	$database->query($theq);
}

// Clean up ordering
require_once(WB_PATH.'/framework/class.order.php');
$order = new order(TABLE_PREFIX.'mod_'.$tablename, 'position', 'topic_id', 'section_id');
$order->clean($section_id); 

// Check if there is a db error, otherwise say successful
$modifyurl = ADMIN_URL.'/pages/modify.php?page_id='.$page_id;
if ($fredit == 1) {$modifyurl = WB_URL.'/modules/'.$mod_dir.'/modify_fe.php?page_id='.$page_id.'&section_id='.$section_id.'&fredit=1';}

if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&topic_id='.$topic_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], $modifyurl);
}

// Print admin footer
$admin->print_footer();

?>
