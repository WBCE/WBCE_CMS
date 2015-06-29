-- phpMyAdmin SQL Dump
-- Erstellungszeit: 20. Januar 2012 um 12:37
-- Server Version: 5.1.41
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- --------------------------------------------------------
-- Database structure for module 'news'
--
-- Replacements: {TABLE_PREFIX}, {TABLE_ENGINE}, {TABLE_COLLATION}
--
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `mod_news_comments`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_news_comments`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}mod_news_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255){TABLE_COLLATION} NOT NULL,
  `comment` text{TABLE_COLLATION} NOT NULL,
  `commented_when` int(11) NOT NULL DEFAULT '0',
  `commented_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `mod_news_groups`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_news_groups`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}mod_news_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255){TABLE_COLLATION} NOT NULL,
  PRIMARY KEY (`group_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `mod_news_posts`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_news_posts`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}mod_news_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255){TABLE_COLLATION} NOT NULL,
  `link` text{TABLE_COLLATION} NOT NULL,
  `content_short` text{TABLE_COLLATION} NOT NULL,
  `content_long` text{TABLE_COLLATION} NOT NULL,
  `commenting` varchar(7){TABLE_COLLATION} NOT NULL,
  `created_when` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `published_when` int(11) NOT NULL DEFAULT '0',
  `published_until` int(11) NOT NULL DEFAULT '0',
  `posted_when` int(11) NOT NULL DEFAULT '0',
  `posted_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
){TABLE_ENGINE};
-- --------------------------------------------------------
--
-- Tabellenstruktur für Tabelle `mod_news_settings`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}mod_news_settings`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}mod_news_settings` (
  `section_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `header` text{TABLE_COLLATION} NOT NULL,
  `post_loop` text{TABLE_COLLATION} NOT NULL,
  `footer` text{TABLE_COLLATION} NOT NULL,
  `posts_per_page` int(11) NOT NULL DEFAULT '0',
  `post_header` text{TABLE_COLLATION} NOT NULL,
  `post_footer` text{TABLE_COLLATION} NOT NULL,
  `comments_header` text{TABLE_COLLATION} NOT NULL,
  `comments_loop` text{TABLE_COLLATION} NOT NULL,
  `comments_footer` text{TABLE_COLLATION} NOT NULL,
  `comments_page` text{TABLE_COLLATION} NOT NULL,
  `commenting` varchar(7){TABLE_COLLATION} NOT NULL,
  `resize` int(11) NOT NULL DEFAULT '0',
  `use_captcha` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`section_id`)
){TABLE_ENGINE};
-- EndOfFile