-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 24. Aug 2014 um 02:28
-- Server Version: 5.5.38-0ubuntu0.14.04.1 - (Ubuntu)
-- PHP-Version: 5.5.9
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- --------------------------------------------------------
-- Database structure for module 'captcha_control'
--
-- Replacements: {TABLE_PREFIX}, {TABLE_ENGINE}, {TABLE_COLLATION}
--
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `{TABLE_PREFIX}mod_captcha_control`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_captcha_control`;
CREATE TABLE `{TABLE_PREFIX}mod_captcha_control` (
  `enabled_captcha` int(11) NOT NULL DEFAULT '1',
  `enabled_asp` int(11) NOT NULL DEFAULT '1',
  `captcha_type` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT 'calc_text',
  `asp_session_min_age` int(11) NOT NULL DEFAULT '20',
  `asp_view_min_age` int(11) NOT NULL DEFAULT '10',
  `asp_input_min_age` int(11) NOT NULL DEFAULT '5',
  `ct_text` longtext{TABLE_COLLATION} NOT NULL
){TABLE_ENGINE};
--
-- Daten für Tabelle `{TABLE_PREFIX}mod_captcha_control`
--
INSERT INTO `{TABLE_PREFIX}mod_captcha_control`
SET `captcha_type`='calc_ttf_image', `ct_text`='';
-- necessary fields only. all others will be set to their default values
-- fields without an default MUST be set manualy in any case
