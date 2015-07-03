<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: delete_droplet.php 1503 2011-08-18 02:18:59Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/droplets/delete_droplet.php $
 * @lastmodified    $Date: 2011-08-18 04:18:59 +0200 (Do, 18. Aug 2011) $
 *
 */

require( '../../config.php' );
require_once( dirname( __FILE__ ) . '/functions.inc.php' );

// Include WB admin wrapper script
require_once( WB_PATH . '/framework/class.admin.php' );
require_once( WB_PATH . '/framework/functions.php' );
$admin = new admin( 'admintools', 'admintools', false, false );
if ( $admin->get_permission( 'admintools' ) == true )
{
    // Get id
    if ( version_compare(WB_VERSION, '2.8.2', '>=') )
    {
        $droplet_id = intval( $admin->checkIDKEY( 'droplet_id', false, 'GET' ) );
        if ( !$droplet_id )
        {
            $admin->print_error( $MESSAGE[ 'GENERIC_SECURITY_ACCESS' ], $module_edit_link );
            exit();
        }
    }
    else
    {
        if ( isset( $_GET[ 'droplet_id' ] ) && is_numeric( $_GET[ 'droplet_id' ] ) )
        {
            $droplet_id = intval( $_GET[ 'droplet_id' ] );
        }
        else
        {
            header( "Location: " . ADMIN_URL . "/pages/index.php" );
        }
    }
}
else
{
    $admin->print_error( $MESSAGE[ 'GENERIC_SECURITY_ACCESS' ], $module_edit_link );
    exit();
}

// fake for wb_handle_export
$_POST[ 'markeddroplet' ] = array(
     $droplet_id
);
ob_start();
wb_handle_export( 'drop_export', $droplet_id );
ob_end_clean();

// check website baker platform (with WB 2.7, Admin-Tools were moved out of settings dialogue)
if ( file_exists( ADMIN_PATH . '/admintools/tool.php' ) )
{
    $admintool_link   = ADMIN_URL . '/admintools/index.php';
    $module_edit_link = ADMIN_URL . '/admintools/tool.php?tool=droplets';
    $admin            = new admin( 'admintools', 'admintools' );
}
else
{
    $admintool_link   = ADMIN_URL . '/settings/index.php?advanced=yes#administration_tools"';
    $module_edit_link = ADMIN_URL . '/settings/tool.php?tool=droplets';
    $admin            = new admin( 'Settings', 'settings_advanced' );
}

// Delete droplet
$database->query( "DELETE FROM " . TABLE_PREFIX . "mod_droplets WHERE id = '$droplet_id' LIMIT 1" );

// Check if there is a db error, otherwise say successful
if ( $database->is_error() )
{
    $admin->print_error( $database->get_error(), WB_URL . '/modules/droplets/modify_droplet.php?droplet_id=' . $droplet_id );
}
else
{
    $admin->print_success( $TEXT[ 'SUCCESS' ], $module_edit_link );
}

// Print admin footer
$admin->print_footer();

?>