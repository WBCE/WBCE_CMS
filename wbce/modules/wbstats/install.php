<?php
/**
 *
 * @category        admintool
 * @package         wbstats
 * @author          Ruud Eisinga - dev4me.com
 * @link			https://dev4me.com/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 7 and higher
 * @version         0.2.5.12
 * @lastmodified    October 17, 2026
 *
 */


defined('WB_PATH') OR die(header('Location: ../index.php'));

// DDL lives in install_struct.sql (drops + recreates all tables on every
// module install/reinstall). WBCE 1.7.0+ imports it with importSql(); older
// cores (WBCE 1.6.x and earlier, WB 2.8) only have SqlImport().
$sStructFile = __DIR__ . '/install_struct.sql';

if (method_exists($database, 'importSql')) {
	foreach ($database->importSql($sStructFile, null, false) as $r) {
		if (!$r['ok']) {
			echo "<h2>DB error creating " . htmlspecialchars($r['statement']) . ": " . htmlspecialchars($r['msg']) . "</h2>";
		}
	}
} elseif (!$database->SqlImport($sStructFile, TABLE_PREFIX, false)) {
	echo "<h2>DB error creating tables: " . htmlspecialchars((string)$database->get_error()) . "</h2>";
}
