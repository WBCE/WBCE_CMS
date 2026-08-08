<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * uninstall.php
 * Removes the module's setting from the shared {TP}settings table.
 *
 * @package     wbSeoTool
 * @author      Christian M. Stefan (https://www.wbEasy.de/)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) exit("Cannot access this file directly " . __FILE__);

Settings::delete('seo_cfg');

// Defensive cleanup: only relevant if a pre-1.1.0 install is uninstalled
// without ever having gone through upgrade.php (e.g. staged but never re-loaded).
/** @var Database $database */
$database->query('DROP TABLE IF EXISTS `{TP}mod_page_seo_tool`');

echo '<code>wbSeoTool settings removed.</code>';
