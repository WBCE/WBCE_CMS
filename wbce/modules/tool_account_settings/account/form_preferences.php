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

$oAccounts = new Accounts();

// Check preferences data input, include external file
if(isset($_POST['action']) && $oAccounts->get_post('action') == 'profile'){
    require_once ACCOUNT_TOOL_PATH . '/account/check_preferences.php';
}

// Get user's data array
$aUser = $database->get_array("SELECT * FROM `{TP}users` WHERE `user_id` = ".$oAccounts->get_user_id())[0];
$sDisplayName = $aUser['display_name'];
$sEmail       = $aUser['email'];

// UserBase AdminTool Connector
$sUserBaseForm = '';
$sFile = WB_PATH.'/modules/UserBase/account/FrontendAccountConnector.php';
if(file_exists($sFile)){
    require_once $sFile;
    $oExtend = new FrontendAccountConnector;
    $sUserBaseForm = $oExtend->renderExtendForm($oAccounts->get_user_id(), PREFERENCES_URL);
}

// we need the utf8_fast_entities_to_umlauts() function in order to correctly display Umlauts
require_once WB_PATH . '/framework/functions-utf8.php';

$TEXT['NEED_CURRENT_PASSWORD'] = ucfirst($TEXT['NEED_CURRENT_PASSWORD']);
$sMsg = json_encode(utf8_fast_entities_to_umlauts($TEXT['NEED_CURRENT_PASSWORD']));
I::insertJsCode('var MSG_CONFIRM = ' . ($sMsg) . ';', 'BODY BTM-');
I::insertJsFile(get_url_from_path(ACCOUNT_TOOL_PATH) . '/js/password_confirm.js', 'BODY BTM-');

// get referer link for use with [cancel] button
$sHttpReferer = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : WB_URL.((INTRO_PAGE) ? PAGES_DIRECTORY : '').'/index.php';

$user_time = true;
require_once ADMIN_PATH . '/interface/timezones.php';
require_once ADMIN_PATH . '/interface/languages.php';
require_once ADMIN_PATH . '/interface/time_formats.php';
require_once ADMIN_PATH . '/interface/date_formats.php';

// Get the template file for preferences
$oMsgBox = new MessageBox();
$aToTwig = array(
    'EMAIL'         => $sEmail,
    'DISPLAY_NAME'  => $sDisplayName,
    
    'TIME_ZONES'    => getTimeZonesArray($TIMEZONES, true),
    'LANGUAGES'     => getLanguagesArray(),
    'DATE_FORMATS'  => getTimeFormatsArray($TIME_FORMATS),
    'TIME_FORMATS'  => getDateFormatsArray($DATE_FORMATS),
    
    'NEW_PASSWORD'  => $wb->passwordField('new_password'),
    'REFFERER_URL'  => $sHttpReferer,
    'USERBASE_FORM' => $sUserBaseForm,
    'MESSAGE_BOX'   => $oMsgBox->fetchDisplay(), 
);
$oAccounts->useTwigTemplate('form_preferences.twig', $aToTwig);