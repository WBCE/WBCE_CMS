<?php
/**
 *
 * @category        admintool
 * @package         wbstats
 * @author          Ruud Eisinga - dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 7 and higher
 * @version         0.2.5.7
 * @lastmodified    September 1, 2025
 *
 */


defined('WB_PATH') OR die(header('Location: ../index.php'));


function _wbs_db_add_field($field, $table, $desc) {
	global $database;
	$table = TABLE_PREFIX.$table;
	$database->field_add($table, $field, $desc, false);
}

_wbs_db_add_field("refspam", "mod_wbstats_day", "int(1) NOT NULL default '0' AFTER `view`");
_wbs_db_add_field("suspected", "mod_wbstats_day", "int(1) NOT NULL default '0' AFTER `bots`");
_wbs_db_add_field("spam", "mod_wbstats_ref", "int(1) NOT NULL default '0' AFTER `view`");
_wbs_db_add_field("last_page", "mod_wbstats_ips", "varchar(512) NOT NULL default '' AFTER `page`");
_wbs_db_add_field("pages", "mod_wbstats_ips", "int(11) NOT NULL default '0' AFTER `last_page`");

_wbs_db_add_field("location", "mod_wbstats_ips", "varchar(64) NOT NULL default '' AFTER `loggedin`");
_wbs_db_add_field("browser", "mod_wbstats_ips", "varchar(32) NOT NULL default '' AFTER `location`");
_wbs_db_add_field("os", "mod_wbstats_ips", "varchar(32) NOT NULL default '' AFTER `browser`");
_wbs_db_add_field("language", "mod_wbstats_ips", "varchar(32) NOT NULL default '' AFTER `os`");
_wbs_db_add_field("referer", "mod_wbstats_ips", "varchar(64) NOT NULL default '' AFTER `language`");
_wbs_db_add_field("ua", "mod_wbstats_ips", "varchar(255) NOT NULL default '' AFTER `referer`");
_wbs_db_add_field("last_status", "mod_wbstats_ips", "varchar(10) NOT NULL default '' AFTER `last_page`");
_wbs_db_add_field("country", "mod_wbstats_ips", "varchar(64) NOT NULL default '' AFTER `location`");


$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_ips` MODIFY `ip` VARCHAR(50)");

$database->index_add(TABLE_PREFIX."mod_wbstats_ips","time","time");
$database->index_add(TABLE_PREFIX."mod_wbstats_ips","online","online");
$database->index_add(TABLE_PREFIX."mod_wbstats_ips","ip","ip,online");

//$database->query("UPDATE `".TABLE_PREFIX."mod_wbstats_ips` SET `location`=`session` WHERE `session`!='ignore' and `session`!='' and `location`=''");

// clean bad data from previous upgrades.
$database->query("UPDATE `".TABLE_PREFIX."mod_wbstats_ips` SET `location`='-' WHERE `session`=`location`");

$database->query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_wbstats_browser` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`agent` varchar(200) NOT NULL default '',
	`browser` varchar(50) NOT NULL default '',
	`version` varchar(50) NOT NULL default '',
	`os` varchar(100) NOT NULL default '',
	`view` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY  (`id`),
	INDEX `browser_version` (`browser`, `version`),
	INDEX `os` (`os`)	
	)"
);

/* for existing table, modify fieldslengths and reindex */
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_browser` MODIFY `agent` VARCHAR(200)");
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_browser` MODIFY `browser` VARCHAR(50)");
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_browser` MODIFY `version` VARCHAR(50)");
$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_browser` MODIFY `os` VARCHAR(100)");
$database->index_add(TABLE_PREFIX."mod_wbstats_browser","browser","browser,version");
$database->index_add(TABLE_PREFIX."mod_wbstats_browser","os","os");


$database->query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_wbstats_hist`  (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	`ip` VARCHAR(50) NOT NULL DEFAULT '',
	`session` VARCHAR(64) NOT NULL DEFAULT '',
	`page` VARCHAR(255) NOT NULL DEFAULT '',
	`status` VARCHAR(10) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `ip` (`ip`, `timestamp`)
	)"
);	

_wbs_db_add_field("status", "mod_wbstats_hist", "varchar(10) NOT NULL default '' AFTER `page`");
_wbs_db_add_field("session", "mod_wbstats_hist", "varchar(64) NOT NULL default '' AFTER `ip`");


$database->query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_wbstats_cfg`  (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(50) NOT NULL DEFAULT 'none',
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`) 
	)"
);

$database->query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_wbstats_loc`  (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(50) NOT NULL DEFAULT '',
	`location` VARCHAR(128) NOT NULL DEFAULT '',
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `ip` (`ip`)
	)"
);

_wbs_db_add_field("country", "mod_wbstats_loc", "varchar(128) NOT NULL default '' AFTER `location`");
_wbs_db_add_field("country_code", "mod_wbstats_loc", "varchar(6) NOT NULL default '' AFTER `country`");
_wbs_db_add_field("latitude", "mod_wbstats_loc", "varchar(12) NOT NULL default '' AFTER `country_code`");
_wbs_db_add_field("longitude", "mod_wbstats_loc", "varchar(12) NOT NULL default '' AFTER `country_code`");


$database->query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_wbstats_utm`  (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`session` varchar(64) NOT NULL default '',
	`page` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`source` VARCHAR(128) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`medium` VARCHAR(128) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`campaign` VARCHAR(128) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`term` VARCHAR(128) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`content` VARCHAR(128) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`referer` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	`pagecount` INT(11) NOT NULL DEFAULT '0',
	`day` VARCHAR(8) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `campaign` (`campaign`) USING BTREE,
	INDEX `day` (`day`) USING BTREE
	)"
);
$database->query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX ."mod_wbstats_shop`  (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
	`shoptype` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
	`order_id` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
	`invoice_id` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	`order_total` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
	`status` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
	`payment_method` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
	`order_data` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `order_id` (`order_id`) USING BTREE,
	INDEX `timestamp` (`timestamp`) USING BTREE,
	INDEX `ip` (`ip`) USING BTREE
	)"
);


