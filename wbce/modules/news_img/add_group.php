<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

require_once __DIR__.'/functions.inc.php';

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
$request = (isset($_GET['section_key']) ? 'GET' : 'POST');
$section_key = $admin->checkIDKEY('section_key', 0, $request);
if (!$section_key || $section_key != $section_id){
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (IDKEY) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
}

// Get new order
$order = new order(TABLE_PREFIX.'mod_news_img_groups', 'position', 'group_id', 'section_id');
$position = $order->get_new($section_id);
$active = 1;

if($admin->get_post('title') == '')
{
	$admin->print_error($MESSAGE['GENERIC_FILL_IN_ALL'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&tab=g');
    $admin->print_footer();
    exit();
}
else
{
	$title = mod_nwi_escapeString($admin->get_post('title'));
	$active = mod_nwi_escapeString($admin->get_post('active'));
	$title = strip_tags($title);
}

// Insert new row into database
$database->query(sprintf(
    "INSERT INTO `%smod_news_img_groups` (`section_id`,`position`,`active`,`title`) " .
    "VALUES ('$section_id','$position','$active','$title')",
    TABLE_PREFIX
));

// Get the id
$group_id = $database->get_one("SELECT LAST_INSERT_ID()");

$forward_url = WB_URL.'/modules/news_img/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$admin->getIDKEY($group_id);
if($request=='POST') {
    $forward_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&tab=g';
}

if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&tab=g');
} else {
	$admin->print_success($TEXT['SUCCESS'], $forward_url);
}

// Print admin footer
$admin->print_footer();
