<?php
/**
 * tinymce_wbce — icons.php
 * Durable, server-side extraction of the TinyMCE toolbar icons.
 *
 * The SVGs live as plain text in tinymce/icons/default/icons.min.js
 * (`bold:'<svg…>'` for identifier-safe names, `"strike-through":'<svg…>'`
 * for hyphenated ones). We parse them once, normalize each SVG to a
 * chip-friendly form (viewBox instead of fixed width/height), and cache the
 * result to a JSON file — so no hidden-editor probe is needed at runtime.
 *
 * Public API:
 *   tinymce_wbce_icon_map()      → [ iconName  => svg ]  (all ~266 icons)
 *   tinymce_wbce_button_icons()  → [ buttonId  => svg ]  (only toolbar buttons)
 *
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) { return; }

if (!function_exists('tinymce_wbce_icon_map')) {

    /** Map a configurator/toolbar button id → the TinyMCE icon name it uses. */
    function tinymce_wbce_button_icon_names(): array
    {
        return [
            'undo' => 'undo', 'redo' => 'redo',
            'bold' => 'bold', 'italic' => 'italic', 'underline' => 'underline',
            'strikethrough' => 'strike-through', 'removeformat' => 'remove-formatting',
            'forecolor' => 'text-color', 'backcolor' => 'highlight-bg-color',
            'alignleft' => 'align-left', 'aligncenter' => 'align-center',
            'alignright' => 'align-right', 'alignjustify' => 'align-justify',
            'bullist' => 'unordered-list', 'numlist' => 'ordered-list',
            'outdent' => 'outdent', 'indent' => 'indent', 'blockquote' => 'quote',
            'link' => 'link', 'wblink' => 'link', 'image' => 'image',
            'media' => 'embed', 'table' => 'table',
            'subscript' => 'subscript', 'superscript' => 'superscript',
            'charmap' => 'insert-character', 'emoticons' => 'emoji',
            'hr' => 'horizontal-rule', 'lineheight' => 'line-height',
            'code' => 'sourcecode', 'wbcodemirror' => 'sourcecode',
            'fullscreen' => 'fullscreen',
        ];
    }

    /**
     * Full icon map (iconName → normalized svg), parsed from icons.min.js.
     * Cached per request (static) and persisted to cache/ (keyed by mtime).
     */
    function tinymce_wbce_icon_map(): array
    {
        static $map = null;
        if ($map !== null) return $map;

        $src = WB_PATH . '/modules/tinymce_wbce/tinymce/icons/default/icons.min.js';
        $srcMtime = @filemtime($src) ?: 0;

        // Persistent cache (best effort — falls back to live parse if unwritable)
        $cacheFile = WB_PATH . '/cache/tinymce_wbce_icons.json';
        if (is_file($cacheFile) && @filemtime($cacheFile) >= $srcMtime) {
            $cached = json_decode((string)@file_get_contents($cacheFile), true);
            if (is_array($cached) && $cached) return $map = $cached;
        }

        $map = tinymce_wbce_parse_icons($src);

        // Write cache (ignore failures — the map still works in-memory)
        if ($map) {
            $dir = dirname($cacheFile);
            if (is_dir($dir) && is_writable($dir)) {
                @file_put_contents($cacheFile, json_encode($map, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
            }
        }
        return $map;
    }

    /** Parse + normalize icons from the icon-pack JS file. */
    function tinymce_wbce_parse_icons(string $src): array
    {
        $js = @file_get_contents($src);
        if ($js === false || $js === '') return [];

        // name:'<svg…</svg>'  or  "name":'<svg…</svg>'  (SVG values are single-quoted;
        // their attributes use double quotes, so no single quote appears inside).
        $re = '/(?:"([a-zA-Z0-9_-]+)"|([a-zA-Z0-9_-]+))\s*:\s*\'(<svg.*?<\/svg>)\'/s';
        if (!preg_match_all($re, $js, $rows, PREG_SET_ORDER)) return [];

        $out = [];
        foreach ($rows as $r) {
            $name = $r[1] !== '' ? $r[1] : $r[2];
            $out[$name] = tinymce_wbce_normalize_svg($r[3]);
        }
        return $out;
    }

    /**
     * Make an SVG chip-friendly: replace fixed width/height on the root <svg>
     * with a viewBox so it scales cleanly to whatever the CSS sets.
     */
    function tinymce_wbce_normalize_svg(string $svg): string
    {
        return preg_replace_callback('/<svg\b([^>]*)>/i', function (array $m): string {
            $attrs = $m[1];
            preg_match('/\bwidth="(\d+)"/i',  $attrs, $w);
            preg_match('/\bheight="(\d+)"/i', $attrs, $h);
            $vw = $w[1] ?? '24';
            $vh = $h[1] ?? '24';
            // Keep any pre-existing viewBox; otherwise derive from width/height
            if (preg_match('/\bviewBox="[^"]*"/i', $attrs, $vb)) {
                return '<svg ' . trim($vb[0]) . '>';
            }
            return '<svg viewBox="0 0 ' . $vw . ' ' . $vh . '">';
        }, $svg, 1) ?? $svg;
    }

    /**
     * Toolbar-button icon map (buttonId → svg). Only buttons that actually have
     * an icon; dropdowns (blocks/fontfamily/fontsize) are intentionally absent
     * so callers fall back to a text chip.
     */
    function tinymce_wbce_button_icons(): array
    {
        $icons = tinymce_wbce_icon_map();
        $out = [];
        foreach (tinymce_wbce_button_icon_names() as $btn => $iconName) {
            if (isset($icons[$iconName])) $out[$btn] = $icons[$iconName];
        }
        // wbce_history: clock with a rewind arm — mirrors addIcon('wb-history', …)
        // in plugins/wbce_history/plugin.js.
        $out['wbce_history'] = '<svg width="24" height="24" viewBox="0 0 24 24">'
            . '<path d="M13 3a9 9 0 1 0 8.94 10h-2.02A7 7 0 1 1 13 5a7 7 0 0 1 6.32 4H16v2h6V5h-2v2.35A9 9 0 0 0 13 3z"/>'
            . '<path d="M12.5 8v5l4 2.3.75-1.3-3.25-1.9V8z"/></svg>';
        // wbce_codesample: chevrons + slash — mirrors addIcon('wb-codesample', …)
        // in plugins/wbce_codesample/plugin.js.
        $out['wbce_codesample'] = '<svg width="24" height="24" viewBox="0 0 24 24">'
            . '<path d="M9.4 16.6 4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0 4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/>'
            . '<path d="M11 4h1.6l-2.4 16H8.6z" opacity=".5"/></svg>';
        // wbce_shy: "a-b" glyph — mirrors addIcon('wb-shy', …) in
        // plugins/wbce_shy/plugin.js.
        $out['wbce_shy'] = '<svg width="24" height="24" viewBox="0 0 24 24">'
            . '<text x="12" y="16" text-anchor="middle" font-size="11" font-family="sans-serif" fill="currentColor">a-b</text></svg>';
        // wbdroplets: flat droplet SVG — mirrors addIcon('wb-droplet', …) in
        // plugins/wbdroplets/plugin.js.
        $out['wbdroplets'] = '<svg width="24" height="24" viewBox="0 0 24 24">'
            . '<path d="M12 2.4c.3 0 .57.15.73.4C14.4 5.4 18.5 10.3 18.5 14a6.5 6.5 0 1 1-13 0c0-3.7 4.1-8.6 5.77-11.2.16-.25.43-.4.73-.4zm0 2.53C10.4 7.3 7.5 11.2 7.5 14a4.5 4.5 0 0 0 9 0c0-2.8-2.9-6.7-4.5-9.07z" fill-rule="nonzero"/></svg>';
        // fa_picker has no TinyMCE stock icon — same custom wavy-flag SVG the
        // plugin registers via addIcon('fa-flag', …) (plugins/fa_picker).
        $out['fa_picker'] = '<svg width="24" height="24" viewBox="0 0 24 24">'
            . '<path d="M6.5 3.25a1 1 0 0 0-2 0V21h2v-6.03c1.6-.98 3.06-.92 4.68-.3 1.83.7 3.9 1.06 6.15-.42a1 1 0 0 0 .42-.84V5.02c0-.8-.9-1.27-1.55-.83-1.7 1.13-3.2.9-4.9.25-1.5-.57-3.16-.94-4.8-.3v-.9z" fill-rule="nonzero"/></svg>';
        return $out;
    }
}
