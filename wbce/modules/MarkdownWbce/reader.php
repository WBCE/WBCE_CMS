<?php
/**
 * reader.php
 *
 * MarkdownWbce popup entry point.
 * Called via MdReaderLink — never linked to directly by end users.
 *
 * GET parameters (built by MdReaderLink):
 *   doc   — single file, relative to WB_PATH          (Variante A)
 *   docs  — JSON array [{path, label}, …]              (Variante B)
 *   dir   — directory path, reads md_reader.json       (Variante D)
 *   title — optional window title override
 *
 * @package  MarkdownWbce
 * @author     Christian M. Stefan (https://www.wbEasy.de/)
 * @version  0.1.0
 */
require_once '../../config.php';
require_once __DIR__ . '/MdReaderHelper.php';

// Admin check — reader is backend-only
$admin = new Admin('Start', 'start', false, true);

// Write access mirrors admin/admintools/tool.php's own per-tool permission
// check (admin/admintools/tool.php:81-86): group 1 always may, everyone else
// needs the MarkdownWbce AdminTool permission specifically — not the generic
// modules_install/modules_uninstall system permission, and not tied to page
// ACL (these files aren't page content, see MDR docs).
$canWrite = $admin->isAdmin()
    || in_array('MarkdownWbce_tool', (array) $admin->get_session('MODULE_PERMISSIONS'), true);

$lang = strtoupper(LANGUAGE);

// ── 1. Resolve input mode ─────────────────────────────────────────────────────

$docs  = [];   // [{absPath, label, relPath}, …]
$title = trim(strip_tags($_GET['title'] ?? ''));

if (isset($_GET['doc'])) {
    // ── Variante A — single file
    //
    // 'exact' — set only on the language-switcher flags below (never by
    // MdReaderLink-generated links, which want the smart default). Without
    // it, findExistingDoc()'s auto-upgrade-to-active-LANGUAGE would bounce
    // an explicit click on the EN flag straight back to README_DE.md,
    // since doc=/…/README.md is indistinguishable from any other generic
    // request for the base file — the flag's own choice has to be able to
    // override that.
    $abs = isset($_GET['exact'])
        ? MdReaderHelper::safePath($_GET['doc'])
        : MdReaderHelper::findExistingDoc($_GET['doc'], $lang);
    if ($abs === null) {
        _mdr_die('Invalid or inaccessible file path.');
    }
    $docs[] = [
        'absPath' => $abs,
        'relPath' => $_GET['doc'],
        'label'   => $title !== '' ? $title : basename($abs, '.md'),
    ];
    if ($title === '') {
        $title = basename($abs, '.md');
    }

} elseif (isset($_GET['docs'])) {
    // ── Variante B — multiple files
    $raw = json_decode($_GET['docs'], true);
    if (!is_array($raw)) {
        _mdr_die('Malformed docs parameter.');
    }
    foreach ($raw as $entry) {
        $abs = MdReaderHelper::findExistingDoc($entry['path'] ?? '', $lang);
        if ($abs === null) continue;
        $docs[] = [
            'absPath' => $abs,
            'relPath' => $entry['path'],
            'label'   => $entry['label'] ?? basename($abs, '.md'),
        ];
    }
    if (empty($docs)) {
        _mdr_die('No accessible files found in docs array.');
    }
    if ($title === '') {
        $title = $docs[0]['label'];
    }

} elseif (isset($_GET['dir'])) {
    // ── Variante D — directory + manifest
    $absDir = MdReaderHelper::safeDir($_GET['dir']);
    if ($absDir === null) {
        _mdr_die('Invalid or inaccessible directory.');
    }
    $manifest = MdReaderHelper::loadManifest($absDir);
    if ($title === '') {
        $title = $manifest['title'];
    }
    foreach ($manifest['docs'] as $entry) {
        $abs = MdReaderHelper::findExistingDoc($entry['path'], $lang);
        if ($abs === null) continue;
        $docs[] = [
            'absPath' => $abs,
            'relPath' => $entry['path'],
            'label'   => $entry['label'],
        ];
    }
    if (empty($docs)) {
        _mdr_die('No accessible files found in directory.');
    }

} else {
    _mdr_die('No document specified.');
}

// ── 2. Active document (tab index) ───────────────────────────────────────────

$activeIdx = max(0, (int) ($_GET['tab'] ?? 0));
if ($activeIdx >= count($docs)) {
    $activeIdx = 0;
}
$activeDoc = $docs[$activeIdx];

// The path of the file actually on screen. 'relPath' is only what the link
// ASKED for; findExistingDoc() may have resolved it to a language variant
// (README.md -> README_DE.md on a DE install). Everything that has to hit that
// exact file - above all the edit form's rel_path, which ajax_save_doc.php
// writes to - must use this, or an edit silently lands in the other language's
// file while the reader keeps showing the unchanged one.
$activeRelPath = MdReaderHelper::_toWebRelPath($activeDoc['absPath']);

// ── 3. Render active document ─────────────────────────────────────────────────

$content = MdReaderHelper::renderFile($activeDoc['absPath']);
$toc     = MdReaderHelper::buildToc($content);   // modifies $content in place

// ── 4. Language variants for active doc ───────────────────────────────────────

$langVariants = MdReaderHelper::availableLanguages($activeDoc['absPath']);

// Mark whichever variant matches the file actually being displayed — the
// segmented-control look (layout/style.css) needs an "active" state, same
// as the tab bar below. A file with no _<CODE> suffix is the base file
// itself, which availableLanguages() lists as 'EN' (this project's own
// fallback-language convention).
if (preg_match('/_([A-Za-z]{2,3})\.md$/', basename($activeDoc['absPath']), $m)) {
    $activeLangCode = strtoupper($m[1]);
} else {
    $activeLangCode = 'EN';
}
foreach ($langVariants as &$lv) {
    $lv['active'] = ($activeLangCode !== null && $lv['code'] === $activeLangCode);
    // relPath is a raw filesystem-relative path (e.g. for safePath() calls
    // elsewhere) — not a usable href on its own. Route through reader.php
    // itself, same as the tab bar below, so it opens the same way any
    // other MdReaderLink-built link does instead of hitting the raw .md
    // file directly (unrendered text, or blocked entirely).
    $lv['url'] = WB_URL . '/modules/MarkdownWbce/reader.php?' . http_build_query(
        ['doc' => $lv['relPath'], 'title' => $title, 'exact' => 1],
        '', '&'
    );
}
unset($lv);

// WB_PATH-relative directory the active doc lives in (e.g.
// "/modules/outputfilter_dashboard") — layout/reader.js needs this to
// resolve an internal markdown link like [x](./CHANGELOG.md) or
// [x](../docs/y.md) against the DOC's own location, not reader.php's own
// URL (window.location.pathname is always .../modules/MarkdownWbce/
// reader.php, regardless of which doc is open).
$docDirRel = str_replace('\\', '/', dirname($activeRelPath));

// ── 5. Syntax highlighting (self-hosted highlight.js) ────────────────────────
//
// Viewer code blocks used to render through readonly CodeMirror instances;
// now highlight.js, self-hosted under layout/vendor/hljs/ with the shared
// layout/highlight.js init (also used by inline embeds — see
// MdReaderHelper::highlightAssets() / renderForEmbed()). PlainMDE's own editor
// engine still needs real CodeMirror regardless — wired below via I::loadPlugin().

$needsHljs = MdReaderHelper::needsCodeMirror($content) || $canWrite;

// ── 5c. FileTree — visual tree for ```file-tree / ```page-tree blocks ────────
// See ParsedownWbce::blockFencedCode() for the <pre class="file-tree"> /
// <pre class="page-tree"> markup and layout/filetree.js (vendored from
// modules/tiptap_editor) for the render.
//
// In edit mode the assets are always loaded, regardless of what the document
// currently contains: the editor's inline tree preview and its live preview
// (PlainMDETreeView / PlainMDE.markdown()) need the renderer the moment an
// author *types* a tree fence, which is by definition after this check.

$needsFileTree = $canWrite || MdReaderHelper::needsFileTree($content);

// ── 5b. Edit mode assets + raw source ─────────────────────────────────────────

$ftanTag  = '';
$rawMd    = '';
$docDirUrl = '';
if ($canWrite) {
    I::loadPlugin('include/PlainMDE');
    $ftanTag = $admin->getFTAN(true);
    $rawMd   = file_get_contents($activeDoc['absPath']);
    if ($rawMd === false) {
        $rawMd = '';
    }
    // Base URL for the doc's own directory — the editor's live preview
    // (layout/reader.htt) rewrites relative image src against this so
    // e.g. ![x](docs/foo.gif) resolves the same way it does in the
    // rendered viewer (MdReaderHelper::_rewriteImagePaths()), instead of
    // resolving relative to reader.php's own URL.
    $docDirUrl = WB_URL . str_replace('\\', '/', dirname($activeRelPath));
}

// ── 6. Build tab URLs (for multi-doc navigation) ──────────────────────────────

$tabs = [];
if (count($docs) > 1) {
    foreach ($docs as $i => $doc) {
        // Rebuild the URL for each tab — keep all original params, swap tab index
        $params              = $_GET;
        $params['tab']       = $i;
        $tabs[] = [
            'label'  => $doc['label'],
            'url'    => WB_URL . '/modules/MarkdownWbce/reader.php?' . http_build_query($params, '', '&'),
            'active' => ($i === $activeIdx),
        ];
    }
}

// ── 7. Render via LayoutParser ────────────────────────────────────────────────

require_once WB_PATH . '/framework/LayoutParser.php';

$parser   = new LayoutParser(__DIR__ . '/layout');
$template = file_get_contents(__DIR__ . '/layout/reader.htt');

$dirUrl = WB_URL . '/modules/MarkdownWbce';

// mtime-based cache-bust for this module's own layout/ assets — without
// it a browser can pin a stale reader.js/filetree.js indefinitely across
// edits (no other versioned query string on these), same recurring bite
// documented in FEE's own fee_ensure_chrome() for the identical reason.
$assetVer = static fn (string $rel): int => (int) @filemtime(__DIR__ . '/layout/' . $rel) ?: 1;

$html = $parser->parse($template, [
    'TITLE'         => $title,
    'CONTENT'       => $content,
    'TOC'           => $toc,
    'HAS_TABS'      => count($tabs) > 1,
    'TABS'          => $tabs,
    'LANG_VARIANTS' => $langVariants,
    'HAS_LANGS'     => !empty($langVariants),
    'NEEDS_HLJS'    => $needsHljs,
    'NEEDS_FILETREE' => $needsFileTree,
    'DIR_URL'       => $dirUrl,
    'WB_URL'        => WB_URL,
    'HAS_EDIT'      => $canWrite,
    'DOC_DIR_URL'   => $docDirUrl,
    'DOC_DIR_REL'   => $docDirRel,
    'FTAN_TAG'      => $ftanTag,
    'RAW_MARKDOWN'  => $rawMd,
    'REL_PATH'      => $activeRelPath,
    'DOC_PATH'      => ltrim($activeRelPath, '/'),
    'SAVE_URL'      => WB_URL . '/modules/MarkdownWbce/ajax_save_doc.php',
    'STYLE_V'       => $assetVer('style.css'),
    'MARKDOWN_V'    => $assetVer('markdown.css'),
    'READER_JS_V'   => $assetVer('reader.js'),
    'FILETREE_JS_V' => $assetVer('filetree.js'),
    'FILETREE_CSS_V' => $assetVer('filetree.css'),
    'HIGHLIGHT_JS_V' => $assetVer('highlight.js'),
    'HLJS_JS_V'     => $assetVer('vendor/hljs/highlight.min.js'),
    'HLJS_CSS_V'    => $assetVer('vendor/hljs/github.min.css'),
]);

// Flush any I::-queued assets (PlainMDE's CSS/JS/webfonts, when edit mode is
// active) into the finished HTML — this page builds its own document instead
// of going through the normal WBCE page pipeline, so nothing else calls
// AssetQueue::process() for us.
I::process($html);

echo $html;

// ── Helper ────────────────────────────────────────────────────────────────────

function _mdr_die(string $msg): never
{
    http_response_code(400);
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>MarkdownWbce</title></head>'
       . '<body style="font-family:sans-serif;padding:2rem;color:#c01727">'
       . '<strong>MarkdownWbce:</strong> ' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8')
       . '</body></html>';
    exit;
}
