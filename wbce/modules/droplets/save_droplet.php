<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require_once ('../../config.php');
require_once (dirname(__FILE__) . '/functions.inc.php');

// Include WB admin wrapper script
$admin = new admin('admintools', 'admintools');
/*
echo "<textarea style=\"width:100%;height:200px;color:#000;background-color:#fff;\">";
print_r( $_POST );
echo "</textarea>";
*/
// check permission
if ( $admin->get_permission('admintools') == true )
{
    // Get id
    if ( isset($_POST['droplet_id']) && is_numeric($_POST['droplet_id']) && !empty($_POST['droplet_id']))
    {
        $droplet_id = intval($_POST['droplet_id']);
    }
    else
    {
        header( "Location: " . ADMIN_URL . "/pages/index.php" );
    }
}
else
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], ADMIN_URL.'/admintools/tool.php?tool=droplets' );
    $admin->print_footer();
    exit();
}

// Validate all fields
if($admin->get_post('title') == '')
{
	$admin->print_error($MESSAGE['GENERIC_FILL_IN_ALL'], ADMIN_URL.'/admintools/tool.php?tool=droplets&amp;do=modify&amp;droplet_id='.$droplet_id);
}
else
{
	$title         = $admin->add_slashes($admin->get_post('title'));
	$active        = (int) $admin->get_post('active');
	$admin_view    = (int) $admin->get_post('admin_view');
	$admin_edit    = (int) $admin->get_post('admin_edit');
	$show_wysiwyg  = (int) $admin->get_post('show_wysiwyg');
	$description   = $admin->add_slashes($admin->get_post('description'));
	$tags          = array('<?php', '?'.'>' , '<?');
	$content       = $admin->add_slashes(str_replace($tags, '', $_POST['savecontent']));
	$comments      = $admin->add_slashes($admin->get_post('comments'));
	$modified_when = time();
	$modified_by   = (int) $admin->get_user_id(); 
}

// Update row
$database->query(sprintf(
    "UPDATE `%smod_droplets` SET `name` = '%s', active = '%s', admin_view = '%s', " .
    "admin_edit = '%s', show_wysiwyg = '%s', description = '%s', code = '%s', " .
    "comments = '%s', modified_when = '%s', modified_by = '%s' WHERE id = '%d'",
    TABLE_PREFIX, $title, $active, $admin_view, $admin_edit, $show_wysiwyg,
    $description, $content, $comments, $modified_when, $modified_by, $droplet_id
));

if($database->is_error()) {
	$admin->print_error($database->get_error(),ADMIN_URL.'/admintools/tool.php?tool=droplets');
} else {
    $admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/admintools/tool.php?tool=droplets');
}

// Print admin footer
$admin->print_footer();