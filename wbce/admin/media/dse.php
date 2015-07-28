<?php
if (!defined('WB_PATH')) die(header('Location: index.php'));

/*
$DB_HOST = DB_HOST;
$DB_NAME = DB_NAME ;
$DB_USER = DB_USERNAME;
$DB_PASS = DB_PASSWORD;
$link = mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
mysql_select_db ( $DB_NAME, $link );
*/
require_once(WB_PATH.'/framework/DseTwo.php');

$CachePath = WB_PATH.'/temp/mediacache';
// $ListFile = ADMIN_PATH.'/media/MediaBlackList'; // WhiteList   BlackList
$ListFile = ADMIN_PATH.'/media/MediaWhiteList'; // WhiteList   BlackList

$link = $database->db_handle;
$Dse = new DseTwo();
$Dse->db_handle = $link;
$Dse->db_name = DB_NAME;
$Dse->base_dir = WB_PATH.MEDIA_DIRECTORY;
$Dse->table_prefix = TABLE_PREFIX;
$Dse->cache_dir = $CachePath;

$Dse->addControllList($ListFile, DseTwo::USE_WHITELIST );  // $type const USE_ALL / USE_BLACKLIST / USE_WHITELIST

if(!empty($directory)) {
	$usedFiles = $Dse->getMatchesFromDir( $directory, DseTwo::RETURN_USED);
}

