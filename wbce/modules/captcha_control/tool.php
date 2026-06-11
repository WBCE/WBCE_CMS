<?php
/**
 * captcha_control — tool.php
 *
 * Admin-Tool entry point. Handles save and renders the Twig form.
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

defined('WB_PATH') or exit('No direct access allowed');
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

$returnUrl = ADMIN_URL . '/admintools/tool.php?tool=captcha_control';

// ── Save ──────────────────────────────────────────────────────────────────────
if (isset($_POST['save_settings'])) {
    if (!$admin->checkFTAN()) {
        (new Alerts())->sessionToast($MESSAGE['GENERIC_SECURITY_ACCESS'], 'error');
        header('Location: ' . $returnUrl);
        exit;
    }

    // Flat control settings — each becomes a PHP constant via Settings::setup()
    $e1 = Settings::set('enabled_captcha', ($_POST['enabled_captcha'] ?? '0') === '1' ? 'true' : 'false');
    $e2 = Settings::set('enabled_asp',     ($_POST['enabled_asp']     ?? '0') === '1' ? 'true' : 'false');
    $e3 = Settings::set('captcha_type',    in_array($_POST['captcha_type'] ?? '', ['altcha'])
                                           ? $_POST['captcha_type'] : 'altcha');

    // ALTCHA provider internals — preserve HMAC key, never accept it from POST
    $altcha  = Captcha::getAltchaCfg();
    $hmacKey = $altcha['hmac_key'] ?? '';
    if (empty($hmacKey)) {
        require_once WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';
        $hmacKey = AltchaLib::generateHmacKey();
    }

    // Sanitize widget customization fields from POST
    $allowedAuto   = ['off', 'onload', 'onsubmit'];
    $allowedRadius = ['', '0px', '4px', '12px'];

    $postAuto   = in_array($_POST['altcha_auto']          ?? '', $allowedAuto,   true) ? $_POST['altcha_auto']   : 'off';
    $postDelay  = max(0, min(3000, (int)($_POST['altcha_delay']   ?? 0)));
    $postHFoot  = ($_POST['altcha_hidefooter'] ?? '0') === '1';
    $postHLogo  = ($_POST['altcha_hidelogo']   ?? '0') === '1';
    $postRadius = in_array($_POST['altcha_border_radius'] ?? '', $allowedRadius, true) ? $_POST['altcha_border_radius'] : '';

    $postBrand   = sanitizeCssColor($_POST['altcha_color_brand']    ?? '');
    $postSuccess = sanitizeCssColor($_POST['altcha_color_success']  ?? '');
    $postBase    = sanitizeCssColor($_POST['altcha_color_base']     ?? '');
    $postCb      = sanitizeCssColor($_POST['altcha_color_checkbox'] ?? '');
    $postText    = sanitizeCssColor($_POST['altcha_color_text']     ?? '');

    $e4 = Settings::set('captcha_altcha', json_encode([
        'hmac_key'      => $hmacKey,
        'max'           => $altcha['max'] ?? 50000,
        'ttl'           => $altcha['ttl'] ?? 600,
        'auto'          => $postAuto,
        'delay'         => $postDelay,
        'hidefooter'    => $postHFoot,
        'hidelogo'      => $postHLogo,
        'color_brand'   => $postBrand,
        'color_success' => $postSuccess,
        'color_base'    => $postBase,
        'color_checkbox'=> $postCb,
        'color_text'    => $postText,
        'border_radius' => $postRadius,
    ]));
    Captcha::resetAltchaCfg();

    if ($e1 || $e2 || $e3 || $e4) {
        (new Alerts())->sessionToast($e1 ?: $e2 ?: $e3 ?: $e4, 'error');
    } else {
        (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
    }
    header('Location: ' . $returnUrl.'#config');
    exit;
}


// ── Render ────────────────────────────────────────────────────────────────────
I::insertCssFile(INCLUDE_URL . '/wbeColoris/wbeColoris.css', 'HEAD BTM+');
I::insertCssFile(INCLUDE_URL . '/wbeColoris/wbeColoris.admin.css', 'HEAD BTM+');
I::insertJsFile(INCLUDE_URL . '/wbeColoris/wbeColoris.js', 'HEAD BTM-');
I::insertJsFile(INCLUDE_URL . '/wbeColoris/wbeColoris.i18n.js', 'HEAD BTM-');
$oTwig    = getTwig(__DIR__ . '/twig/');
$altchaCfg = Captcha::getAltchaCfg();

$aToTwig = [
    'RETURN_URL'       => $returnUrl,
    'RETURN_TO_TOOLS'  => ADMIN_URL . '/admintools/index.php',
    'USEABLE_CAPTCHAS' => ['altcha' => 'ALTCHA (Proof-of-Work, self-hosted)'],
    'CAPTCHA_TYPE'     => defined('CAPTCHA_TYPE')    ? CAPTCHA_TYPE    : 'altcha',
    'ENABLED_CAPTCHA'  => defined('ENABLED_CAPTCHA') ? (ENABLED_CAPTCHA ? '1' : '0') : '1',
    'ENABLED_ASP'      => defined('ENABLED_ASP')     ? (ENABLED_ASP     ? '1' : '0') : '1',
    'ALTCHA'           => [
        'auto'          => $altchaCfg['auto']          ?? 'off',
        'delay'         => (int)($altchaCfg['delay']   ?? 0),
        'hidefooter'    => !empty($altchaCfg['hidefooter']),
        'hidelogo'      => !empty($altchaCfg['hidelogo']),
        'color_brand'   => $altchaCfg['color_brand']    ?? '',
        'color_success' => $altchaCfg['color_success'] ?? '',
        'color_base'    => $altchaCfg['color_base']    ?? '',
        'color_checkbox'=> $altchaCfg['color_checkbox']?? '',
        'color_text'    => $altchaCfg['color_text']    ?? '',
        'border_radius' => $altchaCfg['border_radius'] ?? '',
    ],
];

$oTwig->load('tool.twig')->display($aToTwig);
