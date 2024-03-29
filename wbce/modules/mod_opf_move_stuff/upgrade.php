<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-2019)
 * @category        opffilter
 * @package         OPF Move Contents
 * @version         1.0.8
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.3.x
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


//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

if(!class_exists('Settings')) return FALSE;

Settings::Set('opf_move_stuff',1, false);
Settings::Set('opf_move_stuff'.'_be',1, false);

include_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');

if(!opf_is_registered('Move Contents')) return FALSE;

if(opf_get_type('Move Contents',FALSE) != OPF_TYPE_PAGE_LAST){
    return opf_unregister_filter('Move Contents')
    && require(WB_PATH.'/modules/mod_opf_move_stuff/install.php');
}

return TRUE;
