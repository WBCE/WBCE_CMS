-- Create tables for WBstats module
DROP TABLE IF EXISTS `{TP}mod_wbstats_day`;
CREATE TABLE `{TP}mod_wbstats_day` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`user` int(10) NOT NULL default '0',
	`view` int(10) NOT NULL default '0',
	`bots` int(10) NOT NULL default '0',
	`suspected` int(10) NOT NULL default '0',
	`refspam` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`),
	INDEX `day` (`day`)
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_ips`;
CREATE TABLE `{TP}mod_wbstats_ips` (
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
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_pages`;
CREATE TABLE `{TP}mod_wbstats_pages` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`page` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_ref`;
CREATE TABLE `{TP}mod_wbstats_ref` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`referer` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	`spam` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_keywords`;
CREATE TABLE `{TP}mod_wbstats_keywords` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`keyword` varchar(255) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_lang`;
CREATE TABLE `{TP}mod_wbstats_lang` (
	`id` int(11) NOT NULL auto_increment,
	`day` varchar(8) NOT NULL default '',
	`language` varchar(2) NOT NULL default '',
	`view` int(10) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_browser`;
CREATE TABLE `{TP}mod_wbstats_browser` (
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
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_hist`;
CREATE TABLE `{TP}mod_wbstats_hist` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`timestamp` INT(11) NOT NULL DEFAULT '0',
	`ip` VARCHAR(50) NOT NULL DEFAULT '',
	`session` varchar(64) NOT NULL default '',
	`page` VARCHAR(255) NOT NULL DEFAULT '',
	`status` VARCHAR(10) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `ip` (`ip`, `timestamp`)
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_cfg`;
CREATE TABLE `{TP}mod_wbstats_cfg` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`type` VARCHAR(50) NOT NULL DEFAULT 'none',
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_loc`;
CREATE TABLE `{TP}mod_wbstats_loc` (
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
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_utm`;
CREATE TABLE IF NOT EXISTS `{TP}mod_wbstats_utm`  (
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
	);

DROP TABLE IF EXISTS `{TP}mod_wbstats_shop`;
CREATE TABLE IF NOT EXISTS `{TP}mod_wbstats_shop`  (
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
	);
