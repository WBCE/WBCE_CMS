<?php
/**
 * WBCE Captcha — ALTCHA + optional Honeypot (ASP)
 *
 * Only one captcha type is available: ALTCHA (proof-of-work, self-hosted).
 * The Honeypot is an independent extra protection layer controlled by the
 * `enabled_asp` setting — it is NOT a captcha type and does not appear in
 * the captcha_control dropdown.
 *
 * Usage (unchanged for existing modules)
 * ───────────────────────────────────────
 *   call_captcha();                   // renders ALTCHA widget (+ honeypot if ASP on)
 *   call_captcha('widget');           // same, no table wrapper
 *   Captcha::verify($_POST['captcha'], $sec_id);   // verifies both layers
 *
 * Settings table
 * ──────────────
 *   captcha_type              → 'altcha' (only valid value now)
 *   enabled_captcha           → true
 *   enabled_asp               → true/false  (honeypot extra layer)
 *   captcha_altcha_hmac_key   → 64-char hex (generate with AltchaLib::generateHmacKey())
 *   captcha_altcha_max        → integer, default 50000
 *   captcha_altcha_ttl        → seconds,  default 600
 *
 * @author      Ryan Djurovich, WebsiteBaker Project, WBCE contributors
 * @author      Christian M. Stefan (refactored)
 * @license     GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */

class Captcha
{
    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Resolve whether captcha is enabled — local module setting takes precedence
     * over the global ENABLED_CAPTCHA constant.
     *
     * Usage:
     *   Captcha::isEnabled()               // use global setting
     *   Captcha::isEnabled(true)           // force on  (module override)
     *   Captcha::isEnabled(false)          // force off (module override)
     *   Captcha::isEnabled($local ?? null) // three-state: null = fall back to global
     *
     * @param  bool|null $override  Module-level override; null = use global constant.
     * @return bool
     */
    public static function isEnabled(?bool $override = null): bool
    {
        return $override ?? (defined('ENABLED_CAPTCHA') && ENABLED_CAPTCHA);
    }

    /**
     * Resolve whether ASP (honeypot) is enabled — same override pattern as isEnabled().
     *
     * @param  bool|null $override  Module-level override; null = use global constant.
     * @return bool
     */
    public static function isAspEnabled(?bool $override = null): bool
    {
        return $override ?? (defined('ENABLED_ASP') && ENABLED_ASP);
    }

    /**
     * Render the captcha widget (+ honeypot if ENABLED_ASP is true).
     *
     * @param string      $action   'all'|'widget'|'image'|'image_iframe'|'input'|'text'
     *                              All actions render the full ALTCHA widget.
     *                              'text'  → empty (ALTCHA has its own built-in label).
     *                              'input' → hidden sync input + statechange script only.
     * @param string      $style    Unused, kept for backward compatibility.
     * @param string      $sec_id   Session key suffix for multiple captchas per page.
     * @param string|null $type_override  Ignored — only altcha is available.
     */
    public static function render(
        string  $action        = 'all',
        string  $style         = '',
        string  $sec_id        = '',
        ?string $type_override = null
    ): void {
        if (defined('NO_SESSION_COOKIE') && NO_SESSION_COOKIE) {
            echo '<div style="color:firebrick">ERROR: No session data available.</div>';
            return;
        }

        // Honeypot is always rendered alongside ALTCHA when ASP is enabled.
        $aspEnabled = self::isAspEnabled();

        switch ($action) {
            case 'text':
                // ALTCHA has its own built-in label ("Ich bin kein Roboter" etc.)
                break;

            case 'input':
                // Only the hidden sync input + listener — no widget chrome.
                self::renderSyncInput($sec_id);
                if ($aspEnabled) {
                    echo self::renderHoneypot($sec_id);
                }
                break;

            default:
                // 'all', 'widget', 'image', 'image_iframe' — render the full widget.
                self::renderAltchaWidget($sec_id);
                if ($aspEnabled) {
                    echo self::renderHoneypot($sec_id);
                }
                break;
        }
    }

    /**
     * Verify the submitted captcha (and honeypot if ASP is enabled).
     *
     * Handles two paths transparently:
     *
     * A) Modules that call Captcha::verify() directly — full ALTCHA crypto check.
     * B) Legacy modules that compare $_POST['captcha'] == $_SESSION['captcha']
     *    themselves — the sync token satisfies that check; if they also call
     *    verify() we do the full crypto check here too.
     *
     * @param  string|int $input  $_POST['captcha'] — the sync token for ALTCHA.
     * @param  string     $sec_id Same $sec_id used when rendering.
     * @return bool
     */
    public static function verify($input, string $sec_id = ''): bool
    {
        $key         = 'captcha' . $sec_id;
        $altchaField = 'altcha' . ($sec_id !== '' ? '_' . $sec_id : '');

        // ── Honeypot check (runs first — cheap, no crypto) ───────────────────
        if (defined('ENABLED_ASP') && ENABLED_ASP) {
            if (!self::verifyHoneypotField($sec_id)) {
                return false;
            }
        }

        // ── ALTCHA verification ───────────────────────────────────────────────
        if (!empty($_POST[$altchaField])) {
            // Full SHA-256 PoW + HMAC check.
            unset($_SESSION[$key]);
            return self::verifyAltcha($_POST[$altchaField]);
        }

        // ── Sync token path (legacy modules that check POST vs SESSION) ───────
        if (isset($_SESSION[$key])) {
            $expected = $_SESSION[$key];
            unset($_SESSION[$key]);
            return (string)trim($input) === (string)$expected;
        }

        return false;
    }


    // ── ALTCHA ════════════════════════════════════════════════════════════════

    /**
     * Render the full ALTCHA widget.
     *
     * Emits (in order, all synchronous):
     *   1. altcha.min.js              — defines $altcha, registers the custom element
     *   2. i18n.js                    — populates $altcha.i18n for non-EN languages
     *   3. <input name="captcha">     — sync token for legacy module checks
     *   4. <altcha-widget>            — the visible widget
     *   5. <script>                   — statechange listener that fills the sync input
     */
    private static function renderAltchaWidget(string $sec_id): void
    {
        $moduleUrl    = WB_URL . '/modules/captcha_control';
        $widgetJs     = $moduleUrl . '/altcha/altcha.min.js';
        $i18nJs       = $moduleUrl . '/altcha/i18n.js';
        $challengeUrl = $moduleUrl . '/altcha/altcha_challenge.php';
        $inputName    = $sec_id !== '' ? 'altcha_' . $sec_id : 'altcha';
        $widgetId     = 'altcha_widget_' . ($sec_id !== '' ? $sec_id : 'default');

        // Language mapping: WBCE LANGUAGE constant → ALTCHA i18n code.
        $wbceLang = defined('LANGUAGE') ? strtoupper(LANGUAGE) : 'EN';
        $i18nMap  = ['FR' => 'fr-fr', 'PT' => 'pt-pt', 'ES' => 'es-es', 'NO' => 'nb', 'GR' => 'el'];
        $i18nCode = strtolower($i18nMap[$wbceLang] ?? $wbceLang);

        // Scripts — emitted once per page, synchronous (no async/defer) so
        // altcha.min.js runs first, i18n second, widget tag parsed last.
        if (!defined('_CAPTCHA_ALTCHA_SCRIPT_LOADED')) {
            define('_CAPTCHA_ALTCHA_SCRIPT_LOADED', true);
            echo '<script src="' . h($widgetJs) . '"></script>' . "\n";
            if ($wbceLang !== 'EN') {
                echo '<script src="' . h($i18nJs) . '"></script>' . "\n";
            }
        }

        // Sync token — lets legacy modules' own $_POST['captcha'] check pass.
        self::renderSyncInput($sec_id);

        // Widget
        echo '<altcha-widget'
            . ' id="' . h($widgetId) . '"'
            . ' challenge="' . h($challengeUrl) . '"'
            . ' name="' . h($inputName) . '"'
            . ' auto="off"'
            . ' language="' . h($i18nCode) . '"'
            . '></altcha-widget>' . "\n";

        // statechange listener — fills the sync input when widget verifies.
        $syncInputId = 'captcha_sync_' . ($sec_id !== '' ? $sec_id : 'default');
        $jsWidget    = json_encode($widgetId);
        $jsSync      = json_encode($syncInputId);
        $jsToken     = json_encode($_SESSION['captcha' . $sec_id] ?? '');
        echo '<script>'
            . '(function(){'
            .   'var w=document.getElementById(' . $jsWidget . ');'
            .   'if(!w)return;'
            .   'w.addEventListener("statechange",function(e){'
            .     'if(e.detail&&e.detail.state==="verified"){'
            .       'var f=document.getElementById(' . $jsSync . ');'
            .       'if(f)f.value=' . $jsToken . ';'
            .     '}'
            .   '});'
            . '})();'
            . '</script>' . "\n";
    }

    /**
     * Render only the hidden sync input (for the 'input' action).
     * Also initialises the session token if not already set.
     */
    private static function renderSyncInput(string $sec_id): void
    {
        $key = 'captcha' . $sec_id;
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = bin2hex(random_bytes(16));
        }
        $syncInputId = 'captcha_sync_' . ($sec_id !== '' ? $sec_id : 'default');
        echo '<input type="hidden" name="captcha" id="' . h($syncInputId) . '" value="" />' . "\n";
    }

    /**
     * Server-side ALTCHA verification — SHA-256 PoW + HMAC.
     */
    private static function verifyAltcha(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $hmacKey = defined('CAPTCHA_ALTCHA_HMAC_KEY') ? CAPTCHA_ALTCHA_HMAC_KEY : '';
        if (empty($hmacKey)) {
            trigger_error('ALTCHA: CAPTCHA_ALTCHA_HMAC_KEY is not set.', E_USER_WARNING);
            return false;
        }

        $libFile = WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';
        if (!class_exists('AltchaLib')) {
            if (!file_exists($libFile)) {
                trigger_error('ALTCHA: AltchaLib.php not found at ' . $libFile, E_USER_WARNING);
                return false;
            }
            require_once $libFile;
        }

        return (new AltchaLib($hmacKey))->verifySolution($token);
    }


    // ══ Honeypot (ASP extra layer) ════════════════════════════════════════════

    /**
     * Render the honeypot field and return it as an HTML string.
     *
     * Public so modules can place the honeypot independently from the ALTCHA
     * widget (e.g. at the top of a form while the widget is near the submit button).
     *
     * Visually hidden via absolute positioning (not display:none — bots skip those).
     * Real users never see or fill it. Bots auto-fill all inputs and get blocked.
     * Also stores a timestamp for a minimum-time check (blocks instant submissions).
     *
     * @param  string $sec_id  Same sec_id used when calling verify().
     * @return string          HTML to output.
     */
    public static function renderHoneypot(string $sec_id = ''): string
    {
        $field = 'hp_' . $sec_id;
        $_SESSION['captcha_hp_time_' . $sec_id] = time();

        return '<div style="position:absolute;left:-9999px;height:1px;width:1px;overflow:hidden" aria-hidden="true">' . "\n"
             . '    <label for="' . h($field) . '">Leave this field empty</label>' . "\n"
             . '    <input type="text" id="' . h($field) . '" name="' . h($field) . '" value="" tabindex="-1" autocomplete="off" />' . "\n"
             . '</div>' . "\n";
    }

    /**
     * Verify the honeypot field.
     * Returns false if the field was filled OR the form was submitted too fast.
     */
    private static function verifyHoneypotField(string $sec_id): bool
    {
        $field = 'hp_' . $sec_id;

        // Field must be empty — if filled, it's a bot
        if (!empty($_POST[$field]) || !empty($_GET[$field])) {
            return false;
        }

        // Minimum time check — legitimate users take at least 2 seconds
        $timeKey = 'captcha_hp_time_' . $sec_id;
        if (isset($_SESSION[$timeKey])) {
            $elapsed = time() - $_SESSION[$timeKey];
            unset($_SESSION[$timeKey]);
            if ($elapsed < 2) {
                return false;
            }
        }

        return true;
    }
}