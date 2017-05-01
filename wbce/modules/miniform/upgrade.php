<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.10.0
 * @lastmodified    april 10, 2017
 *
 */


// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: index.php'));  

function mod_minform_db_add_field($field, $table, $desc) {
	global $database;
	$table = TABLE_PREFIX.$table;
	$query = $database->query("DESCRIBE $table '$field'");
	if(!$query || $query->numRows() == 0) { // add field
		$query = $database->query("ALTER TABLE $table ADD $field $desc");
	}
}
mod_minform_db_add_field("`use_recaptcha`"   ,"mod_miniform", "INT NOT NULL default '0'");
mod_minform_db_add_field("`recaptcha_key`"	  ,"mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");
mod_minform_db_add_field("`recaptcha_secret`","mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");
mod_minform_db_add_field("`remote_id`"		  ,"mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");
mod_minform_db_add_field("`remote_name`"	  ,"mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");

$path = WB_PATH.'/modules/miniform/';
if(file_exists($path.'new_frontend.css')) {
	if(!file_exists($path.'frontend.css')) {
		if(!rename($path.'new_frontend.css',$path.'frontend.css')) {
			echo "<h2>Error renaming frontend.css. Please rename new_frontend.css manually to frontend.css</h2>";
		}
	}
}

$files = scandir ( $path.'/defaults/');
foreach ( $files as $file ) {
	if ($file != "." && $file != "..") {
		if(!file_exists($path.'/templates/'.$file)) {
			if(!rename ( $path.'/defaults/'.$file, $path.'/templates/'.$file )) {
				echo "<h2>Error copying file: ".$file." to the templates folder. Please move the files from the defaults folder manually to the templates folder!</h2>";
			}
		}
	}
}
					
					



