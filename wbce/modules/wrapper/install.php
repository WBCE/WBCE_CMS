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

if(defined('WB_URL')) {

	// DDL lives in install_struct.sql and goes through importSql() rather
	// than raw query() — it normalises MySQL-only syntax (ENGINE=/CHARSET=/
	// COLLATE=) for SQLite the same way the core installer's SQL files are
	// handled.
	foreach ($database->importSql(__DIR__ . '/install_struct.sql') as $r) {
		if (!$r['ok']) {
			echo "<h2>DB error creating " . h($r['statement']) . ": " . h($r['msg']) . "</h2>";
		}
	}
}
