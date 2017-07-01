<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.11.0
 * @lastmodified    june 30, 2017
 *
 */


if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
	
// Create tables
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_miniform`");
$mod_miniform = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_miniform` ('
	. ' `section_id` INT NOT NULL DEFAULT \'0\','
	. ' `email` VARCHAR(128) NOT NULL DEFAULT \'\',' 
	. ' `subject` VARCHAR(128) NOT NULL DEFAULT \'\',' 
	. ' `template` VARCHAR(64) NOT NULL DEFAULT \'form\',' 
	. ' `successpage` INT NOT NULL DEFAULT \'0\',' 
	. ' `use_ajax` INT NOT NULL DEFAULT \'1\',' 
	. ' `use_recaptcha` INT NOT NULL DEFAULT \'0\',' 
	. ' `recaptcha_key` VARCHAR(64) NOT NULL DEFAULT \'\',' 
	. ' `recaptcha_secret` VARCHAR(64) NOT NULL DEFAULT \'\',' 
	. ' `remote_id` VARCHAR(64) NOT NULL DEFAULT \'\',' 
	. ' `remote_name` VARCHAR(64) NOT NULL DEFAULT \'\',' 
	. ' PRIMARY KEY ( `section_id` ) '
	. ' )';
$database->query($mod_miniform);

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_miniform_data`");
$mod_miniformdata = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_miniform_data` ('
	. ' `message_id` INT NOT NULL NOT NULL auto_increment,'
	. ' `section_id` INT NOT NULL DEFAULT \'0\','
	. ' `data` TEXT NOT NULL,' 
	. ' `submitted_when` INT NOT NULL DEFAULT \'0\',' 
	. ' PRIMARY KEY ( `message_id` ) '
	. ' )';
$database->query($mod_miniformdata);


$path = WB_PATH.'/modules/miniform/';
if(file_exists($path.'new_frontend.css')) {
	if(!rename($path.'new_frontend.css',$path.'frontend.css')) {
		echo "<h2>Error renaming frontend.css. Please rename new_frontend.css manually to frontend.css</h2>";
	}
}


$files = scandir ( $path.'/defaults/');
foreach ( $files as $file ) {
	if ($file != "." && $file != "..") {
		if(!rename ( $path.'/defaults/'.$file, $path.'/templates/'.$file )) {
			echo "<h2>Error copying file: ".$file." to the templates folder. Please move the files from the defaults folder manually to the templates folder!</h2>";
		}
	}
}

