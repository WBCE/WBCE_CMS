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

require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . ' /init.php';

// include default language file
require_once WB_PATH . '/languages/' . DEFAULT_LANGUAGE . '.php';
$load_language = false;

// get config from INI file
$config = account_getConfig();

// set required page details
$page_id          = (isset($_SESSION['PAGE_ID']) && ($_SESSION['PAGE_ID'] != '') ? $_SESSION['PAGE_ID'] : 0);
$page_description = '';
$page_keywords    = '';
define('TEMPLATE', $config['signup_template']);
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['SIGNUP']);
define('MENU_TITLE', $TEXT['SIGNUP']);
define('MODULE', '');
define('VISIBILITY', 'public');

// set the page content include file
define('PAGE_CONTENT', ACCOUNT_TOOL_PATH . '/account/signup_switch.php');
	
// disable auto authentication
$auto_auth = false;

// include index wrapper file
require WB_PATH . '/index.php';