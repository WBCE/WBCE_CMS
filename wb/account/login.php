<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: login.php 1605 2012-02-08 07:11:36Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/login.php $
 * @lastmodified    $Date: 2012-02-08 08:11:36 +0100 (Mi, 08. Feb 2012) $
 *
 */

require_once("../config.php");

// Make sure the login is enabled
if(!FRONTEND_LOGIN) {
	if(INTRO_PAGE) {
		header('Location: '.WB_URL.PAGES_DIRECTORY.'/index.php');
		exit(0);
	} else {
		header('Location: '.WB_URL.'/index.php');
		exit(0);
	}
}

$page_id = !empty($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0;

// Required page details
// $page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['PLEASE_LOGIN']);
define('MENU_TITLE', $TEXT['PLEASE_LOGIN']);
define('VISIBILITY', 'public');
// Set the page content include file
define('PAGE_CONTENT', WB_PATH.'/account/login_form.php');

require_once(WB_PATH.'/framework/class.login.php');
require_once(WB_PATH.'/framework/class.frontend.php');

// Create new frontend object
$wb = new frontend();

// Create new login app
$requestMethod = '_'.strtoupper($_SERVER['REQUEST_METHOD']);
$redirect  = strip_tags(isset(${$requestMethod}['redirect']) ? ${$requestMethod}['redirect'] : '');
$redirect = ((isset($_SERVER['HTTP_REFERER']) && empty($redirect)) ?  $_SERVER['HTTP_REFERER'] : $redirect);
$_SESSION['HTTP_REFERER'] = str_replace(WB_URL,'',$redirect);

$loginUrl  = WB_URL.'/account/login.php';
$loginUrl .= (!empty($redirect) ? '?redirect=' .$_SESSION['HTTP_REFERER'] : '');

$ThemeUrl  = WB_URL.$wb->correct_theme_source('warning.html');
// Setup template object, parse vars to it, then parse it
$ThemePath = realpath(WB_PATH.$wb->correct_theme_source('login.htt'));

$thisApp = new Login(
				array(
						"MAX_ATTEMPS" => "3",
						"WARNING_URL" => $ThemeUrl."/warning.html",
						"USERNAME_FIELDNAME" => 'username',
						"PASSWORD_FIELDNAME" => 'password',
						"REMEMBER_ME_OPTION" => SMART_LOGIN,
						"MIN_USERNAME_LEN" => "2",
						"MIN_PASSWORD_LEN" => "2",
						"MAX_USERNAME_LEN" => "30",
						"MAX_PASSWORD_LEN" => "30",
						"LOGIN_URL" => $loginUrl,
						"DEFAULT_URL" => WB_URL.PAGES_DIRECTORY."/index.php",
						"TEMPLATE_DIR" => $ThemePath,
						"TEMPLATE_FILE" => "login.htt",
						"FRONTEND" => true,
						"FORGOTTEN_DETAILS_APP" => WB_URL."/account/forgot.php",
						"USERS_TABLE" => TABLE_PREFIX."users",
						"GROUPS_TABLE" => TABLE_PREFIX."groups",
						"REDIRECT_URL" => $redirect
				)
		);

// Set extra outsider var
$globals[] = 'thisApp';

// Include the index (wrapper) file
require(WB_PATH.'/index.php');
