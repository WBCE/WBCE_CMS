<?php
if(!isset($_POST['purpose']) || $_POST['purpose'] != 'delete_row'){
    header( 'Location: ../../index.php' );
}

// json_respond array which will be sent back to the javascript
$aRspnd = [
    'message' => 'failed to change status',
    'success' => false
];

if (!isset($_POST['idkey'])){
     exit(json_encode($aRspnd));
}

if(is_readable($sConfigFile = '../../../config.php')) {
    require_once($sConfigFile);
} else {
    $aRspnd ['message'] = 'config.php fail';
    exit(json_encode($aRspnd));
}

// check if user has permissions to access the outputfilter_dashboard module
require WB_PATH.'/framework/class.admin.php';
$admin = new admin('admintools', 'admintools', false, false);
if (!($admin->is_authenticated() && $admin->get_permission('outputfilter_dashboard', 'module'))) {
    $aRspnd ['message'] = 'insuficcient privileges';
    exit(json_encode($aRspnd));
}
require WB_PATH.'/modules/outputfilter_dashboard/functions_outputfilter.php';

// Sanitize variables

$iFilterIDKEY = $_POST['idkey'];
$iId = $admin->checkIDKEY($iFilterIDKEY, 0, 'POST', true);
if($iId == 0){
    $aRspnd ['message'] = 'ID '.$iId.' can\'t work';
    exit(json_encode($aRspnd));
}

if(!opf_unregister_filter($iId)) {
    // we use opf_unregister_filter because it removes the filter 
    //   and all its files too, when necessary
    $aRspnd ['message'] = 'db query failed: '.$database->get_error();
    exit(json_encode($aRspnd));
    
} else {
    // query worked
    $aRspnd ['message'] = 'id: #'.$iId.' status: deleted.';
    $aRspnd ['success'] = true;
    exit(json_encode($aRspnd));
}