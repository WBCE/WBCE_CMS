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

//Prevent this file from being accessed directly
defined('WB_PATH') or exit("Cannot access this file directly");
global $module_version, $new_module_version;

require_once(WB_PATH.'/framework/functions.php');
$database = new database();

$dbtable = TABLE_PREFIX.'mod_sitemap'; 

//
// UPGRADE TO VERSION 4.0.0 or higher
// new DB columns have been integrated
// 
$result = 0;
if ($module_version < $new_module_version) {
	// Title: Upgrading to
	echo'<h3>SITEMAP - Upgrading to version '.$new_module_version.':</h3>';
	echo "<br /><b>Trying to rename database field '<i>loop</i>' to '<i>sitemaploop</i>'...</b><br />";
	// Get ITEMS table to see what needs to be added or modified
	$itemstable = $database->query("SELECT * FROM `".$dbtable."`");
	$items = $itemstable->fetchRow();
	if (!array_key_exists('sitemaploop', $items)){
		if ($database->query("ALTER TABLE `".$dbtable."` CHANGE `loop` `sitemaploop` TEXT NOT NULL")) {
			echo '<span class="good">Database field <i>loop</i> renamed to <i>sitemaploop</i> successfully</span><br />';
		} else { 
			echo '<span class="bad">'.$database->get_error().'</span><br />'; $result = 1;
		}
	} else { 
		echo '<span class="ok">Database field <i>sitemaploop</i> already exists, update not needed.</span><br />';
	}

	echo "<br /><b>Trying to add database field '<i>show_hidden</i>'...</b><br />";

	if (!array_key_exists('show_hidden', $items)){
		if ($database->query("ALTER TABLE `".$dbtable."` ADD `show_hidden` INT NOT NULL")) {
			echo '<span class="good">Database field <i>show_hidden</i> added successfully</span><br />';
		} else { 
			echo '<span class="bad">'.$database->get_error().'</span><br />'; $result = 1;
		}
	} else { 
		echo '<span class="ok">Database field <i>show_hidden</i> already exists, update not needed.</span><br />'; 
	}

	// ADD new fields for version 4.0.0
	if (!array_key_exists('show_settings', $items)){		
		echo "<br /><b>Trying to add database field '<i>show_settings</i>'...</b><br />";
		echo "<br /><b>Trying to add database field '<i>menus</i>'...</b><br />";
		echo "<br /><b>Trying to add database field '<i>layout</i>'...</b><br />";
		// add new columns to table
		if ($database->query(
			"ALTER TABLE `".$dbtable."` 
				ADD `show_settings` INT(1) NOT NULL DEFAULT '1', 
				ADD `menus` VARCHAR(30) NOT NULL DEFAULT '0',
				ADD `layout` varchar(128) NOT NULL DEFAULT '0'"
		)){
			echo '<span class="ok">DB fields <i>show_settings, menus, layout</i> added successfully.</span><br />';
		}else{
			echo '<span class="bad">DB fields can not be added.</span><br />';			
		}
	}else { 
		echo '<span class="ok">Database fields <i>show_settings, menus, layout</i> already exist, update not needed.</span><br />'; 
	}

}
// Setup styles to help id errors
?>
<style type="text/css">
.good {color: green;}
.bad  {color: red;}
.ok   {color: blue;}
.warn {color: yellow;}
</style>
<?php 