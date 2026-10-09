<?php
/**
 * tinymce_wbce — install.php
 * Seeds the module config (v2 named presets) as a single JSON value in {TP}settings.
 * @author  Slugger & Claude KI
 * @license GNU GPL2
 */

require_once __DIR__ . '/presets.php';

Settings::set(
    'tinymce_cfg',
    json_encode(tinymce_wbce_default_cfg(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    false
);

// Version-history table (wbce_history plugin). Idempotent.
require_once __DIR__ . '/history.php';
tinymce_wbce_history_ensure_table();
