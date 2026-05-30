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
        $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $returnUrl, false);
        return;
    }

    Settings::Set('enabled_captcha', ($_POST['enabled_captcha'] ?? '0') === '1' ? 'true' : 'false');
    Settings::Set('enabled_asp',     ($_POST['enabled_asp']     ?? '0') === '1' ? 'true' : 'false');

    // Generate HMAC key on first save if missing (e.g. fresh install, key lost)
    if (!defined('CAPTCHA_ALTCHA_HMAC_KEY') || empty(CAPTCHA_ALTCHA_HMAC_KEY)) {
        require_once WB_PATH . '/modules/captcha_control/altcha/AltchaLib.php';
        Settings::Set('captcha_altcha_hmac_key', AltchaLib::generateHmacKey());
    }

    header('Location: ' . $returnUrl);
    exit;
}

// ── Language ──────────────────────────────────────────────────────────────────
Lang::loadLanguage(WB_PATH . '/modules/captcha_control');

// ── Render ────────────────────────────────────────────────────────────────────
$oTwig   = getTwig(WB_PATH . '/modules/captcha_control/twig/');
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
    ],
    'USEABLE_CAPTCHAS' => ['altcha' => 'ALTCHA (Proof-of-Work, self-hosted)'],
    'CAPTCHA_TYPE'     => defined('CAPTCHA_TYPE') ? CAPTCHA_TYPE : 'altcha',
    'ENABLED_CAPTCHA'  => ENABLED_CAPTCHA ? '1' : '0',
    'ENABLED_ASP'      => ENABLED_ASP     ? '1' : '0',
];

$oTwig->load('tool.twig')->display($aToTwig);
