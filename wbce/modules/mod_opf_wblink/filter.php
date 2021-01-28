<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 * @category        opffilter
 * @package         OPF WB-Link
 * @version         1.0.6
 * @authors         Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @platform        WBCE 1.2.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 */


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */



/*
 * replace all "[wblink{page_id}]" with real links
 */

function opff_mod_opf_wblink (&$content, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings') || Settings::Get('opf_wblink', true)){
        global $database;

        $pattern = '/\[wblink([0-9]+)\]/isU';
        if (preg_match_all($pattern, $content, $aMatches, PREG_SET_ORDER))
        {
            $aSearchReplaceList = array();
            foreach ($aMatches as $aMatch) {
                 // collect matches formatted like '[wblink123]' => 123
                $aSearchReplaceList[strtolower($aMatch[0])] = $aMatch[1];
            }
            // build list of PageIds for SQL query
            $sPageIdList = implode(',', $aSearchReplaceList); // '123,124,125'
            // replace all PageIds with '#' (stay on page death link)
            array_walk($aSearchReplaceList, function(&$value, $index){ $value = '#'; });
            $sql = 'SELECT `page_id`, `link` FROM `'.TABLE_PREFIX.'pages` '
                 . 'WHERE `page_id` IN('.$sPageIdList.')';
            if (($oPages = $database->query($sql))) {
                while (($aPage = $oPages->fetchRow())) {
                    $aPage['link'] = ($aPage['link']
                                     ? PAGES_DIRECTORY.$aPage['link'].PAGE_EXTENSION
                                     : '#');
                    // collect all search-replace pairs with valid links
                    if (is_readable(WB_PATH.$aPage['link'])) {
                        // replace death link with found and valide link
                        $aSearchReplaceList['[wblink'.$aPage['page_id'].']'] =
                            WB_URL.$aPage['link'];
                    }
                }
            }
            // replace all found [wblink**] tags with their urls
            $content = str_ireplace(
                array_keys($aSearchReplaceList),
                $aSearchReplaceList,
                $content
            );
        }
    }
    return(TRUE);
}

