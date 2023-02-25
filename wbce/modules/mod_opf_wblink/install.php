<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright       Ryan Djurovich (2004-2009)
 * @copyright       WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-)
 * @category        opffilter
 * @package         OPF Internal Link Replacer
 * @version         1.0.7
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

        require_once(WB_PATH.'/modules/mod_opf_wblink/upgrade.php');

        if(opf_is_registered('Internal Link Replacer')) return TRUE; // filter already registered

        // install filter
        return opf_register_filter(array(
            'name' => 'Internal Link Replacer',
            'type' => OPF_TYPE_PAGE,
            'file' => '{SYSVAR:WB_PATH}/modules/mod_opf_wblink/filter.php',
            'funcname' => 'opff_mod_opf_wblink',
            'desc' => array(
				'EN' => "When this filter is active, internal links can be set with the shortcode [wblinkXX] as URL (i.e. [wblink12]  = URL of the page with the page-ID 12).",
				'DE' => "Ist dieser Filter aktiviert, können Adressen von internen Seiten als [wblinkXX] angegeben werden (z.B. [wblink12] = URL der seite mit der Page-ID 12)."
			),
            'active' => (!class_exists('Settings') || (Settings::Get('opf_wblink', 1)==1))?1:0,
            'allowedit' => 0,
            'pages_parent' => 'all,search'
        ));
    }
}

return FALSE;
