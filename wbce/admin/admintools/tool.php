<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require('../../config.php');


//Fetch toolname
$toolDir = (isset($_GET['tool']) && (trim($_GET['tool']) != '') ? trim($_GET['tool']) : '');

// figure out if the form of the tool was send
// the form needs to have exactly the right field names for this to function.
// 'save_settings' set or 'action'set and == 'save'
$doSave = (isset($_POST['save_settings'])|| isset($_POST['save_default']) || (isset($_POST['action']) && strtolower($_POST['action']  ) == 'save'));

// return url if something goes wrong , or back button is used
$returnToTools = ADMIN_URL.'/admintools/index.php';

$toolError= false;
$toolCheck = true;

// test for valid tool name
if(!preg_match('/^[a-z][a-z_\-0-9]{2,}$/i', $toolDir)) $toolCheck=false;

// Check if tool is installed
$sql = 'SELECT `name` FROM `'.TABLE_PREFIX.'addons` '.
       'WHERE `type`=\'module\' AND `function` LIKE \'%tool%\' '.
       'AND `directory`=\''.$database->escapeString($toolDir).'\' '.
       'AND `directory` NOT IN(\''.(implode("','",$_SESSION['MODULE_PERMISSIONS'])).'\') ';
if(!($toolName = $database->get_one($sql)))  $toolCheck=false;

// back button triggered, so go back.
if (isset ($_POST['admin_tools'])) {$toolCheck=false;}

// all ok go for display
if ($toolCheck) {

    // Defining some path for use in the actual admin tool
    $modulePath=WB_PATH."/modules/$toolDir/"; // we need this one later on too
    $languagePath=$modulePath.'languages/';
    $returnUrl= ADMIN_URL."/admintools/tool.php?tool=$toolDir";

    //include info,php for additional infos
    include($modulePath."/info.php" );

    // a few more helper vars (save values or reset to default settings)
    $saveSettings= (isset($_POST['save_settings'])|| (isset($_POST['action']) && strtolower($_POST['action']  ) == 'save'));
    $saveDefault = (isset($_POST['save_default']));
    $noPage=false;
    if (isset($_POST['no_page']) and $_POST['no_page']=="no_page") $noPage=true;
    if (isset($module_nopage) and $module_nopage)                  $noPage=true;

    // create admin-object but suppress headers if no page is set
    // for example this offers opportunety to give back  files for download
    if ($noPage) $admin = new admin('admintools', 'admintools',false);
    else         $admin = new admin('admintools', 'admintools');

    // show title if not function 'save' is requested
    if(!$doSave and !$noPage) {
        print '<h4><a href="'.ADMIN_URL.'/admintools/index.php" '.
              'title="'.$HEADING['ADMINISTRATION_TOOLS'].'">'.
               $HEADING['ADMINISTRATION_TOOLS'].'</a>'.
              '&nbsp;&raquo;&nbsp;'.$toolName.'</h4>'."\n";
    }

    // Loading language files we start whith default EN
    if(is_file($languagePath.'EN.php')) {
        require_once($languagePath.'EN.php');
    }
    // Get actual language if exists
    if(is_file($languagePath.LANGUAGE.'.php')) {
        require_once($languagePath.LANGUAGE.'.php');
    }

    //Load actual tool
    require(WB_PATH.'/modules/'.$toolDir.'/tool.php');

    // output footer if  we are not in no_page mode
    if (!$noPage) $admin->print_footer();

} else {
    // invalid module name requested, jump to index.php of admintools
    header('location: '.$returnToTools); exit;
}

// helper Function
function toolMsg ($setError=false, $returnUrl="" ){
    global $admin;
    global $MESSAGE;
    if ($returnUrl=="" )   $returnUrl=ADMIN_URL.'/admintools/index.php';

    // Check if there is error, otherwise say successful
    if($setError) {
        //3rd param = false =>no auto footer, no exit
        $admin->print_error($setError, $returnUrl,false);
    } else {
        $admin->print_success($MESSAGE['PAGES_SAVED'], $returnUrl);
    }
}
