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
 
 
//Fetch the default content for Template 
 
$query = "SELECT * FROM ".TABLE_PREFIX."search WHERE extra = ''";
$results = $database->query($query);



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
    $setError="";
    //I simply copied this stuff in as i am so tired of redoing this shit


    // Query current search settings in the db, then loop through them and update the db with the new value
    $sql = 'SELECT `name`, `value` FROM `' . TABLE_PREFIX . 'search` '
    . 'WHERE `extra`=\'\'';
    if (!($res_search = $database->query($sql))) {
        $setError=$database->is_error();
    }
    while ($search_setting = $res_search->fetchRow()) {
        $old_value = $search_setting['value'];
        $setting_name = $search_setting['name'];
        $post_name = 'search_' . $search_setting['name'];

        // hold old value if post is empty
        // check search template
        $value = (($admin->get_post($post_name) == '') && ($setting_name != 'template'))
        ? $old_value
        : $admin->get_post($post_name);
        if (isset($value)) {
            $value = $admin->add_slashes($value);
            $sql = 'UPDATE `' . TABLE_PREFIX . 'search` '
            . 'SET `value`=\'' . $value . '\' '
            . 'WHERE `name`=\'' . $setting_name . '\' AND `extra`=\'\'';
            if (!($database->query($sql))) {
                $setError=$database->get_error;
                break;
            }
            // $sql_info = mysql_info($database->db_handle); //->> nicht mehr erforderlich
        }
    }
    
    // END ACTION!! 
    // report success or failure
    Tool::Msg ($setError, $returnUrl );    

} else if ($saveDefault) {
    $setError="";

    $resetSql=$modulePath."/sql/reset.sql";
    
    if (is_readable($resetSql)) {
        if (!$database->SqlImport($resetSql, TABLE_PREFIX, false)) {
            $setError=("unable to import $resetSql ");
        }
    } else {
        $setError=("unable to read file $resetSql");
    }
           
    // report success or failure
    Tool::Msg ($setError, $returnUrl );

} else { 

    //This hapens if form is not send in any way 
    // We simply go for display the form 

    //Fetch form  content from DB
    include($modulePath."/includes/fetch_form_content.php");

    // we need to preload no values , as they all stored in constants
    include($this->GetTemplatePath("tool.tpl.php")); 
}

//////////////////////////////
// Helper functions down here 

function se_GetTemplatesArray(){
    
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

function se_TemplatePossible($Name=""){

    global $database;
    if ($Name=="") return true;
    if (empty ($Name)) return false;
    if (!preg_match("/^[a-z0-9\-\_]+$/is",$Name)) return false;
    
    $sql="SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template' AND function != 'theme' AND directory ='$Name'";

    $results = $database->query($sql);
    if($results->numRows() > 0) return true;
    else                        return false;
}

function se_VisibilityPossible($value=""){
    if ($value=="public" or 
        $value=="private" or
        $value=="registered" or
        $value=="none"
        ) return true;
        
        return false;
}

