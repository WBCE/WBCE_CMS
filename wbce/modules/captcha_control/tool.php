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
    $colorRegex    = '/^#[0-9a-fA-F]{6}$/';

    $postAuto   = in_array($_POST['altcha_auto']          ?? '', $allowedAuto,   true) ? $_POST['altcha_auto']   : 'off';
    $postDelay  = max(0, min(3000, (int)($_POST['altcha_delay']   ?? 0)));
    $postHFoot  = ($_POST['altcha_hidefooter'] ?? '0') === '1';
    $postHLogo  = ($_POST['altcha_hidelogo']   ?? '0') === '1';
    $postRadius = in_array($_POST['altcha_border_radius'] ?? '', $allowedRadius, true) ? $_POST['altcha_border_radius'] : '';

    $postBrand  = preg_match($colorRegex, $_POST['altcha_color_brand'] ?? '') ? $_POST['altcha_color_brand'] : '';
    $postBase   = preg_match($colorRegex, $_POST['altcha_color_base']  ?? '') ? $_POST['altcha_color_base']  : '';
    $postText   = preg_match($colorRegex, $_POST['altcha_color_text']  ?? '') ? $_POST['altcha_color_text']  : '';

    $e4 = Settings::set('captcha_altcha', json_encode([
        'hmac_key'      => $hmacKey,
        'max'           => $altcha['max'] ?? 50000,
        'ttl'           => $altcha['ttl'] ?? 600,
        'auto'          => $postAuto,
        'delay'         => $postDelay,
        'hidefooter'    => $postHFoot,
        'hidelogo'      => $postHLogo,
        'color_brand'   => $postBrand,
        'color_base'    => $postBase,
        'color_text'    => $postText,
        'border_radius' => $postRadius,
    ]));
    Captcha::resetAltchaCfg();

    if ($e1 || $e2 || $e3 || $e4) {
        (new Alerts())->sessionToast($e1 ?: $e2 ?: $e3 ?: $e4, 'error');
    } else {
        (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
    }
    header('Location: ' . $returnUrl);
    exit;
}

// ── Language ──────────────────────────────────────────────────────────────────
Lang::loadLanguage(WB_PATH . '/modules/captcha_control');

// ── Render ────────────────────────────────────────────────────────────────────
$oTwig    = getTwig(WB_PATH . '/modules/captcha_control/twig/');
$altchaCfg = Captcha::getAltchaCfg();

$aToTwig = [
    'RETURN_URL'       => $returnUrl,
    'RETURN_TO_TOOLS'  => ADMIN_URL . '/admintools/index.php',
    'TXT'              => [
        'HEADING'            => L_('MOD_CAPTCHA_CONTROL:HEADING'),
        'HOWTO'              => L_('MOD_CAPTCHA_CONTROL:HOWTO'),
        'CAPTCHA_EXP'        => L_('MOD_CAPTCHA_CONTROL:CAPTCHA_EXP'),
        'CAPTCHA_TYPE'       => L_('MOD_CAPTCHA_CONTROL:CAPTCHA_TYPE'),
        'USE_SIGNUP_CAPTCHA' => L_('MOD_CAPTCHA_CONTROL:USE_SIGNUP_CAPTCHA'),
        'ENABLED'            => L_('MOD_CAPTCHA_CONTROL:ENABLED'),
        'DISABLED'           => L_('MOD_CAPTCHA_CONTROL:DISABLED'),
        // Widget customization strings
        'WIDGET_HEADING'     => L_('CAPTCHA:WIDGET_HEADING'),
        'AUTO_LABEL'         => L_('CAPTCHA:AUTO_LABEL'),
        'AUTO_OFF'           => L_('CAPTCHA:AUTO_OFF'),
        'AUTO_ONLOAD'        => L_('CAPTCHA:AUTO_ONLOAD'),
        'AUTO_ONSUBMIT'      => L_('CAPTCHA:AUTO_ONSUBMIT'),
        'DELAY_LABEL'        => L_('CAPTCHA:DELAY_LABEL'),
        'DELAY_HINT'         => L_('CAPTCHA:DELAY_HINT'),
        'HIDEFOOTER'         => L_('CAPTCHA:HIDEFOOTER'),
        'HIDELOGO'           => L_('CAPTCHA:HIDELOGO'),
        'COLOR_BRAND'        => L_('CAPTCHA:COLOR_BRAND'),
        'COLOR_BRAND_HINT'   => L_('CAPTCHA:COLOR_BRAND_HINT'),
        'COLOR_BASE'         => L_('CAPTCHA:COLOR_BASE'),
        'COLOR_TEXT'         => L_('CAPTCHA:COLOR_TEXT'),
        'BORDER_RADIUS'      => L_('CAPTCHA:BORDER_RADIUS'),
        'COLOR_DEFAULT'      => L_('CAPTCHA:COLOR_DEFAULT'),
        'COLOR_CUSTOM'       => L_('CAPTCHA:COLOR_CUSTOM'),
        'CORNER_SQUARE'      => L_('CAPTCHA:CORNER_SQUARE'),
        'CORNER_LIGHT'       => L_('CAPTCHA:CORNER_LIGHT'),
        'CORNER_ROUND'       => L_('CAPTCHA:CORNER_ROUND'),
        'PREVIEW'            => L_('CAPTCHA:PREVIEW'),
        'WIDGET_FOOTER_TEXT' => L_('CAPTCHA:WIDGET_FOOTER_TEXT'),
    ],
    'USEABLE_CAPTCHAS' => ['altcha' => 'ALTCHA (Proof-of-Work, self-hosted)'],
    'CAPTCHA_TYPE'     => defined('CAPTCHA_TYPE')    ? CAPTCHA_TYPE    : 'altcha',
    'ENABLED_CAPTCHA'  => defined('ENABLED_CAPTCHA') ? (ENABLED_CAPTCHA ? '1' : '0') : '1',
    'ENABLED_ASP'      => defined('ENABLED_ASP')     ? (ENABLED_ASP     ? '1' : '0') : '1',
    'ALTCHA'           => [
        'auto'          => $altchaCfg['auto']          ?? 'off',
        'delay'         => (int)($altchaCfg['delay']   ?? 0),
        'hidefooter'    => !empty($altchaCfg['hidefooter']),
        'hidelogo'      => !empty($altchaCfg['hidelogo']),
        'color_brand'   => $altchaCfg['color_brand']   ?? '',
        'color_base'    => $altchaCfg['color_base']    ?? '',
        'color_text'    => $altchaCfg['color_text']    ?? '',
        'border_radius' => $altchaCfg['border_radius'] ?? '',
    ],
];

$oTwig->load('tool.twig')->display($aToTwig);
