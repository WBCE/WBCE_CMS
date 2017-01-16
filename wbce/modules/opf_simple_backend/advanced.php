<?php

/*
__M4_FILE__
*/

/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2017)
 * @category        backend,hidden
 * @package         OpF Simple Backend
 * @version         1.0.0
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x 
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// check if user is allowed to use admin-tools (to prevent this file to be called by an unauthorized user e.g. from a code-section)
if(!$admin->get_permission('admintools')) die(header('Location: ../../index.php'));

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

$msgTxt = '';   //message content
$msgCls = 'msg-box'; // message css class

if($doSave) {
// take over post - arguments
    $data = array();
    // all filter 
    $data['show_advanced_backend'] = (int)(intval(isset($_POST['show_advanced_backend']) ? $_POST['show_advanced_backend'] : 0) != 0);
    
    
    if ($admin->checkFTAN()) {
        $errmsg="";
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
    }else {
    // FTAN error
        $msgTxt = "<b>".$MESSAGE['GENERIC_SECURITY_ACCESS']."</b>";
        $msgCls = 'error-box';
    }
} else {
// read settings from the database to show
// the trick ist to use return values that will function as default values if 
// the value is not set :-)

    $data = array();
    //all filters
    $data['show_advanced_backend']  = Settings::Get('opf_show_advanced_backend',0);
    
}

include(WB_PATH."/modules/opf_simple_backend/templates/advanced.tpl.php");

