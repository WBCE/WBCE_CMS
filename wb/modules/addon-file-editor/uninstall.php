<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file contains the wrapper routines when de-installing this module.
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     cwsoft-addon-file-editor
 * @author      cwsoft (http://cwsoft.de)
 * @copyright   cwsoft
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 */

// prevent this file from being accessed directly
if (defined('WB_PATH') == false) {
	exit("Cannot access this file directly");
}

// remove Addon File Editor settings table from database
$table = TABLE_PREFIX . 'mod_cwsoft-addon-file-editor';
$database->query("DROP TABLE IF EXISTS `$table`");

// remove temporary download folder in /temp if exists
require_once ('code/config.php');
require_once ('code/functions.php');
removeFileOrFolder($temp_zip_path);
