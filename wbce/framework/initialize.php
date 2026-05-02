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
 *
 * The main initialization and bootstraping file for WBCE CMS.
 * Takes care of the set up (initialization) of variables and constants,
 * the autoloader class, sessions, settings, internationalization and more.
 *
 * Usualy this is included by loading the config.php in the main directory
 * (webroot), however, this file is also being used during the installation.
 *
 */

// prevent direct file access
if (count(get_included_files()) == 1) {
    header("Location: ../index.php", true, 301);
}

// Stop execution if PHP version is too old
// Check minimum PHP version (new PDO Database class requires PHP 8.1+)
wbce_check_php_version('8.1.0');

// Starting Output buffering
ob_start();

// Define constants that are the absolute minimum needed for autoloader and DB
// 
// During istallation WB_PATH is not defined yet, therefore we do it he for that case.
defined("WB_PATH") or define("WB_PATH", dirname(__DIR__));

// Load Constants from var/wbce_file_based_settings.php early
// allows for setting constants via the backend rather than manually in the config.php
define('WBCE_FILE_BASED_SETTINGS', WB_PATH . '/var/wbce_file_based_settings.php');
wbce_load_file_based_settings();

// WB_DEBUG can be overwritten via WBCE config.php or wbce_load_file_based_settings()
// (if enabled, max. PHP error output is shown)
defined('WB_DEBUG') or define('WB_DEBUG', false);

// INITIALIZE AUTOLOADER
require_once __DIR__ . "/class.autoload.php";

// PREDB MODULES LOADED HERE
foreach (wbce_get_init_files('predb') as $_predbFile) {
    require_once $_predbFile;
}
unset($_predbFile);

// INITIALIZE DATABASE IF NOT ALREADY DONE (e.g. on install)
if (!isset($database) || !is_object($database)) {
    $database = new Database();
}

// SYSTEM CONSTANTS
// Now we start definig System constants if not already set
// Lots of compatibility work here, please only use the WB_ constants in future stuff

defined('ADMIN_DIRECTORY') or define('ADMIN_DIRECTORY', 'admin');
validate_admin_directory_constant(); // check for faulty constructions
defined('ADMIN_URL')       or define('ADMIN_URL', WB_URL . '/' . ADMIN_DIRECTORY);
defined('ADMIN_PATH')      or define('ADMIN_PATH', WB_PATH . '/' . ADMIN_DIRECTORY);

// Load Lang internationalization functions (L_(), Ln_()).
// Must be loaded explicitly before Autoloader
require_once WB_PATH . '/framework/i18n/init.php';

// Load core functions before preinit files so we can use functions right away.
require_once WB_PATH . '/framework/functions.php';

// MODULES preinit.php
foreach (wbce_get_init_files('preinit') as $_preinitFile) {
    include $_preinitFile;
}
unset($_preinitFile);


// define DOMAIN_PROTOCOLL constant
define("DOMAIN_PROTOCOLL", wbce_detect_protocol());

// use bootInitialize to register all core relevant classes
WbAuto::bootInitialize();

// Connect to Twig TE (the contemporary Templating Engine)
require_once WB_PATH . '/include/Sensio/Twig/WbceCustom/TwigLoader.php';

// SETUP SYSTEM CONSTANTS (GLOBAL SETTINGS)
// We use Settings Class to fetch all Settings from DB
// Then we process all data into the coresponding constants.
Settings::setup(); 

// Configure ERROR REPORTING based on WB_DEBUG and ER_LEVEL
wbce_setup_error_reporting();

// File & Directory modes
defined('STRING_FILE_MODE') or define('STRING_FILE_MODE', '0644'); // not yet defined ...
defined('STRING_DIR_MODE')  or define('STRING_DIR_MODE',  '0755'); // ... during install
define('OCTAL_FILE_MODE',    (int)octdec(STRING_FILE_MODE));
define('WB_OCTAL_FILE_MODE', (int)octdec(STRING_FILE_MODE));
define('OCTAL_DIR_MODE',     (int)octdec(STRING_DIR_MODE));
define('WB_OCTAL_DIR_MODE',  (int)octdec(STRING_DIR_MODE));


// DEFAULT TIMEZONE
// @todo this needs to be replaced by a real locale handling
// same for the timeformat stuff further down.
date_default_timezone_set('UTC');


// SESSION 
// Initialize Custom Session Handler 
// that stores sessions in the database.
$_dbSessionHandler = new DbSession();
// Initialize special Session handling and Start session.
WSession::Start();

// MODULES initialize.php
foreach (wbce_get_init_files('initialize') as $_initFile) {
    include $_initFile;
}
unset($_initFile);

// SANITIZE REFERER
SanitizeHttpReferer();

// LANGUAGES
// Only if no module already did this
if (!defined("LANGUAGE")) {
    // Get users language
    if (isset($_GET['lang']) && preg_match('/^[A-Z]{2}$/', $_GET['lang'])) {
        define('LANGUAGE', $_GET['lang']);
        $_SESSION['LANGUAGE'] = LANGUAGE;
    } else {
        if (isset($_SESSION['LANGUAGE']) && $_SESSION['LANGUAGE'] != '') {
            define('LANGUAGE', $_SESSION['LANGUAGE']);
        } else {
            defined('DEFAULT_LANGUAGE') or define('DEFAULT_LANGUAGE', 'EN');
            define('LANGUAGE', DEFAULT_LANGUAGE);
        }
    }
}
// Load system Language files from WB_PATH.'/langauges/'
Lang::setLocale(LANGUAGE);
Lang::loadCore(LANGUAGE, WB_PATH . '/languages');

// maintain old.format.inc.php for Legacy Modules
if (file_exists($file = WB_PATH . '/languages/old.format.inc.php')) {
    include $file;
}
define("LANGUAGE_LOADED", true);

// Constants needed in Accounts and Accounts AdminTool
if (FRONTEND_LOGIN) {
    defined('ACCOUNT_DIR') or define('ACCOUNT_DIR', 'account'); // no leading/trailing slash
    define('ACCOUNT_PATH', WB_PATH . DIRECTORY_SEPARATOR . ACCOUNT_DIR);
    define('ACCOUNT_URL',  get_url_from_path(ACCOUNT_PATH));
    define('LOGIN_URL',    ACCOUNT_URL . '/login.php');
    define('LOGOUT_URL',   ACCOUNT_URL . '/logout.php');
    define('FORGOT_URL',   ACCOUNT_URL . '/forgot.php');
    define('SIGNUP_URL',   ACCOUNT_URL . '/signup.php');
    define('PREFERENCES_URL', ACCOUNT_URL . '/preferences.php');
}

// define more system constants
defined("THEME_URL")        or define('THEME_URL', WB_URL . '/templates/' . DEFAULT_THEME);
defined("THEME_PATH")       or define('THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);
defined("EDIT_ONE_SECTION") or define('EDIT_ONE_SECTION', false);
defined("EDITOR_WIDTH")     or define('EDITOR_WIDTH', 0);

// TIMEZONE and DATE/TIME FORMAT constants
defined('DEFAULT_TIMEZONE') or define('DEFAULT_TIMEZONE', 'UTC');
define('TIMEZONE',    isset($_SESSION['TIMEZONE'])    ? intval($_SESSION['TIMEZONE']) : intval(DEFAULT_TIMEZONE));
define('DATE_FORMAT', isset($_SESSION['DATE_FORMAT']) ? $_SESSION['DATE_FORMAT'] : DEFAULT_DATE_FORMAT);
define('TIME_FORMAT', isset($_SESSION['TIME_FORMAT']) ? $_SESSION['TIME_FORMAT'] : DEFAULT_TIME_FORMAT);

// FETCH WBCE VERSION CONSTANTS
// Version should be available not only in admin section(BE)
require_once ADMIN_PATH . '/interface/version.php';

// TEMPLATE SWITCHER 
// Simple template switcher for development and preview
// Only available in Frontend if TEMPLATE_SWITCHER is set and true
wbce_template_switcher();

// CONSTANTS SNAPSHOT FOR DEVELOPERS
// Placed here so all constants (ADMIN_URL, THEME_PATH, TIMEZONE etc.) are fully defined.
Settings::exportSnapshot();


// ────────────────────────── END OF INITIALIZATION ────────────────────────────────


// ───────────────────────────── HELPER FUNCTIONS ──────────────────────────────────
/**
 * SanitizeHttpReferer
 * Sanitize and validate the HTTP_REFERER to ensure it originates from our own site.
 *
 * This function maintains backward compatibility by overwriting $_SERVER['HTTP_REFERER']
 * and also sets the safer $_SERVER['WBCE_HTTP_REFERER'].
 *
 * @return string The sanitized referer URL or empty string if invalid/untrusted.
 */
function SanitizeHttpReferer(): string
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    // No referer → clear both and return early
    if (empty($referer)) {
        $_SERVER['HTTP_REFERER'] = '';
        $_SERVER['WBCE_HTTP_REFERER'] = '';
        return '';
    }

    $refUrl  = parse_url($referer);
    $siteUrl = parse_url(WB_URL);

    // Parsing failed → invalid referer
    if ($refUrl === false || $siteUrl === false) {
        $_SERVER['HTTP_REFERER'] = '';
        $_SERVER['WBCE_HTTP_REFERER'] = '';
        return '';
    }

    $refHost  = $refUrl['host']  ?? '';
    $siteHost = $siteUrl['host'] ?? '';

    // Referer must come from the same domain
    if ($refHost !== $siteHost) {
        $_SERVER['HTTP_REFERER'] = '';
        $_SERVER['WBCE_HTTP_REFERER'] = '';
        return '';
    }

    // Build clean path
    $path     = $refUrl['path']     ?? '/';
    $query    = isset($refUrl['query'])    ? '?' . $refUrl['query']    : '';
    $fragment = isset($refUrl['fragment']) ? '#' . $refUrl['fragment'] : '';

    // Remove base path if WBCE is installed in a subdirectory
    $basePath = $siteUrl['path'] ?? '';
    if ($basePath && str_starts_with($path, $basePath)) {
        $path = substr($path, strlen($basePath));
        if ($path === '') {
            $path = '/';
        }
    }

    $safeReferer = WB_URL . $path . $query . $fragment;

    // Maintain original behavior for backward compatibility
    $_SERVER['HTTP_REFERER']      = $safeReferer;
    $_SERVER['WBCE_HTTP_REFERER'] = $safeReferer;

    return $safeReferer;
}


/**
 * wbce_setup_error_reporting
 * Setup PHP error reporting according to WBCE configuration.
 *
 * Priority:
 *   1. WB_DEBUG === true          → Maximum error reporting (development)
 *   2. ER_LEVEL setting           → Specific configuration
 *   3. php.ini default            → Fallback
 */
function wbce_setup_error_reporting(): void
{
    // Highest priority: Debug mode
    if (defined('WB_DEBUG') && WB_DEBUG === true) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        return;
    }

    // Get ER_LEVEL with fallback
    $level = defined('ER_LEVEL') ? (string)ER_LEVEL : 'E0';

    // Map all the Error Levels
    $mapping = [
        '-1' => E_ALL,                                 // old compatibility
        'E0' => (int)ini_get('error_reporting'),       // system default
        'E1' => 0,                                     // hide all errors
        'E2' => E_ALL,                                 // show everything
        'E3' => E_ALL & ~E_NOTICE & ~E_WARNING,        // errors only
    ];

    $errorLevel = $mapping[$level] ?? (int)ini_get('error_reporting');

    error_reporting($errorLevel);

    // Control display_errors accordingly
    if ($level === 'E1') {
        ini_set('display_errors', '0');
    } elseif (in_array($level, ['-1', 'E2', 'E3'], true)) {
        ini_set('display_errors', '1');
    }
    // For E0 and unknown values we leave display_errors as configured in php.ini
}

/**
 * wbce_detect_protocol
 * Detect the current protocol (http or https) as reliably as possible.
 * Takes into account reverse proxies, load balancers and various server configurations.
 *
 * @return string 'https' or 'http'
 */
function wbce_detect_protocol(): string
{
    // 1. Check for forwarded protocol (most common with proxies/load balancers)
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 
        strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        return 'https';
    }

    // 2. Check for standard HTTPS indicators
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' && $_SERVER['HTTPS'] !== '') {
        return 'https';
    }

    // 3. Check server port (443 is standard for HTTPS)
    if (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) {
        return 'https';
    }

    // 4. Some proxies use HTTP_X_FORWARDED_SSL or HTTPS header
    if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') {
        return 'https';
    }

    return 'http';
}

/**
 * wbce_template_switcher
 * Handle template preview / template switcher functionality.
 *
 * Allows switching the active template via ?template=xxx in the URL for development
 * and testing purposes.
 */
function wbce_template_switcher(): void
{
    if (!defined('TEMPLATE_SWITCHER') || TEMPLATE_SWITCHER !== true) {
        return;
    }

    // Reset template preview
    if (isset($_GET['reset_template'])) {
        unset($_SESSION['wb_preview_tpl']);
    }

    // Validate existing preview template (in case it was deleted)
    if (isset($_SESSION['wb_preview_tpl'])) {
        $previewTpl = (string)$_SESSION['wb_preview_tpl'];
        if (!file_exists(WB_PATH . '/templates/' . $previewTpl . '/info.php')) {
            unset($_SESSION['wb_preview_tpl']);
        }
    }

    // Switch to new template via GET parameter
    if (isset($_GET['template']) && is_string($_GET['template'])) {
        $newTemplate = preg_replace('/(\.\.\/|\/)/', '', trim($_GET['template']));

        if ($newTemplate !== '' && file_exists(WB_PATH . '/templates/' . $newTemplate . '/info.php')) {
            $_SESSION['wb_preview_tpl'] = $newTemplate;
        }
    }

    // Finally define the TEMPLATE constant if a preview is active
    if (isset($_SESSION['wb_preview_tpl']) && is_string($_SESSION['wb_preview_tpl'])) {
        define('TEMPLATE', $_SESSION['wb_preview_tpl']);
    }
}

/**
 * wbce_check_php_version
 * Check minimum PHP version requirement and exit with clear message if not met.
 */
function wbce_check_php_version(string $minimumVersion = '8.1.0'): void
{
    if (version_compare(PHP_VERSION, $minimumVersion, '<')) {
        $message = sprintf(
            "WBCE CMS requires PHP %s or higher.\n" .
            "You are currently running PHP %s.\n\n" .
            "Please upgrade your PHP version and try again.",
            $minimumVersion,
            PHP_VERSION
        );

        if (php_sapi_name() === 'cli') {
            fwrite(STDERR, $message . PHP_EOL);
        } else {
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8">';
            echo '<title>PHP Version Error - WBCE CMS</title>';
            echo '<style>body{font-family:system-ui,Arial,sans-serif;max-width:700px;margin:50px auto;padding:30px;border:2px solid #d32f2f;background:#fff8f8;}</style>';
            echo '</head><body>';
            echo '<h1>The PHP Version running is insufficient.</h1>';
            echo nl2br(htmlspecialchars($message));
            echo '</body></html>';
        }
        exit(1);
    }
}
/**
 * validate_admin_directory_constant
 * Assumes that ADMIN_DIRECTORY has already been defined.
 */
function validate_admin_directory_constant(): void
{
    // Validate ADMIN_DIRECTORY
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_-]{1,30}$/', ADMIN_DIRECTORY)) {
        die('Configuration Error: Invalid ADMIN_DIRECTORY.<br>' .
            'Allowed characters: letters (a-z, A-Z), numbers (0-9), underscore (_) and hyphen (-).<br>' .
            'Must start with a letter and be between 2 and 31 characters long.<br>' .
            'Current value: "' . htmlspecialchars(ADMIN_DIRECTORY) . '"');
    }
}

/**
 * wbce_get_init_files
 * Returns paths of module init files for the given function type.
 *
 * Supports three types with different discovery mechanisms:
 *   'predb'   — filesystem glob on modules/predb_* (no DB, runs before DB init)
 *   'preinit' — DB query on addons.function LIKE '%preinit%'
 *   'init'    — DB query on addons.function LIKE '%initialize%'
 *
 * The actual loading (require_once / include) happens in the caller's scope
 * so that variables set by the files remain globally accessible.
 *
 * @param  string $functionType  One of: 'predb', 'preinit', 'initialize'
 * @return array                 Absolute file paths, ready to load
 */
function wbce_get_init_files(string $functionType): array
{
    if (!in_array($functionType, ['predb', 'preinit', 'initialize'], true)) {
        return [];
    }

    // predb: filesystem only — DB does not exist yet at this point
    if ($functionType === 'predb') {
        $pattern = dirname(__DIR__) . '/modules/predb_*/predb.php';
        $found   = glob($pattern) ?: [];
        return array_filter($found, 'is_readable');
    }

    // preinit / init: DB-registered modules
    global $database;

    // 'init' maps to initialize.php; 'preinit' maps to preinit.php
    $filename  = $functionType.'.php';
    $likeValue = '%'.$functionType.'%';

    $modules = $database->fetchAll(
        "SELECT `directory` FROM `{TP}addons` WHERE `function` LIKE ?",
        [$likeValue]
    );

    $files = [];
    foreach ($modules as $module) {
        $path = WB_PATH . '/modules/' . $module['directory'] . '/' . $filename;
        if (file_exists($path)) {
            $files[] = $path;
        }
    }

    return $files;
}

/** 
 * wbce_load_file_based_settings
 * Load file-based settings and define them as constants.
 * Called very early during initialization — before autoloader and DB
 * to have a way to read/write certain settings before the Database is loaded. 
 * Introduced to WBCE CMS in version 1.7.0 by:
 *  
 * @author    Christian M. Stefan (https://www.wbEasy.de)
 * @copyright Christian M. Stefan (2026)
 * @license   GNU/GPL 2
 */
function wbce_load_file_based_settings(): void
{
    // the constant WBCE_FILE_BASED_SETTINGS is defined at the top of the 
    // initialize.php once, right after define WB_PATH
    $file = defined('WBCE_FILE_BASED_SETTINGS') ? WBCE_FILE_BASED_SETTINGS : null;
    if (!$file || !file_exists($file)) return;

    $settings = include $file; // the file contains a simple, one dimensional array
    if (!is_array($settings)) {
        trigger_error("File-based Settings: invalid format in " . basename($file), E_USER_WARNING);
        return;
    }

    foreach ($settings as $key => $value) {
        if (!preg_match('/^[A-Z][A-Z0-9_]*$/', $key)) {
            trigger_error("File-based Settings: invalid key '{$key}' skipped", E_USER_WARNING);
            continue;
        }
        defined($key) || define($key, $value);
    }
}