<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
    require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
    throw new IllegalFileException();
}

// global $admin;

$msg = [];
$sql  = 'DROP TABLE IF EXISTS `{TP}mod_droplets`';
if (!$database->query($sql)) {
    $msg[] = $database->get_error();
}

$sql  = "CREATE TABLE IF NOT EXISTS `{TP}mod_droplets` ( 
    `id`            INT NOT NULL auto_increment, 
    `name`          VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL, 
    `code`          LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL , 
    `description`   TEXT  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, 
    `modified_when` INT NOT NULL default '0', 
    `modified_by`   INT NOT NULL default '0', 
    `active`        INT NOT NULL default '0', 
    `admin_edit`    INT NOT NULL default '0', 
    `admin_view`    INT NOT NULL default '0', 
    `show_wysiwyg`  INT NOT NULL default '0', 
    `comments`      TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL, 
    PRIMARY KEY ( `id` ) 
    ) 
    ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
if (!$database->query($sql)) {
    $msg[] = $database->get_error();
}

// add all droplets from the droplet subdirectory
$folder=opendir(WB_PATH.'/modules/droplets/example/.');
$names = array();
while ($file = readdir($folder)) {
    $ext=strtolower(substr($file, -4));
    if ($ext==".php") {
        if ($file<>"index.php") {
            $names[count($names)] = $file;
        }
    }
}
closedir($folder);

foreach ($names as $dropfile) {
    $droplet = addslashes(getDropletCodeFromFile($dropfile));
    if ($droplet != "") {
        $description = "Example Droplet";
        $comments = "Example Droplet";
        $cArray = explode("\n", $droplet);
        if (isset($cArray[0]) && substr($cArray[0], 0, 3) == "//:") {
            $description = trim(substr($cArray[0], 3));
            array_shift($cArray);
        }
        if (isset($cArray[0]) && substr($cArray[0], 0, 3) == "//:") {
            $comments = trim(substr($cArray[0], 3));
            array_shift($cArray);
        }
        
        $aDroplet = [
            'name'        => substr($dropfile, 0, -4),
            'code'        => implode("\n", $cArray),
            'description' => $description,
            'comments'    => $comments,
            'active'      => 1,
            'modified_when' => time(),
            'modified_by' => (method_exists($admin, 'get_user_id') && ($admin->get_user_id()!=null) ? $admin->get_user_id() : 1),
        ];
        
        if (!$database->insertRow('{TP}mod_droplets', $aDroplet)) {
            $msg[] = $database->get_error();
        }
        // do not output anything if this script is called during fresh installation
        // if (method_exists($admin, 'get_user_id')) echo "Droplet import: $name<br/>";
    }
}

// install OpF Filter 
// check whether outputfilter-module is installed
if(file_exists($sOpfFile = WB_PATH.'/modules/outputfilter_dashboard/functions.php')) {
    require_once $sOpfFile;
  
    if(opf_is_registered('Droplets')){
        // unregister old filter if already registered 
        opf_unregister_filter('Droplets');
        rm_full_dir(WB_PATH.'/modules/mod_opf_droplets');
    }

    // install filter
    return opf_register_filter(array(
        'name'     => 'Droplets',
        'type'     => OPF_TYPE_PAGE,
        'file'     => '{SYSVAR:WB_PATH}/modules/droplets/opf_filter_droplets.php',
        'funcname' => 'opff_droplets',
        'desc' => array(
				'EN' => "This filter is needed for the replacement of droplet calls (terms in double brackets) in the backend by the code which should be executed in the frontend output.",
				'DE' => "Dieser Filter wird benötigt, um Droplet-Aufrufe im Backend (Ausdrücke in doppelten eckigen Klammern) im Frontend durch den jeweiligen auszuführenden Code zu ersetzen."
			),
        'active'   => 1,
        'allowedit' => 0,
        'pages_parent' => 'all, backend, search'
    ))
    && opf_move_up_before('Droplets');  // move up to the top
        
    Settings::Set('opf_droplets', 1, false);
    Settings::Set('opf_droplets_be', 1, false);
}


function getDropletCodeFromFile($dropletfile)
{
    $data = '';
    $filename = WB_PATH."/modules/droplets/example/".$dropletfile;
    if (file_exists($filename)) {
        $filehandle = fopen($filename, "r");
        $data = fread($filehandle, filesize($filename));
        fclose($filehandle);
        // unlink($filename); doesnt work in unix
    }
    return $data;
}
