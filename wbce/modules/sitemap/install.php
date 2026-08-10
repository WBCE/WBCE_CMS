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

if(defined('WB_PATH')) {

	// DDL lives in install_struct.sql and goes through importSql() 
	foreach ($database->importSql(__DIR__ . '/install_struct.sql') as $r) {
		if (!$r['ok']) {
			echo "<h2>DB error creating " . h($r['statement']) . ": " . h($r['msg']) . "</h2>";
		}
	}
}
