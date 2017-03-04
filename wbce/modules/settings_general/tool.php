<?php
/**
 * @category        modules
 * @package         maintainance_mode
 * @author          WBCE Project
 * @copyright       Norbert Heimsath
 * @license         WTFPL
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
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

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
    // We set the setting
    $value=(int)$admin->get_post("page_level_limit");
    if ($value >=1 and $value <=10) {
        $value= (string)$value;
        $setError.=Settings::Set ("page_level_limit", $value);
        $setError.=Settings::Set ("wb_page_level_limit", $value);
    }

    // We set the setting
    if ($admin->get_post("page_trash")=="inline") {
        $setError.=Settings::Set ("page_trash", "inline");
        $setError.=Settings::Set ("wb_page_trash", "inline");
    }
    else {
        $setError.=Settings::Set ("page_trash", "disabled");
        $setError.=Settings::Set ("wb_page_trash", "disabled");
    }

    // We set the setting
    if ($admin->get_post("page_languages")=="true") {
        $setError.=Settings::Set ("page_languages", true);
        $setError.=Settings::Set ("wb_page_languages", true);
    }
    else {
        $setError.=Settings::Set ("page_languages", false);
        $setError.=Settings::Set ("wb_page_languages", false);
    }

    // We set the setting
    if ($admin->get_post("multiple_menus")=="true") {
        $setError.=Settings::Set ("multiple_menus", true);
        $setError.=Settings::Set ("wb_multiple_menus", true);
    }
    else {
        $setError.=Settings::Set ("multiple_menus", false);
        $setError.=Settings::Set ("wb_multiple_menus", false);
    }

    // We set the setting
    if ($admin->get_post("home_folders")=="true") {
        $setError.=Settings::Set ("home_folders", true);
        $setError.=Settings::Set ("wb_home_folders", true);
    }
    else {
        $setError.=Settings::Set ("home_folders", false);
        $setError.=Settings::Set ("wb_home_folders", false);
    }

     // We set the setting
    if ($admin->get_post("manage_sections")=="true") {
        $setError.=Settings::Set ("manage_sections", true);
        $setError.=Settings::Set ("wb_manage_sections", true);
    }
    else {
        $setError.=Settings::Set ("manage_sections", false);
        $setError.=Settings::Set ("wb_manage_sections", false);
    }

    // We set the setting
    if ($admin->get_post("section_blocks")=="true") {
        $setError.=Settings::Set ("section_blocks", true);
        $setError.=Settings::Set ("wb_section_blocks", true);
    }
    else {
        $setError.=Settings::Set ("section_blocks", false);
        $setError.=Settings::Set ("wb_section_blocks", false);
    }

    // We set the setting
    if ($admin->get_post("intro_page")=="true") {
        $setError.=Settings::Set ("intro_page", true);
        $setError.=Settings::Set ("wb_intro_page", true);
    }
    else {
        $setError.=Settings::Set ("intro_page", false);
        $setError.=Settings::Set ("wb_intro_page", false);
    }

    // We set the setting
    if ($admin->get_post("homepage_redirection")=="true") {
        $setError.=Settings::Set ("homepage_redirection", true);
        $setError.=Settings::Set ("wb_homepage_redirection", true);
    }
    else {
        $setError.=Settings::Set ("homepage_redirection", false);
        $setError.=Settings::Set ("wb_homepage_redirection", false);
    }

    // We set the setting
    if ($admin->get_post("smart_login")=="true") {
        $setError.=Settings::Set ("smart_login", true);
        $setError.=Settings::Set ("wb_smart_login", true);
    }
    else {
        $setError.=Settings::Set ("smart_login", false);
        $setError.=Settings::Set ("wb_smart_login", false);
    }

    // We set the setting
    if ($admin->get_post("frontend_login")=="true") {
        $setError.=Settings::Set ("frontend_login", true);
        $setError.=Settings::Set ("wb_frontend_login", true);
    }
    else {
        $setError.=Settings::Set ("frontend_login", false);
        $setError.=Settings::Set ("wb_frontend_login", false);
    }

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
            $setError.=Settings::Set ("redirect_timer", "500");
            $setError.=Settings::Set ("wb_redirect_timer", "500");
        }
    }

    // FRONTEND_SIGNUP
    $value=$admin->get_post("frontend_signup");
    if ($value){
        if ($value==false) {
            $setError.=Settings::Set ("frontend_signup", false);
            $setError.=Settings::Set ("wb_frontend_signup", false);
        } else {
            $value=(int)$value;
            if (gs_GroupPossible($value)) {
                $setError.=Settings::Set ("frontend_signup", $value);
                $setError.=Settings::Set ("wb_frontend_signup", $value);
            }
        }
    }

    // ER_LEVEL (Error Level)
    include (ADMIN_PATH.'/interface/er_levels.php');
    if (isset ($_POST['er_level'])) {
        if ($_POST['er_level']=="") $_POST['er_level']=="0"; // Standard einstellung

        if (isset($ER_LEVELS[$_POST['er_level']]) ){
            $setError.=Settings::Set ("er_level", $_POST['er_level']);
            $setError.=Settings::Set ("wb_er_level", $_POST['er_level']);
        }
    }

    // WYSIWYG_STYLE
    $value=$admin->get_post("wysiwyg_style");
    if ($value){
        $value=strip_tags ($value);
        $setError.=Settings::Set ("wysiwyg_style", $value);
        $setError.=Settings::Set ("wb_wysiwyg_style", $value);
    }

    // WYSIWYG_EDITOR
    $value=$admin->get_post("wysiwyg_editor");
    if ($value){
        if (gs_EditorPossible($value)) {
            $setError.=Settings::Set ("wysiwyg_editor", $value);
            $setError.=Settings::Set ("wb_wysiwyg_editor", $value);
        }
    }



    // END ACTION!!

    // report success or failure
    Tool::Msg ($setError, $returnUrl );
    //echo "LAAAAAAA: Error: $setError, Url:$returnUrl " ;


} else if ($saveDefault) {
    $setError="";
    // setting defaults
    $setError.=Settings::Set ("page_level_limit", '4');
    $setError.=Settings::Set ("wb_page_level_limit", '4');

    $setError.=Settings::Set ("page_trash", "inline");
    $setError.=Settings::Set ("wb_page_trash", "inline");

    $setError.=Settings::Set ("page_languages", true);
    $setError.=Settings::Set ("wb_page_languages", true);

    $setError.=Settings::Set ("multiple_menus", true);
    $setError.=Settings::Set ("wb_multiple_menus", true);

    $setError.=Settings::Set ("home_folders", true);
    $setError.=Settings::Set ("wb_home_folders", true);

    $setError.=Settings::Set ("manage_sections", "enabled");
    $setError.=Settings::Set ("wb_manage_sections", "enabled");

    $setError.=Settings::Set ("section_blocks", true);
    $setError.=Settings::Set ("wb_section_blocks", true);

    $setError.=Settings::Set ("intro_page", false);
    $setError.=Settings::Set ("wb_intro_page", false);

    $setError.=Settings::Set ("homepage_redirection", false);
    $setError.=Settings::Set ("wb_homepage_redirection", false);

    $setError.=Settings::Set ("smart_login", true);
    $setError.=Settings::Set ("wb_smart_login", true);

    $setError.=Settings::Set ("frontend_login", false);
    $setError.=Settings::Set ("wb_frontend_login", false);

    $setError.=Settings::Set ("redirect_timer", "500");
    $setError.=Settings::Set ("wb_redirect_timer", "500");

    $setError.=Settings::Set ("frontend_signup", false);
    $setError.=Settings::Set ("wb_frontend_signup", false);

    $setError.=Settings::Set ("er_level", '');
    $setError.=Settings::Set ("wb_er_level", '');

    $setError.=Settings::Set ("wysiwyg_style", "font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;");
    $setError.=Settings::Set ("wb_wysiwyg_style", "font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;");

    $value="ckeditor";
    if (!gs_EditorPossible($value)) $value="none";
    $setError.=Settings::Set ("wysiwyg_editor", $value);
    $setError.=Settings::Set ("wb_wysiwyg_editor", $value);


    // report success or failure
    Tool::Msg ($setError, $returnUrl );

} else {

    // Display form
    // get setting from DB , as constant may not be set yet.
    $maintMode=(string)Settings::Get ("wb_maintainance_mode");
    if ($maintMode=="true") $maintMode=' checked="checked" ';
    else                $maintMode='';

    // we need to preload no values , as they all stored in constants
    include($this->GetTemplatePath("general.tpl.php"));
}

//////////////////////////////
// Helper functions down here

function gs_GetGroupArray(){

    global $database;
    $ret = array();

    $sql="SELECT group_id, name FROM ".TABLE_PREFIX."groups WHERE group_id != '1'";
    $results = $database->query($sql);
    $ret = array();
    if($results->numRows() > 0) {
        while($group = $results->fetchRow()) {
           $ret[]=$group;
        }
        return $ret;
    }
    return false;
}

function gs_GroupPossible($GroupId){

    global $database;

    $GroupId=(int)$GroupId;
    if ($GroupId==1) return false;
    $sql="SELECT group_id, name FROM ".TABLE_PREFIX."groups WHERE group_id = '$GroupId'";
    $results = $database->query($sql);
    if($results->numRows() > 0) return true;
    else                        return false;
}

function gs_GetEditorArray(){

    global $database;
    $ret = array();

    $result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'wysiwyg' ORDER BY name");
    if($result->numRows() > 0) {
        while($addon = $result->fetchRow())
        {
            $ret[]=$addon;
        }
        return $ret;
    }
    return false;
}

function gs_EditorPossible($AddonId){

    global $database;

    if ($AddonId=="none") return true;
    $AddonId=$database->escapeString($AddonId);

    $sql="SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'wysiwyg' AND directory= '$AddonId' ";
    $results = $database->query($sql);
    if($results->numRows() > 0) return true;
    else                        return false;
}
