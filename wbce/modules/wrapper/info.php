<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

$module_directory = 'wrapper';
$module_name = 'Wrapper';
$module_function = 'page';
$module_version = '2.9.0';
$module_platform = '1.7.x';
$module_author = 'Ryan Djurovich, Christian M. Stefan';
$module_license = 'GNU General Public License';
$module_description = 'This module allows you to wrap your site around another using an inline frame';

/**
 * Version history
 *
 * 2.9.0 Christian M. Stefan 14.08.2026
 *       - modify.php reworked to render via Twig (twig/modify.twig) instead
 *         of the old phplib Template + modify.htt
 *       - copyright headers unified to the modern WBCE format across
 *         index.php, add.php, delete.php, save.php, view.php, install.php,
 *         upgrade.php (old WebsiteBaker/SVN headers removed)
 *       - upgrade.php's MyISAM engine enforcement now skipped on SQLite
 *         (was invalid SQL there — getTableEngine() returns a fixed
 *         'SQLite' sentinel, never 'myisam', so the ALTER always ran)
 *
 * 2.8.4 Christian M. Stefan 10.08 2026
 *       - multi-driver SQL corrections (MySQL/SQLite) as preparation for
 *         experimental SQLite readiness: install.php's table DDL moved to
 *         install_struct.sql, routed through Database::importSql() instead
 *         of raw query() so it gets normalized for the active driver
 *
 **/
