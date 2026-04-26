<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file       install/install_save.php
 * @brief      Processes the installation form and streams 
 *             live progress to the browser.
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('WBCE_INSTALL_PROCESS') or define('WBCE_INSTALL_PROCESS', true);
defined('WB_DEBUG')             or define('WB_DEBUG',     true);
defined('WB_INSTALLER')         or define('WB_INSTALLER', true);
defined('WB_SECFORM_TIMEOUT')   or define('WB_SECFORM_TIMEOUT', '7200');

if (WB_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if (!defined('SESSION_STARTED')) {
    session_name('wb-installer');
    session_start();
    define('SESSION_STARTED', true);
}
$_SESSION['ERROR_FIELD'] = [];
$_SESSION['message']     = [];

list($usec, $sec) = explode(' ', microtime());
$session_rand = rand(1000, 9999);

$_POST = array_map('trim', $_POST);

// ── INCLUDE LANGUAGE FILE(S) ────────────────────────────────────────────────
// We always include English first as base/fallback
$langDir = __DIR__ . '/languages/';
$enFile = $langDir . 'EN.php';
if (!file_exists($enFile) || !is_file($enFile) || !is_readable($enFile)) {
    die('Critical Error: Base language file (EN) not found!');
}
include $enFile;
// Get language from POST (from the install form) or fallback to EN
$langCode = strtoupper(trim($_POST['default_language'] ?? $_GET['lang'] ?? 'EN'));
if ($langCode !== 'EN' && preg_match('/^[A-Z]{1,5}$/', $langCode)) {
    $filePath = $langDir . $langCode . '.php';
    if (file_exists($filePath) && is_file($filePath) && is_readable($filePath)) {
        include $filePath;
    }
}

require_once 'helper_functions.php';

// ── Validate POST ─────────────────────────────────────────────────────────────
$_isError = false;

if (!isset($_POST['website_title'])) {
    set_error(d('e1: ') . 'Please fill-in the form below');
    $_isError = true;
}
if (!isset($_POST['wb_url']) || $_POST['wb_url'] == '') {
    set_error(d('e2: ') . 'Please enter an absolute URL', 'wb_url');
    $_isError = true;
} else {
    $wb_url = rtrim($_POST['wb_url'], '\\/');
}

if (!isset($_POST['default_timezone']) || !is_numeric($_POST['default_timezone'])) {
    set_error(d('e3: ') . 'Please select a valid default timezone', 'default_timezone');
    $_isError = true;
} else {
    $default_timezone = $_POST['default_timezone'] * 60 * 60;
}

$sLangDir = str_replace('\\', '/', dirname(__DIR__) . '/languages/');
$allowed_languages = preg_replace('/^.*\/([A-Z]{2})\.php$/iU', '\1', glob($sLangDir . '??.php'));
if (!isset($_POST['default_language']) || !in_array($_POST['default_language'], $allowed_languages)) {
    set_error(d('e4: ') . 'Please select a valid default backend language', 'default_language');
    $_isError = true;
} else {
    $default_language = $_POST['default_language'];
    if (!file_exists('../languages/' . $default_language . '.php')) {
        set_error(d('e5: ') . "Language file '{$default_language}.php' is missing.", 'default_language');
        $_isError = true;
    }
}

if (!isset($_POST['operating_system']) ||
    ($_POST['operating_system'] !== 'linux' && $_POST['operating_system'] !== 'windows')) {
    set_error(d('e6: ') . 'Please select a valid operating system');
    $_isError = true;
} else {
    $operating_system = $_POST['operating_system'];
}

if ($operating_system === 'windows') {
    $file_mode = '0666'; $dir_mode = '0777';
} elseif (isset($_POST['world_writeable']) && $_POST['world_writeable'] === 'true') {
    $file_mode = '0666'; $dir_mode = '0777';
} else {
    $file_mode = default_file_mode('../temp');
    $dir_mode  = default_dir_mode('../temp');
}

if (!isset($_POST['database_host']) || empty($_POST['database_host'])) {
    set_error(d('e7: ') . 'Please enter a database host name', 'database_host');
    $_isError = true;
} else { $database_host = $_POST['database_host']; }

if (!isset($_POST['database_username']) || $_POST['database_username'] === '') {
    set_error(d('e8: ') . 'Please enter a database username', 'database_username');
    $_isError = true;
} else { $database_username = $_POST['database_username']; }

if (!isset($_POST['database_password'])) {
    set_error(d('e9: ') . 'Please enter a database password', 'database_password');
    $_isError = true;
} else { $database_password = $_POST['database_password']; }

if (!isset($_POST['database_name']) || $_POST['database_name'] === '') {
    set_error(d('e10: ') . 'Please enter a database name', 'database_name');
    $_isError = true;
} elseif (preg_match('/[^a-z0-9_-]+/i', $_POST['database_name'])) {
    set_error(d('e11: ') . 'Only a-z, A-Z, 0-9, - and _ allowed in database name.', 'database_name');
    $_isError = true;
} else { $database_name = $_POST['database_name']; }

if (preg_match('/[^a-z0-9_]+/', $_POST['table_prefix'] ?? '')) {
    set_error(d('e12: ') . 'Only a-z, 0-9 and _ allowed in table prefix.', 'table_prefix');
    $_isError = true;
} else { $table_prefix = $_POST['table_prefix'] ?? 'wb_'; }

$website_title = !empty($_POST['website_title']) ? $_POST['website_title'] : 'My New Site';

if (!isset($_POST['admin_username']) || $_POST['admin_username'] === '') {
    set_error(d('e13: ') . 'Please enter a username for the Administrator account', 'admin_username');
    $_isError = true;
} else { $admin_username = $_POST['admin_username']; }

if (!isset($_POST['admin_email']) || $_POST['admin_email'] === '') {
    set_error(d('e14: ') . 'Please enter an email for the Administrator account', 'admin_email');
    $_isError = true;
} elseif (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,25})$/i', $_POST['admin_email'])) {
    set_error(d('e15: ') . 'Please enter a valid email address', 'admin_email');
    $_isError = true;
} else { $admin_email = $_POST['admin_email']; }

if (!isset($_POST['admin_password']) || $_POST['admin_password'] === '') {
    set_error(d('e16: ') . 'Please enter a password for the Administrator account', 'admin_password');
    $_isError = true;
} else { $admin_password = $_POST['admin_password']; }

if (!isset($_POST['admin_repassword']) || $_POST['admin_repassword'] === '') {
    set_error(d('e17: ') . 'Please re-enter the password', 'admin_repassword');
    $_isError = true;
} elseif (($admin_password ?? '') !== $_POST['admin_repassword']) {
    set_error(d('e18: ') . 'The two passwords do not match', 'admin_repassword');
    $_isError = true;
} else {
    if (preg_match('/[^a-zA-Z0-9\_\-\!\#\*\+\@\$\&\:]/', $admin_password)) {
        set_error(d('e19: ') . 'Invalid password characters', 'admin_password');
        $_isError = true;
    } elseif (strlen($admin_password) < 8) {
        set_error(d('e20: ') . 'Password too short (min 8 characters)', 'admin_password');
        $_isError = true;
    }
}

if ($_isError) {
    header('Location: index.php?sessions_checked=true');
    exit;
}

// Parse optional host:port
$database_port = null;
if (str_contains($database_host, ':')) {
    [$database_host, $portStr] = explode(':', $database_host, 2);
    $database_port = is_numeric($portStr) ? (int)$portStr : null;
}
$database_charset = 'utf8mb4';

// ── Quick DB connectivity check ───────────────────────────────────────────────
try {
    $dsn = "mysql:host={$database_host};dbname={$database_name};charset=utf8mb4";
    if ($database_port !== null) $dsn .= ";port={$database_port}";
    $dbtest = new PDO($dsn, $database_username, $database_password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $dbtest->query("SELECT 1");
    unset($dbtest);
} catch (PDOException $e) {
    $msg = $e->getMessage();
   
    if (str_contains($msg, 'Access denied'))          $sMsg = 'Access denied — check username and password.';
    elseif (str_contains($msg, 'Unknown database'))   $sMsg = 'Database does not exist.';
    elseif (str_contains($msg, 'Connection refused')) $sMsg = 'Connection refused — check hostname and port.';
    else                                              $sMsg = 'DB error: ' . _h($msg);
    
    set_error(d('e29: ') . 'Cannot connect to database. ' . $sMsg, '', true);
    exit;
}

// ── Streaming setup — from here: live output ──────────────────────────────────

header('Content-Type: text/html; charset=utf-8');
header('X-Accel-Buffering: no');
header('Cache-Control: no-cache');
while (ob_get_level()) ob_end_flush();

$admin_dir    = 'admin';
$install_date = date('F j, Y \a\t g:i A T');
$WB_PATH      = dirname(__DIR__);

flush();

$_fatal = false;
// ── Error handler ─────────────────────────────────────────────────────────────
set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    global $WB_PATH;
    if (str_contains($errstr, 'already defined')) return true;
    $short = str_replace($WB_PATH, '', $errfile);
    $cls   = ($errno & (E_ERROR | E_USER_ERROR)) ? 'err' : 'warn';
    $icon  = ($errno & (E_ERROR | E_USER_ERROR)) ? '✗' : '⚠';
    echo '<div class="log-' . $cls . '">' . $icon . ' ' . _h($errstr)
       . " <small>($short:$errline)</small></div>\n";
    flush();
    return true;
});

log_sep("INSTALL WBCE CMS: ".addslashes($wb_url));

// =============================================================================
// 1. WRITE config.php FROM TEMPLATE
// =============================================================================
log_sep($TXT['log_writing_config']);

$config_file = $WB_PATH . '/config.php';
$config_template = __DIR__ . '/config.php.txt';

if (!is_readable($config_template)) {
    log_err("config.php.txt template not found at: $config_template");
    goto install_failed;
}

// Template uses {PLACEHOLDERS} — replace with actual values.
$config_content = '<?php' . PHP_EOL . strtr(
    file_get_contents($config_template),
    [
        '{DATETIMESTRING}'  => $install_date,
        '{DB_TYPE}'         => 'mysql',
        '{DB_HOST}'         => addslashes($database_host)
                               . ($database_port !== null ? ':' . $database_port : ''),
        '{DB_NAME}'         => addslashes($database_name),
        '{DB_USERNAME}'     => addslashes($database_username),
        '{DB_PASSWORD}'     => addslashes($database_password),
        '{DB_CHARSET}'      => $database_charset,
        '{TABLE_PREFIX}'    => addslashes($table_prefix),
        '{WB_URL}'          => addslashes($wb_url),
        '{ADMIN_DIRECTORY}' => addslashes($admin_dir),
    ]
);

if (!file_exists($config_file)) {
    log_err("config.php does not exist at: $config_file");
} elseif (!is_writable($config_file)) {
    log_err("config.php is not writable — chmod 644 and try again");
} elseif (file_put_contents($config_file, $config_content) === false) {
    log_err("Could not write config.php");
} else {
    log_ok($TXT['log_done']);
}

if (is_fatal()) goto install_failed;

// =============================================================================
// 2. DEFINE RUNTIME CONSTANTS + CONNECT TO DATABASE
// =============================================================================
log_sep($TXT['log_connecting_db'] ?? 'Connecting to database');

defined('WB_PATH')        or define('WB_PATH',        $WB_PATH);
defined('TABLE_PREFIX')   or define('TABLE_PREFIX',   $table_prefix);
defined('WB_URL')         or define('WB_URL',         $wb_url);
defined('ADMIN_DIRECTORY')or define('ADMIN_DIRECTORY',$admin_dir);
defined('ADMIN_PATH')     or define('ADMIN_PATH',     WB_PATH . '/' . ADMIN_DIRECTORY);
defined('ADMIN_URL')      or define('ADMIN_URL',      WB_URL  . '/' . ADMIN_DIRECTORY);
defined('DB_TYPE')        or define('DB_TYPE',        'mysql');
defined('DB_CHARSET')     or define('DB_CHARSET',     $database_charset);
defined('DB_HOST')        or define('DB_HOST',        $database_host);
defined('DB_NAME')        or define('DB_NAME',        $database_name);
defined('DB_USERNAME')    or define('DB_USERNAME',    $database_username);
defined('DB_PASSWORD')    or define('DB_PASSWORD',    $database_password);
if ($database_port !== null) {
    defined('DB_PORT')    or define('DB_PORT',        (string)$database_port);
}

require_once WB_PATH . '/framework/class.autoload.php';
require_once WB_PATH . '/framework/Settings.php';

$_vFile = ADMIN_PATH . '/interface/version.php';
if (is_readable($_vFile)) {
    require_once $_vFile;
    log_ok('Version: ' . (defined('WBCE_VERSION') ? WBCE_VERSION : '?'));
} else {
    log_warn('version.php not found — version constants will use fallback values');
}

try {
    $database = new Database();
    log_ok($TXT['log_done'] ?? '✓ Done');
} catch (Throwable $e) {
    log_err('Database connection failed: ' . $e->getMessage());
}

if (is_fatal()) goto install_failed;

// =============================================================================
// 3. IMPORT SQL FILES
// =============================================================================
// Uses Database::importSql() — same parameters as update.php.
// {TABLE_ENGINE}    → ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
// {TABLE_COLLATION} → collate utf8mb4_unicode_ci  (column-level, importSql prepends a space)
log_sep($TXT['log_importing_sql'] ?? 'Importing SQL');

$_ENGINE    = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
$_COLLATION = 'collate utf8mb4_unicode_ci';

$aSqlFiles = [
    __DIR__ . '/sql/install_prepare.sql' => false,  // DROP first, then CREATE
    __DIR__ . '/sql/install_struct.sql'  => true,   // CREATE IF NOT EXISTS — preserve tables
    __DIR__ . '/sql/install_data.sql'    => true,   // INSERT IGNORE — preserve data
];

foreach ($aSqlFiles as $sqlFilePath => $preserve) {
    $sqlFileName = basename($sqlFilePath);

    if (!is_readable($sqlFilePath)) {
        log_err("SQL file not found: $sqlFileName");
        continue;
    }

    log_info("Importing $sqlFileName …");

    $results = $database->importSql(
        $sqlFilePath,
        null,
        $preserve,
        $_ENGINE,
        $_COLLATION,
        function (array $r, int $idx, int $total) use ($sqlFileName, $TXT): void {
            if (!$r['ok']) {
                log_err("  $sqlFileName: " . $r['msg']);
            } elseif (str_starts_with($r['statement'], TABLE_PREFIX)) {
                $tbl = substr($r['statement'], strlen(TABLE_PREFIX));
                log_ok("  {TP}$tbl");
            }
            flush();
        }
    );

    $errors = count(array_filter($results, fn($r) => !$r['ok']));
    $ok     = count(array_filter($results, fn($r) =>  $r['ok']));

    if ($errors === 0) log_ok("$sqlFileName: $ok statement(s) — " . ($TXT['log_done'] ?? 'done'));
    else               log_err("$sqlFileName had $errors error(s)");
}

if (is_fatal()) goto install_failed;

// =============================================================================
// 4. WRITE SETTINGS
// =============================================================================
log_sep($TXT['log_writing_settings'] ?? 'Writing system settings');

$settings = [
    'wbce_version'          => defined('WBCE_VERSION') ? WBCE_VERSION : '1.7.0',
    'wbce_tag'              => defined('WBCE_TAG')     ? WBCE_TAG     : '',
    'wb_version'            => defined('VERSION')      ? VERSION      : '2.8.3',
    'wb_revision'           => defined('REVISION')     ? REVISION     : '1641',
    'wb_sp'                 => defined('SP')           ? SP           : 'SP4',
    'website_title'         => $website_title,
    'website_url'           => $wb_url,
    'default_language'      => $default_language,
    'default_timezone'      => (string)$default_timezone,
    'operating_system'      => $operating_system,
    'app_name'              => 'phpsessid-' . $session_rand,
    'server_email'          => $admin_email,
    'string_file_mode'      => $file_mode,
    'string_dir_mode'       => $dir_mode,
    'page_level_limit'      => 8,
    'default_template'      => 'wbcezon',
    'page_trash'            => 'inline',
    'intro_page'            => false,
    'redirect_timer'        => '500',
    'sec_anchor'            => 'wb_',
    'wb_secform_secret'     => bin2hex(random_bytes(16)),
    'wb_secform_secrettime' => '86400',
    'wb_secform_timeout'    => '7200',
    'wb_secform_tokenname'  => 'formtoken',
    'wb_secform_usefp'      => false,
    'er_level'              => 'E3',
];

$sOk = $sFail = 0;
foreach ($settings as $_sName => $_sVal) {
    $result = Settings::set($_sName, $_sVal);
    if ($result !== false) {
        log_err("Setting `$_sName` failed: $result");
        $sFail++;
    } elseif ($database->hasError()) {
        log_err("Setting `$_sName` DB error: " . $database->getError());
        $sFail++;
    } else {
        $display = is_bool($_sVal) ? ($_sVal ? 'true' : 'false') : (string)$_sVal;
        log_ok("  `$_sName` = " . _h($display));
        $sOk++;
    }
}

if ($sFail > 0) log_err("$sFail setting(s) failed.");
else            log_ok($TXT['log_done'] ?? "All $sOk settings written.");

// =============================================================================
// 5. CREATE ADMIN ACCOUNT
// =============================================================================
log_sep($TXT['log_creating_admin'] ?? 'Creating administrator account');

$database->insertRow('{TP}users', [
    'user_id'            => 1,
    'group_id'           => 1,
    'groups_id'          => '1',
    'active'             => '1',
    'username'           => $admin_username,
    'password'           => password_hash($admin_password, PASSWORD_BCRYPT),
    'email'              => $admin_email,
    'language'           => $default_language,
    'timezone'           => $default_timezone,
    'display_name'       => 'Administrator',
    'signup_checksum'    => date('Y-m-d H:i:s'),
    'signup_timestamp'   => time(),
    'signup_confirmcode' => 'install-script',
]);

if ($database->hasError()) {
    log_err('Could not create admin account: ' . $database->getError());
} else {
    log_ok($TXT['log_done'] ?? "Administrator account created: $admin_username");
}

// =============================================================================
// 6. BOOT FRAMEWORK
// =============================================================================
log_sep($TXT['log_booting_framework'] ?? 'Booting framework');

require_once $config_file;
log_ok('Framework booted' . (defined('WBCE_VERSION') ? ' — WBCE ' . WBCE_VERSION : ''));

// =============================================================================
// 7. INSTALL ADDONS: MODULES / TEMPLATES / LANGUAGES
// =============================================================================
// Uses AddonService::installFromDisk() — no admin stub needed.
// Signals are printed via the log_* functions (Capture Mode).
log_sep('# Installing add-ons #');
global $TEXT, $MENU, $MESSAGE, $SIGNAL; // Make available for service scripts 

// Minimal $admin stub — some install.php scripts call $admin->isAdmin() etc.
// AddonService::runScript() will use this if $GLOBALS['admin'] is not set.
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

$_currentType = $TXT['module'];
if (!class_exists('AddonService')) {
    require_once __DIR__ . '/framework/AddonService.php';
}
$addonService = new AddonService();
$addonService->setProgress(function (array $r) use (&$_fatal, &$_currentType): void {
    global $SIGNAL;
    $label = $r['label'];
    $msg   = isset($SIGNAL[$r['signal']])
        ? sprintf($SIGNAL[$r['signal']], $_currentType, $label)
        : $label;
    if ($r['signal'] === 'ADDON_PRECHECK_FAILED' || $r['signal'] === 'ADDON_SCRIPT_ERROR') {
        log_warn($msg);
    } elseif (in_array($r['signal'], ['ADDON_DB_ERROR','ADDON_INFO_INVALID','ADDON_PATH_NOT_FOUND'], true)) {
        log_err($msg);
        $_fatal = true;
    } else {
        log_ok($msg);
    }
    flush();
});


// 7A. MODULES
log_sep($TXT['log_installing_modules']);

// priority modules first
$priorityModules = ['outputfilter_dashboard', 'droplets'];
$moduleDirs      = glob(WB_PATH . '/modules/*/', GLOB_ONLYDIR) ?: [];

$missing = array_filter($priorityModules, fn($m) => !is_dir(WB_PATH . '/modules/' . $m));
if ($missing) {
    log_err(($TXT['log_required_mod_missing'] ?? 'Required modules missing: ') . implode(', ', $missing));
    goto install_failed;
}

usort($moduleDirs, function ($a, $b) use ($priorityModules) {
    $pa = in_array(basename($a), $priorityModules, true);
    $pb = in_array(basename($b), $priorityModules, true);
    return $pa !== $pb ? ($pa ? -1 : 1) : strcasecmp(basename($a), basename($b));
});

$modOk = $modWarn = 0;
$_currentType = $TXT['module'].':';
foreach ($moduleDirs as $dir) {
    if (!file_exists($dir . 'info.php')) continue; // skip dirs without info.php
    $mod     = basename($dir);
    $signals = $addonService->installFromDisk($mod, 'module');
    if ($addonService->hasError()) { $modWarn++; }
    else                  { $modOk++;   }
}
log_info($MENU['MODULES'].':'. $modOk . ($modWarn ? ", $modWarn warning(s)" : ''));

// 7B. TEMPLATES
log_sep($MENU['TEMPLATES']);

$tplOk = 0;
$_currentType = $TXT['template'].':';
foreach (glob(WB_PATH . '/templates/*/') ?: [] as $dir) {
    if (!file_exists($dir . 'info.php')) continue; // skip non-installable dirs (systemplates, theme_fallbacks etc.)
    $signals = $addonService->installFromDisk(basename($dir), 'template');
    if (!$addonService->hasError()) $tplOk++;
}
log_info($MENU['TEMPLATES'].':'. $tplOk);

// 7C. LANGUAGES
log_sep($MENU['LANGUAGES']);

$langOk = 0;
$_currentType = $TXT['language'].':';
foreach (glob(WB_PATH . '/languages/??.php') ?: [] as $file) {
    foreach ($addonService->dbRegister($file, 'language') as $r) {
        $msg = isset($SIGNAL[$r['signal']])
            ? sprintf($SIGNAL[$r['signal']], $_currentType, $r['label'])
            : $r['label'];
        if (in_array($r['signal'], ['ADDON_INSERTED_OK', 'ADDON_UPDATED_OK'], true)) {
            log_ok($msg);
            $langOk++;
        } elseif ($r['signal'] !== 'ADDON_ALREADY_CURRENT') {
            log_warn($msg);
        }
    }
    flush();
}


log_info($MENU['LANGUAGES'].':'. $langOk);
// =============================================================================
// 8. FINALIZE
// =============================================================================
log_sep($TXT['log_finalizing'] ?? 'Finalizing installation');


Settings::exportSnapshot(true);
log_ok($TXT['log_export_snapshot'] . ': var/sys_constants.php');


log_ok($TXT['log_complete'] ?? '━━━ Installation complete ━━━');
log_sep('**************************************');
log_sep(' ' . $install_date);

$_SESSION = [];
session_destroy();

?>
<?php goto install_end; ?>

<?php install_failed: ?>
<?php
echo '<div class="log-err">&#10060; ' . _h($TXT['log_failed'] ?? 'Installation failed – see errors above') . '</div>';

install_end:
// Stream ends here — JS detects end-of-stream and shows action buttons