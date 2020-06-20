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
 */

if(!defined('WB_URL')) {
    header('Location: ../../../index.php');
    exit(0);
}

// Define that this file is loaded
if(!defined('TIME_FORMATS_LOADED')) {
    define('TIME_FORMATS_LOADED', true);
}

// Create array
$TIME_FORMATS = array();

// Get the current time (in the users timezone if required)
$sShowTime = time()+ ((isset($user_time) && $user_time == true) ? TIMEZONE : DEFAULT_TIMEZONE);

// Add values to list
$TIME_FORMATS['g:i|A'] = date('g:i A', $sShowTime);
$TIME_FORMATS['g:i|a'] = date('g:i a', $sShowTime);
$TIME_FORMATS['H:i:s'] = date('H:i:s', $sShowTime);
$TIME_FORMATS['H:i']   = date('H:i',   $sShowTime);

// Add "System Default" to list (if we need to)
if(isset($user_time) && $user_time == true) {
    global $TEXT;
    $TIME_FORMATS['system_default'] = date(DEFAULT_TIME_FORMAT, $sShowTime).' ('.$TEXT['SYSTEM_DEFAULT'].')';
}

// Reverse array so "System Default" is at the top
$TIME_FORMATS = array_reverse($TIME_FORMATS, true);

if(!function_exists('getTimeFormatsArray')){
    
    /**
     * @brief  Returns an array of time formats set up by the system
     *         This function will return an array that can be used
     *         to display all the time formats or in order to create  
     *         a select box to choose from.
     * 
     * @param  array  $TIME_FORMATS
     * @return array
     */
    function getTimeFormatsArray($TIME_FORMATS){
        $aTimeFormats = array();
        $i = 0;
        foreach ($TIME_FORMATS as $sFormat => $sTitle) {
            $sFormat = str_replace('|', ' ', $sFormat); // Adds white-spaces (not able to be stored in array key)

            $aTimeFormats[$i]['VALUE'] = ($sFormat != 'system_default') ? $sFormat : '';
            $aTimeFormats[$i]['NAME']  = $sTitle;

            $aTimeFormats[$i]['SELECTED'] = false;
            if (TIME_FORMAT == $sFormat && !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
                $aTimeFormats[$i]['SELECTED'] = true;
            } elseif ($sFormat == 'system_default' && isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
                $aTimeFormats[$i]['SELECTED'] = true;
            }
            $i++;
        }
        return $aTimeFormats;
    }
}