<?php
isset($_POST['action']) or header( 'Location: ../../index.php' );

// json_respond array wich is sent back to the backend
$aRspnd = array();
$aRspnd['message'] = 'failed to change status';
$aRspnd['success'] = true;
#exit(json_encode($aRspnd));

if (!isset($_POST['idkey'])){
    trigger_error(1);
     exit(json_encode($aRspnd));
}
$iFilterID = $_POST['idkey'];

if(is_readable($sConfigFile = '../../../config.php')) {
    $aRspnd['message'] = 'L: '.__LINE__;
    require_once($sConfigFile);
} else {
    $aRspnd['message'] = 'L: '.__LINE__;
    exit(json_encode($aRspnd));
}

// check if user has permissions to access the outputfilter_dashboard module
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('admintools', 'admintools', false, false);
if (!($admin->is_authenticated() && $admin->get_permission('outputfilter_dashboard', 'module'))) {
    $aRspnd['message'] = 'insuficcient rights';
    exit(json_encode($aRspnd));
}

// Sanitize variables
$purpose = $admin->add_slashes($_POST['purpose']);
if ($purpose == "toggle_status") {
    require WB_PATH.'/modules/outputfilter_dashboard/functions_outputfilter.php';
    
    $iId = $admin->checkIDKEY($iFilterID, 0, 'POST', true);
    if($iId == 0){
        $aRspnd['message'] = 'ID '.$iId.' can\'t work';
        $aRspnd['success'] = false;
        exit(json_encode($aRspnd));
    }
    $iActive = (int) $_POST['action'];
    
    if(!opf_set_active($iId, $iActive)) {
        $aRspnd['success'] = false;
        $aRspnd['message'] = 'db query failed: '.$database->get_error();
        $aRspnd['message'] = 'id: '.$iId.' active: '.$iActive;
        $aRspnd['icon'] = 'cancel.gif';
        exit(json_encode($aRspnd));
    } else {
        $aRspnd['message'] = ('id: #'.$iId.' status: '.$iActive);
        $aRspnd['message'] .= '. Status was changed properly.';
        $aRspnd['success'] = true;
        exit(json_encode($aRspnd));
    }
    

}else{
    $aRspnd['message'] = 'wrong arguments "$action"';
    exit(json_encode($aRspnd));
}
