<?php
/**
 * include/PlainMDE/plugin.php — I::loadPlugin('include/PlainMDE') entry point.
 *
 * Purpose-built markdown editor for WBCE. 
 * Reuses CodeMirror core + addons already loaded by modules/CodeMirror_Config
 * (not bundled here, zero marginal footprint for pages that already load it
 * for other reasons).
 *
 * License: MIT, see LICENSE. Design lineage (EasyMDE/SimpleMDE) and
 * third-party code/assets vendored here (CodeMirror addons, Tabler Icons)
 * are documented and licensed in NOTICE.md.
 */

$cmDir     = WB_URL . '/modules/CodeMirror_Config/codemirror';
$vendorDir = INCLUDE_URL . '/PlainMDE/vendor/codemirror-addons';
$srcDir    = INCLUDE_URL . '/PlainMDE/src';

I::insertCssBundle(
    [
        $cmDir . '/lib/codemirror.css',
        INCLUDE_URL . '/PlainMDE/css/plainmde.css',
        // Theming/override hook only — stays empty by default, exists so a project
        // can override colors/fonts via CSS custom properties without touching
        // plainmde.css itself.
        INCLUDE_URL . '/PlainMDE/css/plainmde-overrides.css'
    ],
    'PlainMDE_css', 'head_late'
);

I::insertJsCode(include __DIR__ . '/plainmde-icons.php', 'body_late');
// ── CodeMirror core + addons already present via CodeMirror_Config ──
I::insertJsBundle([
    $cmDir . '/lib/codemirror.js',
    $cmDir . '/addon/edit/continuelist.js',
    $cmDir . '/addon/mode/overlay.js',
    $cmDir . '/mode/xml/xml.js',

    // ── The only two CodeMirror pieces PlainMDE still vendors: the markdown/
    // gfm modes (for live syntax highlighting) and autorefresh (editor shown
    // inside a tab/modal that starts hidden). Search-cursor and placeholder
    // addons from the previous plugin are dropped — unused. These are
    // PlainMDE's own forked copies (mode names "plainmde-markdown"/
    // "plainmde-gfm", not "markdown"/"gfm") — see plainmde-markdown.js's
    // header comment: it fixes a closing-fence detection bug for code
    // blocks nested under a list item, and forking avoids either colliding
    // with, or being silently overwritten by, another unpatched copy of
    // these same-named CodeMirror modes loaded by some other module into
    // the same shared global CodeMirror instance on the same admin page. ──
    $vendorDir . '/mode/markdown/plainmde-markdown.js',
    $vendorDir . '/mode/gfm/plainmde-gfm.js',
    $vendorDir . '/addon/display/autorefresh.js',

    // ── PlainMDE itself ──
    // Load order matters: plainmde-core.js defines the PlainMDE constructor;
    // toolbar.js and preview.js both attach properties onto it (PlainMDE.buttons,
    // PlainMDE.markdown, ...) and must load after it. decorations.js/sync.js
    // define their own independent PlainMDEDecorations/PlainMDESync objects.
    // treeview.js defines PlainMDETreeView, which core's constructor attaches
    // when present - hence after core, whose constructor only runs later.
    $srcDir . '/plainmde-core.js',
    $srcDir . '/plainmde-toolbar.js',
    $srcDir . '/plainmde-preview.js',
    $srcDir . '/plainmde-decorations.js',
    $srcDir . '/plainmde-sync.js',
    $srcDir . '/plainmde-media.js',
    $srcDir . '/plainmde-treeview.js',
], 'PlainMDE', 'body_late');

// Toolbar's image button opens elFinder instead of a plain URL prompt when
// the module is installed — via modules/elfinder/ef/elfinder_postmessage.php
// (see plainmde-media.js), a generic postMessage-based bridge shared with
// modules/tiptap_editor, not modules/elfinder/ef/elfinder_cke.php: that page
// only knows CKEditor's callback protocol, and CKEditor is no longer
// shipped with this project. No-op fallback to window.prompt() stays
// intact if elfinder isn't installed on this site.
if (is_dir(WB_PATH . '/modules/elfinder')) {
    I::insertJsCode(
        'PlainMDE.mediaUrl = ' . json_encode(WB_URL . '/modules/elfinder/ef/elfinder_postmessage.php') . ';',
        'body_late'
    );
}

// Two deliberately different prose fonts — editor and preview shouldn't
// look alike. Self-hosted/cached via framework/Assets/FontCache.php, never
// linked straight to Google's CDN.
//
// Editor: Space Grotesk — proportional (not a code font), but geometric/
// technical in character so typing still feels a little "mechanical".
// Code/table lines stay on --pmde-font-mono in plainmde.css.
I::insertWebFont('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap', 'PmdeEditorProse');

// Preview: Lexend — also sans (no serif), but humanist/reading-oriented
// rather than geometric, so it still reads as clearly different from the
// editor's Space Grotesk.
I::insertWebFont('https://fonts.googleapis.com/css2?family=Lexend:wght@400;600;700&display=swap', 'PmdePreviewProse');

