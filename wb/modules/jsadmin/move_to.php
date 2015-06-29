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
*/

require('../../config.php');

if(isset($_GET['page_id']) AND is_numeric($_GET['page_id']) AND is_numeric(@$_GET['position'])) {
	$position = (int)$_GET['position'];

	// Include WB admin wrapper script
	$update_when_modified = true;
	// Tells script to update when this page was last updated
	require(WB_PATH.'/modules/admin.php');

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
	} else {
		$id = (int)$_GET['page_id'];
		$id_field = 'page_id';
		$common_field = 'parent';
		$table = TABLE_PREFIX.'pages';
	}
}

	// Get current index
	$sql = <<<EOT
SELECT $common_field, position FROM $table WHERE $id_field = $id
EOT;
	echo "$sql<br>";
	$rs = $database->query($sql);
	if($row = $rs->fetchRow()) {
		$common_id = $row[$common_field];
		$old_position = $row['position'];
	}
	echo "$old_position<br>";
	if($old_position == $position)
		return;
	
	// Build query to update affected rows
	if($old_position < $position)
		$sql = <<<EOT
UPDATE $table SET position = position - 1
	WHERE position > $old_position AND position <= $position
		AND $common_field = $common_id
EOT;
	else
		$sql = <<<EOT
UPDATE $table SET position = position + 1
	WHERE position >= $position AND position < $old_position
		AND $common_field = $common_id
EOT;
	echo "<pre>$sql</pre>";
	$database->query($sql);

	// Build query to update specified row
	$sql = <<<EOT
UPDATE $table SET position = $position
	WHERE $id_field = $id
EOT;
	echo "<pre>$sql</pre>";
	$database->query($sql);
} else {
	die("Missing parameters");
	header("Location: index.php");
	exit(0);
}
