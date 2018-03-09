<?php
/**
 @file /modules/el_finder/connector.wbce.php
 @brief The WBCE connector for connecting whith elFinder

 @important The including script needs to take care of a granular access controll!        
*/

//no direct file access 
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// Make sure we include from wbce
if(!defined('WB_PATH')) header("Location: ../index.php",TRUE,301);

// Define Fileendings we dont want to see (the files , not just the endings)
$sForbiddenRegex="/(\." .str_replace(",","|\.", RENAME_FILES_ON_UPLOAD). ")$/";


// In wbce we have our own setting for Error reporting 
//error_reporting(E_ALL); // Set E_ALL for debuging

// load composer autoload before load elFinder autoload If you need composer
// No composer in wbce
//require './vendor/autoload.php';

// elFinder autoload
require 'autoload.php';
// ===============================================


// ===============================================
/**
    @brief Simple function to prevent uploads forbidden by WBCEs  RENAME_FILES_ON_UPLOAD constant.   

    @param string $sFileName When the connector calls this function and hands the filename to it.    
    @return boolean true if filename is OK  and false if not.
*/
function wbce_filenames_ok ($sFileName) {

    // Convert WBCE Setting to regex 
    $sForbidden = str_replace(",","|",RENAME_FILES_ON_UPLOAD);

    // check forbidden list 
    if (preg_match("/\.($sForbidden)$/i" , $sFileName)) 
        return false;
    
    // check if file contains at least a file extension .***
    if (preg_match('/^[^\.].*$/',$sFileName )) 
        return true;
    
    return false;   
}


/**
    @brief Simple function to demonstrate how to control file access using "accessControl" callback.

    This method will disable accessing files/folders starting from '.' (dot)
    Good idea we keep this in WBCE.

    @param  string    $attr    attribute name (read|write|locked|hidden)
    @param  string    $path    absolute file path
    @param  string    $data    value of volume option `accessControlData`
    @param  object    $volume  elFinder volume driver object
    @param  bool|null $isDir   path is directory (true: directory, false: file, null: unknown)
    @param  string    $relpath file path relative to volume root directory started with directory separator
    @return bool|null
*/
function access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}




// Set the parameters for connecting to elFinder
if (empty($_SESSION['HOME_FOLDER'])) {

    // Documentation for connector options:
    // https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
    $opts = array(
        'debug' => true,
        'roots' => array(
            // Items volume
            array(
                'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                'path'          => WB_PATH. '/media/',          // path to files (REQUIRED)
                'URL'           => WB_URL. '/media/',           // URL to files (REQUIRED)
                'quarantine'    => WB_PATH. '/var/modules/el_finder/.quarantine',  // Temporary directory for archive file extracting.
                'tmbPath'       => WB_PATH. '/var/modules/el_finder/.tmb',            // Tumbnail Directory
                'tmbURL'        => WB_URL.  '/var/modules/el_finder/.tmb',            // Tumbnail Directory
                'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too              
                'accessControl' => 'access',                    // disable and hide dot starting files (OPTIONAL) see "access" function above
                'acceptedName'  => 'wbce_filenames_ok',         // call this function to check filename 
				'attributes' => array(
					array(
						'pattern' => $sForbiddenRegex, //You can also set permissions for file types by adding, for example, .jpg inside pattern.
						'read'    => false,
						'write'   => false,
						'locked'  => true,
						'hidden'  => true
					)
				)
            )       
        )
    );
} 
else {
    $opts = array(
        'debug' => true,
        'roots' => array(
        
            // Items volume
            array(
				            'alias'        => "Home (".$_SESSION['HOME_FOLDER'].")",
                'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                'path'          => WB_PATH. '/media/'.$_SESSION['HOME_FOLDER'],         // path to files (REQUIRED)
                'URL'           => WB_URL. '/media/'.$_SESSION['HOME_FOLDER'],           // URL to files (REQUIRED)
                'quarantine'    => WB_PATH. '/var/modules/el_finder/.quarantine',  // Temporary directory for archive file extracting.
                'tmbPath'       => WB_PATH. '/var/modules/el_finder/.tmb',            // Tumbnail Directory
                'tmbURL'        => WB_URL.  '/var/modules/el_finder/.tmb',            // Tumbnail Directory
                'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too              
                'accessControl' => 'access',                    // disable and hide dot starting files (OPTIONAL) see "access" function above
                'acceptedName'  => 'wbce_filenames_ok',         // call this function to check filename 				
            ),     
            array(
                'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                'path'          => WB_PATH. '/media/',          // path to files (REQUIRED)
                'URL'           => WB_URL. '/media/',           // URL to files (REQUIRED)
                'quarantine'    => WB_PATH. '/var/modules/el_finder/.quarantine',  // Temporary directory for archive file extracting.
                'tmbPath'       => WB_PATH. '/var/modules/el_finder/.tmb',            // Tumbnail Directory
                'tmbURL'        => WB_URL.  '/var/modules/el_finder/.tmb',            // Tumbnail Directory
                'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too              
                'accessControl' => 'access',                    // disable and hide dot starting files (OPTIONAL) see "access" function above
                'acceptedName'  => 'wbce_filenames_ok',         // call this function to check filename 

				'attributes' => array(
					array(
						'pattern' => $sForbiddenRegex, //You can also set permissions for file types by adding, for example, .jpg inside pattern.
						'read'    => false,
						'write'   => false,
						'locked'  => true,
						'hidden'  => true
					)
				),

                'disabled'      => array(                       // Very limited access to main folder when user has a homedir set
                    'upload',
                    'mkdir', 
                    'mkfile',
                    'duplicate',
                    'rename', 
                    'rm', 
                    'cut', 
                    'copy', 
                    'archive', 
                    'extract', 
                    'resize', 
                    'paste', 
                    'edit')   
             )
        )
    );
}

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

