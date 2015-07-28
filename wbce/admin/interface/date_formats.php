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
 * @version         $Id: date_formats.php 1413 2011-01-23 17:08:31Z FrankH $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/interface/date_formats.php $
 * @lastmodified    $Date: 2011-01-23 18:08:31 +0100 (So, 23. Jan 2011) $
 *
 * Date format list file
 * This file is used to generate a list of date formats for the user to select
 *
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
$DATE_FORMATS['l,|jS|F,|Y'] = gmdate('l, jS F, Y', $actual_time);
$DATE_FORMATS['jS|F,|Y'] = gmdate('jS F, Y', $actual_time);
$DATE_FORMATS['d|M|Y'] = gmdate('d M Y', $actual_time);
$DATE_FORMATS['M|d|Y'] = gmdate('M d Y', $actual_time);
$DATE_FORMATS['D|M|d,|Y'] = gmdate('D M d, Y', $actual_time);
$DATE_FORMATS['d-m-Y'] = gmdate('d-m-Y', $actual_time).' (D-M-Y)';
$DATE_FORMATS['m-d-Y'] = gmdate('m-d-Y', $actual_time).' (M-D-Y)';
$DATE_FORMATS['d.m.Y'] = gmdate('d.m.Y', $actual_time).' (D.M.Y)';
$DATE_FORMATS['m.d.Y'] = gmdate('m.d.Y', $actual_time).' (M.D.Y)';
$DATE_FORMATS['d/m/Y'] = gmdate('d/m/Y', $actual_time).' (D/M/Y)';
$DATE_FORMATS['m/d/Y'] = gmdate('m/d/Y', $actual_time).' (M/D/Y)';
$DATE_FORMATS['j.n.Y'] = gmdate('j.n.Y', $actual_time).' (j.n.Y)';

// Add "System Default" to list (if we need to)
if(isset($user_time) && $user_time == true)
{
	if(isset($TEXT['SYSTEM_DEFAULT']))
	{
		$DATE_FORMATS['system_default'] = gmdate(DEFAULT_DATE_FORMAT, $actual_time).' ('.$TEXT['SYSTEM_DEFAULT'].')';
	} else {
		$DATE_FORMATS['system_default'] = gmdate(DEFAULT_DATE_FORMAT, $actual_time).' (System Default)';
	}
}

// Reverse array so "System Default" is at the top
$DATE_FORMATS = array_reverse($DATE_FORMATS, true);

?>