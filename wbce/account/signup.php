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

// check if frontend signup_group and user_id is defined
$signup_group = defined('FRONTEND_SIGNUP') ? (int) FRONTEND_SIGNUP : 0;
$user_id = isset($_SESSION['USER_ID']) ? (int) $_SESSION['USER_ID'] : 0;

// work out redirect_url (either root index.php or intro page in pages/index.php)
$redirect_url = WB_URL . ((INTRO_PAGE) ? PAGES_DIRECTORY : '') . '/index.php';

// do not show signup form if no signup_group was defined or user is already logged in
if ($signup_group === 0 || $user_id != 0) {
	die(header('Location: ' . $redirect_url));
}

// check if form honeypot fields were filled out
if (ENABLED_ASP && isset($_POST['username']) && (
		(!isset($_POST['submitted_when']) OR !isset($_SESSION['submitted_when'])) OR
		($_POST['submitted_when'] != $_SESSION['submitted_when']) OR
		(!isset($_POST['email-address']) OR $_POST['email-address']) OR
		(!isset($_POST['name']) OR $_POST['name']) OR
		(!isset($_POST['full_name']) OR $_POST['full_name'])
		)
	) {
	die(header('Location: ' . $redirect_url));
}

// check if default langauge file exists
if (! is_readable(WB_PATH . '/languages/' . DEFAULT_LANGUAGE . '.php')) {
	die(header('Location: ' . $redirect_url));
}

// include default language file
require_once WB_PATH . '/languages/' . DEFAULT_LANGUAGE . '.php';
$load_language = false;

// set required page details
$page_id = (isset($_SESSION['PAGE_ID']) && ($_SESSION['PAGE_ID'] != '') ? $_SESSION['PAGE_ID'] : 0);
$page_description = '';
$page_keywords = '';
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['SIGNUP']);
define('MENU_TITLE', $TEXT['SIGNUP']);
define('MODULE', '');
define('VISIBILITY', 'public');

// set the page content include file
if (isset($_POST['username'])) {
	define('PAGE_CONTENT', WB_PATH . '/account/signup2.php');
} else {
	define('PAGE_CONTENT', WB_PATH . '/account/signup_form.php');
}

// disable auto authentication
$auto_auth = false;

// include index wrapper file
require WB_PATH . '/index.php';
