<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          Christian Sommer, WB-Project, Werner v.d. Decken
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: install.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/install.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
require_once( dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
if(!defined('WB_PATH')) { throw new IllegalFileException(); }
/* -------------------------------------------------------- */

$table = TABLE_PREFIX .'mod_output_filter';
$database->query("DROP TABLE IF EXISTS `$table`");

$database->query("CREATE TABLE IF NOT EXISTS `$table` (
    `sys_rel` INT NOT NULL DEFAULT '0',
    `email_filter` VARCHAR(1) NOT NULL DEFAULT '0',
    `mailto_filter` VARCHAR(1) NOT NULL DEFAULT '0',
    `at_replacement` VARCHAR(255) NOT NULL DEFAULT '(at)',
    `dot_replacement` VARCHAR(255) NOT NULL DEFAULT '(dot)'
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
);

// add default values to the module table
$database->query("INSERT INTO ".TABLE_PREFIX
    ."mod_output_filter (sys_rel,email_filter, mailto_filter, at_replacement, dot_replacement) VALUES ('0','1', '1', '(at)', '(dot)')");
