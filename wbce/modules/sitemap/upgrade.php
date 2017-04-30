<?php
/*
 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2010, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


//Prevent this file from being accessed directly
defined('WB_PATH') or exit("Cannot access this file directly");

require_once(WB_PATH.'/framework/functions.php');
$database = new database();

$dbtable = TABLE_PREFIX.'mod_sitemap'; 
// Version to be installed is the same or older than currently installed version
if ($module_version >= $new_module_version) {
	echo '<span class="bad">';
	$admin->print_error($MESSAGE['GENERIC']['ALREADY_INSTALLED']);
	echo '</span><br />';
	return;
}

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