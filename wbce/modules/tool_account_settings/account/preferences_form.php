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
 */

// prevent this file from being accesses directly
defined('WB_PATH') or exit("Cannot access this file directly");

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

$error   = array();
$success = array();

switch ($wb->get_post('action')) {
    case 'details':
        require_once __DIR__ . '/check_details.php';
        break;
    case 'email':
        require_once __DIR__ . '/check_email.php';
        break;
    case 'password':
        require_once __DIR__ . '/check_password.php';
        break;
    default:
        // do nothing
}

if (!empty($success)) {
    $wb->print_success(implode('<br />', $success), WB_URL.'/account/preferences.php');
}

// Get user's display_name and email from database
$sSql = "SELECT `display_name`, `email` FROM `{TP}users` WHERE `user_id` = '" . $wb->get_user_id() . "'";
$rSet = $database->query($sSql);
if ($database->is_error()) {
    $error[] = $database->get_error();
}

$aSet = $rSet->fetchRow();
$sDisplayName = $aSet['display_name'];
$sEmail       = $aSet['email'];

// Collect languages array
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

// Collect time zones array
require ADMIN_PATH . '/interface/timezones.php';
$aTimeZones = array();
$i = 0;
foreach ($TIMEZONES as $hour_offset => $sTitle) {
    $aTimeZones[$i]['VALUE']    = $hour_offset;
    $aTimeZones[$i]['NAME']     = isset($sTitle) ? $sTitle : '';
    $aTimeZones[$i]['SELECTED'] = ($wb->get_timezone() == $hour_offset * 3600) ? true : false;
    $i++;
}

// Collect date format array
require ADMIN_PATH . '/interface/date_formats.php';
$aDateFormats = array();
$i = 0;
foreach ($DATE_FORMATS as $sFormat => $sTitle) {
    $sFormat = str_replace('|', ' ', $sFormat); // Add's white-spaces (not able to be stored in array key)
    
    $aDateFormats[$i]['VALUE'] = ($sFormat != 'system_default') ? $sFormat : '';
    $aDateFormats[$i]['NAME']  = $sTitle;	

    $aDateFormats[$i]['SELECTED'] = false;
    if (DATE_FORMAT == $sFormat and !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
        $aDateFormats[$i]['SELECTED'] = true;
    } elseif ($sFormat == 'system_default' and isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
        $aDateFormats[$i]['SELECTED'] = true;
    } 
    $i++;
}

// Collect time format array
require ADMIN_PATH . '/interface/time_formats.php';
$aTimeFormats = array();
$i = 0;
foreach ($TIME_FORMATS as $sFormat => $sTitle) {
    $sFormat = str_replace('|', ' ', $sFormat); // Add's white-spaces (not able to be stored in array key)
	
    $aTimeFormats[$i]['VALUE'] = ($sFormat != 'system_default') ? $sFormat : '';
    $aTimeFormats[$i]['NAME']  = $sTitle;	

	$aTimeFormats[$i]['SELECTED'] = false;
    if (DATE_FORMAT == $sFormat and !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
        $aTimeFormats[$i]['SELECTED'] = true;
    } elseif ($sFormat == 'system_default' and isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
        $aTimeFormats[$i]['SELECTED'] = true;
    } 
    $i++;
}

$sUserBaseForm = '';
$sFile = WB_PATH.'/modules/UserBase/account/FrontendAccountConnector.php';
if(file_exists($sFile)){
    require_once $sFile;
    $oExtend = new FrontendAccountConnector;
    $sUserBaseForm = $oExtend->renderExtendForm($wb->get_user_id(), WB_URL . '/account/preferences.php');
}

// Get the template file for preferences
include account_getTemplate('form_preferences');