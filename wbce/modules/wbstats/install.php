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
 * @version         0.2.5.9
 * @lastmodified    May 29, 2026
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
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(50) NULL DEFAULT NULL ,
	`session` VARCHAR(64) NOT NULL DEFAULT '' ,
	`time` INT(20) NOT NULL DEFAULT '0',
	`online` INT(20) NOT NULL DEFAULT '0',
	`page` VARCHAR(255) NOT NULL DEFAULT '' ,
	`last_page` VARCHAR(512) NOT NULL DEFAULT '' ,
	`last_status` VARCHAR(10) NOT NULL DEFAULT '' ,
	`pages` INT(11) NOT NULL DEFAULT '0',
	`loggedin` INT(1) NOT NULL DEFAULT '0',
	`location` VARCHAR(64) NOT NULL DEFAULT '' ,
	`country` VARCHAR(64) NOT NULL DEFAULT '' ,
	`browser` VARCHAR(32) NOT NULL DEFAULT '' ,
	`os` VARCHAR(32) NOT NULL DEFAULT '' ,
	`language` VARCHAR(32) NOT NULL DEFAULT '' ,
	`referer` VARCHAR(64) NOT NULL DEFAULT '' ,
	`ua` VARCHAR(255) NOT NULL DEFAULT '' ,
	PRIMARY KEY (`id`),
	INDEX `time` (`time`) ,
	INDEX `online` (`online`) ,
	INDEX `ip` (`ip`, `online`)
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
	`city` VARCHAR(64) NOT NULL DEFAULT '',
	`country` VARCHAR(64) NOT NULL DEFAULT '',
	`country_code` VARCHAR(64) NOT NULL DEFAULT '',
	`timezone` VARCHAR(64) NOT NULL DEFAULT '',
	`latitude` VARCHAR(12) NOT NULL DEFAULT '',
	`longitude` VARCHAR(12) NOT NULL DEFAULT '',
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `ip` (`ip`)
	)"
);

$database->query("DROP TABLE IF EXISTS `$table_utm`");
$database->query("CREATE TABLE IF NOT EXISTS `$table_utm`  (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(50) NOT NULL DEFAULT '' ,
	`session` varchar(64) NOT NULL default '',
	`page` VARCHAR(255) NOT NULL DEFAULT '' ,
	`source` VARCHAR(128) NOT NULL DEFAULT '' ,
	`medium` VARCHAR(128) NOT NULL DEFAULT '' ,
	`campaign` VARCHAR(128) NOT NULL DEFAULT '' ,
	`term` VARCHAR(128) NOT NULL DEFAULT '' ,
	`content` VARCHAR(128) NOT NULL DEFAULT '' ,
	`referer` VARCHAR(255) NOT NULL DEFAULT '' ,
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	`pagecount` INT(11) NOT NULL DEFAULT '0',
	`day` VARCHAR(8) NOT NULL DEFAULT '' ,
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
