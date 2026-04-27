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

defined('WB_PATH') or die();

// 1. Upgrade table engine + collation to InnoDB / utf8mb4
$database->query(
    "ALTER TABLE `{TP}mod_wysiwyg`
     CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
);
if ($database->hasError()) user_error('wysiwyg charset: ' . $database->getError());

$database->query("ALTER TABLE `{TP}mod_wysiwyg` ENGINE = InnoDB");
if ($database->hasError()) user_error('wysiwyg engine: ' . $database->getError());

// 2. Convert stored absolute media URLs to portable placeholder
$database->query(
    "UPDATE `{TP}mod_wysiwyg`
     SET `content` = REPLACE(`content`, ?, ?)
     WHERE `content` LIKE ?",
    ['"' . WB_URL . MEDIA_DIRECTORY, '"{SYSVAR:MEDIA_REL}', '%' . WB_URL . MEDIA_DIRECTORY . '%']
);
if ($database->hasError()) {
    user_error('wysiwyg media replace: ' . $database->getError());
}