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

$requestMethod            = '_' . strtoupper($_SERVER['REQUEST_METHOD']);
$sRedirect                = strip_tags(isset(${$requestMethod}['redirect']) ? ${$requestMethod}['redirect'] : '');
$sRedirect                = ((isset($_SERVER['HTTP_REFERER']) && empty($sRedirect)) ? $_SERVER['HTTP_REFERER'] : $sRedirect);
$sRedirect                = ($sRedirect != '') ? $sRedirect : WB_URL . ((INTRO_PAGE) ? PAGES_DIRECTORY : '') . '/index.php';
$_SESSION['HTTP_REFERER'] = str_replace(WB_URL, '', $sRedirect);

if ($oAccounts->is_authenticated() == true) {
    header('Location: ' . $sRedirect); // User already logged-in, redirect
    exit();
}

// Create new Login object
$oLogin = new Login(
    array(
        "MAX_ATTEMPTS"          => "3",
        "TIMEFRAME"             => "600",
        "LOGIN_DELAY"           => "60",
        "WARNING_URL"           => get_url_from_path($oAccounts->correct_theme_source('warning.html')),
        "USERNAME_FIELDNAME"    => 'username',
        "PASSWORD_FIELDNAME"    => 'password',
        "REMEMBER_ME_OPTION"    => SMART_LOGIN,
        "MIN_USERNAME_LEN"      => "2",
        "MIN_PASSWORD_LEN"      => "3",
        "MAX_USERNAME_LEN"      => "30",
        "MAX_PASSWORD_LEN"      => "30",
        "LOGIN_URL"             => LOGIN_URL . (!empty($sRedirect) ? '?redirect=' . $_SESSION['HTTP_REFERER'] : ''),
        "DEFAULT_URL"           => WB_URL . PAGES_DIRECTORY . "/index.php",
        "TEMPLATE_DIR"          => realpath(WB_PATH . $oAccounts->correct_theme_source('login.htt')),
        "TEMPLATE_FILE"         => "login.htt",
        "FRONTEND"              => true,
        "FORGOTTEN_DETAILS_APP" => FORGOT_URL,
        "REDIRECT_URL"          => $sRedirect,
    )
);
$globals[] = 'oLogin'; // Set extra outsider var (used in the page_content() function

// Required page details
$page_id          = (isset($_SESSION['PAGE_ID']) && is_numeric($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0);
define('TEMPLATE', $oAccounts->cfg['login_template']);
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['PLEASE_LOGIN']);
define('MENU_TITLE', $TEXT['PLEASE_LOGIN']);
define('VISIBILITY', 'public');
define('PAGE_CONTENT', ACCOUNT_TOOL_PATH . '/account/form_login.php');

// Include the index (wrapper) file
require WB_PATH . '/index.php';
