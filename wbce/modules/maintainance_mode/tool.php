<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license			WTFPL
 */

/*
Info for module(tool) builders.
Already included here :
config.php
framework/initialize.php
framework/class.wb.php
framework/class.admin.php
framework/functions.php

Admin class is initialized($admin) and header printed.

Additional vars for this tool: 
$modulePath     Path to this module directory
$languagePath   Path to language files of this module
$returnToTools  Url to return to generic tools page
$returnUrl      Url for return link after saving AND for sending the form!
$doSave         Set true if form is send
$toolDir        Plain tool directory name like "maintainance_mode"
$toolName       The name of the tool eg "Maintainance Mode"

For language vars please take a look in the language files.
Language files no longer need manual loading.

All other vars usually abailable in Admin pages schould be available here too.
Maybe you need to import them via global.

backend.js and backend.css are automatically loaded, 
manual loading is no longer required.
*/

//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// Form send , ok lets see what to do
if($doSave) {

    // Check if this is is no CSRF attack or timeout.
    if (!$admin->checkFTAN()) {
        //3rd param = false =>no auto footer, no exit.
	    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$returnUrl, false); 
    }

    // HERE THE ACTUAL ACTION IS GOING ON!!    
    // We set the setting
   
    if ($admin->get_post("maintMode")) $setError=Settings::Set ("wb_maintainance_mode", true);
    else                               $setError=Settings::Set ("wb_maintainance_mode", false);
    // END ACTION!! 

    $setError="Dumm gelaufen";

    // Check if there is error, otherwise say successful
    if($setError) {
        //3rd param = false =>no auto footer, no exit
	    $admin->print_error($setError, $returnUrl,false); 
    } else {
	    $admin->print_success($MESSAGE['PAGES_SAVED'], $returnUrl); 
    }

} else { 

    // Display form
    // get setting from DB , as constant may not be set yet.
	$maintMode=(string)Settings::Get ("wb_maintainance_mode");
    if ($maintMode=="true") $maintMode=' checked="checked" ';
    else                $maintMode='';  

    include($modulePath."templates/maintainance.tpl.php");
    
}
 
