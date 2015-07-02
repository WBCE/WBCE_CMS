<?php
/**
 *
 * @category        admin
 * @package         media
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: parameters.php 5 2015-04-27 08:02:19Z luisehahne $
 * @filesource      $HeadURL: https://localhost:8443/svn/wb283Sp4/SP4/branches/wb/admin/media/parameters.php $
 * @lastmodified    $Date: 2015-04-27 10:02:19 +0200 (Mo, 27. Apr 2015) $
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

