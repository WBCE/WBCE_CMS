<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright WBCE Project (2026)
 * @license GNU GPL2 (or any later version)
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * Legacy compatibility stub for class.admin.php
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 *
 * This file exists for two reasons:
 * 1. Maintain compatibility with older modules that still do:
 *      require_once WB_PATH . '/framework/class.admin.php';
 * 2. Help us detect old code that is still referencing the legacy file directly.
 *
 * All new code should rely on the autoloader.
 * Do NOT manually require this file or Admin.php in new code.
 */

defined('WB_PATH') or die('No direct access allowed');

// Load the modern Admin class ONLY if it hasn't been loaded yet
// (The autoloader may have already loaded it)
if (!class_exists('Admin')) {
    require_once WB_PATH . '/framework/Admin.php';
}

// Create alias so old code like `new admin()` or `$GLOBALS['admin']` patterns still work
if (class_exists('Admin') && !class_exists('admin', false)) {
    class_alias('Admin', 'admin');
}

// Show deprecation notice only when debugging is enabled
// Set WBCE_CANONICAL_DEBUG = true to track and handle all manual file inclusions
if (defined('WBCE_CANONICAL_DEBUG') && WBCE_CANONICAL_DEBUG) {
    trigger_error(
        'require_once WB_PATH . \'/framework/class.admin.php\' is deprecated. ' .
        'New code should rely on the autoloader.',
        E_USER_DEPRECATED
    );
}