<?php
/**
 * tinymce_wbce — include.php
 * WYSIWYG bootstrap — called by WBCE on every backend page load.
 * @author  SC-Peet
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) { return; }

define('TINYMCE_MOD_URL',  WB_URL  . '/modules/tinymce_wbce');
define('TINYMCE_MOD_PATH', WB_PATH . '/modules/tinymce_wbce');

/** Directory-safe template name (used to build filesystem paths). */
function _tinymce_wbce_sanitize_tpl(string $t): string
{
    return preg_replace('/[^a-zA-Z0-9_\-]/', '', $t);
}

/**
 * Effective template of the page currently being edited. The backend edits one
 * page at a time (admin/pages/modify.php?page_id=N), so we read that page's
 * template; an empty template inherits DEFAULT_TEMPLATE, and contexts without a
 * page_id (module settings etc.) fall back to DEFAULT_TEMPLATE too. This is what
 * makes a template's editor.css apply ONLY on pages that actually use it.
 */
function _tinymce_wbce_current_template(): string
{
    global $database;
    $tpl = '';
    $pid = (isset($_REQUEST['page_id']) && is_numeric($_REQUEST['page_id'])) ? (int) $_REQUEST['page_id'] : 0;
    if ($pid > 0 && isset($database)) {
        $row = $database->fetchValue('SELECT `template` FROM `{TP}pages` WHERE `page_id` = ?', [$pid]);
        if (is_string($row) && $row !== '') { $tpl = $row; }
    }
    if ($tpl === '' && defined('DEFAULT_TEMPLATE')) { $tpl = DEFAULT_TEMPLATE; }
    return _tinymce_wbce_sanitize_tpl($tpl);
}

/**
 * Ordered editor.css candidate chain for a template, each entry a filesystem
 * path (existence not checked here). Shared by the resolver below and the tool's
 * info scanner so both agree on the search locations.
 */
function _tinymce_wbce_editor_css_chain(string $tpl): array
{
    $chain = [];
    if ($tpl !== '') {
        $base = WB_PATH . '/templates/' . $tpl;
        $chain['template'] = [
            $base . '/editor.css',
            $base . '/css/editor.css',
            $base . '/assets/editor.css',
        ];
    } else {
        $chain['template'] = [];
    }
    $chain['wb_config'] = [WB_PATH . '/templates/wb_config/editor.css'];
    $chain['default']   = [TINYMCE_MOD_PATH . '/css/editor_default.css'];
    return $chain;
}

/** First existing filesystem path in a flat list, or '' if none exist. */
function _tinymce_wbce_first_css_path(array $paths): string
{
    foreach ($paths as $path) {
        if (file_exists($path)) { return $path; }
    }
    return '';
}

/** Map an editor.css filesystem path to its browser URL (+ mtime cache-bust). */
function _tinymce_wbce_css_path_to_url(string $path): string
{
    if ($path === '') { return ''; }
    $url = (strpos($path, WB_PATH) === 0)
        ? str_replace(WB_PATH, WB_URL, $path)
        : TINYMCE_MOD_URL . '/css/editor_default.css';
    return $url . '?v=' . (@filemtime($path) ?: 1);
}

/**
 * Resolve the editor.css FILESYSTEM PATH for the given template and global source
 * setting. `none` returns '' (no editor.css); every other mode falls back down
 * the chain so the editor is never left completely unstyled. The path form is
 * what the annotated-class parser (#1) needs; the URL is derived from it.
 */
function _tinymce_wbce_resolve_editor_css_path(string $tpl, string $source = 'auto'): string
{
    $chain = _tinymce_wbce_editor_css_chain($tpl);
    $tplP = _tinymce_wbce_first_css_path($chain['template']);
    $wbcP = _tinymce_wbce_first_css_path($chain['wb_config']);
    $defP = _tinymce_wbce_first_css_path($chain['default']);

    switch ($source) {
        case 'none':
            return '';
        case 'default':
            return $defP;
        case 'wb_config':
            return $wbcP !== '' ? $wbcP : $defP;
        case 'template':
            // Only this page-template's editor.css; if it has none, fall back.
            return $tplP !== '' ? $tplP : ($wbcP !== '' ? $wbcP : $defP);
        case 'auto':
        default:
            if ($tplP !== '') { return $tplP; }
            if ($wbcP !== '') { return $wbcP; }
            return $defP;
    }
}

/** Resolve the editor.css URL (thin wrapper over the path resolver). */
function _tinymce_wbce_find_editor_css(string $tpl, string $source = 'auto'): string
{
    return _tinymce_wbce_css_path_to_url(_tinymce_wbce_resolve_editor_css_path($tpl, $source));
}

/**
 * Parse an editor.css file for our comment-annotated style whitelist (#1,
 * annotated mode). No canonical standard exists, so this is our own minimal
 * convention: a marker comment immediately followed by the rule it describes.
 *
 *   / * tinymce: label="Button primär"; group="Buttons"; element="a" * /
 *   .btn-primary { … }
 *
 *   label   (required) — menu title
 *   group   (optional) — submenu grouping
 *   element (optional, default 'span') — inline (span/code/strong/em) vs block,
 *           or a target element (a/img/td/…)
 *
 * The applied class is the FIRST .class in the following selector. Returns rows
 * [{title, element, classes, group}] ready for the style_formats builder.
 */
function _tinymce_wbce_parse_annotated_css(string $path): array
{
    if ($path === '' || !is_file($path)) { return []; }
    $css = @file_get_contents($path);
    if ($css === false || $css === '') { return []; }

    if (!preg_match_all('#/\*\s*tinymce:\s*(.*?)\*/\s*([^{}]+?)\{#is', $css, $matches, PREG_SET_ORDER)) {
        return [];
    }

    $out = [];
    foreach ($matches as $hit) {
        $marker   = $hit[1];
        $selector = $hit[2];

        $attrs = [];
        if (preg_match_all('#([a-z_]+)\s*=\s*"([^"]*)"#i', $marker, $am, PREG_SET_ORDER)) {
            foreach ($am as $a) { $attrs[strtolower($a[1])] = $a[2]; }
        }
        if (preg_match_all("#([a-z_]+)\s*=\s*'([^']*)'#i", $marker, $am2, PREG_SET_ORDER)) {
            foreach ($am2 as $a) { $attrs[strtolower($a[1])] = $a[2]; }
        }

        $label = trim($attrs['label'] ?? '');
        if ($label === '') { continue; }
        if (!preg_match('#\.([A-Za-z0-9_\-]+)#', $selector, $cm)) { continue; }

        $out[] = [
            'title'   => $label,
            'element' => trim($attrs['element'] ?? 'span'),
            'classes' => $cm[1],
            'group'   => trim($attrs['group'] ?? ''),
        ];
    }
    return $out;
}

// The real TinyMCE renderer, owned by this module under a STABLE, module-specific
// name. This matters for the modern WysiwygEditor dispatcher: it calls
// tinymce_wbce_wysiwyg_render() (below), which calls this — so TinyMCE renders
// correctly even when ANOTHER editor module holds the global show_wysiwyg_editor()
// name (e.g. mixing editors on one page). Guarded on its own name, not the legacy one.
if (!function_exists('tinymce_wbce_render_editor')) {

    function tinymce_wbce_render_editor(
        string $name,
        string $id,
        ?string $content = '',
        string $width    = '100%',
        string $height   = '',
        string $toolbar  = ''  // preset id — empty = the configured default preset
    ): void {

        static $tinymce_loaded = false;

        require_once TINYMCE_MOD_PATH . '/presets.php';

        // Null-safe content (fixes Bakery/NWI passing null)
        $content = $content ?? '';

        // Resolve the requested preset (or the default) to a full editor profile.
        // A localStorage "Only me" override may still win client-side, but only
        // when no explicit preset was requested (i.e. the default editor).
        $isDefaultEditor = ($toolbar === '');
        $preset = tinymce_wbce_resolve_preset($toolbar);

        $heightVal = (int) ($height ?: $preset['height']);

        // Build the toolbar rows from the preset (runtime code→wbcodemirror swap).
        $rows = array_values(array_filter([
            tinymce_wbce_runtime_toolbar(trim($preset['toolbar_row1'] ?? '')),
            tinymce_wbce_runtime_toolbar(trim($preset['toolbar_row2'] ?? '')),
            tinymce_wbce_runtime_toolbar(trim($preset['toolbar_row3'] ?? '')),
        ], fn($r) => $r !== ''));

        // Auto-switch to the small toolbar for short editor fields.
        if (($preset['auto_minimal'] ?? '0') === '1') {
            $threshold = (int) ($preset['auto_minimal_height'] ?? 200);
            if ($heightVal > 0 && $heightVal < $threshold) {
                $minRow = tinymce_wbce_runtime_toolbar(trim($preset['toolbar_minimal'] ?? ''));
                $rows   = $minRow !== '' ? [$minRow] : ['undo redo | bold italic underline | bullist numlist | link | ' . tinymce_wbce_runtime_toolbar('code')];
            }
        }
        if (empty($rows)) {
            $rows = [
                'undo redo | blocks | bold italic underline | removeformat',
                'forecolor backcolor | alignleft aligncenter alignright | bullist numlist',
                'link image table | wbdroplets | ' . tinymce_wbce_runtime_toolbar('code') . ' fullscreen',
            ];
        }

        // editor.css is resolved against the CURRENT page's template (so a
        // template's editor.css only styles its own pages) and the PRESET's own
        // source setting (per-preset, not global — different fields on the same
        // page can want different in-editor CSS). Empty string = "none" → no
        // editor.css joins content_css.
        $editorCssTpl    = _tinymce_wbce_current_template();
        $editorCssSource = $preset['editor_css_source'] ?? 'auto';
        $editorCssPath   = _tinymce_wbce_resolve_editor_css_path($editorCssTpl, $editorCssSource);
        $editorCss = _tinymce_wbce_css_path_to_url($editorCssPath);
        $modUrl    = TINYMCE_MOD_URL;
        $baseUrl   = $modUrl . '/tinymce';

        // Cache-bust the merged link plugin by its file mtime. TinyMCE loads
        // external_plugins WITHOUT a version query, so browsers cache plugin.min.js
        // hard across edits — this makes a changed file always re-fetch, while an
        // unchanged file still caches normally.
        $linkVer   = @filemtime(TINYMCE_MOD_PATH . '/plugins/link/plugin.min.js') ?: 1;
        $faVer     = @filemtime(TINYMCE_MOD_PATH . '/plugins/fa_picker/plugin.min.js') ?: 1;
        $ccVer     = @filemtime(TINYMCE_MOD_PATH . '/plugins/wbce_casechange/plugin.js') ?: 1;
        $shyVer    = @filemtime(TINYMCE_MOD_PATH . '/plugins/wbce_shy/plugin.js') ?: 1;
        $csVer     = @filemtime(TINYMCE_MOD_PATH . '/plugins/wbce_codesample/plugin.js') ?: 1;
        $histVer   = @filemtime(TINYMCE_MOD_PATH . '/plugins/wbce_history/plugin.js') ?: 1;
        // Same cache-bust for the wbeSelect assets the link plugin lazy-loads at
        // runtime — runtime-injected <script>/<link> often bypass the browser's
        // hard-reload cache clearing, so without this an edited wbeSelect keeps
        // serving stale from disk cache.
        $wbeselVer = max(
            @filemtime(WB_PATH . '/include/wbeSelect/wbeSelect.js') ?: 1,
            @filemtime(WB_PATH . '/include/wbeSelect/wbeSelect.css') ?: 1
        );

        if (!$tinymce_loaded) {
            $tinymce_loaded = true;

            // Translation map for the editor plugins (wblink, wbdroplets, …)
            require_once TINYMCE_MOD_PATH . '/plugin_i18n.php';
            $i18nJs = tinymce_wbce_plugin_i18n_js();

            $editorCssJson  = json_encode($editorCss, JSON_UNESCAPED_SLASHES);
            $ajaxDroplets   = $modUrl . '/ajax_droplets.php';
            $ajaxPages      = $modUrl . '/ajax_pages.php';
            $ajaxLinkItems  = $modUrl . '/ajax_link_items.php';
            // elfinder_postmessage.php is the shared, generic postMessage-based
            // bridge (also used by include/PlainMDE and modules/tiptap_editor):
            // no window[cbName] global-callback dance, and it supports
            // ?select=<absolute file URL> to jump straight to and highlight an
            // already-placed image's real folder instead of always opening
            // wherever elFinder last happened to be. modules/tinymce_wbce's own
            // elfinder_tinymce.php (window-global-callback protocol) stays as
            // it is for modules/ves/fee_canvas.js, which still depends on it.
            $elfinderUrl    = WB_URL . '/modules/elfinder/ef/elfinder_postmessage.php';
            $mediaBaseUrl   = WB_URL . MEDIA_DIRECTORY;
            $codemirrorUrl  = $modUrl . '/codemirror_tinymce.php';
            $codesampleUrl  = $modUrl . '/codesample_tinymce.php';
            $historyUrl     = $modUrl . '/history_ajax.php';
            $uploadImgUrl   = $modUrl . '/upload_image.php';
            $pluginsUrl     = $modUrl . '/plugins';
            $includeUrl     = defined('INCLUDE_URL') ? INCLUDE_URL : (WB_URL . '/include');

            // Cache-busted label fix for em/rem font-size lists (see the file header)
            $sizeFixVer = @filemtime(TINYMCE_MOD_PATH . '/js/fontsize-label-fix.js') ?: 1;
            $imgBadgeVer = @filemtime(TINYMCE_MOD_PATH . '/js/image-size-badge.js') ?: 1;
            $imgAltVer   = @filemtime(TINYMCE_MOD_PATH . '/js/image-alt-badge.js') ?: 1;
            // Editor-only content decorations (soft-hyphen dots etc.)
            $extrasVer  = @filemtime(TINYMCE_MOD_PATH . '/css/content-extras.css') ?: 1;
            $extrasUrl  = $modUrl . '/css/content-extras.css?v=' . $extrasVer;

            echo <<<HTML
<link rel="stylesheet" href="{$modUrl}/css/tinymce_backend.css">
<script src="{$modUrl}/js/fontsize-label-fix.js?v={$sizeFixVer}"></script>
<script src="{$modUrl}/js/image-size-badge.js?v={$imgBadgeVer}"></script>
<script src="{$modUrl}/js/image-alt-badge.js?v={$imgAltVer}"></script>
<script>
var TINYMCE_EDITOR_CSS  = {$editorCssJson};
var TINYMCE_PLUGINS_URL  = '{$pluginsUrl}';
var TINYMCE_AJAX_DROP    = '{$ajaxDroplets}';
var TINYMCE_AJAX_PAGES   = '{$ajaxPages}';
var TINYMCE_AJAX_LINK_ITEMS = '{$ajaxLinkItems}';
var TINYMCE_INCLUDE_URL  = '{$includeUrl}';
var TINYMCE_CONTENT_EXTRAS = '{$extrasUrl}';
var TINYMCE_ELFINDER     = '{$elfinderUrl}';
var TINYMCE_MEDIA_URL    = '{$mediaBaseUrl}';
var TINYMCE_CODEMIRROR_URL = '{$codemirrorUrl}';
var TINYMCE_CODESAMPLE_URL = '{$codesampleUrl}';
var TINYMCE_HISTORY_URL = '{$historyUrl}';
var TINYMCE_UPLOAD_IMG_URL = '{$uploadImgUrl}';
{$i18nJs}

// Personal "Only me" override — applies to the DEFAULT editor only.
// Returns an object with toolbar rows + settings, or null when unset.
function tinymceWbceLocalOverride() {
    try {
        var raw = localStorage.getItem('tinymce_wbce_user_cfg');
        if (!raw) return null;
        var c = JSON.parse(raw);
        var rows = [c.toolbar_row1, c.toolbar_row2, c.toolbar_row3]
            .filter(function(r){ return r && r.trim(); });
        return { rows: rows, cfg: c };
    } catch(e) { return null; }
}

var tinymceWbcePending = [];

function tinymceWbceInit() {
    tinymceWbcePending.forEach(function(cfg) { tinymce.init(cfg); });
    tinymceWbcePending = [];
}

// Editor-agnostic flush registry (see framework/WysiwygEditor.php): syncs every
// TinyMCE editor into its <textarea> so a form serialize / AJAX save reads the
// current content. triggerSave() flushes ALL instances, so one entry suffices.
(window.WBCE_WYSIWYG_FLUSH = window.WBCE_WYSIWYG_FLUSH || []).push(function () {
    if (window.tinymce && tinymce.triggerSave) { tinymce.triggerSave(); }
});
</script>
<script src="{$modUrl}/tinymce/tinymce.min.js" onload="tinymceWbceInit()"></script>
HTML;
        }

        $lang        = (defined('LANGUAGE') && strtoupper(LANGUAGE) === 'DE') ? 'de' : 'en';

        // The bundled de.js ships EMPTY translations for a few labels
        // ("Format" menu title, inline "Code" format, "Emojis…") — they render
        // label-less. An empty value defeats TinyMCE's key fallback, so re-add
        // them (key = value) after the lang pack has loaded (on init). DE only.
        $emojiFixJs = ($lang === 'de')
            ? "\n            editor.on('init', function () { if (window.tinymce && tinymce.addI18n) tinymce.addI18n('de', {'Format': 'Format', 'Code': 'Code', 'Emojis...': 'Emojis...', 'Emojis': 'Emojis'}); });"
            : '';

        // Note: re-pointing the 'code' menu item (View > Source code) at the CM5
        // source view now lives in plugins/wbcodemirror/plugin.js (direct, no
        // command indirection). Format > Code stays the inline-code format.

        $menubarJs   = ($preset['menubar']   === '1') ? 'true' : 'false';
        $statusbarJs = ($preset['statusbar'] === '1') ? 'true' : 'false';
        $cfgSkin     = $preset['skin'];
        $cfgContent  = $preset['content_theme'];
        $cfgContentCss = strpos($cfgSkin, 'dark') !== false ? 'dark' : $cfgContent;
        $toolbarJson = json_encode($rows, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $allowLocal  = $isDefaultEditor ? 'true' : 'false';

        // Version history is per preset now: tell the plugin which preset this
        // editor uses (server resolves the settings from it) and whether it's on.
        $historyPresetId  = $toolbar !== '' ? $toolbar : (string)(tinymce_wbce_load_cfg()['default_preset'] ?? '');
        $historyEnabledJs = (($preset['history_enabled'] ?? '1') === '1') ? 'true' : 'false';

        // Paste clipboard images → upload to MEDIA_DIRECTORY (per preset). The
        // handler POSTs the blob + preset id to upload_image.php, which resolves
        // the target folder (incl. the user's home folder) server-side.
        $pasteJs = '';
        if (($preset['paste_images'] ?? '0') === '1') {
            $pasteJs = "\n    cfg.paste_data_images = true;"
                . "\n    cfg.automatic_uploads = true;"
                . "\n    cfg.images_upload_handler = function (blobInfo, progress) {"
                . "\n        return new Promise(function (resolve, reject) {"
                . "\n            var fd = new FormData();"
                . "\n            fd.append('file', blobInfo.blob(), blobInfo.filename());"
                . "\n            fd.append('preset', '{$historyPresetId}');"
                . "\n            var xhr = new XMLHttpRequest();"
                . "\n            xhr.open('POST', TINYMCE_UPLOAD_IMG_URL);"
                . "\n            xhr.withCredentials = true;"
                . "\n            xhr.upload.onprogress = function (e) { if (e.lengthComputable && progress) { progress(e.loaded / e.total * 100); } };"
                . "\n            xhr.onload = function () {"
                . "\n                var j; try { j = JSON.parse(xhr.responseText); } catch (e) { reject('Upload: invalid response'); return; }"
                . "\n                if (xhr.status !== 200 || !j || !j.location) { reject((j && j.error) || ('Upload failed (' + xhr.status + ')')); return; }"
                . "\n                resolve(j.location);"
                . "\n            };"
                . "\n            xhr.onerror = function () { reject('Upload: network error'); };"
                . "\n            xhr.send(fd);"
                . "\n        });"
                . "\n    };";
        }

        // Double-click an image → open elFinder to pick a replacement (per
        // preset). Reuses the same popup contract as file_picker_callback.
        // stopImmediatePropagation keeps the bundled image dialog from also
        // opening (this setup handler is registered before the image plugin).
        $imgDblJs = '';
        if (($preset['img_dblclick'] ?? '0') === '1') {
            $imgDblJs = "\n            editor.on('dblclick', function (ev) {"
                . "\n                var img = ev.target;"
                . "\n                if (!img || img.nodeName !== 'IMG') { return; }"
                . "\n                ev.preventDefault(); ev.stopImmediatePropagation();"
                . "\n                var apply = function (url) { if (url) { editor.dom.setAttrib(img, 'src', url); editor.dom.setAttrib(img, 'data-mce-src', url); editor.nodeChanged(); } };"
                . "\n                var msgH = function (e) { if (e.origin === window.location.origin && e.data && e.data.wbceMediaPick === true) { apply(e.data.url); window.removeEventListener('message', msgH); } };"
                . "\n                window.addEventListener('message', msgH);"
                . "\n                var w = 900, h = 600, l = Math.round(screen.width/2 - w/2), tp = Math.round(screen.height/2 - h/2);"
                . "\n                var popupUrl = TINYMCE_ELFINDER + (img.src ? '?select=' + encodeURIComponent(img.src) : '');"
                . "\n                window.open(popupUrl, 'tinymce_elfinder', 'width='+w+',height='+h+',top='+tp+',left='+l+',resizable=yes,scrollbars=yes');"
                . "\n            });";
        }

        // Selected-image size badge (% of natural size; click resets to 100%).
        $imgBadgeJs = (($preset['img_size_badge'] ?? '0') === '1')
            ? "\n            if (window.tinymceWbceImageSizeBadge) { tinymceWbceImageSizeBadge(editor); }"
            : '';

        // #5 Alt-text reminder: mark images with a missing/empty alt (no enforce).
        // Opt-out — a missing key means on.
        $imgAltJs = (($preset['alt_reminder'] ?? '1') !== '0')
            ? "\n            if (window.tinymceWbceImageAltBadge) { tinymceWbceImageAltBadge(editor); }"
            : '';

        // Creatable class/rel option lists for the link dialog (per preset).
        $linkOpts        = tinymce_wbce_link_options($historyPresetId);
        $linkClassesJson = json_encode($linkOpts['classes'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $linkRelsJson    = json_encode($linkOpts['rels'],    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $linkOptsSaveUrl = $modUrl . '/link_options_save.php';

        // ── Typography from the preset (empty = TinyMCE default, option omitted) ──
        $typographyJs = '';
        if (trim($preset['font_families'] ?? '') !== '') {
            $typographyJs .= "\n    cfg.font_family_formats = " . json_encode($preset['font_families'], JSON_UNESCAPED_UNICODE) . ';';
        }
        if (trim($preset['font_sizes'] ?? '') !== '') {
            $typographyJs .= "\n    cfg.font_size_formats = " . json_encode($preset['font_sizes']) . ';';
        }
        if (in_array($preset['font_size_unit'] ?? '', ['px','pt','em','rem'], true)) {
            $typographyJs .= "\n    cfg.font_size_input_default_unit = " . json_encode($preset['font_size_unit']) . ';';
        }
        if (trim($preset['block_formats'] ?? '') !== '') {
            $typographyJs .= "\n    cfg.block_formats = " . json_encode($preset['block_formats'], JSON_UNESCAPED_UNICODE) . ';';
        }
        // Styles dropdown = block formats + manual custom styles + (optional)
        // classes pulled from the template's editor.css (#1, annotated mode). We
        // emit ONE merged style_formats whenever there is anything to show.
        $manualRows = [];
        if (trim($preset['style_formats'] ?? '') !== '') {
            $decoded = json_decode($preset['style_formats'], true);
            if (is_array($decoded)) { $manualRows = $decoded; }
        }
        $ecMode        = $preset['editorcss_classes'] ?? 'off';
        $annotatedRows = ($ecMode === 'annotated')
            ? _tinymce_wbce_parse_annotated_css($editorCssPath)
            : [];

        if ($manualRows || $annotatedRows) {
            $inlineElements = ['span', 'code', 'strong', 'em'];
            $mapRow = function (string $title, string $el, string $classes) use ($inlineElements): array {
                $el    = $el !== '' ? $el : 'span';
                $entry = ['title' => $title];
                $entry[in_array($el, $inlineElements, true) ? 'inline' : 'block'] = $el;
                if ($classes !== '') { $entry['classes'] = $classes; }
                return $entry;
            };

            // The "Styles" dropdown is a SUPERSET of the Paragraph dropdown:
            // selected block formats first, then custom styles — so replacing the
            // 'blocks' button with 'styles' gives ONE dropdown.
            $mceStyles = [];
            $blocksStr = trim($preset['block_formats'] ?? '');
            if ($blocksStr === '') {
                $blocksStr = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre';
            }
            foreach (explode(';', $blocksStr) as $e) {
                $pos = strpos($e, '=');
                if ($pos > 0) {
                    $mceStyles[] = ['title' => trim(substr($e, 0, $pos)), 'block' => trim(substr($e, $pos + 1))];
                }
            }
            // Manual custom styles (configured in the tool)
            foreach ($manualRows as $row) {
                if (!is_array($row) || trim((string)($row['title'] ?? '')) === '') continue;
                $mceStyles[] = $mapRow((string)$row['title'], (string)($row['element'] ?? 'p'), trim((string)($row['classes'] ?? '')));
            }
            // Annotated editor.css classes — flat entries, grouped ones as submenus
            $ecGroups = [];
            foreach ($annotatedRows as $row) {
                $entry = $mapRow($row['title'], $row['element'], $row['classes']);
                $grp   = trim((string)($row['group'] ?? ''));
                if ($grp !== '') { $ecGroups[$grp][] = $entry; }
                else            { $mceStyles[] = $entry; }
            }
            foreach ($ecGroups as $grpTitle => $items) {
                $mceStyles[] = ['title' => $grpTitle, 'items' => $items];
            }

            $typographyJs .= "\n    cfg.style_formats = " . json_encode($mceStyles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ';';
        }

        // #4 word/character count in the statusbar (bundled wordcount plugin).
        if (($preset['wordcount'] ?? '0') === '1') {
            $typographyJs .= "\n    if (cfg.plugins.indexOf('wordcount') < 0) cfg.plugins.push('wordcount');";
        }

        // #1 auto mode: TinyMCE's importcss plugin pulls classes straight from the
        // loaded editor.css — filtered to that ONE file so Font Awesome / content
        // skin classes stay out; an optional per-preset regex narrows it further.
        if ($ecMode === 'auto' && $editorCss !== '') {
            $ecFile    = basename(preg_replace('/\?.*$/', '', $editorCss)); // strip ?v=
            $typographyJs .= "\n    if (cfg.plugins.indexOf('importcss') < 0) cfg.plugins.push('importcss');";
            $typographyJs .= "\n    cfg.importcss_append = true;";
            $typographyJs .= "\n    cfg.importcss_file_filter = " . json_encode($ecFile, JSON_UNESCAPED_SLASHES) . ';';
            $ecFilter = trim((string)($preset['editorcss_selector_filter'] ?? ''));
            if ($ecFilter !== '') {
                $typographyJs .= "\n    cfg.importcss_selector_filter = " . json_encode($ecFilter, JSON_UNESCAPED_SLASHES) . ';';
            }
        }

        // ── Color swatches (forecolor / backcolor) ──
        // 'shared' = one color_map for both buttons; 'split' = separate
        // foreground/background maps. '' = TinyMCE's default palette.
        $colorMode = $preset['color_mode'] ?? '';
        $colorSet  = false;
        if ($colorMode === 'shared') {
            $map = tinymce_wbce_color_map($preset['colors_shared'] ?? '');
            if ($map) {
                $typographyJs .= "\n    cfg.color_map = " . json_encode($map, JSON_UNESCAPED_UNICODE) . ';';
                $colorSet = true;
            }
        } elseif ($colorMode === 'split') {
            $fore = tinymce_wbce_color_map($preset['colors_fore'] ?? '');
            $back = tinymce_wbce_color_map($preset['colors_back'] ?? '');
            if ($fore) $typographyJs .= "\n    cfg.color_map_foreground = " . json_encode($fore, JSON_UNESCAPED_UNICODE) . ';';
            if ($back) $typographyJs .= "\n    cfg.color_map_background = " . json_encode($back, JSON_UNESCAPED_UNICODE) . ';';
            $colorSet = $fore || $back;
        }
        if ($colorSet) {
            $cols = (int)($preset['color_cols'] ?? 0);
            if ($cols >= 1) $typographyJs .= "\n    cfg.color_cols = " . min(12, $cols) . ';';
            if (($preset['custom_colors'] ?? '1') === '0') $typographyJs .= "\n    cfg.custom_colors = false;";
        }

        // Toolbar visibility / compact mode. Sliding needs ONE toolbar string —
        // TinyMCE ignores toolbar_mode when rows come as an array. Applied after
        // the cfg object, so it also covers "Only me" override rows.
        if (($preset['show_toolbar'] ?? '1') === '0') {
            $typographyJs .= "\n    cfg.toolbar = false;";
        } elseif (($preset['toolbar_sliding'] ?? '0') === '1') {
            $typographyJs .= "\n    if (Array.isArray(cfg.toolbar)) cfg.toolbar = cfg.toolbar.join(' | ');"
                          .  "\n    cfg.toolbar_mode = 'sliding';";
        }

        // fa_picker swatches — dedicated icon palette, falling back to the
        // font-color palette (split) or the shared palette (shared).
        $iconListJson = trim($preset['colors_icons'] ?? '');
        if ($iconListJson === '') {
            $iconListJson = $colorMode === 'split'
                ? trim($preset['colors_fore'] ?? '')
                : ($colorMode === 'shared' ? trim($preset['colors_shared'] ?? '') : '');
        }
        if ($iconListJson !== '') {
            $swatches = [];
            foreach ((array)json_decode($iconListJson, true) as $row) {
                if (is_array($row) && ($row['color'] ?? '') !== '') $swatches[] = (string)$row['color'];
            }
            if ($swatches) {
                $typographyJs .= "\n    cfg.fa_picker_swatches = " . json_encode($swatches) . ';';
            }
        }

        echo <<<HTML
<textarea
    name="{$name}"
    id="{$id}"
    class="tinymce_wbce_editor"
    style="width:{$width}"
>{$content}</textarea>
<script>
(function(id, baseToolbar, baseHeight, allowLocal) {
    var toolbar   = baseToolbar,   height = baseHeight;
    var menubar   = {$menubarJs},  statusbar = {$statusbarJs};
    var skin      = '{$cfgSkin}',  contentTheme = '{$cfgContentCss}';

    // Personal "Only me" override — default editor only
    if (allowLocal) {
        var ov = tinymceWbceLocalOverride();
        if (ov) {
            if (ov.rows && ov.rows.length) toolbar = ov.rows;
            var c = ov.cfg || {};
            if (c.height)                 height    = parseInt(c.height, 10) || height;
            if (c.menubar   !== undefined) menubar   = (c.menubar   === '1');
            if (c.statusbar !== undefined) statusbar = (c.statusbar === '1');
            if (c.skin) { skin = c.skin; contentTheme = (skin.indexOf('dark') !== -1) ? 'dark' : contentTheme; }
        }
    }

    var cfg = {
        target:          document.getElementById(id),
        license_key:     'gpl',
        base_url:        '{$baseUrl}',
        suffix:          '.min',
        skin:            skin,
        // Order matters: the named content theme (skin) loads FIRST, then the
        // template's editor.css — so editor.css ALWAYS wins over the skin's body
        // defaults (incl. dark). Font Awesome + editor-only extras are orthogonal
        // and trail. Empty editor.css ('none' setting) is filtered out.
        content_css:     [contentTheme, TINYMCE_EDITOR_CSS, TINYMCE_INCLUDE_URL + '/font-awesome/css/font-awesome.min.css', TINYMCE_CONTENT_EXTRAS].filter(Boolean),
        language:        '{$lang}',
        height:          height,
        menubar:         menubar,
        statusbar:       statusbar,
        branding:        false,
        promotion:       false,
        entity_encoding: 'raw',
        convert_urls:    false,
        relative_urls:   false,
        // 'link' is NOT in this bundled list — our own merged plugin below fully
        // replaces TinyMCE's stock link plugin (generic link + internal WBCE page
        // picker in one dialog; the old separate 'wblink' button is merged into it).
        // visualchars runs permanently (default state below) so soft hyphens
        // show as pink dots — styled in css/content-extras.css, editor-only.
        plugins:         ['lists','image','table','code','fullscreen','charmap','media','emoticons','visualchars'],
        visualchars_default_state: true,
        external_plugins: {
            'link':         TINYMCE_PLUGINS_URL + '/link/plugin.min.js?v={$linkVer}',
            'fa_picker':    TINYMCE_PLUGINS_URL + '/fa_picker/plugin.min.js?v={$faVer}',
            'wbce_casechange': TINYMCE_PLUGINS_URL + '/wbce_casechange/plugin.js?v={$ccVer}',
            'wbce_shy':     TINYMCE_PLUGINS_URL + '/wbce_shy/plugin.js?v={$shyVer}',
            'wbce_codesample': TINYMCE_PLUGINS_URL + '/wbce_codesample/plugin.js?v={$csVer}',
            'wbce_history': TINYMCE_PLUGINS_URL + '/wbce_history/plugin.js?v={$histVer}',
            'wbdroplets':   TINYMCE_PLUGINS_URL + '/wbdroplets/plugin.js',
            'wbcodemirror': TINYMCE_PLUGINS_URL + '/wbcodemirror/plugin.js'
        },
        wbdroplets_ajax_url: TINYMCE_AJAX_DROP,
        link_ajax_url:       TINYMCE_AJAX_PAGES,
        link_items_url:      TINYMCE_AJAX_LINK_ITEMS,
        link_include_url:    TINYMCE_INCLUDE_URL,
        link_asset_ver:      '{$wbeselVer}',
        link_classes:        {$linkClassesJson},
        link_rels:           {$linkRelsJson},
        link_preset:         '{$historyPresetId}',
        link_opts_save_url:  '{$linkOptsSaveUrl}',
        fa_picker_css_url:   TINYMCE_INCLUDE_URL + '/font-awesome/css/font-awesome.min.css',
        wbce_codesample_url: TINYMCE_CODESAMPLE_URL,
        wbce_history_url:     TINYMCE_HISTORY_URL,
        wbce_history_preset:  '{$historyPresetId}',
        wbce_history_enabled: {$historyEnabledJs},
        // Keep <i class style> intact on cleanup (fa_picker inserts these; the
        // zero-width space inside prevents empty-element stripping)
        extended_valid_elements: 'i[class|style|aria-hidden]',
        toolbar:         toolbar,
        toolbar_mode:    'wrap',
        resize:          true,
        file_picker_types:    'image media file',
        file_picker_callback: function (callback, value, meta) {
            var msgHandler = function (e) {
                if (e.origin === window.location.origin && e.data && e.data.wbceMediaPick === true) {
                    callback(e.data.url, { title: e.data.title || '' });
                    window.removeEventListener('message', msgHandler);
                }
            };
            window.addEventListener('message', msgHandler);

            var w = 900, h = 600;
            var left = Math.round(screen.width  / 2 - w / 2);
            var top  = Math.round(screen.height / 2 - h / 2);
            // "value" is the dialog's current Source field - the existing
            // image's URL when editing one, empty when inserting new. When
            // present, jump straight to and highlight its real folder
            // instead of wherever elFinder last happened to be.
            var popupUrl = TINYMCE_ELFINDER + (value ? '?select=' + encodeURIComponent(value) : '');
            window.open(
                popupUrl,
                'tinymce_elfinder',
                'width=' + w + ',height=' + h + ',top=' + top + ',left=' + left + ',resizable=yes,scrollbars=yes'
            );
        },
        setup: function(editor) {
            editor.on('change', function() { editor.save(); });
            // Ctrl/Cmd+S while typing fires INSIDE the editor iframe, so it never
            // reaches a host keydown listener. Forward it: sync content, then submit
            // the surrounding form — the host decides AjaxSave vs. normal save. This
            // is what makes Ctrl+S work editor-agnostically (e.g. docs_section, wysiwyg).
            editor.addShortcut('meta+s', 'Save', function() {
                editor.save();
                var el = editor.getElement(), form = el && el.form;
                if (form) { form.requestSubmit ? form.requestSubmit() : form.submit(); }
            });
            if (window.tinymceWbceSizeLabelFix) tinymceWbceSizeLabelFix(editor);{$emojiFixJs}{$imgDblJs}{$imgBadgeJs}{$imgAltJs}
            editor.on('SkinLoaded', function() {
                var skin = editor.options.get('skin') || '';
                var darkSkins = ['oxide-dark', 'tinymce-5-dark'];
                var isDark = darkSkins.indexOf(skin) !== -1;
                var html = document.documentElement;
                html.classList.remove('wbce-tmce-skin-light');
                if (!isDark) { html.classList.add('wbce-tmce-skin-light'); }
            });
        }
    };{$typographyJs}{$pasteJs}
    // Insert menu mirrors the insert-capable buttons actually placed in the
    // toolbar (reads the live `toolbar`, so "Only me" overrides count too).
    // Unregistered names are ignored by TinyMCE, so this is safe.
    (function () {
        if (!menubar) return;
        var MAP = { image:'image', link:'link', wblink:'link', media:'media',
            table:'inserttable', charmap:'charmap', emoticons:'emoticons',
            fa_picker:'fa_picker', wbdroplets:'wbdroplets', hr:'hr', wbce_shy:'wbce_shy' };
        var toks = (Array.isArray(toolbar) ? toolbar.join(' ') : String(toolbar || '')).split(/\\s+/);
        var have = {};
        toks.forEach(function (tk) { if (MAP[tk]) have[MAP[tk]] = true; });
        var GROUPS = [ ['image','link','media','inserttable'],
            ['charmap','emoticons','fa_picker','wbdroplets'], ['hr','wbce_shy'] ];
        var parts = [];
        GROUPS.forEach(function (g) {
            var it = g.filter(function (i) { return have[i]; });
            if (it.length) parts.push(it.join(' '));
        });
        var items = parts.join(' | ');
        if (items) {
            cfg.menu = cfg.menu || {};
            cfg.menu.insert = { title: 'Insert', items: items };
            cfg.menubar = 'file edit view insert format tools table help';
        } else {
            cfg.menubar = 'file edit view format tools table help';
        }
    })();
    if (typeof tinymce !== 'undefined') {
        tinymce.init(cfg);
    } else {
        tinymceWbcePending.push(cfg);
    }
})('{$id}', {$toolbarJson}, {$heightVal}, {$allowLocal});
</script>
HTML;
    }
}

/**
 * Legacy WBCE contract: show_wysiwyg_editor() — kept for backward compatibility
 * with the existing ~15 call sites. A thin alias to this module's real renderer,
 * defined ONLY if no other editor module has already claimed the global name.
 */
if (!function_exists('show_wysiwyg_editor')) {
    function show_wysiwyg_editor(
        string $name, string $id, ?string $content = '',
        string $width = '100%', string $height = '', string $toolbar = ''
    ): void {
        tinymce_wbce_render_editor($name, $id, $content, $width, $height, $toolbar);
    }
}

/**
 * Modern dispatcher entry point for THIS editor.
 *
 * The editor-agnostic core class framework/WysiwygEditor.php resolves an editor
 * "chain" (e.g. "tinymce>tiptap") to an installed editor module directory, then
 * calls <dir>_wysiwyg_render() by convention. Every editor module provides one
 * such function; it RETURNS the editor HTML (the dispatcher echoes it, or hands
 * it to FEE / a panel, etc.).
 *
 * @param string $id       DOM id / textarea id of the field
 * @param string $content  Initial HTML content
 * @param array  $opts     Recognized keys:
 *                           name    string  form field name (default: $id)
 *                           width   string  '100%' etc.
 *                           height  string  '350' / '350px' / '' (auto)
 *                           config  string  preset fallback chain, e.g.
 *                                           "inline>small>default" (see below)
 *                         (future: save, mode …)
 * @return string  The editor HTML.
 */
if (!function_exists('tinymce_wbce_wysiwyg_render')) {
    function tinymce_wbce_wysiwyg_render(string $id, string $content, array $opts): string
    {
        $preset = tinymce_wbce_resolve_preset_chain((string) ($opts['config'] ?? ''));
        ob_start();
        tinymce_wbce_render_editor(
            (string) ($opts['name']   ?? $id),
            $id,
            $content,
            (string) ($opts['width']  ?? '100%'),
            (string) ($opts['height'] ?? ''),
            $preset
        );
        return (string) ob_get_clean();
    }
}

/**
 * Resolve a preset fallback chain like "inline>small>default" to a concrete
 * preset id. Returns the FIRST token naming an existing preset; the literal token
 * "default" (and an empty or exhausted chain) resolves to default_preset. This
 * lets callers request presets portably, without knowing which presets a given
 * install actually has.
 */
if (!function_exists('tinymce_wbce_resolve_preset_chain')) {
    function tinymce_wbce_resolve_preset_chain(string $chain): string
    {
        require_once TINYMCE_MOD_PATH . '/presets.php';
        $cfg     = tinymce_wbce_load_cfg();
        $presets = $cfg['presets'] ?? [];
        $default = (string) ($cfg['default_preset'] ?? '');
        foreach (explode('>', $chain) as $tok) {
            $tok = trim($tok);
            if ($tok === '')           { continue; }
            if ($tok === 'default')    { return $default; }
            if (isset($presets[$tok])) { return $tok; }
        }
        return $default; // nothing in the chain existed → the configured default
    }
}
