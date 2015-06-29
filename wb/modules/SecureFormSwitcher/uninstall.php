<?php
/**
 *
 * @category        modules
 * @package         SecureFormSwitcher
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.2
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: uninstall.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/SecureFormSwitcher/uninstall.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

require_once(WB_PATH.'/framework/class.database.php');
require_once(WB_PATH.'/framework/functions.php');

$sql  = 'DELETE FROM `'.TABLE_PREFIX.'settings` ';
$sql .= 'WHERE `name`=\'wb_secform_useip\' ';
$sql .=    'OR `name`=\'wb_secform_usefp\' ';
$sql .=    'OR `name`=\'wb_secform_tokenname\' ';
$sql .=    'OR `name`=\'wb_secform_timeout\' ';
$sql .=    'OR `name`=\'wb_secform_secrettime\' ';
$sql .=    'OR `name`=\'wb_secform_secret\' ';
$sql .=    'OR `name`=\'secure_form_module\' ';
$database->query($sql);
$dest_to_delete = WB_PATH.'/framework/SecureForm.mtab.php';
if(is_writeable(WB_PATH.'/framework') ) {
	@chmod($dest_to_delete, 0666);
	@unlink($dest_to_delete);
}

