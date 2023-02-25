<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-)
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


if(defined('WB_URL'))
{
    // check whether outputfilter-module is installed
    if(file_exists(WB_PATH.'/modules/outputfilter_dashboard/functions.php')) {
        require_once(WB_PATH.'/modules/outputfilter_dashboard/functions.php');

        require_once(WB_PATH.'/modules/mod_opf_move_stuff/upgrade.php');

        if(opf_is_registered('Move Contents')) return TRUE; // filter already registered

        // install filter
        return opf_register_filter(array(
            'name' => 'Move Contents',
            'type' => OPF_TYPE_PAGE_LAST,
            'file' => '{SYSVAR:WB_PATH}/modules/mod_opf_move_stuff/filter.php',
            'funcname' => 'opff_mod_opf_move_stuff',
            'desc' => array(
				'EN' => "This filter is needed to move content or code to defined hooks. See https://help.wbce.org/pages/de/module-programmieren/platzhalter-hooks.php for details.",
				'DE' => "Verschiebt Code an die dafür vorgesehenen Stellen. Siehe https://help.wbce.org/pages/de/module-programmieren/platzhalter-hooks.php für Details."
			),
            'active' => (!class_exists('Settings') || (Settings::Get('opf_move_stuff', 1)==1))?1:0,
            'allowedit' => 0,
            'pages_parent' => 'all,backend,search'
        ))
        && opf_move_up_before(
            'Move Contents',
               'Replace Contents',
               'Cache Control'               
        );
    }
}

return FALSE;
