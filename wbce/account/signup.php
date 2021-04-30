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

require_once dirname(__DIR__) . '/config.php';

if (!FRONTEND_LOGIN) {
    header('Location: ' . WB_URL . ((INTRO_PAGE) ? PAGES_DIRECTORY : '') . '/index.php');
    exit(0);
}

$oAccounts = new Accounts();

foreach ($oAccounts->getLanguageFiles() as $sLangFile) {
    require_once $sLangFile;
}

// Check if FRONTEND_SIGNUP group constant is defined or USER_ID is in Session
$iSignupGroupID = defined('FRONTEND_SIGNUP') ? (int)FRONTEND_SIGNUP : 0;
$iUserID = isset($_SESSION['USER_ID']) ? (int)$_SESSION['USER_ID'] : 0;

// Work out redirect_url (either root index.php or intro page in pages/index.php)
$sRedirect = WB_URL . ((INTRO_PAGE) ? PAGES_DIRECTORY : '') . '/index.php';

// Do not show signup form if no FRONTEND_SIGNUP was defined or user already logged-in
if ($iSignupGroupID === 0 || $iUserID != 0) {
    die(header('Location: ' . $sRedirect));
}

// Check if form honeypot fields were filled out
if (ENABLED_ASP && isset($_POST['username']) && (
        (!isset($_POST['submitted_when']) or !isset($_SESSION['submitted_when'])) or
        ($_POST['submitted_when'] != $_SESSION['submitted_when']) or (!isset($_POST['email-address']) or
            $_POST['email-address']) or (!isset($_POST['name']) or $_POST['name']) or (!isset($_POST['full_name']) or $_POST['full_name'])
    )) {
    die(header('Location: ' . $sRedirect));
}

// Required page details
$page_id = (isset($_SESSION['PAGE_ID']) && is_numeric($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0);
define('TEMPLATE', $oAccounts->cfg['signup_template']);
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['SIGNUP']);
define('MENU_TITLE', $TEXT['SIGNUP']);
define('VISIBILITY', 'public');
define('PAGE_CONTENT', ACCOUNT_TOOL_PATH . '/account/form_signup.php');

// Setup wb object, skip header and skip permission checks
#$wb = new wb('Start', 'start', false, false);

// disable auto authentication
$auto_auth = false;

// Include index wrapper file
require WB_PATH . '/index.php';
