<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          Christian Sommer, WB-Project, Werner v.d. Decken
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: tool.php 1520 2011-11-09 00:12:37Z darkviper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/tool.php $
 * @lastmodified    $Date: 2011-11-09 01:12:37 +0100 (Mi, 09. Nov 2011) $
 *
 */
//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

$msgTxt = '';   //message content
$msgCls = 'msg-box'; // message css class

if($doSave) {
// take over post - arguments
    $data = array();
    // all filter 
    $data['suppress_old_opf']       = (int)(intval(isset($_POST['suppress_old_opf']) ? $_POST['suppress_old_opf'] : 0) != 0);
    //frontend
    $data['droplets']        = (int)(intval(isset($_POST['droplets']) ? $_POST['droplets'] : 0) != 0);
    $data['wblink']          = (int)(intval(isset($_POST['wblink']) ? $_POST['wblink'] : 0) != 0);
    $data['insert']          = (int)(intval(isset($_POST['insert']) ? $_POST['insert'] : 0) != 0);
    $data['sys_rel']         = (int)(intval(isset($_POST['sys_rel']) ? $_POST['sys_rel'] : 0) != 0);
    $data['email_filter']    = (int)(intval(isset($_POST['email_filter']) ? $_POST['email_filter'] : 0) != 0);
    $data['mailto_filter']   = (int)(intval(isset($_POST['mailto_filter']) ? $_POST['mailto_filter'] : 0) != 0);
    $data['js_mailto']       = (int)(intval(isset($_POST['js_mailto']) ? $_POST['js_mailto'] : 0) != 0);
    $data['short_url']       = (int)(intval(isset($_POST['short_url']) ? $_POST['short_url'] : 0) != 0);
    $data['css_to_head']     = (int)(intval(isset($_POST['css_to_head']) ? $_POST['css_to_head'] : 0) != 0);
    $data['at_replacement']  = isset($_POST['at_replacement']) ? trim(strip_tags($_POST['at_replacement'])) : '';
    $data['dot_replacement'] = isset($_POST['dot_replacement']) ? trim(strip_tags($_POST['dot_replacement'])) : '';
    //backend
    $data['insert_be']       = (int)(intval(isset($_POST['insert_be']) ? $_POST['insert_be'] : 0) != 0);
    $data['css_to_head_be']  = (int)(intval(isset($_POST['css_to_head_be']) ? $_POST['css_to_head_be'] : 0) != 0);
    
    // dont use JAvascript Mailto if no mailto filter active.
    if ($data['js_mailto'] and !$data['mailto_filter']) $data['js_mailto']=0;
    
    
    if ($admin->checkFTAN()) {
    // update database settings
    // OPF_JS_MAILTO
        $errmsg="";
        
        // do some small validations
        if ((!file_exists(WB_PATH.'/short.php') or !file_exists(WB_PATH.'/.htaccess')) and $data['short_url']){
            $errmsg.= "Short URL not set, please check that short.php and .htaccess are present in your webroot directory";  
            $data['short_url']=0;
        }
        
        // set the values
        // all filters
        $errmsg.=(string)Settings::Set("wb_suppress_old_opf", $data['suppress_old_opf']);
        //frontend
        $errmsg.=(string)Settings::Set("opf_droplets", $data['droplets']);
        $errmsg.=(string)Settings::Set("opf_wblink", $data['wblink']);  
        $errmsg.=(string)Settings::Set("opf_insert", $data['insert']); 
        $errmsg.=(string)Settings::Set("opf_sys_rel", $data['sys_rel']);
        $errmsg.=(string)Settings::Set("opf_email_filter", $data['email_filter']);
        $errmsg.=(string)Settings::Set("opf_mailto_filter", $data['mailto_filter']);
        $errmsg.=(string)Settings::Set("opf_js_mailto", $data['js_mailto']);       
        $errmsg.=(string)Settings::Set("opf_short_url", $data['short_url']);
        $errmsg.=(string)Settings::Set("opf_css_to_head", $data['css_to_head']);
        $errmsg.=(string)Settings::Set("opf_at_replacement", $data['at_replacement']);
        $errmsg.=(string)Settings::Set("opf_dot_replacement", $data['dot_replacement']);
        //backend
        $errmsg.=(string)Settings::Set("opf_insert_be", $data['insert_be']); 
        $errmsg.=(string)Settings::Set("opf_css_to_head_be", $data['css_to_head_be']);
        
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

    $data = array();
    //all filters
    $data['suppress_old_opf']  = Settings::Get('wb_suppress_old_opf',0);
    //frontend
    $data['droplets']          = Settings::Get('opf_droplets',1);
    $data['wblink']            = Settings::Get('opf_wblink',1);
    $data['insert']            = Settings::Get('opf_insert',1);   
    $data['sys_rel']           = Settings::Get('opf_sys_rel',1);
    $data['email_filter']      = Settings::Get('opf_email_filter',1);
    $data['mailto_filter']     = Settings::Get('opf_mailto_filter',1);
    $data['js_mailto']         = Settings::Get('opf_js_mailto',1);
    $data['short_url']         = Settings::Get('opf_short_url',0);
    $data['css_to_head']       = Settings::Get('opf_css_to_head',1);
    $data['at_replacement']    = Settings::Get('opf_at_replacement',"(at)");
    $data['dot_replacement']   = Settings::Get('opf_dot_replacement',"(dot)");
    //backend
    $data['insert_be']         = Settings::Get('opf_insert_be',1); 
    $data['css_to_head_be']    = Settings::Get('opf_css_to_head_be',1);
}


include($modulePath."templates/output_filter.tpl.php");

