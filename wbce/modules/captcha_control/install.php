<?php
/**
 *
 * @category        modules
 * @package         captcha_control
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: install.php 1536 2011-12-10 04:22:29Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/captcha_control/install.php $
 * @lastmodified    $Date: 2011-12-10 05:22:29 +0100 (Sa, 10. Dez 2011) $
 *
 */

// prevent this file from being accessed directly
/* -------------------------------------------------------- */
if (!defined('WB_PATH')) { die('Cannot access this file directly'); }
/* -------------------------------------------------------- */
// create tables from sql dump file
if (is_readable(dirname(__FILE__).'/install_struct.sql')) {
    $database->SqlImport(dirname(__FILE__).'/install_struct.sql', TABLE_PREFIX, false);
}
