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
 * Convert full qualified, local URLs into relative URLs
 */

function opff_mod_opf_short_url (&$content, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings') || Settings::Get('opf_short_url', true)){
        // $GLOBALS['wb']->preprocess($content); // this line is obsolete IMHO
        $sUrlStart = WB_URL.PAGES_DIRECTORY;
        $sUrlEnd = PAGE_EXTENSION;
        $sNewUrlStart = WB_URL;
        $sNewUrlEnd = '/';

        preg_match_all('~'.$sUrlStart.'(.*?)\\'.$sUrlEnd.'~', $content, $aLinks);
        foreach ($aLinks[1] as $sLink) {
            $content = str_replace(
                $sUrlStart.$sLink.$sUrlEnd,
                $sNewUrlStart.$sLink.$sNewUrlEnd,
                $content
            );
        }
    }
    return(TRUE);
}

