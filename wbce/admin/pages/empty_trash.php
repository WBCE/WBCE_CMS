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

require('../../config.php');
require_once(WB_PATH . '/framework/class.admin.php');
$admin = new admin('Pages', 'pages');

// Include the WB functions file
require_once(WB_PATH . '/framework/functions.php');

// Get page list from database
// $database = new database();
$query = "SELECT * FROM " . TABLE_PREFIX . "pages WHERE visibility = 'deleted' ORDER BY level DESC";
$get_pages = $database->query($query);

// Insert values into main page list
if ($get_pages->numRows() > 0) {
    while ($page = $get_pages->fetchRow()) {
        // Delete page subs
        $sub_pages = get_subs($page['page_id'], array());
        foreach ($sub_pages as $sub_page_id) {
            delete_page($sub_page_id);
        }
        // Delete page
        delete_page($page['page_id']);
    }
}

// Check if there is a db error, otherwise say successful
if ($database->is_error()) {
    $admin->print_error($database->get_error());
} else {
    $admin->print_success($TEXT['TRASH_EMPTIED']);
}

// Print admin
$admin->print_footer();
