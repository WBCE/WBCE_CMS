<?php
/**
 * WBCE CMS AdminTool: tool_account_settings
 * 
 * @platform    WBCE CMS 1.3.2 and higher
 * @package     modules/tool_account_settings
 * @author      Christian M. Stefan <stefek@designthings.de>
 * @copyright   Christian M. Stefan
 * @license     see LICENSE.md of this package
 */
 
// prevent this file from being accessed directly
defined('WB_PATH') or exit("insufficient privileges" . __FILE__);

// check if user is allowed to use admin-tools (to prevent this file 
// to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) exit("insuficient privileges");

$oAccounts = new Accounts();

// Load Language Files 
foreach ($oAccounts->getLanguageFiles() as $sLangFile) require_once $sLangFile;

// Load Language files
//if(LANGUAGE_LOADED) {
//    require_once __DIR__ .'/languages/EN.php';	
//    $sLangFile = __DIR__ .'/languages/'.LANGUAGE.'.php';
//    if(LANGUAGE != 'EN' && is_readable($sLangFile)) {
//        require_once $sLangFile;
//    }
//}

// load CSS Files if Backend Theme not ACPI Ready
$sAcpiCheckfile = WB_PATH.'/templates/'.DEFAULT_THEME.'/ACPI_READY';
if(!is_file($sAcpiCheckfile)){
    I::insertCssFile(get_url_from_path(__DIR__)."/css/ACPI_content.css");
    I::insertCssFile(get_url_from_path(__DIR__)."/css/ACPI_backend.css");
    I::insertCssFile(get_url_from_path(__DIR__)."/css/ACPI_buttons.css");
}

defined('UB_TOOL_URI') or define('UB_TOOL_URI', ADMIN_URL.'/admintools/tool.php?tool=tool_account_settings');
$toolUrl = UB_TOOL_URI;

$sJsOnSubmit = 'onsubmit="javascript: setTimeout(function(){location.reload();}, 1000);return true;"';

$sPos = isset($_GET["pos"]) ? $_GET["pos"] : 'tool_overview';

switch ($sPos) {
    case 'config':   $sFile = 'tool_config';   break;	
    case 'overview': 
    default:         $sFile = 'tool_overview'; break;
}

include __DIR__.'/theme/head.tpl.php'; 

// include the correct file based on $_GET['pos']/$sPos
$sFile = __DIR__ .'/'.$sFile.'.php';
if(file_exists($sFile)) require $sFile;
else echo 'file not found';

include __DIR__.'/theme/foot.tpl.php'; 