<?php
/**
 * @category        modules
 * @package         preferences
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2
 */

// no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// error ans message arrays 
// alternative way of handling errors whitout $admin->print_message... 
$error=array();
$message=array();

$Preferences=new Preferences();

// Save button is pressed 
if($doSave) {

    // fetch the preferences data
    include $modulePath."/includes/fetch_preferences_data.php";

    // validate the data
    include $modulePath."/includes/validate_data.php";
    
    // no error lets store to DB 
    if (empty($error)) {
        include $modulePath."/includes/update_db_session.php";
    }
}

// fetch the preferences data
include $modulePath."/includes/fetch_preferences_data.php";

$errorTxt="";
if (!empty($error)) {
    foreach ($error as $value){
        $errorTxt.="<p>$value</p>";   
    }
}

$msgTxt="";
if (!empty($message)) {
    foreach ($message as $value){
        $msgTxt.="<p>$value</p>";   
    }
}

// fetch the template
include($this->GetTemplatePath("preferences_form.tpl.php")); 