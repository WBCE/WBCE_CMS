<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/update.php
 * @brief      Upgrade script with live streaming progress feedback.
 *             Uses the same visual style as the installer (install.css, logo, cards).
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Phase 1 (GET / first visit):  Backup confirmation form
 * Phase 2 (POST confirmed):     Streamed update progress
 */

// ── Initialize and Boot WBCE ─────────────────────────────────────────────────

defined('WB_UPGRADE_SCRIPT') or define('WB_UPGRADE_SCRIPT', true);
defined('WB_SECFORM_TIMEOUT') or define('WB_SECFORM_TIMEOUT', 7200);
defined('WB_PATH') or define('WB_PATH', rtrim(dirname(__DIR__), '/\\'));

require_once WB_PATH . '/config.php';
require_once __DIR__ . '/helper_functions.php';

// Guard anything that might be missing on very old installs
defined('ADMIN_PATH') or 
    define('ADMIN_PATH', WB_PATH . '/' . (defined('ADMIN_DIRECTORY') ? ADMIN_DIRECTORY : 'admin'));
defined('ADMIN_URL')  or 
    define('ADMIN_URL',  (defined('WB_URL') ? WB_URL : '') . '/' . (defined('ADMIN_DIRECTORY') ? ADMIN_DIRECTORY : 'admin'));


// ── Language ──────────────────────────────────────────────────────────────────
$langDir = __DIR__ . '/languages/';
$enFile = $langDir . 'EN.php';
if (is_readable($enFile)) include $enFile;

// Safe default before any condition
$langCode = 'EN';
if (!empty($_GET['lang'])  && is_string($_GET['lang']))          $langCode = strtoupper(trim($_GET['lang']));
elseif (!empty($_POST['lang']) && is_string($_POST['lang']))     $langCode = strtoupper(trim($_POST['lang']));
elseif (defined('DEFAULT_LANGUAGE') && DEFAULT_LANGUAGE !== '')  $langCode = strtoupper(trim(DEFAULT_LANGUAGE));
if (!preg_match('/^[A-Z]{1,5}$/', $langCode))                    $langCode = 'EN';

if ($langCode !== 'EN') {
    $filePath = $langDir . $langCode . '.php';
    if (is_readable($filePath)) include $filePath;
}

function check_wb_tables(): array
{
    global $database, $table_list;
    $search_for = addcslashes(TABLE_PREFIX, '%_');
    $result     = $database->query('SHOW TABLES LIKE ?', [$search_for . '%']);
    $found      = [];
    while ($row = $result->fetchRow()) {
        $name = substr($row[0], strlen(TABLE_PREFIX));
        if (in_array($name, $table_list)) $found[] = $name;
    }
    return $found;
}
// ── Decide which phase to show ────────────────────────────────────────────────

$confirmed = isset($_POST['backup_confirmed']) && $_POST['backup_confirmed'] === 'confirmed';

// ── Phase 1: Backup confirmation form ────────────────────────────────────────

if (!$confirmed) {

    // Need a DB connection just for reading version — isolated try/catch
    // Settings::setup() runs in initialize.php (via config.php).
    // All settings are now available as constants — no extra DB query needed.
    $oldWbceVersion = defined('WBCE_VERSION') ? (string)WBCE_VERSION : '';
    $oldWbceTag     = defined('WBCE_TAG')     ? (string)WBCE_TAG     : '';

    $vFile = ADMIN_PATH . '/interface/version.php';
    if (is_readable($vFile)) include $vFile;

    $oldVersion = $oldWbceVersion ? "WBCE v{$oldWbceVersion}" : 'Unknown / WB Classic';
    $oldTag     = $oldWbceTag;
    $newVersion = defined('NEW_WBCE_VERSION') ? 'WBCE v' . NEW_WBCE_VERSION : 'WBCE (current)';
    $newTag     = defined('NEW_WBCE_TAG') ? NEW_WBCE_TAG : '';

    ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WBCE CMS Update</title>
<link href="./assets/install.css" rel="stylesheet">
<link href="./favicon.png" rel="icon" type="image/png">
</head>
<body>
<div class="page-wrapper">

  <header class="site-header">
    <div class="logo-wrap">
      <img src="./assets/wbce_logo.svg" alt="WBCE CMS Logo">
    </div>
    <div class="wizard-title">
      <span class="version-badge">Update Wizard
        <svg width="10" height="10" viewBox="0 0 10 10"><circle cx="5" cy="5" r="4" fill="#f6ad55" opacity=".8"/></svg>
        <?= defined('NEW_WBCE_VERSION') ? _h(NEW_WBCE_VERSION) : '' ?>
      </span>
    </div>
  </header>

  <div class="card">
    <div class="card-header">
      <div class="step-badge" style="background:linear-gradient(135deg,#f6ad55,#ed8936)">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
      </div>
      <div class="card-header-text">
        <h2><?= _h($TXT['upgrade_heading'] ?? 'Before you start — create a backup!') ?></h2>
        <p><?= _h($TXT['upgrade_subheading'] ?? 'This script modifies the database and file structure.') ?></p>
      </div>
    </div>

    <div class="card-body">

      <div class="version-comparison">
        <div class="version-box current">
          <div class="label"><?= _h($TXT['current_version'] ?? 'Installed') ?></div>
          <div class="version-number"><?= _h($oldVersion) ?></div>
          <div class="version-tag"><?= _h($oldTag) ?></div>
        </div>
        <div class="arrow-container">
          <div class="big-arrow">→</div>
          <div class="arrow-label">Upgrade</div>
        </div>
        <div class="version-box target">
          <div class="label"><?= _h($TXT['target_version'] ?? 'Target') ?></div>
          <div class="version-number"><?= _h($newVersion) ?></div>
          <div class="version-tag"><?= _h($newTag) ?></div>
        </div>
      </div>

      <div class="req-item"><code><?=$TXT['disclaimer'] ?></code></div>
      <br>
      <div class="warningbox" style="margin-bottom:20px">
        <p><?= sprintf($TXT['upgrade_warning'], '/pages') ?></p>
      </div>
      <br>

      <form id="update-form" method="post" action="update.php">
        <input type="hidden" name="backup_confirmed" value="confirmed">
        <input type="hidden" name="lang" value="<?= _h($langCode) ?>">

        <label class="check-label">
          <input type="checkbox" id="confirm-check" required>
          <?= sprintf($TXT['upgrade_confirm'], '/pages') ?>
        </label>

        <div style="margin-top:20px">
          <button type="submit" class="install-btn" id="btn-update" disabled>
            &#9654;&nbsp; <?= _h($TXT['proceed_upgrade'] ?? 'Start Update') ?>
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- Progress card — hidden until update starts -->
  <div id="progress-card" class="card" style="display:none">
    <div class="card-header">
      <div class="step-badge" style="background:linear-gradient(135deg,#48bb78,#38a169)">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
      </div>
      <div class="card-header-text">
        <h2>Update in progress&hellip;</h2>
        <p id="phase-label">Starting&hellip;</p>
      </div>
    </div>
    <div class="card-body">
      <div class="progress-wrap">
        <div class="progress-track">
          <div class="progress-fill" id="progress-fill" style="width:0%"></div>
        </div>
        <span class="progress-pct" id="progress-label">0%</span>
      </div>
      <div class="install-log" id="update-log"></div>
      <div class="install-actions" id="update-actions" style="display:none"></div>
    </div>
  </div>

  <div class="page-footer"></div>
</div>

<script>
const I18N = {
    currLang:        <?= json_encode($langCode) ?>,
    upgradeComplete: <?= json_encode($TXT['update_complete']   ?? '✓ Update complete!') ?>,
    updateFailed:    <?= json_encode($TXT['update_failed']     ?? 'Update had errors — check the log above.') ?>,
    loginToBackend:  <?= json_encode($TXT['progress_go_admin'] ?? 'Login to Backend') ?>,
    runAgain:        <?= json_encode($TXT['run_again']         ?? 'Run again') ?>,
    startingUpdate:  <?= json_encode($TXT['starting_update']   ?? 'Starting update…') ?>
};
const ADMIN_URL = <?= json_encode(ADMIN_URL) ?>;
</script>
<script src="./assets/upgrade.js" type="text/javascript"></script>
</body>
</html>
<?php
    exit; // Phase 1 done — wait for POST
}

// ── Phase 2: Streaming update ─────────────────────────────────────────────────

header('Content-Type: text/html; charset=utf-8');
header('X-Accel-Buffering: no');
header('Cache-Control: no-cache');

// FIX: ob_implicit_flush() does NOT end buffers — must end them first
while (ob_get_level()) ob_end_flush();
ob_implicit_flush(true);

// log_ok is the only log helper missing from helper_functions.php.
// log_info / log_warn / log_err / log_sep / is_fatal are already defined there.
// Wrapping everything in one if() and redefining them would cause
// "Cannot redeclare function" Fatal Error → HTTP 500.
if (!function_exists('log_ok')) {
    function log_ok(string $msg): void
    {
        echo '<div class="log-ok">✓ ' . _h($msg) . "</div>\n";
        flush();
    }
}

set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    if (str_contains($errstr, 'already defined')) return true;
    $short = str_replace(WB_PATH, '', $errfile);
    $cls   = ($errno & (E_ERROR | E_USER_ERROR)) ? 'err' : 'warn';
    $icon  = ($errno & (E_ERROR | E_USER_ERROR)) ? '✗' : '⚠';
    echo '<div class="' . $cls . '">' . $icon . ' ' . _h($errstr)
       . " <small>($short:$errline)</small></div>\n";
    flush();
    return true;
});

// Load framework functions needed for load_module / load_template / load_language
require_once WB_PATH . '/framework/functions.php';
//// AddonService replaces functions.load_addons.php — no longer needed here
//    require_once WB_PATH . '/framework/functions.load_addons.php';
//}

$vFile = ADMIN_PATH . '/interface/version.php';
if (is_readable($vFile)) require_once $vFile;

// ── Connect to DB ─────────────────────────────────────────────────────────────

// $database is already created by initialize.php (via config.php)
// No second connection needed.
try {
    if (!isset($database) || !is_object($database)) {
        $database = new Database();
    }
    log_ok('Database connected');

    // Guard: prevent upgrade from WB-Classic < 2.7
    $_oldWbVer   = $database->fetchValue(
        'SELECT `value` FROM `{TP}settings` WHERE `name` = ? LIMIT 1', ['wb_version']
    );
    $_hasWbce    = $database->fetchValue(
        'SELECT `value` FROM `{TP}settings` WHERE `name` = ? LIMIT 1', ['wbce_version']
    );

    if ($_oldWbVer && !$_hasWbce) {
        if (version_compare($_oldWbVer, '2.7', '<')) {
            log_err("Cannot upgrade from WB-Classic {$_oldWbVer} — minimum is 2.7. Upgrade WB first.");
            exit;
        }
        log_info("Upgrading from WB-Classic {$_oldWbVer}");
    } else {
        log_info("Upgrading from WBCE " . ($database->fetchValue(
            'SELECT `value` FROM `{TP}settings` WHERE `name` = ?', ['wbce_version']
        ) ?: '?'));
    }

} catch (Throwable $e) {
    log_err('Database connection failed: ' . $e->getMessage());
    exit;
}

// ── Config ────────────────────────────────────────────────────────────────────

$table_list = [
    'addons','blocking','groups','pages','search','sections','settings','users',
    'mod_droplets','mod_menu_link','mod_miniform','mod_news_img_posts',
    'mod_outputfilter_dashboard','mod_sitemap','mod_wbstats_day',
];

$aDefaultThemes = ['wbce_flat_theme','argos_theme_reloaded','fraggy-backend-theme'];

// FIX: DEFAULT_THEME constant not yet defined — read directly from DB
$_currentTheme = (string)($database->fetchValue(
    "SELECT `value` FROM `{TP}settings` WHERE `name` = ?", ['default_theme']
) ?: '');
$DEFAULT_THEME = in_array($_currentTheme, $aDefaultThemes) ? $_currentTheme : 'wbce_flat_theme';

// Minimal admin stub — AddonService::runScript() and some module scripts use $admin
if (!isset($GLOBALS['admin'])) {
    $GLOBALS['admin'] = new class {
        public function isAdmin(): bool      { return true; }
        public function isSuperAdmin(): bool { return true; }
        public function isLoggedIn(): bool   { return true; }
        public function get_user_id(): int   { return 1; }
        public function getFTAN(): string    { return ''; }
        public function checkFTAN(): bool    { return true; }
        public function get_permission(string $p): bool { return true; }
    };
}
$admin = $GLOBALS['admin'];


// =============================================================================
// PHASE 1 — Ensure DB tables exist
// =============================================================================

log_sep('Database tables');

$sqlFile = __DIR__ . '/sql/install_struct.sql';
if (!is_readable($sqlFile)) {
    log_warn('install_struct.sql not found');
} else {
    $created = 0;
    $database->importSql(
        $sqlFile,
        null,                                                                    
        true,                                                                    // preserve — no DROP TABLE
        'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',     // {TABLE_ENGINE}  
        'collate utf8mb4_unicode_ci',                                           // {TABLE_COLLATION} 
        function (array $r, int $idx, int $total) use (&$created, $TXT): void {
            if (!$r['ok']) {
                log_warn('SQL: ' . $r['msg']);
            } elseif (str_starts_with($r['statement'], TABLE_PREFIX)) {
                $tbl = substr($r['statement'], strlen(TABLE_PREFIX));
                log_ok(sprintf($TXT['db_table_is_ready'] ?? 'Table `%s` ready', TABLE_PREFIX . $tbl));
                $created++;
            }
        }
    );
    log_ok("$created table(s) ensured");
}

$all_tables = check_wb_tables();

// =============================================================================
// PHASE 2 — Settings, DB fields, core module updates
// =============================================================================

log_sep('Settings & module updates');

Settings::set('default_theme',               $DEFAULT_THEME);
log_ok("default_theme = $DEFAULT_THEME");
Settings::set('er_level',                    'E3');
Settings::set('rename_files_on_upload',      'ph.*?,cgi,pl,pm,exe,com,bat,pif,cmd,src,asp,aspx,js,lnk,inc', false);
Settings::set('sec_anchor',                  'wb_', false);
Settings::set('redirect_timer',              '1500', false);
Settings::set('mediasettings',               '', false);
Settings::set('wb_secform_secret',           bin2hex(random_bytes(12)), false);
Settings::set('wb_secform_secrettime',       '86400', false);
Settings::set('wb_secform_timeout',          '7200', false);
Settings::set('wb_secform_tokenname',        'formtoken', false);
Settings::set('wb_secform_usefp',            false, false);
Settings::set('fingerprint_with_ip_octets',  '0', false);
Settings::delete('secure_form_module');
log_ok('Settings updated');

// DB field additions
$newDbFields = [
    // table                 field                  definition
    ['{TP}pages',            'visibility_backup',   "VARCHAR(255) NOT NULL DEFAULT '' AFTER `visibility`"],
    ['{TP}mod_menu_link',    'redirect_type',      "INT           NOT NULL DEFAULT '302' AFTER `target_page_id`"],
    ['{TP}sections',         'namesection',         "VARCHAR(255) NULL"],
    ['{TP}users',            'signup_checksum',     "varchar(64)  NOT NULL DEFAULT ''"],
    ['{TP}users',            'signup_timestamp',    "int(11)      NOT NULL DEFAULT '0'"],
    ['{TP}users',            'signup_timeout',      "int(11)      NOT NULL DEFAULT '0'"],
    ['{TP}users',            'signup_confirmcode',  "varchar(64)  NOT NULL DEFAULT ''"],
    // new cols since 1.7.0
    ['{TP}addons',           'core',                "TINYINT(1)   NOT NULL DEFAULT 0"],
    ['{TP}addons',           'updated_when',        "INT(11)      NOT NULL DEFAULT 0"],
    ['{TP}addons',           'updated_by',          "INT(11)      NOT NULL DEFAULT 0"],
];
// DB field additions — uses Database::fieldExists() / addField()
$fldNum = $fldAdd = $fldErr = 0;
foreach ($newDbFields as [$table, $field, $def]) {
    $fldNum++;
    if ($database->fieldExists($table, $field)) {
        log_info("`$table`.`$field` already exists — skipped");
        continue;
    }
    $database->addField($table, $field, $def);
    if ($database->hasError()) {
        log_warn("`$field` in `$table`: " . $database->getError());
        $fldErr++;
    } else {
        log_ok("`$field` added to `$table`");
        $fldAdd++;
    }
}
// `{TP}pages`.`slug` — separate from $newDbFields because it needs its own index
if (!$database->fieldExists('{TP}pages', 'slug')) {
    $database->addField('{TP}pages', 'slug', "VARCHAR(255) NULL DEFAULT NULL AFTER `link`");
    // addField() strips AFTER automatically for SQLite
    if ($database->hasError()) {
        log_warn('`slug` in `{TP}pages`: ' . $database->getError());
    } else {
        log_ok('`slug` added to `{TP}pages`');
        // Unique index — idempotent on MySQL 8+, MariaDB 10.1.4+, SQLite
        $database->query("CREATE UNIQUE INDEX IF NOT EXISTS `uniq_slug` ON `{TP}pages` (`slug`)");
        if ($database->hasError()) {
            log_warn('Index `uniq_slug` on `{TP}pages`: ' . $database->getError());
        } 
    }
} else {
    log_info('`{TP}pages`.`slug` already exists — skipped');
}



$dbFieldsLog = "{$fldNum} DB fields checked";
if($fldAdd > 0){
    $dbFieldsLog .= ", {$fldAdd} fields added";    
}
if($fldErr > 0){
    $dbFieldsLog .= ", {$fldErr} errors";    
}
log_ok($dbFieldsLog);

// Users table fixes
foreach ([
    "ALTER TABLE `{TP}users` CHANGE `timezone` `timezone` VARCHAR(11) NOT NULL DEFAULT ''",
    "ALTER TABLE `{TP}users` CHANGE `login_ip` `login_ip` VARCHAR(50) NOT NULL DEFAULT ''",
    "UPDATE `{TP}users` SET `timezone` = ''",
    "UPDATE `{TP}users` SET `group_id` = CAST(groups_id AS SIGNED)",
    "UPDATE `{TP}users` SET `group_id` = 1 WHERE FIND_IN_SET('1', groups_id) > '0'",
] as $usql) {
    $database->query($usql);
    if ($database->hasError()) log_warn($database->getError());
}
log_ok('Users table updated');

// ── Core modules — install or upgrade ────────────────────────────────────────
$coreModules = [
    //$mod                     => $cfg[0] or $tableKey,          $install,  $upgrade
    'captcha_control'          => ['upgrade'], 
    'errorlogger'              => ['install'], 
    'droplets'                 => ['mod_droplets',               'install', 'upgrade'],
    'menu_link'                => ['mod_menu_link',              'install', 'upgrade'],
    'miniform'                 => ['mod_miniform',               'install', 'upgrade'],
    'news_img'                 => ['mod_news_img_posts',         'install', 'upgrade'],
    'outputfilter_dashboard'   => ['mod_outputfilter_dashboard', 'install', 'upgrade'],
    'sitemap'                  => ['mod_sitemap',                'install', 'upgrade'],
    'wbstats'                  => ['mod_wbstats_day',            'install', 'upgrade'],
];

foreach ($coreModules as $mod => $cfg) {
    $infoFile = WB_PATH . "/modules/$mod/info.php";
    if (!file_exists($infoFile)) continue;

    // Determine install vs upgrade
    if (count($cfg) === 1) {
        $script = $cfg[0]; // not a table col but an action (upgrade|install)
    } else {
        [$tableKey, $install, $upgrade] = $cfg;
        $script = in_array($tableKey, $all_tables) ? $upgrade : $install;
    }

    $scriptFile = WB_PATH . "/modules/$mod/$script.php";
    if (is_readable($scriptFile)) {
        require_once $scriptFile;
        log_ok("{$TXT['module']}: `$mod` — $script {$TXT['log_done']}");
    } else {
        log_warn("{$TXT['module']}: `$mod` — $script.php {$TXT['log_not_found']}");
    }
}

// ── Setting-gated modules: run install only when the setting is absent  ──────
$settingGatedModules = [
    // module_dir       => {TP}settings setting-name 
    'CodeMirror_Config' => 'cmc_cfg',
    'tool_debug_dump'   => 'debug_dump_cfg',
];

foreach ($settingGatedModules as $mod => $settingKey) {
    if (!file_exists(WB_PATH . "/modules/$mod/info.php")) continue;

    if (Settings::get($settingKey) !== null) {
        log_info(sprintf($TXT['module_already_configured'] ?? 'Module `%s` already configured — skipped', $mod));
        continue;
    }
    $file = WB_PATH . "/modules/$mod/install.php";
    if (is_readable($file)) {
        require_once $file;
        log_ok("Module `$mod` installed");
    } else {
        log_warn("Module `$mod` — install.php not found");
    }
}

// ── Remove old conflicting modules ────────────────────────────────────────────
foreach (['output_filter', 'opf_simple_backend'] as $old) {
    $uninstall = WB_PATH . "/modules/$old/uninstall.php";
    if (file_exists($uninstall)) {
        include_once $uninstall;
        if (function_exists('opf_io_rmdir')) opf_io_rmdir(WB_PATH . "/modules/$old");
        $database->query("DELETE FROM `{TP}addons` WHERE `directory` = ? AND `type` = 'module'", [$old]);
        log_ok("Old module `$old` removed");
    }
}

// Search table fix
$database->query("UPDATE `{TP}search` SET `value`='false' WHERE `name`='cfg_enable_old_search'");

// =============================================================================
// PHASE 3 — Remove deprecated files and directories
// =============================================================================

log_sep('Removing deprecated files and directories');

$itemsToRemove = function_exists('arrayFromTextFile')
    ? arrayFromTextFile(__DIR__ . '/update_remove_list.txt')
    : [];

$pathMap = [
    '[ROOT]'      => '',
    '[ACCOUNT]'   => '/account',
    '[ADMIN]'     => '/' . substr(ADMIN_PATH, strlen(WB_PATH) + 1),
    '[FRAMEWORK]' => '/framework',
    '[INCLUDE]'   => '/include',
    '[LANGUAGES]' => '/languages',
    '[MEDIA]'     => defined('MEDIA_DIRECTORY') ? MEDIA_DIRECTORY : '/media',
    '[MODULES]'   => '/modules',
    '[PAGES]'     => defined('PAGES_DIRECTORY') ? PAGES_DIRECTORY : '/pages',
    '[TEMPLATE]'  => '/templates',
];

$removedFiles = $removedDirs = $failedRemove = 0;

foreach ($itemsToRemove as $item) {
    $path = WB_PATH . strtr($item, $pathMap);

    if (function_exists('removePath')) {
        $signal = removePath($path, false);
        switch ($signal) {
            case 'RM_FILE_OK':                 $removedFiles++; break;
            case 'RM_DIR_OK':                  $removedDirs++;  break;
            case 'RM_PATH_PERMISSION_DENIED':
            case 'RM_PATH_COULD_NOT_REMOVE':   $failedRemove++; break;
        }
        if (function_exists('log_path_removal')){
            if($signal === 'RM_PATH_NOT_FOUND') {
                // don't log if path doesn't exist
                continue;
            }
            log_path_removal($signal, $item);
        }
    } else {
        // Fallback: simple remove
        if (is_file($path)) {
            @unlink($path) ? $removedFiles++ : $failedRemove++;
        } elseif (is_dir($path)) {
            @rmdir($path) ? $removedDirs++ : $failedRemove++;
        }
    }
}

$summary = sprintf($TXT['paths_removed'] ?? '%d file(s) and %d director(ies) removed', $removedFiles, $removedDirs);
if ($failedRemove > 0) {
    $summary .= sprintf($TXT['failed_to_remove'] ?? ' — %d could not be removed', $failedRemove);
}
log_ok($summary);

// =============================================================================
// PHASE 4 — Reload all addons + set version
// =============================================================================

// Rebuild folder protect files
if (function_exists('rebuildFolderProtectFile')) {
    $mediaPath = WB_PATH . (defined('MEDIA_DIRECTORY') ? MEDIA_DIRECTORY : '/media');
    $postsPath = WB_PATH . (defined('PAGES_DIRECTORY') ? PAGES_DIRECTORY : '/pages') . '/posts';
    if (is_dir($mediaPath)) {
        $n = count(rebuildFolderProtectFile($mediaPath));
        log_ok("Protect files rebuilt in media/ ($n files)");
    }
    if (is_dir($postsPath)) {
        $n = count(rebuildFolderProtectFile($postsPath));
        log_ok("Protect files rebuilt in pages/posts/ ($n files)");
    }
}

$database->query("UPDATE `{TP}search` SET `value`='<tr><td><p>[TEXT_NO_RESULTS]</p></td></tr>' WHERE `name`='no_results'");

// =============================================================================
log_sep('Reloading add-ons');
// =============================================================================
// TRUNCATE + reload: re-register all addons in DB without running any scripts.
// Uses AddonService::dbRegister() directly — no precheck, no install/upgrade.php.

require_once WB_PATH . '/framework/AddonService.php';

$database->query("TRUNCATE `{TP}addons`"); // get rid of the entire table content

$addonService = new AddonService();
$modOk = $tplOk = $langOk = 0;

// ── Modules ──────────────────────────────────────────────────────────────────
foreach (glob(WB_PATH . '/modules/*/') ?: [] as $dir) {
    if (!file_exists($dir . 'info.php')) continue;
    foreach ($addonService->dbRegister($dir, 'module') as $r) {
        $msg = isset($SIGNAL[$r['signal']])
            ? sprintf($SIGNAL[$r['signal']], 'module', $r['label'])
            : $r['label'];
        if (in_array($r['signal'], ['ADDON_INSERTED_OK', 'ADDON_UPDATED_OK'], true)) {
            log_ok($msg); $modOk++;
        } elseif ($r['signal'] !== 'ADDON_ALREADY_CURRENT') {
            log_warn($msg);
        }
    }
    flush();
}
log_ok($MENU['MODULES'] . ": $modOk reloaded");

// ── Templates ─────────────────────────────────────────────────────────────────
foreach (glob(WB_PATH . '/templates/*/') ?: [] as $dir) {
    if (!file_exists($dir . 'info.php')) continue;
    foreach ($addonService->dbRegister($dir, 'template') as $r) {
        $msg = isset($SIGNAL[$r['signal']])
            ? sprintf($SIGNAL[$r['signal']], 'template', $r['label'])
            : $r['label'];
        if (in_array($r['signal'], ['ADDON_INSERTED_OK', 'ADDON_UPDATED_OK'], true)) {
            log_ok($msg); $tplOk++;
        } elseif ($r['signal'] !== 'ADDON_ALREADY_CURRENT') {
            log_warn($msg);
        }
    }
    flush();
}
log_ok($MENU['TEMPLATES'] . ": $tplOk reloaded");

// ── Languages ─────────────────────────────────────────────────────────────────
foreach (glob(WB_PATH . '/languages/??.php') ?: [] as $file) {
    foreach ($addonService->dbRegister($file, 'language') as $r) {
        $msg = isset($SIGNAL[$r['signal']])
            ? sprintf($SIGNAL[$r['signal']], 'language', $r['label'])
            : $r['label'];
        if (in_array($r['signal'], ['ADDON_INSERTED_OK', 'ADDON_UPDATED_OK'], true)) {
            log_ok($msg); $langOk++;
        } elseif ($r['signal'] !== 'ADDON_ALREADY_CURRENT') {
            log_warn($msg);
        }
    }
    flush();
}
log_ok($MENU['LANGUAGES'] . ": $langOk reloaded");

// ── Set new version ───────────────────────────────────────────────────────────
if (defined('NEW_WBCE_VERSION')) {
    Settings::set('wbce_version', NEW_WBCE_VERSION);
    Settings::set('wbce_tag',     NEW_WBCE_TAG);
    Settings::set('wb_version',   defined('VERSION')  ? VERSION  : '');
    Settings::set('wb_revision',  defined('REVISION') ? REVISION : '');
    Settings::set('wb_sp',        defined('SP')       ? SP       : '');
    log_ok('Version updated to ' . NEW_WBCE_VERSION . ' (' . NEW_WBCE_TAG . ')');
}

Settings::set('app_name', 'phpsessid-' . rand(1000, 9999));
$database->query("TRUNCATE `{TP}dbsessions`"); // will be instantiated anew all by itself
Settings::exportSnapshot(true);
log_ok('Settings snapshot written');

echo '<div class="ok" style="font-weight:700;margin-top:8px">✓ ── Update complete ──</div>' . "\n";
flush();