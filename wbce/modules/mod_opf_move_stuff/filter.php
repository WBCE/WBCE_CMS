<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       WBCE Project (2015-2019)
 * @category        opffilter
 * @package         OPF Move Stuff
 * @version         1.0.7
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



function opff_mod_opf_move_stuff (&$sContent, $page_id, $section_id, $module, $wb) {
    if(!class_exists('Settings')
        || (Settings::Get('opf_move_stuff', true) && ($page_id != 'backend'))
        || (Settings::Get('opf_move_stuff'.'_be', true) && ($page_id == 'backend'))){

        // Templates does not want any movement ?
        if (strpos($sContent,'<!--(NO MOVE)-->') !== false) {return TRUE;}

        // Do we have any placeholders to move to ?
        if (strpos($sContent,'<!--(PH)') === false) {return TRUE;}

        // Do we have any stuff to move, if not abort?
        if (strpos($sContent,'<!--(MOVE)') === false) {return TRUE;}

        // Does the stuf has at least one end, if not abort?
        if (strpos($sContent,'<!--(END)-->') === false) {return TRUE;}


        // As recursion is not allowed and makes no sense anyway , we can fetch all moves in one regex.

        // The regex fetches this:
        // <!--(MOVE)(Content1) -->(Content2)<!--(END)-->
        // Arbeitet auf dem gesamten String(s)->Zeilen werden nicht beachtet und ist Ungreedy
        $sRegex = '/\s*?\<\!\-\-\(MOVE\)\ (.+([\+\-]))\ \-\-\>(.+)\<\!\-\-\(END\)\-\-\>\s*?/sU';
        preg_match_all($sRegex, $sContent, $aMatches);


        // Runn through the Array and remove old content
        foreach ($aMatches[0] as $iKey=>$sOldEntry) {

            // Remove the Old Entry
            $sContent = str_replace($sOldEntry, '', $sContent);
        }

        // Remove exact Doubles
        foreach ($aMatches[0] as $iKey=>$sEntry) {
            foreach ($aMatches[0] as $iOKey=>$sOEntry){
                // found entry whith exact match
                if (trim($sEntry) == trim($sOEntry) AND $iKey != $iOKey){
                    //Remove the old one
                    if (isset($aMatches[0][$iKey])) unset ($aMatches[0][$iKey]);
                    if (isset($aMatches[1][$iKey])) unset ($aMatches[1][$iKey]);
                    if (isset($aMatches[2][$iKey])) unset ($aMatches[2][$iKey]);
                    if (isset($aMatches[3][$iKey])) unset ($aMatches[3][$iKey]);
                    if (isset($aMatches[4][$iKey])) unset ($aMatches[4][$iKey]);
                    continue 2;
                }
            }
        }


        // Runn through the Array Again now Put in stuff
        foreach ($aMatches[0] as $iKey=>$sOldEntry) {

            // fetch the right Placeholder
            $sToPlaceHolder="<!--(PH) ".$aMatches[1][$iKey]." -->";

            // Add at begin or end of placeholder block
            if ($aMatches[2][$iKey]=="+") {

                // here we add to the beginning of the block
                $sInsert = $sToPlaceHolder.
                           "\n".
                           trim($aMatches[3][$iKey]);

            } else {

                // here we add to the end of the block
               $sInsert = trim($aMatches[3][$iKey]).
                          "\n".
                          $sToPlaceHolder;

            }
            $sContent = preg_replace('/'.preg_quote($sToPlaceHolder).'/s', $sInsert, $sContent,1);

        }

        return(TRUE);
    }
}
