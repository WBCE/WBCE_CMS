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


//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


// general stuff
Settings::Set('opf_show_advanced_backend',false,false);


//frontend
Settings::Set('opf_droplets',1, false);
Settings::Set('opf_auto_placeholder',1, false);
Settings::Set('opf_move_stuff',1, false);
Settings::Set('opf_replace_stuff',1, false);
Settings::Set('opf_css_to_head',1, false);
Settings::Set('opf_wblink',1, false);
Settings::Set('opf_short_url',0, false);
Settings::Set('opf_sys_rel',0, false);
Settings::Set("opf_remove_system_ph", 1, false)  ;

//backend
Settings::Set('opf_droplets_be',1, false);
Settings::Set('opf_auto_placeholder_be',1, false);
Settings::Set('opf_move_stuff_be',1, false);
Settings::Set('opf_replace_stuff_be',1, false);
Settings::Set('opf_css_to_head_be',0);
Settings::Set("opf_remove_system_ph_be", 1, false)    ;
