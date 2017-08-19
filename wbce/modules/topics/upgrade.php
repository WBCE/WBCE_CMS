<?php

// Must include code to stop this file being access directly
if(!defined('WB_PATH')) die(header('Location: index.php'));

global $database;
global $admin;

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;

$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$mod_dir);
$fetch = $query->fetchRow();

// Add field groups_id
if(!isset($fetch['groups_id'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$mod_dir."` ADD `groups_id` VARCHAR(255) NOT NULL DEFAULT ''")) {
		echo 'Database Field "groups_id" added successfully. <span class="good">OK</span> .<br />';
	}
		echo 'Database Field "groups_id" already exists. <span class="good">OK</span> .<br />';
}

$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$mod_dir.'_comments');
$fetch = $query->fetchRow();

// Add field commentextra
if(!isset($fetch['commentextra'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$mod_dir."_comments` ADD `commentextra` VARCHAR(255) NOT NULL DEFAULT ''")) {
		echo 'Database Field "commentextra" added successfully. <span class="good">OK</span> .<br />';
	}
		echo 'Database Field "commentextra" already exists. <span class="good">OK</span> .<br />';
}	

// create the RSS count table
$SQL = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_topics_rss_count` ( ".
    "`id` INT(11) NOT NULL AUTO_INCREMENT, ".
    "`section_id` INT(11) NOT NULL DEFAULT '-1', ".
    "`md5_ip` VARCHAR(32) NOT NULL DEFAULT '', ".
    "`count` INT(11) NOT NULL DEFAULT '0', ".
    "`date` DATE NOT NULL DEFAULT '0000-00-00', ".
    "`timestamp` TIMESTAMP, ".
    "PRIMARY KEY (`id`), ".
    "KEY (`md5_ip`, `date`) ".
    ") ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if (!$database->query($SQL))
    $admin->print_error($database->get_error());

// create the RSS statistics table
$SQL = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_topics_rss_statistic` ( ".
    "`id` INT(11) NOT NULL AUTO_INCREMENT, ".
    "`section_id` INT(11) NOT NULL DEFAULT '-1', ".
    "`date` DATE NOT NULL DEFAULT '0000-00-00', ".
    "`callers` INT(11) NOT NULL DEFAULT '0', ".
    "`views` INT(11) NOT NULL DEFAULT '0', ".
    "`timestamp` TIMESTAMP, ".
    "PRIMARY KEY (`id`), ".
    "KEY (`date`) ".
    ") ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if (!$database->query($SQL))
    $admin->print_error($database->get_error());

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;
require_once(WB_PATH.'/modules/'.$mod_dir.'/inc/upgrade.inc.php');
