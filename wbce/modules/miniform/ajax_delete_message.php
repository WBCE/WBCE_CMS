<?php

// initialize json_respond array  (will be sent back)
$aJsonRespond = array();
$aJsonRespond['message'] = 'ajax operation failed';
$aJsonRespond['success'] = FALSE;

    if(!isset($_POST['action']) )
    {
        $aJsonRespond['message'] = '"action" was not set';
        exit(json_encode($aJsonRespond));
    }


        // require config for Core Constants
        require '../../config.php';
        // retrieve Data from ajax data string
        $sDbRecordTable = TABLE_PREFIX."mod_".addslashes($_POST['DB_RECORD_TABLE']);
        $sDbColumn      = addslashes($_POST['DB_COLUMN']);
        $iRecordID      = addslashes($_POST['iRecordID']);
        $sModuleDIR     = addslashes($_POST['MODULE']);

        require_once(WB_PATH.'/framework/class.admin.php');
        $admin = new admin('Modules', 'module_view', FALSE, FALSE);
        if(!is_numeric($iRecordID)) {
            if(method_exists( $admin, 'checkIDKEY' ))
               $iRecordID = $admin->checkIDKEY($iRecordID,-1,'key',true);
               else $iRecordID = -1;
        }



    switch ($_POST['purpose'])
    {
        
        case 'delete_record':
            // Check the Parameters
            if(isset($_POST['action']) && $_POST['action'] == 'delete')    {

                $query = "DELETE FROM `".$sDbRecordTable."` WHERE `".$sDbColumn."` = ".$iRecordID." LIMIT 1";
                $database->query($query);
                if($database->is_error())
                {
                    $aJsonRespond['message'] = 'db query failed: '.$database->get_error();
                    exit(json_encode($aJsonRespond));
                } else {
                    $aJsonRespond['message'] = 'Record deleted successfully!';
                }
				
                // Clean up ordering after deletion
               // require(WB_PATH.'/framework/class.order.php');
              //  $order = new order($sDbRecordTable, 'position', $sDbColumn, 'section_id');
               // $order->clean($_POST['iSectionID']);
            }
            else{
                $aJsonRespond['message'] = "can't delete from list";
                exit(json_encode($aJsonRespond));
            }
        break;
    }



// If the script is still running, set success to true
$aJsonRespond['success'] = true;
// and echo the json_respond to the ajax function
exit(json_encode($aJsonRespond));

