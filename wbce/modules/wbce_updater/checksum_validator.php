<?php
/**
 * WBCE Updater - Checksum Validator
 *
 * Validates downloads using SHA256 checksums
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
 * Extracts checksum hash from GitHub digest field
 *
 * @param string $digest Format: "sha256:HASH" or just "HASH"
 * @return string|null Just the hash or null if invalid
 */
function extractDigestHash($digest) {
    if (empty($digest)) {
        return null;
    }

    // Remove "sha256:" prefix if present
    if (stripos($digest, 'sha256:') === 0) {
        $hash = substr($digest, 7);
    } else {
        $hash = $digest;
    }

    // Validate hash format (64 hex characters for SHA256)
    if (preg_match('/^[a-fA-F0-9]{64}$/', $hash)) {
        return strtolower($hash);
    }

    return null;
}

/**
 * Loads checksums file from URL
 *
 * @param string $url URL to SHA256SUMS file or JSON
 * @return array Associative array [filename => hash]
 */
function loadChecksumsFromUrl($url) {
    $checksums = [];

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

    $content = @file_get_contents($url, false, $context);

    if ($content === false) {
        return $checksums;
    }

    // Try to parse as JSON first
    $json = @json_decode($content, true);
    if ($json !== null && json_last_error() === JSON_ERROR_NONE) {
        // Expected format: {"filename.zip": "sha256hash", ...}
        if (is_array($json)) {
            foreach ($json as $filename => $hash) {
                $validHash = extractDigestHash($hash);
                if ($validHash !== null) {
                    $checksums[$filename] = $validHash;
                }
            }
        }
        return $checksums;
    }

    // Parse as text file (SHA256SUMS format)
    // Format: "HASH  FILENAME" or "HASH *FILENAME"
    $lines = explode("\n", $content);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') {
            continue;
        }

        // Split by whitespace (2+ spaces or tab)
        if (preg_match('/^([a-fA-F0-9]{64})\s+\*?(.+)$/', $line, $matches)) {
            $hash = strtolower($matches[1]);
            $filename = trim($matches[2]);
            $checksums[$filename] = $hash;
        }
    }

    return $checksums;
}

/**
 * Validates downloaded file against expected checksum
 *
 * @param string $filePath Path to file
 * @param string $expectedHash SHA256 hash (without prefix)
 * @return bool True if valid, false otherwise
 */
function validateFileChecksum($filePath, $expectedHash) {
    if (!file_exists($filePath)) {
        return false;
    }

    if (empty($expectedHash)) {
        return false;
    }

    // Normalize expected hash
    $expectedHash = strtolower($expectedHash);

    // Validate hash format
    if (!preg_match('/^[a-fA-F0-9]{64}$/', $expectedHash)) {
        return false;
    }

    // Calculate actual hash
    $actualHash = calculateFileHash($filePath);

    if ($actualHash === false) {
        return false;
    }

    // Use hash_equals for timing-attack safe comparison
    return hash_equals($expectedHash, $actualHash);
}

/**
 * Calculates SHA256 hash of a file
 *
 * @param string $filePath Path to file
 * @return string|false SHA256 hash or false on error
 */
function calculateFileHash($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    $hash = @hash_file('sha256', $filePath);

    if ($hash === false) {
        return false;
    }

    return strtolower($hash);
}

/**
 * Finds checksum for a specific file from checksums array
 *
 * @param string $filename Filename to search for
 * @param array $checksums Array from loadChecksumsFromUrl()
 * @return string|null Checksum or null if not found
 */
function findChecksumForFile($filename, $checksums) {
    // Direct match
    if (isset($checksums[$filename])) {
        return $checksums[$filename];
    }

    // Try basename match
    $basename = basename($filename);
    if (isset($checksums[$basename])) {
        return $checksums[$basename];
    }

    // Try case-insensitive match
    foreach ($checksums as $file => $hash) {
        if (strcasecmp($file, $filename) === 0 || strcasecmp($file, $basename) === 0) {
            return $hash;
        }
    }

    return null;
}
