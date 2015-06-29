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
 * @version         $Id: install.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/SecureFormSwitcher/install.php $
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

$mod_path = (dirname(__FILE__));
require_once( $mod_path.'/language_load.php' );

$aDefault = array(
	'secure_form_module' => '',
	'wb_secform_secret' => '5609bnefg93jmgi99igjefg',
	'wb_secform_secrettime' => '86400',
	'wb_secform_timeout' => '7200',
	'wb_secform_tokenname' => 'formtoken',
	'wb_secform_usefp' => 'true',
	'wb_secform_useip' => '2',
);

db_update_key_value('settings', $aDefault );

