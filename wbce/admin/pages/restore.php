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

// Get page id
if (!isset($_GET['page_id']) or !is_numeric($_GET['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = $_GET['page_id'];
}

// Create new admin object and print admin header
require '../../config.php';
$admin   = new Admin('Pages', 'pages_delete', false);
$alerts  = new Alerts();
$backUrl = ADMIN_URL . '/pages/index.php';


// Get Page Data from Database
$resPage = $database->query("SELECT * FROM `{TP}pages` WHERE `page_id` = ?", [$page_id]);
if ($database->hasError()) {
    $admin->print_header();
    $admin->print_error($database->getError(), true);
}
if ($resPage->numRows() == 0) {
    $admin->print_header();
    $admin->print_error($MESSAGE['PAGES_NOT_FOUND'], true);
}
$aPage = $resPage->fetchRow(MYSQLI_ASSOC);
if (!$admin->isPageAdmin($aPage['admin_groups'], $aPage['admin_users'])) {
    $admin->print_header();
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'], true);
}

$visibility = $aPage['visibility'];

if (PAGE_TRASH) {
    if ($visibility == 'deleted') {
        // Reset the visibility to its previous status
        $sNewVisibility = $aPage['visibility_backup'] != '' ? $aPage['visibility_backup'] : 'public';
        $database->upsertRow('{TP}pages', 'page_id', [
            'page_id'    => $page_id,
            'visibility' => $sNewVisibility,
        ]);

        // Run trash subs for this page
        restore_subs($page_id);
    }
}

$sFilePath = getAccessFilePath($page_id);
if (!file_exists($sFilePath)) {
    create_access_file($sFilePath, $page_id, $aPage['level']);
}

// Check if there is a db error, otherwise say successful
if ($database->hasError()) {
    $admin->print_header();
    $admin->print_error($database->getError(), true);
} else {
    $alerts->sessionToast($TEXT['SUCCESS'], 'success');
    header('Location: ' . $backUrl);
    exit;
}

// Function to change all child pages visibility to deleted
function restore_subs($parent = 0)
{
    global $database;
    // Query pages
    $query_menu = $database->query(
        "SELECT `page_id`, `visibility_backup` FROM `{TP}pages` WHERE `parent` = ? ORDER BY `position` ASC",
        [$parent]
    );
    // Check if there are any pages to show
    if ($query_menu->numRows() > 0) {
        // Loop through pages
        while ($row = $query_menu->fetchRow()) {
            // Reset the visibility to its previous status
            $sNewVisibility = $row['visibility_backup'] != '' ? $row['visibility_backup'] : 'public';
            $database->upsertRow('{TP}pages', 'page_id', [
                'page_id'    => $row['page_id'],
                'visibility' => $sNewVisibility,
            ]);

            // Run this function again for all sub-pages
            restore_subs($row['page_id']);
        }
    }
}
