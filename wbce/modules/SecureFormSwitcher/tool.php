<?php
/**
 * @category        modules
 * @package         Security Settings
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

// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// Check if this is is no CSRF attack or timeout.
// but only check if form was send. Maybe we move this to tool too if all tools got secform
if($doSave) {
    if (!$admin->checkFTAN()) {
        //3rd param = false =>no auto footer, no exit.
	    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$returnUrl, false); 
    }
}

// Form send , ok lets see what to do
if($saveSettings) {

    // ONLY HERE THE ACTUAL ACTION IS GOING ON!!    

    // Use fingerprinting
    if ($admin->get_post("useFP")) $setError=Settings::Set ("wb_secform_usefp", true);
    else                           $setError=Settings::Set ("wb_secform_usefp", false);

    $ipOctets=$admin->get_post("ipOctets");
    if (preg_match("/^[0-4]$/u", $ipOctets))   $setError.=Settings::Set ("fingerprint_with_ip_octets", $ipOctets);
    else $setError.=$SFS['USEIP_ERR'];

    $tokenName=$admin->get_post("tokenName");
    if (preg_match("/^[a-zA-Z]{5,20}$/u", $tokenName)) $setError.=Settings::Set ("wb_secform_tokenname", $tokenName);
    else $setError.=$SFS['TOKENNAME_ERR'];

    $timeout=$admin->get_post("timeout");
    if (preg_match("/^[0-9]{1,5}$/u", $timeout)){ 
        $setError.=Settings::Set ("wb_secform_timeout", $timeout);
        $setError.=Settings::Set ("wb_session_timeout", $timeout);
    }
    else $setError.=$SFS['TIMEOUT_ERR'];

    $secret=$admin->get_post("secret");
    if (preg_match("/^[a-zA-Z0-9]{20,60}$/u", $secret)) $setError.=Settings::Set ("wb_secform_secret", $secret);
    else $setError.=$SFS['SECRET_ERR'];

    $secretTime=$admin->get_post("secretTime");
    if (preg_match("/^[0-9]{1,5}$/u", $secretTime)) $setError.=Settings::Set ("wb_secform_secrettime", $secretTime);
    else $setError.=$SFS['SECRETTIME_ERR'];


    // END ACTION!! 

    // report success or failure
    toolMsg ($setError, $returnUrl );

} else if ($saveDefault) {

    // setting defaults
    $setError=Settings::Set ("wb_secform_secret", "5609bnefg93jmgi99igjefg");
    $setError=Settings::Set ("wb_secform_secrettime", '86400');
    $setError=Settings::Set ("wb_secform_timeout", '7200');
    $setError=Settings::Set ("wb_session_timeout", '7200');
    $setError=Settings::Set ("wb_secform_tokenname", 'formtoken');
    $setError=Settings::Set ("wb_secform_usefp", false);
    $setError=Settings::Set ("fingerprint_with_ip_octets", "2");

    // report success or failure
    toolMsg ($setError, $returnUrl );

} else { 

    // Get form vars
    $selected = ' selected="selected" ';
    $checked  = ' checked="checked" ';

    // get settings from DB , as constant may not be set yet.
    $useFP = Settings::Get ("wb_secform_usefp");
    if ($useFP == true) $useFP = $checked;
    else                $useFP = '';  
    
    $ipOctets   = (string)Settings::Get ("fingerprint_with_ip_octets");
    $tokenName  = Settings::Get ("wb_secform_tokenname");
    $timeout    = Settings::Get ("wb_secform_timeout");
    $secret     = Settings::Get ("wb_secform_secret");
    $secretTime = Settings::Get ("wb_secform_secrettime");

    //Display form
    include($modulePath."templates/sfs.tpl.php");
    
}
