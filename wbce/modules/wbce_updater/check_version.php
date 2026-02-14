<?php
/**
 * WBCE Update-Assistent - Version Check
 *
 * Prüft GitHub API auf verfügbare WBCE Updates
 *
 * @category    module
 * @package     wbce_updater
 * @version     0.9.16
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// Start output buffering to catch any unwanted output
ob_start();

// Error handling - don't display errors, capture them
$originalErrorReporting = error_reporting();
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Include WBCE framework
$configFile = dirname(dirname(dirname(__FILE__))) . '/config.php';
if (!file_exists($configFile)) {
    ob_end_clean();
    header('Content-Type: application/json');
    exit(json_encode(['error' => 'Configuration file not found']));
}
require $configFile;
require_once WB_PATH . '/framework/class.admin.php';

// Load central configuration
require_once __DIR__ . '/config_defaults.php';

// Security check: Admin only (without header output for AJAX)
$admin = new admin('Admintools', 'admintools', false, false);

// CSRF protection: Check FTAN token (with fallback for older WBCE versions)
// Token can be sent via POST parameter or custom header
$ftan = $_POST['ftan'] ?? $_SERVER['HTTP_X_FTAN'] ?? '';

// Try FTAN check first (modern WBCE versions)
$ftan_valid = false;
if (!empty($ftan) && method_exists($admin, 'checkFTAN')) {
    $ftan_valid = $admin->checkFTAN($ftan);
}

// Fallback for WBCE 1.4.x: Check session-based authentication
$session_valid = isset($_SESSION['USER_ID']) && $_SESSION['USER_ID'] &&
                 isset($_SESSION['GROUP_ID']) && $_SESSION['GROUP_ID'] == 1;

if (!$ftan_valid && !$session_valid) {
    ob_end_clean();
    http_response_code(403);
    header('Content-Type: application/json');
    exit(json_encode(['error' => 'Invalid or missing CSRF token']));
}

// Clear any output that might have been generated
ob_end_clean();

// Set JSON header early
header('Content-Type: application/json');

// GitHub API endpoint (from central config)
$github_api = WBCE_UPDATER_GITHUB_API;

// Cache file path (unified location)
$cache_file = WBCE_UPDATER_CACHE_DIR . '/.wbce_releases_cache.json';
$cache_lifetime = WBCE_UPDATER_RELEASES_CACHE;

try {
    // Check if allow_url_fopen is enabled
    if (!ini_get('allow_url_fopen')) {
        throw new Exception('allow_url_fopen is disabled on this server');
    }

    // Check cache first
    $use_cache = false;
    if (file_exists($cache_file)) {
        $cache_age = time() - filemtime($cache_file);
        if ($cache_age < $cache_lifetime) {
            $response = file_get_contents($cache_file);
            if ($response !== false && !empty($response)) {
                $use_cache = true;
            }
        }
    }

    // Fetch from GitHub if no valid cache
    if (!$use_cache) {
        // Create context for API request with configurable timeout
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: WBCE-Updater/1.0\r\nAccept: application/vnd.github.v3+json",
                'timeout' => WBCE_UPDATER_HTTP_TIMEOUT,
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true
            ]
        ]);

        // Retry mechanism: try twice with delay
        $response = false;
        $max_retries = 2;
        $last_error = '';

        for ($attempt = 1; $attempt <= $max_retries; $attempt++) {
            $response = @file_get_contents($github_api, false, $context);

            if ($response !== false && !empty($response)) {
                // Success - save to cache
                @file_put_contents($cache_file, $response);
                break;
            }

            // Get error details
            $error = error_get_last();
            $last_error = isset($error['message']) ? $error['message'] : 'Unknown error';

            // Check HTTP response code
            if (isset($http_response_header)) {
                foreach ($http_response_header as $header) {
                    if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                        $status_code = (int)$matches[1];

                        // Don't retry on client errors (4xx)
                        if ($status_code >= 400 && $status_code < 500) {
                            throw new Exception("GitHub API returned error $status_code");
                        }

                        // 5xx errors: retry after delay
                        if ($status_code >= 500 && $attempt < $max_retries) {
                            sleep(2); // Wait 2 seconds before retry
                            continue;
                        }
                    }
                }
            }

            // Wait before retry
            if ($attempt < $max_retries) {
                sleep(2);
            }
        }

        if ($response === false || empty($response)) {
            // Try to use old cache as fallback
            if (file_exists($cache_file)) {
                $response = file_get_contents($cache_file);
                if ($response !== false && !empty($response)) {
                    $use_cache = true;
                    // Add warning that cache is used
                } else {
                    // Generic error message to avoid information disclosure
                    throw new Exception('GitHub API is currently unavailable. Please try again later.');
                }
            } else {
                throw new Exception('GitHub API is currently unavailable. Please try again later.');
            }
        }
    }

    $releases = json_decode($response, true);

    if (!is_array($releases)) {
        throw new Exception('Invalid response from GitHub API');
    }

    // Filter and prepare available updates
    $available_updates = [];

    foreach ($releases as $release) {
        // Skip drafts and pre-releases
        if ($release['draft'] || $release['prerelease']) {
            continue;
        }

        // Extract version from tag_name (e.g., "1.6.5" from "1.6.5" or "v1.6.5")
        $version = ltrim($release['tag_name'], 'v');

        // Find download URL for zip file and extract digest (checksum)
        $download_url = null;
        $checksum = null;
        if (isset($release['assets']) && is_array($release['assets'])) {
            foreach ($release['assets'] as $asset) {
                if (isset($asset['name']) && strpos($asset['name'], '.zip') !== false) {
                    $download_url = $asset['browser_download_url'];
                    // Extract digest field (available since June 2025)
                    if (isset($asset['digest'])) {
                        $checksum = $asset['digest']; // Format: "sha256:HASH"
                    }
                    break;
                }
            }
        }

        // If no asset found, try zipball_url as fallback
        if ($download_url === null && isset($release['zipball_url'])) {
            $download_url = $release['zipball_url'];
        }

        $available_updates[] = [
            'version' => $version,
            'name' => $release['name'] ?? $version,
            'published_at' => $release['published_at'] ?? '',
            'download_url' => $download_url,
            'checksum' => $checksum,  // NEW: Checksum from digest field
            'body' => $release['body'] ?? '', // Release notes
            'html_url' => $release['html_url'] ?? ''
        ];
    }

    // Return JSON response
    $result = [
        'success' => true,
        'updates' => $available_updates
    ];

    // Add cache info if cached data was used
    if ($use_cache && file_exists($cache_file)) {
        $cache_age = time() - filemtime($cache_file);
        $result['cached'] = true;
        $result['cache_age'] = $cache_age;
    }

    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
