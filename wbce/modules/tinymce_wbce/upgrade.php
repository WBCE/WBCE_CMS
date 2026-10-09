<?php
/**
 * tinymce_wbce — upgrade.php
 * Force-updates description/author in the addons table and migrates the module
 * config to the current schema:
 *   - very old: individual settings keys       → single JSON 'tinymce_cfg' (v1)
 *   - v1 flat cfg                              → v2 named presets
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

require_once __DIR__ . '/presets.php';

// Force-update module meta
global $database;
$database->query("UPDATE `{TP}addons` SET
    `description` = 'TinyMCE 8 WYSIWYG Editor (self-hosted, GPL2). Replaces CKEditor 4.',
    `author`      = 'Slugger & Claude KI'
    WHERE `directory` = 'tinymce_wbce'");

// Keys that existed in even older versions and are removed now
$obsoleteKeys = [
    'ef_theme', 'wbce_toolbar', 'wbce_toolbar2', 'wbce_accent',
    'wbce_navbar', 'wbce_status', 'wbce_icon_color', 'wbce_icon_hover',
];

// ── Step 1: consolidate very old individual keys into a v1 flat array ─────────
// (Only relevant for installs that predate the single-JSON migration.)
if (!Settings::exists('tinymce_cfg')) {
    $v1 = [
        'toolbar'             => 'standard',
        'height'              => '400',
        'skin'                => 'oxide',
        'content_theme'       => 'default',
        'menubar'             => '0',
        'statusbar'           => '1',
        'auto_minimal'        => '1',
        'auto_minimal_height' => '200',
        'toolbar_minimal'     => '',
        'toolbar_row1'        => '',
        'toolbar_row2'        => '',
        'toolbar_row3'        => '',
    ];
    $keyMap = [
        'tinymce_toolbar'       => 'toolbar',
        'tinymce_height'        => 'height',
        'tinymce_skin'          => 'skin',
        'tinymce_content_theme' => 'content_theme',
        'tinymce_menubar'       => 'menubar',
        'tinymce_statusbar'     => 'statusbar',
        'tinymce_toolbar_row1'  => 'toolbar_row1',
        'tinymce_toolbar_row2'  => 'toolbar_row2',
        'tinymce_toolbar_row3'  => 'toolbar_row3',
    ];
    foreach ($keyMap as $old => $new) {
        if (Settings::exists($old)) {
            $v1[$new] = Settings::get($old);
            Settings::delete($old);
        }
    }
    foreach ($obsoleteKeys as $key) {
        Settings::delete('tinymce_' . $key);
    }
    Settings::set('tinymce_cfg', json_encode($v1));
}

// ── Step 2: normalize / migrate to v2 named presets ──────────────────────────
// tinymce_wbce_load_cfg() accepts v1 (migrates on the fly) or v2 (normalizes),
// guarantees the built-in presets exist, and fills every field default.
$cfg = tinymce_wbce_load_cfg();
Settings::set('tinymce_cfg', json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

// ── Step 3: ensure the version-history table exists (wbce_history plugin) ─────
require_once __DIR__ . '/history.php';
tinymce_wbce_history_ensure_table();
