<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

$sMediaUrl = WB_URL . MEDIA_DIRECTORY;

// Get content 
$content = '';
$sql = 'SELECT `content` FROM `' . TABLE_PREFIX . 'mod_wysiwyg` WHERE `section_id`=' . (int) $section_id;
if (($content = $database->get_one($sql))) {
    $content = str_replace('{SYSVAR:MEDIA_REL}', $sMediaUrl, $content);
}

echo $content;