<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Must include code to stop this file being access directly
defined('WB_PATH') or die("Cannot access this file directly"); 

// Check FTAN
if (!$wb->checkFTAN()) {
    $wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
}

// Get entered values
$display_name = $wb->add_slashes(strip_tags($admin->get_post('display_name')));
$language     = preg_match('/^[a-z]{2}$/si', $wb->get_post('language')) ? $wb->get_post('language') : 'EN';
$timezone     = is_numeric($wb->get_post('timezone')) ? $wb->get_post('timezone')*60*60 : 0;
$date_format  = $wb->get_post('date_format');
$time_format  = $wb->get_post('time_format');

// Update user data
$aUpdate = array(
	'user_id'      => $wb->get_user_id(),
	'display_name' => $database->escapeString($display_name),
	'language'     => $database->escapeString($language),
	'timezone'     => $database->escapeString($timezone),
	'date_format'  => $database->escapeString($date_format),
	'time_format'  => $database->escapeString($time_format),
);
$database->updateRow('{TP}users', 'user_id', $aUpdate);
	
if($database->is_error()) {
	$error[] = $database->get_error();
} else {
	$success[] = $TXT_ACCOUNT['DETAILS_SAVED'];
	
	// update SESSION values
	$_SESSION['DISPLAY_NAME'] = $display_name;
	$_SESSION['LANGUAGE']     = $language;
	$_SESSION['TIMEZONE']     = $timezone;
	$_SESSION['HTTP_REFERER'] = (($_SESSION['LANGUAGE']== LANGUAGE) ? $_SESSION['HTTP_REFERER'] : WB_URL);
	
	// Update date format
	if($date_format != '') {
		$_SESSION['DATE_FORMAT'] = $date_format;
		if(isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) { 
			unset($_SESSION['USE_DEFAULT_DATE_FORMAT']); 
		}
	} else {
		$_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
		if(isset($_SESSION['DATE_FORMAT'])) { 
			unset($_SESSION['DATE_FORMAT']); 
		}
	}
	
	// Update time format
	if($time_format != '') {
		$_SESSION['TIME_FORMAT'] = $time_format;
		if(isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) { 
			unset($_SESSION['USE_DEFAULT_TIME_FORMAT']); 
		}
	} else {
		$_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
		if(isset($_SESSION['TIME_FORMAT'])) { 
			unset($_SESSION['TIME_FORMAT']); 
		}
	}
}
