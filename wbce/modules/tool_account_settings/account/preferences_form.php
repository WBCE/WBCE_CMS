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
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;


// Check preferences data input, include external file
if(isset($_POST['action']) && $wb->get_post('action') == 'profile'){
    require_once ACCOUNT_TOOL_PATH . '/account/check_preferences.php';
}


// Get user's data array
$sSql = "SELECT * FROM `{TP}users` WHERE `user_id` = ".$wb->get_user_id();
$resUser = $database->query($sSql);
$aUser   = $resUser->fetchRow(MYSQL_ASSOC);

$sDisplayName = $aUser['display_name'];
$sEmail       = $aUser['email'];

// Get languages array
require_once ADMIN_PATH . '/interface/languages.php';
$aLanguages = getLanguagesArray();

// Get time zones array
$user_time = true;
require_once ADMIN_PATH . '/interface/timezones.php';
$aTimeZones = getTimeZonesArray($TIMEZONES, true);

// Get time format array
require_once ADMIN_PATH . '/interface/time_formats.php';
$aTimeFormats = getTimeFormatsArray($TIME_FORMATS);

// Collect date format array
require_once ADMIN_PATH . '/interface/date_formats.php';
$aDateFormats = getDateFormatsArray($DATE_FORMATS);

// UserBase AdminTool Connector
$sUserBaseForm = '';
$sFile = WB_PATH.'/modules/UserBase/account/FrontendAccountConnector.php';
if(file_exists($sFile)){
    require_once $sFile;
    $oExtend = new FrontendAccountConnector;
    $sUserBaseForm = $oExtend->renderExtendForm($wb->get_user_id(), PREFERENCES_URL);
}

// we need the utf8_fast_entities_to_umlauts() function in order to correctly display Umlauts
require_once WB_PATH . '/framework/functions-utf8.php';

$TEXT['NEED_CURRENT_PASSWORD'] = ucfirst($TEXT['NEED_CURRENT_PASSWORD']);
$sMsg = json_encode(utf8_fast_entities_to_umlauts($TEXT['NEED_CURRENT_PASSWORD']));
I::insertJsCode('var MSG_CONFIRM = ' . ($sMsg) . ';', 'BODY BTM-');
I::insertJsFile(get_url_from_path(ACCOUNT_TOOL_PATH) . '/js/password_confirm.js', 'BODY BTM-');

// get referer link for use with [cancel] button
$sHttpReferer = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : WB_URL.((INTRO_PAGE) ? PAGES_DIRECTORY : '').'/index.php';

// Get the template file for preferences
include account_getTemplate('form_preferences');