<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


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


