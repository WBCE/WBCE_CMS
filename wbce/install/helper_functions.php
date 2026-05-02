<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @file install/helper_functions.php
 * @brief  this file contains the functions used by installer and save script.
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 * 
 */

function remove_github_fetcher(): void
{
    $file = dirname(__DIR__) . '/wbce_fetcher.php';
    if (is_file($file)) @unlink($file);
}

// Function to set error
// Stores errors to Session
// Returns to Installer form if  there are invalid Values
function set_error($message, $field_name = '', $now = false)
{
    if (isset($message) and $message != '') {

        // Copy values entered into session so user doesn't have to re-enter everything
        save_user_data();

        // Set the message
        $_SESSION['message'][] = $message;
        // Set the element(s) to highlight
        if ($field_name != '') {
            $_SESSION['ERROR_FIELD'][] = $field_name;
        }
        // Specify that session support is enabled
        $_SESSION['session_support'] = '<span class="good">Enabled</span>';

        // There was a request for immediate redirect
        if ($now === true) {
            header('Location: index.php?sessions_checked=true');
            exit;
        }
    }
}

// Copy values entered into session so user doesn't have to re-enter everything
function save_user_data()
{
    // Copy values entered into session so user doesn't have to re-enter everything
    if (isset($_POST['website_title'])) {
        $_SESSION['wb_url'] = $_POST['wb_url'];
        $_SESSION['default_timezone']  = $_POST['default_timezone'];
        $_SESSION['default_language']  = $_POST['default_language'];
        $_SESSION['database_host']     = $_POST['database_host'];
        $_SESSION['database_username'] = $_POST['database_username'];
        $_SESSION['database_password'] = $_POST['database_password'];
        $_SESSION['database_name']     = $_POST['database_name'];
        $_SESSION['table_prefix']      = $_POST['table_prefix'];
        $_SESSION['website_title']     = $_POST['website_title'];
        $_SESSION['admin_username']    = $_POST['admin_username'];
        $_SESSION['admin_email']       = $_POST['admin_email'];
        $_SESSION['admin_password']    = $_POST['admin_password'];
        $_SESSION['admin_repassword']  = $_POST['admin_repassword'];
        $_SESSION['operating_system']  = $_POST['operating_system'] ?? 'linux';
        $_SESSION['install_tables']    = isset($_POST['install_tables']);
        $_SESSION['world_writeable']   = isset($_POST['world_writeable']) && $_POST['world_writeable'] == "true" ? "true" : "false";
    }
}

// Function to workout what the default permissions are for files created by the webserver
function default_file_mode($temp_dir)
{
    if (is_writable($temp_dir)) {
        $filename = $temp_dir . '/test_permissions.txt';
        $handle = fopen($filename, 'w');
        fwrite($handle, 'This file is to get the default file permissions');
        fclose($handle);
        $default_file_mode = '0' . substr(sprintf('%o', fileperms($filename)), -3);
        unlink($filename);
    } else {
        $default_file_mode = '0777';
    }
    return $default_file_mode;
}

// Function to workout what the default permissions are for directories created by the webserver
function default_dir_mode($temp_dir)
{
    if (is_writable($temp_dir)) {
        $dirname = $temp_dir . '/test_permissions/';
        mkdir($dirname);
        $default_dir_mode = '0' . substr(sprintf('%o', fileperms($dirname)), -3);
        rmdir($dirname);
    } else {
        $default_dir_mode = '0777';
    }
    return $default_dir_mode;
}

function add_slashes($input)
{
    if (!is_string($input)) {
        return $input;
    }
    $output = addslashes($input);
    return $output;
}

// Function to highlight input fields which contain wrong/missing data
function field_error($field_name = '')
{
    if (!defined('SESSION_STARTED') || $field_name == '') {
        return;
    }

    if (isset($_SESSION['ERROR_FIELD']) && $_SESSION['ERROR_FIELD'] == $field_name) {
        return ' class="wrong"';
    }
}


/**
 * Secure HTML escaping helper (recommended default).
 *
 * This function protects against XSS by converting special characters
 * (<, >, &, ", ') into their HTML entity equivalents.
 *
 * Security features:
 *   - ENT_QUOTES      : Escapes single quotes (critical for HTML attributes)
 *   - ENT_SUBSTITUTE  : Gracefully handles malformed UTF-8 instead of silent failure
 *   - Explicit UTF-8  : Avoids problems with php.ini default_charset
 *   - Type coercion   : Never throws on null/int/bool input
 *   - Short & readable: <?= h($title) ?>  is much cleaner than the long 
 *                       <?= htmlspecialchars($title) ?> call
 *
 * Always prefer this function when outputting any user-controlled or dynamic 
 * data into HTML templates.
 */
function _h(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// returns content only if WB_DEBUG is true
function d($s)
{
    if (defined("WB_DEBUG") and WB_DEBUG === true) {
        return $s;
    } else {
        return '';
    }
}


/**
 * Returns a list of all available language files.
 *
 * EN is always the default language and shown as the first entry.
 * The current language is detected from $_GET['lang'] or falls back to EN.
 * Other languages are sorted alphabetically by their display name.
 *
 * @param string|null $langDir Path to the languages folder (default: __DIR__ . '/languages/')
 * @return array Array with language information
 */
function getAvailableLanguages(?string $langDir = null): array
{
    if ($langDir === null) {
        $langDir = __DIR__ . '/languages/';
    }

    if (!is_dir($langDir)) {
        return [];
    }

    $languages = [];
    $mainLanguages = ['EN', 'DE', 'NL'];   // Main languages that should come first by default

    // Get currently selected language (from ?lang=XX)
    $currentLang = isset($_GET['lang']) && is_string($_GET['lang'])
        ? strtoupper(trim($_GET['lang']))
        : 'EN';

    $files = scandir($langDir);

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
            continue;
        }

        $langFile = $langDir . $file;

        ob_start();
        include $langFile;
        ob_end_clean();

        if (isset($INFO['language_code'], $INFO['language_name'])) {
            $code = strtoupper($INFO['language_code']);
            $name = $INFO['language_name'];

            $query = $_GET;
            $query['lang'] = $code;
            $newUrl = '?' . http_build_query($query);

            $languages[$code] = [
                'code'    => $code,
                'name'    => $name,
                'url'     => $newUrl,
                'current' => ($currentLang === $code)
            ];
        }

        unset($INFO, $TXT, $MSG);
    }

    // Fallback to EN if selected language doesn't exist
    if (!isset($languages[$currentLang])) {
        $currentLang = 'EN';
    }

    $currentEntry = $languages[$currentLang] ?? null;
    unset($languages[$currentLang]);   // Remove it so we can reorder

    // Separate main languages and others
    $main = [];
    $others = [];

    foreach ($languages as $code => $lang) {
        if (in_array($code, $mainLanguages)) {
            $main[$code] = $lang;
        } else {
            $others[$code] = $lang;
        }
    }

    // Sort "others" alphabetically by name
    uasort($others, fn($a, $b) => strnatcasecmp($a['name'], $b['name']));

    // Build final ordered array
    $result = [];

    // 1. Selected language FIRST (if it's not one of the main ones)
    if ($currentEntry !== null) {
        $result[$currentLang] = $currentEntry;
    }

    // 2. Then the main languages (EN, DE, NL) in fixed order
    foreach ($mainLanguages as $code) {
        if (isset($main[$code])) {
            $result[$code] = $main[$code];
        }
    }

    // 3. Then all other languages alphabetically
    foreach ($others as $lang) {
        $result[$lang['code']] = $lang;
    }

    return $result;
}

// ── Log helpers ───────────────────────────────────────────────────────────────




// Log helpers
function _log(string $cls, string $icon, string $msg): void
{
    echo '<div class="log-' . $cls . '">' . $icon . ' ' . _h($msg) . "</div>\n";
    flush();
}
function log_info(string $msg): void 
{ 
    _log('info', '›', $msg);     
}
function log_ok(string $msg): void 
{
    _log('ok', '✓', $msg);     
}
function log_warn(string $msg): void 
{
    _log('warn', '⚠', $msg);     
}
function log_sep(string $label = ''): void
{
    echo '<div class="log-sep">── ' . _h($label) . " ──</div>\n";
    flush();
}

function log_err(string $msg): void
{
    global $_fatal;
    $_fatal = true;
    echo '<div class="log-err">✗ ' . _h($msg) . "</div>\n";
    flush();
}
function is_fatal(): bool 
{ 
    global $_fatal; 
        return $_fatal; 
}

/**
 * Logs the result of removePath() using the multilingual $MESSAGE array.
 *
 * @param string $signal  RM_* signal returned by removePath()
 * @param string $path    The path that was processed
 */
function log_path_removal(string $signal, string $path): void
{
    global $MESSAGE;
    $msg = sprintf($MESSAGE[$signal], $path);

    switch ($signal) {
        case 'RM_FILE_OK':
        case 'RM_DIR_OK':
            log_ok($msg);
            break;

        case 'RM_PATH_NOT_READABLE':
        case 'RM_PATH_PERMISSION_DENIED':
        case 'RM_PATH_COULD_NOT_REMOVE':
            log_err($msg);
            break;

        case 'RM_PATH_NOT_FOUND': // "does not exist" is usually just info during cleanup
            log_info($msg); 
            break;
    }
}


/**
 * Loads upgrade remove list from a single text file.
 * Supports:
 *   - Lines starting with # or // are full comments
 *   - Inline comments after path using // or #
 *   - Empty lines are skipped
 *   - Only lines starting with [ are processed
 *
 * @param string $filename
 * @return array 
 */
/**
 * Reads a plain text remove list file and returns an array of paths.
 * 
 * Features:
 * - Skips empty lines and full-line comments (# or //)
 * - Removes inline comments (// or #)
 * - Handles indented lines (spaces or tabs before the path)
 * - Only keeps lines that start with '[' after trimming
 *
 * @param string $filename  Full path to the text file
 * @return array            Array of cleaned paths
 */
function arrayFromTextFile(string $filename): array
{
    $paths = [];

    if (!file_exists($filename) || !is_readable($filename)) {
        return $paths;
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // First: remove leading whitespace (spaces + tabs)
        $line = ltrim($line);

        if ($line === '') {
            continue;
        }

        // Skip full-line comments
        if (str_starts_with($line, '#') || str_starts_with($line, '//')) {
            continue;
        }

        // Remove inline comments (everything after // or #)
        $line = preg_replace('/\s*(?:\/\/|#).*$/', '', $line);
        $line = trim($line);        // final trim in case inline comment left spaces

        // Only add lines that start with '['
        if ($line !== '' && str_starts_with($line, '[')) {
            $paths[] = $line;
        }
    }

    return $paths;
}        