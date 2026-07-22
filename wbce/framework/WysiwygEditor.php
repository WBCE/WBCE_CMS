<?php
/**
 * WysiwygEditor — the editor-agnostic entry point for rich-text-editor fields.
 * =============================================================================
 * @author     Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 *
 * WHAT THIS IS
 * ------------
 * A single, modern facade that a module calls to render a WYSIWYG editor for a
 * field, WITHOUT knowing or caring which editor (TinyMCE, CKEditor, TipTap …) is
 * installed or active. It replaces the ageing global function show_wysiwyg_editor()
 * as the recommended API, while that function lives on as a thin compatibility
 * shim (see "LEGACY" below).
 *
 *     echo WysiwygEditor::render('content5', $html, [
 *         'editor' => 'tinymce>tiptap',       // which editor (fallback chain)
 *         'config' => 'inline>small>default', // which preset inside it (fallback chain)
 *         'height' => 350,
 *     ]);
 *
 * TWO FALLBACK CHAINS
 * -------------------
 *   1. editor chain  (option 'editor'):  "tinymce>tiptap"
 *      → which editor MODULE handles the field. The dispatcher walks the chain
 *        and picks the FIRST one that is actually installed (see below). An empty
 *        chain, or the literal token "default", means the site's default editor
 *        (the WYSIWYG_EDITOR setting).
 *
 *   2. config chain  (option 'config'):  "inline>small>default"
 *      → which named PRESET/profile inside that editor. The dispatcher passes it
 *        through untouched; the chosen editor resolves it against its own presets
 *        (TinyMCE: tinymce_wbce_resolve_preset_chain()). "default" = that editor's
 *        default preset.
 *
 * HOW AN EDITOR MODULE PLUGS IN  (no registration, discovery + convention)
 * -----------------------------------------------------------------------
 * There is deliberately NO registry to call. The dispatcher discovers editors and
 * finds their render entry by CONVENTION:
 *
 *   • Discovery: an editor module is "installed" when it has a row in {TP}addons
 *     with type='module' and function containing 'wysiwyg' (the same authoritative
 *     query WBCE uses for its editor dropdown).
 *
 *   • Entry point: each editor module's include.php defines ONE function named
 *     "<module_directory>_wysiwyg_render", e.g.
 *
 *         function tinymce_wbce_wysiwyg_render(string $id, string $content, array $opts): string
 *         {
 *             // build the editor for $id/$content honouring $opts (config chain,
 *             // width, height, name …) and RETURN the HTML.
 *         }
 *
 *     The dispatcher resolves the editor chain to a directory, includes that
 *     module's include.php if needed, and calls "<dir>_wysiwyg_render()".
 *
 * Because each editor exposes its OWN render function (not the shared global
 * show_wysiwyg_editor name), different editors can even be used for different
 * fields on the SAME page without colliding.
 *
 * If nothing usable is found, render() falls back to a plain <textarea> — so a
 * caller never has to guard with function_exists() the way old code did.
 *
 * CLIENT-SIDE FLUSH CONVENTION  (editor-agnostic AJAX save / Ctrl+S)
 * -----------------------------------------------------------------
 * Some editors keep their form <textarea> in sync live (TipTap); others only on
 * submit (TinyMCE). For AJAX saves — where no submit event fires — a page-global
 * array lets save code flush whatever editor is active WITHOUT knowing which:
 *
 *     window.WBCE_WYSIWYG_FLUSH  // Array<function()>
 *
 * Each editor that does NOT live-sync pushes a callback that syncs its instances
 * into their textareas (TinyMCE pushes tinymce.triggerSave()). Save code runs:
 *
 *     (window.WBCE_WYSIWYG_FLUSH || []).forEach(function (fn) { try { fn(); } catch (e) {} });
 *     // then read the field by NAME (not element id — ids may differ per editor):
 *     var content = form.elements['content42'].value; // or querySelector('[name=…]')
 *
 * OPTIONS (array $opts)
 * ---------------------
 *   name    string  form field name               (default: $id)
 *   editor  string  editor fallback chain         (default: '' → default editor)
 *   config  string  preset fallback chain         (default: '' → editor's default)
 *   width   string  CSS width, e.g. '100%'        (default: '100%')
 *   height  string  '350' | '350px' | '' (auto)   (default: '')
 *   (reserved for later: save, mode, readonly, placeholder …)
 *
 * LEGACY
 * ------
 * show_wysiwyg_editor($name,$id,$content,$width,$height,$toolbar) stays valid; the
 * active editor module still defines it. New code should prefer WysiwygEditor.
 *
 * @author   C
 * @license  GNU GPL2
 */

defined('WB_PATH') or die('Access denied');

class WysiwygEditor
{
    /** @var string[]|null Cached list of installed editor module directories. */
    private static $installed = null;

    // ── Public API ──────────────────────────────────────────────────────────

    /**
     * Render the editor for a field and RETURN the HTML.
     *
     * @param string $id       DOM id / textarea id of the field.
     * @param string $content  Initial HTML content.
     * @param array  $opts     See the class doc block ("OPTIONS").
     * @return string          Editor HTML (or a <textarea> fallback).
     */
    public static function render(string $id, string $content = '', array $opts = []): string
    {
        // Walk the editor chain candidates in order; the first one that both
        // exists and yields markup wins. This makes the chain a REAL fallback:
        // e.g. "tiptap_editor>tinymce_wbce" uses TinyMCE while TipTap hasn't yet
        // exposed its <dir>_wysiwyg_render() convention function.
        foreach (self::editorCandidates((string) ($opts['editor'] ?? '')) as $dir) {
            $fn = $dir . '_wysiwyg_render';
            if (!function_exists($fn)) {
                $inc = WB_PATH . '/modules/' . $dir . '/include.php';
                if (is_file($inc)) { include_once $inc; }
            }
            if (function_exists($fn)) {
                $html = $fn($id, $content, $opts);
                if (is_string($html) && $html !== '') { return $html; }
            }
        }
        return self::textarea($id, $content, $opts);
    }

    /**
     * Convenience: render() and echo it immediately (matches the old contract's
     * "prints directly" behaviour).
     */
    public static function init(string $id, string $content = '', array $opts = []): void
    {
        echo self::render($id, $content, $opts);
    }

    /**
     * Installed editor module directories (type=module, function contains 'wysiwyg').
     * Cached for the request. Public so tooling/diagnostics can list them.
     *
     * @return string[]
     */
    public static function installedEditors(): array
    {
        if (self::$installed !== null) { return self::$installed; }

        self::$installed = [];
        global $database;
        if (isset($database) && is_object($database)) {
            $rows = $database->fetchAll(
                "SELECT `directory` FROM `{TP}addons`
                  WHERE `type` = 'module' AND `function` LIKE '%wysiwyg%'"
            );
            foreach (($rows ?: []) as $row) {
                $dir = self::sanitizeDir((string) ($row['directory'] ?? ''));
                if ($dir !== '') { self::$installed[] = $dir; }
            }
        }
        return self::$installed;
    }

    // ── Internals ───────────────────────────────────────────────────────────

    /**
     * Ordered list of installed editor directories to try for a chain.
     * Tokens are module directories, or the literal "default" (= WYSIWYG_EDITOR).
     * Order: the chain's installed tokens first, then the default editor, then any
     * remaining installed editors — so render() always has a fallback to try.
     * Only installed editors are included; duplicates are removed.
     *
     * @return string[]
     */
    private static function editorCandidates(string $chain): array
    {
        $installed = self::installedEditors();
        $default   = defined('WYSIWYG_EDITOR') ? self::sanitizeDir((string) WYSIWYG_EDITOR) : '';
        $out       = [];

        $add = function (string $dir) use (&$out, $installed) {
            if ($dir !== '' && in_array($dir, $installed, true) && !in_array($dir, $out, true)) {
                $out[] = $dir;
            }
        };

        foreach (explode('>', $chain) as $tok) {
            $tok = trim($tok);
            if ($tok === '') { continue; }
            $add($tok === 'default' ? $default : self::sanitizeDir($tok));
        }
        $add($default);
        foreach ($installed as $dir) { $add($dir); }

        return $out;
    }

    /** Keep a directory token safe to use in a path / function name. */
    private static function sanitizeDir(string $dir): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $dir);
    }

    /**
     * Plain <textarea> fallback — used when no editor is installed or a provider
     * failed to return markup. Mirrors the old "degrade to a textarea" behaviour.
     */
    private static function textarea(string $id, string $content, array $opts): string
    {
        $name  = htmlspecialchars((string) ($opts['name'] ?? $id), ENT_QUOTES);
        $idAtt = htmlspecialchars($id, ENT_QUOTES);
        $width = htmlspecialchars((string) ($opts['width'] ?? '100%'), ENT_QUOTES);

        $height = trim((string) ($opts['height'] ?? ''));
        if ($height !== '' && ctype_digit($height)) { $height .= 'px'; }
        $style = 'width:' . $width . ';'
               . ($height !== '' ? 'height:' . htmlspecialchars($height, ENT_QUOTES) . ';' : '');

        return '<textarea name="' . $name . '" id="' . $idAtt . '" style="' . $style . '">'
             . htmlspecialchars((string) $content) . '</textarea>';
    }
}
