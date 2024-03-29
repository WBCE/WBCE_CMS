<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-)
 * @category        opffilter
 * @package         OPF Auto Placeholder
 * @version         1.3.4
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.4.x
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

        require_once(WB_PATH.'/modules/mod_opf_auto_placeholder/upgrade.php');

        if(opf_is_registered('Auto Placeholder')) return TRUE; // filter already registered

        // install filter
        return opf_register_filter(array(
            'name' => 'Auto Placeholder',
            'type' => OPF_TYPE_PAGE_FIRST,
            'file' => '{SYSVAR:WB_PATH}/modules/mod_opf_auto_placeholder/filter.php',
            'funcname' => 'opff_mod_opf_auto_placeholder',
            'desc' => array(
				'EN' => "This filter adds placeholders (hooks) which can be used for code replacements or inserts. See https://help.wbce.org/pages/de/module-programmieren/platzhalter-hooks.php for more information.",
				'DE' => "Dieser Filter fügt Platzhalter (Hooks) ein, die für Code-Ersetzungen oder -Ergänzungen genutzt werden können. Siehe https://help.wbce.org/pages/de/module-programmieren/platzhalter-hooks.php für weitere Informationen."
			),
            'active' => (!class_exists('Settings') || (Settings::Get('opf_auto_placeholder', 1)==1))?1:0,
            'allowedit' => 0,
            'pages_parent' => 'all,backend,search'
        ));
    }
}

return FALSE;
