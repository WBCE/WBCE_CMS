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
 *   enabled_captcha  → true/false  (flat row → constant ENABLED_CAPTCHA)
 *   enabled_asp      → true/false  (flat row → constant ENABLED_ASP)
 *   captcha_type     → 'altcha'    (flat row → constant CAPTCHA_TYPE)
 *   captcha_altcha   → JSON: { hmac_key, max, ttl,
 *                              auto, delay, hidefooter, hidelogo,
 *                              color_brand, color_base, color_text, border_radius }
 *
 * @author      Ryan Djurovich, WebsiteBaker Project, WBCE contributors
 * @author      Christian M. Stefan (refactored)
 * @license     GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 */

class Captcha
{
    // ── Config ────────────────────────────────────────────────────────────────

    /** @var array|null Cached ALTCHA provider settings — null until first getAltchaCfg() call */
    private static ?array $altchaCfg = null;

    /** ALTCHA provider defaults */
    private static array $altchaCfgDefaults = [
        'hmac_key'      => '',
        'max'           => 50000,
        'ttl'           => 600,
        // Widget customization (empty = use ALTCHA's own defaults)
        'auto'          => 'off',   // 'off' | 'onload' | 'onsubmit'
        'delay'         => 0,       // ms pause before PoW starts (0–3000)
        'hidefooter'    => false,   // hide "Powered by ALTCHA" footer
        'hidelogo'      => false,   // hide ALTCHA shield logo in footer
        'color_brand'   => '',      // accent: spinner/button/border (hex or '')
        'color_success' => '',      // checked fill + auto-contrast tick (hex or '')
        'color_base'    => '',      // widget background (hex or '')
        'color_checkbox'=> '',      // checkbox background, overrides color_base (hex or '')
        'color_text'    => '',      // widget text (hex or '')
        'border_radius' => '',      // ''|'0px'|'4px'|'12px'
    ];

    /**
     * Load and cache the ALTCHA provider settings from the 'captcha_altcha' JSON key.
     * Falls back to $altchaCfgDefaults for any missing key.
     *
     * Note: enabled_captcha / enabled_asp / captcha_type are stored as flat
     * Settings rows and are available as PHP constants (ENABLED_CAPTCHA etc.)
     * so all modules — including older ones — can read them without changes.
     */
    public static function getAltchaCfg(): array
    {
        if (self::$altchaCfg === null) {
            self::$altchaCfg = array_merge(
                self::$altchaCfgDefaults,
                json_decode(Settings::get('captcha_altcha', '{}'), true) ?? []
            );
        }
        return self::$altchaCfg;
    }

    /**
     * Invalidate the ALTCHA config cache.
     * Must be called after Settings::set('captcha_altcha', ...).
     */
    public static function resetAltchaCfg(): void
    {
        self::$altchaCfg = null;
    }

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
        // altcha.min.js runs first, i18n second, widget_setup last.
        //
        // widget_setup.js is a static external file rather than an inline <script>
        // because two code paths both fail for inline JS with {…}:
        //   • frontend pages: WBCE template processor eats any {...} in module output
        //   • admin/login:    Captcha::render() runs inside ob_start()/ob_get_clean()
        //                     so I::insertJsCode() items are never output
        // A plain <script src="…"> survives both paths unharmed.
        $setupJs = $moduleUrl . '/altcha/widget_setup.js';
        if (!defined('_CAPTCHA_ALTCHA_SCRIPT_LOADED')) {
            define('_CAPTCHA_ALTCHA_SCRIPT_LOADED', true);
            echo '<script src="' . h($widgetJs) . '"></script>' . "\n";
            if ($wbceLang !== 'EN') {
                echo '<script src="' . h($i18nJs) . '"></script>' . "\n";
            }
            echo '<script src="' . h($setupJs) . '"></script>' . "\n";
        }

        // Sync token — lets legacy modules' own $_POST['captcha'] check pass.
        // The session key is initialised here (not in renderSyncInput) so we can
        // also embed it as a data-token attribute on the widget element below.
        $syncKey = 'captcha' . $sec_id;
        if (!isset($_SESSION[$syncKey])) {
            $_SESSION[$syncKey] = bin2hex(random_bytes(16));
        }
        self::renderSyncInput($sec_id);

        // Widget — apply saved customization settings
        $cfg      = self::getAltchaCfg();
        $autoAttr = in_array($cfg['auto'] ?? '', ['onload', 'onsubmit']) ? $cfg['auto'] : 'off';

        // data-syncid / data-token: read by widget_setup.js to fill the hidden
        //   sync input when the challenge is solved (no inline JS required).
        // data-hidefooter / data-hidelogo: plain "1"/"" attributes, no {…} chars,
        //   so they survive the WBCE template processor and ob_start() capture alike.
        $syncInputId = 'captcha_sync_' . ($sec_id !== '' ? $sec_id : 'default');

        $attrStr = ' id="' . h($widgetId) . '"'
                 . ' challenge="' . h($challengeUrl) . '"'
                 . ' name="' . h($inputName) . '"'
                 . ' auto="' . $autoAttr . '"'
                 . ' language="' . h($i18nCode) . '"'
                 . ' data-syncid="' . h($syncInputId) . '"'
                 . ' data-token="'  . h($_SESSION[$syncKey]) . '"'
                 . (!empty($cfg['hidefooter']) ? ' data-hidefooter="1"' : '')
                 . (!empty($cfg['hidelogo'])   ? ' data-hidelogo="1"'   : '');

        // Widget CSS customization.
        //
        // ALTCHA's .altcha* { all: revert-layer } breaks CSS custom-property inheritance for
        // nested children. We therefore target the ACTUAL CSS PROPERTIES directly using
        // high-specificity child selectors emitted as an inline <style> immediately before
        // the widget. This sidesteps all cascade/variable-inheritance issues.
        //
        // Additionally, CSS variables on the host element are still set (style="") for
        // .altcha-main (background/text/radius), because that element is a direct child and
        // is NOT matched by `.altcha *`.
        $wid = $widgetId;   // short alias for selectors below

        // ── Build inline style for the host element (direct child .altcha-main) ──
        $hostStyle = '';
        if (!empty($cfg['color_base'])) {
            $hostStyle .= '--altcha-color-base:' . h($cfg['color_base']) . ';';
        }
        // Text color: explicit or auto-contrast to keep logo/text visible on custom dark bg
        $textColor = '';
        if (!empty($cfg['color_text'])) {
            $textColor = h($cfg['color_text']);
        } elseif (!empty($cfg['color_base'])) {
            $hex  = ltrim($cfg['color_base'], '#');
            $r    = hexdec(substr($hex, 0, 2)) / 255;
            $g    = hexdec(substr($hex, 2, 2)) / 255;
            $b    = hexdec(substr($hex, 4, 2)) / 255;
            $lum  = 0.299 * $r + 0.587 * $g + 0.114 * $b;
            $textColor = $lum < 0.5 ? '#f9fafb' : '#111827';
        }
        if ($textColor !== '')             { $hostStyle .= '--altcha-color-base-content:' . $textColor . ';'; }
        if (!empty($cfg['border_radius'])) { $hostStyle .= '--altcha-border-radius:'      . h($cfg['border_radius']) . ';'; }
        if ($hostStyle !== '') { $attrStr .= ' style="' . $hostStyle . '"'; }

        // ── Build direct child-element rules (bypass CSS-var inheritance) ──────────
        // We collect checkbox-input rules separately so brand + base can both
        // contribute to a single selector (avoids duplicate rule overhead).
        $cbRules    = '';
        $styleBlock = '';

        if (!empty($cfg['color_brand'])) {
            $brand    = h($cfg['color_brand']);
            // Checkbox: accent border, 2 px thick to match the preview mock
            $cbRules .= 'border-color:' . $brand . '!important;'
                      . 'border-width:2px!important;'
                      . 'border-style:solid!important;';
            // Spinner ring (top + left sides; bottom+right stay transparent)
            $styleBlock .= '#' . $wid . ' .altcha-spinner{'
                         . 'border-top-color:'  . $brand . '!important;'
                         . 'border-left-color:' . $brand . '!important;}';
            // Primary button (resend / popover, if ever visible)
            $styleBlock .= '#' . $wid . ' .altcha-button{'
                         . 'background:' . $brand . '!important;'
                         . 'border-color:' . $brand . '!important;}';
        }

        // Checked state: custom success / verified colour.
        // - checkbox fill + border when :checked  → color_success
        // - tick SVG stroke                       → auto-contrast of color_success
        //
        // SVG CENTERING FIX: ALTCHA's tick SVG is positioned via CSS variables
        // (--altcha-radio-svg-offset = --altcha-checkbox-size * 0.25). These
        // variables fail to inherit through ALTCHA's own `.altcha{all:revert-layer}`
        // rule, so left/top default to 0 and the checkmark appears in the top-left
        // corner. We replace the variable-based values with equivalent percentages
        // (25% / 50%) which are immune to inheritance issues and work for any
        // checkbox size. This rule is always emitted when color_success is set.
        if (!empty($cfg['color_success'])) {
            $success = h($cfg['color_success']);
            $hex     = ltrim($cfg['color_success'], '#');
            $r       = hexdec(substr($hex, 0, 2)) / 255;
            $g       = hexdec(substr($hex, 2, 2)) / 255;
            $b       = hexdec(substr($hex, 4, 2)) / 255;
            $tickCol = (0.299 * $r + 0.587 * $g + 0.114 * $b) < 0.5 ? '#f9fafb' : '#111827';
            $styleBlock .= '#' . $wid . ' .altcha-checkbox input:checked{'
                         . 'background-color:' . $success . '!important;'
                         . 'border-color:'     . $success . '!important;}';
            $styleBlock .= '#' . $wid . ' .altcha-checkbox input:checked+svg{'
                         . 'color:' . $tickCol . '!important;}';
            // Fix SVG position — percentage values bypass the CSS-variable
            // inheritance failure described above (25% = offset, 50% = size).
            $styleBlock .= '#' . $wid . ' .altcha-checkbox svg{'
                         . 'left:25%!important;'
                         . 'top:25%!important;'
                         . 'width:50%!important;'
                         . 'height:50%!important;}';
        }

        // Checkbox background: color_checkbox takes priority; color_base as fallback so the
        // native white input doesn't clash with a custom dark widget background.
        $checkboxBg = !empty($cfg['color_checkbox']) ? h($cfg['color_checkbox'])
                    : (!empty($cfg['color_base'])    ? h($cfg['color_base'])     : '');
        if ($checkboxBg !== '') {
            $cbRules .= 'background-color:' . $checkboxBg . '!important;';
        }
        if ($cbRules !== '') {
            $styleBlock = '#' . $wid . ' .altcha-checkbox input{' . $cbRules . '}' . $styleBlock;
        }
        if ($styleBlock !== '') {
            // I::insertCssCode() queues the rules for HEAD BTM- injection (frontend).
            // Additionally we echo an inline <style> so the rules also apply on
            // admin/login pages where the captcha is captured via ob_start()/ob_get_clean()
            // and the I:: queue may not reach the page's <head>.
            I::insertCssCode($styleBlock, 'HEAD BTM-');
            echo '<style>' . $styleBlock . '</style>' . "\n";
        }

        echo '<altcha-widget' . $attrStr . '></altcha-widget>' . "\n";
        // widget_setup.js (loaded above) reads the data-* attributes and handles
        // statechange + configure() — no inline JS or I:: call needed here.
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

        $hmacKey = self::getAltchaCfg()['hmac_key'] ?? '';
        if (empty($hmacKey)) {
            trigger_error('ALTCHA: hmac_key is not configured in captcha_altcha settings.', E_USER_WARNING);
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