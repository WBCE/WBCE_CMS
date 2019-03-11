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
 
// Direct access prevention
defined('WB_PATH') or die(header('Location: ../index.php'));

$sToCss = "
        .groupWrapper div.settingValue textarea {
                padding: 0.3em;
                vertical-align: top;
                width: 100%;
                min-height: 0.6em;
        }
        .groupWrapper div.settingValue textarea:focus {
                outline-style: solid;
                outline-width: 2px;
        }
";

I::insertCssCode($sToCss, 'HEAD BTM-', 'autosize');
I::insertJsFile(get_url_from_path(__DIR__)."/js/autosize/autosize.min.js", "BODY TOP-");
I::insertJsCode("autosize(document.querySelectorAll('textarea'));", "BODY TOP-");


// Set up of the iniConfigEditor
// =============================|=============================|=============================|

// include the class by whichever way suitable for your project
include_once(__DIR__.'/IniEditor/IniEditor.class.php'); 

$sIniFile = __DIR__.'/account/Accounts.cfg.php';	
echo '<h2>'.$TOOL_TXT['ACCOUNTS_CONFIG'].'</h2>';

$oIniEditor = new IniEditor();      // initialize the class object		
$oIniEditor->setIniFile($sIniFile); // set ini file path (use full path)		
#$oIniEditor->bShowFileSrc = true; 		

$oIniEditor->enableEdit(true);      // set to true to allow edit of the config file

// We don't need the following settings of the INI Editor in this modul

#$oIniEditor->enableAdd(true);      // set to true to allow add of sections and fields in the config file
#$oIniEditor->enableDelete(true);   // set to true to allow delete of fields in the config file	
#$oIniEditor->enableMove(true);     // set to true to allow reordering if fields

// set cancel URL. If not set no cancel button will appear.
#$oIniEditor->setCancelUrl(ADMIN_URL.'/admintools/tool.php?tool='.$module_directory);


// set language directory if you want to translate the form fields	
$oIniEditor->setLanguageDir(__DIR__.'/languages/_config'); 
$TRANS_TEXT = $oIniEditor->getTransArray();
// $sForm      = $oIniEditor->getForm();  
$oIniEditor->printForm(); 

// END: Set up of the iniConfigEditor
// =============================|=============================|=============================|
?>
<div class="alert alert-info" style="margin-top:10px;"><?=$TRANS_TEXT['SIGNUP_CONFIG_EXPL']?></div>