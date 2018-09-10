<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-2018)
 * @category        opffilter
 * @package         OPF Auto Placeholder
 * @version         1.2.1
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



function opff_mod_opf_auto_placeholder (&$sContent, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings')
        || (Settings::Get('opf_auto_placeholder', true) && ($page_id != 'backend'))
        || (Settings::Get('opf_auto_placeholder'.'_be', true) && ($page_id == 'backend'))){

        if (class_exists('Insert')){
                $sContent = I::addPlaceholdersToDom($sContent);
                $sContent = I::doFilter($sContent);
        } else {

            // Template does not want placeholders to be populated on automatic?
            // OK, return right away.
            if (strpos($sContent,'<!--(NO PH)-->') !== false) {
                        return true;
            }

            // While working with jQuery and other JS Libraries it's important to have its
            //    CSS files added before the actual JS code.
            //    We have taken care of it using the proper order of placeholders.
            $aPlaceholders = array(
                'JS HEAD TOP' => array(
                    "/<\s*meta[^>]*?charset.*?\/?\s*>/si",
                    "$0\n<!--(PH) JS HEAD TOP+ -->\n<!--(PH) JS HEAD TOP- -->\n"
                ),
                'CSS HEAD TOP' => array(
                    "/<\s*meta[^>]*?charset.*?\/?\s*>/si",
                    "$0\n<!--(PH) CSS HEAD TOP+ -->\n<!--(PH) CSS HEAD TOP- -->\n"
                ),
                'CSS HEAD BTM' => array(
                    "#<\s*/\s*head\s*>#iU",
                    "\n<!--(PH) CSS HEAD BTM+ -->\n<!--(PH) CSS HEAD BTM- -->\n$0"
                ),
                'JS HEAD BTM' => array(
                    "#<\s*/\s~head\s*>#iU",
                    "\n<!--(PH) JS HEAD BTM+ -->\n<!--(PH) JS HEAD BTM- -->\n$0"
                ),
                'HTML BODY TOP' => array(
                    "/<\s*body.*>/iU",
                    "$0\n<!--(PH) HTML BODY TOP+ -->\n<!--(PH) HTML BODY TOP- -->\n"
                ),
                'JS BODY TOP' => array(
                    "/<\s*body.*>/iU",
                    "$0\n<!--(PH) JS BODY TOP+ -->\n<!--(PH) JS BODY TOP- -->\n"
                ),
                'HTML BODY BTM' => array(
                    "#<\s*/\s~body\s*>#iU",
                    "\n<!--(PH) HTML BODY BTM+ -->\n<!--(PH) HTML BODY BTM- -->\n$0"
                ),
                'JS BODY BTM' => array(
                    "#<\s*/\s*body\s*>#iU",
                    "\n<!--(PH) JS BODY BTM+ -->\n<!--(PH) JS BODY BTM- -->\n$0"
                ),
                'META HEAD' => array(
                    "/<\s*meta[^>]*?charset.*?\/?\s*>/si",
                    "\n<!--(PH) META HEAD+ -->\n<!--(PH) META HEAD- -->\n$0"
                ),
                'TITLE' => array(
                    "/<\s*title.*?<\s*\/\s*title\s*>/si",
                    "<!--(PH) TITLE+ -->$0<!--(PH) TITLE- -->"
                ),
                'META DESC' => array(
                    "/<\s*meta[^>]*?\=\"description\".*?\/?\s*>/si",
                    "<!--(PH) META DESC+ -->$0<!--(PH) META DESC- -->"
                ),
                'META KEY' => array(
                    "/<\s*meta[^>]*?\=\"keywords\".*?\/?\s*>/si",
                    "<!--(PH) META KEY+ -->$0<!--(PH) META KEY- -->"
                )
            );

            // populate Placeholders in $sContent
            foreach ($aPlaceholders as $sPlaceholder => $rec) {
                $sPlaceholder = '<!--(PH) ' . $sPlaceholder . '+ -->';
                if (strpos($sContent, $sPlaceholder) === false)
                    $sContent = preg_replace($rec[0], $rec[1], $sContent);
            }
        }
    }
    return(TRUE);
}

