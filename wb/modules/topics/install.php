<?php

// $Id: install.php 707 2008-02-18 18:40:08Z doc $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

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

if(!defined('WB_URL')) { die(); }

global $database;
global $admin;

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."`");
$mod_topics = 'CREATE TABLE `'.TABLE_PREFIX.'mod_'.$tablename.'` ( '
      . '`topic_id` INT NOT NULL AUTO_INCREMENT,'
      . '`section_id` INT NOT NULL DEFAULT \'0\','
      . '`page_id` INT NOT NULL DEFAULT \'0\','
	  . '`groups_id` VARCHAR(255) NOT NULL DEFAULT \'\',' //new field

      . '`active` TINYINT NOT NULL DEFAULT \'0\','
      . '`hascontent` TINYINT NOT NULL DEFAULT \'0\','
      . '`published_when` INT NOT NULL DEFAULT \'0\','
      . '`published_until` INT NOT NULL DEFAULT \'0\','
      . '`posted_first` INT NOT NULL DEFAULT \'0\','
      . '`posted_modified` INT NOT NULL DEFAULT \'0\','
      . '`posted_by` INT NOT NULL DEFAULT \'0\','
      . '`modified_by` TEXT NOT NULL ,'
      . '`authors` TEXT NOT NULL ,'

      . '`position` INT NOT NULL DEFAULT \'0\','
      . '`link` TEXT NOT NULL ,'

      . '`title` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`short_description` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`description` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`keywords` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`picture` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`is_master_for` VARCHAR(255) NOT NULL DEFAULT \'\','

      . '`content_short` TEXT NOT NULL ,'
      . '`content_long` LONGTEXT NOT NULL ,'
      . '`content_extra` TEXT NOT NULL ,'

      . '`commenting` TINYINT NOT NULL DEFAULT \'0\','
      . '`see_also` VARCHAR(255) NOT NULL DEFAULT \'\','

      . '`topic_score` INT NOT NULL DEFAULT \'0\','
      . '`comments_count` INT NOT NULL DEFAULT \'-1\','

      . '`txtr1` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`txtr2` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`txtr3` VARCHAR(255) NOT NULL DEFAULT \'\','

      . '`pnsa_cache` TEXT NOT NULL ,'

      . 'PRIMARY KEY (topic_id)'
      . ' )';
$database->query($mod_topics);


$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_comments`");
$mod_topics = 'CREATE TABLE `'.TABLE_PREFIX.'mod_'.$tablename.'_comments` ( '
      . '`comment_id` INT NOT NULL AUTO_INCREMENT,'
      . '`topic_id` INT NOT NULL DEFAULT \'0\','
      . '`active` INT NOT NULL DEFAULT \'0\','
	  . '`commentextra` VARCHAR(255) NOT NULL DEFAULT \'\',' //new field
      . '`name` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`email` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`website` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`show_link` INT NOT NULL DEFAULT \'0\','
      . '`comment` TEXT NOT NULL ,'
      . '`commented_when` INT NOT NULL DEFAULT \'0\','
      . '`commented_by` INT NOT NULL DEFAULT \'0\','
      . 'PRIMARY KEY (comment_id)'
            . ' )';
$database->query($mod_topics);

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_settings`");
$mod_topics = 'CREATE TABLE `'.TABLE_PREFIX.'mod_'.$tablename.'_settings` ( '
      . '`section_id` INT NOT NULL DEFAULT \'0\','
      . '`get_settings_from` INT NOT NULL DEFAULT \'0\','
      . '`page_id` INT NOT NULL DEFAULT \'0\','

      . '`section_title` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`section_description` TEXT NOT NULL ,'
      . '`is_master_for` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`sort_topics` INT NOT NULL DEFAULT \'0\','
      . '`topics_per_page` INT NOT NULL DEFAULT \'0\','
      . '`use_timebased_publishing` TINYINT NOT NULL DEFAULT \'0\','
      . '`autoarchive` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`various_values` VARCHAR(255) NOT NULL DEFAULT \'150,450,0,0,2,0,0,0,0,0\','

      . '`picture_dir` VARCHAR(255) NOT NULL DEFAULT \'\','
      . '`picture_values` VARCHAR(255) NOT NULL DEFAULT \'0,0,300,0,70,70,fbx\','

      . '`header` TEXT NOT NULL ,'
      . '`topics_loop` TEXT NOT NULL ,'
      . '`footer` TEXT NOT NULL ,'

      . '`topic_header` TEXT NOT NULL,'
      . '`topic_footer` TEXT NOT NULL,'
      . '`topic_block2` TEXT NOT NULL,'
      . '`pnsa_string` TEXT NOT NULL,'
      . '`pnsa_max` INT NOT NULL DEFAULT \'4\','

      . '`commenting` TINYINT NOT NULL DEFAULT \'0\','
      . '`default_link` TINYINT NOT NULL DEFAULT \'0\','
      . '`use_captcha` TINYINT NOT NULL DEFAULT \'0\','
      . '`sort_comments` TINYINT NOT NULL DEFAULT \'0\','

      . '`comments_header` TEXT NOT NULL,'
      . '`comments_loop` TEXT NOT NULL,'
      . '`comments_footer` TEXT NOT NULL,'

      . 'PRIMARY KEY (section_id)'
            . ' )';
$database->query($mod_topics);

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
    ") ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
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
    ") ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
if (!$database->query($SQL))
  $admin->print_error($database->get_error());

// Make topics post access files dir
require_once(WB_PATH.'/framework/functions.php');
if(make_dir(WB_PATH.PAGES_DIRECTORY.'/'.$tablename)) {
    // Add a index.php file to prevent directory spoofing
    $content = "<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

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

header('Location: ../');
?>";



  $handle = fopen(WB_PATH.PAGES_DIRECTORY.'/'.$tablename.'/index.php', 'w');
  fwrite($handle, $content);
  fclose($handle);
  change_mode(WB_PATH.PAGES_DIRECTORY.'/'.$tablename.'/index.php', 'file');
}


//Create folders and copy example pics
$picpath = WB_PATH.MEDIA_DIRECTORY.'/'.$tablename.'-pictures';
make_dir($picpath);
$frompath = (WB_PATH.'/modules/'.$mod_dir.'/img/');
if (!file_exists($picpath.'/1.jpg')) { copy($frompath.'1.jpg', $picpath.'/1.jpg') ; }
if (!file_exists($picpath.'/2.jpg')) { copy($frompath.'2.jpg', $picpath.'/2.jpg') ; }
if (!file_exists($picpath.'/3.jpg')) { copy($frompath.'3.jpg', $picpath.'/3.jpg') ; }

$picpath = WB_PATH.MEDIA_DIRECTORY.'/'.$tablename.'-pictures/thumbs';
make_dir($picpath);
if (!file_exists($picpath.'/1.jpg')) { copy($frompath.'thumb1.jpg', $picpath.'/1.jpg') ; }
if (!file_exists($picpath.'/2.jpg')) { copy($frompath.'thumb2.jpg', $picpath.'/2.jpg') ; }
if (!file_exists($picpath.'/3.jpg')) { copy($frompath.'thumb3.jpg', $picpath.'/3.jpg') ; }

$picpath = WB_PATH.MEDIA_DIRECTORY.'/'.$tablename.'-pictures/zoom';
make_dir($picpath);
if (!file_exists($picpath.'/1.jpg')) { copy($frompath.'zoom1.jpg', $picpath.'/1.jpg') ; }
if (!file_exists($picpath.'/2.jpg')) { copy($frompath.'zoom2.jpg', $picpath.'/2.jpg') ; }
if (!file_exists($picpath.'/3.jpg')) { copy($frompath.'zoom3.jpg', $picpath.'/3.jpg') ; }


//Copy settings files
$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
if (!file_exists($mpath.'module_settings.php')) { copy($mpath.'defaults/module_settings.default.php', $mpath.'module_settings.php') ; }
if (!file_exists($mpath.'frontend.css')) { copy($mpath.'defaults/frontend.default.css', $mpath.'frontend.css') ; }
//if (!file_exists($mpath.'comment_frame.css')) { copy($mpath.'defaults/comment_frame.default.css', $mpath.'comment_frame.css') ; }
if (!file_exists($mpath.'frontend.js')) { copy($mpath.'defaults/frontend.default.js', $mpath.'frontend.js') ; }


// install or upgrade droplets
if (file_exists(WB_PATH.'/modules/droplets/functions.inc.php')) {
  include_once(WB_PATH.'/modules/droplets/functions.inc.php');
}

if (!function_exists('wb_unpack_and_import')) {
  function wb_unpack_and_import($temp_file, $temp_unzip) {
    global $admin, $database;

    // Include the PclZip class file
    require_once (WB_PATH . '/include/pclzip/pclzip.lib.php');

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