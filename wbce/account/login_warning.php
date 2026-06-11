<?php
/**
 * WBCE CMS — Frontend login warning page
 *
 * Shown when the maximum number of failed login attempts has been exceeded.
 * Replaces the static warning.html with a templated, i18n-aware page
 * rendered within the site template — analogous to admin/login/login_warning.php
 * for the backend context.
 *
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require_once dirname(__DIR__) . '/config.php';

if (!FRONTEND_LOGIN) {
    header('Location: ' . WB_URL . ((INTRO_PAGE) ? PAGES_DIRECTORY : '') . '/index.php');
    exit;
}

$oAccounts = new Accounts();

foreach ($oAccounts->getLanguageFiles() as $sLangFile) {
    require_once $sLangFile;
}

$page_id = (isset($_SESSION['PAGE_ID']) && is_numeric($_SESSION['PAGE_ID'])) ? $_SESSION['PAGE_ID'] : 0;
define('TEMPLATE',     $oAccounts->cfg['login_template']);
define('PAGE_ID',      $page_id);
define('ROOT_PARENT',  0);
define('PARENT',       0);
define('LEVEL',        0);
define('PAGE_TITLE',   $MESSAGE['LOGIN_BLOCKED_TITLE'] ?? 'Too Many Login Attempts');
define('MENU_TITLE',   $MESSAGE['LOGIN_BLOCKED_TITLE'] ?? 'Too Many Login Attempts');
define('VISIBILITY',   'public');
define('PAGE_CONTENT', ACCOUNT_TOOL_PATH . '/account/form_login_warning.php');

require WB_PATH . '/index.php';
