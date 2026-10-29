<?php
/**
 * WBCE Updater – Local Configuration (Template)
 *
 * To set your own configuration:
 * 1. Copy this file and rename the copy to "user_config.php"
 * 2. Enter the desired values in user_config.php
 *
 * user_config.php is NEVER created or overwritten automatically.
 * Since it is created manually, it is never included in a release ZIP
 * and therefore automatically survives all WBCE and module updates.
 *
 * This file (user_config.default.php) is always included in the release ZIP
 * and gets refreshed on updates - always put your own settings in
 * user_config.php, never here.
 *
 * @category    module
 * @package     wbce_updater
 */
defined('WB_PATH') or die("This file can't be accessed directly!");

// ============================================================================
// CUSTOM UPDATE SOURCE
// ============================================================================
// Full HTTPS URL to your own update ZIP package.
// The ZIP must have the same structure as the official WBCE release package
// (i.e. contain a "wbce" folder, or the WBCE files directly).
// Leave empty to use only the default source (GitHub).
//
// Example: 'https://example.com/updates/wbce_custom_build.zip'
$wbce_updater_custom_source_url = '';

// ============================================================================
// UPDATE LOCK
// ============================================================================
// Set to true to lock the update tool for regular administrators.
// In future WBCE versions this will be replaced by the extended
// permission system and can then be removed here.
$wbce_updater_disabled = false;
