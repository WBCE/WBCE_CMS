<?php
/**
 * Internationalization — Lang
 *
 * A static translation registry and loader that handles loading, chaining,
 * and retrieval of language strings across all contexts: 
 * core, modules, templates and Twig.
 *
 * Adopted to support the classic WBCE assignment format ($TEXT['KEY'] = 'value')
 * maintaining full backward compatibility for existing language files and
 * global array access patterns.
 *
 * @file      framework/i18n/Lang.php
 * @package   Framework i18n
 * @author    Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright 2025-2026 Christian M. Stefan
 * @copyright 2026 WBCE CMS Project
 * @license   GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @note      Global arrays ($TEXT, $MENU, $MESSAGE, $HEADING, ...) remain
 *            fully available alongside the registry — existing modules and
 *            templates require no changes.
 *
 * -----------------------------------------------------------------------------
 *
 * LOCALE CHAINING
 * ────────────────────────────
 * For locale 'DE', loadCore() loads in order:
 *   1. EN.php  — always, full base set
 *   2. DE.php  — exact locale (partial ok)
 *
 * Each file only needs the keys that differ from the level above.
 *
 * USAGE — loading
 * ────────────────────────────
 *   // initialize.php loads these automatically
 *   Lang::setLocale(LANGUAGE);
 *   Lang::loadCore(LANGUAGE, WB_PATH . '/languages');
 *   Lang::syncToGlobals();  // keeps $TEXT/$MENU/$MESSAGE/$HEADING available
 *
 *   // Modules and Templates
 *   Lang::loadLanguage(); 
 *   // this will load /my_module/languages/EN.php (and e.g. DE.php etc.)
 *   // no manual inludes needed anymore
 *
 * USAGE — reading
 * ────────────────────────────
 *   Legacy usage 
 *   $TEXT['SAVE']   still works just the same
 *   L_("TEXT['SAVE']")                          → 'Speichern'
 *   L_("TEXT:SAVE")                             → 'Speichern' (like the old L_ function)
 *   L_("TEXT['DELETE_RECORD']", "TEXT['PAGE']") → 'Seite löschen'
 *   L_("TEXT['MISSING']||Fallback text")        → 'Fallback text'
 *   Ln_("TEXT['PAGE_COUNT']", 5)                → '5 Seiten'
 *   also possible:
 *   Ln_($TEXT['PAGE_COUNT'], count($pages));
 *
 * MISSING KEY TRACKING
 * ────────────────────────────
 * Enable after auth is established:
 *   Lang::enableTracking();
 *
 * Misses are collected in-memory and flushed to
 *   var/logs/i18n_missing.php
 * on shutdown. 
 * Each unique namespace:key is recorded once with the
 * file/line of the first call that triggered the miss.
 */

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

class Lang
{
    /** @var array<string, array<string, string|string[]>>  namespace → key → value */
    private static array $registry = [];

    /** @var string  Active locale — WBCE uses 2-char uppercase codes: 'EN', 'DE', 'FR' */
    private static string $locale = 'EN';

    /** @var bool  Whether to track missing keys */
    private static bool $tracking = false;

    /** @var bool  Shutdown handler registered? */
    private static bool $shutdownRegistered = false;

    /** @var array<string, array>  "NS:KEY" → miss record (deduplication key) */
    private static array $misses = [];

    /** @var array<string, true>  Keys already in the log file (loaded once) */
    private static array $loggedMisses = [];

    /** @var bool  Have we read the existing log file yet? */
    private static bool $logRead = false;

    /** @var array<string, true>  Directories already loaded via loadLanguage() */
    private static array $loadedDirs = [];

    // ── Configuration ─────────────────────────────────────────────────────────

    public static function setLocale(string $locale): void
    {
        self::$locale = $locale;
    }

    public static function getLocale(): string { return self::$locale; }

    /**
     * Enable missing-key tracking.
     * Call after auth is confirmed (bootstrap step 10).
     * Registers a shutdown handler to flush misses to logs/i18n_missing.php.
     */
    public static function enableTracking(): void
    {
        self::$tracking = true;
        if (!self::$shutdownRegistered) {
            register_shutdown_function([self::class, 'flushMissing']);
            self::$shutdownRegistered = true;
        }
    }

    public static function isTracking(): bool { return self::$tracking; }

    // ── Loading ───────────────────────────────────────────────────────────────

    /**
     * Load language files for a module, template, or any component.
     *
     * Two discovery modes — checked in order:
     *
     *   1. Directory mode: looks for a /languages/ subdirectory and loads:
     *        languages/EN.php  — always (full base set)
     *        languages/{locale}.php  — active locale if different from EN
     *
     *   2. Single-file mode: if no /languages/ directory exists, looks for
     *        languages.php  — one file containing all locales as a nested array:
     *        return ['EN' => ['NS' => [...]], 'DE' => ['NS' => [...]]];
     *        EN keys are always merged first, then the active locale on top.
     *
     * The namespace(s) loaded are determined entirely by which arrays the
     * language files assign — no namespace parameter needed.
     *
     * Calling loadLanguage() twice for the same directory is a no-op after
     * the first call (tracked by directory path).
     *
     * $dir is optional. When omitted, the directory of the calling file is
     * used automatically — so both forms are equivalent:
     *
     *   Lang::loadLanguage(__DIR__);   // explicit — clearer, recommended
     *   Lang::loadLanguage();          // auto-detect caller directory
     *
     * @param string|null $dir  Absolute path to the component directory,
     *                          or null to auto-detect from the call site.
     */
    public static function loadLanguage(?string $dir = null): void
    {
        // Auto-detect the calling file's directory when $dir is omitted
        if ($dir === null) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            $dir   = dirname($trace[0]['file'] ?? '');
        }

        $dir = rtrim(str_replace('\\', '/', $dir), '/');

        // Idempotent — skip if already loaded from this directory
        if (isset(self::$loadedDirs[$dir])) return;
        self::$loadedDirs[$dir] = true;

        $locale = self::$locale;

        // ── Mode 1: /languages/ directory ────────────────────────────────────
        $langDir = $dir . '/languages';
        if (is_dir($langDir)) {
            foreach (self::buildChain($locale) as $loc) {
                self::includeFile($langDir . '/' . $loc . '.php');
            }
            return;
        }

        // ── Mode 2: single languages.php file ─────────────────────────────────
        // Format: return ['EN' => ['NS' => [...]], 'DE' => ['NS' => [...]]];
        // EN is always merged first as the base, then the active locale on top.
        $singleFile = $dir . '/languages.php';
        if (!is_file($singleFile)) return;

        $all = include $singleFile;
        if (!is_array($all)) return;

        // Always load EN base first
        foreach ($all['EN'] ?? [] as $ns => $data) {
            if (is_string($ns) && is_array($data)) self::merge($ns, $data);
        }

        // Overlay active locale (skip if already handled as EN)
        if ($locale !== 'EN' && isset($all[$locale])) {
            foreach ($all[$locale] as $ns => $data) {
                if (is_string($ns) && is_array($data)) self::merge($ns, $data);
            }
        }
    }

    /**
     * Load core language files from the WBCE /languages/ directory.
     * Used by initialize.php to load TEXT, MENU, HEADING, MESSAGE.
     *
     * For LANGUAGE='DE' loads: EN.php → DE.php
     *
     * @param string $locale  WBCE 2-char code, e.g. 'DE', 'FR'
     * @param string $dir     Absolute path to WB_PATH/languages/
     */
    public static function loadCore(string $locale, string $dir): void
    {
        $dir   = rtrim(str_replace('\\', '/', $dir), '/');
        $chain = self::buildChain($locale);

        foreach ($chain as $loc) {
            self::includeFile($dir . '/' . $loc . '.php');
        }
    }

    /**
     * Load a single language file into the registry.
     *
     * Supports both file formats:
     *
     *   Legacy (WBCE-compatible):   $NS['KEY'] = 'value';
     *   Modern (return array):      return ['NS' => ['KEY' => 'value']];
     *
     * WHY a standalone helper function is used:
     *   PHP runs an included file in the *caller's variable scope*. When
     *   include is called from inside a class method, variables assigned by
     *   the included file (e.g. $MENU['DASHBOARD'] = 'Dashboard') become
     *   local to that method and are discarded on return — they never reach
     *   $GLOBALS. The standalone _wbe_lang_include() function captures all
     *   variables via get_defined_vars() before they go out of scope.
     *
     * Namespace detection for legacy format:
     *   Any variable the file assigns that is an array with an UPPER_SNAKE
     *   name is treated as a language namespace and merged into the registry.
     *   e.g. $TEXT, $MENU, $MOD_ERRLOG, $TPL_DEFAULT all qualify.
     *
     * @param string $file  Absolute path to language file
     */
    public static function includeFile(string $file): void
    {
        if (!is_file($file)) return;

        // _wbe_lang_include() is a standalone function defined in translations.php.
        // It must be a plain function (not a method) so that get_defined_vars()
        // captures all variables the included file assigns before they go out of scope.
        //
        // Ideally translations.php is require_once'd in initialize.php before any
        // Lang usage. But if it was somehow skipped, we load it here automatically
        // since both files live in the same framework/ directory.
        if (!function_exists('_wbe_lang_include')) {
            require_once __DIR__ . '/translations.php';
        }

        $vars     = _wbe_lang_include($file);
        $returned = $vars['_wbe_returned'] ?? null;

        // Modern format: file returned an array
        if (is_array($returned) && !empty($returned)) {
            $firstVal = reset($returned);
            if (is_array($firstVal)) {
                // Multi-namespace: ['TEXT' => [...], 'MENU' => [...],'MOD_FOO' => [...]]
                foreach ($returned as $ns => $data) {
                    if (is_string($ns) && is_array($data)) self::merge($ns, $data);
                }
            }
            // Single-namespace return without a name — cannot auto-detect namespace, skip.
            // Use legacy $NS['KEY'] = 'val' format for single-namespace files.
            return;
        }

        // Legacy format: detect namespace arrays by name pattern UPPER_SNAKE
        // (all uppercase letters, digits, underscores — e.g. TEXT, MOD_ERRLOG, MOD_MENU_LINK)
        foreach ($vars as $name => $value) {
            if (!is_array($value)) continue;
            if (!preg_match('/^[A-Z][A-Z0-9_]*$/', $name)) continue;
            // Skip PHP superglobals and internal variables
            if (in_array($name, ['GLOBALS', 'SERVER', 'GET', 'POST', 'FILES',
                                  'COOKIE', 'SESSION', 'REQUEST', 'ENV'])) continue;
            self::merge($name, $value);
        }
    }

    // ── Retrieval ─────────────────────────────────────────────────────────────

    /**
     * Get a raw translation value (string or plural array).
     * Returns null if not found — callers handle fallback themselves.
     */
    public static function getRaw(string $namespace, string $key): string|array|null
    {
        return self::$registry[$namespace][$key] ?? null;
    }

    /**
     * Get a translated string for direct output (no args).
     * Strips the translator hint prefix ('record|Delete %s' → 'Delete %s').
     * Returns $fallback or an auto-generated string if key not found.
     */
    public static function get(string $namespace, string $key, string $fallback = ''): string
    {
        $val = self::$registry[$namespace][$key] ?? null;

        if ($val === null) {
            return $fallback !== '' ? $fallback : self::autoFallback($key);
        }

        $str = is_array($val)
            ? ($val['other'] ?? $val['one'] ?? (string)reset($val))
            : (string)$val;

        return self::stripHint($str);
    }

    /**
     * Get the hint part of a value ('record|Delete %s' → 'record').
     * Returns empty string if no hint is present.
     * Used by L_() when an argument cannot be resolved.
     */
    public static function getHint(string $namespace, string $key): string
    {
        $val = self::$registry[$namespace][$key] ?? null;
        if ($val === null || is_array($val)) return '';
        $str = (string)$val;
        $pos = strpos($str, '|');
        if ($pos === false || $pos === 0 || ($str[$pos + 1] ?? '') === '|') return '';
        return substr($str, 0, $pos);
    }

    /**
     * Get a plural-aware translation for count $n.
     */
    public static function getPlural(string $namespace, string $key, int $n, string $fallback = ''): string
    {
        $val = self::$registry[$namespace][$key] ?? null;

        if ($val === null) {
            $tpl = $fallback !== '' ? $fallback : '%d ' . self::autoFallback($key);
            return sprintf($tpl, $n);
        }
        if (is_string($val)) return sprintf($val, $n);

        // Explicit 'zero' key always wins when count === 0, in any language.
        // Lets translators write "No files found" instead of "0 files found"
        // without fighting CLDR rules. Purely opt-in — omit the key and the
        // normal plural category ('other', 'many', etc.) is used as before.
        if ($n === 0 && isset($val['zero'])) {
            return sprintf((string)$val['zero'], $n);
        }

        $category = PluralRules::category(self::$locale, $n);
        $form     = $val[$category] ?? $val['other'] ?? $val['many'] ?? $val['one'] ?? reset($val);
        return sprintf((string)$form, $n);
    }

    public static function has(string $namespace, string $key): bool
    {
        return isset(self::$registry[$namespace][$key]);
    }

    public static function getRegistry(): array { return self::$registry; }

    /**
     * Sync registry namespaces back into PHP globals for backward compatibility.
     *
     * WBCE core and modules use $TEXT['KEY'], $MENU['KEY'] etc. directly.
     * After Lang::loadCore() loads all translations into the registry, calling
     * syncToGlobals() makes the same data available as globals — so existing
     * code continues to work without any changes.
     *
     * Called once in initialize.php after loadCore():
     *   Lang::loadCore(LANGUAGE, WB_PATH . '/languages');
     *   Lang::syncToGlobals();
     *
     * @param string[] $namespaces  Namespaces to sync (default: the four core ones)
     */
    public static function syncToGlobals(array $namespaces = ['TEXT', 'MENU', 'HEADING', 'MESSAGE', 'HINT', 'OVERVIEW']): void
    {
        foreach ($namespaces as $ns) {
            if (!empty(self::$registry[$ns])) {
                $GLOBALS[$ns] = self::$registry[$ns];
            }
        }
    }

    // ── Missing key tracking ──────────────────────────────────────────────────

    /**
     * Record a translation miss. Called from _wbe_resolve_translation().
     * Only active when tracking is enabled (isAdmin).
     *
     * @param string $namespace  e.g. 'TEXT'
     * @param string $key        e.g. 'MISSING_KEY'
     * @param string $file       Caller file (from debug_backtrace)
     * @param int    $line       Caller line
     */
    public static function recordMiss(string $namespace, string $key, string $file, int $line): void
    {
        if (!self::$tracking) return;

        $id = $namespace . ':' . $key;

        // Already recorded this request
        if (isset(self::$misses[$id])) return;

        // Read existing log once to avoid re-logging known misses
        if (!self::$logRead) {
            self::readLog();
        }

        if (isset(self::$loggedMisses[$id])) return;

        // Strip WB_PATH for portability
        $relFile = defined('WB_PATH')
            ? str_replace(WB_PATH, '', str_replace('\\', '/', $file))
            : $file;

        self::$misses[$id] = [
            'namespace'  => $namespace,
            'key'        => $key,
            'file'       => $relFile,
            'line'       => $line,
            'first_seen' => date('c'),
        ];
    }

    /**
     * Flush accumulated misses to logs/i18n_missing.php.
     * Called automatically on shutdown.
     */
    public static function flushMissing(): void
    {
        if (empty(self::$misses)) return;

        $logFile = defined('WB_PATH')
            ? WB_PATH . '/var/logs/i18n_missing.php'
            : null;

        if (!$logFile) return;

        if (!self::$logRead) self::readLog();

        // Merge new misses (already deduped against existing)
        $all = self::$loggedMisses;   // existing: id → record
        foreach (self::$misses as $id => $record) {
            $all[$id] = $record;
        }

        // Write as PHP return array
        $lines   = [];
        $lines[] = "<?php if (!defined('WB_PATH')) die('No direct access'); ?>\n";
        $lines[] = "<?php\n/**\n * WBCE CMS i18n missing keys log\n";
        $lines[] = " * Auto-generated — do not edit manually.\n";
        $lines[] = " * Each entry is unique by namespace:key.\n */\n";
        $lines[] = "return [\n";
        foreach ($all as $id => $rec) {
            if (!is_array($rec)) continue;
            $lines[] = '    ' . var_export($id, true) . ' => ' . var_export($rec, true) . ",\n";
        }
        $lines[] = "];\n";

        file_put_contents($logFile, implode('', $lines));
    }

    // ── Internals ─────────────────────────────────────────────────────────────

    private static function merge(string $namespace, array $data): void
    {
        $merged = array_merge(self::$registry[$namespace] ?? [], $data);

        // Keep registry and global in sync simultaneously.
        // This means $MOD_FOO['KEY'] works everywhere without a separate
        // syncToGlobals() call — whether loaded via loadCore() or loadLanguage().
        self::$registry[$namespace] = $merged;
        $GLOBALS[$namespace]        = $merged;
    }

    /**
     * Strip translator hint prefix from a string value.
     * 'record|Delete %s'  →  'Delete %s'
     * 'Dashboard'         →  'Dashboard'   (no pipe — unchanged)
     */
    private static function stripHint(string $val): string
    {
        // Single | separates hint from value; || is the L_() fallback marker
        // and never appears in stored values. pos must be > 0 (non-empty hint).
        $pos = strpos($val, '|');
        if ($pos !== false && $pos > 0 && ($val[$pos + 1] ?? '') !== '|') {
            return substr($val, $pos + 1);
        }
        return $val;
    }

    private static function autoFallback(string $key): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $key)));
    }

    /**
     * Build the locale chain for a given locale.
     *
     * WBCE uses 2-char uppercase codes:
     *   'DE'  →  ['EN', 'DE']
     *   'EN'  →  ['EN']
     *   'FR'  →  ['EN', 'FR']
     *
     * familyLocale() returns null for 2-char codes (no underscore),
     * so the chain is always: base (EN) + exact locale if different.
     */
    private static function buildChain(string $locale): array
    {
        $chain  = ['EN'];
        $family = self::familyLocale($locale);
        if ($family && $family !== 'EN') $chain[] = $family;
        if ($locale !== 'EN' && $locale !== $family) $chain[] = $locale;
        return $chain;
    }

    private static function familyLocale(string $locale): ?string
    {
        $parts = explode('_', $locale, 2);
        if (count($parts) !== 2) return null;
        return strtoupper($parts[0]);
    }

    private static function readLog(): void
    {
        self::$logRead      = true;
        self::$loggedMisses = [];

        $logFile = defined('WB_PATH') ? WB_PATH . '/var/logs/i18n_missing.php' : null;
        if (!$logFile || !is_file($logFile)) return;

        // The file guard checks for WB_PATH — always defined by this point.
        $data = include $logFile;
        if (is_array($data)) {
            self::$loggedMisses = $data;
        }
    }
}