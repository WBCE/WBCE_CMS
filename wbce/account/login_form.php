<?php
/**
 *
 * @category        frontend
 * @package         account
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       Website Baker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: login_form.php 1599 2012-02-06 15:59:24Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/login_form.php $
 * @lastmodified    $Date: 2012-02-06 16:59:24 +0100 (Mo, 06. Feb 2012) $
 *
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