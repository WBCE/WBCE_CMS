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
 * @package         OPF Sys Rel
 * @version         1.0.11
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


/**
 * Convert full qualified, local URLs into relative URLs
 */

function opff_mod_opf_sys_rel (&$content, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings') || Settings::Get('opf_sys_rel', true)){
        $sAppUrl  = rtrim(str_replace('\\', '/', WB_URL), '/').'/';
        $sAppPath = rtrim(str_replace('\\', '/', WB_PATH), '/').'/';
        $content = preg_replace_callback(
            '/((?:href|src)\s*=\s*")([^?][^\?\"]*?)/isU',
            function ($aMatches) use ($sAppUrl, $sAppPath) {
                $sAppRel = preg_replace('/^https?:\/\/[^\/]*(.*)$/is', '$1', $sAppUrl);
                $aMatches[2] = str_replace('\\', '/', $aMatches[2]);
                $aMatches[2] = preg_replace('/^'.preg_quote($sAppUrl, '/').'/is', '', $aMatches[2]);
                $aMatches[2] = preg_replace('/(\.+\/)|(\/+)/', '/', $aMatches[2]);
                if (!is_readable($sAppPath.$aMatches[2])) {
                // in case of death link show original link
                    return $aMatches[0];
                } else {
                    return  preg_replace('/(?<!:)\/+/','/',$aMatches[1].$sAppRel.$aMatches[2]);
                }
            },
            $content
        );

    }
    return(TRUE);
}

