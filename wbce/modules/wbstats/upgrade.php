<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.2
 * @lastmodified    December 9, 2020
 *
 */
defined('WB_PATH') OR die(header('Location: ../index.php'));


function _wbs_db_add_field($field, $table, $desc) {
	global $database;
	$table = TABLE_PREFIX.$table;
	$query = $database->query("DESCRIBE $table '$field'");
	if(!$query || $query->numRows() == 0) { // add field
		$query = $database->query("ALTER TABLE $table ADD $field $desc");
		$query = $database->query("DESCRIBE $table '$field'");
	}
}

$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_ips` MODIFY `ip` VARCHAR(32)");

_wbs_db_add_field("`refspam`", "mod_wbstats_day", "int(1) NOT NULL default '0' AFTER `view`");
_wbs_db_add_field("`spam`", "mod_wbstats_ref", "int(1) NOT NULL default '0' AFTER `view`");
_wbs_db_add_field("`last_page`", "mod_wbstats_ips", "varchar(512) NOT NULL default '' AFTER `page`");
_wbs_db_add_field("`pages`", "mod_wbstats_ips", "int(11) NOT NULL default '0' AFTER `last_page`");

