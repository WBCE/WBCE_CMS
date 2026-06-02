<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 *
 * Session management class for WBCE CMS.
 *
 * Central class to handle session start, regeneration, persistent data
 * and CSRF-like tokens. Uses its own namespace inside $_SESSION to avoid
 * collisions with other software running in the same PHP environment.
 *
 * Design goals (originally noted as @todo in the dev branch):
 *   - Subarray namespacing so WBCE vars do not collide with other scripts
 *   - Persistent storage that survives logout (e.g. language preference)
 *   - Replaceable by a module via the autoloader (drop-in override)
 *   - Modern PHP 8.x with type hints and return types throughout
 *
 * @note  SESSION_STARTED constant is still defined after session_start()
 *        for installer and legacy module compatibility. New code should
 *        use WbceSession::isStarted() instead of checking the constant.
 *
 */

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

class WbceSession
{
    // ── Namespace keys inside $_SESSION ──────────────────────────────────────

    /** Sub-array key for normal session data (cleared on logout) */
    public static string $store          = 'WBCE';

    /** Sub-array key for persistent data (survives logout, e.g. language) */
    public static string $storePersistent = 'WBCE_Perm';

    /** Session lifetime in seconds — overridden by DB settings on start() */
    public static int $ttl = 7200;

    // ── Core lifecycle ────────────────────────────────────────────────────────

    /**
     * Start or resume the session.
     *
     * Called once from initialize.php after the DB connection is ready.
     * Safe to call during install/upgrade — falls back gracefully when
     * Settings or constants are not yet available.
     */
    public static function start(): void
    {
        // Determine session lifetime from DB settings if available.
        // This runs before constants like WB_SECFORM_TIMEOUT are defined,
        // so the installer can also get a sensible expiry.
        self::tryToSetValidExpirationForInstallOrUpgrade();

        // ── Cookie security settings ──────────────────────────────────────
        if (!self::isStarted()) {
            ini_set('session.use_cookies',    true);
            ini_set('session.gc_maxlifetime', (int) self::$ttl);
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_samesite', 'Lax');

            // Secure flag — only when we know we are on HTTPS.
            // The defined() guard prevents a fatal error during early bootstrap
            // (e.g. install) where DOMAIN_PROTOCOLL may not yet be defined.
            if (defined('DOMAIN_PROTOCOLL') && DOMAIN_PROTOCOLL === 'https') {
                ini_set('session.cookie_secure', 1);
            }
            defined('APP_NAME') or define('APP_NAME', 'wbce');
            session_name(APP_NAME . '-sid');
            session_set_cookie_params(0);
        }

        // ── Decide whether to actually start a session ────────────────────
        // Some public pages opt out of cookies (NO_SESSION_COOKIE = true).
        // Even then, module pages and the admin area always get a session.
        $noSessionCookie = defined('NO_SESSION_COOKIE') && NO_SESSION_COOKIE === true;
        $needsSession    = !$noSessionCookie
            || preg_match('@(modules/|/' . ADMIN_DIRECTORY . '/)@', $_SERVER['REQUEST_URI'] ?? '');

        if ($needsSession && !self::isStarted()) {
            session_start();

            // SESSION_STARTED constant: kept for installer compatibility and
            // older modules that check it directly. New code: use isStarted().
            if (!defined('SESSION_STARTED')) {
                define('SESSION_STARTED', true);
            }

            // SessionTokenIdentifier is used by SecureForm so CSRF tokens
            // remain valid across session_regenerate_id() calls.
            if (!self::get('SessionTokenIdentifier')) {
                self::set('SessionTokenIdentifier', self::generateSecureToken(32));
            }
        }

        // ── Enforce session lifetime ──────────────────────────────────────
        // PHP's gc_maxlifetime is not reliable for per-session expiry,
        // so we track it ourselves with a discard_after timestamp.
        $now = time();
        if (self::get('discard_after') && $now > self::get('discard_after')) {
            self::reinit();
        }

        if (defined('WB_SECFORM_TIMEOUT')) {
            self::set('discard_after', $now + WB_SECFORM_TIMEOUT);
        }

        // Legacy compatibility: some older modules check $_SESSION['session_started']
        if (!isset($_SESSION['session_started'])) {
            $_SESSION['session_started'] = $now;
        }
    }

    /**
     * Determine a reasonable session expiry from DB settings.
     *
     * Called before constants are fully defined (installer, early bootstrap),
     * so we try multiple methods in order of reliability.
     *
     * The dev branch had a typo: Settings::GetDB() vs Settings::GetDb() —
     * both appeared in the same method. Unified to Settings::getFromDb()
     * which is the correct new API name.
     */
    private static function tryToSetValidExpirationForInstallOrUpgrade(): void
    {
        // Start from the current php.ini value as baseline
        self::$ttl = (int) ini_get('session.gc_maxlifetime');

        // Prefer cached Settings (fast, already in memory)
        if (Settings::get('wb_session_timeout') !== false) {
            self::$ttl = (int) Settings::get('wb_session_timeout');
            return;
        }
        if (Settings::get('wb_secform_timeout') !== false) {
            self::$ttl = (int) Settings::get('wb_secform_timeout');
            return;
        }

        // Fall back to direct DB read (slower, but works before cache is warm)
        if (Settings::getFromDb('wb_session_timeout') !== false) {
            self::$ttl = (int) Settings::getFromDb('wb_session_timeout');
            return;
        }
        if (Settings::getFromDb('wb_secform_timeout') !== false) {
            self::$ttl = (int) Settings::getFromDb('wb_secform_timeout');
        }
    }

    // ── State checks ──────────────────────────────────────────────────────────

    /**
     * Check whether a PHP session is currently active.
     *
     * The dev branch only checked defined('SESSION_STARTED'), which failed
     * in no-cookie contexts where session_start() was never called and the
     * constant was therefore never defined. session_status() is the reliable
     * PHP 5.4+ way to check session state.
     *
     * SESSION_STARTED is still checked first for installer compatibility —
     * the installer defines it manually before WbceSession is available.
     */
    public static function isStarted(): bool
    {
        if (defined('SESSION_STARTED')) {
            return true;
        }

        return session_status() === PHP_SESSION_ACTIVE;
    }

    // ── Normal session storage ─────────────────────────────────────────────────

    /**
     * Get a value from the WBCE session namespace.
     *
     * Falls back to the bare $_SESSION[$var] key for backward compatibility
     * with older modules and code that wrote directly to $_SESSION
     * (e.g. $_SESSION['LANGUAGE'] = 'DE' instead of WbceSession::set()).
     *
     * @param string $var     Key name
     * @param mixed  $default Return value when key is not found
     * @return mixed
     */
    public static function get(string $var = '', mixed $default = false): mixed
    {
        if (empty($var)) {
            return $default;
        }

        // Primary: namespaced WBCE storage
        if (isset($_SESSION[self::$store][$var])) {
            return $_SESSION[self::$store][$var];
        }

        // Fallback: bare $_SESSION key — for old modules and direct assignments
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }

        return $default;
    }

    /**
     * Set a value in the WBCE session namespace.
     */
    public static function set(string $var, mixed $value): void
    {
        if (!empty($var)) {
            $_SESSION[self::$store][$var] = $value;
        }
    }

    // ── Persistent session storage ─────────────────────────────────────────────

    /**
     * Get a value from persistent session storage.
     *
     * Persistent data survives reinit() / logout — useful for language, theme,
     * or other preferences that should persist across login sessions.
     */
    public static function getPersistent(string $var = '', mixed $default = false): mixed
    {
        if (empty($var)) {
            return $default;
        }

        return $_SESSION[self::$storePersistent][$var] ?? $default;
    }

    /**
     * Set a value in persistent session storage.
     */
    public static function setPersistent(string $var, mixed $value): void
    {
        if (!empty($var)) {
            $_SESSION[self::$storePersistent][$var] = $value;
        }
    }

    // ── Session lifecycle ─────────────────────────────────────────────────────

    /**
     * Soft logout — clears normal session data but keeps persistent data.
     *
     * Used on logout and session timeout. The persistent namespace (language,
     * preferences) is saved, the session ID is regenerated, and persistent
     * data is restored. A new SessionStarted timestamp is written.
     *
     * Guard: if no session is running (no-cookie context) this is a no-op
     * to avoid PHP warnings from session_unset() on an inactive session.
     * The dev branch called session_unset() without this guard.
     */
    public static function reinit(): void
    {
        if (!self::isStarted()) {
            return;
        }

        // Save persistent data before clearing everything
        $savePersistent = $_SESSION[self::$storePersistent] ?? [];

        session_unset();
        self::regenerateId(true);

        self::set('SessionStarted', time());
        $_SESSION[self::$storePersistent] = $savePersistent;
    }

    /**
     * Hard reset — destroys the session completely and starts a new one.
     *
     * Use only when you need a completely clean slate (e.g. security incident,
     * forced role switch). Persistent data is also lost.
     *
     * @param bool $kill  If true, terminate the script after destroying the session.
     */
    public static function restart(bool $kill = false): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();

        if ($kill) {
            die('Session and script terminated by WbceSession::restart(true)');
        }

        self::start();
        self::regenerateId(true);
    }

    /**
     * Regenerate the session ID.
     *
     * Should be called after privilege changes (login, logout, role change)
     * to prevent session fixation attacks.
     *
     * @param bool $deleteOldSession  Delete the old session file/record.
     */
    public static function regenerateId(bool $deleteOldSession = false): void
    {
        session_regenerate_id($deleteOldSession);
    }

    /**
     * Logout — alias for reinit().
     * Kept as a named alias so call sites read clearly.
     */
    public static function logout(): void
    {
        self::reinit();
    }

    // ── Token generation ──────────────────────────────────────────────────────

    /**
     * Generate a cryptographically secure random token.
     *
     * Replaces the old RandomGen->TextToken() dependency from the dev branch.
     * random_bytes() is the PHP 7.0+ standard for secure random data and does
     * not require any external class.
     *
     * @param  int    $length  Byte length of random data (hex output is 2× this)
     * @return string          Hex-encoded token, e.g. 64 chars for $length = 32
     */
    private static function generateSecureToken(int $length = 32): string
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (\Exception $e) {
            // Should never happen on a properly configured server,
            // but log and fall back rather than crashing.
            trigger_error('WbceSession: random_bytes() failed: ' . $e->getMessage(), E_USER_WARNING);
            return hash('sha256', uniqid((string) mt_rand(), true));
        }
    }

    // ── Debug ─────────────────────────────────────────────────────────────────

    /**
     * Dump all session data split into three buckets:
     *   1. Normal WBCE namespace (cleared on logout)
     *   2. Persistent WBCE namespace (survives logout)
     *   3. Bare $_SESSION keys set by third-party code or old modules
     *
     * Uses dump() when available (WBCE_DEBUG = true, WBCE native),
     * otherwise falls back to var_dump inside a <pre> block.
     *
     * The dev branch version had a copy-paste bug: $sOut was assigned with =
     * inside every loop instead of concatenated with .=, so only the last
     * item was ever visible in the output. Fixed here.
     */
    public static function debug(): void
    {
        $normal     = $_SESSION[self::$store]          ?? [];
        $persistent = $_SESSION[self::$storePersistent] ?? [];

        // Everything outside the two WBCE namespaces
        $global = $_SESSION;
        unset($global[self::$store], $global[self::$storePersistent]);

        $debugData = [
            'WBCE Session (normal)'     => $normal,
            'WBCE Session (persistent)' => $persistent,
            'Other $_SESSION keys'      => $global,
        ];

        if (function_exists('dump')) {
            dump($debugData, 'WbceSession::debug()');
        } else {
            echo '<pre style="background:#fffeed;border:1px solid #ccc;padding:1em">';
            echo '<strong>WbceSession::debug()</strong>' . PHP_EOL;
            var_dump($debugData);
            echo '</pre>';
        }
    }
}
