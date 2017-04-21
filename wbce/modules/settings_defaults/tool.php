<?php
/**
 * @category        modules
 * @package         SEO Settings
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         GPLv2 or any later 
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

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
    $setError="";
   

    
   

    // REDIRECT_TIMER
    if ($admin->get_post("redirect_timer")){
        $value=(int)$admin->get_post("redirect_timer");
        if ($value >=-1 and $value <=100000) {
            $value= (string)$value;
            $setError.=Settings::Set ("redirect_timer", $value);
            $setError.=Settings::Set ("wb_redirect_timer", $value);     
        }
        else {
            $setError.=$TEXT['REDIRECT_AFTER']." (Redirect Timer) out of range, default set.<br />";
            $setError.=Settings::Set ("redirect_timer", "1500");
            $setError.=Settings::Set ("wb_redirect_timer", "1500");             
        }
    }
    
    
    // WYSIWYG_EDITOR 
    
    $value=$admin->get_post("wysiwyg_editor");
    if ($value){ 
        if (gs_EditorPossible($value)) {
            $setError.=Settings::Set ("wysiwyg_editor", $value);
            $setError.=Settings::Set ("wb_wysiwyg_editor", $value);            
        }              
    }    
    
    //Languages
    $value=$admin->get_post("default_language");
    if ($value){  
        if (ds_LanguagePossible($value)) {
            $setError.=Settings::Set ("DEFAULT_LANGUAGE", $value);
            $setError.=Settings::Set ("WB_DEFAULT_LANGUAGE", $value);             
        }              
    }   
    
    //Timezones
    $value=$admin->get_post("default_timezone");
    if ($value or $value=="0"){  
        if (array_key_exists((string)$value, ds_GetTimezonesArray())) {
            $setError.=Settings::Set ("DEFAULT_TIMEZONE", $value*60*60);
            $setError.=Settings::Set ("WB_DEFAULT_TIMEZONE", $value*60*60);  
        }              
    }   

    //Date Formats
    $value=$admin->get_post("default_date_format");
    if ($value){  
        $cvalue = preg_replace("/\ /","|",$value);
        if (array_key_exists((string)$cvalue, ds_GetDateFormatArray())) {
            $setError.=Settings::Set ("DEFAULT_DATE_FORMAT", $value);
            $setError.=Settings::Set ("WB_DEFAULT_DATE_FORMAT", $value);  
        }              
    }   
    
    //Time Formats
    $value=$admin->get_post("default_time_format");
    if ($value){ 
        $cvalue = preg_replace("/\ /","|",$value);
        if (array_key_exists((string)$cvalue, ds_GetTimeFormatArray())) {
            $setError.=Settings::Set ("DEFAULT_TIME_FORMAT", $value);
            $setError.=Settings::Set ("WB_DEFAULT_TIME_FORMAT", $value);  
        }              
    }   
    
    
   
    
    // END ACTION!! 

    // report success or failure
    Tool::Msg ($setError, $returnUrl );
    

} else if ($saveDefault) {
     $setError="";
    // setting defaults
    $setError.=Settings::Set ("DEFAULT_LANGUAGE", 'EN');
    $setError.=Settings::Set ("WB_DEFAULT_LANGUAGE", 'EN');  

    $setError.=Settings::Set ("DEFAULT_TIMEZONE", '0');
    $setError.=Settings::Set ("WB_DEFAULT_TIMEZONE", '0');  
    
    $setError.=Settings::Set ("DEFAULT_CHARSET", 'utf-8');
    $setError.=Settings::Set ("WB_DEFAULT_CHARSET", 'utf-8');  

    $setError.=Settings::Set ("DEFAULT_DATE_FORMAT", 'M d Y');
    $setError.=Settings::Set ("WB_DEFAULT_DATE_FORMAT", 'M d Y'); 
    
    $setError.=Settings::Set ("DEFAULT_TIME_FORMAT", 'g:i A');
    $setError.=Settings::Set ("WB_DEFAULT_TIME_FORMAT", 'g:i A');  
    
  
    
    
    // report success or failure
    Tool::Msg ($setError, $returnUrl );

} else { 

    // we need to preload no values , as they all stored in constants

    include($this->GetTemplatePath("tool.tpl.php")); 
}

//////////////////////////////
// Helper functions down here 



function ds_GetTimezonesArray(){
    include(ADMIN_PATH.'/interface/timezones.php');
    unset($TIMEZONES['system_default']); 
    return $TIMEZONES;    
}

function ds_GetCharsetArray(){
    include(ADMIN_PATH.'/interface/charsets.php');
    return $CHARSETS;    
}

function ds_GetDateFormatArray(){
    include(ADMIN_PATH.'/interface/date_formats.php');
    unset($DATE_FORMATS['system_default']); 
    return $DATE_FORMATS;    
}

function ds_GetTimeFormatArray(){
    include(ADMIN_PATH.'/interface/time_formats.php');
    unset($TIME_FORMATS['system_default']); 
    return $TIME_FORMATS;    
}

function ds_GetTemplatesArray(){
    
    global $database;
    $ret = array();
    
    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function != 'theme' ORDER BY name");
    if($result->numRows() > 0) {
        while($addon = $result->fetchRow())
        {
            $ret[]=$addon;   
        }
        return $ret;
    } 
    return false;
}


function ds_GetThemesArray(){
    
    global $database;
    $ret = array();
    
    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function = 'theme' ORDER BY name");
    if($result->numRows() > 0) {
        while($addon = $result->fetchRow())
        {
            $ret[]=$addon;   
        }
        return $ret;
    } 
    return false;
}


function ds_TemplatePossible($Name=""){

    global $database;

    if (empty ($Name)) return false;
    if (!preg_match("/^[a-z0-9\-\_]+$/is",$Name)) return false;
    
    $sql="SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function != 'theme' AND directory ='$Name'";

    $results = $database->query($sql);
    if($results->numRows() > 0) return true;
    else                        return false;
}

function ds_ThemePossible($Name=""){

    global $database;

    if (empty ($Name)) return false;
    if (!preg_match("/^[a-z0-9\-\_]+$/is",$Name)) return false;
    
    $sql="SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function = 'theme' AND directory ='$Name'";

    $results = $database->query($sql);
    if($results->numRows() > 0) return true;
    else                        return false;
}

function ds_LanguagePossible($Name=""){

    global $database;

    if (empty ($Name)) return false;
    if (!preg_match("/^[A-Z]{2}$/s",$Name)) return false;

    $sql="SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'language' AND directory ='$Name'";
    
    $results = $database->query($sql);
    if($results->numRows() > 0) return true;
    else                        return false;
}




