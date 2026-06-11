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

// Include config file
require '../../config.php';

// Make sure people are allowed to access this page
if (MANAGE_SECTIONS != 'enabled') {
    header('Location: ' . ADMIN_URL . '/pages/index.php');
    exit(0);
}

// Create new admin object
$admin = new Admin('Pages', 'pages_modify', false);

// Get page_id
if (!isset($_GET['page_id']) || !is_numeric($_GET['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = (int)$_GET['page_id'];
}

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL . '/pages/sections.php?page_id=' . $page_id);
}

// Get perms
// $database = new database();
$results = $database->query("SELECT `admin_groups`, `admin_users` FROM `{TP}pages` WHERE `page_id` = ?", [$page_id]);
$results_array = $results->fetchRow();
if (!$admin->isPageAdmin($results_array['admin_groups'], $results_array['admin_users'])) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

// Get page details
// $database = new database();
$results = $database->query("SELECT * FROM `{TP}pages` WHERE `page_id` = ?", [$page_id]);
if ($database->hasError()) {
    $admin->print_header();
    $admin->print_error($database->getError());
}
if ($results->numRows() == 0) {
    $admin->print_header();
    $admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}
$results_array = $results->fetchRow();

// Set module permissions
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];

// Loop through sections
$query_sections = $database->query("SELECT `section_id`, `module`, `position` FROM `{TP}sections` WHERE `page_id` = ? ORDER BY `position` ASC", [$page_id]);
if ($query_sections->numRows() > 0) {
    $num_sections = $query_sections->numRows();
    while ($section = $query_sections->fetchRow()) {
        if (!is_numeric(array_search($section['module'], $module_permissions))) {
            // Update the section record with properties
            $section_id = $section['section_id'];
            $parts      = [];
            $params     = [];
            $publ_start = 0;
            $publ_end   = 0;
            if (isset($_POST['block' . $section_id]) && $_POST['block' . $section_id] != '') {
                $parts[]  = '`block` = ?';
                $params[] = intval($_POST['block' . $section_id]);
            }
            // named sections patch
            if (isset($_POST['namesection' . $section_id])) {
                $parts[]  = '`namesection` = ?';
                $params[] = htmlentities(strip_tags($_POST['namesection' . $section_id]));
            }
            // update publ_start and publ_end, trying to make use of the strtotime()-features like "next week", "+1 month", ...
            if (isset($_POST['start_date' . $section_id]) && isset($_POST['end_date' . $section_id])) {
                require_once INCLUDE_PATH . '/date_time_picker/wbce_setup.php';
                if (trim($_POST['start_date' . $section_id]) == '0' || trim($_POST['start_date' . $section_id]) == '') {
                    $publ_start = 0;
                } else {
                    $publ_start = jscalendar_to_timestamp($_POST['start_date' . $section_id], '', 'true');
                }
                if (trim($_POST['end_date' . $section_id]) == '0' || trim($_POST['end_date' . $section_id]) == '') {
                    $publ_end = 0;
                } else {
                    $publ_end = jscalendar_to_timestamp($_POST['end_date' . $section_id], $publ_start, 'true');
                }
                $parts[]  = '`publ_start` = ?';
                $parts[]  = '`publ_end` = ?';
                $params[] = intval($publ_start);
                $params[] = intval($publ_end);
            }
            if (!empty($parts)) {
                $params[] = $section_id;
                $database->query(
                    'UPDATE `{TP}sections` SET ' . implode(', ', $parts) . ' WHERE `section_id` = ? LIMIT 1',
                    $params
                );
            }
        }
    }
}

$target = $admin->get_post_escaped('saveandback');

if ($target == 'saveandback') {
    $target_url = ADMIN_URL . '/pages/index.php';
} else {
    $target_url = ADMIN_URL . '/pages/sections.php?page_id=' . $page_id;
}

// Check for error or redirect with toast
if ($database->hasError()) {
    $admin->print_header();
    $admin->print_error($database->getError(), $target_url);
    $admin->print_footer();
} else {
    $alerts = new Alerts();
    $alerts->sessionToast($MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'], 'success');
    header('Location: ' . $target_url);
    exit;
}
