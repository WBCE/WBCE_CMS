<?php
/**
 * Admin tool: cwsoft-addon-file-editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the WebsiteBaker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file creates the module settings table when the module is installed.
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

// drop existing module tables
$table = TABLE_PREFIX . 'mod_cwsoft-addon-file-editor';
$database->query("DROP TABLE IF EXISTS `$table`");

// create new module table
$sql = "CREATE TABLE `$table` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ftp_enabled` INT(1) NOT NULL,
  `ftp_server` VARCHAR(255) collate latin1_general_ci NOT NULL,
  `ftp_user` VARCHAR(255) collate latin1_general_ci NOT NULL,
  `ftp_password` VARCHAR(255) collate latin1_general_ci NOT NULL,
  `ftp_port` INT NOT NULL,
  `ftp_start_dir` VARCHAR(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
)";

$database->query($sql);

// add table with default values
$sql = "INSERT INTO `$table`
	(`ftp_enabled`, `ftp_server`, `ftp_user`, `ftp_password`, `ftp_port`, `ftp_start_dir`)
	VALUES ('0', '-', '-', '', '21', '/')
";

$database->query($sql);