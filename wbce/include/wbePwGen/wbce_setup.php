<?php
/**
 * wbe_pw_gen — WBCE backend integration setup
 *
 * Include this file in your module's backend PHP to enqueue the
 * password-strength widget assets and get the localised labels.
 *
 * After including this file the following are available:
 *   $wpg_labels  — array, pass to WbePwGen.attach() via json_encode()
 *
 * Assets are loaded exactly once per page via the WBCE asset pipeline.
 *
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

if (!defined('WB_URL')) {
    header('Location: ../../index.php');
    exit(0);
}

require_once __DIR__ . '/i18n.php'; // defines $wpg_labels

$_wpg_base = WB_URL . '/include/wbePwGen/';
I::insertCssFile($_wpg_base . 'wbePwGen.css',  'HEAD TOP+');
I::insertJsFile( $_wpg_base . 'wbePwGen.js',   'BODY BTM-');
unset($_wpg_base);
