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

// Whith the no_page marker , we know that we want to show the Connector 
if ($noPage){
    // We serve the connector file via include 
    include ("ef/php/connector.wbce.php");
    
}
else {
    // we serve the main application 
    include($modulePath."templates/elfinder.tpl.php");
    //echo "<pre>"; print_r($_SESSION);echo "</pre>";
    //echo (Settings::Info());
    //echo "RENAME_FILES_ON_UPLOAD:".RENAME_FILES_ON_UPLOAD;
    $sForbidden = str_replace(",","|",RENAME_FILES_ON_UPLOAD); 
	//echo $sForbidden;
    //echo "<pre>"; print_r ($_SESSION); echo "</pre>";
}
 
