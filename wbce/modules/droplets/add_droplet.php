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

require_once '../../config.php';

$admin = new admin( 'admintools', 'admintools', true, false );
if ( $admin->get_permission( 'admintools' ) == true )
{
    $admintool_link   = ADMIN_URL . '/admintools/index.php';
    $module_edit_link = ADMIN_URL . '/admintools/tool.php?tool=droplets';
    $modified_when    = time();
    $modified_by      = intval( $admin->get_user_id() );

    $query = 'INSERT INTO `%smod_droplets` SET `name`="", `code`="", `description`="", `comments`="", `active`=1, `modified_when`="%s", `modified_by`="%s"';
    $database->query(sprintf($query,TABLE_PREFIX,$modified_when,$modified_by));

    if($database->is_error())
    {
        $admin->print_error( $database->get_error(), $module_edit_link );
    }
    else
    {
        $droplet_id = intval( $database->get_one( "SELECT LAST_INSERT_ID()" ) );
        $admin->print_success( $TEXT['SUCCESS'], ADMIN_URL.'/admintools/tool.php?tool=droplets&do=modify&droplet_id=' . $droplet_id );
    }
}
else
{
    die( header('Location: '.WB_URL) );
}

// Print admin footer
$admin->print_footer();
