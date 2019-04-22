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
 * @package         OPF Short URL
 * @version         1.0.5
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

        require_once(WB_PATH.'/modules/mod_opf_short_url/upgrade.php');

        if(opf_is_registered('Short URL')) return TRUE; // filter already registered

        // install filter
        return opf_register_filter(array(
            'name' => 'Short URL',
            'type' => OPF_TYPE_PAGE_LAST,
            'file' => '{SYSVAR:WB_PATH}/modules/mod_opf_short_url/filter.php',
            'funcname' => 'opff_mod_opf_short_url',
            'desc' => "short url via filter (instead of a droplet)",
            'active' => (class_exists('Settings') && (Settings::Get('opf_short_url', 0)==0))?0:1,
            'allowedit' => 0
        ))
        && opf_move_up_before(
            'Short URL',
            'Sys Rel'
        );
    }
}

return FALSE;
