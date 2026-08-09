<?php

// initialize json_respond array  (will be sent back)
$aJsonRespond = array();
$aJsonRespond['message'] = 'Nothing to do!';
$aJsonRespond['success'] = false;

// require config for initialisation.
require '../../config.php';

// test admin access for this
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Modules', 'modules_view', false, true); 
 
// check for the iRecordID parameter. Preventing warnings in the errorlogs
if(!isset($_POST['iRecordID'])) die(json_encode($aJsonRespond));

// get record_id to delete
$iRecordID      = intval($_POST['iRecordID']);

// extra validation
if (is_array($_POST['iRecordID'])) { $iRecordID=0; }

if($iRecordID > 0) {
	// excecute query
	$database->deleteRow('{TP}mod_miniform_data', 'message_id', $iRecordID);
	// test for errors
	if($database->hasError()) {
		$aJsonRespond['message'] = 'Deleting record failed!';
	} else {
		$aJsonRespond['message'] = 'Record deleted successfully!';
		$aJsonRespond['success'] = true;
	}
}
// return json data
die(json_encode($aJsonRespond));

