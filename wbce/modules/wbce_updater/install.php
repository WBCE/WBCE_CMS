<?php
/**
 * WBCE Update-Assistent - Install Script
 *
 * Wird beim Deinstallieren des Moduls ausgeführt
 * Räumt temporäre Dateien und Cache auf
 *
 * @category    module
 * @package     wbce_updater
 * @version     0.9.16
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// Must include code to stop this file being access directly
if (defined('WB_PATH') == false) {
    die("Cannot access this file directly");
}

require_once(WB_PATH.'/framework/functions.php');
make_dir(WB_PATH.'/var/logs', OCTAL_DIR_MODE, true);
