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

// Get page id
if(!isset($_GET['page_id']) OR !is_numeric($_GET['page_id'])) {
    header("Location: index.php");
    exit(0);
} else {
    $page_id = $_GET['page_id'];
}

// Create new admin object and print admin header
require '../../config.php';
$admin = new admin('Pages', 'pages_delete');


// Get Page Data from Database
$resPage = $database->query("SELECT * FROM `{TP}pages` WHERE `page_id` = ".$page_id);
if($database->is_error()) {
    $admin->print_error($database->get_error());
}
if($resPage->numRows() == 0) {
    $admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}
$aPage = $resPage->fetchRow(MYSQLI_ASSOC);
$old_admin_groups = explode(',', str_replace('_', '', $aPage['admin_groups']));
$old_admin_users  = explode(',', str_replace('_', '', $aPage['admin_users']));

$in_old_group = FALSE;
foreach($admin->get_groups_id() as $cur_gid){
    if (in_array($cur_gid, $old_admin_groups)) {
	$in_old_group = TRUE;
    }
}
if((!$in_old_group) AND !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

$visibility = $aPage['visibility'];

if(PAGE_TRASH) {
    if($visibility == 'deleted') {
        // Reset the visibility to its previous status
        $sNewVisibility = $aPage['visibility_backup'] != '' ? $aPage['visibility_backup'] : 'public';
        $database->updateRow('{TP}pages', 'page_id', array(
                'visibility' => $sNewVisibility,
                'page_id'    => $page_id
            )
        );
                                                
        // Run trash subs for this page
        restore_subs($page_id);
    }
}

$sFilePath = getAccessFilePath($page_id);
if (!file_exists($sFilePath)) {
    create_access_file($sFilePath, $page_id, $aPage['level']);
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
    $admin->print_error($database->get_error());
} else {
    $admin->print_success($MESSAGE['PAGES_RESTORED']);
}

// Print admin footer
$admin->print_footer();

// Function to change all child pages visibility to deleted
function restore_subs($parent = 0) {
    global $database;
    // Query pages
    $query_menu = $database->query(
        "SELECT `page_id`, `visibility_backup` 
            FROM `{TP}pages` WHERE `parent` = '".$parent."' 
            ORDER BY `position` ASC"
    );
    // Check if there are any pages to show
    if($query_menu->numRows() > 0) {
        // Loop through pages
        while($row = $query_menu->fetchRow()) {
            // Reset the visibility to its previous status
            $sNewVisibility = $row['visibility_backup'] != '' ? $row['visibility_backup'] : 'public';
            $database->updateRow('{TP}pages', 'page_id', array(
                    'visibility' => $sNewVisibility,
                    'page_id'    => $row['page_id']
                )
            );
                                                
            // Run this function again for all sub-pages
            restore_subs($row['page_id']);
        }
    }
}