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

// Insert an extra row into the database
$sql = 'INSERT INTO `'.TABLE_PREFIX.'mod_wysiwyg` '
     . 'SET `page_id`='.$page_id.', '
     .     '`section_id`='.$section_id.', '
     .     '`content`=\'\', '
     .     '`text`=\'\'';
$database->query($sql);

