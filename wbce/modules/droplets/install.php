<?php

/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


	// global $admin;

	$msg = array();
	$sql  = 'DROP TABLE IF EXISTS `{TP}mod_droplets` ';
	if( !$database->query($sql) ) {
		$msg[] = $database->get_error();
	}

	$sql  = 'CREATE TABLE IF NOT EXISTS `{TP}mod_droplets` ( ';
	$sql .= '`id` INT NOT NULL auto_increment, ';
	$sql .= '`name` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL, ';
	$sql .= '`code` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL , ';
	$sql .= '`description` TEXT  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, ';
	$sql .= '`modified_when` INT NOT NULL default \'0\', ';
	$sql .= '`modified_by` INT NOT NULL default \'0\', ';
	$sql .= '`active` INT NOT NULL default \'0\', ';
	$sql .= '`admin_edit` INT NOT NULL default \'0\', ';
	$sql .= '`admin_view` INT NOT NULL default \'0\', ';
	$sql .= '`show_wysiwyg` INT NOT NULL default \'0\', ';
	$sql .= '`comments` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL, ';
	$sql .= 'PRIMARY KEY ( `id` ) ';
	$sql .= ') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

	if( !$database->query($sql) ) {
		$msg[] = $database->get_error();
	}

	//add all droplets from the droplet subdirectory
	$folder=opendir(WB_PATH.'/modules/droplets/example/.');
	$names = array();
	while ($file = readdir($folder)) {
		$ext=strtolower(substr($file,-4));
		if ($ext==".php"){
			if ($file<>"index.php" ) {
				$names[count($names)] = $file;
			}
		}
	}
	closedir($folder);

	foreach ($names as $dropfile)
	{
		$droplet = addslashes(getDropletCodeFromFile($dropfile));
		if ($droplet != "")
		{
			$description = "Example Droplet";
			$comments = "Example Droplet";
			$cArray = explode("\n",$droplet);
			if (isset($cArray[0]) && substr($cArray[0],0,3) == "//:") {
				$description = trim(substr($cArray[0],3));
				array_shift ( $cArray );
			}
			if (isset($cArray[0]) && substr($cArray[0],0,3) == "//:") {
				$comments = trim(substr($cArray[0],3));
				array_shift ( $cArray );
			}
			$droplet = implode ( "\n", $cArray );
			$name = substr($dropfile,0,-4);
			$modified_when = time();
			$modified_by = (method_exists($admin, 'get_user_id') && ($admin->get_user_id()!=null) ? $admin->get_user_id() : 1);
			$sql  = 'INSERT INTO `{TP}mod_droplets` SET ';
			$sql .= '`name` = \''.$name.'\', ';
			$sql .= '`code` = \''.$droplet.'\', ';
			$sql .= '`description` = \''.$description.'\', ';
			$sql .= '`comments` = \''.$comments.'\', ';
			$sql .= '`active` = 1, ';
			$sql .= '`modified_when` = '.$modified_when.', ';
			$sql .= '`modified_by` = '.$modified_by;
			if( !$database->query($sql) ) {
				$msg[] = $database->get_error();
			}
			// do not output anything if this script is called during fresh installation
			// if (method_exists($admin, 'get_user_id')) echo "Droplet import: $name<br/>";
		}
	}

	function getDropletCodeFromFile ( $dropletfile ) {
		$data = '';
		$filename = WB_PATH."/modules/droplets/example/".$dropletfile;
		if (file_exists($filename)) {
			$filehandle = fopen ($filename, "r");
			$data = fread ($filehandle, filesize ($filename));
			fclose($filehandle);
			// unlink($filename); doesnt work in unix
		}
		return $data;
	}
