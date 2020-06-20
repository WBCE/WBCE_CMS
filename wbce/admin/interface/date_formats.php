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
if(!defined('DATE_FORMATS_LOADED')) {
    define('DATE_FORMATS_LOADED', true);
}

// Create array
$DATE_FORMATS = array();

// Get the current time (in the users timezone if required)
$actual_time = time()+ ((isset($user_time) && $user_time == true) ? TIMEZONE : DEFAULT_TIMEZONE);

// Add values to list
$DATE_FORMATS['l,|jS|F,|Y'] = date('l, jS F, Y', $actual_time);
$DATE_FORMATS['jS|F,|Y']    = date('jS F, Y',    $actual_time);
$DATE_FORMATS['d|M|Y']      = date('d M Y',      $actual_time);
$DATE_FORMATS['M|d|Y']      = date('M d Y',      $actual_time);
$DATE_FORMATS['D|M|d,|Y']   = date('D M d, Y',   $actual_time);
$DATE_FORMATS['d-m-Y']      = date('d-m-Y',      $actual_time).' (D-M-Y)';
$DATE_FORMATS['m-d-Y']      = date('m-d-Y',      $actual_time).' (M-D-Y)';
$DATE_FORMATS['d.m.Y']      = date('d.m.Y',      $actual_time).' (D.M.Y)';
$DATE_FORMATS['m.d.Y']      = date('m.d.Y',      $actual_time).' (M.D.Y)';
$DATE_FORMATS['d/m/Y']      = date('d/m/Y',      $actual_time).' (D/M/Y)';
$DATE_FORMATS['m/d/Y']      = date('m/d/Y',      $actual_time).' (M/D/Y)';
$DATE_FORMATS['j.n.Y']      = date('j.n.Y',      $actual_time).' (j.n.Y)';

// Add "System Default" to list (if we need to)
if(isset($user_time) && $user_time == true) {
    global $TEXT;
    $DATE_FORMATS['system_default'] = date(DEFAULT_DATE_FORMAT, $actual_time).' ('.$TEXT['SYSTEM_DEFAULT'].')';
}

// Reverse array so "System Default" is at the top
$DATE_FORMATS = array_reverse($DATE_FORMATS, true);

if(!function_exists('getDateFormatsArray')){
    
    /**
     * @brief  Returns an array of date formats set up by the system
     *         This function will return an array that can be used
     *         to display all the date formats or in order to create  
     *         a select box to choose from.
     * 
     * @param  array  $DATE_FORMATS
     * @return array
     */
    function getDateFormatsArray($DATE_FORMATS){
        $aDateFormats = array();
        $i = 0;
        foreach ($DATE_FORMATS as $sFormat => $sTitle) {
            $sFormat = str_replace('|', ' ', $sFormat); // Adds white-spaces (not able to be stored in array key)

            $aDateFormats[$i]['VALUE'] = ($sFormat != 'system_default') ? $sFormat : '';
            $aDateFormats[$i]['NAME']  = $sTitle;

            $aDateFormats[$i]['SELECTED'] = false;
            if (DATE_FORMAT == $sFormat && !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
                $aDateFormats[$i]['SELECTED'] = true;
            } elseif ($sFormat == 'system_default' && isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
                $aDateFormats[$i]['SELECTED'] = true;
            }
            $i++;
        }
        return $aDateFormats;
    }
}