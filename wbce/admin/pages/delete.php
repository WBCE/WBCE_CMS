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

// Create new admin object and print admin header
require '../../config.php';
$admin   = new Admin('Pages', 'pages_delete', false);
$alerts  = new Alerts();
$backUrl = ADMIN_URL . '/pages/index.php';

if ((!($page_id = $admin->checkIDKEY('page_id', 0, $_SERVER['REQUEST_METHOD'])))) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], true);
    exit();
}

// Get perms
if (!$admin->get_page_permission($page_id, 'admin')) {
    $admin->print_header();
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'], true);
}


// Find out more about the page
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
$visibility = $aPage['visibility'];

$sFilePath = getAccessFilePath($page_id);
if (file_exists($sFilePath)) {
    unlink($sFilePath);
}


// Check if we should delete it or just set the visibility to 'deleted'
if (PAGE_TRASH != 'disabled' and $visibility != 'deleted') {
    // Page trash is enabled and page has not yet been deleted
    // Update the page visibility to 'deleted'
    $database->upsertRow('{TP}pages', 'page_id', [
        'page_id'           => $page_id,
        'visibility'        => 'deleted',
        'visibility_backup' => $aPage['visibility'],
    ]);

    trash_subs($page_id); // Run trash subs for this page
} else {
    // Really dump the page
    // Delete page subs
    $sub_pages = get_subs($page_id, array());
    foreach ($sub_pages as $sub_page_id) {
        delete_page($sub_page_id);
    }
    // Delete page
    delete_page($page_id);
    $sDirPath = str_replace(PAGE_EXTENSION, '', $sFilePath);
    if (is_dir($sDirPath)) {
        rm_full_dir($sDirPath);
    }
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
function trash_subs($iParentID = 0)
{
    global $database;
    // Query pages
    $rChildPages = $database->query(
        "SELECT `page_id`, `visibility` FROM `{TP}pages` WHERE `parent` = ? ORDER BY `position` ASC",
        [$iParentID]
    );
    // Check if there are any pages to show
    if ($rChildPages->numRows() > 0) {
        // Loop through pages
        while ($row = $rChildPages->fetchRow()) {
            // Update the page visibility to 'deleted'
            $database->upsertRow('{TP}pages', 'page_id', [
                'page_id'           => $row['page_id'],
                'visibility'        => 'deleted',
                'visibility_backup' => $row['visibility'],
            ]);
            // Run this function again for all sub-pages
            trash_subs($row['page_id']);
        }
    }
}
