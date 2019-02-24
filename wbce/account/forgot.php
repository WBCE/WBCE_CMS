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

require_once dirname(__DIR__) . ' /config.php';
require_once __DIR__ . ' /init.php';

$page_id = (!empty($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0);

// Required page details
// $page_id = 0;
$page_description = '';
$page_keywords = '';

define('TEMPLATE', account_getConfig()['login_template']);
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $MENU['FORGOT']);
define('MENU_TITLE', $MENU['FORGOT']);
define('VISIBILITY', 'public');

if(!FRONTEND_LOGIN) {
	header('Location: '.WB_URL.((INTRO_PAGE) ? PAGES_DIRECTORY : '').'/index.php');
	exit(0);
}

// Set the page content include file
define('PAGE_CONTENT', WB_PATH .'/modules/tool_account_settings/account/forgot_form.php');

// Set auto authentication to false
$auto_auth = false;

// Include the index (wrapper) file
require WB_PATH.'/index.php';