-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Aug 2014 um 10:46
-- Server Version: 5.5.32
-- PHP-Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- --------------------------------------------------------
-- Database structure for module 'news'
--
-- Replacements: {TABLE_PREFIX}, {TABLE_ENGINE}, {TABLE_COLLATION}
--
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `addons`
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}addons` (
  `addon_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `directory` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `name` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `description` text{TABLE_COLLATION} NOT NULL,
  `function` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `version` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `platform` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `author` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `license` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  PRIMARY KEY (`addon_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `groups`
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `system_permissions` text{TABLE_COLLATION} NOT NULL,
  `module_permissions` text{TABLE_COLLATION} NOT NULL,
  `template_permissions` text{TABLE_COLLATION} NOT NULL,
  PRIMARY KEY (`group_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `pages`
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `root_parent` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `link` text{TABLE_COLLATION} NOT NULL,
  `target` varchar(7){TABLE_COLLATION} NOT NULL DEFAULT '',
  `page_title` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `menu_title` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `description` text{TABLE_COLLATION} NOT NULL,
  `keywords` text{TABLE_COLLATION} NOT NULL,
  `page_trail` text{TABLE_COLLATION} NOT NULL,
  `template` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `visibility` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `menu` int(11) NOT NULL DEFAULT '0',
  `language` varchar(5){TABLE_COLLATION} NOT NULL DEFAULT '',
  `searching` int(11) NOT NULL DEFAULT '0',
  `admin_groups` text{TABLE_COLLATION} NOT NULL,
  `admin_users` text{TABLE_COLLATION} NOT NULL,
  `viewing_groups` text{TABLE_COLLATION} NOT NULL,
  `viewing_users` text{TABLE_COLLATION} NOT NULL,
  `modified_when` int(11) NOT NULL DEFAULT '0',
  `modified_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `search`
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}search` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `value` text{TABLE_COLLATION} NOT NULL,
  `extra` text{TABLE_COLLATION} NOT NULL,
  PRIMARY KEY (`search_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `sections`
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `module` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `block` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `publ_start` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '0',
  `publ_end` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '0',
  `namesection` VARCHAR( 255 ) NULL,
  PRIMARY KEY (`section_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `settings`
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `value` text{TABLE_COLLATION} NOT NULL,
  PRIMARY KEY (`setting_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `users` (new fields since WBCE v.1.4.0)
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '0',
  `username` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `display_name` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `language` varchar(5){TABLE_COLLATION} NOT NULL DEFAULT 'DE',
  `email` text{TABLE_COLLATION} NOT NULL,

  `signup_checksum` varchar(64){TABLE_COLLATION} NOT NULL DEFAULT '',
  `active` int(11) NOT NULL DEFAULT '0',

  `gdpr_check` int(1) NOT NULL DEFAULT '0',               
  `password` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `remember_key` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `last_reset` int(11) NOT NULL DEFAULT '0',
  `timezone` int(11) NOT NULL DEFAULT '0',
  `date_format` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `time_format` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `home_folder` text{TABLE_COLLATION} NOT NULL,
  `login_when` int(11) NOT NULL DEFAULT '0',
  `login_ip` varchar(50){TABLE_COLLATION} NOT NULL DEFAULT '',
  `signup_timestamp` int(11) NOT NULL DEFAULT '0',
  `signup_timeout` int(11) NOT NULL DEFAULT '0',

  `signup_confirmcode` varchar(64){TABLE_COLLATION} NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `blocking` (new table since WBCE v.1.4.0)
--
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}blocking` (
  `source_ip` varchar(50){TABLE_COLLATION} NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `attempts` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`source_ip`)
){TABLE_ENGINE};
