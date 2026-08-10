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
 * @version         0.2.5.8
 * @lastmodified    November 21, 2025
 *
 */


defined('WB_PATH') OR die(header('Location: ../index.php'));

// DDL lives in install_struct.sql and goes through importSql() rather than a raw query() 
// drops + recreates all tables on every module install/reinstall).
foreach ($database->importSql(__DIR__ . '/install_struct.sql', null, false) as $r) {
	if (!$r['ok']) {
		echo "<h2>DB error creating " . h($r['statement']) . ": " . h($r['msg']) . "</h2>";
	}
}
