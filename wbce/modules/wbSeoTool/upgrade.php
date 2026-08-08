<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * upgrade.php
 * Runs when an already-installed wbSeoTool is updated to a newer version.
 *
 * Pre-1.1.0 installs kept their config in a module-owned table
 * ({TP}mod_page_seo_tool, single `settings_json` column). 1.1.0+ stores
 * the same JSON blob as a single row in the shared {TP}settings table
 * (Settings::set('seo_cfg', ...)) — same mechanism as CodeMirror_Config.
 *
 * If the old table is still present, migrate its content into Settings
 * and drop it. If Settings already has 'seo_cfg' (e.g. a fresh 1.1.0+
 * install being "upgraded" from itself), leave it untouched.
 *
 * @package     wbSeoTool
 * @author      Christian M. Stefan (https://www.wbEasy.de/)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) exit("Cannot access this file directly " . __FILE__);

/** @var Database $database */

$jsonSettings = $database->fetchValue('SELECT `settings_json` FROM `{TP}mod_page_seo_tool` LIMIT 1');
$bOldTableExists = !$database->hasError();

if ($bOldTableExists) {
    if (!Settings::exists('seo_cfg')) {
        $aOld = json_decode((string) $jsonSettings, true);
        if (!is_array($aOld)) {
            $aOld = [];
        }
        // Fields introduced after the old table stopped being written to
        $aOld += ['menuTitleConfig' => ['use' => false]];

        Settings::set('seo_cfg', json_encode($aOld));
    }

    $database->query('DROP TABLE IF EXISTS `{TP}mod_page_seo_tool`');
}

// Existing seo_cfg (installs already past 1.1.0) may still predate the
// menu-title feature — backfill the key so modify_config.php always finds it.
$aCfg = json_decode((string) Settings::get('seo_cfg', '{}'), true);
$bCfgChanged = false;
if (is_array($aCfg)) {
    if (!isset($aCfg['menuTitleConfig'])) {
        $aCfg['menuTitleConfig'] = ['use' => false];
        $bCfgChanged = true;
    }
    // 1.3.0 — added an explicit "maximum" threshold (status dots / duplicate
    // check) alongside the existing minimum/optimum pair.
    if (!isset($aCfg['iTitleCount']['maximum'])) {
        $aCfg['iTitleCount']['maximum'] = 60;
        $bCfgChanged = true;
    }
    if (!isset($aCfg['iDescriptionCount']['maximum'])) {
        $aCfg['iDescriptionCount']['maximum'] = 160;
        $bCfgChanged = true;
    }
    if ($bCfgChanged) {
        Settings::set('seo_cfg', json_encode($aCfg));
    }
}

// remove files and directories that are not needed any longer
// (1.2.0 — CSS/JS/icons consolidated into /assets, /skel renamed to /twig)
$obsoleteFilesAndDirs = [
    '/css',           // pageTree.css moved to /assets
    '/skel',          // directory renamed to /twig
    '/icons',         // action/visibility icons replaced by inline Tabler SVGs in /assets
    '/CHANGLOG.txt',  // superseded by the version history in info.php
];
foreach ($obsoleteFilesAndDirs as $rec) {
    $path = __DIR__ . $rec;
    $signal = removePath($path, 0, 0);
    echo(sprintf($SIGNAL[$signal], $rec)) . '<br>';
}
