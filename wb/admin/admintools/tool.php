<?php
/**
 *
 * @category        admin
 * @package         admintools
 * @author          WB-Project, Werner v.d. Decken
 * @copyright       2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.2
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: tool.php 1552 2011-12-31 14:57:31Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/admintools/tool.php $
 * @lastmodified    $Date: 2011-12-31 15:57:31 +0100 (Sa, 31. Dez 2011) $
 *
 */
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

	$toolDir = (isset($_GET['tool']) && (trim($_GET['tool']) != '') ? trim($_GET['tool']) : '');
	$doSave = (isset($_POST['save_settings']) || (isset($_POST['action']) && strtolower($_POST['action']) == 'save'));
// test for valid tool name
	if(preg_match('/^[a-z][a-z_\-0-9]{2,}$/i', $toolDir)) {
	// Check if tool is installed
		$sql = 'SELECT `name` FROM `'.TABLE_PREFIX.'addons` '.
		       'WHERE `type`=\'module\' AND `function`=\'tool\' '.
		              'AND `directory`=\''.$toolDir.'\'';
		if(($toolName = $database->get_one($sql))) {
		// create admin-object and print header if FTAN is NOT supported AND function 'save' is requested
			$admin_header = !(is_file(WB_PATH.'/modules/'.$toolDir.'/FTAN_SUPPORTED') && $doSave);
			$admin = new admin('admintools', 'admintools', $admin_header );
			if(!$doSave) {
			// show title if not function 'save' is requested
				print '<h4><a href="'.ADMIN_URL.'/admintools/index.php" '.
				      'title="'.$HEADING['ADMINISTRATION_TOOLS'].'">'.
				      $HEADING['ADMINISTRATION_TOOLS'].'</a>'.
					  '&nbsp;&raquo;&nbsp;'.$toolName.'</h4>'."\n";
			}
			// include modules tool.php
			require(WB_PATH.'/modules/'.$toolDir.'/tool.php');
			$admin->print_footer();
		}else {
		// no installed module found, jump to index.php of admintools
			header('location: '.ADMIN_URL.'/admintools/index.php');
			exit(0);
		}
	}else {
	// invalid module name requested, jump to index.php of admintools
		header('location: '.ADMIN_URL.'/admintools/index.php');
		exit(0);
	}
