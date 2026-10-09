<?php
/**
 * Asset Optimizer — tool.php
 *
 * AdminTool controller: permission check, then state changes (settings save,
 * cache clear) followed by a plain data array handed to twig/tool.twig. No
 * markup here — see AssetInsight.php for the read side and twig/ for layout.
 *
 * Included by admin/admintools/tool.php, which already enforced the
 * 'admintools' permission, loaded the language files and opened the page shell.
 *
 * @author  Christian M. Stefan · www.wbEasy.de
 * @license GNU GPL2
 */

defined('WB_PATH') or die('Cannot access this file directly');
$admin->get_permission('admintools') or die(header('Location: ../../index.php'));

require_once __DIR__ . '/AssetInsight.php';

$base = ADMIN_URL . '/admintools/tool.php?tool=asset_optimizer';
$tab  = in_array($_GET['tab'] ?? '', ['cache', 'docs'], true) ? $_GET['tab'] : 'settings';

// Documentation tab — Markdown files rendered inline (MarkdownWbce). Keys are
// the ?doc= values; first entry is the default.
$DOC_PAGES = [
    'guide' => ['file' => '/modules/asset_optimizer/docs/TOOL_GUIDE.md',        'label_key' => 'DOC_GUIDE'],
    'embed' => ['file' => '/modules/asset_optimizer/docs/ASSETS_TUTORIAL.md',   'label_key' => 'DOC_EMBED'],
    'fonts' => ['file' => '/modules/asset_optimizer/docs/FONTS_TUTORIAL.md',    'label_key' => 'DOC_FONTS'],
    'ref'   => ['file' => '/modules/asset_optimizer/docs/ASSETS_REFERENCE.md',  'label_key' => 'DOC_REFERENCE'],
];

$pinned      = AssetInsight::configPinned();
$isPinned    = static fn (string $k): bool => in_array($k, $pinned, true);
$warnings    = [];

// ── Settings save (FTAN-protected POST, Post/Redirect/Get) ─────────────────
if (!empty($_POST['ao_save'])) {
    while (ob_get_level() > 0) { ob_end_clean(); }

    if (!$admin->checkFTAN()) {
        (new Alerts())->sessionToast($MESSAGE['GENERIC_SECURITY_ACCESS'], 'error');
        header('Location: ' . $base);
        exit;
    }

    $on = static fn (string $k): bool => !empty($_POST['ao'][$k]);

    // Constants whose value decides what the asset cache holds — minify on/off
    // and the .min filename suffix. Snapshot them so we can wipe the cache when
    // one flips; otherwise the old, now-mislabelled or now-unminified entries
    // linger until each source file happens to change. (MINIFY_USE_SUFFIX
    // defaults to on when undefined.)
    $cacheKeys = ['MINIFY_CSS', 'MINIFY_JS', 'MINIFY_USE_SUFFIX'];
    $before    = [
        'MINIFY_CSS'        => defined('MINIFY_CSS') && MINIFY_CSS,
        'MINIFY_JS'         => defined('MINIFY_JS') && MINIFY_JS,
        'MINIFY_USE_SUFFIX' => !defined('MINIFY_USE_SUFFIX') || (bool) MINIFY_USE_SUFFIX,
    ];

    // ── File-based booleans (var/config_constants.ini.php) ────────────────
    // Convention: a line is written only when the value differs from the
    // AssetQueue default, so an untouched install keeps a clean ini file.
    foreach (['MINIFY_CSS', 'MINIFY_JS', 'ASSETS_MINIFY_DEBUG', 'ASSET_QUEUE_DEBUG'] as $k) {
        if ($isPinned($k)) { continue; }
        if ($on($k)) {
            Settings::setFileBasedSetting($k, true);
        } else {
            Settings::deleteFileBasedSetting($k);
        }
    }

    // MINIFY_USE_SUFFIX default is ON — only persist the "off" case.
    if (!$isPinned('MINIFY_USE_SUFFIX')) {
        if ($on('MINIFY_USE_SUFFIX')) {
            Settings::deleteFileBasedSetting('MINIFY_USE_SUFFIX');
        } else {
            Settings::setFileBasedSetting('MINIFY_USE_SUFFIX', false);
        }
    }

    // Custom cache directory (string). The field is prefilled read-only with the
    // resolved default path, so a value equal to that default — or empty — means
    // "no custom directory": clear the constant.
    if (!$isPinned('MINIFY_ASSETS_DIR')) {
        $custom  = trim((string) ($_POST['minify_assets_dir'] ?? ''));
        $norm    = static fn (string $p): string => rtrim(str_replace('\\', '/', $p), '/');
        if ($custom !== '' && $norm($custom) !== $norm(AssetInsight::assetsDir())) {
            Settings::setFileBasedSetting('MINIFY_ASSETS_DIR', $custom);
        } else {
            Settings::deleteFileBasedSetting('MINIFY_ASSETS_DIR');
        }
    }

    // ── Cache busting → settings table (shared with Outputfilter Dashboard) ─
    // These two are owned in the settings table. If an older config left a copy
    // in config_constants.ini.php, drop it — a file-based constant is defined
    // first at boot and would shadow the settings-table value we write here.
    //
    // The backend never lags the public site: while front-end busting is on, the
    // backend switch is forced on (and shown read-only). It is only a free
    // choice when the front end has busting off — its checkbox is disabled then,
    // so it does not post; force the value here.
    $feBust = $isPinned('OPF_ASSETS_CACHE_BUSTING')
        ? (bool) AssetInsight::constantValue('OPF_ASSETS_CACHE_BUSTING')
        : $on('OPF_ASSETS_CACHE_BUSTING');
    $bustValue = [
        'OPF_ASSETS_CACHE_BUSTING'    => $on('OPF_ASSETS_CACHE_BUSTING'),
        'OPF_ASSETS_CACHE_BUSTING_BE' => $feBust ? true : $on('OPF_ASSETS_CACHE_BUSTING_BE'),
    ];
    $migrated = [];
    foreach (['OPF_ASSETS_CACHE_BUSTING' => 'opf_assets_cache_busting',
              'OPF_ASSETS_CACHE_BUSTING_BE' => 'opf_assets_cache_busting_be'] as $const => $row) {
        if ($isPinned($const)) { continue; }
        if (AssetInsight::constantSource($const) === 'ini') {
            Settings::deleteFileBasedSetting($const);
            $migrated[] = $const;
        }
        Settings::set($row, $bustValue[$const]);
    }
    if ($migrated) {
        (new Alerts())->sessionToast(sprintf($TXT['MIGRATED_TO_DB'], implode(', ', $migrated)), 'info');
    }

    // A minify/suffix switch changed → drop the asset cache so it is rebuilt
    // cleanly (correct filenames, minified or not) on the next page load.
    $cacheDirty = false;
    foreach ($cacheKeys as $k) {
        if (!$isPinned($k) && $before[$k] !== $on($k)) { $cacheDirty = true; }
    }
    if ($cacheDirty) {
        I::clearCache();
        (new Alerts())->sessionToast($TXT['ASSET_CACHE_CLEARED'], 'info');
    }

    (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
    header('Location: ' . $base);
    exit;
}

// ── Cache actions (GET) ───────────────────────────────────────────────────
if (isset($_GET['clear']) && $admin->checkFTAN('GET')) {
    if ($_GET['clear'] === 'assets') {
        I::clearCache();
        $warnings[] = ['type' => 'info', 'text' => $TXT['ASSET_CACHE_CLEARED']];
    } elseif ($_GET['clear'] === 'fonts') {
        I::clearFontCache();
        $warnings[] = ['type' => 'info', 'text' => $TXT['FONT_CACHE_CLEARED']];
    }
} elseif (isset($_GET['clear'])) {
    $warnings[] = ['type' => 'warning', 'text' => $MESSAGE['GENERIC_SECURITY_ACCESS']];
}

// ── View model ───────────────────────────────────────────────────────────
$toTwig = [
    'MODULE_NAME' => $module_name,
    'BASE'        => $base,
    'TAB'         => $tab,
    'WARNINGS'    => $warnings,
    'FTAN'        => $admin->getFTAN(),
    'FTAN_GET'    => $admin->getFTAN(false),  // querystring form for the clear links
];

if ($tab === 'settings') {

    loadPlugin('include/wbeSelect'); // reserved for future selects; harmless if unused

    $switch = static function (string $const, string $group): array {
        $src = AssetInsight::constantSource($const);
        $val = AssetInsight::constantValue($const);
        // MINIFY_USE_SUFFIX: "on" means the .min suffix is kept (the default).
        $on = $const === 'MINIFY_USE_SUFFIX'
            ? ($src === 'default' ? true : (bool) $val)
            : (bool) $val;
        return [
            'const'  => $const,
            'group'  => $group,
            'on'     => $on,
            'source' => $src,
            'locked' => $src === 'config.php',
            'forced' => false,   // set below for the backend cache-busting switch
        ];
    };

    $legacyDebug = AssetInsight::legacyDebugAlias();

    // Cache-busting switches. While front-end busting is on, the backend switch
    // follows it: forced on and read-only (the runtime does the same — see
    // AssetQueue::cacheBustingEnabled()). Editable only when the front end has
    // busting off. A config.php-pinned backend switch keeps its own lock.
    $bustFe = $switch('OPF_ASSETS_CACHE_BUSTING', 'bust');
    $bustBe = $switch('OPF_ASSETS_CACHE_BUSTING_BE', 'bust');
    if ($bustFe['on'] && !$bustBe['locked']) {
        $bustBe['on']     = true;
        $bustBe['forced'] = true;
    }

    $dirCustom  = defined('MINIFY_ASSETS_DIR') && (string) MINIFY_ASSETS_DIR !== '';
    $dirDefault = str_replace('\\', '/', AssetInsight::assetsDir());

    $toTwig += [
        'PINNED'         => $pinned,
        'LEGACY_DEBUG'   => $legacyDebug,
        'QUEUE_DEBUG'    => $switch('ASSET_QUEUE_DEBUG', 'advanced'),
        'DIR_VALUE'      => $dirCustom ? str_replace('\\', '/', (string) MINIFY_ASSETS_DIR) : $dirDefault,
        'DIR_DEFAULT'    => $dirDefault,
        'DIR_CUSTOM'     => $dirCustom,
        'DIR_LOCKED'     => $isPinned('MINIFY_ASSETS_DIR'),
        'SWITCHES_MINIFY' => [
            $switch('MINIFY_CSS', 'minify'),
            $switch('MINIFY_JS', 'minify'),
            $switch('MINIFY_USE_SUFFIX', 'minify'),
            $switch('ASSETS_MINIFY_DEBUG', 'minify'),
        ],
        'SWITCHES_BUST' => [$bustFe, $bustBe],
    ];

} elseif ($tab === 'docs') {

    $mdOk   = class_exists('MdReaderHelper') && method_exists('MdReaderHelper', 'embedAssets');
    $docKey = array_key_exists($_GET['doc'] ?? '', $DOC_PAGES) ? $_GET['doc'] : array_key_first($DOC_PAGES);
    $doc    = null;

    if ($mdOk) {
        $doc = MdReaderHelper::renderForEmbed($DOC_PAGES[$docKey]['file'], null, 'mdr-toc');
        if ($doc !== null) {
            // MarkdownWbce ships every stylesheet/script the embed needs —
            // markdown.css + markdown-embed.css + conditionally file-tree /
            // highlight.js. No per-module CSS here.
            $embed = MdReaderHelper::embedAssets($doc);
            foreach ($embed['css'] as $u) { I::insertCssFile($u); }
            foreach ($embed['js']  as $u) { I::insertJsFile($u, 'body_late'); }
        }
    }

    $subtabs = [];
    foreach ($DOC_PAGES as $k => $p) {
        $subtabs[] = ['key' => $k, 'label' => $TXT[$p['label_key']] ?? $k, 'sel' => $k === $docKey];
    }

    // Show the file actually rendered (may be a *_DE.md language variant), not
    // the logical base path we asked for. NB: do not name a local `$wb` here —
    // this file runs at the admin runner's file scope, where `$wb` is the CMS
    // engine global that getTwig() reads.
    $docFile = ltrim($DOC_PAGES[$docKey]['file'], '/');
    if ($doc !== null && !empty($doc['abs'])) {
        $docAbs  = str_replace('\\', '/', (string) $doc['abs']);
        $wbRoot  = str_replace('\\', '/', rtrim(WB_PATH, '/\\')) . '/';
        if (str_starts_with($docAbs, $wbRoot)) {
            $docFile = substr($docAbs, strlen($wbRoot));
        }
    }

    $toTwig += [
        'MD_OK'       => $mdOk,
        'DOC_SUBTABS' => $subtabs,
        'DOC_KEY'     => $docKey,
        'DOC_FILE'    => $docFile,
        'DOC'         => $doc,   // {abs, html, toc, title, langs, needsCode, needsFileTree} | null
    ];

} else { // 'cache'

    $assets = AssetInsight::assetCache();
    $fonts  = AssetInsight::fontCache();
    $paths  = AssetInsight::paths();

    // Health checks — booleans from AssetInsight, wording from languages.php.
    $checks  = [
        ['ok' => $paths['assets_writable'], 'title' => $TXT['CHK_ASSETS_DIR'],
         'text' => sprintf($TXT['CHK_ASSETS_DIR_T'], $paths['assets_rel'])],
        ['ok' => $paths['fonts_writable'], 'title' => $TXT['CHK_FONTS_DIR'],
         'text' => sprintf($TXT['CHK_FONTS_DIR_T'], $paths['fonts_rel'])],
        ['ok' => extension_loaded('curl'), 'title' => $TXT['CHK_CURL'],
         'text' => $TXT['CHK_CURL_T']],
    ];
    // matthiasmullie/minify ships with WBCE core — only flag it when it has
    // gone missing (a broken install), never as an always-green row.
    if (!AssetInsight::minifyLibraryPresent()) {
        $checks[] = ['ok' => false, 'title' => $TXT['CHK_MINLIB'], 'text' => $TXT['CHK_MINLIB_NO']];
    }
    if ($pinned) {
        $checks[] = ['ok' => false, 'title' => sprintf($TXT['CHK_PINNED'], count($pinned)),
                     'text' => sprintf($TXT['CHK_PINNED_T'], implode(', ', $pinned))];
    }

    $toTwig += [
        'ASSETS'  => $assets,
        'FONTS'   => $fonts,
        'PATHS'   => $paths,
        'CHECKS'  => $checks,
    ];
}

getTwig(__DIR__ . '/twig/')->load('tool.twig')->display($toTwig);
