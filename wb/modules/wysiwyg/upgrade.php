<?php
/**
 *
 * @category        modules
 * @package         wysiwyg
 * @author          WebsiteBaker Project
 * @copyright       2009-2012, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version      	$Id: upgrade.php 1576 2012-01-16 17:29:11Z darkviper $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/wysiwyg/upgrade.php $
 * @lastmodified    $Date: 2012-01-16 18:29:11 +0100 (Mo, 16. Jan 2012) $
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
	require_once(dirname(dirname(__FILE__)).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$msg = '';
$sTable = TABLE_PREFIX.'mod_wysiwyg';
if(($sOldType = $database->getTableEngine($sTable))) {
	if(('myisam' != strtolower($sOldType))) {
		if(!$database->query('ALTER TABLE `'.$sTable.'` Engine = \'MyISAM\' ')) {
			$msg = $database->get_error();
		}
	}
} else {
	$msg .= $database->get_error().'<br />';
}
// change internal absolute links into relative links
$sTable = TABLE_PREFIX.'mod_wysiwyg';
$sql  = 'UPDATE `'.$sTable.'` ';
$sql .= 'SET `content` = REPLACE(`content`, \'"'.WB_URL.MEDIA_DIRECTORY.'\', \'"{SYSVAR:MEDIA_REL}\')';
if (!$database->query($sql)) {
	$msg .= $database->get_error().'<br />';
}

// ------------------------------------