<?php
/**
 * WBCE Update-Assistent - English Language File
 *
 * @category    module
 * @package     wbce_updater
 * @version     0.9.16
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) {
    exit('Direct access to this file is not allowed');
}

$LANG = [
    // General
    'TOOL_NAME' => 'WBCE Update Assistant',
    'CURRENT_VERSION' => 'Installed Version',

    // Backup Section
    'BACKUP_REQUIRED' => 'Backup Required!',
    'BACKUP_BUTTON' => 'Open Backup Plus (new window)',
    'BACKUP_CONFIRMED' => 'I have created and downloaded a backup',
    'BACKUP_PLUS_MISSING' => 'Backup Plus is not installed!',
    'INSTALL_BACKUP_PLUS' => 'Install Backup Plus',
    'BACKUP_COMPLETE_QUESTION' => 'Have you created and downloaded a backup?',

    // Updates Section
    'AVAILABLE_UPDATES' => 'Available Updates',
    'RECOMMENDED_UPDATE' => 'Recommended Update',
    'OTHER_UPDATES' => 'Other Available Updates',
    'CHECK_UPDATES' => 'Check for Updates',
    'LOADING' => 'Loading Updates',
    'LOADING_DOWNLOAD' => 'Downloading update',
    'DOWNLOAD_PLEASE_WAIT' => 'Please wait, this may take several minutes.',
    'NO_UPDATES_AVAILABLE' => 'No updates available',
    'UP_TO_DATE' => 'Your WBCE installation is up to date.',
    'SHOW_ADDITIONAL_UPDATES' => 'Show additional versions',
    'HIDE_ADDITIONAL_UPDATES' => 'Hide additional versions',
    'HIDDEN_UPDATES' => 'hidden',
    'CACHED_DATA_INFO' => 'Note: Showing cached data (age: %s). GitHub API was not reachable.',
    'GITHUB_TIMEOUT_HINT' => 'GitHub API not responding (timeout). Please try again later.',

    // Update Actions
    'DOWNLOAD_PREPARE' => 'Download & Prepare Update',
    'START_UPDATE_NOW' => 'Start Update Now',
    'VIEW_DETAILS' => 'View Details',
    'RELEASED' => 'Released',

    // Risk Levels
    'RISK_PATCH' => 'Patch Update (safe)',
    'RISK_MINOR' => 'Minor Update (caution)',
    'RISK_MAJOR' => 'Large Update (high risk)',

    // Manual Upload Section
    'MANUAL_UPLOAD_TITLE' => 'Manual Upload',
    'MANUAL_UPLOAD_DESCRIPTION' => 'Upload your own WBCE ZIP file (e.g., custom build or prepared update package)',
    'SELECT_ZIP_FILE' => 'Select ZIP file',
    'UPLOAD_AND_PREPARE' => 'Upload & Prepare',
    'UPLOAD_NOTE' => 'Note: The ZIP file must contain a "wbce" folder with the WBCE installation or directly contain the WBCE files.',
    'MAX_UPLOAD_SIZE' => 'Max. upload size',
    'UPLOAD_SIZE_WARNING' => 'Warning: Upload limit may be too small for WBCE updates!',
    'RECOMMENDED' => 'Recommended',
    'JUMP_TO_UPLOAD' => 'Jump to manual upload',

    // Upload Success/Error Messages
    'UPLOAD_SUCCESS_TITLE' => 'Upload successful!',
    'UPLOAD_FILES_PREPARED' => 'The following files have been prepared:',
    'ERROR_NO_FILE_UPLOADED' => 'No file uploaded',
    'ERROR_UPLOAD_FAILED' => 'Upload failed',
    'ERROR_INVALID_ZIP' => 'Invalid ZIP file',
    'ERROR_ZIP_TOO_LARGE' => 'ZIP file is too large',
    'ERROR_UPLOAD_PARTIAL' => 'File was only partially uploaded',
    'ERROR_UPLOAD_NO_TMP_DIR' => 'No temporary directory available',
    'ERROR_UPLOAD_CANT_WRITE' => 'Failed to write to disk',

    // Maintenance Mode
    'MAINTENANCE_MODE' => 'Enable maintenance mode (recommended)',
    'MAINTENANCE_ENABLED' => 'Maintenance mode enabled',
    'MAINTENANCE_INFO' => 'Maintenance mode has been successfully activated. The website is now unavailable to regular visitors (administrators can still log in).',
    'MAINTENANCE_DISABLE_INFO' => 'After the update: Deactivate maintenance mode via Admin Tools → Maintenance Mode Switcher or Backend → Settings.',
    'MAINTENANCE_NOT_ACTIVATED' => 'Maintenance mode could not be activated',
    'MAINTENANCE_MANUAL_INFO' => 'Activate maintenance mode manually via the backend module "Maintenance Mode Switcher" (Admin Tools) or Backend → Settings.',
    'WARNING_NO_MAINTENANCE_TEMPLATE' => 'No maintenance page template found. Install the Maintenance Mode Switcher module or create a maintainance.tpl.php file.',
    'MAINTENANCE_ALREADY_ACTIVE' => 'Maintenance mode already active',
    'MAINTENANCE_ALREADY_ACTIVE_INFO' => 'Maintenance mode was already enabled. The website is unavailable to regular visitors.',

    // Confirmations
    'CONFIRM_DOWNLOAD' => 'Do you want to download and prepare the update package now?',
    'CONFIRM_MINOR_UPDATE' => 'CAUTION: This is a minor update! Changes may be required. Do you have a current backup and want to continue?',
    'CONFIRM_MAJOR_UPDATE' => 'WARNING: This is a large update (major version or multiple minor levels)! Significant changes and incompatibilities may occur. Please read the release notes carefully and ensure you have a complete backup. Continue?',

    // Success Messages
    'SUCCESS_TITLE' => 'Update files downloaded successfully!',
    'SUCCESS_FILES_DOWNLOADED' => 'The following files have been saved in the root directory:',
    'SUCCESS_UPDATE_PACKAGE' => 'Update package',
    'SUCCESS_UPDATE_SCRIPT' => 'Update script',
    'READY_TO_UPDATE' => 'Everything is ready for the update!',
    'CLICK_BUTTON_TO_START' => 'Click the button below to start the update.',
    'OR_MANUAL' => 'Or call manually',
    'BACK_TO_UPDATER' => 'Back to Update Assistant',
    'WARNINGS_OCCURRED' => 'Warnings during preparation',

    // Error Messages
    'ERROR_TITLE' => 'Download Error',
    'ERROR_OCCURRED' => 'The following errors occurred:',
    'ERROR_FTAN' => 'Security check failed',
    'ERROR_NO_URL' => 'No download URL specified',
    'ERROR_BACKUP_NOT_CONFIRMED' => 'Please confirm that you have created a backup!',
    'ERROR_NO_WRITE_PERMISSION' => 'No write permissions in root directory! Please check file permissions.',
    'ERROR_DOWNLOAD_FAILED' => 'Download of update package failed',
    'ERROR_SAVE_FAILED' => 'Saving update package failed',
    'ERROR_REPACK_FAILED' => 'ZIP repack failed',
    'ERROR_UNZIP_DOWNLOAD_FAILED' => 'Download of update script failed',
    'ERROR_UNZIP_SAVE_FAILED' => 'Saving update script failed',
    'ERROR_CONFIG_READ_FAILED' => 'Could not read config.php',
    'ERROR_CONFIG_WRITE_FAILED' => 'Could not write config.php',
    'ERROR_LOADING_UPDATES' => 'Error loading updates',
    'WARNING_MAINTENANCE_FAILED' => 'Could not enable maintenance mode',

    // PHP Compatibility
    'PHP_COMPATIBLE' => 'PHP compatible',
    'PHP_INCOMPATIBLE' => 'PHP incompatible',
    'PHP_EOL_WARNING' => 'Your PHP version is end-of-life',
    'PHP_CURRENT' => 'Current PHP version',
    'PHP_REQUIRED' => 'Required PHP version',
    'PHP_RECOMMENDED' => 'Recommended PHP version',
    'CONFIRM_PHP_INCOMPATIBLE' => 'WARNING: Your PHP version (%s) is NOT compatible with WBCE %s!

Required: PHP %s - %s
Recommended: PHP %s

The update may cause errors. Please update PHP first.

Continue anyway?',

    // Checksums
    'CHECKSUM_VALIDATED' => 'Download successfully validated',
    'ERROR_CHECKSUM_MISMATCH' => 'Checksum does not match! Download may be corrupted or manipulated.',
    'WARNING_NO_CHECKSUM' => 'No checksum available - download cannot be validated',
    'WARNING_CHECKSUM_DISABLED' => 'WARNING: Checksum verification is disabled. The integrity of the downloaded file cannot be guaranteed.',
    'WARNING_CHECKSUM_DISABLED_MANUAL' => 'NOTE: Automatic checksum verification is disabled. Please verify the checksum manually!',
    'CHECKSUM_INFO' => 'SHA256 Checksum',
    'CHECKSUM_VERIFY_INFO' => 'Compare this checksum with the official release checksum before proceeding.',
];
