<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Include required files
require '../../config.php';
require_once WB_PATH . '/framework/functions.php'; // WBCE 1.1.x compatibility

// Setup admin object, skip header for FTAN validation and check section permissions
$admin = new admin('Addons', 'languages_uninstall', false, true);
if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    $admin->print_footer();
}
// Output admin backend header (this creates a new FTAN)
$admin->print_header();

// Check if user selected a valid language file
$lang_code = $admin->get_post('code');
if (!preg_match('/^[A-Z]{2}$/', $lang_code)) {
    // no valid WBCE language code defined (e.g. EN, DE ..)
    $admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Check if the language files exists
if (!file_exists(WB_PATH . '/languages/' . $lang_code . '.php')) {
    $admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Create escaped string (not needed here, but beeing explicit is better than implicit)
$lang_code_escaped = $database->escapeString($lang_code);

// Check if the language is in use
if ($lang_code == DEFAULT_LANGUAGE or $lang_code == LANGUAGE) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE']);
} else {
    $query_users = $database->query("SELECT user_id FROM " . TABLE_PREFIX . "users WHERE language = '" . $lang_code_escaped . "' LIMIT 1");
    if ($query_users->numRows() > 0) {
        $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE']);
    }
}

// Try to delete the language code
if (!unlink(WB_PATH . '/languages/' . $lang_code . '.php')) {
    $admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
} else {
    // Remove entry from DB
    $database->query("DELETE FROM " . TABLE_PREFIX . "addons WHERE directory = '" . $lang_code_escaped . "' AND type = 'language'");
}

// Print success message
$admin->print_success($MESSAGE['GENERIC_UNINSTALLED']);

// Print admin footer
$admin->print_footer();
