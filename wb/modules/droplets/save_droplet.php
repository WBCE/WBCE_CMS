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
 * @version         $Id: save_droplet.php 1503 2011-08-18 02:18:59Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/droplets/save_droplet.php $
 * @lastmodified    $Date: 2011-08-18 04:18:59 +0200 (Do, 18. Aug 2011) $
 *
 */

require('../../config.php');
// Get id
/*
if(!isset($_POST['droplet_id']) OR !is_numeric($_POST['droplet_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$droplet_id = (int) $_POST['droplet_id'];
}
*/

// Include WB admin wrapper script
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=droplets';

$admin = new admin('admintools', 'admintools',false);

$droplet_id = intval($admin->checkIDKEY('droplet_id', false, 'POST'));

if(!$admin->checkFTAN() || !$droplet_id ) {
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $module_edit_link );
}
$admin->print_header();

// Validate all fields
if($admin->get_post('title') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/droplets/modify_droplet.php?droplet_id='. $admin->getIDKEY($droplet_id));
} else {
	$title = $admin->add_slashes($admin->get_post('title'));
	$active = (int) $admin->get_post('active');
	$admin_view = (int) $admin->get_post('admin_view');
	$admin_edit = (int) $admin->get_post('admin_edit');
	$show_wysiwyg = (int) $admin->get_post('show_wysiwyg');
	$description = $admin->add_slashes($admin->get_post('description'));
	$tags = array('<?php', '?>' , '<?');
	$content = $admin->add_slashes(str_replace($tags, '', $_POST['savecontent']));
	$comments = $admin->add_slashes($admin->get_post('comments'));
	$modified_when = time();
	$modified_by = (int) $admin->get_user_id();
}

// Update row
$sql = 'UPDATE `'.TABLE_PREFIX.'mod_droplets` SET ';
$sql .= '`name` = \''.$title.'\', ';
$sql .= '`active` = '.$active.', ';
$sql .= '`admin_view` = '.$admin_view.', ';
$sql .= '`admin_edit` = '.$admin_edit.', ';
$sql .= '`show_wysiwyg` = '.$show_wysiwyg.', ';
$sql .= '`description` = \''.$description.'\', ';
$sql .= '`code` = \''.$content.'\', ';
$sql .= '`comments` = \''.$comments.'\', ';
$sql .= '`modified_when` = '.$modified_when.', ';
$sql .= '`modified_by` = '.$modified_by.' ';
$sql .= 'WHERE `id` = '.$droplet_id;
$database->query($sql);

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/droplets/modify_droplet.php?droplet_id='. $admin->getIDKEY($droplet_id));
} else {
    $admin->print_success($TEXT['SUCCESS'], $module_edit_link);
}

// Print admin footer
$admin->print_footer();
