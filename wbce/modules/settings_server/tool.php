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
    //echo "<pre>"; print_r($_POST);echo "</pre>";

    // ONLY HERE THE ACTUAL ACTION IS GOING ON!!
    $setError="";

    // Set default values if nothing is posted
    $file_mode = STRING_FILE_MODE;
    $dir_mode = STRING_DIR_MODE;

    // Work-out the octal value for file mode
    $u = 0;
    if (isset($_POST['file_u_r']) && $_POST['file_u_r'] == 'true') {
        $u = $u + 4;
    }
    if (isset($_POST['file_u_w']) && $_POST['file_u_w'] == 'true') {
        $u = $u + 2;
    }
    if (isset($_POST['file_u_e']) && $_POST['file_u_e'] == 'true') {
        $u = $u + 1;
    }
    $g = 0;
    if (isset($_POST['file_g_r']) && $_POST['file_g_r'] == 'true') {
        $g = $g + 4;
    }
    if (isset($_POST['file_g_w']) && $_POST['file_g_w'] == 'true') {
        $g = $g + 2;
    }
    if (isset($_POST['file_g_e']) && $_POST['file_g_e'] == 'true') {
        $g = $g + 1;
    }
    $o = 0;
    if (isset($_POST['file_o_r']) && $_POST['file_o_r'] == 'true') {
        $o = $o + 4;
    }
    if (isset($_POST['file_o_w']) && $_POST['file_o_w'] == 'true') {
        $o = $o + 2;
    }
    if (isset($_POST['file_o_e']) && $_POST['file_o_e'] == 'true') {
        $o = $o + 1;
    }
    $file_mode = "0" . $u . $g . $o;


    // Work-out the octal value for dir mode
    $u = 0;
    if (isset($_POST['dir_u_r']) && $_POST['dir_u_r'] == 'true') {
        $u = $u + 4;
    }
    if (isset($_POST['dir_u_w']) && $_POST['dir_u_w'] == 'true') {
        $u = $u + 2;
    }
    if (isset($_POST['dir_u_e']) && $_POST['dir_u_e'] == 'true') {
        $u = $u + 1;
    }
    $g = 0;
    if (isset($_POST['dir_g_r']) && $_POST['dir_g_r'] == 'true') {
        $g = $g + 4;
    }
    if (isset($_POST['dir_g_w']) && $_POST['dir_g_w'] == 'true') {
        $g = $g + 2;
    }
    if (isset($_POST['dir_g_e']) && $_POST['dir_g_e'] == 'true') {
        $g = $g + 1;
    }
    $o = 0;
    if (isset($_POST['dir_o_r']) && $_POST['dir_o_r'] == 'true') {
        $o = $o + 4;
    }
    if (isset($_POST['dir_o_w']) && $_POST['dir_o_w'] == 'true') {
        $o = $o + 2;
    }
    if (isset($_POST['dir_o_e']) && $_POST['dir_o_e'] == 'true') {
        $o = $o + 1;
    }
    $dir_mode = "0" . $u . $g . $o;


    $setError.=Settings::Set ("string_file_mode", $file_mode);
    $setError.=Settings::Set ("wb_string_file_mode", $file_mode);

    $setError.=Settings::Set ("string_dir_mode", $dir_mode);
    $setError.=Settings::Set ("wb_string_dir_mode", $dir_mode);

    // OS Setting
    // We set the setting
    if ($admin->get_post("operating_system")=="linux") {
        $setError.=Settings::Set ("operating_system", 'linux');
        $setError.=Settings::Set ("wb_operating_system", 'linux');
    }
    else {
        $setError.=Settings::Set ("operating_system", "windows");
        $setError.=Settings::Set ("wb_operating_system", "windows");
    }

    $value=$admin->get_post("pages_directory");
    if ($value){
        if (preg_match("/^[\/\\][a-z0-9\_\-\/\\\.]{0,255}$/si",$value)) {
            if (file_exists(WB_PATH.$value)) {
                $setError.=Settings::Set ("pages_directory", $value);
                $setError.=Settings::Set ("wb_pages_directory", $value);
            } else {
                $setError.="Pages path directory does not exist.".WB_PATH.$value."<br />";
            }
        } else {
           $setError.="Invalid path, only 'A-Za-z0-9-_.' allowed in pages directory path."."<br />";
        }
    }

    $value=$admin->get_post("media_directory");
    if ($value){
        if (preg_match("/^[\/\\][a-z0-9\_\-\/\\\.]{0,255}$/si",$value)) {
            if (file_exists(WB_PATH.$value)) {
                $setError.=Settings::Set ("media_directory", $value);
                $setError.=Settings::Set ("wb_media_directory", $value);
            } else {
                $setError.="Media path directory does not exist.:".WB_PATH.$value."<br />";
            }
        } else {
           $setError.="Invalid path, only 'A-Za-z0-9-_.' allowed in media directory path."."<br />";
        }
    }

    $value=$admin->get_post("page_extension");
    if ($value){
        if (preg_match("/^[\.][a-z0-9]{2,10}$/si",$value)) {
            $setError.=Settings::Set ("page_extension", $value);
            $setError.=Settings::Set ("wb_page_extension", $value);
        } else {
           $setError.="Invalid page extension, only 'A-Za-z.' allowed in page extension.";
        }
    }

    $value=$admin->get_post("page_spacer");
    if ($value){
        if (preg_match("/^[a-z0-9\.\_\-]{0,5}$/si",$value)) {
            $setError.=Settings::Set ("page_spacer", $value);
            $setError.=Settings::Set ("wb_page_spacer", $value);
        } else {
           $setError.="Invalid page spacer(space replacement), only 'a-z0-9-_.'or '' (nothing) allowed in page spacer.";
        }
    }

    $value=$admin->get_post("rename_files_on_upload");
    if ($value){
        if (preg_match("/^[a-z0-9\.\?\+\*\_\-\,]{0,250}$/si",$value)) {
            $setError.=Settings::Set ("rename_files_on_upload", $value);
            $setError.=Settings::Set ("wb_rename_files_on_upload", $value);
            $setError.=Settings::Set ("wb_deny_files_on_upload", $value);
        } else {
           $setError.="Invalid entry in file upload setting, only 'a-z0-9-_,+*?.' or '' (nothing) allowed.";
        }
    }

    $value=$admin->get_post("app_name");
    if ($value){
        if (preg_match("/^[a-z0-9\_\-]{2,20}$/si",$value)) {
            $setError.=Settings::Set ("app_name", $value);
            $setError.=Settings::Set ("wb_app_name", $value);
        } else {
           $setError.="Invalid session ID (app_name), only 'a-z0-9-_' allowed, min/max length 2/20 .";
        }
    }


    $value=$admin->get_post("sec_anchor");
    if ($value){
        if (preg_match("/^[a-z0-9\_\-]{2,15}$/si",$value)) {
            $setError.=Settings::Set ("sec_anchor", $value);
            $setError.=Settings::Set ("wb_sec_anchor", $value);
        } else {
           $setError.="Invalid Section Anchor (sec_anchor), only 'a-z0-9-_' allowed, min/max length 2/15 .";
        }
    }

    // END ACTION!!

    // report success or failure
    Tool::Msg ($setError, $returnUrl );



} else if ($saveDefault) {
    $setError="";
    // setting defaults
    $setError.=Settings::Set ("operating_system", 'linux');
    $setError.=Settings::Set ("wb_operating_system", 'linux');

    $setError.=Settings::Set ("string_file_mode", '0644');
    $setError.=Settings::Set ("wb_string_file_mode", '0644');

    $setError.=Settings::Set ("string_dir_mode", '0755');
    $setError.=Settings::Set ("wb_string_dir_mode", '0755');

    $setError.=Settings::Set ("pages_directory", '/pages');
    $setError.=Settings::Set ("wb_pages_directory", '/pages');

    $setError.=Settings::Set ("media_directory", '/media');
    $setError.=Settings::Set ("wb_media_directory", '/media');

    $setError.=Settings::Set ("page_extension", '.php');
    $setError.=Settings::Set ("page_extension", '.php');

    $setError.=Settings::Set ("page_spacer", '-');
    $setError.=Settings::Set ("wb_page_spacer", '-');

    $setError.=Settings::Set ("rename_files_on_upload", 'ph.*?,cgi,pl,pm,exe,com,bat,pif,cmd,src,asp,aspx,js,lnk');
    $setError.=Settings::Set ("wb_rename_files_on_upload", 'ph.*?,cgi,pl,pm,exe,com,bat,pif,cmd,src,asp,aspx,js,lnk');
    $setError.=Settings::Set ("wb_deny_files_on_upload", 'ph.*?,cgi,pl,pm,exe,com,bat,pif,cmd,src,asp,aspx,js,lnk');

    $setError.=Settings::Set ("wb_allowed_files_on_upload", 'txt,gif,png,pdf,doc,docx,jpg,jpeg');

    //$setError.=Settings::Set ("APP_NAME", 'wb-3302');
    //$setError.=Settings::Set ("wb_APP_NAMEs", 'wb-3302');

    $setError.=Settings::Set ("sec_anchor", 'wb_');
    $setError.=Settings::Set ("wb_sec_anchor", 'wb_');

    // report success or failure
    Tool::Msg ($setError, $returnUrl );

} else {

     // we need to preload no values , as they all stored in constants
    include($this->GetTemplatePath("tool.tpl.php"));

}

//////////////////////////////
// Helper functions down here

