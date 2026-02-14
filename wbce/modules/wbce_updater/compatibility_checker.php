<?php
/**
 * WBCE Updater - PHP Compatibility Checker
 *
 * Checks PHP version compatibility for WBCE versions based on requirements.json
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

// Load central configuration
require_once __DIR__ . '/config_defaults.php';

/**
 * Loads PHP requirements from URL with fallback to local file
 *
 * @param bool $forceLocal Force using local file instead of URL
 * @return array|false Parsed JSON or false on error
 */
function loadPhpRequirements($forceLocal = false) {
    $localFile = __DIR__ . '/wbce_php_requirements.json';
    // Use unified cache location from central config
    $cacheFile = WBCE_UPDATER_CACHE_DIR . '/.wbce_requirements_cache.json';

    // Check if cache is valid (not older than CACHE time)
    if (!$forceLocal && file_exists($cacheFile)) {
        $cacheAge = time() - filemtime($cacheFile);
        if ($cacheAge < WBCE_UPDATER_REQUIREMENTS_CACHE) {
            $cached = @file_get_contents($cacheFile);
            if ($cached !== false) {
                $data = @json_decode($cached, true);
                if ($data !== null && json_last_error() === JSON_ERROR_NONE) {
                    return $data;
                }
            }
        }
    }

    // Try to load from URL if not forced local
    if (!$forceLocal) {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => WBCE_UPDATER_HTTP_TIMEOUT,
                'user_agent' => 'WBCE-Updater/1.0',
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ]
        ]);

        $json = @file_get_contents(WBCE_UPDATER_REQUIREMENTS_URL, false, $context);

        if ($json !== false) {
            $data = @json_decode($json, true);
            if ($data !== null && json_last_error() === JSON_ERROR_NONE) {
                // Cache the result
                @file_put_contents($cacheFile, $json);
                return $data;
            }
        }
    }

    // Fallback to local file
    if (file_exists($localFile)) {
        $json = @file_get_contents($localFile);
        if ($json !== false) {
            $data = @json_decode($json, true);
            if ($data !== null && json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        }
    }

    return false;
}

/**
 * Finds matching PHP requirement for a WBCE version
 *
 * @param string $wbceVersion e.g. "1.6.5"
 * @param array $requirements JSON data
 * @return array|null Matching requirement or null
 */
function findRequirementForVersion($wbceVersion, $requirements) {
    if (!isset($requirements['php_requirements']) || !is_array($requirements['php_requirements'])) {
        return null;
    }

    // Normalize version (remove 'v' prefix if present)
    $wbceVersion = ltrim($wbceVersion, 'vV');

    // Check each requirement (first match wins)
    foreach ($requirements['php_requirements'] as $req) {
        $minVersion = $req['wbce_version_min'] ?? null;
        $maxVersion = $req['wbce_version_max'] ?? null;

        if ($minVersion === null) {
            continue;
        }

        // Check if version is in range
        $minCheck = version_compare($wbceVersion, $minVersion, '>=');
        $maxCheck = ($maxVersion === null) || version_compare($wbceVersion, $maxVersion, '<=');

        if ($minCheck && $maxCheck) {
            return $req;
        }
    }

    return null;
}

/**
 * Checks if PHP version is compatible with WBCE version
 *
 * @param string $wbceVersion Target WBCE version
 * @param string $phpVersion Current PHP version (default: PHP_VERSION)
 * @return array ['compatible' => bool, 'details' => array]
 */
function checkPhpCompatibility($wbceVersion, $phpVersion = PHP_VERSION) {
    $result = [
        'compatible' => false,
        'details' => [
            'wbce_version' => $wbceVersion,
            'php_current' => $phpVersion,
            'php_min' => null,
            'php_max' => null,
            'php_recommended' => null,
            'error' => null,
            'warning' => null,
        ]
    ];

    // Load requirements
    $requirements = loadPhpRequirements();
    if ($requirements === false) {
        $result['details']['error'] = 'Could not load PHP requirements';
        return $result;
    }

    // Find requirement for WBCE version
    $requirement = findRequirementForVersion($wbceVersion, $requirements);
    if ($requirement === null) {
        $result['details']['error'] = 'No requirements found for WBCE version ' . $wbceVersion;
        return $result;
    }

    // Extract PHP requirements
    $phpMin = $requirement['php_min'] ?? null;
    $phpMax = $requirement['php_max'] ?? null;
    $phpRecommended = $requirement['php_recommended'] ?? null;

    $result['details']['php_min'] = $phpMin;
    $result['details']['php_max'] = $phpMax;
    $result['details']['php_recommended'] = $phpRecommended;

    // Check compatibility
    if ($phpMin !== null && version_compare($phpVersion, $phpMin, '<')) {
        $result['compatible'] = false;
        $result['details']['error'] = sprintf(
            'PHP version %s is too old. Minimum required: %s',
            $phpVersion,
            $phpMin
        );
        return $result;
    }

    if ($phpMax !== null && version_compare($phpVersion, $phpMax, '>')) {
        $result['compatible'] = false;
        $result['details']['error'] = sprintf(
            'PHP version %s is too new. Maximum supported: %s',
            $phpVersion,
            $phpMax
        );
        return $result;
    }

    // Check EOL status
    $eolCheck = checkPhpEol($phpVersion, $requirements);
    if ($eolCheck['is_eol']) {
        $result['details']['warning'] = sprintf(
            'PHP %s reached end-of-life on %s. Security updates are no longer provided.',
            $phpVersion,
            $eolCheck['eol_date']
        );
    }

    // All checks passed
    $result['compatible'] = true;

    return $result;
}

/**
 * Checks if PHP version is End-of-Life
 *
 * @param string $phpVersion e.g. "8.1.0"
 * @param array $requirements JSON data
 * @return array ['is_eol' => bool, 'eol_date' => string|null]
 */
function checkPhpEol($phpVersion, $requirements) {
    $result = [
        'is_eol' => false,
        'eol_date' => null
    ];

    if (!isset($requirements['php_eol_dates']['versions'])) {
        return $result;
    }

    $eolDates = $requirements['php_eol_dates']['versions'];

    // Extract major.minor version (e.g., "8.1" from "8.1.15")
    $parts = explode('.', $phpVersion);
    if (count($parts) < 2) {
        return $result;
    }

    $majorMinor = $parts[0] . '.' . $parts[1];

    // Check if EOL date exists for this version
    if (isset($eolDates[$majorMinor])) {
        $eolDate = $eolDates[$majorMinor];
        $result['eol_date'] = $eolDate;

        // Check if EOL date has passed
        $eolTimestamp = strtotime($eolDate);
        if ($eolTimestamp !== false && $eolTimestamp < time()) {
            $result['is_eol'] = true;
        }
    }

    return $result;
}
