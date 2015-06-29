<?php
/**
 *
 * @category        module
 * @package         Form
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: uninstall.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/form/uninstall.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 * @description     
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

$database->query("DELETE FROM ".TABLE_PREFIX."search WHERE name = 'module' AND value = 'form'");
$database->query("DELETE FROM ".TABLE_PREFIX."search WHERE extra = 'form'");

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_fields`");
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_settings`");
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_submissions`");
