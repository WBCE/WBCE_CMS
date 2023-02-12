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
