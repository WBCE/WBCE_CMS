<?php

/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.6.3
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, Christian M. Stefan (Stefek) <stefek@designthings.de>, Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010-2023 Christian M. Stefan (Stefek), 2016-2023 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            https://addons.wbce.org/pages/addons.php?do=item&item=53
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU General Public License, Version 3
 * @platform        WBCE 1.x
 * @requirements    PHP 7.4 - 8.2
 *
 * This file is part of OutputFilter-Dashboard, a module for WBCE and Website Baker CMS.
 *
 * OutputFilter-Dashboard is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OutputFilter-Dashboard is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OutputFilter-Dashboard. If not, see <http://www.gnu.org/licenses/>.
 *
 **/
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
