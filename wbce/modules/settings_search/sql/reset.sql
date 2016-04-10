-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Aug 2014 um 10:47
-- Server Version: 5.5.32
-- PHP-Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

--
-- Tabellenstruktur für Tabelle `search`
--
DROP TABLE IF EXISTS `{TABLE_PREFIX}search`;
CREATE TABLE IF NOT EXISTS `{TABLE_PREFIX}search` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255){TABLE_COLLATION} NOT NULL DEFAULT '',
  `value` text{TABLE_COLLATION} NOT NULL,
  `extra` text{TABLE_COLLATION} NOT NULL,
  PRIMARY KEY (`search_id`)
){TABLE_ENGINE};

--
-- Daten für Tabelle `search`
--
INSERT INTO `{TABLE_PREFIX}search` (`search_id`, `name`, `value`, `extra`) VALUES
(1, 'header', '<form name="search" action="[WB_URL]/search/index.php" method="get">\n<input type="hidden" name="referrer" value="[REFERRER_ID]" />\n<input type="hidden" name="search_path" value="[SEARCH_PATH]" />\n<input type="text" name="string" value="[SEARCH_STRING]" style="width: 200px;" />&nbsp;&nbsp;<input type="submit" value="[TEXT_SEARCH]" style="width: 100px;" /><br />\n<input type="radio" name="match" id="match_all" value="all"[ALL_CHECKED] /><label for="match_all">[TEXT_ALL_WORDS]</label>&nbsp;&nbsp;\n<input type="radio" name="match" id="match_any" value="any"[ANY_CHECKED] /><label for="match_any">[TEXT_ANY_WORDS]</label>&nbsp;&nbsp;\n<input type="radio" name="match" id="match_exact" value="exact"[EXACT_CHECKED] /><label for="match_exact">[TEXT_EXACT_MATCH]</label>\n\n</form>\n\n<hr />    ', ''),
(2, 'footer', '', ''),
(3, 'results_header', '[TEXT_RESULTS_FOR] ''<b>[SEARCH_STRING]</b>'':\n<dl>', ''),
(4, 'results_loop', '<dt><a href="[LINK]">[TITLE]</a></dt>\n<dd style="margin-bottom:1em"><div>[DESCRIPTION]</div>\n[EXCERPT]</dd>', ''),
(5, 'results_footer', '</dl>', ''),
(6, 'no_results', '<p>[TEXT_NO_RESULTS]</p>', ''),
(7, 'module_order', 'wysiwyg,topics', ''),
(8, 'max_excerpt', '15', ''),
(9, 'time_limit', '0', ''),
(10, 'cfg_enable_old_search', 'true', ''),
(11, 'cfg_search_keywords', 'true', ''),
(12, 'cfg_search_description', 'true', ''),
(13, 'cfg_show_description', 'true', ''),
(14, 'cfg_enable_flush', 'false', ''),
(15, 'template', '', '');

