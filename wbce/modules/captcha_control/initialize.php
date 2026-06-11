<?php
/**
 * captcha_control — initialize.php
 *
 * Runs on every page load (FE + BE) because call_captcha() is used in both
 * contexts: frontend contact/registration forms and the backend login page.
 *
 * Registers the Captcha class with the WBCE autoloader and defines the
 * call_captcha() public API function.
 *
 * Language strings are NOT loaded here — only tool.php needs them, so they
 * are loaded on-demand there to avoid pointless work on every FE page.
 *
 * Pattern: same approach as CodeMirror_Config/initialize.php.
 */
defined('WB_PATH') or exit('No direct access allowed');

// ── 1. Register Captcha class ─────────────────────────────────────────────────
//
// Explicit AddFile wins over the AddDir('/framework/') scan, so even if
// a stale framework/Captcha.php exists it will not be loaded in its place.

WbAuto::AddFile('Captcha', '/modules/captcha_control/Captcha.php');

// ── 2. Define call_captcha() ──────────────────────────────────────────────────
//
// Guard: the shim at include/captcha/captcha.php also defines this function,
// so the guard prevents a fatal redefinition if both files are loaded.

if (!function_exists('call_captcha')) {
    /**
     * Render the captcha widget.
     *
     * @param string      $action        'all'|'widget'|'image'|'image_iframe'|'input'|'text'
     * @param string      $style         Unused, kept for backward compatibility.
     * @param string      $sec_id        Session key suffix for multiple captchas per page.
     * @param string|null $type_override Ignored — only altcha is available.
     */
    function call_captcha(
        string  $action        = 'all',
        string  $style         = '',
        string  $sec_id        = '',
        ?string $type_override = null
    ): void {
        Captcha::render($action, $style, $sec_id, $type_override);
    }
}
