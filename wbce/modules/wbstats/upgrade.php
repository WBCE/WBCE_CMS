<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.1.11
 * @lastmodified    June 29, 2017 
 *
 */
defined('WB_PATH') OR die(header('Location: ../index.php'));

//disabled for WBCE install or upgrade reasons
//function _db_add_field($field, $table, $desc) {
//	global $database;
//	$table = TABLE_PREFIX.$table;
//	$query = $database->query("DESCRIBE $table '$field'");
//	if(!$query || $query->numRows() == 0) { // add field
//		$query = $database->query("ALTER TABLE $table ADD $field $desc");
//		//echo (mysql_error()?mysql_error().'<br />':'');
//		$query = $database->query("DESCRIBE $table '$field'");
//		//echo (mysql_error()?mysql_error().'<br />':'');
//	}
//}

$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_ips` MODIFY `ip` VARCHAR(32)");

//_db_add_field("`session`", "mod_wbstats_ips", "varchar(64) NOT NULL default '' AFTER `ip`");
//_db_add_field("`page`", "mod_wbstats_ips", "varchar(255) NOT NULL default '' AFTER `online`");
//_db_add_field("`loggedin`", "mod_wbstats_ips", "int(1) NOT NULL default '0' AFTER `page`");
//_db_add_field("`bots`", "mod_wbstats_day", "int(1) NOT NULL default '0' AFTER `view`");

