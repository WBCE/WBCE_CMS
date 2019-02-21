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

require_once '../config.php';
require_once WB_PATH .'/modules/tool_account_settings/functions.php';

// Check if default langauge file exists
$sLangFile = WB_PATH . '/languages/' . DEFAULT_LANGUAGE . '.php';
if (is_readable($sLangFile)) {
    // Include default language file
    require_once WB_PATH . '/languages/' . DEFAULT_LANGUAGE . '.php';    
    $load_language = false;
} else {
    // Language file is needed, break up if it doesn't exist
    die(header('Location: ' . $sRedirect));    
}

// Check if FRONTEND_SIGNUP group constant is defined or USER_ID is in Session
$iSignupGroupID = defined('FRONTEND_SIGNUP') ? (int) FRONTEND_SIGNUP : 0;
$iUserID = isset($_SESSION['USER_ID']) ? (int) $_SESSION['USER_ID'] : 0;

// Work out redirect_url (either root index.php or intro page in pages/index.php)
$sRedirect = WB_URL . ((INTRO_PAGE) ? PAGES_DIRECTORY : '') . '/index.php';

// Do not show signup form if no FRONTEND_SIGNUP was defined or user already logged-in
if ($iSignupGroupID === 0 || $iUserID != 0) {
	die(header('Location: ' . $sRedirect));
}

// Check if form honeypot fields were filled out
if (ENABLED_ASP && isset($_POST['username']) && (
        (!isset($_POST['submitted_when']) OR !isset($_SESSION['submitted_when'])) OR
        ($_POST['submitted_when'] != $_SESSION['submitted_when']) OR
        (!isset($_POST['email-address']) OR $_POST['email-address']) OR
        (!isset($_POST['name']) OR $_POST['name']) OR
        (!isset($_POST['full_name']) OR $_POST['full_name'])
        )
    ) {
    die(header('Location: ' . $sRedirect));
}

// Define Page Details
define('TEMPLATE',    account_getConfig()['signup_template']);
define('PAGE_ID',     (!empty($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0));
define('ROOT_PARENT', 0);
define('PARENT',      0);
define('LEVEL',       0);
define('PAGE_TITLE',  $TEXT['SIGNUP']);
define('MENU_TITLE',  $TEXT['SIGNUP']);
define('VISIBILITY',  'public');
define('PAGE_CONTENT', WB_PATH .'/modules/tool_account_settings/account/signup_form.php');

// Setup wb object, skip header and skip permission checks
$wb = new wb('Start', 'start', false, false);
// disable auto authentication
$auto_auth = false;

// Include index wrapper file
require WB_PATH . '/index.php';
