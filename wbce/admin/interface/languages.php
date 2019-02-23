<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (since 2015)
 * @license GNU GPL2 (or any later version)
 *
 * function to get all languages that are in use on the installation
 */

$aLanguages = array();

if(!function_exists('getDateFormatsArray')){
    
    /**
     * @brief  Returns an array of all the languages active on the 
     *         WBCE CMS installation.
     *         This function will return an array that can be used
     *         to display all languages as a list or in order to   
     *         create a select box to choose from.
     * 
     * @param  array  $DATE_FORMATS
     * @return array
     */
    function getLanguagesArray(){
        global $database;
        $aLanguages = array();
        if ($rLang = $database->query("SELECT * FROM `{TP}addons` WHERE `type` = 'language' ORDER BY `directory`")) {
            while ($rec = $rLang->fetchRow(MYSQL_ASSOC)){
                $sLC = $rec['directory'];
                $aLanguages[$sLC]['CODE']     = $sLC;
                $aLanguages[$sLC]['NAME']     = $rec['name'];
                $aLanguages[$sLC]['FLAG']     = WB_URL.'/languages/'.(empty($sLC)) ? 'none' : strtolower($sLC);
                $aLanguages[$sLC]['SELECTED'] = LANGUAGE == $sLC ? true : false;
            }
        }
        return $aLanguages;
    }
}
