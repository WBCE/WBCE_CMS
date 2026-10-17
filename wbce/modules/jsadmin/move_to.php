<?php
/**
 *
 * @category        modules
 * @package         JsAdmin
 * @author          WebsiteBaker Project, modified by Swen Uth for Website Baker 2.7
 * @copyright       (C) 2006, Stepan Riha
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: move_to.php 1441 2011-04-09 23:04:22Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/jsadmin/move_to.php $
 * @lastmodified    $Date: 2011-04-10 01:04:22 +0200 (So, 10. Apr 2011) $
 *
 * 1.4.5 security hardening (see _docs/wbce-jsadmin/security-fixes-1.4.5.md):
 *   - requires a valid FTAN (this is a state-changing request; dragdrop.js now
 *     sends the token) -> closes the GET/CSRF hole;
 *   - enforces that the section actually belongs to the authorised page, so
 *     rights on page A can no longer reorder the sections of page B;
 *   - bounds the target position to the valid range of the sibling group.
*/

require('../../config.php');

$admin_header = false;

if(isset($_GET['page_id']) AND is_numeric($_GET['page_id']) AND is_numeric(@$_GET['position'])) {
	$position = (int)$_GET['position'];

	// Include WB admin wrapper script (checks login, pages_modify, admin rights
	// on page_id and the section's module right).
	$update_when_modified = true;
	// Tells script to update when this page was last updated
	require(WB_PATH.'/modules/admin.php');

	// CSRF: this is a state-changing request. Since 1.4.5 dragdrop.js sends an
	// FTAN token; reject anything without a valid one.
	if (!$admin->checkFTAN('GET')) {
		header('HTTP/1.1 403 Forbidden');
		die('Security offense! Access denied!');
	}

if( isset($_GET['file_id']) || (isset($_GET['group_id'])) ) {
	if(isset($_GET['group_id']) && is_numeric($_GET['group_id'])) {
		$id = (int)$_GET['group_id'];
		$id_field = 'group_id';
		$table = TABLE_PREFIX.'mod_download_gallery_groups';
		$common_field = 'section_id';
	} else {
		$id = (int)$_GET['file_id'];
		$id_field = 'file_id';
		$table = TABLE_PREFIX.'mod_download_gallery_files';
		$common_field = 'group_id';
	}
} elseif( isset($_GET['page_id']) || (isset($_GET['section_id'])) ) {
	// Get common fields
	if(isset($_GET['section_id']) && is_numeric($_GET['section_id'])) {
		$page_id = (int)$_GET['page_id'];
		$id = (int)$_GET['section_id'];
		$id_field = 'section_id';
		$common_field = 'page_id';
		$table = TABLE_PREFIX.'sections';

		// Ensure the section really belongs to the authorised page, so rights on
		// page A cannot reorder the sections of page B (the admin.php wrapper only
		// checks the page right on page_id and the section's module right).
		$iSectionPage = (int) $database->get_one("SELECT `page_id` FROM `".TABLE_PREFIX."sections` WHERE `section_id` = ".$id);
		if ($iSectionPage !== $page_id) {
			header('HTTP/1.1 403 Forbidden');
			die('Security offense! Access denied!');
		}
	} else {
		$id = (int)$_GET['page_id'];
		$id_field = 'page_id';
		$common_field = 'parent';
		$table = TABLE_PREFIX.'pages';
	}
} else {
	header('HTTP/1.1 400 Bad Request');
	die("Missing parameters");
}

	// Get current index
	$rs = $database->query("SELECT `$common_field`, `position` FROM `$table` WHERE `$id_field` = ".(int)$id);
	if(!($row = $rs->fetchRow(MYSQLI_ASSOC))) {
		header('HTTP/1.1 404 Not Found');
		die("Unknown id");
	}
	$common_id = (int) $row[$common_field];
	$old_position = (int) $row['position'];

	if($old_position == $position)
		return;

	// Bound the target position to the valid range [1, count] of the sibling group.
	$iCount = (int) $database->get_one("SELECT COUNT(*) FROM `$table` WHERE `$common_field` = ".$common_id);
	if ($position < 1) { $position = 1; }
	if ($iCount > 0 && $position > $iCount) { $position = $iCount; }
	if ($old_position == $position)
		return;

	// Build query to update affected rows
	if($old_position < $position)
		$database->query("UPDATE `$table` SET `position` = `position` - 1 WHERE `position` > ".$old_position." AND `position` <= ".$position." AND `$common_field` = ".$common_id);
	else
		$database->query("UPDATE `$table` SET `position` = `position` + 1 WHERE `position` >= ".$position." AND `position` < ".$old_position." AND `$common_field` = ".$common_id);

	// Build query to update specified row
	$database->query("UPDATE `$table` SET `position` = ".$position." WHERE `$id_field` = ".(int)$id);
} else {
	header('HTTP/1.1 400 Bad Request');
	die("Missing parameters");
}
