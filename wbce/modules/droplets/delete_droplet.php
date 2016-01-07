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
 *
 **/

require_once '../../config.php';
require_once dirname(__FILE__) . '/functions.inc.php';

// Include WB admin wrapper script
$admin = new admin('admintools', 'admintools');
$module_edit_link = ADMIN_URL . '/admintools/tool.php?tool=droplets';

// check permission
if ( $admin->get_permission('admintools') == true )
{
    if ( isset($_GET['droplet_id']) && is_numeric($_GET['droplet_id']) )
    {
        $droplet_id = intval($_GET['droplet_id']);
    }
    else
    {
        header( "Location: " . ADMIN_URL . "/pages/index.php" );
    }
}
else
{
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $module_edit_link );
    $admin->print_footer();
    exit();
}

wbce_export_droplets( array(), 'drop_export', $droplet_id );

$admintool_link   = ADMIN_URL . '/admintools/index.php';

// Delete droplet
$database->query(sprintf(
    "DELETE FROM `%smod_droplets` WHERE `id` = '%d'",
    TABLE_PREFIX, $droplet_id
));

if ( $database->is_error() )
{
    $admin->print_error( $database->get_error(), WB_URL . '/modules/droplets/modify_droplet.php?droplet_id=' . $droplet_id );
}
else
{
    $admin->print_success($DR_TEXT['DELETED'], $module_edit_link);
}

// Print admin footer
$admin->print_footer();
