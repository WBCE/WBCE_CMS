<?php
$aToJson = array();
$aToJson['success'] = false;
$aToJson['message'] = 'failure';
if(!empty($_POST["message_id"])){
	require_once '../../config.php';

	// module directory
	$mod_dir = 'miniform';
	// Include WB admin wrapper script
	require_once(WB_PATH.'/framework/class.admin.php');
    $admin = new admin('Pages', 'pages_modify', false, false);
    if (!($admin->is_authenticated() && $admin->get_permission($mod_dir, 'module'))) {
        $aToJson['message'] = 'insuficcient rights';
        exit(json_encode($aToJson));
    }

       // Get records from the database
	$section_id  = (int) $_POST['section_id'];
	$msg_id      = (int) $_POST['message_id'];
    $number_load = (int) $_POST['load'];



   	$rows = $database->fetchAll(
		"SELECT * FROM `{TP}mod_miniform_data`
			WHERE `section_id` = ? AND `message_id` < ?
			ORDER BY `message_id` DESC
			LIMIT ?",
		[$section_id, $msg_id, $number_load]
	);
	$number_loaded = count($rows);

	$aToJson['data'] = array();
    if($number_loaded > 0){

		$aToJson['success'] = true;
        foreach($rows as $msg){
			$msg_id = $msg['message_id'];
			$aToJson['message'] = $msg_id ;
			$aToJson['data'][$msg_id]['message_id'] = $msg_id;
			$aToJson['data'][$msg_id]['data'] = $msg['data'];
			$aToJson['data'][$msg_id]['submitted_when'] = date(DATE_FORMAT.' - '.TIME_FORMAT,$msg['submitted_when']+TIMEZONE);
		}
		rsort($aToJson['data']);
		// Count all records except already displayed
		$totalRowCount = count($database->fetchAll(
			"SELECT `message_id`
				FROM `{TP}mod_miniform_data`
				WHERE `section_id` = ? AND `message_id` < ?
				ORDER BY message_id DESC",
			[$section_id, $msg_id]
		));

		if($totalRowCount > 0){
			$next_amount = $totalRowCount > $number_load ? $number_load : $totalRowCount;
			$aToJson['data']['load_after'] = $msg_id;
			$aToJson['data']['next_amount']  = $next_amount;
		}
    }
}

exit(json_encode($aToJson));
