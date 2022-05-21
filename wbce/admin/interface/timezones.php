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

if (!defined('WB_URL')) {
    header('Location: ../../../index.php');
    exit(0);
}

// Define that this file is loaded
if (!defined('TIMEZONES_LOADED')) {
    define('TIMEZONES_LOADED', true);
}

// Create array
$TIMEZONES = array();

// time in seconds
global $actual_timezone;
$actual_timezone = ((int)TIMEZONE <> 0) ? (int)TIMEZONE / 3600 : 0;
$default_timezone = ((int)DEFAULT_TIMEZONE <> 0) ? (int)DEFAULT_TIMEZONE / 3600 : 0;

// Add values to list
$TIMEZONES['-12'] = 'UTC -12 Hours';
$TIMEZONES['-11'] = 'UTC -11 Hours';
$TIMEZONES['-10'] = 'UTC -10 Hours';
$TIMEZONES['-9'] = 'UTC -9 Hours';
$TIMEZONES['-8'] = 'UTC -8 Hours';
$TIMEZONES['-7'] = 'UTC -7 Hours';
$TIMEZONES['-6'] = 'UTC -6 Hours';
$TIMEZONES['-5'] = 'UTC -5 Hours';
$TIMEZONES['-4'] = 'UTC -4 Hours';
$TIMEZONES['-3'] = 'UTC -3 Hours';
$TIMEZONES['-2'] = 'UTC -2 Hours';
$TIMEZONES['-1'] = 'UTC -1 Hours';
$TIMEZONES['0'] = 'UTC';
$TIMEZONES['1'] = 'UTC +1 Hours';
$TIMEZONES['2'] = 'UTC +2 Hours';
$TIMEZONES['3'] = 'UTC +3 Hours';
$TIMEZONES['4'] = 'UTC +4 Hours';
$TIMEZONES['5'] = 'UTC +5 Hours';
$TIMEZONES['6'] = 'UTC +6 Hours';
$TIMEZONES['7'] = 'UTC +7 Hours';
$TIMEZONES['8'] = 'UTC +8 Hours';
$TIMEZONES['9'] = 'UTC +9 Hours';
$TIMEZONES['10'] = 'UTC +10 Hours';
$TIMEZONES['11'] = 'UTC +11 Hours';
$TIMEZONES['12'] = 'UTC +12 Hours';
$TIMEZONES['13'] = 'UTC +13 Hours';

// Add "System Default" to list (if we need to)
if (isset($user_time) && $user_time == true) {
    global $TEXT;
    foreach ($TIMEZONES as $k => $v) {
        if ($k == $default_timezone) {
            $TIMEZONES[$k] .= ' (' . $TEXT['SYSTEM_DEFAULT'] . ')';
        }
    }
}

// Reverse array so "System Default" is at the top
$TIMEZONES = array_reverse($TIMEZONES, true);

if (!function_exists('getTimeZonesArray')) {

    /**
     * @brief  Returns an array of timezones set up by the system
     *         This function will return an array that can be used
     *         to display all the timezones or in order to create a
     *         select box to choose from.
     *
     * @param array $TIMEZONES
     * @return array
     */
    function getTimeZonesArray($TIMEZONES)
    {
        global $actual_timezone;

        $aTimeZones = array();
        $i = 0;
        foreach ($TIMEZONES as $iOffset => $sTitle) {
            $aTimeZones[$i]['VALUE'] = $iOffset;
            $aTimeZones[$i]['NAME'] = $sTitle;

            $aTimeZones[$i]['SELECTED'] = false;
            if (TIMEZONE === $iOffset && !isset($_SESSION['USE_DEFAULT_TIMEZONE'])) {
                $aTimeZones[$i]['SELECTED'] = true;
            } elseif ($iOffset === $actual_timezone) {
                $aTimeZones[$i]['SELECTED'] = true;
            }
            $i++;
        }
        return $aTimeZones;
    }
}
