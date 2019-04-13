<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Create new admin object and print admin header
require '../../config.php';
$admin = new admin('Pages', 'pages_delete');

if ( (!($page_id = $admin->checkIDKEY('page_id', 0, $_SERVER['REQUEST_METHOD']))) ) {
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    exit();
}

// Get perms
if (!$admin->get_page_permission($page_id,'admin')) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

defined('PAGES_DIR_PATH') or define('PAGES_DIR_PATH', WB_PATH.PAGES_DIRECTORY);

// Find out more about the page
$resPage = $database->query("SELECT * FROM {TP}pages WHERE page_id = '$page_id'");
if ($database->is_error()) {
    $admin->print_error($database->get_error());
}
if ($resPage->numRows() == 0) {
    $admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}

$aPage      = $resPage->fetchRow(MYSQLI_ASSOC);
$visibility = $aPage['visibility'];

$sFilePath = getAccessFilePath($page_id);
debug_dump($sFilePath);
if (file_exists($sFilePath)) {
    unlink($sFilePath);
}


// Check if we should delete it or just set the visibility to 'deleted'
if (PAGE_TRASH != 'disabled' AND $visibility != 'deleted') {
    // Page trash is enabled and page has not yet been deleted
    // Update the page visibility to 'deleted'
    $database->query("UPDATE `{TP}pages` SET `visibility` = 'deleted' WHERE `page_id` = '$page_id.' LIMIT 1");   
    trash_subs($page_id); // Run trash subs for this page
} else {
    // Really dump the page
    // Delete page subs
    $sub_pages = get_subs($page_id, array());
    foreach($sub_pages AS $sub_page_id) {
        delete_page($sub_page_id);
    }
    // Delete page
    delete_page($page_id);
}    

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
    $admin->print_error($database->get_error());
} else {
    $admin->print_success($MESSAGE['PAGES_DELETED']);
}

// Print admin footer
$admin->print_footer();

// Function to change all child pages visibility to deleted
function trash_subs($iParentID = 0) {
    global $database;
    // Query pages
    $rChildPages = $database->query("SELECT `page_id` FROM `{TP}pages` WHERE `parent` = '$iParentID' ORDER BY `position` ASC");
    // Check if there are any pages to show
    if ($rChildPages->numRows() > 0) {
        // Loop through pages
        while($row = $rChildPages->fetchRow()) {
            // Update the page visibility to 'deleted'
            $database->query("UPDATE `{TP}pages` SET `visibility` = 'deleted' WHERE `page_id` = '".$row['page_id']."' LIMIT 1");
            // Run this function again for all sub-pages
            trash_subs($row['page_id']);
        }
    }
}

// get the path of pages access file
function getAccessFilePath($iPageID){
    $iParentID = $GLOBALS['database']->get_one("SELECT `parent` FROM `{TP}pages` WHERE `page_id` = ".$iPageID);
    $sDbLink  = $GLOBALS['database']->get_one("SELECT `link` FROM `{TP}pages` WHERE `page_id` = ".$iPageID);
    if ($iParentID == '0') {
        $sFilePath = PAGES_DIR_PATH.'/'.page_filename($sDbLink).PAGE_EXTENSION;
    } else {
        $sParentLink = $database->get_one('SELECT `link` FROM `{TP}pages` WHERE `page_id` = '.$aPage['parent']); 
        $sFilePath = PAGES_DIR_PATH.$sParentLink.'/'.page_filename($sDbLink).PAGE_EXTENSION;
    }
    return $sFilePath;
}