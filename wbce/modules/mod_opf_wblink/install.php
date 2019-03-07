<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       Ryan Djurovich (2004-2009)
 * @copyright       WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2019)
 * @category        opffilter
 * @package         OPF WB-Link
 * @version         1.0.2
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

        $upgrade_result=require_once(WB_PATH.'/modules/mod_opf_wblink/upgrade.php');
        if($upgrade_result==FALSE) return FALSE;
        if(opf_is_registered('WB-Link')){ // filter already registered
            return TRUE;
        }

        // install filter
        return opf_register_filter(array(
            'name' => 'WB-Link',
            'type' => OPF_TYPE_PAGE,
            'file' => '{SYSVAR:WB_PATH}/modules/mod_opf_wblink/filter.php',
            'funcname' => 'opff_mod_opf_wblink',
            'desc' => "change internal wblinkxx into real URLs",
            'active' => (!class_exists('Settings') || (Settings::Get('opf_wblink', 1)==1))?1:0,
            'allowedit' => 0
        ))
        && opf_move_up_before(
            'WB-Link',
            array(
               'Short URL',
               'Sys Rel'
            )
        );
    }
}

return FALSE;
