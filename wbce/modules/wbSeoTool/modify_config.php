<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * modify_config.php
 * Settings form for the module's advanced configuration.
 * Rendered via the shared cp-* control-panel design system (see
 * templates/theme_fallbacks/css/cp_theme.css + cp_chrome.css), same
 * pattern as modules/captcha_control/tool.php.
 *
 * @package     wbSeoTool
 * @author      Christian M. Stefan (https://www.wbEasy.de/)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) exit("Cannot access this file directly " . __FILE__);

// user needs permission for admintools OR pages
if (!$admin->get_permission('admintools') || !$admin->get_permission('pages')) {
    exit("insuficient privileges");
}

$returnUrl = $toolUrl . '&pos=config';

// ── Save ─────────────────────────────────────────────────────────────────────
if (isset($_POST['save_config'])) {
    if (!$admin->checkFTAN()) {
        (new Alerts())->sessionToast($MESSAGE['GENERIC_SECURITY_ACCESS'], 'error');
        header('Location: ' . $returnUrl);
        exit;
    }

    $rewriteColumn = trim((string) ($_POST['rewriteUrl_dbString'] ?? ''));
    if ($rewriteColumn !== '' && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $rewriteColumn)) {
        $rewriteColumn = '';
    }

    $aSettings = [
        'iTitleCount' => [
            'use'     => isset($_POST['iTitleCount_use']) ? 1 : 0,
            'minimum' => (int) ($_POST['iTitleCount_minimum'] ?? 30),
            'optimum' => (int) ($_POST['iTitleCount_optimum'] ?? 50),
            'maximum' => (int) ($_POST['iTitleCount_maximum'] ?? 60),
        ],
        'iDescriptionCount' => [
            'use'     => isset($_POST['iDescriptionCount_use']) ? 1 : 0,
            'minimum' => (int) ($_POST['iDescriptionCount_minimum'] ?? 90),
            'optimum' => (int) ($_POST['iDescriptionCount_optimum'] ?? 150),
            'maximum' => (int) ($_POST['iDescriptionCount_maximum'] ?? 160),
        ],
        'keywordsConfig' => [
            'use'         => isset($_POST['keywordsConfig_use']) ? 1 : 0,
            'wordReplace' => trim((string) ($_POST['keywordsConfig_wordReplace'] ?? '')),
        ],
        'menuTitleConfig' => [
            'use' => isset($_POST['menuTitleConfig_use']) ? 1 : 0,
        ],
        'rewriteUrl' => [
            'use'      => isset($_POST['rewriteUrl_use']) ? 1 : 0,
            'dbString' => $rewriteColumn,
        ],
        'bUseRemainingChars' => isset($_POST['bUseRemainingChars']) ? 1 : 0,
        'bUseFlags'          => isset($_POST['bUseFlags']) ? 1 : 0,
    ];

    $setError = Settings::set('seo_cfg', json_encode($aSettings));

    if ($setError) {
        (new Alerts())->sessionToast($setError, 'error');
    } else {
        (new Alerts())->sessionToast('Changes saved successfully', 'success');
    }
    header('Location: ' . $returnUrl);
    exit;
}

// ── Render ───────────────────────────────────────────────────────────────────
$config = json_decode((string) Settings::get('seo_cfg', '{}'), true) ?: [];

$rewriteColumnMissing  = false;
$rewriteColumnWarning  = '';
$sConfiguredColumn     = (string) ($config['rewriteUrl']['dbString'] ?? '');
if ($sConfiguredColumn !== '' && !$database->fieldExists('{TP}pages', $sConfiguredColumn)) {
    $rewriteColumnMissing = true;
    $rewriteColumnWarning = sprintf($TXT['REWRITE_URL_WARNING_HINT'], htmlspecialchars($sConfiguredColumn));
}

// No 'T'/'lang' passed — templates read strings directly via L_('TXT:KEY') /
// L_('TEXT:KEY'), which resolve from the Lang registry (always populated by
// Lang::loadLanguage() in tool.php), so nothing needs to be forwarded here.
$oTwig = getTwig(dirname(__FILE__) . '/twig/');
$oTwig->load('modify_config.twig')->display([
    'RETURN_URL'          => $returnUrl,
    'RETURN_TO_TOOL'      => $toolUrl,
    'config'              => $config,
    'rewriteColumnMissing' => $rewriteColumnMissing,
    'rewriteColumnWarning' => $rewriteColumnWarning,
]);
