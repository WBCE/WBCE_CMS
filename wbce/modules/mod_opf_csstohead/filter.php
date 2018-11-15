<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       Ryan Djurovich (2004-2009)
 * @copyright       WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2018)
 * @category        opffilter
 * @package         OPF CSS to head
 * @version         1.0.3
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
 * moves all css definitions from <body> into <head> section
 */
function opff_mod_opf_csstohead (&$sContent, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings')
        || (Settings::Get('opf_css_to_head', true) && ($page_id != 'backend'))
        || (Settings::Get('opf_css_to_head'.'_be', true) && ($page_id == 'backend'))){
        $sToPlaceHolder="<!--(PH) CSS HEAD BTM- -->";

        // move css definitions into head section
        $sPattern1 = '/(?:<\s*body.*?)(<\s*link[^>]*?\"text\/css\".*?\/?\s*>)/si';
        $sPattern3 = '/(?:<\s*body.*?)(<\s*link[^>]*?\"stylesheet\".*?\/?\s*>)/si';
        $sPattern2 = '/(?:<\s*body.*?)(<\s*style.*?<\s*\/\s*style\s*>)/si';


        $aInsert = array();
        while(preg_match($sPattern1, $sContent, $aMatches)) {
            $aInsert[] = $aMatches[1];
            $sContent = str_replace($aMatches[1], '', $sContent);
        }
        while(preg_match($sPattern3, $sContent, $aMatches)) {
            $aInsert[] = $aMatches[1];
            $sContent = str_replace($aMatches[1], '', $sContent);
        }
        while(preg_match($sPattern2, $sContent, $aMatches)) {
            $aInsert[] = $aMatches[1];
            $sContent = str_replace($aMatches[1], '', $sContent);
        }
        $aInsert = array_unique($aInsert);
        if(sizeof($aInsert) > 0) {
            $sInsert = "\n".implode("\n", $aInsert)."\n".$sToPlaceHolder;
            $sContent = preg_replace('/'.preg_quote($sToPlaceHolder).'/si', $sInsert, $sContent, 1);
        }
    }
    return(TRUE);
}

