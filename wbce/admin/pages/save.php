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

// Create new admin object
require('../../config.php');
require_once(WB_PATH . '/framework/class.admin.php');

// suppress to print the header, so no new FTAN will be set
$admin = new admin('Pages', 'pages_modify', false);

// Get page & section id
if (!isset($_POST['page_id']) || !is_numeric($_POST['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = intval($_POST['page_id']);
}

if (!isset($_POST['section_id']) || !is_numeric($_POST['section_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $section_id = intval($_POST['section_id']);
}

// $js_back = "javascript: history.go(-1);";
$js_back = ADMIN_URL . '/pages/modify.php?page_id=' . $page_id;

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}
// After check print the header
$admin->print_header();

// Get perms
$results = $database->query(
    'SELECT `admin_groups`, `admin_users` FROM `{TP}pages` WHERE `page_id` = ?',
    [$page_id]
);
$results_array = $results->fetchRow();
if (!$admin->isInGroup($results_array['admin_users']) &&
    !$admin->is_group_match($admin->get_groups_id(), $results_array['admin_groups'])) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}
// Get page module
$module = $database->fetchValue(
    'SELECT `module` FROM `{TP}sections` WHERE `page_id` = ? AND `section_id` = ?',
    [$page_id, $section_id]
);
if (!$module) {
    $admin->print_error($database->hasError() ? $database->getError() : $MESSAGE['PAGES_NOT_FOUND']);
}

// Update the pages table
$database->query(
    'UPDATE `{TP}pages` SET `modified_when` = ?, `modified_by` = ? WHERE `page_id` = ?',
    [time(), $admin->get_user_id(), $page_id]
);

// Include the modules saving script if it exists
if (file_exists(WB_PATH . '/modules/' . $module . '/save.php')) {
    include_once(WB_PATH . '/modules/' . $module . '/save.php');
}
// Check if there is a db error, otherwise say successful
if ($database->hasError()) {
    $admin->print_error($database->getError(), ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
} else {
    $admin->print_success($MESSAGE['PAGES_SAVED'], ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
}

// Print admin footer
$admin->print_footer();
