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

$table_day   = TABLE_PREFIX .'mod_wbstats_day';
$table_ips   = TABLE_PREFIX .'mod_wbstats_ips';
$table_pages = TABLE_PREFIX .'mod_wbstats_pages';
$table_ref   = TABLE_PREFIX .'mod_wbstats_ref';
$table_key   = TABLE_PREFIX .'mod_wbstats_keywords';
$table_lang  = TABLE_PREFIX .'mod_wbstats_lang';
$table_brw   = TABLE_PREFIX .'mod_wbstats_browser';
$table_hist  = TABLE_PREFIX .'mod_wbstats_hist';
$table_cfg 	 = TABLE_PREFIX .'mod_wbstats_cfg';
$table_loc 	 = TABLE_PREFIX .'mod_wbstats_loc';
$table_utm 	 = TABLE_PREFIX .'mod_wbstats_utm';
$table_shop	 = TABLE_PREFIX .'mod_wbstats_shop';


$database->query("DROP TABLE IF EXISTS `$table_day`");
$database->query("CREATE TABLE `$table_day` (
  	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`user` int(10) NOT NULL default '0',
	`view` int(10) NOT NULL default '0',
	`bots` int(10) NOT NULL default '0',
	`suspected` int(10) NOT NULL default '0',
	`refspam` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	INDEX `day` (`day`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_ips`");
$database->query("CREATE TABLE `$table_ips` (
	`id` int(11) NOT NULL auto_increment,
	`ip` varchar(50) NOT NULL default '',
	`session` varchar(64) NOT NULL default '',
	`time` int(20) NOT NULL default '0',
	`online` int(20) NOT NULL default '0',
	`page` varchar(512) NOT NULL default '',
	`last_page` varchar(512) NOT NULL default '',
	`last_status` varchar(10) NOT NULL default '',
	`pages` int(11) NOT NULL default '0',
	`loggedin` int(1) NOT NULL default '0',
	`location` varchar(64) NOT NULL default '',
	`os` varchar(32) NOT NULL default '',
	`browser` varchar(32) NOT NULL default '',
	`language` varchar(32) NOT NULL default '',
	`referer` varchar(64) NOT NULL default '',
	`ua` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`id`),
	INDEX `time` (`time`),
	INDEX `ip` (`ip`, `online`),
	INDEX `online` (`online`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_pages`");
$database->query("CREATE TABLE `$table_pages` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`page` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_ref`");
$database->query("CREATE TABLE `$table_ref` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	`spam` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_key`");
$database->query("CREATE TABLE `$table_key` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`keyword` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);
$database->query("DROP TABLE IF EXISTS `$table_lang`");
$database->query("CREATE TABLE `$table_lang` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`language` varchar(2) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_brw`");
$database->query("CREATE TABLE `$table_brw` (
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
$database->query("DROP TABLE IF EXISTS `$table_hist`");
$database->query("CREATE TABLE `$table_hist` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	`ip` VARCHAR(50) NOT NULL DEFAULT '',
	`session` varchar(64) NOT NULL default '',
	`page` VARCHAR(255) NOT NULL DEFAULT '',
	`status` VARCHAR(10) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `ip` (`ip`, `timestamp`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_cfg`");
$database->query("CREATE TABLE `$table_cfg` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(50) NOT NULL DEFAULT 'none',
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`) 
	)"
);


$database->query("DROP TABLE IF EXISTS `$table_loc`");
$database->query("CREATE TABLE `$table_loc` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(50) NOT NULL DEFAULT '',
	`location` VARCHAR(128) NOT NULL DEFAULT '',
	`country` VARCHAR(64) NOT NULL DEFAULT '',
	`country_code` VARCHAR(64) NOT NULL DEFAULT '',
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `ip` (`ip`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_utm`");
$database->query("CREATE TABLE IF NOT EXISTS `$table_utm`  (
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

$database->query("DROP TABLE IF EXISTS `$table_shop`");
$database->query("CREATE TABLE IF NOT EXISTS `$table_shop`  (
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
