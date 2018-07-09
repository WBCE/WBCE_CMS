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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

$sUsernameField = 'username';
$sPasswordField = 'password';

if(defined('SMART_LOGIN') AND SMART_LOGIN == 'enabled') {
	// Generate username field name
	$sUsernameField = 'username_';
	$sPasswordField = 'password_';

	$temp = array_merge(range('a','z'), range(0,9));
	shuffle($temp);
	for($i=0;$i<=7;$i++) {
		$sUsernameField .= $temp[$i];
		$sPasswordField .= $temp[$i];
	}
}

$thisApp->redirect_url = (isset($thisApp->redirect_url) && ($thisApp->redirect_url != '') ? $thisApp->redirect_url : $_SESSION['HTTP_REFERER']);

require_once __DIR__ .'/functions/functions.php';

// load Language Files 
foreach (account_getLanguageFiles() as $sLangFile) require_once $sLangFile;

// Get the template file for forgot_login_details
include account_getTemplate('form_login');

#wb_dump(get_included_files());