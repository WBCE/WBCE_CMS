<?php
/**
 *
 * @category        admin
 * @package         media
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: parameters.php 1529 2011-11-25 05:03:32Z Luisehahne $
 * @filesource		$HeadURL:  $
 * @lastmodified    $Date:  $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

function __unserialize($sObject) {  // found in php manual :-)
	$_ret = preg_replace_callback(
	                '!s:(\d+):"(.*?)";!', 
	                function($matches) {return 's:'.strlen($matches[2]).':"'.$matches[2].'";';}, 
	                $sObject 
	         );
	return unserialize($_ret);
}
$pathsettings = array();
if(DEFAULT_THEME != ' wb_theme') {
	$query = $database->query ( "SELECT * FROM ".TABLE_PREFIX."settings where `name`='mediasettings'" );
	if ($query && $query->numRows() > 0) {
		$settings = $query->fetchRow();
		$pathsettings = __unserialize($settings['value']);
	} else {
		$database->query ( "INSERT INTO ".TABLE_PREFIX."settings (`name`,`value`) VALUES ('mediasettings','')" );
	}
}

