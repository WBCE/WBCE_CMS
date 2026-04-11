<?php
/**
 * @file class.settings.php
 * @brief Simple static class for handling global settings in Core and Modules
 *
 * The basic idea is to make handling of global settings a lot easier.
 *
 * @author  Norbert Heimsath (heimsath.org)
 * @author  Christian M. Stefan  (for WBCE 1.7.0)
 * 
 * @license GPLv2 or any later
 *
 * For detailed information take a look at the actual documentation of class Settings.
 * 
 * @brief This class handles the management of global settings.
 *
 * It takes care of getting and setting those and it handles the converion and generation into good old constants.
 *
 * All setting names may only contain a-z 0-9 and "_" \n
 * Core settings are prepended by "wb_". (e.g. wb_maintainance_mode) \n
 * Module settings are prepended by module name or maybe by a shortened form of the module name. \n
 * (e.g. wysi_my_setting for wysiwyg)
 *
 *
 * @attention
 * Please keep in mind that all settings in WBCE are stored as TEXT strings only.
 *
 * All those settings are converted into constants during the initialization
 * process using Settings::setup();
 * So "wb_maintainance_mode" is available as WB_MAINTAINANCE_MODE allover in WBCE.
 * 
 * Since WBCE 1.7.0 all constants generated during setup can be easily looked up
 * in ROOT/var/sys_constants.php or using `debug(Settings:showConstants);`
 *
 *
 * Some examples:
 * @code
 * // create or edit a setting
 * Settings::set("wb_new_setting","the value");
 *
 * // using a setting
 * if (WB_NEW_SETTING =="the value") echo "Hooray!";
 *
 * // there is a get function also (but it is mostly used internally)
 * $myValue= Settings::get("wb_new_setting");
 *
 * // deleting
 * Settings::delete("wb_new_setting");
 *
 * // if used in modules please prepend (shortened) module name to avoid collisions
 * Settings::set("wysi_new_setting","another value");
 * @endcode
 *
 * @attention
 * Arrays and objects are now automatically serialized and deserialized by the 
 * "get" functions.
 * As constants aren't capable of containing arrays and objects prior to PHP7 
 * constants keep the serialized version whith a !!SARRAY!! or !!SOBJECT!! prefix. 
 * However, you can use the Settings::deserialize() method to deserialize a constant
 *
 * @code
 * $aNeededArray = Settings::deserialize(WB_MY_ARRAY_SETTING);
 * @endcode
 *
 *
 * @todo Extend this class to handle different tables. Maybe by making this a class whith instances and a static facade.
 *
 * IMPORTANT NOTICE:
 * The method names in this class have been modernized to follow PSR naming 
 * conventions (camelCase). Old method names are still fully supported through 
 * automatic mapping for backward compatibility.
 *
 */

class Settings
{
    /** @var array Cached settings for fast access — stores raw DB values */
    private static array $cache = [];

    /** @var array Public alias — backward compat. Stores deserialized values. */
    public static array $settings = [];

    /** Snapshot TTL in seconds — regenerate if older than this */
    private const CACHE_TTL = 300;

    /**
     * Path to the developer snapshot file.
     * Cannot be a class constant because WB_PATH is a runtime value.
     */
    private static function cacheFile(): string
    {
        return WB_PATH . '/var/sys_constants.php';
    }
    
    /**
     * Set or update a global setting.
     *
     * @param string $name      Setting name (will be converted to lowercase)
     * @param mixed  $value     Value to store (arrays and objects are serialized)
     * @param bool   $overwrite If false, existing settings will not be overwritten
     * @return bool|string      false on success, error message on failure
     */
    public static function set(string $name, $value, bool $overwrite = true)
    {
        global $database;

        if (empty($name) || !preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            return "Setting name may only contain a-z, A-Z, 0-9 and underscore (_)";
        }

        $name = strtolower($name);

        if (is_bool($value)) {
            $value = $value ? 'btrueb' : 'bfalseb';
        } elseif (is_array($value)) {
            $value = '!!SARRAY!!' . serialize($value);
        } elseif (is_object($value)) {
            $value = '!!SOBJECT!!' . serialize($value);
        } elseif (is_resource($value)) {
            return "Resources cannot be stored as settings";
        }

        $exists = self::exists($name);

        if (!$exists) {
            // Insert new setting
            $database->insertRow('{TP}settings', [
                'name'  => $name,
                'value' => $value
            ]);
        } else {
            if (!$overwrite) {
                return "Setting already exists and overwrite is disabled";
            }

            // Update existing setting
            // Update — explicit UPDATE (no UNIQUE constraint on 'name')
            $database->query(
                "UPDATE `{TP}settings` SET `value` = ? WHERE `name` = ?",
                [$value, $name]
            );
        }

        // Update cache
        self::$cache[$name] = $value;
        self::$settings[$name] = self::deserialize($value);

        return false;
    }

    /**
     * Get a setting (preferred method - uses cache).
     */
    public static function get(string $name, $default = false)
    {
        $name = strtolower($name);

        if (isset(self::$cache[$name])) {
            return self::deserialize(self::$cache[$name]);
        }

        return $default;
    }

    /**
     * Check if a setting exists.
     */
    public static function exists(string $name): bool
    {
        $name = strtolower($name);
        return isset(self::$cache[$name]) || 
               self::getFromDb($name, '#++#null#++') !== '#++#null#++';
    }

    /**
     * Get a setting directly from the database (bypasses cache).
     */
    public static function getFromDb(string $name, $default = false)
    {
        global $database;

        $name = strtolower($name);

        $value = $database->fetchValue(
            'SELECT `value` FROM `{TP}settings` WHERE `name` = ?',
            [$name]
        );

        return ($value !== null) ? self::deserialize($value) : $default;
    }

    /**
     * Get all settings with a given prefix.
     */
    public static function getByPrefix(string $prefix, $default = false): array|false
    {
        $prefix = strtolower(trim($prefix, '_')) . '_';

        $result = [];
        foreach (self::$cache as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $result[$key] = self::deserialize($value);
            }
        }

        return !empty($result) ? $result : $default;
    }

    /**
     * Delete a setting.
     */
    public static function delete(string $name)
    {
        global $database;

        $name = strtolower($name);

        if (!isset(self::$cache[$name]) && !self::exists($name)) {
            return "Setting does not exist";
        }

        unset(self::$cache[$name], self::$settings[$name]);
        $database->deleteRow('{TP}settings', 'name', $name);

        return false;
    }

    /**
     * Deserialize special values (booleans, arrays, objects).
     *
     * Handles three boolean formats in order of precedence:
     *   'btrueb'/'bfalseb' — current internal format
     *   'true'/'false'     — plain strings stored by WBCE 1.x (still in DB)
     */
    public static function deserialize($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        // Internal boolean markers
        if ($value === 'btrueb')  return true;
        if ($value === 'bfalseb') return false;

        // Plain string booleans — stored by WBCE 1.x, still present in existing DBs
        if ($value === 'true')  return true;
        if ($value === 'false') return false;

        // Serialized arrays and objects
        if (str_starts_with($value, '!!SARRAY!!')) {
            return unserialize(substr($value, 10), ['allowed_classes' => false]);
        }
        if (str_starts_with($value, '!!SOBJECT!!')) {
            return unserialize(substr($value, 11), ['allowed_classes' => false]);
        }

        return $value;
    }

    /**
     * Setup all settings as constants and fill the internal cache.
     * This method is called during system initialization.
     *
     * The internal cache stores raw DB values. Deserialization happens
     * on demand in get() and showConstants() — this keeps the cache
     * consistent regardless of whether a setting was loaded via setup()
     * or written via set().
     *
     * Note: exportSnapshot() is NOT called here. Call it explicitly at
     * the end of initialize.php so all constants (ADMIN_URL, THEME_PATH,
     * TIMEZONE, ...) are already defined when the snapshot is written.
     */
    public static function setup(): bool
    {
        global $database;

        self::$cache = [];

        $rows = $database->fetchAll('SELECT `name`, `value` FROM `{TP}settings`');

        foreach ($rows as $row) {
            $constName = strtoupper($row['name']);
            $rawValue  = $row['value'];

            if (!defined($constName)) {
                define($constName, self::deserialize($rawValue));
            }

            // Store raw value — get() and showConstants() deserialize on demand
            self::$cache[strtolower($constName)] = $rawValue;
            self::$settings[strtolower($constName)] = self::deserialize($rawValue);
        }

        return false;
    }

    /**
     * Returns all current settings as a structured associative array with three sections:
     *
     *   'from_db'            — constants originating from the settings table
     *   'from_code'          — other user-defined PHP constants (WB_PATH, ADMIN_URL, etc.)
     *   'file_based_settings'— constants loaded from /var/file_based_settings.php
     *
     * Both DB and code sections use UPPERCASE keys with fully deserialized values.
     * Always reflects the current in-memory state — never reads from file.
     */
    public static function showConstants(): array
    {
        // Section 1: constants from settings table
        $fromDb = [];
        foreach (self::$cache as $lowerName => $rawValue) {
            $fromDb[strtoupper($lowerName)] = self::deserialize($rawValue);
        }

        // Section 2: file-based settings (loaded early in bootstrap)
        $fileBased = [];
        $fbFile    = defined('WBCE_FILE_BASED_SETTINGS') ? WBCE_FILE_BASED_SETTINGS : null;
        if ($fbFile && file_exists($fbFile)) {
            $loaded = include $fbFile;
            if (is_array($loaded)) {
                $fileBased = $loaded;
            }
        }

        // Section 3: all other user-defined constants
        // Excludes: already in from_db, file_based, and per-request dynamic constants
        $dynamic = [
            'ORG_REFERER', 'LANGUAGE', 'PAGE_ID', 'PAGE_TITLE', 'MENU_TITLE',
            'PARENT', 'ROOT_PARENT', 'LEVEL', 'VISIBILITY', 'PAGE_DESCRIPTION',
            'TEMPLATE', 'TEMPLATE_DIR',
        ];

        $userConstants = get_defined_constants(true)['user'] ?? [];
        $fromCode = [];
        foreach ($userConstants as $name => $value) {
            if (isset($fromDb[$name])) continue;
            if (isset($fileBased[$name])) continue;
            if (in_array($name, $dynamic, true)) continue;
            $fromCode[$name] = $value;
        }
        ksort($fromCode);

        return [
            'from_db'             => $fromDb,
            'file_based_settings' => $fileBased,
            'from_code'           => $fromCode,
        ];
    }

    /**
     * Export current settings as a readable PHP file for developers.
     *
     * Skips writing if the file already exists and is younger than CACHE_TTL
     * seconds — unless $force is true (used by refreshSnapshot()).
     *
     * The file has two clearly separated sections:
     *   from_db   — what comes from the settings table (db-driven)
     *   from_code — all other user-defined constants (code-driven)
     *
     * Call this at the end of initialize.php so all constants are defined:
     *   Settings::exportSnapshot();
     *
     * FOR DEVELOPER REFERENCE ONLY — the system always reads from the database.
     * Writes atomically via a temp file and rename() to prevent partial reads.
     *
     * @param bool $force  Skip TTL check and always regenerate (default false)
     */
    public static function exportSnapshot(bool $force = false): bool
    {
        $cacheFile = self::cacheFile();

        // Respect TTL unless a forced refresh was requested
        if (!$force && file_exists($cacheFile) && (time() - filemtime($cacheFile) <= self::CACHE_TTL)) {
            return true;
        }

        $data       = self::showConstants();
        $fromDb     = $data['from_db'];
        $fileBased  = $data['file_based_settings'];
        $fromCode   = $data['from_code'];

        $lines   = [];
        $lines[] = "<?php\r\n";
        $lines[] = "/**\r\n";
        $lines[] = " * WBCE CMS — System Constants Snapshot\r\n";
        $lines[] = " *\r\n";
        $lines[] = " * Generated : " . date('Y-m-d H:i:s') . "\r\n";
        $lines[] = " * DB source : {TP}settings table\r\n";
        $lines[] = " *\r\n";
        $lines[] = " * FOR DEVELOPER REFERENCE ONLY.\r\n";
        $lines[] = " * The system always reads from the database — this file has no\r\n";
        $lines[] = " * effect on runtime behaviour. Use it to inspect which constants\r\n";
        $lines[] = " * are available and as an IDE hint source.\r\n";
        $lines[] = " *\r\n";
        $lines[] = " * Sections:\r\n";
        $lines[] = " *   from_db            — constants from the settings table\r\n";
        $lines[] = " *   file_based_settings — constants from /var/file_based_settings.php\r\n";
        $lines[] = " *   from_code          — other user-defined constants (WB_PATH, ADMIN_URL, ...)\r\n";
        $lines[] = " *\r\n";
        $lines[] = " * Regenerated automatically every " . self::CACHE_TTL . " seconds\r\n";
        $lines[] = " * or on demand via Settings::refreshSnapshot().\r\n";
        $lines[] = " */\r\n";
        $lines[] = "\r\n";
        $lines[] = "defined('WB_PATH') or die('No direct access');\r\n";
        $lines[] = "\r\n";
        $lines[] = "return [\r\n";
        $lines[] = "\r\n";
        $lines[] = "    // ── From database (settings table) " . str_repeat('─', 41) . "\r\n";
        $lines[] = "\r\n";
        $lines[] = "    'from_db' => " . str_replace("\n", "\n    ", var_export($fromDb, true)) . ",\r\n";
        $lines[] = "\r\n";
        $lines[] = "    // ── File-based settings (/var/file_based_settings.php) " . str_repeat('─', 20) . "\r\n";
        $lines[] = "\r\n";
        $lines[] = "    'file_based_settings' => " . str_replace("\n", "\n    ", var_export($fileBased, true)) . ",\r\n";
        $lines[] = "\r\n";
        $lines[] = "    // ── From code (config.php / initialize.php) " . str_repeat('─', 32) . "\r\n";
        $lines[] = "\r\n";
        $lines[] = "    'from_code' => " . str_replace("\n", "\n    ", var_export($fromCode, true)) . ",\r\n";
        $lines[] = "\r\n";
        $lines[] = "];\r\n";

        $content   = implode('', $lines);
        $cacheFile = self::cacheFile();
        $dir       = dirname($cacheFile);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Atomic write: write to temp file first, then rename.
        // rename() is atomic on all OS — no partial reads possible.
        $tmp = $cacheFile . '.tmp';
        if (file_put_contents($tmp, $content, LOCK_EX) === false) {
            return false;
        }

        return rename($tmp, $cacheFile);
    }

    /**
     * Force regeneration of the snapshot file, ignoring the TTL.
     * Useful for a "Refresh" button in the admin backend.
     */
    public static function refreshSnapshot(): bool
    {
        return self::exportSnapshot(true);
    }

    /**
     * Debug method: Display all current settings (from DB and cache).
     */
    public static function info(): string
    {
        global $database;

        $out = "<h3>Settings Overview</h3>";

        foreach (self::$cache as $name => $cachedValue) {
            $dbValue = $database->fetchValue(
                'SELECT `value` FROM `{TP}settings` WHERE `name` = ?',
                [$name]
            );

            $out .= "<br><strong>" . strtoupper($name) . "</strong><br>";
            $out .= "Database : " . htmlentities((string)$dbValue) . "<br>";
            $out .= "Cache    : " . htmlentities(var_export($cachedValue, true)) . "<br>";
        }

        return $out;
    }
    
    /**
     * Write or update a file-based setting.
     *
     * File-based settings live in /var/file_based_settings.php by default and are 
     * loaded as constants very early in the bootstrap — before the DB and autoloader.
     * Use for values that must be available before Settings::setup() runs.
     *
     * Keys must be UPPER_SNAKE_CASE (A-Z, 0-9, underscore, must start with a letter).
     * Only scalar values are supported (no arrays, objects, or resources).
     *
     * @param string $key    Constant name, e.g. 'WB_DEBUG'
     * @param mixed  $value  Scalar value only
     * @return bool|string   false on success, error message on failure
     */
    public static function setFileBasedSetting(string $key, mixed $value): bool|string
    {
        if (!preg_match('/^[A-Z][A-Z0-9_]*$/', $key)) {
            return "Invalid key '{$key}' — use UPPER_SNAKE_CASE (e.g. WB_DEBUG)";
        }
        if (!is_scalar($value)) {
            return "File-based settings only support scalar values (string, int, float, bool)";
        }

        $file     = defined('WBCE_FILE_BASED_SETTINGS') ? WBCE_FILE_BASED_SETTINGS : null;
        if (!$file) return "WBCE_FILE_BASED_SETTINGS constant is not defined";

        $settings = (file_exists($file) && is_array($loaded = include $file)) ? $loaded : [];
        $settings[$key] = $value;

        return self::_writeFileBasedSettings($file, $settings);
    }

    /**
     * Remove a file-based setting.
     *
     * @param string $key  Constant name to remove
     * @return bool|string false on success, error message on failure
     */
    public static function deleteFileBasedSetting(string $key): bool|string
    {
        $file = defined('WBCE_FILE_BASED_SETTINGS') ? WBCE_FILE_BASED_SETTINGS : null;
        if (!$file) return "WBCE_FILE_BASED_SETTINGS constant is not defined";

        if (!file_exists($file)) return false;

        $settings = include $file;
        if (!is_array($settings) || !array_key_exists($key, $settings)) return false;

        unset($settings[$key]);
        return self::_writeFileBasedSettings($file, $settings);
    }

    /**
     * Atomically write the file-based settings array to disk.
     * Uses a temp file + rename() to prevent partial writes on crash.
     */
    private static function _writeFileBasedSettings(string $file, array $settings): bool|string
    {
        $lines = ["<?php\n", "return [\n"];
        foreach ($settings as $key => $value) {
            $lines[] = "    '{$key}' => " . var_export($value, true) . ",\n";
        }
        $lines[] = "];\n";

        $tmp = $file . '.tmp';
        if (file_put_contents($tmp, implode('', $lines), LOCK_EX) === false) {
            return "File-based Settings: could not write to '{$tmp}'";
        }
        if (!rename($tmp, $file)) {
            @unlink($tmp);
            return "File-based Settings: atomic rename failed for '{$file}'";
        }
        return false;
    }

    /**
     * Magic static call to maintain full backward 
     * compatibility with old method names.
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $mapping = [
            'Set'             => 'set',
            'Get'             => 'get',
            'GetDb'           => 'getFromDb',
            'GetPrefix'       => 'getByPrefix',
            'Del'             => 'delete',
            'DeSerialize'     => 'deserialize',
            'Setup'           => 'setup',
            'Info'            => 'info'
        ];

        if (isset($mapping[$name])) {
            return self::{$mapping[$name]}(...$arguments);
        }

        trigger_error("Call to undefined static method Settings::$name()", E_USER_ERROR);
        return null;
    }

}