<?php
/**
 *
 * @category        backend,hidden
 * @package         OpF Simple Backend
 * @version         1.3.0
 * @authors         Martin Hecht (mrbaseman)
 * @copyright       (c) 2018, Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) die(header('Location: ../../index.php'));

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

if(!function_exists('opf_set_active_be')){
    if(file_exists(WB_PATH.'/modules/outputfilter_dashboard/functions.php')){
        require_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');
    }

    function opf_set_active_be($filter_name, $active=true){
        if(opf_is_registered($filter_name, OPF_VERBOSE)) {
            $filter_name = opf_check_name($filter_name);
            if(!$filter_name) {
                trigger_error('opf_set_active_be(): filter_name: '.$filter_name, E_USER_WARNING);
                 return(FALSE);
            }
            $filter_settings=opf_filter_get_data($filter_name);
            if(!is_array($filter_settings)){
                trigger_error('opf_set_active_be(): filter_settings not an array: '.$filter_name, E_USER_WARNING);
                return false;
            }
            if($active){
                    if (($key = array_search('backend', $filter_settings['pages_parent'])) === false) {
                            array_push($filter_settings['pages_parent'],'backend');
                    }
            } else{
                    if (($key = array_search('backend', $filter_settings['pages_parent'])) !== false) {
                            unset($filter_settings['pages_parent'][$key]);
                    }
            }
            $filter_settings['force']=true;
            return opf_register_filter($filter_settings);
        }

        if(OPF_VERBOSE)
            trigger_error('opf_set_active_be(): filter not registered: '.$filter_name, E_USER_WARNING);
        return false;
    }
}

$msgTxt = '';   //message content
$msgCls = 'msg-box'; // message css class


$data = array();  // store the settings for each filter

$data['show_advanced_backend'] = Settings::Get('opf_show_advanced_backend',0);

//frontend
$data['droplets']          = Settings::Get('opf_droplets',1);
$data['auto_placeholder']  = Settings::Get('opf_auto_placeholder',1);
$data['move_stuff']        = Settings::Get('opf_move_stuff',1);
$data['replace_stuff']     = Settings::Get('opf_replace_stuff',1);
$data['css_to_head']       = Settings::Get('opf_css_to_head',1);
$data['wblink']            = Settings::Get('opf_wblink',1);
$data['short_url']         = Settings::Get('opf_short_url',0);
$data['sys_rel']           = Settings::Get('opf_sys_rel',1);
$data['remove_system_ph']           = Settings::Get('opf_remove_system_ph',1);

//backend
$data['droplets_be']        = Settings::Get('opf_droplets_be',1);
$data['auto_placeholder_be']= Settings::Get('opf_auto_placeholder_be',1);
$data['move_stuff_be']      = Settings::Get('opf_move_stuff_be',1);
$data['replace_stuff_be']   = Settings::Get('opf_replace_stuff_be',1);
$data['css_to_head_be']     = Settings::Get('opf_css_to_head_be',1);
$data['remove_system_ph_be']           = Settings::Get('opf_remove_system_ph_be',1);

$errmsg="";


if($doSave) {
    // take over post - arguments
    if($data['show_advanced_backend'] != 1){ // classical view, take all values from post - arguments

        //frontend
        $data['droplets']         = (int)(intval(isset($_POST['droplets']) ? $_POST['droplets'] : 0) != 0);
        $data['auto_placeholder'] = (int)(intval(isset($_POST['auto_placeholder']) ? $_POST['auto_placeholder'] : 0) != 0);
        $data['move_stuff']       = (int)(intval(isset($_POST['move_stuff']) ? $_POST['move_stuff'] : 0) != 0);
        $data['replace_stuff']    = (int)(intval(isset($_POST['replace_stuff']) ? $_POST['replace_stuff'] : 0) != 0);
        $data['css_to_head']      = (int)(intval(isset($_POST['css_to_head']) ? $_POST['css_to_head'] : 0) != 0);
        $data['wblink']           = (int)(intval(isset($_POST['wblink']) ? $_POST['wblink'] : 0) != 0);
        $data['short_url']        = (int)(intval(isset($_POST['short_url']) ? $_POST['short_url'] : 0) != 0);
        $data['sys_rel']          = (int)(intval(isset($_POST['sys_rel']) ? $_POST['sys_rel'] : 0) != 0);
        $data['remove_system_ph']          = (int)(intval(isset($_POST['remove_system_ph']) ? $_POST['remove_system_ph'] : 0) != 0);

        //backend
        $data['droplets_be']         = (int)(intval(isset($_POST['droplets_be']) ? $_POST['droplets_be'] : 0) != 0);
        $data['auto_placeholder_be'] = (int)(intval(isset($_POST['auto_placeholder_be']) ? $_POST['auto_placeholder_be'] : 0) != 0);
        $data['move_stuff_be']       = (int)(intval(isset($_POST['move_stuff_be']) ? $_POST['move_stuff_be'] : 0) != 0);
        $data['replace_stuff_be']    = (int)(intval(isset($_POST['replace_stuff_be']) ? $_POST['replace_stuff_be'] : 0) != 0);
        $data['css_to_head_be']      = (int)(intval(isset($_POST['css_to_head_be']) ? $_POST['css_to_head_be'] : 0) != 0);
        $data['remove_system_ph_be']             = (int)(intval(isset($_POST['remove_system_ph_be']) ? $_POST['remove_system_ph_be'] : 0) != 0);

    }

    // and this one is always present:
    $data['show_advanced_backend'] = (int)(intval(isset($_POST['show_advanced_backend']) ? $_POST['show_advanced_backend'] : 0) != 0);

        // do some small validations
        if ((!file_exists(WB_PATH.'/short.php') or !file_exists(WB_PATH.'/.htaccess')) and $data['short_url']){
            $errmsg.= "Short URL not set, please check that short.php and .htaccess are present in your webroot directory";
            $data['short_url']=0;
        }

        // set the values
        // all filters


        if(Settings::Get('opf_show_advanced_backend',0)!=1){

            if(file_exists(WB_PATH.'/modules/outputfilter_dashboard/functions.php')){
                require_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');

                //frontend
                opf_set_active('Droplets', $data['droplets'])                  || $errmsg.="failed to update Droplets";
                opf_set_active('Auto Placeholder', $data['auto_placeholder'])  || $errmsg.="failed to update Auto Placeholder";
                opf_set_active('Move Stuff', $data['move_stuff'])              || $errmsg.="failed to update Move Stuff";
                opf_set_active('Replace Stuff', $data['replace_stuff'])        || $errmsg.="failed to update Replace Stuff";
                opf_set_active('CSS to head', $data['css_to_head'])            || $errmsg.="failed to update CSS to head";
                opf_set_active('WB-Link', $data['wblink'])                     || $errmsg.="failed to update WB-Link";
                opf_set_active('Short URL', $data['short_url'])                || $errmsg.="failed to update Short URL";
                opf_set_active('Sys Rel', $data['sys_rel'])                    || $errmsg.="failed to update Sys Rel";
                opf_set_active('Remove System PH', $data['remove_system_ph'])  || $errmsg.="failed to update Remove System PH";

                //backend
                opf_set_active_be('Droplets', $data['droplets_be'])                  || $errmsg.="failed to update backend Droplets";
                opf_set_active_be('Auto Placeholder', $data['auto_placeholder_be'])  || $errmsg.="failed to update backend Auto Placeholder";
                opf_set_active_be('Move Stuff', $data['move_stuff_be'])              || $errmsg.="failed to update backend Move Stuff";
                opf_set_active_be('Replace Stuff', $data['replace_stuff_be'])        || $errmsg.="failed to update backend Replace Stuff";
                opf_set_active_be('CSS to head', $data['css_to_head_be'])            || $errmsg.="failed to update backend CSS to head";
                opf_set_active_be('Remove System PH', $data['remove_system_ph_be'])  || $errmsg.="failed to update backend Remove System PH";

            } else {
                //frontend
                $errmsg.=(string)Settings::Set("opf_droplets", $data['droplets']);
                $errmsg.=(string)Settings::Set("opf_auto_placeholder", $data['auto_placeholder']);
                $errmsg.=(string)Settings::Set("opf_move_stuff", $data['move_stuff']);
                $errmsg.=(string)Settings::Set("opf_replace_stuff", $data['replace_stuff']);
                $errmsg.=(string)Settings::Set("opf_css_to_head", $data['css_to_head']);
                $errmsg.=(string)Settings::Set("opf_wblink", $data['wblink']);
                $errmsg.=(string)Settings::Set("opf_short_url", $data['short_url']);
                $errmsg.=(string)Settings::Set("opf_sys_rel", $data['sys_rel']);
                $errmsg.=(string)Settings::Set("opf_remove_system_ph", $data['remove_system_ph']);

                //backend
                $errmsg.=(string)Settings::Set("opf_droplets_be", $data['droplets_be']);
                $errmsg.=(string)Settings::Set("opf_auto_placeholder_be", $data['auto_placeholder_be']);
                $errmsg.=(string)Settings::Set("opf_move_stuff_be", $data['move_stuff_be']);
                $errmsg.=(string)Settings::Set("opf_replace_stuff_be", $data['replace_stuff_be']);
                $errmsg.=(string)Settings::Set("opf_css_to_head_be", $data['css_to_head_be']);
                $errmsg.=(string)Settings::Set("opf_remove_system_ph_be", $data['remove_system_ph_be']);
            }
        }

        $errmsg.=(string)Settings::Set("opf_show_advanced_backend", $data['show_advanced_backend']);

        if($errmsg=="") {
        //anything ok
            $msgTxt = "<b>".$MESSAGE['RECORD_MODIFIED_SAVED']."</b>";
            $msgCls = 'msg-box';
        } else {
        // error
            $msgTxt = "<b>".$MESSAGE['RECORD_MODIFIED_FAILED']."</b><p>".$errmsg."</p>";
            $msgCls = 'error-box';
        }
}

if ( Settings::Get('opf_show_advanced_backend',0)!=1){
  include(WB_PATH."/modules/opf_simple_backend/templates/output_filter.tpl.php");
}

