<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: add_droplet.php 1503 2011-08-18 02:18:59Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/droplets/add_droplet.php $
 * @lastmodified    $Date: 2011-08-18 04:18:59 +0200 (Do, 18. Aug 2011) $
 *
 */

require('../../config.php');

require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');
$admin = new admin('admintools','admintools',true,false);
if($admin->get_permission('admintools') == true) {

	$admintool_link = ADMIN_URL .'/admintools/index.php';
	$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=droplets';
	// $admin = new admin('admintools', 'admintools');

	$modified_when = time();
	$modified_by = intval($admin->get_user_id());

	// Insert new row into database
	$sql = 'INSERT INTO `'.TABLE_PREFIX.'mod_droplets` SET ';
	$sql .= '`active` = 1, ';
	$sql .= '`modified_when` = '.$modified_when.', ';
	$sql .= '`modified_by` = '.$modified_by.' ';
	$database->query($sql);

	// Get the id
	$droplet_id = intval($database->get_one("SELECT LAST_INSERT_ID()"));

	// Say that a new record has been added, then redirect to modify page
	if($database->is_error()) {
		$admin->print_error($database->get_error(), $module_edit_link);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/droplets/modify_droplet.php?droplet_id='. $admin->getIDKEY($droplet_id));
	}

} else {
		$admin->print_error($database->get_error(), $module_edit_link);
}

// Print admin footer
$admin->print_footer();
