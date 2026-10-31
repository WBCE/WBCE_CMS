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
	$query = $database->query("DESCRIBE $table $field");
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

// v0.23.7: clean messages stored by older versions (droplet syntax, script markup).
// Only affected rows are changed; their original data is kept in mod_miniform_data_backup.
require_once __DIR__.'/functions.php';
$sDataTable   = TABLE_PREFIX.'mod_miniform_data';
$sBackupTable = TABLE_PREFIX.'mod_miniform_data_backup';
// check each query on its own: $database->is_error() keeps earlier errors
$oCreate = $database->query("CREATE TABLE IF NOT EXISTS `$sBackupTable` ("
	. ' `message_id` INT NOT NULL DEFAULT \'0\','
	. ' `data` MEDIUMTEXT NOT NULL,'
	. ' `cleaned_when` INT NOT NULL DEFAULT \'0\','
	. ' PRIMARY KEY ( `message_id` ) '
	. ' )');
$iCleaned = 0;
$res = $oCreate->error() ? false : $database->query("SELECT `message_id`, `data` FROM `$sDataTable`");
if($res && !$res->error()) {
	while($row = $res->fetchRow(MYSQLI_ASSOC)) {
		$sClean = miniform_clean_stored_message($row['data']);
		if($sClean === $row['data']) continue;
		$iId = (int) $row['message_id'];
		// keep the first original only (repeated upgrades must not overwrite it)
		$oBackup = $database->query("INSERT IGNORE INTO `$sBackupTable` (`message_id`, `data`, `cleaned_when`) VALUES ($iId, '".$database->escapeString($row['data'])."', ".time().")");
		if($oBackup->error()) continue; // never change a message without backup
		$oUpdate = $database->query("UPDATE `$sDataTable` SET `data` = '".$database->escapeString($sClean)."' WHERE `message_id` = $iId");
		if(!$oUpdate->error()) $iCleaned++;
	}
}
if($iCleaned) echo "<br />MiniForm: $iCleaned stored message(s) cleaned, originals in ".$sBackupTable."<br />";

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