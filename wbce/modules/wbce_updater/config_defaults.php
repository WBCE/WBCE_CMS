<?php
/**
 * WBCE Updater - Configuration Defaults
 *
 * Central configuration file for module constants and settings
 *
 * @category    module
 * @package     wbce_updater
 * @version     0.9.16
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// Prevent direct access
if (!defined('WB_PATH')) {
    exit("Cannot access this file directly");
}

// ============================================================================
// EXTERNAL SERVICE URLs
// ============================================================================

// GitHub API endpoint for WBCE releases
if (!defined('WBCE_UPDATER_GITHUB_API')) {
    define('WBCE_UPDATER_GITHUB_API', 'https://api.github.com/repos/WBCE/WBCE_CMS/releases');
}

// PHP requirements JSON file URL
if (!defined('WBCE_UPDATER_REQUIREMENTS_URL')) {
    define('WBCE_UPDATER_REQUIREMENTS_URL', 'https://wbce.org/media/wbce_php_requirements.json');
}

// Checksums JSON file URL
if (!defined('WBCE_UPDATER_CHECKSUMS_URL')) {
    define('WBCE_UPDATER_CHECKSUMS_URL', 'https://wbce.org/media/checksums.json');
}

// ============================================================================
// CACHE SETTINGS
// ============================================================================

// Cache lifetime for GitHub releases (15 minutes)
if (!defined('WBCE_UPDATER_RELEASES_CACHE')) {
    define('WBCE_UPDATER_RELEASES_CACHE', 900);
}

// Cache lifetime for PHP requirements (1 hour)
if (!defined('WBCE_UPDATER_REQUIREMENTS_CACHE')) {
    define('WBCE_UPDATER_REQUIREMENTS_CACHE', 3600);
}

// Cache directory (unified location for all cache files)
if (!defined('WBCE_UPDATER_CACHE_DIR')) {
    define('WBCE_UPDATER_CACHE_DIR', WB_PATH . '/temp');
}

// ============================================================================
// SECURITY SETTINGS
// ============================================================================

// Maximum upload file size (100 MB)
if (!defined('WBCE_UPDATER_MAX_UPLOAD_SIZE')) {
    define('WBCE_UPDATER_MAX_UPLOAD_SIZE', 100 * 1024 * 1024);
}

// Allowed download hosts (whitelist)
if (!defined('WBCE_UPDATER_ALLOWED_HOSTS')) {
    define('WBCE_UPDATER_ALLOWED_HOSTS', 'github.com,api.github.com');
}

// HTTP request timeout (seconds)
if (!defined('WBCE_UPDATER_HTTP_TIMEOUT')) {
    define('WBCE_UPDATER_HTTP_TIMEOUT', 30);
}

// Verify checksums for downloaded files
// Set to false if no official checksum file is available yet
// WARNING: Disabling checksum verification reduces security!
//
// To enable checksum verification:
// 1. Ensure official checksums are published at WBCE_UPDATER_CHECKSUMS_URL
// 2. Set this constant to true
// 3. Checksums from GitHub API (digest field) will be automatically verified
if (!defined('WBCE_UPDATER_VERIFY_CHECKSUMS')) {
    define('WBCE_UPDATER_VERIFY_CHECKSUMS', false);
}

// ============================================================================
// DEBUG MODE
// ============================================================================

// Enable detailed error messages (should be false in production)
if (!defined('WBCE_UPDATER_DEBUG')) {
    define('WBCE_UPDATER_DEBUG', false);
}
