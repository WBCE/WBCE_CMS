<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

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

//require('../../config.php');

if (!defined('WB_PATH')) { die('Sopperlott!'); }

global $database;
global $admin;

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;

$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$mod_dir);
$fetch = $query->fetchRow();	
// Add field groups_id
if(!isset($fetch['groups_id'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$mod_dir."` ADD `groups_id` VARCHAR(255) NOT NULL DEFAULT ''")) {
		echo '<span class="good">Database Field groups_id added successfully</span><br />';
	}
		echo '<span class="bad">'.$database->error().'</span><br />';
} else {
	echo '<span class="ok">Database Field groups_id exists, update not needed</span><br />';
}
	
$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$mod_dir.'_comments');
$fetch = $query->fetchRow();	
// Add field commentextra
if(!isset($fetch['commentextra'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$mod_dir."_comments` ADD `commentextra` VARCHAR(255) NOT NULL DEFAULT ''")) {
		echo '<span class="good">Database Field commentextra added successfully</span><br />';
	}
		echo '<span class="bad">'.$database->error().'</span><br />';
} else {
	echo '<span class="ok">Database Field commentextra exists, update not needed</span><br />';
}	
	

// create the RSS count table
$SQL = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_topics_rss_count` ( ".
    "`id` INT(11) NOT NULL AUTO_INCREMENT, ".
    "`section_id` INT(11) NOT NULL DEFAULT '-1', ".
    "`md5_ip` VARCHAR(32) NOT NULL DEFAULT '', ".
    "`count` INT(11) NOT NULL DEFAULT '0', ".
    "`date` DATE NOT NULL DEFAULT '0000-00-00', ".
    "`timestamp` TIMESTAMP, ".
    "PRIMARY KEY (`id`), ".
    "KEY (`md5_ip`, `date`) ".
    ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
if (!$database->query($SQL))
  $admin->print_error($database->get_error());

// create the RSS statistics table
$SQL = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."mod_topics_rss_statistic` ( ".
    "`id` INT(11) NOT NULL AUTO_INCREMENT, ".
    "`section_id` INT(11) NOT NULL DEFAULT '-1', ".
    "`date` DATE NOT NULL DEFAULT '0000-00-00', ".
    "`callers` INT(11) NOT NULL DEFAULT '0', ".
    "`views` INT(11) NOT NULL DEFAULT '0', ".
    "`timestamp` TIMESTAMP, ".
    "PRIMARY KEY (`id`), ".
    "KEY (`date`) ".
    ") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
if (!$database->query($SQL))
  $admin->print_error($database->get_error());

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;
require_once(WB_PATH.'/modules/'.$mod_dir.'/inc/upgrade.inc.php');

// install or upgrade droplets
if (file_exists(WB_PATH.'/modules/droplets/functions.inc.php')) {
  include_once(WB_PATH.'/modules/droplets/functions.inc.php');
}

if (!function_exists('wb_unpack_and_import')) {
  function wb_unpack_and_import($temp_file, $temp_unzip) {
    global $admin, $database;

    // Include the PclZip class file
    require_once (WB_PATH . '/include/pclzip/pclzip.lib.php');
    $imports= array();
    $errors = array();
    $count = 0;
    $archive = new PclZip($temp_file);
    $list = $archive->extract(PCLZIP_OPT_PATH, $temp_unzip);
    // now, open all *.php files and search for the header;
    // an exported droplet starts with "//:"
    if (false !== ($dh = opendir($temp_unzip))) {
      while (false !== ($file = readdir($dh))) {
        if ($file != "." && $file != "..") {
          if (preg_match('/^(.*)\.php$/i', $file, $name_match)) {
            // Name of the Droplet = Filename
            $name = $name_match[1];
            // Slurp file contents
            $lines = file($temp_unzip . '/' . $file);
            // First line: Description
            if (preg_match('#^//\:(.*)$#', $lines[0], $match)) {
              $description = $match[1];
            }
            // Second line: Usage instructions
            if (preg_match('#^//\:(.*)$#', $lines[1], $match)) {
              $usage = addslashes($match[1]);
            }
            // Remaining: Droplet code
            $code = implode('', array_slice($lines, 2));
            // replace 'evil' chars in code
            $tags = array(
                '<?php',
                '?>',
                '<?'
            );
            $code = addslashes(str_replace($tags, '', $code));
            // Already in the DB?
            $stmt = 'INSERT';
            $id = NULL;
            $found = $database->get_one("SELECT * FROM " . TABLE_PREFIX . "mod_droplets WHERE name='$name'");
            if ($found && $found > 0) {
              $stmt = 'REPLACE';
              $id = $found;
            }
            // execute
            $result = $database->query("$stmt INTO " . TABLE_PREFIX . "mod_droplets VALUES('$id','$name','$code','$description','" . time() . "','" . $admin->get_user_id() . "',1,0,0,0,'$usage')");
            if (!$database->is_error()) {
              $count++;
              $imports[$name] = 1;
            }
            else {
              $errors[$name] = $database->get_error();
            }
          }
        }
      }
      closedir($dh);
    }
    return array(
        'count' => $count,
        'errors' => $errors,
        'imported' => $imports
    );
  } // function wb_unpack_and_import()
}
// install the droplet(s)
wb_unpack_and_import(WB_PATH.'/modules/topics/droplets/droplet_topics_rss_statistic.zip', WB_PATH . '/temp/unzip/');

?>
