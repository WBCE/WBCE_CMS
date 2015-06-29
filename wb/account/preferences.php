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
 * @version         $Id: preferences.php 1508 2011-09-07 18:51:47Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/account/preferences.php $
 * @lastmodified    $Date: 2011-09-07 20:51:47 +0200 (Mi, 07. Sep 2011) $
 *
 */

require_once('../config.php');

if(!FRONTEND_LOGIN) {
	if(INTRO_PAGE) {
		header('Location: '.WB_URL.PAGES_DIRECTORY.'/index.php');
		exit(0);
	} else {
		header('Location: '.WB_URL.'/index.php');
		exit(0);
	}
}

require_once(WB_PATH.'/framework/class.frontend.php');
$wb_inst = new wb();
if ($wb_inst->is_authenticated()==false) {
	header('Location: '.WB_URL.'/account/login.php');
	exit(0);
}

$page_id = !empty($_SESSION['PAGE_ID']) ? $_SESSION['PAGE_ID'] : 0;

// Required page details
/* */
// $page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', $page_id);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);

define('PAGE_TITLE', $MENU['PREFERENCES']);
define('MENU_TITLE', $MENU['PREFERENCES']);
define('MODULE', '');
define('VISIBILITY', 'public');

define('PAGE_CONTENT', WB_PATH.'/account/preferences_form.php');
// Include the index (wrapper) file
require(WB_PATH.'/index.php');
