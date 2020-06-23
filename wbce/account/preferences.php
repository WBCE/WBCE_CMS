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

if ($oAccounts->is_authenticated() == false) {
    header('Location: ' . WB_URL . '/account/login.php');
    exit(0);
}

foreach ($oAccounts->getLanguageFiles() as $sLangFile) {
    require_once $sLangFile;
}

// Required page details
$page_id          = (isset($_SESSION['PAGE_ID']) && is_numeric($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0);
$page_description = '';
$page_keywords    = '';
define('TEMPLATE', $oAccounts->cfg['preferences_template']);
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $MENU['PREFERENCES']);
define('MENU_TITLE', $MENU['PREFERENCES']);
define('MODULE', '');
define('VISIBILITY', 'public');
define('PAGE_CONTENT', ACCOUNT_TOOL_PATH . '/account/form_preferences.php');

// Include the index (wrapper) file
require WB_PATH . '/index.php';
