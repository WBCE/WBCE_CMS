<?php
/**
 * WBCE CMS - Droplets
 * Authenticated download of a droplet backup zip. The backup dir is denied
 * direct web access (see functions.inc.php), so downloads go through here.
 *
 * @license GNU GPL2 (or any later version)
 */

require_once '../../config.php';
require_once(WB_PATH . '/framework/class.admin.php');
$admin = new admin('admintools', 'admintools', false, false);
require_once(dirname(__FILE__) . '/functions.inc.php');

// Only droplets-tool users, and only with a valid FTAN on the download link.
if (!droplets_may_use_tool($admin) || !$admin->checkFTAN('GET')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

// droplets_backup_path() only accepts a .zip that really exists in the backup
// dir, so $_GET['file'] cannot traverse out of it.
$sFile = isset($_GET['file']) ? droplets_backup_path($_GET['file']) : false;
if ($sFile === false || !is_file($sFile)) {
    header('HTTP/1.1 404 Not Found');
    exit('Not found');
}

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($sFile) . '"');
header('Content-Length: ' . filesize($sFile));
header('X-Content-Type-Options: nosniff');
readfile($sFile);
exit();
