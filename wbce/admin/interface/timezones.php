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
 * @version         $Id: timezones.php 1374 2011-01-10 12:21:47Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/interface/timezones.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10. Jan 2011) $
 *
 * Timezone list file
 * This file is used to generate a list of timezones for the user to select
 *
 */

if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

// Create array
$TIMEZONES = array();
$actual_timezone = ( DEFAULT_TIMEZONE <> 0 ) ? DEFAULT_TIMEZONE/3600 : 0;

$TIMEZONES['-12'] = 'GMT - 12 Hours';
$TIMEZONES['-11'] = 'GMT -11 Hours';
$TIMEZONES['-10'] = 'GMT -10 Hours';
$TIMEZONES['-9'] = 'GMT -9 Hours';
$TIMEZONES['-8'] = 'GMT -8 Hours';
$TIMEZONES['-7'] = 'GMT -7 Hours';
$TIMEZONES['-6'] = 'GMT -6 Hours';
$TIMEZONES['-5'] = 'GMT -5 Hours';
$TIMEZONES['-4'] = 'GMT -4 Hours';
$TIMEZONES['-3.5'] = 'GMT -3.5 Hours';
$TIMEZONES['-3'] = 'GMT -3 Hours';
$TIMEZONES['-2'] = 'GMT -2 Hours';
$TIMEZONES['-1'] = 'GMT -1 Hour';
$TIMEZONES['0'] = 'GMT';
$TIMEZONES['1'] = 'GMT +1 Hour';
$TIMEZONES['2'] = 'GMT +2 Hours';
$TIMEZONES['3'] = 'GMT +3 Hours';
$TIMEZONES['3.5'] = 'GMT +3.5 Hours';
$TIMEZONES['4'] = 'GMT +4 Hours';
$TIMEZONES['4.5'] = 'GMT +4.5 Hours';
$TIMEZONES['5'] = 'GMT +5 Hours';
$TIMEZONES['5.5'] = 'GMT +5.5 Hours';
$TIMEZONES['6'] = 'GMT +6 Hours';
$TIMEZONES['6.5'] = 'GMT +6.5 Hours';
$TIMEZONES['7'] = 'GMT +7 Hours';
$TIMEZONES['8'] = 'GMT +8 Hours';
$TIMEZONES['9'] = 'GMT +9 Hours';
$TIMEZONES['9.5'] = 'GMT +9.5 Hours';
$TIMEZONES['10'] = 'GMT +10 Hours';
$TIMEZONES['11'] = 'GMT +11 Hours';
$TIMEZONES['12'] = 'GMT +12 Hours';
$TIMEZONES['13'] = 'GMT +13 Hours';

// Add "System Default" to list (if we need to)
if(isset($user_time) && $user_time == true)
{
	if(isset($TEXT['SYSTEM_DEFAULT']))
	{
		$TIMEZONES['system_default'] = $TIMEZONES[$actual_timezone].' ('.$TEXT['SYSTEM_DEFAULT'].')';
	} else {
		$TIMEZONES['system_default'] = $TIMEZONES[$actual_timezone].' (System Default)';
	}
}

// Reverse array so "System Default" is at the top
$TIMEZONES = array_reverse($TIMEZONES, true);

?>