<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;
require('permissioncheck.php');

// Get id
if(!isset($_GET['comment_id']) OR !is_numeric($_GET['comment_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$comment_id = $_GET['comment_id'];
}

// Get post id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$topic_id = $_GET['topic_id'];
}


// Update row
$database->query("DELETE FROM ".TABLE_PREFIX."mod_".$tablename."_comments  WHERE comment_id = '$comment_id' AND topic_id = '$topic_id'");

topics_update_comments_count ($topic_id);


// Check if there is a db error, otherwise say successful
$backlink =  WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$page_id.'&section_id='.$section_id.'&topic_id='.$topic_id.'&fredit='.$fredit.'#comments';
if($database->is_error()) {
	$admin->print_error($database->get_error(),$backlink);
} else {
	$admin->print_success($TEXT['SUCCESS'], $backlink);
}

if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>