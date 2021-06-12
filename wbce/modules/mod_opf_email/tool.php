<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright    Ryan Djurovich (2004-2009)
 * @copyright    WebsiteBaker Org. e.V. (2009-2015)
 * @copyright    WBCE Project (2015-)
 * @category     tool
 * @package      OPF E-Mail
 * @version      1.1.7
 * @authors      Martin Hecht (mrbaseman)
 * @link         https://forum.wbce.org/viewtopic.php?id=176
 * @license      GNU GPL2 (or any later version)
 * @platform     WBCE 1.x
 * @requirements OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
    // Stop this file being access directly
    if (!headers_sent()) {
        header("Location: ../index.php", true, 301);
    }
    die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

$msgTxt = '';   //message content
$msgCls = 'msg-box'; // message css class

// if the filter as a whole has been switched on, but all settings are off, behave as if they were on (and simply switch them on
if (Settings::Get('opf_email') && ! (Settings::Get('OPF_MAILTO_FILTER') || Settings::Get('OPF_JS_MAILTO') || Settings::Get('OPF_EMAIL_FILTER'))) {
    Settings::Set('OPF_MAILTO_FILTER', 1);
    Settings::Set('OPF_JS_MAILTO', 1);
    Settings::Set('OPF_EMAIL_FILTER', 1);
}
// otherwise, if the filter is off, switch the settings all off
if (! Settings::Get('opf_email')) {
    Settings::Set('OPF_MAILTO_FILTER', 0);
    Settings::Set('OPF_JS_MAILTO', 0);
    Settings::Set('OPF_EMAIL_FILTER', 0);
}

// check whether outputfilter-module is installed
if (file_exists(WB_PATH.'/modules/outputfilter_dashboard/functions.php')) {
    require_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');
}

if ($doSave) {
    // take over post - arguments
    $data = array();
    $data['email_filter']    = (int)(intval(isset($_POST['email_filter']) ? $_POST['email_filter'] : 0) != 0);
    $data['mailto_filter']   = (int)(intval(isset($_POST['mailto_filter']) ? $_POST['mailto_filter'] : 0) != 0);
    $data['js_mailto']       = (int)(intval(isset($_POST['js_mailto']) ? $_POST['js_mailto'] : 0) != 0);
    $data['at_replacement']  = isset($_POST['at_replacement']) ? trim(strip_tags($_POST['at_replacement'])) : '';
    $data['dot_replacement'] = isset($_POST['dot_replacement']) ? trim(strip_tags($_POST['dot_replacement'])) : '';

    // dont use JAvascript Mailto if no mailto filter active.
    if ($data['js_mailto'] and !$data['mailto_filter']) {
        $data['js_mailto']=0;
    }

    if ($admin->checkFTAN()) {
        // update database settings
        // OPF_JS_MAILTO
        $errmsg="";

        // set the values
        $errmsg.=(string)Settings::Set("opf_email_filter", $data['email_filter']);
        $errmsg.=(string)Settings::Set("opf_mailto_filter", $data['mailto_filter']);
        $errmsg.=(string)Settings::Set("opf_js_mailto", $data['js_mailto']);
        $errmsg.=(string)Settings::Set("opf_at_replacement", $data['at_replacement']);
        $errmsg.=(string)Settings::Set("opf_dot_replacement", $data['dot_replacement']);

        if (function_exists('opf_set_active')) {
            // if any of the three is active switch on the filter, otherwise switch it off
            if ($data['email_filter'] || $data['mailto_filter'] || $data['js_mailto']) {
                opf_set_active('E-Mail', 1);
            } else {
                opf_set_active('E-Mail', 0);
            }
        }

        if ($errmsg=="") {
            //anything ok
            $msgTxt = "<b>".$MESSAGE['RECORD_MODIFIED_SAVED']."</b>";
            $msgCls = 'msg-box';
        } else {
            // error
            $msgTxt = "<b>".$MESSAGE['RECORD_MODIFIED_FAILED']."</b><p>".$errmsg."</p>";
            $msgCls = 'error-box';
        }
    } else {
        // FTAN error
        $msgTxt = "<b>".$MESSAGE['GENERIC_SECURITY_ACCESS']."</b>";
        $msgCls = 'error-box';
    }
} else {
    // read settings from the database to show

    $data = array();
    $data['email_filter']      = Settings::Get('opf_email_filter', 1);
    $data['mailto_filter']     = Settings::Get('opf_mailto_filter', 1);
    $data['js_mailto']         = Settings::Get('opf_js_mailto', 1);
    $data['at_replacement']    = Settings::Get('opf_at_replacement', "(at)");
    $data['dot_replacement']   = Settings::Get('opf_dot_replacement', "(dot)");
}

include($modulePath."templates/output_filter.tpl.php");
