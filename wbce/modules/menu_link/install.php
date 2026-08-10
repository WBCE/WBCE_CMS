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

// Prevent this file from being access directly
defined('WB_PATH') or die('Cannot access this file directly');

// DDL lives in install_struct.sql
foreach ($database->importSql(__DIR__ . '/install_struct.sql', null, false) as $r) {
    if (!$r['ok']) {
        echo "<h2>DB error creating " . h($r['statement']) . ": " . h($r['msg']) . "</h2>";
    }
}
