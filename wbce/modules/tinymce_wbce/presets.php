<?php
/**
 * tinymce_wbce — presets.php
 * Single source of truth for the preset data model (tinymce_cfg v2).
 *
 * v2 shape stored in Settings 'tinymce_cfg':
 *   {
 *     "presets": {
 *        "standard": { …full editor profile… },
 *        "full": {…}, "custom": {…}, "minimal": {…}, "myPreset": {…}
 *     },
 *     "default_preset": "standard"
 *   }
 *
 * Each preset is a COMPLETE editor profile — everything that used to live in the
 * single flat cfg (toolbar rows, small toolbar, height, skin, bars, content
 * theme) now belongs to the preset. Phase-2/3 fields (fonts, size unit, style
 * formats, inline) are already present with neutral defaults so the structure is
 * forward-stable and needs no second migration.
 *
 * Used by: upgrade.php, install.php, include.php, tool.php.
 *
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

if (!defined('WB_PATH')) { return; }

if (!function_exists('tinymce_wbce_preset_defaults')) {

    /**
     * Default field set for a single preset. Every preset is normalized against
     * this, so callers can rely on every key being present.
     */
    function tinymce_wbce_preset_defaults(): array
    {
        return [
            'label'               => '',
            'toolbar_row1'        => '',
            'toolbar_row2'        => '',
            'toolbar_row3'        => '',
            'toolbar_minimal'     => '',
            'auto_minimal'        => '1',
            'auto_minimal_height' => '200',
            'height'              => '400',
            'show_toolbar'        => '1',  // '0' = no toolbar at all
            'toolbar_sliding'     => '0',  // '1' = compact single row, rest behind the "…" overflow button
            'skin'                => 'oxide',
            'menubar'             => '0',
            'statusbar'           => '1',
            'wordcount'           => '0',  // '1' = load the wordcount plugin (word/char count in the statusbar)
            'content_theme'       => 'default',
            // ── Phase 2 ──
            'font_families'       => '',   // TinyMCE font_family_formats string ('' = TinyMCE default list)
            'font_sizes'          => '',   // TinyMCE font_size_formats string   ('' = TinyMCE default list)
            'font_size_unit'      => 'px', // px | pt | em | rem
            'block_formats'       => '',   // TinyMCE block_formats string       ('' = TinyMCE default list)
            'style_formats'       => '',   // JSON array of custom styles         ('' = none)
            // ── #1: pull CSS classes from the template's editor.css into Styles ──
            'editorcss_classes'         => 'off', // off | auto (importcss) | annotated (comment-marked whitelist)
            'editorcss_selector_filter' => '',    // auto-mode only: regex to narrow which selectors import
            // Which editor.css layer wins IN THIS PRESET. Per-preset (not global) on
            // purpose: different fields on the SAME page may want different in-editor
            // styling (e.g. a "minimal" field ignoring template CSS while a "full"
            // field uses it). auto|template|wb_config|default|none — see
            // _tinymce_wbce_resolve_editor_css_path() in include.php.
            'editor_css_source'         => 'auto',
            'color_mode'          => '',   // '' = TinyMCE default | 'shared' (one palette for both) | 'split' (separate palettes)
            'colors_shared'       => '',   // JSON [{color,label},...] — used when color_mode = shared
            'colors_fore'         => '',   // JSON — font color palette when color_mode = split
            'colors_back'         => '',   // JSON — marker/background palette when color_mode = split
            'colors_icons'        => '',   // JSON — fa_picker swatches ('' = fall back to fore, then shared)
            'color_cols'          => '',   // swatch grid columns ('' = TinyMCE auto)
            'custom_colors'       => '1',  // allow the free color picker inside the dropdown
            // ── Version history (wbce_history plugin) ──
            'history_enabled'     => '1',  // '0' = record no versions for editors using this preset
            'history_max'         => '10', // rotation cap: versions kept per field (1..100)
            'history_keep'        => '10', // min recent versions the automatic cleanup retains
            'history_autoclean'   => '0',  // '1' = run the age-based cleanup (drop stale versions)
            'history_ttl_days'    => '90', // age threshold (days) beyond the keep floor
            // ── Paste images to MEDIA_DIRECTORY (upload_image.php) ──
            'paste_images'        => '0',      // '1' = upload pasted clipboard images to the server
            'paste_folder'        => 'pasted', // sub-folder under MEDIA (after the user's home folder)
            'paste_webp'          => '0',      // '1' = convert pasted images to WebP (claviska/SimpleImage)
            'paste_webp_quality'  => '82',     // WebP quality 1..100
            'img_dblclick'        => '0',      // '1' = double-click an image opens elFinder (replace src)
            'img_size_badge'      => '0',      // '1' = show a %-of-original size badge on a selected image (click = reset 100%)
            'alt_reminder'        => '1',      // opt-out: mark images with a missing/empty alt attribute (reminder only, no enforcement)
            // ── Link dialog creatable option lists (newline/space separated tokens) ──
            'link_classes'        => '',                                        // class="" suggestions (creatable multiselect)
            'link_rels'           => "nofollow\nnoopener\nnoreferrer\nexternal", // rel="" suggestions (creatable multiselect)
            // ── Phase 3 ──
            'inline'              => '0',
        ];
    }

    /**
     * Curated catalogue of web-safe font stacks offered as checkboxes in the
     * admin tool. Keys are stable ids; value = [display name, css stack].
     * The TinyMCE font_family_formats string is built as "Name=stack;Name=stack".
     */
    function tinymce_wbce_font_catalog(): array
    {
        return [
            'andale'    => ['Andale Mono',     'andale mono,monospace'],
            'arial'     => ['Arial',           'arial,helvetica,sans-serif'],
            'arialblk'  => ['Arial Black',     'arial black,sans-serif'],
            'bookman'   => ['Book Antiqua',    'book antiqua,palatino,serif'],
            'comic'     => ['Comic Sans MS',   'comic sans ms,sans-serif'],
            'courier'   => ['Courier New',     'courier new,courier,monospace'],
            'georgia'   => ['Georgia',         'georgia,palatino,serif'],
            'helvetica' => ['Helvetica',       'helvetica,arial,sans-serif'],
            'impact'    => ['Impact',          'impact,chicago,sans-serif'],
            'system'    => ['System UI',       'system-ui,-apple-system,segoe ui,sans-serif'],
            'tahoma'    => ['Tahoma',          'tahoma,arial,helvetica,sans-serif'],
            'terminal'  => ['Terminal',        'terminal,monaco,monospace'],
            'times'     => ['Times New Roman', 'times new roman,times,serif'],
            'trebuchet' => ['Trebuchet MS',    'trebuchet ms,geneva,sans-serif'],
            'verdana'   => ['Verdana',         'verdana,geneva,sans-serif'],
        ];
    }

    /**
     * Sensible default size lists per unit — offered when the unit changes,
     * never forced over hand-edited values.
     */
    function tinymce_wbce_size_defaults(): array
    {
        return [
            'px'  => '10px 12px 14px 16px 18px 24px 36px 48px',
            'pt'  => '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            'em'  => '0.6em 0.8em 1em 1.2em 1.5em 2em 3em',
            'rem' => '0.6rem 0.8rem 1rem 1.2rem 1.5rem 2rem 3rem',
        ];
    }

    /**
     * Block formats offered as checkboxes (id => TinyMCE block_formats entry).
     * The block_formats string is built as "Label=tag;Label=tag".
     */
    function tinymce_wbce_block_catalog(): array
    {
        return [
            'p'          => ['Paragraph',    'p'],
            'h1'         => ['Heading 1',    'h1'],
            'h2'         => ['Heading 2',    'h2'],
            'h3'         => ['Heading 3',    'h3'],
            'h4'         => ['Heading 4',    'h4'],
            'h5'         => ['Heading 5',    'h5'],
            'h6'         => ['Heading 6',    'h6'],
            'pre'        => ['Preformatted', 'pre'],
            'blockquote' => ['Blockquote',   'blockquote'],
            'div'        => ['Div',          'div'],
        ];
    }

    /**
     * Sanitize a stored swatch list (JSON [{color,label},...]).
     * Every color goes through the core sanitizeCssColor() allowlist; entries
     * whose color does not survive are dropped. Returns the re-encoded JSON,
     * or '' when nothing valid remains.
     */
    function tinymce_wbce_sanitize_color_list(string $json): string
    {
        if (trim($json) === '') return '';
        $rows = json_decode($json, true);
        if (!is_array($rows)) return '';

        $clean = [];
        foreach ($rows as $row) {
            if (!is_array($row)) continue;
            $color = sanitizeCssColor((string)($row['color'] ?? ''));
            if ($color === '') continue;
            $label = trim((string)($row['label'] ?? ''));
            // Labels feed TinyMCE's color_map tooltips — plain text only
            $label = preg_replace('/[<>"]/', '', $label);
            $clean[] = ['color' => $color, 'label' => mb_substr($label, 0, 64)];
        }
        return $clean ? json_encode($clean, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
    }

    /**
     * Map a sanitized swatch list to TinyMCE's flat color_map format:
     * ['#e63946', 'Brand red', '#1d3557', 'Brand blue', ...].
     * Empty labels fall back to the color value itself.
     */
    function tinymce_wbce_color_map(string $json): array
    {
        $rows = json_decode($json ?: '[]', true);
        if (!is_array($rows)) return [];
        $map = [];
        foreach ($rows as $row) {
            if (!is_array($row) || ($row['color'] ?? '') === '') continue;
            $map[] = (string)$row['color'];
            $map[] = (string)(($row['label'] ?? '') !== '' ? $row['label'] : $row['color']);
        }
        return $map;
    }

    /**
     * The built-in preset profiles, keyed by id. Labels are plain here; the tool
     * shows translated fallbacks via L_('TXT:PRESET_<ID>') where available.
     * The 'code' token is swapped to 'wbcodemirror' at runtime when CM5 is
     * present (see tinymce_wbce_runtime_toolbar()).
     */
    /**
     * Built-in presets, with an optional install-default override: for each id a
     * presets/defaults/<id>.json (export-envelope OR raw preset) replaces the
     * shipped code default. Integrators can thus tune the install defaults — and
     * "Reset to defaults" pulls from the very same source — without editing PHP.
     * Falls back to the hardcoded set when a file is missing/invalid.
     */
    function tinymce_wbce_builtin_presets(): array
    {
        static $cache = null;
        if ($cache !== null) { return $cache; }

        $out = tinymce_wbce_builtin_presets_hardcoded();
        $dir = __DIR__ . '/presets/defaults/';
        foreach ($out as $id => $def) {
            $file = $dir . $id . '.json';
            if (!is_file($file)) { continue; }
            $j = json_decode((string) @file_get_contents($file), true);
            if (!is_array($j)) { continue; }
            $p = (isset($j['preset']) && is_array($j['preset'])) ? $j['preset'] : $j;
            $out[$id] = tinymce_wbce_normalize_preset($p);
        }
        return $cache = $out;
    }

    /** The shipped, code-defined built-in presets (fallback / canonical source). */
    function tinymce_wbce_builtin_presets_hardcoded(): array
    {
        $d = tinymce_wbce_preset_defaults();

        $presets = [];

        $presets['minimal'] = array_merge($d, [
            'label'        => 'Minimal',
            'toolbar_row1' => 'undo redo | bold italic underline | bullist numlist | link | code',
        ]);

        $presets['standard'] = array_merge($d, [
            'label'        => 'Standard',
            'toolbar_row1' => 'undo redo | blocks | bold italic underline strikethrough | removeformat',
            'toolbar_row2' => 'forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote',
            'toolbar_row3' => 'link image table | wbdroplets | code fullscreen',
        ]);

        $presets['full'] = array_merge($d, [
            'label'        => 'Full',
            'toolbar_row1' => 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | removeformat',
            'toolbar_row2' => 'forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote',
            'toolbar_row3' => 'link image media table | wbdroplets | subscript superscript charmap | code fullscreen',
        ]);

        $presets['custom'] = array_merge($d, [
            'label' => 'Custom',
            // rows intentionally empty — filled by the user (or by migration)
        ]);

        return $presets;
    }

    /**
     * Reserved built-in ids that cannot be deleted/renamed by the user.
     */
    function tinymce_wbce_builtin_ids(): array
    {
        return ['minimal', 'standard', 'full', 'custom'];
    }

    /**
     * Normalize one raw preset array against the defaults (fills missing keys,
     * drops unknown ones, casts label to string).
     */
    function tinymce_wbce_normalize_preset(array $raw): array
    {
        $d   = tinymce_wbce_preset_defaults();
        $out = [];
        foreach ($d as $k => $default) {
            $out[$k] = array_key_exists($k, $raw) ? $raw[$k] : $default;
        }
        // Validated here (not just at the save_preset endpoint) so every entry
        // path — save, import, migration — ends up with a safe value.
        $out['editor_css_source'] = tinymce_wbce_editor_css_source($out['editor_css_source']);
        return $out;
    }

    /**
     * Load and normalize the full v2 config from Settings.
     *
     * Accepts:
     *   - a v2 structure ({presets, default_preset}) → normalized in place
     *   - a legacy v1 flat structure                → migrated on the fly
     *   - empty / invalid                           → built-in defaults
     *
     * This is READ-ONLY (never writes). upgrade.php persists the migrated form.
     *
     * @return array{presets: array<string,array>, default_preset: string}
     */
    function tinymce_wbce_load_cfg(): array
    {
        $stored = json_decode(\Settings::get('tinymce_cfg', '{}'), true);
        if (!is_array($stored)) $stored = [];

        // Already v2?
        if (isset($stored['presets']) && is_array($stored['presets'])) {
            // One-time migration: editor.css source used to be a single GLOBAL
            // setting. It is now per-preset (different fields on the same page can
            // want different in-editor CSS). Seed any preset that doesn't yet carry
            // its own value from the old global one, so upgrading installs keep
            // their existing behaviour until an admin diverges a preset on purpose.
            $legacyGlobalCss = isset($stored['editor_css_source'])
                ? tinymce_wbce_editor_css_source($stored['editor_css_source'])
                : null;

            $presets = [];
            foreach ($stored['presets'] as $id => $p) {
                if (!is_array($p)) continue;
                if ($legacyGlobalCss !== null && !array_key_exists('editor_css_source', $p)) {
                    $p['editor_css_source'] = $legacyGlobalCss;
                }
                $presets[(string)$id] = tinymce_wbce_normalize_preset($p);
            }
            // Guarantee the built-ins always exist
            foreach (tinymce_wbce_builtin_presets() as $id => $bp) {
                if (!isset($presets[$id])) $presets[$id] = $bp;
            }
            $default = (string)($stored['default_preset'] ?? 'standard');
            if (!isset($presets[$default])) $default = 'standard';

            // Second, lightweight list for inline (FEE) instances.
            $inlinePresets = tinymce_wbce_load_inline_presets($stored);
            $defInline     = (string) ($stored['default_inline_preset'] ?? 'default');
            if (!isset($inlinePresets[$defInline])) $defInline = 'default';
            // inline_toolbar stays as a back-compat MIRROR of the default inline
            // preset's toolbar (fee_config.php and any legacy reader keep working).
            $inlineToolbar = $inlinePresets[$defInline]['toolbar'] !== ''
                ? $inlinePresets[$defInline]['toolbar']
                : tinymce_wbce_inline_toolbar_default();

            return [
                'presets'               => $presets,
                'default_preset'        => $default,
                'inline_presets'        => $inlinePresets,
                'default_inline_preset' => $defInline,
                'inline_toolbar'        => $inlineToolbar,
            ];
        }

        // Legacy v1 → migrate on the fly (non-persisting)
        return tinymce_wbce_migrate_v1($stored);
    }

    /**
     * The default FEE inline toolbar (used until an admin configures one) — this
     * is the toolbar the old hardcoded fee_canvas.js shipped, so nothing changes
     * visually until it is customized. A single compact row; groups split by "|".
     */
    function tinymce_wbce_inline_toolbar_default(): string
    {
        return 'undo redo | blocks | bold italic underline | bullist numlist | link table | removeformat | code';
    }

    /**
     * Sanitize a TinyMCE toolbar string: keep only button tokens (letters, digits,
     * underscore), group separators "|" and spaces; collapse whitespace. Prevents
     * anything unexpected from a stored/posted value reaching the editor config.
     */
    function tinymce_wbce_sanitize_toolbar($raw): string
    {
        $s = is_string($raw) ? $raw : '';
        $s = preg_replace('/[^a-zA-Z0-9_ |]/', '', $s);
        $s = preg_replace('/\s+/', ' ', $s);
        return trim($s);
    }

    // ── Inline (FEE) presets ─────────────────────────────────────────────────
    // A SECOND, lightweight preset list, dedicated to in-place frontend editing
    // (fee_canvas.js / inline TinyMCE). It carries FAR fewer settings than a full
    // preset — inline editing has no iframe, no big chrome — just a single toolbar
    // row and a handful of appearance toggles. Stored separately in the v2 config
    // under 'inline_presets' + 'default_inline_preset'.

    /**
     * Default field set for a single INLINE preset. Deliberately small.
     */
    function tinymce_wbce_inline_preset_defaults(): array
    {
        return [
            'label'         => '',
            'toolbar'       => '',        // single-row toolbar string (groups split by "|")
            'min_height'    => '0',       // 0 = auto (grow with content); >0 = min px
            'skin'          => 'oxide',
            'menubar'       => '0',       // inline editing: off by default
            // NOTE: 'content_theme' and 'statusbar' were dropped (2026-07-14) — inline
            // editing has no iframe, so content_theme's content_css never had anywhere
            // to apply, and TinyMCE's inline mode has no fixed slot for a status bar.
            // Both were dead UI controls; keep the field list to what's actually wired
            // through fee_config.php → fee_canvas.js.
        ];
    }

    /** Normalize one raw inline preset against the defaults (fills/sanitizes). */
    function tinymce_wbce_normalize_inline_preset(array $raw): array
    {
        $d   = tinymce_wbce_inline_preset_defaults();
        $out = [];
        foreach ($d as $k => $def) {
            $out[$k] = array_key_exists($k, $raw) ? $raw[$k] : $def;
        }
        $out['label']      = (string) $out['label'];
        $out['toolbar']    = tinymce_wbce_sanitize_toolbar((string) $out['toolbar']);
        $out['min_height'] = (string) max(0, min(2000, (int) $out['min_height']));
        $out['menubar']    = ($out['menubar'] === '1') ? '1' : '0';
        $out['skin']       = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) $out['skin']) ?: 'oxide';
        return $out;
    }

    /**
     * The shipped inline presets. A single 'default' whose toolbar mirrors the old
     * hardcoded fee_canvas toolbar, so nothing changes visually until customized.
     */
    function tinymce_wbce_builtin_inline_presets(): array
    {
        return [
            'default' => tinymce_wbce_normalize_inline_preset([
                'label'   => 'Default',
                'toolbar' => tinymce_wbce_inline_toolbar_default(),
            ]),
        ];
    }

    /**
     * Build the inline_presets dict from a stored v2 config, migrating the legacy
     * single 'inline_toolbar' string into the 'default' inline preset when no
     * inline_presets exist yet. Always guarantees a 'default' entry.
     *
     * @return array<string,array>
     */
    function tinymce_wbce_load_inline_presets(array $stored): array
    {
        $out = [];
        if (isset($stored['inline_presets']) && is_array($stored['inline_presets'])) {
            foreach ($stored['inline_presets'] as $id => $p) {
                if (!is_array($p)) continue;
                $sid = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) $id);
                if ($sid === '') continue;
                $out[$sid] = tinymce_wbce_normalize_inline_preset($p);
            }
        }
        if (!$out) {
            // Legacy migration: the old single inline_toolbar string → 'default'.
            $out    = tinymce_wbce_builtin_inline_presets();
            $legacy = tinymce_wbce_sanitize_toolbar((string) ($stored['inline_toolbar'] ?? ''));
            if ($legacy !== '') { $out['default']['toolbar'] = $legacy; }
        }
        if (!isset($out['default'])) {
            $bi  = tinymce_wbce_builtin_inline_presets();
            $out = ['default' => $bi['default']] + $out; // keep 'default' first
        }
        return $out;
    }

    /**
     * Resolve an inline preset id to a fully-defaulted inline profile. Empty or
     * unknown id → the configured default_inline_preset → 'default' → built-in.
     */
    function tinymce_wbce_resolve_inline_preset(string $id = ''): array
    {
        $cfg = tinymce_wbce_load_cfg();
        $ips = $cfg['inline_presets'] ?? [];
        if ($id !== '' && isset($ips[$id])) { return $ips[$id]; }
        $def = (string) ($cfg['default_inline_preset'] ?? 'default');
        if (isset($ips[$def])) { return $ips[$def]; }
        $bi = tinymce_wbce_builtin_inline_presets();
        return $bi['default'];
    }

    /**
     * Validate the global editor.css source setting (a whole-config, not per
     * preset, value): which layer of the editor.css chain to use.
     *   auto      — first existing across the whole chain (default)
     *   template  — only the current page-template's editor.css (else fallback)
     *   wb_config — templates/wb_config/editor.css (else fallback)
     *   default   — the module's editor_default.css
     *   none      — no editor.css at all (let the content theme rule)
     */
    function tinymce_wbce_editor_css_source($raw): string
    {
        $v = is_string($raw) ? $raw : 'auto';
        return in_array($v, ['auto', 'template', 'wb_config', 'default', 'none'], true) ? $v : 'auto';
    }

    /**
     * Build a v2 structure from a legacy v1 flat config array.
     * The old editor settings were global, so they seed every built-in preset;
     * the old custom toolbar rows land in the 'custom' preset.
     */
    function tinymce_wbce_migrate_v1(array $v1): array
    {
        $presets = tinymce_wbce_builtin_presets();

        // Old global editor settings → apply to every built-in preset
        $globalKeys = [
            'height', 'skin', 'menubar', 'statusbar', 'content_theme',
            'auto_minimal', 'auto_minimal_height', 'toolbar_minimal',
        ];
        foreach ($presets as $id => &$p) {
            foreach ($globalKeys as $k) {
                if (isset($v1[$k]) && $v1[$k] !== '') {
                    $p[$k] = $v1[$k];
                }
            }
        }
        unset($p);

        // Old custom toolbar rows → the 'custom' preset
        foreach (['toolbar_row1', 'toolbar_row2', 'toolbar_row3'] as $k) {
            if (isset($v1[$k]) && trim((string)$v1[$k]) !== '') {
                $presets['custom'][$k] = $v1[$k];
            }
        }

        // Old active toolbar name → default_preset
        $default = (string)($v1['toolbar'] ?? 'standard');
        if (!isset($presets[$default])) $default = 'standard';

        return [
            'presets'               => $presets,
            'default_preset'        => $default,
            'inline_presets'        => tinymce_wbce_load_inline_presets($v1),
            'default_inline_preset' => 'default',
            'inline_toolbar'        => tinymce_wbce_inline_toolbar_default(),
        ];
    }

    /**
     * Resolve a preset id to a fully-defaulted profile.
     * Falls back to default_preset, then 'standard', then built-in defaults.
     */
    function tinymce_wbce_resolve_preset(string $id = ''): array
    {
        $cfg     = tinymce_wbce_load_cfg();
        $presets = $cfg['presets'];

        if ($id === '' || !isset($presets[$id])) {
            $id = $cfg['default_preset'];
        }
        if (!isset($presets[$id])) {
            $id = isset($presets['standard']) ? 'standard' : array_key_first($presets);
        }
        return $presets[$id] ?? tinymce_wbce_builtin_presets()['standard'];
    }

    /**
     * Swap the neutral 'code' token for 'wbcodemirror' in a toolbar string when
     * the CodeMirror_Config module is installed. Applied at runtime only, so the
     * stored preset stays portable.
     */
    function tinymce_wbce_runtime_toolbar(string $row): string
    {
        static $hasCm = null;
        if ($hasCm === null) {
            $hasCm = file_exists(WB_PATH . '/modules/CodeMirror_Config/CodeEditor.php');
        }
        if (!$hasCm) return $row;
        // Replace the standalone 'code' button token, not substrings of other names
        return preg_replace('/(^|\s)code(\s|$)/', '$1wbcodemirror$2', $row) ?? $row;
    }

    /**
     * The default full v2 config (built-ins + standard as default) — used by
     * install.php for a fresh install.
     */
    function tinymce_wbce_default_cfg(): array
    {
        return ['presets' => tinymce_wbce_builtin_presets(), 'default_preset' => 'standard'];
    }

    /**
     * Sanitize a paste-image sub-folder to safe path segments (no traversal, no
     * special chars). Empty result falls back to 'pasted'.
     */
    function tinymce_wbce_paste_sanitize_folder(string $folder): string
    {
        $out = [];
        foreach (explode('/', str_replace('\\', '/', $folder)) as $seg) {
            $seg = preg_replace('/[^a-zA-Z0-9_-]/', '', $seg);
            if ($seg !== '' && $seg !== '.' && $seg !== '..') { $out[] = $seg; }
        }
        $r = implode('/', $out);
        return $r !== '' ? $r : 'pasted';
    }

    /**
     * Split a stored token string (whitespace/newline separated) into a clean,
     * de-duplicated array of class/rel tokens. Basic sanitizing: allowed chars
     * are letters, digits, _ - : (covers CSS classes incl. utility syntax and
     * rel keywords). Store the result back with implode("\n", …).
     */
    function tinymce_wbce_link_tokens(string $str): array
    {
        $out = [];
        foreach (preg_split('/\s+/', $str, -1, PREG_SPLIT_NO_EMPTY) as $tok) {
            $tok = preg_replace('/[^a-zA-Z0-9_\-:]/', '', $tok);
            if ($tok !== '' && !in_array($tok, $out, true)) { $out[] = $tok; }
        }
        return $out;
    }

    /**
     * Link-dialog option lists for a preset (falls back to the default preset):
     * [classes => [...tokens], rels => [...tokens]].
     */
    function tinymce_wbce_link_options(?string $presetId = null): array
    {
        $defaults = ['classes' => [], 'rels' => ['nofollow', 'noopener', 'noreferrer', 'external']];
        if (!function_exists('tinymce_wbce_load_cfg')) { return $defaults; }
        $cfg = tinymce_wbce_load_cfg();
        $id  = ($presetId !== null && isset($cfg['presets'][$presetId]))
            ? $presetId : ($cfg['default_preset'] ?? '');
        $p = $cfg['presets'][$id] ?? null;
        if (!is_array($p)) { return $defaults; }
        return [
            'classes' => tinymce_wbce_link_tokens((string) ($p['link_classes'] ?? '')),
            'rels'    => tinymce_wbce_link_tokens((string) ($p['link_rels'] ?? '')),
        ];
    }

    /**
     * Paste-image settings for a preset (falls back to the default preset when
     * $presetId is null/unknown): [enabled => bool, folder => 'sub/folder'].
     */
    function tinymce_wbce_paste_settings(?string $presetId = null): array
    {
        $defaults = ['enabled' => false, 'folder' => 'pasted', 'webp' => false, 'quality' => 82];
        if (!function_exists('tinymce_wbce_load_cfg')) { return $defaults; }
        $cfg = tinymce_wbce_load_cfg();
        $id  = ($presetId !== null && isset($cfg['presets'][$presetId]))
            ? $presetId : ($cfg['default_preset'] ?? '');
        $p = $cfg['presets'][$id] ?? null;
        if (!is_array($p)) { return $defaults; }
        return [
            'enabled' => (($p['paste_images'] ?? '0') === '1'),
            'folder'  => tinymce_wbce_paste_sanitize_folder((string) ($p['paste_folder'] ?? 'pasted')),
            'webp'    => (($p['paste_webp'] ?? '0') === '1'),
            'quality' => max(1, min(100, (int) ($p['paste_webp_quality'] ?? 82))),
        ];
    }
}
