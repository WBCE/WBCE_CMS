<?php
/**
 *
 * @category        admin
 * @package         interface
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: time_formats.php 1374 2011-01-10 12:21:47Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/interface/time_formats.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10. Jan 2011) $
 *
 * Time format list file
 * This file is used to generate a list of time formats for the user to select
 *
 */

defined('WB_URL') or header('Location: ../../../index.php');

// Define that this file is loaded
defined('TIME_FORMATS_LOADED') or define('TIME_FORMATS_LOADED', true);
       
// Get the current time (in the users timezone if required)
$sShowTime = time() +  DEFAULT_TIMEZONE;
if(isset($user_time) && $user_time == true && TIMEZONE != '-72000'){ 
    $sShowTime = time() + TIMEZONE;  
}

// Create array
$TIME_FORMATS = array();
$TIME_FORMATS['g:i|A'] = gmdate('g:i A', $sShowTime);
$TIME_FORMATS['g:i|a'] = gmdate('g:i a', $sShowTime);
$TIME_FORMATS['H:i:s'] = gmdate('H:i:s', $sShowTime);
$TIME_FORMATS['H:i']   = gmdate('H:i',   $sShowTime);

// Add "System Default" to list (if we need to)
if(isset($user_time) AND $user_time == true) {
    $TIME_FORMATS['system_default'] = gmdate(DEFAULT_TIME_FORMAT, $sShowTime).' ('.$TEXT['SYSTEM_DEFAULT'].')';
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
            if( (TIME_FORMAT == $sFormat && !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) ||
            ('system_default' == $sFormat && isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) ){
                $aTimeFormats[$i]['SELECTED'] = true;
            } 
            $i++;
        }

        // debug_dump();
        return $aTimeFormats;
    }
}