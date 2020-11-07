<?php
/**
 * WBCE CMS
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

// figure out if the form of the tool was send
// the form needs to have exactly the right field names for this to function.
// 'save_settings' set or 'action'set and == 'save'
$doSave = (isset($_POST['save_settings']) || isset($_POST['save_default']) || (isset($_POST['action']) && strtolower($_POST['action']) == 'save'));

// return url if something goes wrong , or back button is used
$returnToTools = ADMIN_URL . '/admintools/index.php';

$toolError = false;
$toolCheck = true;

// test for valid tool name
if (!preg_match('/^[a-z][a-z_\-0-9]{2,}$/i', $toolDir)) {
    $toolCheck = false;
}

// User has absolutely no permissions , possibly even not logged in :-)?
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
    $languagePath = $modulePath . 'languages/';
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
    // for example this offers opportunety to give back  files for download
    if ($noPage) {
        $admin = new admin('admintools', 'admintools', false);
    } else {
        $admin = new admin('admintools', 'admintools');
    }

    // show title if not function 'save' is requested
    if (!$doSave and !$noPage and !preg_match("/backend/", $module_function)) {
        $sTPL = '<h4><a href="{{URL}}" title="{{HEADING_TOOLS}}">{{HEADING_TOOLS}}</a>&nbsp;&raquo;&nbsp;{{MODULE_NAME}}</h4>';
        $aReplacements = array(
            'URL' => ADMIN_URL . '/admintools/index.php',
            'MODULE_NAME' => $admin->get_module_name($toolDir),
            'HEADING_TOOLS' => $HEADING['ADMINISTRATION_TOOLS']
        );
        echo replace_vars($sTPL, $aReplacements);
    }

    // Loading language files we start whith default EN
    if (is_file($languagePath . 'EN.php')) {
        require_once $languagePath . 'EN.php';
    }
    // Get actual language if exists
    if (is_file($languagePath . LANGUAGE . '.php')) {
        require_once $languagePath . LANGUAGE . '.php';
    }

    //Load actual tool
    echo '<div class="adminModuleWrapper ' . $toolDir . '">';
    require WB_PATH . '/modules/' . $toolDir . '/tool.php';
    echo '</div>';

    // Fetch the Buffer for later filtering
    $toolOutput = ob_get_clean();

    // FILTER for OPF DASHBOARD just for this module(tool)
    $file = WB_PATH . '/modules/outputfilter_dashboard/functions.php';
    if (file_exists($file)) {
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
