<?php
/**
 * WBCE CMS — AdminTool Runner
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require '../../config.php';

//Fetch toolname
$toolDir = (isset($_GET['tool']) && (trim($_GET['tool']) != '') ? trim($_GET['tool']) : '');

// figure out if the form of the tool was sent
// the form needs to have exactly the correct field names for this to work.
// 'save_settings' set or 'action'set and == 'save'
$doSave = (isset($_POST['save_settings']) || isset($_POST['save_default']) || (isset($_POST['action']) && strtolower($_POST['action']) == 'save'));

// return url if something goes wrong , or back button is used
$returnToTools = ADMIN_URL . '/admintools/index.php';

// Check if the tool's directory is present on server
if(is_dir(WB_PATH.'/modules/'.$toolDir) == false){
    $admin = new Admin('admintools', 'admintools', true);
    $admin->print_error("<tt>modules/<b>{$toolDir}</b>/</tt><br>".$MESSAGE['GENERIC_MODULE_VERSION_ERROR'], $returnToTools);
    exit;
}

$toolError = false;
$toolCheck = true;

// test for valid tool name
if (!preg_match('/^[a-z][a-z_\-0-9]{2,}$/i', $toolDir)) {
    $toolCheck = false;
}

// User has absolutely no permissions
if (!isset($_SESSION['MODULE_PERMISSIONS'])) {
    $toolCheck = false;
}

// back button triggered, so go back.
if (isset($_POST['admin_tools'])) {
    $toolCheck = false;
}

// all ok go for display
if ($toolCheck) {

    // Defining some path for use in the actual admin tool
    defined('ADMIN_TOOL_DIR') or define('ADMIN_TOOL_DIR', $toolDir);
    $modulePath = WB_PATH . "/modules/$toolDir/"; // we need this one later on too
    $returnUrl = ADMIN_URL . "/admintools/tool.php?tool=$toolDir";
    //include info,php for additional infos
    include $modulePath . "/info.php";

    // a few more helper vars (save values or reset to default settings)
    $saveSettings = (isset($_POST['save_settings']) || (isset($_POST['action']) && strtolower($_POST['action']) == 'save'));
    $saveDefault = (isset($_POST['save_default']));
    $noPage = false;
    if (isset($_POST['no_page']) and $_POST['no_page'] == "no_page") {
        $noPage = true;
    }
    if (isset($_GET['no_page']) and $_GET['no_page'] == "no_page") {
        $noPage = true;
    }
    if (isset($module_nopage) and $module_nopage) {
        $noPage = true;
    }

    // create admin-object but suppress headers if no page is set
    // for example this offers opportunity to give back  files for download
    $doWrapper = ($noPage ? false : true);
    $admin = new Admin('admintools', 'admintools', $doWrapper);
    
    // check if user has access to this specific AdminTool
    if($admin->isAdmin() == false){  
        if(!in_array($toolDir.'_tool', $admin->get_session('MODULE_PERMISSIONS'))){
            $admin->print_error($MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES'].'!', $doWrapper);
            exit();
        }
    } 
    
    $module_name = $admin->get_module_name($toolDir);               // get translated $module_name ...
    $module_description = $admin->get_module_description($toolDir); // and $module_description
    // Load language files
    Lang::loadLanguage($modulePath);

    // Capture tool output in its own buffer so it can be passed to the Twig wrapper
    ob_start();
    require WB_PATH . '/modules/' . $toolDir . '/tool.php';
    $toolContent = ob_get_clean();

    // Render via Twig wrapper (breadcrumb + div) or plain fallback for save/backend/no_page
    if (!$doSave && !$noPage && !preg_match('/backend/', $module_function)) {
        $admin->getThemeFile('admintool_wrapper.twig', [
            'TOOL_DIR'    => $toolDir,
            'MODULE_NAME' => $module_name,
            'BACK_URL'    => ADMIN_URL . '/admintools/index.php',
            'BACK_LABEL'  => $HEADING['ADMINISTRATION_TOOLS'],
            'CONTENT'     => $toolContent,
        ]);
    } else {
        echo '<div class="adminModuleWrapper ' . $toolDir . '">' . $toolContent . '</div>';
    }

    // Fetch the Buffer for later filtering
    $toolOutput = ob_get_clean();

    // FILTER for OPF DASHBOARD just for this module(tool)    
    if (file_exists($file = WB_PATH . '/modules/outputfilter_dashboard/functions.php')) {
        include_once $file;
    }
    if (function_exists('opf_controller')) {
        $toolOutput = opf_controller('backend', $toolOutput, $toolDir);
    }
    echo $toolOutput;

    // output footer if  we are not in no_page mode
    if (!$noPage) {
        $admin->print_footer();
    } else {
        // Fetch the Buffer for later filtering
        $fullOutput = ob_get_clean();

        // FILTER for OPF DASHBOARD for whole page
        if (function_exists('opf_controller')) {
            $fullOutput = opf_controller('backend', $fullOutput);
        }
        echo $fullOutput;
    }
} else {
    // invalid module name requested, jump to index.php of admintools
    header('location: ' . $returnToTools);
    exit;
}

// helper Function
function toolMsg($setError = false, $returnUrl = "")
{
    global $admin, $MESSAGE;
    if ($returnUrl == "") {
        $returnUrl = ADMIN_URL . '/admintools/index.php';
    }

    // Check if there is error, otherwise say successful
    if ($setError) {
        //3rd param = false =>no auto footer, no exit
        $admin->print_error($setError, $returnUrl, false);
    } else {
        $admin->print_success($MESSAGE['PAGES_SAVED'], $returnUrl);
    }
}