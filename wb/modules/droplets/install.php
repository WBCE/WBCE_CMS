<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: install.php 1544 2011-12-15 15:57:59Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/droplets/install.php $
 * @lastmodified    $Date: 2011-12-15 16:57:59 +0100 (Do, 15. Dez 2011) $
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

	// global $admin;

	$msg = array();
	$sql  = 'DROP TABLE IF EXISTS `'.TABLE_PREFIX.'mod_droplets` ';
	if( !$database->query($sql) ) {
		$msg[] = $database->get_error();
	}

	$sql  = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_droplets` ( ';
	$sql .= '`id` INT NOT NULL auto_increment, ';
	$sql .= '`name` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL, ';
	$sql .= '`code` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL , ';
	$sql .= '`description` TEXT  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, ';
	$sql .= '`modified_when` INT NOT NULL default \'0\', ';
	$sql .= '`modified_by` INT NOT NULL default \'0\', ';
	$sql .= '`active` INT NOT NULL default \'0\', ';
	$sql .= '`admin_edit` INT NOT NULL default \'0\', ';
	$sql .= '`admin_view` INT NOT NULL default \'0\', ';
	$sql .= '`show_wysiwyg` INT NOT NULL default \'0\', ';
	$sql .= '`comments` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL, ';
	$sql .= 'PRIMARY KEY ( `id` ) ';
	$sql .= ') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	if( !$database->query($sql) ) {
		$msg[] = $database->get_error();
	}

	//add all droplets from the droplet subdirectory
	$folder=opendir(WB_PATH.'/modules/droplets/example/.');
	$names = array();
	while ($file = readdir($folder)) {
		$ext=strtolower(substr($file,-4));
		if ($ext==".php"){
			if ($file<>"index.php" ) {
				$names[count($names)] = $file;
			}
		}
	}
	closedir($folder);

	foreach ($names as $dropfile)
	{
		$droplet = addslashes(getDropletCodeFromFile($dropfile));
		if ($droplet != "")
		{
			$description = "Example Droplet";
			$comments = "Example Droplet";
			$cArray = explode("\n",$droplet);
			if (substr($cArray[0],0,3) == "//:") {
				$description = trim(substr($cArray[0],3));
				array_shift ( $cArray );
			}
			if (substr($cArray[0],0,3) == "//:") {
				$comments = trim(substr($cArray[0],3));
				array_shift ( $cArray );
			}
			$droplet = implode ( "\n", $cArray );
			$name = substr($dropfile,0,-4);
			$modified_when = time();
			$modified_by = (method_exists($admin, 'get_user_id') && ($admin->get_user_id()!=null) ? $admin->get_user_id() : 1);
			$sql  = 'INSERT INTO `'.TABLE_PREFIX.'mod_droplets` SET ';
			$sql .= '`name` = \''.$name.'\', ';
			$sql .= '`code` = \''.$droplet.'\', ';
			$sql .= '`description` = \''.$description.'\', ';
			$sql .= '`comments` = \''.$comments.'\', ';
			$sql .= '`active` = 1, ';
			$sql .= '`modified_when` = '.$modified_when.', ';
			$sql .= '`modified_by` = '.$modified_by;
			if( !$database->query($sql) ) {
				$msg[] = $database->get_error();
			}
			// do not output anything if this script is called during fresh installation
			// if (method_exists($admin, 'get_user_id')) echo "Droplet import: $name<br/>";
		}
	}

	function getDropletCodeFromFile ( $dropletfile ) {
		$data = '';
		$filename = WB_PATH."/modules/droplets/example/".$dropletfile;
		if (file_exists($filename)) {
			$filehandle = fopen ($filename, "r");
			$data = fread ($filehandle, filesize ($filename));
			fclose($filehandle);
			// unlink($filename); doesnt work in unix
		}
		return $data;
	}
