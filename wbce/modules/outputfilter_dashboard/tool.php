<?php
/**
 *
 * @category        tool
 * @package         Outputfilter Dashboard
 * @version         1.7.0
 * @authors         Thomas "thorn" Hornik <thorn@nettest.thekk.de>, 
 *                   Christian M. Stefan  (https://www.wbEasy.de), 
 *                   Martin Hecht (mrbaseman) <mrbaseman@gmx.de>
 * @copyright       (c) 2009,2010 Thomas "thorn" Hornik, 2010-2023 Christian M. Stefan, 2016-2023 Martin Hecht (mrbaseman)
 * @link            https://github.com/mrbaseman/outputfilter_dashboard
 * @link            https://addons.wbce.org/pages/addons.php?do=item&item=53
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU General Public License, Version 3
 * @platform        WBCE 1.7.x
 * @requirements    PHP 8.1
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

// Bridge the legacy $LANG['MOD_OPF'] array into the Lang registry so that
// Twig templates can resolve L_('L:KEY') for all translation keys.
if (class_exists('Lang')) {
    Lang::register('L', $LANG['MOD_OPF']);
}

// $L is the short alias the PHP side of this tool reads (this file's export
// toast, tool_dashboard.php's filter-type tooltips). Nothing ever assigned it
// -- not the language files, not this file -- so every $L['…'] read returned
// an empty string and raised an undefined-variable warning. The included
// tool_*.php files run in this scope, so assigning it here covers them too.
$L = $LANG['MOD_OPF'];
// load outputfilter-functions
require_once __DIR__ . "/functions.php";

$ftan = $admin->getFTAN(); // set Form TransAction Number

// remove all "filters" with no name
$database->deleteRow('{TP_OPFD}', 'name', '');

$aCssFiles = [
    get_url_from_path($admin->correct_theme_source('../css/ACPI_backend.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_content.css')),
    get_url_from_path($admin->correct_theme_source('../css/ACPI_buttons.css'))
];
I::insertCssFile($aCssFiles, 'HEAD TOP+');
// start Twig object
$sTwigPath = __DIR__ . '/twig/';
$oTwig = getTwig($sTwigPath);
$sImageUrl = get_url_from_path(__DIR__).'/assets/images';
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
    if ($upload_message !== '') {
        (new Alerts())->sessionToast($upload_message, $upload_ok ? 'success' : 'error');
    }
}

// export a filter
$export_message = $export_url = ''; // both will be set in export.php
$export_ok = FALSE;
if ($export && $id ) {
    $res = include __DIR__.'/export.php';
    if ($res) $export_url = $res;
}

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
}

// Export/convert feedback, same $export_message/$export_ok pair either path
// sets (see export.php/convert.php). A successful export gets a download
// link appended to the toast; a successful convert never sets
// $export_message at all (matches the previous popup's exact behaviour --
// convert.php only sets it on failure), so nothing is shown for that case.
if ($export_message !== '') {
    $toastMsg = $export_message;
    if ($export_ok && $export_url) {
        $toastMsg .= ' <a href="' . htmlspecialchars($export_url) . '">' . $L['TXT_DOWNLOAD'] . '</a>';
    }
    (new Alerts())->sessionToast($toastMsg, $export_ok ? 'success' : 'error');
}

// save filter

if (($filtername || $funcname) && $doSave) {
    $tmp = opf_save();
    $saveFailed = !is_numeric($tmp);
    if (!$saveFailed){
        $id = $tmp; // get the $id
        (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
    } else {
        // opf_save() failed -- $id above came from opf_fetch_get('id', ...),
        // but TOOL_URI (the edit form's action) carries no query string, so
        // $id is only ever posted in the form body (see
        // tool_add_edit_filter.twig's hidden "id" field). On success $id
        // gets overwritten from opf_save()'s return value above; on failure
        // it never does, so it stays NULL and the dispatch below
        // ("elseif ($id && $edit)") falls through to the dashboard list
        // instead of staying on this filter's edit page. Recover it from
        // POST so a failed save keeps editing the same filter.
        $postedId = opf_fetch_post( 'id', NULL, 'int');
        if ($postedId) $id = $postedId;

        // CodeVet rejection (opf_register_filter() populated this global --
        // see its docblock) gets the specific message + the unsaved edit
        // preserved for tool_edit_filter.php to re-display, same pattern as
        // modules/droplets/save_droplet.php's $_SESSION['codevet_draft'].
        // Any other kind of save failure (missing name/funcname etc.) still
        // just gets the generic toast -- there's no specific reason to show
        // beyond what the form itself already indicates.
        $codevetError = $GLOBALS['opf_codevet_error'] ?? null;
        if ($codevetError && $id) {
            $_SESSION['codevet_draft']['opf_' . $id] = $_POST + ['line' => $codevetError['line']];
            $label = (class_exists('Lang') && Lang::has('L', 'TXT_INVALIDCODE'))
                ? Lang::get('L', 'TXT_INVALIDCODE')
                : 'Invalid PHP code';
            (new Alerts())->sessionToast($label . ': ' . $codevetError['message'], 'error');
        } else {
            (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
        }
    }
    // Stay on the edit form if the user pressed "save" instead of "save and
    // exit" (submit_return) -- OR the save failed outright, no matter which
    // button was pressed. A failed save (broken/unsafe code, or any other
    // validation issue) must be seen and fixed before "Save & Close" is
    // allowed to actually close -- otherwise clicking it silently discards
    // the failure and lands in the list as if nothing were wrong.
    if (opf_fetch_post( 'submit_return', FALSE, 'exists') || $saveFailed)
        $force_edit = TRUE;
}

// save edited css file
if ($css_save && $doSave) {
    $cssSaveFailed = false;
    if (!empty($_POST)){
        $tmp = opf_css_save();
        if (is_numeric($tmp)) {
            $id = $tmp; // overwrite $id
            (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
        } else {
            $cssSaveFailed = true;
            // Same $id recovery as the opf_save() branch above -- see comment there.
            $postedId = opf_fetch_post( 'id', NULL, 'int');
            if ($postedId) $id = $postedId;
            (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_FAILED', 'error');
        }
    }
    // Stay on the css edit page if "save" was pressed, OR the save failed
    // outright -- same reasoning as the filter-code save block above.
    if (opf_fetch_post( 'submit_return', FALSE, 'exists') || $cssSaveFailed){
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
