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
 * @version         0.15.0
 * @lastmodified    April 30, 2019
 *
 */


// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: index.php'));  

function _db_add_field($field, $table, $desc) {
	global $database;
	$table = TABLE_PREFIX.$table;
	$query = $database->query("DESCRIBE $table '$field'");
	if(!$query || $query->numRows() == 0) { // add field
		$query = $database->query("ALTER TABLE $table ADD $field $desc");
	}
}
_db_add_field("`use_ajax`"   	  ,"mod_miniform", "INT NOT NULL default '1'");
_db_add_field("`use_recaptcha`"   ,"mod_miniform", "INT NOT NULL default '0'");
_db_add_field("`recaptcha_key`"	  ,"mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");
_db_add_field("`recaptcha_secret`","mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");
_db_add_field("`remote_id`"		  ,"mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");
_db_add_field("`remote_name`"	  ,"mod_miniform", "VARCHAR(64) NOT NULL DEFAULT ''");
_db_add_field("`emailfrom`"	  	  ,"mod_miniform", "VARCHAR(128) NOT NULL DEFAULT ''");
_db_add_field("`confirm_user`"	  ,"mod_miniform", "INT NOT NULL default '0'");
_db_add_field("`confirm_subject`" ,"mod_miniform", "VARCHAR(255) NOT NULL DEFAULT ''");
_db_add_field("`disable_tls`"	  ,"mod_miniform", "INT NOT NULL default '0'");
_db_add_field("`no_store`"	  	  ,"mod_miniform", "INT NOT NULL default '0'");
_db_add_field("`user_id`"		  ,"mod_miniform_data", "INT NOT NULL default '0'");
_db_add_field("`guid`"	  		  ,"mod_miniform_data", "VARCHAR(64) NOT NULL DEFAULT ''");
_db_add_field("`session_data`"	  ,"mod_miniform_data", "MEDIUMTEXT NOT NULL");

$path = WB_PATH.'/modules/miniform/';
if(file_exists($path.'new_frontend.css')) {
	if(!file_exists($path.'frontend.css')) {
		if(!rename($path.'new_frontend.css',$path.'frontend.css')) {
			echo "<h2>Error renaming frontend.css. Please rename new_frontend.css manually to frontend.css</h2>";
		}
	}
}
/*
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
*/