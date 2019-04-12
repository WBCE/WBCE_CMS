<?php
/**
 * WBCE CMS AdminTool: Tool Account Settings
 * 
 * @platform    WBCE CMS 1.3.2 and higher
 * @package     modules/UserBase
 * @author      Christian M. Stefan <stefek@designthings.de>
 * @copyright   Christian M. Stefan
 * @license     see LICENSE.md of this package
 */
 
// prevent this file from being accessed directly
defined('WB_PATH') or exit("insufficient privileges" . __FILE__);

// check if user is allowed to use admin-tools (to prevent this file 
// to be called by an unauthorized user e.g. from a code-section)
if($admin->get_permission('admintools') == false) exit("insuficient privileges");

$sPluginsURL = get_url_from_path(__DIR__).'/js';

I::insertCssFile($sPluginsURL."/jquery.tablesorter/theme.wbEasy.css",                    "HEAD BTM-");
I::insertJsFile( $sPluginsURL."/jquery.tablesorter/jquery.tablesorter.js",               "HEAD BTM-");
I::insertJsFile( $sPluginsURL."/jquery.tablesorter/jquery.tablesorter.widgets.js",       "HEAD BTM-");
I::insertJsFile( $sPluginsURL."/jquery.tablesorter/tablesorter_accout_tool_settings.js", "BODY BTM-");
$sToCss = "
    .tablesorter thead .disabled {
        display:none;
    }
";
I::insertCssCode($sToCss, 'HEAD BTM-', 'tablesorter');

// show all users?
$aUsers = $oAccounts->get_users_overview();
#debug_dump($aUsers);
if(!empty($aUsers)){
    $field_row_framer_none = true; // global var, used in field_row_framer() function	
    
    $aToTwig = array(
        'TABS'                => $aTabs,
        'CAN_MODIFY_ACCOUNTS' => (in_array('users_modify', $_SESSION['SYSTEM_PERMISSIONS'])),
        'JS_ONCLICK'          => ' onclick="javascript: setTimeout(function(){location.reload();}, 1000);return true;" ',
        'USERLIST'            => $oAccounts->get_users_overview(),
        'GET'                 => $_GET
    );    
    
    // prepare Twig Template
    $oTwig = getTwig(__DIR__ . '/theme/');
    $oTemplate = $oTwig->loadTemplate('tool_overview.twig');
    $oTemplate->display($aToTwig);

} 
