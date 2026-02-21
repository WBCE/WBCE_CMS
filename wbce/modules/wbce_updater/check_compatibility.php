<?php
/**
 * WBCE Updater - Compatibility Check Endpoint
 *
 * AJAX endpoint for checking PHP compatibility with target WBCE version
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

// Error handling - don't display errors
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

// Include compatibility checker
require_once __DIR__ . '/compatibility_checker.php';

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

// Set JSON header
header('Content-Type: application/json');

try {
    // Get target version from GET or POST
    $target_version = $_GET['version'] ?? $_POST['version'] ?? '';

    if (empty($target_version)) {
        throw new Exception('No version specified');
    }

    // Security: Validate version format (only digits, dots, and optional 'v' prefix)
    if (!preg_match('/^v?\d+\.\d+(\.\d+)?$/i', $target_version)) {
        throw new Exception('Invalid version format: ' . htmlspecialchars($target_version));
    }

    // Check PHP compatibility
    $result = checkPhpCompatibility($target_version);

    // Add current PHP version info
    $result['php_version'] = PHP_VERSION;

    // Check EOL status
    $requirements = loadPhpRequirements();
    if ($requirements !== false) {
        $eolCheck = checkPhpEol(PHP_VERSION, $requirements);
        $result['php_eol'] = $eolCheck;
    }

    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
