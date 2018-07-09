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

// prevent this file from being accesses directly
defined('WB_PATH')or exit("Cannot access this file directly");

// check if user authenticated
if ($wb->is_authenticated() === false) {
	// User needs to login first
    header("Location: " . WB_URL . "/account/login.php?redirect=" . $wb->link);
    exit(0);
}

// get referer link for use with [cancel] button
$sHttpReferer = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $_SERVER['SCRIPT_NAME'];
$sFTAN = $wb->getFTAN();
$user_time = true;


// load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

// load UTF-8 functions
require_once WB_PATH . '/framework/functions-utf8.php';

$error = array();
$success = array();

switch ($wb->get_post('action')) {
    case 'details':
        require_once WB_PATH . '/account/check_details.php';
        break;
    case 'email':
        require_once WB_PATH . '/account/check_email.php';
        break;
    case 'password':
        require_once WB_PATH . '/account/check_password.php';
        break;
    default:
        // do nothing
}

// get user's display_name and email from database
$sSql = "SELECT `display_name`, `email` FROM `{TP}users` WHERE user_id = '" . $wb->get_user_id() . "'";
$rSet = $database->query($sSql);
if ($database->is_error()) {
    $error[] = $database->get_error();
}

$aSet = $rSet->fetchRow();
$sDisplayName = $aSet['display_name'];
$sEmail       = $aSet['email'];

// collect languages array
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

// collect time zones array
require ADMIN_PATH . '/interface/timezones.php';
$aTimeZones = array();
$i = 0;
foreach ($TIMEZONES as $hour_offset => $title) {
    $aTimeZones[$i]['VALUE']    = $hour_offset;
    $aTimeZones[$i]['NAME']     = isset($title) ? $title : '';
    $aTimeZones[$i]['SELECTED'] = ($wb->get_timezone() == $hour_offset * 3600) ? true : false;
	$i++;
}

// collect date format array
require ADMIN_PATH . '/interface/date_formats.php';
$aDateFormats = array();
$i = 0;
foreach ($DATE_FORMATS as $format => $title) {
    $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
    
    $aDateFormats[$i]['VALUE'] = ($format != 'system_default') ? $format : '';
    $aDateFormats[$i]['NAME']  = $title;	

	$aDateFormats[$i]['SELECTED'] = false;
    if (DATE_FORMAT == $format and !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
        $aDateFormats[$i]['SELECTED'] = true;
    } elseif ($format == 'system_default' and isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
        $aDateFormats[$i]['SELECTED'] = true;
    } 
	$i++;
}

// collect time format array
require ADMIN_PATH . '/interface/time_formats.php';
$aTimeFormats = array();
$i = 0;
foreach ($TIME_FORMATS as $format => $title) {
    $format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
	
	$aTimeFormats[$i]['VALUE'] = ($format != 'system_default') ? $format : '';
    $aTimeFormats[$i]['NAME']  = $title;	

	$aTimeFormats[$i]['SELECTED'] = false;
    if (DATE_FORMAT == $format and !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
        $aTimeFormats[$i]['SELECTED'] = true;
    } elseif ($format == 'system_default' and isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
        $aTimeFormats[$i]['SELECTED'] = true;
    } 
	$i++;
}

// Get the template file for preferences
include account_getTemplate('form_preferences');