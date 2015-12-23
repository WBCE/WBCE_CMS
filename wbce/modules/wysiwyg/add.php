<?php
/**
 *
 * @category        modules
 * @package         wysiwyg
 * @author          WebsiteBaker Project
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: add.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: http://svn.websitebaker2.org/branches/2.8.x/wb/modules/wysiwyg/modify.php $
 * @lastmodified    $Date: 2011-01-10 13:21:47 +0100 (Mo, 10 Jan 2011) $
 *
 */
//no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// Insert an extra row into the database
$sql = 'INSERT INTO `'.TABLE_PREFIX.'mod_wysiwyg` '
     . 'SET `page_id`='.$page_id.', '
     .     '`section_id`='.$section_id.', '
     .     '`content`=\'\', '
     .     '`text`=\'\'';
$database->query($sql);

