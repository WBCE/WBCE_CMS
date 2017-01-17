<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2017)
 * @category        opffilter
 * @package         OPF Droplets
 * @version         1.0.0
 * @authors         Martin Hecht (mrbaseman)
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


if(defined('WB_URL'))
{
    // check whether outputfilter-module is installed 
    if(file_exists(WB_PATH.'/modules/outputfilter_dashboard/functions.php')) {
        require_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');

        if(opf_is_registered('OPF Droplets')){
            return require(WB_PATH.'/modules/mod_opf_droplets/upgrade.php');
        }
        
        // install filter
        opf_register_filter(array(
            'name' => 'OPF Droplets',
            'type' => OPF_TYPE_PAGE,
            'file' => '{SYSVAR:WB_PATH}/modules/mod_opf_droplets/filter.php',
            'funcname' => 'opff_mod_opf_droplets',
            'desc' => "This filter module is a replacement for the former output_filter to be used with OpF",
            'active' => 1,
            'allowedit' => 0,
            'pages_parent' => 'all,backend'
        ));
        // move up to the top
        opf_move_up_before('OPF Droplets');

        // opf before 1.5.1 did not register the setting:
        if(class_exists('Settings')) Settings::Set('opf_opf_droplets',1, false);
        if(class_exists('Settings')) Settings::Set('opf_opf_droplets'.'_be',1, false);
     }
}

