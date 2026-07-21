<?php
$dir = INCLUDE_URL . '/wbeColoris';

if (defined('FRONTEND_CONTEXT')) {
    I::insertCssFile($dir . '/wbeColoris.css', 'head_late', [], 'wbecoloris-css');
    I::insertJsBundle([$dir . '/wbeColoris.js', $dir . '/wbeColoris.i18n.js'], 'wbecoloris', 'body_late');
} else {
    // head_late for JS: inline scripts in backend pages call WbeColoris() directly
    // in the body, so the library must be available before body content renders.
    I::insertCssBundle([$dir . '/wbeColoris.css', $dir . '/wbeColoris.admin.css'], 'wbecoloris-be', 'head_late');
    I::insertJsBundle([$dir . '/wbeColoris.js', $dir . '/wbeColoris.i18n.js'], 'wbecoloris', 'head_late');
}
