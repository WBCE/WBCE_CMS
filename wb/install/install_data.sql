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
-- Daten für Tabelle `groups`
--
INSERT INTO `{TABLE_PREFIX}groups` (`group_id`, `name`, `system_permissions`, `module_permissions`, `template_permissions`) VALUES
(1, 'Administrators', 'pages,pages_view,pages_add,pages_add_l0,pages_settings,pages_modify,pages_intro,pages_delete,media,media_view,media_upload,media_rename,media_delete,media_create,addons,modules,modules_view,modules_install,modules_uninstall,templates,templates_view,templates_install,templates_uninstall,languages,languages_view,languages_install,languages_uninstall,settings,settings_basic,settings_advanced,access,users,users_view,users_add,users_modify,users_delete,groups,groups_view,groups_add,groups_modify,groups_delete,admintools', '', '');
--
-- Daten für Tabelle `search`
--
INSERT INTO `{TABLE_PREFIX}search` (`search_id`, `name`, `value`, `extra`) VALUES
(1, 'header', '\n<h1>[TEXT_SEARCH]</h1>\n\n<form name="searchpage" action="[WB_URL]/search/index.php" method="get">\n<table cellpadding="3" cellspacing="0" border="0" width="500">\n<tr>\n<td>\n<input type="hidden" name="search_path" value="[SEARCH_PATH]" />\n<input type="text" name="string" value="[SEARCH_STRING]" style="width: 100%;" />\n</td>\n<td width="150">\n<input type="submit" value="[TEXT_SEARCH]" style="width: 100%;" />\n</td>\n</tr>\n<tr>\n<td colspan="2">\n<input type="radio" name="match" id="match_all" value="all"[ALL_CHECKED] />\n<label for="match_all">[TEXT_ALL_WORDS]</label>\n<input type="radio" name="match" id="match_any" value="any"[ANY_CHECKED] />\n<label for="match_any">[TEXT_ANY_WORDS]</label>\n<input type="radio" name="match" id="match_exact" value="exact"[EXACT_CHECKED] />\n<label for="match_exact">[TEXT_EXACT_MATCH]</label>\n</td>\n</tr>\n</table>\n\n</form>\n\n<hr />\n    ', ''),
(2, 'footer', '', ''),
(3, 'results_header', '[TEXT_RESULTS_FOR] ''<b>[SEARCH_STRING]</b>'':\n<table cellpadding="2" cellspacing="0" border="0" width="100%" style="padding-top: 10px;">', ''),
(4, 'results_loop', '<tr style="background-color: #F0F0F0;">\n<td><a href="[LINK]">[TITLE]</a></td>\n<td align="right">[TEXT_LAST_UPDATED_BY] [DISPLAY_NAME] ([USERNAME]) [TEXT_ON] [DATE]</td>\n</tr>\n<tr><td colspan="2" style="text-align: justify; padding-bottom: 5px;">[DESCRIPTION]</td></tr>\n<tr><td colspan="2" style="text-align: justify; padding-bottom: 10px;">[EXCERPT]</td></tr>', ''),
(5, 'results_footer', '</table>', ''),
(6, 'no_results', '<tr><td><p>[TEXT_NO_RESULTS]</p></td></tr>', ''),
(7, 'module_order', 'faqbaker,manual,wysiwyg', ''),
(8, 'max_excerpt', '15', ''),
(9, 'time_limit', '0', ''),
(10, 'cfg_enable_old_search', 'true', ''),
(11, 'cfg_search_keywords', 'true', ''),
(12, 'cfg_search_description', 'true', ''),
(13, 'cfg_show_description', 'true', ''),
(14, 'cfg_enable_flush', 'false', ''),
(15, 'template', '', '');
--
-- Daten für Tabelle `settings`
--
INSERT INTO `{TABLE_PREFIX}settings` (`setting_id`, `name`, `value`) VALUES
(1, 'website_description', ''),
(2, 'website_keywords', ''),
(3, 'website_header', ''),
(4, 'website_footer', ''),
(5, 'wysiwyg_style', 'font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;'),
(6, 'er_level', ''),
(7, 'sec_anchor', 'wb_'),
(8, 'default_date_format', 'M d Y'),
(9, 'default_time_format', 'g:i A'),
(10, 'redirect_timer', '1500'),
(11, 'home_folders', 'true'),
(12, 'warn_page_leave', '1'),
(13, 'default_template', 'round'),
(14, 'default_theme', 'wb_theme'),
(15, 'default_charset', 'utf-8'),
(16, 'multiple_menus', 'true'),
(17, 'page_level_limit', '4'),
(18, 'intro_page', 'false'),
(19, 'page_trash', 'inline'),
(20, 'homepage_redirection', 'false'),
(21, 'page_languages', 'true'),
(22, 'wysiwyg_editor', 'fckeditor'),
(23, 'manage_sections', 'true'),
(24, 'section_blocks', 'true'),
(25, 'smart_login', 'true'),
(26, 'frontend_login', 'false'),
(27, 'frontend_signup', 'false'),
(28, 'search', 'public'),
(29, 'page_extension', '.php'),
(30, 'page_spacer', '-'),
(31, 'pages_directory', '/pages'),
(32, 'rename_files_on_upload', 'ph.*?,cgi,pl,pm,exe,com,bat,pif,cmd,src,asp,aspx,js'),
(33, 'media_directory', '/media'),
(34, 'wbmailer_routine', 'phpmail'),
(35, 'wbmailer_default_sendername', 'WB Mailer'),
(36, 'wbmailer_smtp_host', ''),
(37, 'wbmailer_smtp_auth', ''),
(38, 'wbmailer_smtp_username', ''),
(39, 'wbmailer_smtp_password', ''),
(40, 'fingerprint_with_ip_octets', '2'),
(41, 'secure_form_module', ''),
(42, 'mediasettings', '');



