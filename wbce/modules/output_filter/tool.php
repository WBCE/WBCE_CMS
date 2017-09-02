<?php
/**
   @file tool.php
   @brief The main Tool that controlls the outputfilter 
   @category        modules
   @package         output_filter
   @author          Norbert Heimsath
   @copyright       GPLv2 or any later version 
 */

 //no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

$msgTxt = '';   //message content
$msgCls = 'msg-box'; // message css class

if($doSave) {
// take over post - arguments
    $data = array();

    //frontend
    $data['droplets']          = (int)(intval(isset($_POST['droplets']) ? $_POST['droplets'] : 0) != 0);
    $data['auto_placeholder']  = (int)(intval(isset($_POST['auto_placeholder']) ? $_POST['auto_placeholder'] : 0) != 0);
    $data['move_stuff']        = (int)(intval(isset($_POST['move_stuff']) ? $_POST['move_stuff'] : 0) != 0);
    $data['replace_stuff']     = (int)(intval(isset($_POST['replace_stuff']) ? $_POST['replace_stuff'] : 0) != 0);
    $data['css_to_head']       = (int)(intval(isset($_POST['css_to_head']) ? $_POST['css_to_head'] : 0) != 0);
    $data['email_filter']      = (int)(intval(isset($_POST['email_filter']) ? $_POST['email_filter'] : 0) != 0);
    $data['mailto_filter']     = (int)(intval(isset($_POST['mailto_filter']) ? $_POST['mailto_filter'] : 0) != 0);
    $data['js_mailto']         = (int)(intval(isset($_POST['js_mailto']) ? $_POST['js_mailto'] : 0) != 0);
    $data['at_replacement']    = isset($_POST['at_replacement']) ? trim(strip_tags($_POST['at_replacement'])) : '';
    $data['dot_replacement']   = isset($_POST['dot_replacement']) ? trim(strip_tags($_POST['dot_replacement'])) : '';
    $data['wblink']            = (int)(intval(isset($_POST['wblink']) ? $_POST['wblink'] : 0) != 0);
    $data['short_url']         = (int)(intval(isset($_POST['short_url']) ? $_POST['short_url'] : 0) != 0);
    $data['sys_rel']           = (int)(intval(isset($_POST['sys_rel']) ? $_POST['sys_rel'] : 0) != 0);
    $data['remove_comments']   = (int)(intval(isset($_POST['remove_comments']) ? $_POST['remove_comments'] : 0) != 0);
    $data['basic_html_minify'] = (int)(intval(isset($_POST['basic_html_minify']) ? $_POST['basic_html_minify'] : 0) != 0);
   
    //backend
    $data['droplets_be']          = (int)(intval(isset($_POST['droplets_be']) ? $_POST['droplets_be'] : 0) != 0);
    $data['auto_placeholder_be']  = (int)(intval(isset($_POST['auto_placeholder_be']) ? $_POST['auto_placeholder_be'] : 0) != 0);
    $data['move_stuff_be']        = (int)(intval(isset($_POST['move_stuff_be']) ? $_POST['move_stuff_be'] : 0) != 0);
    $data['replace_stuff_be']     = (int)(intval(isset($_POST['replace_stuff_be']) ? $_POST['replace_stuff_be'] : 0) != 0);
    $data['css_to_head_be']       = (int)(intval(isset($_POST['css_to_head_be']) ? $_POST['css_to_head_be'] : 0) != 0);
    $data['remove_comments_be']   = (int)(intval(isset($_POST['remove_comments_be']) ? $_POST['remove_comments_be'] : 0) != 0);
    $data['basic_html_minify_be'] = (int)(intval(isset($_POST['basic_html_minify_be']) ? $_POST['basic_html_minify_be'] : 0) != 0);

    
    
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

        //frontend
        $errmsg.=(string)Settings::Set("opf_droplets", $data['droplets']);
        $errmsg.=(string)Settings::Set("opf_auto_placeholder", $data['auto_placeholder']);
        $errmsg.=(string)Settings::Set("opf_move_stuff", $data['move_stuff']);
        $errmsg.=(string)Settings::Set("opf_replace_stuff", $data['replace_stuff']);
        $errmsg.=(string)Settings::Set("opf_css_to_head", $data['css_to_head']);
        $errmsg.=(string)Settings::Set("opf_email_filter", $data['email_filter']);
        $errmsg.=(string)Settings::Set("opf_mailto_filter", $data['mailto_filter']);
        $errmsg.=(string)Settings::Set("opf_js_mailto", $data['js_mailto']);
        $errmsg.=(string)Settings::Set("opf_at_replacement", $data['at_replacement']);
        $errmsg.=(string)Settings::Set("opf_dot_replacement", $data['dot_replacement']);
        $errmsg.=(string)Settings::Set("opf_wblink", $data['wblink']);
        $errmsg.=(string)Settings::Set("opf_short_url", $data['short_url']);
        $errmsg.=(string)Settings::Set("opf_sys_rel", $data['sys_rel']);     
        $errmsg.=(string)Settings::Set("opf_remove_comments", $data['remove_comments']);
        $errmsg.=(string)Settings::Set("opf_basic_html_minify", $data['basic_html_minify']);     
        
        //backend
        $errmsg.=(string)Settings::Set("opf_droplets_be", $data['droplets_be']);
        $errmsg.=(string)Settings::Set("opf_auto_placeholder_be", $data['auto_placeholder_be']);
        $errmsg.=(string)Settings::Set("opf_move_stuff_be", $data['move_stuff_be']);
        $errmsg.=(string)Settings::Set("opf_replace_stuff_be", $data['replace_stuff_be']);
        $errmsg.=(string)Settings::Set("opf_css_to_head_be", $data['css_to_head_be']);      
        $errmsg.=(string)Settings::Set("opf_remove_comments_be", $data['remove_comments_be']);
        $errmsg.=(string)Settings::Set("opf_basic_html_minify_be", $data['basic_html_minify_be']);     

        
        
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

    //frontend
    $data['droplets']          = Settings::Get('opf_droplets',1);
    $data['auto_placeholder']  = Settings::Get('opf_auto_placeholder',1);
    $data['move_stuff']        = Settings::Get('opf_move_stuff',1);
    $data['replace_stuff']     = Settings::Get('opf_replace_stuff',1);
    $data['css_to_head']       = Settings::Get('opf_css_to_head',1);
    $data['email_filter']      = Settings::Get('opf_email_filter',1);
    $data['mailto_filter']     = Settings::Get('opf_mailto_filter',1);
    $data['js_mailto']         = Settings::Get('opf_js_mailto',1);
    $data['at_replacement']    = Settings::Get('opf_at_replacement',"(at)");
    $data['dot_replacement']   = Settings::Get('opf_dot_replacement',"(dot)");
    $data['wblink']            = Settings::Get('opf_wblink',1);
    $data['short_url']         = Settings::Get('opf_short_url',0);    
    $data['sys_rel']           = Settings::Get('opf_sys_rel',1);
    $data['remove_comments']   = Settings::Get('opf_remove_comments',1);    
    $data['basic_html_minify'] = Settings::Get('opf_basic_html_minify',1);
 
    //backend
    $data['droplets_be']          = Settings::Get('opf_droplets_be',1);
    $data['auto_placeholder_be']  = Settings::Get('opf_auto_placeholder_be',1);
    $data['move_stuff_be']        = Settings::Get('opf_move_stuff_be',1);
    $data['replace_stuff_be']     = Settings::Get('opf_replace_stuff_be',1);
    $data['css_to_head_be']       = Settings::Get('opf_css_to_head_be',1);
    $data['remove_comments_be']   = Settings::Get('opf_remove_comments_be',1);    
    $data['basic_html_minify_be'] = Settings::Get('opf_basic_html_minify_be',1);


}

include($modulePath."templates/output_filter.tpl.php");
