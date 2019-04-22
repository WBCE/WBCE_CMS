<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-2019)
 * @category        opffilter
 * @package         OPF Replace Stuff
 * @version         1.0.6
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



function opff_mod_opf_replace_stuff (&$sContent, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings')
        || (Settings::Get('opf_replace_stuff', true) && ($page_id != 'backend'))
        || (Settings::Get('opf_replace_stuff'.'_be', true) && ($page_id == 'backend'))){

        // Template does not want any replacement ?
        if (strpos($sContent,'<!--(NO REPLACE)-->') !== false) {return TRUE;}

        // Do we have any placeholders to move to ?
        if (strpos($sContent,'<!--(PH)') === false) {return TRUE;}

        // Do we have any stuff to move, if not abort?
        if (strpos($sContent,'<!--(REPLACE)') === false) {return TRUE;}

        // Does the stuf has at least one end, if not abort?
        if (strpos($sContent,'<!--(END)-->') === false) {return TRUE;}

        // As recursion is not allowed and makes no sense anyway , we can fetch all replacements in one regex.

        // The regex fetches this:
        // <!--(MOVE)(Content1) -->(Content2)<!--(END)-->
        // Arbeitet auf dem gesamten String(s)->Zeilen werden nicht beachtet und ist Ungreedy
        $sRegex = '/\s*?\<\!\-\-\(REPLACE\)\ (.+)\ \-\-\>(.+)\<\!\-\-\(END\)\-\-\>\s*?/sU';
        preg_match_all($sRegex, $sContent, $aMatches);

        //print_r($aMatches);

        // Runn through the Array
        foreach ($aMatches[0] as $iKey=>$sOldEntry) {

            // Remove the Old Entry
            $sContent = str_replace($sOldEntry, '', $sContent);

            // fetch the right Placeholder
            $sPlaceHolder1="<!--(PH) ".$aMatches[1][$iKey]."+ -->";
            $sPlaceHolder2="<!--(PH) ".$aMatches[1][$iKey]."- -->";

            $sInsert = $sPlaceHolder1.trim($aMatches[2][$iKey]).$sPlaceHolder2 ;

            $sContent = preg_replace('/'.preg_quote($sPlaceHolder1).'.*'.preg_quote($sPlaceHolder2).'/sU', $sInsert, $sContent,1);

        }
    }

    return(TRUE);
}

