<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Bianka (WebBird)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			      http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 *
 */

//defined('WB_PATH') OR die(header('Location: ../index.php'));

global $TEXT;

require_once('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');
require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
require_once( dirname(__FILE__).'/functions.inc.php' );

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/droplets/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php');
	}
}

// create admin object depending on platform (admin tools were moved out of settings with WB 2.7)
$admin = new admin('admintools', 'admintools');
$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=droplets';
$template_edit_link = ADMIN_URL .'/admintools/tool.php?tool=templateedit';

// file to delete?
if ( isset( $_GET['del'] ) ) {
    if ( $_GET['del'] !== 'all' ) {
        if ( file_exists( WB_PATH.'/modules/droplets/export/'.$_GET['del'] ) ) {
            unlink( WB_PATH.'/modules/droplets/export/'.$_GET['del'] );
        }
    }
    else {
        $backup_files = wb_find_backups( WB_PATH.'/modules/droplets/export/' );
        foreach ( $backup_files as $file ) {
            unlink( WB_PATH.'/modules/droplets/export/'.$file );
            echo header( "Location: ".ADMIN_URL."/admintools/tool.php?tool=droplets\n\n" );
        }
    }
}

$backup_files = wb_find_backups( WB_PATH.'/modules/droplets/export/' );

// no more files
if ( ! count( $backup_files ) > 0 ) {
    echo header( "Location: ".ADMIN_URL."/admintools/tool.php?tool=droplets\n\n" );
}

?>

<h4 style="margin: 0; border-bottom: 1px solid #DDD; padding-bottom: 5px;">
	<a href="<?php echo $admintool_link;?>"><?php echo $HEADING['ADMINISTRATION_TOOLS']; ?></a>
	->
	<a href="<?php echo $module_edit_link;?>">Droplets</a>
</h4>

<br /><br />[ <a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets">&laquo; <?php echo $TEXT['BACK'];?></a> ]<br /><br />

<button style="float: right; border: outset 1px #315e8d; padding: 5px; background-color: #e6eeee; width: 15em;"
        type="button"
        name="backup_mgmt"
        onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']?>', '<?php echo WB_URL; ?>/modules/droplets/manage_backups.php?del=all');">
        <?php echo $DR_TEXT['DELETE_ALL']; ?></button><br style="clear: right" /><br />

<?php

if ( count( $backup_files ) > 0 ) {
    // sort by name
    sort($backup_files);
    echo "<table class=\"droplets\" style=\"width: 100%;\">\n",
         "<tr><th>",$TEXT['NAME'],"</th><th>",$TEXT['SIZE']," (Byte)</th><th>",$TEXT['DATE'],"</th><th>",$TEXT['FILES'],"</th><th></th></tr>";
    foreach ( $backup_files as $file ) {
        // stat
        $stat  = stat(WB_PATH.'/modules/droplets/export/'.$file);
        // get zip contents
        $zip   = new PclZip(WB_PATH.'/modules/droplets/export/'.$file);
        $count = $zip->listContent();
        echo "<tr>",
             "<td><a href=\"#\" title=\"",
             implode( ", ", array_map( create_function('$cnt', 'return $cnt["filename"];'), $count ) ),
             "\"><img src=\"".WB_URL."/modules/droplets/img/list.png\" alt=\"\" /> $file</a></td>",
             "<td>", $stat['size'], "</td>",
             "<td>", strftime( '%c', $stat['ctime'] ), "</td>",
             "<td>", count($count), "</td>",
             "<td><a href=\"".WB_URL."/modules/droplets/export/$file\" title=\"Download\"><img src=\"".WB_URL."/modules/droplets/img/download.png\" alt=\"Download\" /></a>",
             "    <a href=\"javascript: confirm_link('".$TEXT['ARE_YOU_SURE']."', '".ADMIN_URL."/admintools/tool.php?tool=droplets&amp;recover=$file');\" title=\"Recover\"><img src=\"".WB_URL."/modules/droplets/img/import.png\" alt=\"Recover\" /></a>",
             "    <a href=\"javascript: confirm_link('".$TEXT['ARE_YOU_SURE']."', '".WB_URL."/modules/droplets/manage_backups.php?del=$file');\" title=\"Delete\"><img src=\"".THEME_URL."/images/delete_16.png\" alt=\"X\" /></a></td></tr>\n";
    }
    echo "</table>\n";
}
?>

<br /><br />[ <a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets">&laquo; <?php echo $TEXT['BACK'];?></a> ]<br /><br />
