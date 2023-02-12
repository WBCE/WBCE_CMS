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
/*      Drag'N'Drop Position
 *      This file is based on the mechanism used in Module "mpform"
**/

$admin_header = FALSE;

require('../../../config.php');

// include module.functions.php
include_once(WB_PATH . '/framework/module.functions.php');

// load outputfilter-functions
require_once(dirname(dirname(__FILE__))."/functions.php");


$aJsonRespond = array();
$aJsonRespond['success'] = false;
$aJsonRespond['message'] = '';
$aJsonRespond['icon'] = '';

if(!isset($_POST['action']) || !isset($_POST['id']) )
{
    $aJsonRespond['message'] = 'one of the parameters does not exist';
    exit(json_encode($aJsonRespond));
}
 else
{
    $aRows = $_POST['id'];
    require_once('../../../config.php');
    // check if user has permissions to access the outputfilter_dashboard module
    require_once(WB_PATH.'/framework/class.admin.php');
    $admin = new admin('admintools', 'admintools', false, false);
    if (!($admin->is_authenticated() && $admin->get_permission('outputfilter_dashboard', 'module'))) {
        $aJsonRespond['message'] = 'insuficcient rights';
        exit(json_encode($aJsonRespond));
    }

    // Sanitize variables
    $action = $admin->add_slashes($_POST['action']);
    if ($action == "updatePosition")
    {
        $i = array();
        $i_keys = array();
        foreach(opf_get_types() as $type => $typename){
            $i[$type]=1;
            $i_keys[]=$type;
        }

        foreach ($aRows as $recID) {
            $id = $admin->checkIDKEY($recID,0,'key',true);
            $filter = opf_get_data($id);
            $type = $filter['type'];
            // now we sanitize array
            $qstring ="UPDATE `".TABLE_PREFIX."mod_outputfilter_dashboard`"
               . " SET `position` = '".$i[$type]."'"
               . " WHERE `id` = ".intval($id)." ";
            if(!opf_db_run_query($qstring)) {
                $aJsonRespond['success'] = false;
                $aJsonRespond['message'] = 'db query failed: '.opf_db_get_error();
                $aJsonRespond['icon'] = 'cancel.gif';
                exit(json_encode($aJsonRespond));
            }
            $i[$type]++;
        }
    }else{
        $aJsonRespond['message'] = 'wrong arguments "$action"';
        exit(json_encode($aJsonRespond));
    }

    $aJsonRespond['icon'] = 'dialog-close.gif';
    $aJsonRespond['message'] = 'seems everything is fine';
    $aJsonRespond['success'] = true;
    exit(json_encode($aJsonRespond));
}

