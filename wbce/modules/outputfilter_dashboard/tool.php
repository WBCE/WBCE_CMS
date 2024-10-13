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

// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));

// Authorization: check if user is allowed to use Admin-Tools
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

// set module vars
$ModDir  = basename(__DIR__);
$ModUrl  = WB_URL."/modules/".$ModDir;
$ToolUrl = $returnUrl;

$L = $LANG['MOD_OPF']; // rename long hand array name to $L for easy access in templates

// load outputfilter-functions
require_once __DIR__ . "/functions.php";

$ftan = $admin->getFTAN(); // set Form TransAction Number

// remove all "filters" with no name
$database->query("DELETE FROM `{TP_OPFD}` WHERE `name` = ''");

$aCssFiles = [
    get_url_from_path($admin->correct_theme_source('../css/ACPI_backend.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_content.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_buttons.css'))
];
I::insertCssFile($aCssFiles, 'HEAD TOP+');
// start Twig object
$sTwigPath = __DIR__ . '/twig/';
$oTwig = getTwig($sTwigPath);
$sImageUrl = get_url_from_path(__DIR__).'/images';
$oTwig->addGlobal('IMAGE_URL', $sImageUrl);
I::insertJsCode(
    'var IMAGE_URL = "'.$sImageUrl.'";
     var ADDON_URL = "'.$ModUrl.'";',
    'HEAD TOP+'
);

// start collecting array to hand over to Twig Template
$aToTwig = [];

// check the FTAN - $doSave is set by admin/admintools/tool.php
if ($doSave){
    global $MESSAGE;
       if ( !$admin->checkFTAN()  ) {
          if ((ob_get_contents()=="") && (!headers_sent())
              && (!(class_exists ("Tool") && defined('WBCE_VERSION'))) ){
             $admin->print_header();
          }
          $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $ToolUrl);
          $admin->print_footer();
          exit();
       }
}

// fetch values from $_GET[]
$id         = opf_fetch_get( 'id',      NULL,  'int');
$dir        = opf_fetch_get( 'dir',     NULL,  'string');
$active     = opf_fetch_get( 'active',  NULL,  'int');
$delete     = opf_fetch_get( 'delete',  FALSE, 'exists');
$convert    = opf_fetch_get( 'convert', FALSE, 'exists');
$css_save   = opf_fetch_get( 'css_save',FALSE, 'exists');
$export     = opf_fetch_get( 'export',  FALSE, 'exists');
$filter     = opf_fetch_get( 'filter',  NULL,  'string');

// fetch values from $_POST[]
$filtername = opf_fetch_post( 'name',     NULL, 'string');
$func       = opf_fetch_post( 'func',     NULL, 'string');
$funcname   = opf_fetch_post( 'funcname', NULL, 'string');



// check file upload
$upload_message = '';
$upload_ok      = ''; // both will be set in upload.php
if (
        isset($_FILES['filterplugin']) && $doSave
        && is_uploaded_file($_FILES['filterplugin']['tmp_name'])
    ) {
    include __DIR__.'/upload.php';
}
// export a filter
$export_message = $export_url = ''; // both will be set in export.php
$export_ok = FALSE;
if ($export && $id ) {
    $res = include __DIR__.'/export.php';
    if ($res) $export_url = $res;
}
$export_success = ($export_ok == FALSE)
        ? $L['TXT_EXPORT_FAILED']
        : $L['TXT_EXPORT_SUCCESS'];



// move up or down (changed to Ajax drag&drop)
//if ($id && $dir == 'up' )     opf_move_up_one($id);
//if ($id && $dir == 'down' )   opf_move_down_one($id);

// toggle active (changed to Ajax)
//if ($id && $active !== NULL ) opf_set_active($id, $active);

// delete userfunc-filter (changed to Ajax)
//if ($id && $delete )          opf_unregister_filter($id);


$convert_message = ''; // will be set in convert.php
$convert_ok = FALSE;
// convert inline filter to plugin
if ($id && $convert ) {
    $res = include __DIR__.'/convert.php';
    if (!$res) $export_message = $convert_message;
    $export_success = ($export_ok==FALSE)
        ? $L['TXT_CONVERT_FAILED']
        : $L['TXT_CONVERT_SUCCESS'];
}

// save filter

if (($filtername || $funcname) && $doSave) {
    $tmp = opf_save();
    if (is_numeric($tmp)){
        $id = $tmp; // get the $id    
    }
    // in case we come from add/edit check if user pressed "save" instead of "save and exit"
    if (opf_fetch_post( 'submit_return', FALSE, 'exists'))
        $force_edit = TRUE;
}

// save edited css file
if ($css_save && $doSave) {
    if (!empty($_POST)){
        $tmp = opf_css_save();
        if (is_numeric($tmp))
            $id = $tmp; // overwrite $id
    }
    // in case we come from add/edit check if user pressed "save" instead of "save and exit"
    if (opf_fetch_post( 'submit_return', FALSE, 'exists')){
        $force_csspath = opf_fetch_post( 'csspath', NULL, 'string');
    }
}

///////////////////////////////////////////////////////////////////////////
//  Now, determine what to do:
//  add filter, edit filter, edit css, open help, show dashboard
///////////////////////////////////////////////////////////////////////////

$add     = opf_fetch_get( 'add',     FALSE, 'exists' );
$edit    = opf_fetch_get( 'edit',    FALSE, 'exists' );
$csspath = opf_fetch_get( 'csspath', NULL,  'string' );

if (isset($force_edit))    $edit = TRUE;
if (isset($force_csspath)) $csspath = $force_csspath;

if ($add && $doSave )     { require __DIR__ . '/tool_add_filter.php'; }
elseif ($id && $edit )    { require __DIR__ . '/tool_edit_filter.php'; }
elseif ($id && $csspath ) { require __DIR__ . '/tool_edit_css.php'; }
else {
    require __DIR__ . '/tool_dashboard.php';
}
