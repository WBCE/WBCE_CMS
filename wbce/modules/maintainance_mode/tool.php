<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
 */

/*
Info for module / admin-tool developers.
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
$saveSettings   Set true if there are actual settings send
$saveDefault    Set true if default button was pressed
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

// Check if this is is no CSRF attack or timeout.
// but only check if form was send. Maybe we move this to tool too if all tools got secform
if($doSave) {
    if (!$admin->checkFTAN()) {
        //3rd param = false =>no auto footer, no exit.
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $returnUrl, false); 
    }
}

if($saveSettings) {
    // Form send, ok lets see what to do

    $isActive = isset($_POST['enabled']);
    $setError = Settings::set("wb_maintainance_mode", $isActive, true); 
    toolMsg ($setError, $returnUrl ); // report success or failure

} else if ($saveDefault) {
    // Form send to reload default values (default is always false for this tool):
    
    $setError = Settings::set("wb_maintainance_mode", false);
    toolMsg ($setError, $returnUrl ); // report success or failure

} else { 
    // Nothing sent: Display form
    include($modulePath."templates/maintainance.tpl.php");    
}