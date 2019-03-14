<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-2019)
 * @category        opffilter
 * @package         OPF Remove System PH
 * @version         1.1.2
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


if(defined('WB_URL'))
{
    // check whether outputfilter-module is installed
    if(file_exists(WB_PATH.'/modules/outputfilter_dashboard/functions.php')) {
        require_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');

        require_once(WB_PATH.'/modules/mod_opf_remove_system_ph/upgrade.php');

        if(opf_is_registered('Remove System PH')) return TRUE; // filter already registered

        // install filter
        return opf_register_filter(array(
            'name' => 'Remove System PH',
            'type' => OPF_TYPE_PAGE_FINAL,
            'file' => '{SYSVAR:WB_PATH}/modules/mod_opf_remove_system_ph/filter.php',
            'funcname' => 'opff_mod_opf_remove_system_ph',
            'desc' => "remove system ph markers ",
            'active' => (!class_exists('Settings') || (Settings::Get('opf_remove_system_ph', 1)==1))?1:0,
            'allowedit' => 0,
            'pages_parent' => 'all,backend'
        ));
    }
}

return FALSE;
