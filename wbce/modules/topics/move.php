<?php
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }
require('permissioncheck.php');

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: index.php");
	exit(0);	
} else {
	$id = $_GET['topic_id'];
	$id_field = 'topic_id';
	$table = TABLE_PREFIX.'mod_'.$tablename;
}

// Get Settings
$query_settings = $database->query("SELECT sort_topics FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '$section_id'");
$settings_fetch = $query_settings->fetchRow();
$sort_topics = $settings_fetch['sort_topics'];

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

// Create new order object an reorder
$order = new order($table, 'position', $id_field, 'section_id');

$back_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&hl='.$id.'#tpid'.$id;
if ($fredit == 1) {$back_url = WB_URL.'/modules/'.$mod_dir.'/modify_fe.php?page_id='.$page_id.'&section_id='.$section_id.'&fredit=1&hl='.$id.'#tpid'.$id;}


if ( $sort_topics == 0) {
	if ($_GET['move'] == 'up') { $ok = $order->move_down($id); }
	if ($_GET['move'] == 'down') { $ok = $order->move_up($id); }
}

if ( $sort_topics == -1) {
	if ($_GET['move'] == 'down') { $ok = $order->move_down($id); }
	if ($_GET['move'] == 'up') { $ok = $order->move_up($id); }
}



if($ok) {
	$admin->print_success($TEXT['SUCCESS'], $back_url);
} else {
	$admin->print_error($TEXT['ERROR'], $back_url);
}

// Print admin footer
if ($fredit == 1) {
	topics_frontendfooter();
} else {
	$admin->print_footer();
}

?>