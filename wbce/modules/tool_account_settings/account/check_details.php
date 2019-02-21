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

// Must include code to stop this file being access directly
defined('WB_PATH') or die("Cannot access this file directly"); 

// Check FTAN
if (!$wb->checkFTAN()) {
    $wb->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
}

// Get entered values
$sDisplayName = $wb->add_slashes(strip_tags($admin->get_post('display_name')));
$sLanguage    = preg_match('/^[a-z]{2}$/si', $wb->get_post('language')) ? $wb->get_post('language') : 'EN';
$sTimezone    = is_numeric($wb->get_post('timezone')) ? $wb->get_post('timezone')*60*60 : 0;
$sDateFormat  = $wb->get_post('date_format');
$sTimeFormat  = $wb->get_post('time_format');

// Update user data
$aUpdate = array(
    'user_id'      => $wb->get_user_id(),
    'display_name' => $database->escapeString($sDisplayName),
    'language'     => $database->escapeString($sLanguage),
    'timezone'     => $database->escapeString($sTimezone),
    'date_format'  => $database->escapeString($sDateFormat),
    'time_format'  => $database->escapeString($sTimeFormat),
);

if($database->updateRow('{TP}users', 'user_id', $aUpdate)) {
    $success[] = $TOOL_TXT['DETAILS_SAVED'];

    // update SESSION values
    $_SESSION['DISPLAY_NAME'] = $sDisplayName;
    $_SESSION['LANGUAGE']     = $sLanguage;
    $_SESSION['TIMEZONE']     = $sTimezone;
    $_SESSION['HTTP_REFERER'] = (($_SESSION['LANGUAGE']== LANGUAGE) ? $_SESSION['HTTP_REFERER'] : WB_URL);

    // Update DATE format
    if($sDateFormat != '') {
        $_SESSION['DATE_FORMAT'] = $sDateFormat;
        if(isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) unset($_SESSION['USE_DEFAULT_DATE_FORMAT']); 
    } else {
        $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
        if(isset($_SESSION['DATE_FORMAT']))             unset($_SESSION['DATE_FORMAT']); 
    }

    // Update TIME format
    if($sTimeFormat != '') {
        $_SESSION['TIME_FORMAT'] = $sTimeFormat;
        if(isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) unset($_SESSION['USE_DEFAULT_TIME_FORMAT']); 
    } else {
        $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
        if(isset($_SESSION['TIME_FORMAT']))             unset($_SESSION['TIME_FORMAT']); 
    }
} else {
    $error[] = $database->get_error();
}
