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

$content = $database->fetchValue(
    'SELECT `content` FROM `{TP}mod_wysiwyg` WHERE `section_id` = ?',
    [(int) $section_id]
) ?? '';

echo str_replace('{SYSVAR:MEDIA_REL}', WB_URL . MEDIA_DIRECTORY, $content);