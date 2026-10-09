<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga · www.dev4me.com (https://dev4me.com/)
 * @author          Christian M. Stefan  (https://www.wbEasy.de)
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.7.x
 *
 */

// Must include code to stop this file being access directly
if (defined('WB_PATH') == false) {
    die("Cannot access this file directly");
}


make_dir(WB_PATH.'/var/logs', OCTAL_DIR_MODE, true);

// remove files that are not needed any longer
$obsoleteFilesAndDirs = [
    '/include.php',  // WebsiteBaker support was dropped. Using pre-/init.
    '/backend.css', 
    '/backend.js',  // both moved to /assets
];
foreach ($obsoleteFilesAndDirs as $rec) {
    $path   = __DIR__ . $rec;
    $signal = removePath($path);
    // Read the signal through L_(), not the raw $SIGNAL array: upgrade.php is
    // require'd from inside upgrade_module(), so it runs in function scope,
    // where that global is not visible -- sprintf() was being handed null and
    // printed nothing at all. L_() reads the Lang registry and works in any
    // scope, and falls back to a readable string if a signal is ever untranslated.
    echo L_("SIGNAL['$signal']", $rec) . '<br>';
}
