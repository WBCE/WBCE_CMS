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

// Include config file
require('../../config.php');

// Make sure people are allowed to access this page
if(MANAGE_SECTIONS != 'enabled') {
	header('Location: '.ADMIN_URL.'/pages/index.php');
	exit(0);
}

require_once(WB_PATH."/include/jscalendar/jscalendar-functions.php");
/**/
// Create new admin object
require_once(WB_PATH.'/framework/class.admin.php');
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Pages', 'pages_modify',false);

// Get page id
if(!isset($_GET['page_id']) || !is_numeric($_GET['page_id'])) {
	header("Location: index.php");
	exit(0);
} else {
	$page_id = (int)$_GET['page_id'];
}

if (!$admin->checkFTAN())
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
}
// After check print the header
$admin->print_header();
/*
if( (!($page_id = $admin->checkIDKEY('page_id', 0, $_SERVER['REQUEST_METHOD']))) )
{
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
	exit();
}
*/
// Get perms
// $database = new database();
$results = $database->query("SELECT admin_groups,admin_users FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$results_array = $results->fetchRow();
$old_admin_groups = explode(',', $results_array['admin_groups']);
$old_admin_users = explode(',', $results_array['admin_users']);
$in_old_group = FALSE;
foreach($admin->get_groups_id() as $cur_gid){
    if (in_array($cur_gid, $old_admin_groups)) {
        $in_old_group = TRUE;
    }
}
if((!$in_old_group) && !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
	$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

// Get page details
// $database = new database();
$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_header();
	$admin->print_error($database->get_error());
}
if($results->numRows() == 0) {
	$admin->print_header();
	$admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}
$results_array = $results->fetchRow();

// Set module permissions
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];

// Loop through sections
$query_sections = $database->query("SELECT section_id,module,position FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' ORDER BY position ASC");
if($query_sections->numRows() > 0) {
	$num_sections = $query_sections->numRows();
	while($section = $query_sections->fetchRow()) {
		if(!is_numeric(array_search($section['module'], $module_permissions))) {
			// Update the section record with properties
			$section_id = $section['section_id'];
			$sql = ''; $publ_start = 0; $publ_end = 0;
			$dst = date("I")?" DST":""; // daylight saving time?
			if(isset($_POST['block'.$section_id]) && $_POST['block'.$section_id] != '') {
				$sql = "block = '".intval($_POST['block'.$section_id])."'";
			}
            // named sections patch
            if (isset($_POST['namesection'.$section_id])) {
                if($sql != '')
                    $sql .= ",";
                $sql .= " namesection = '".$database->escapeString($_POST['namesection'.$section_id])."'";
            }
			// update publ_start and publ_end, trying to make use of the strtotime()-features like "next week", "+1 month", ...
			if(isset($_POST['start_date'.$section_id]) && isset($_POST['end_date'.$section_id])) {
				if(trim($_POST['start_date'.$section_id]) == '0' || trim($_POST['start_date'.$section_id]) == '') {
					$publ_start = 0;
				} else {
					$publ_start = jscalendar_to_timestamp($_POST['start_date'.$section_id]);
				}
				if(trim($_POST['end_date'.$section_id]) == '0' || trim($_POST['end_date'.$section_id]) == '') {
					$publ_end = 0;
				} else {
					$publ_end = jscalendar_to_timestamp($_POST['end_date'.$section_id], $publ_start);
				}
				if($sql != '')
					$sql .= ",";
				$sql .= " publ_start = '".intval($publ_start)."'";
				$sql .= ", publ_end = '".intval($publ_end)."'";
			}
			$query = "UPDATE ".TABLE_PREFIX."sections SET $sql WHERE section_id = '$section_id' LIMIT 1";
			if($sql != '') {
				$database->query($query);
			}
		}
	}
}

$target	= $admin->get_post_escaped('saveandback');

if ($target=='saveandback') {	
	$target_url = ADMIN_URL.'/pages/index.php';	
} else {
	$target_url = ADMIN_URL.'/pages/sections.php?page_id='.$page_id;
}

// Check for error or print success message
if($database->is_error()) {
	$admin->print_error($database->get_error(),$target_url  );
} else {
	$admin->print_success($MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'], $target_url );
}

// Print admin footer
$admin->print_footer();
