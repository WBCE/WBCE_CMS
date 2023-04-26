<?php

// initialize json_respond array  (will be sent back)
$aJsonRespond = array();
$aJsonRespond['message'] = 'Nothing to do!';
$aJsonRespond['success'] = false;

// require config for initialisation.
require '../../config.php';

// test admin access for this
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Modules', 'module_view', FALSE, FALSE);

// check for the iRecordID parameter. Preventing warnings in the errorlogs
if(!isset($_POST['iRecordID'])) die(json_encode($aJsonRespond));

// get record_id to delete
$iRecordID      = intval($_POST['iRecordID']);
if($iRecordID > 0) {
	// build query
	$query = "DELETE FROM `".TABLE_PREFIX."mod_miniform_data` WHERE `message_id` = '".$iRecordID."' LIMIT 1";
	// excecute query
	$database->query($query);
	// test for errors
	if($database->is_error()) {
		$aJsonRespond['message'] = 'Deleting record failed!';
	} else {
		$aJsonRespond['message'] = 'Record deleted successfully!';
		$aJsonRespond['success'] = true;
	}
}
// return json data
die(json_encode($aJsonRespond));

