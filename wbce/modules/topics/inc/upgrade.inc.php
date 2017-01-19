<?php

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_".$tablename."_cache`");

// topics table:
$query_topics = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." LIMIT 1");
$topic_fetch = $query_topics->fetchRow();	

	
// Add field authors to mod_topics
if(!isset($topic_fetch['authors'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."` ADD `authors` VARCHAR(255) NOT NULL DEFAULT ''")) {
		echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "authors" added successfully</div><br />';
	}
		echo '<div style="color:#e92a0b; font-weight:bold;">'.mysql_error().'</div><br />';
} else {
	echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "authors" exists, update not needed</div><br />';
}

if($database->is_error()) {
	echo ("OOPS, something went wrong. If it's a duplicate error then it's okay - it means that your database has already been modified.<br/>The error was: ".$database->get_error());
} else {
	echo ("SUCCESS: The required changes have been made to your database.");
}	

// Add field comments_count to mod_topics
if(!isset($topic_fetch['comments_count'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."` ADD `comments_count` INT NOT NULL DEFAULT '-1'")) {
		echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "comments_count" added successfully</div><br />';
	}
		echo '<div style="color:#e92a0b; font-weight:bold;">'.mysql_error().'</div><br />';
} else {
	echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "comments_count" exists, update not needed</div><br />';
}

if($database->is_error()) {
	echo ("OOPS, something went wrong. If it's a duplicate error then it's okay - it means that your database has already been modified.<br/>The error was: ".$database->get_error());
} else {
	echo ("SUCCESS: The required changes have been made to your database.");
}


//Settings table	

// Add various_values:
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_settings LIMIT 1");
$settings_fetch = $query_settings->fetchRow();	

	
// Add field various_values to mod_topics
if(!isset($settings_fetch['various_values'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `various_values` VARCHAR(255) NOT NULL DEFAULT '150,450,0,0,2,0,0,0,0,0'")) {
		echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "various_values" added successfully</div><br />';
	}
		echo '<div style="color:#e92a0b; font-weight:bold;">'.mysql_error().'</div><br />';
} else {
	echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "various_values" exists, update not needed</div><br />';
}

if($database->is_error()) {
	echo ("OOPS, something went wrong. If it's a duplicate error then it's okay - it means that your database has already been modified.<br/>The error was: ".$database->get_error());
} else {
	echo ("SUCCESS: The required changes have been made to your database.");
}

// Add field autoarchive
if(!isset($settings_fetch['autoarchive'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `autoarchive` VARCHAR(255) NOT NULL DEFAULT ''")) {
		echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "autoarchive" added successfully</div><br />';
	}
		echo '<div style="color:#e92a0b; font-weight:bold;">'.mysql_error().'</div><br />';
} else {
	echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "autoarchive" exists, update not needed</div><br />';
}

if($database->is_error()) {
	echo ("OOPS, something went wrong. If it's a duplicate error then it's okay - it means that your database has already been modified.<br/>The error was: ".$database->get_error());
} else {
	echo ("SUCCESS: The required changes have been made to your database.");
}

// Add field picture_values
if(!isset($settings_fetch['picture_values'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `picture_values` VARCHAR(255) NOT NULL DEFAULT '0,0,300,0,70,70,fbx'")) {
		echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "picture_values" added successfully</div><br />';
	}
		echo '<div style="color:#e92a0b; font-weight:bold;">'.mysql_error().'</div><br />';
} else {
	echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "picture_values" exists, update not needed</div><br />';
}

if($database->is_error()) {
	echo ("OOPS, something went wrong. If it's a duplicate error then it's okay - it means that your database has already been modified.<br/>The error was: ".$database->get_error());
} else {
	echo ("SUCCESS: The required changes have been made to your database.");
}

// Add field is_master_for
if(!isset($settings_fetch['is_master_for'])){
	if($database->query("ALTER TABLE `".TABLE_PREFIX."mod_".$tablename."_settings` ADD `is_master_for` VARCHAR(255) NOT NULL DEFAULT ''")) {
		echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "is_master_for" added successfully</div><br />';
	}
		echo '<div style="color:#e92a0b; font-weight:bold;">'.mysql_error().'</div><br />';
} else {
	echo '<div style="color:#0bb61f; font-weight:bold;">Database Field "is_master_for" exists, update not needed</div><br />';
}

if($database->is_error()) {
	echo ("OOPS, something went wrong. If it's a duplicate error then it's okay - it means that your database has already been modified.<br/>The error was: ".$database->get_error());
} else {
	echo ("SUCCESS: The required changes have been made to your database.");
}

//Create folders and copy example pics
$picpath = WB_PATH.MEDIA_DIRECTORY.'/'.$mod_dir.'-pictures';
make_dir($picpath);
$frompath = (WB_PATH.'/modules/'.$mod_dir.'/img/');
if (!file_exists($picpath.'/1.jpg')) { copy($frompath.'1.jpg', $picpath.'/1.jpg') ; }
if (!file_exists($picpath.'/2.jpg')) { copy($frompath.'2.jpg', $picpath.'/2.jpg') ; }
if (!file_exists($picpath.'/3.jpg')) { copy($frompath.'3.jpg', $picpath.'/3.jpg') ; }

$picpath = WB_PATH.MEDIA_DIRECTORY.'/'.$mod_dir.'-pictures/thumbs';
make_dir($picpath);
if (!file_exists($picpath.'/1.jpg')) { copy($frompath.'thumb1.jpg', $picpath.'/1.jpg') ; }
if (!file_exists($picpath.'/2.jpg')) { copy($frompath.'thumb2.jpg', $picpath.'/2.jpg') ; }
if (!file_exists($picpath.'/3.jpg')) { copy($frompath.'thumb3.jpg', $picpath.'/3.jpg') ; }

$picpath = WB_PATH.MEDIA_DIRECTORY.'/'.$mod_dir.'-pictures/zoom';
make_dir($picpath);
if (!file_exists($picpath.'/1.jpg')) { copy($frompath.'zoom1.jpg', $picpath.'/1.jpg') ; }
if (!file_exists($picpath.'/2.jpg')) { copy($frompath.'zoom2.jpg', $picpath.'/2.jpg') ; }
if (!file_exists($picpath.'/3.jpg')) { copy($frompath.'zoom3.jpg', $picpath.'/3.jpg') ; }



//Copy settings files
$mpath = WB_PATH.'/modules/'.$mod_dir.'/';
if (!file_exists($mpath.'module_settings.php')) { copy($mpath.'defaults/module_settings.default.php', $mpath.'module_settings.php') ; }
if (!file_exists($mpath.'frontend.css')) { copy($mpath.'defaults/frontend.default.css', $mpath.'frontend.css') ; }
if (!file_exists($mpath.'comment_frame.css')) { copy($mpath.'defaults/comment_frame.default.css', $mpath.'comment_frame.css') ; }
if (!file_exists($mpath.'frontend.js')) { copy($mpath.'defaults/frontend.default.js', $mpath.'frontend.js') ; }



?>