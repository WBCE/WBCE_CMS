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

$dbtable = TABLE_PREFIX.'mod_sitemap';

//Prevent this file from being accessed directly
if (defined('WB_PATH') == false) {
	exit("Cannot access this file directly");
}

 
$database = new database();

// Setup styles to help id errors
echo'
<style type="text/css">
.good {color: green;}
.bad  {color: red;}
.ok   {color: blue;}
.warn {color: yellow;}
</style>
';

// Version to be installed is the same or older than currently installed version
if ($module_version >= $new_module_version) {
	echo '<span class="bad">';
	$admin->print_error($MESSAGE['GENERIC']['ALREADY_INSTALLED']);
	echo '</span><br />';
	return;
}
// UPGRADE TO VERSION 3.1.1
// ************************

$result = 0;
if ($module_version < $new_module_version) {
	// Title: Upgrading to
	echo'<h3>SITEMAP - Upgrading to version '.$new_module_version.':</h3>';

	// Get ITEMS table to see what needs to be created or modified
	$itemstable = $database->query("SELECT * FROM `".$dbtable."`");
	$items = $itemstable->fetchRow();

  echo "<BR><B>Trying to rename database field '<i>loop</i>' to '<i>sitemaploop</i>'...</B><BR>";

  if (!array_key_exists('sitemaploop', $items)){
  		if ($database->query("ALTER TABLE `".$dbtable."` CHANGE `loop` `sitemaploop` TEXT NOT NULL")) {
  			echo '<span class="good">Database field <i>loop</i> renamed to <i>sitemaploop</i> successfully</span><br />';
  		} else { echo '<span class="bad">'.mysql_error().'</span><br />'; $result = 1;}
  } else { echo '<span class="ok">Database field <i>sitemaploop</i> already exists, update not needed.</span><br />';}

  echo "<BR><B>Trying to add database field '<i>show_hidden</i>'...</B><BR>";

  if (!array_key_exists('show_hidden', $items)){
  		if ($database->query("ALTER TABLE `".$dbtable."` ADD `show_hidden` INT NOT NULL")) {
  			echo '<span class="good">Database field <i>show_hidden</i> added successfully</span><br />';
  		} else { echo '<span class="bad">'.mysql_error().'</span><br />'; $result = 1;}
  } else { echo '<span class="ok">Database field <i>show_hidden</i> already exists, update not needed.</span><br />'; }

  if($result == 1) die();
}
?>