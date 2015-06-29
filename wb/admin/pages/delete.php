<?php
/**
 *
 * @category        admin
 * @package         pages
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: delete.php 1457 2011-06-25 17:18:50Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/pages/delete.php $
 * @lastmodified    $Date: 2011-06-25 19:18:50 +0200 (Sa, 25. Jun 2011) $
 *
 */


// Create new admin object and print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_delete');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');


if( (!($page_id = $admin->checkIDKEY('page_id', 0, $_SERVER['REQUEST_METHOD']))) )
{
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
	exit();
}

/* 
// Get page id
if(!isset($_GET['page_id']) || !is_numeric($_GET['page_id'])) {
	header("Location: index.php");
	exit(0);
} else {
	$page_id = $_GET['page_id'];
}
*/
// Get perms
if (!$admin->get_page_permission($page_id,'admin')) {
	$admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
}

// Find out more about the page
$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error());
}
if($results->numRows() == 0) {
	$admin->print_error($MESSAGE['PAGES']['NOT_FOUND']);
}

$results_array = $results->fetchRow();

$visibility = $results_array['visibility'];

// Check if we should delete it or just set the visibility to 'deleted'
if(PAGE_TRASH != 'disabled' AND $visibility != 'deleted') {
	// Page trash is enabled and page has not yet been deleted
	// Function to change all child pages visibility to deleted
	function trash_subs($parent = 0) {
		global $database;
		// Query pages
		$query_menu = $database->query("SELECT page_id FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' ORDER BY position ASC");
		// Check if there are any pages to show
		if($query_menu->numRows() > 0) {
			// Loop through pages
			while($page = $query_menu->fetchRow()) {
				// Update the page visibility to 'deleted'
				$database->query("UPDATE ".TABLE_PREFIX."pages SET visibility = 'deleted' WHERE page_id = '".$page['page_id']."' LIMIT 1");
				// Run this function again for all sub-pages
				trash_subs($page['page_id']);
			}
		}
	}
	
	// Update the page visibility to 'deleted'
	$database->query("UPDATE ".TABLE_PREFIX."pages SET visibility = 'deleted' WHERE page_id = '$page_id.' LIMIT 1");
	
	// Run trash subs for this page
	trash_subs($page_id);
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
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['PAGES']['DELETED']);
}

// Print admin footer
$admin->print_footer();

?>