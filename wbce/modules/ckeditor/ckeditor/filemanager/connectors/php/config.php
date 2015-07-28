<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2010 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Configuration file for the File Manager Connector for PHP.
 */

global $Config ;

// SECURITY: You must explicitly enable this "connector". (Set it to "true").
// WARNING: don't just set "$Config['Enabled'] = true ;", you must be sure that only
//		authenticated users can access this file or use some kind of session checking.
$Config['Enabled'] = false ;

/**
*	SECURITY PATCH FOR WEBSITEBAKER (doc)
*	only enable PHP connector if user is authenticated to WB
*	and has at least permissions to view the WB MEDIA folder
*/
// include WB config.php file and admin class
require_once('../../../../../../config.php');
require_once(WB_PATH .'/framework/class.admin.php');

$wb_path = str_replace('\\','/', WB_PATH);
$wb_path = str_replace('//','/', WB_PATH);

// check if user is authenticated if WB and has permission to view MEDIA folder
$admin = new admin('Media', 'media_view', false, false);
if(($admin->get_permission('media_view') === true))
{
	// user allowed to view MEDIA folder -> enable PHP connector
	$Config['Enabled'] = true ;
	// allow actions to list folders and files
	$Config['ConfigAllowedCommands'] = array('GetFolders', 'GetFoldersAndFiles') ;
}

// Path to user files relative to the document root.
// $Config['UserFilesPath'] = '/userfiles/' ;
$Config['UserFilesPath'] = WB_URL.MEDIA_DIRECTORY.'/' ;
// use home folder of current user as document root if available
if(isset($_SESSION['HOME_FOLDER']) && file_exists($wb_path .MEDIA_DIRECTORY .$_SESSION['HOME_FOLDER'])){
   $Config['UserFilesPath'] = $Config['UserFilesPath'].$_SESSION['HOME_FOLDER'];
}

// Fill the following value it you prefer to specify the absolute path for the
// user files directory. Useful if you are using a virtual directory, symbolic
// link or alias. Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
// Attention: The above 'UserFilesPath' must point to the same directory.
// $Config['UserFilesAbsolutePath'] = '' ;

$Config['UserFilesAbsolutePath'] = $wb_path .MEDIA_DIRECTORY.'/' ;
// use home folder of current user as document root if available
if(isset($_SESSION['HOME_FOLDER']) && file_exists($wb_path .MEDIA_DIRECTORY .$_SESSION['HOME_FOLDER'])){
   $Config['UserFilesAbsolutePath'] = $Config['UserFilesAbsolutePath'].$_SESSION['HOME_FOLDER'].'/';
}
// Due to security issues with Apache modules, it is recommended to leave the
// following setting enabled.
$Config['ForceSingleExtension'] = true ;

// Perform additional checks for image files.
// If set to true, validate image size (using getimagesize).
$Config['SecureImageUploads'] = true;

// What the user can do with this connector.
// $Config['ConfigAllowedCommands'] = array('QuickUpload', 'FileUpload', 'GetFolders', 'GetFoldersAndFiles', 'CreateFolder') ;

/**
   Check WB permissions of the user/group for the MEDIA folder and
	enable only those FCKEditor commands the user has permissions for
*/
// check if user is allowed to upload files to the media directory
if(($admin->get_permission('media_upload') === true)) {
	// add actions to upload files to the MEDIA folder
	array_push($Config['ConfigAllowedCommands'], 'FileUpload', 'QuickUpload');
}

// check if user is allowed to create new folders in the media directory
if(($admin->get_permission('media_create') === true)) {
	// add action to create new folders in the MEDIA folder
	array_push($Config['ConfigAllowedCommands'], 'CreateFolder');
}

// Allowed Resource Types.
$Config['ConfigAllowedTypes'] = array('File', 'Image', 'Flash', 'Media') ;

// For security, HTML is allowed in the first Kb of data for files having the
// following extensions only.
$Config['HtmlExtensions'] = array("html", "htm", "xml", "xsd", "txt", "js") ;

// After file is uploaded, sometimes it is required to change its permissions
// so that it was possible to access it at the later time.
// If possible, it is recommended to set more restrictive permissions, like 0755.
// Set to 0 to disable this feature.
// Note: not needed on Windows-based servers.
$Config['ChmodOnUpload'] = defined('OCTAL_FILE_MODE') ? OCTAL_FILE_MODE : 0777 ;

// See comments above.
// Used when creating folders that does not exist.
$Config['ChmodOnFolderCreate'] = defined('OCTAL_DIR_MODE') ? OCTAL_DIR_MODE : 0777 ;

/*
	Configuration settings for each Resource Type

	- AllowedExtensions: the possible extensions that can be allowed.
		If it is empty then any file type can be uploaded.
	- DeniedExtensions: The extensions that won't be allowed.
		If it is empty then no restrictions are done here.

	For a file to be uploaded it has to fulfill both the AllowedExtensions
	and DeniedExtensions (that's it: not being denied) conditions.

	- FileTypesPath: the virtual folder relative to the document root where
		these resources will be located.
		Attention: It must start and end with a slash: '/'

	- FileTypesAbsolutePath: the physical path to the above folder. It must be
		an absolute path.
		If it's an empty string then it will be autocalculated.
		Useful if you are using a virtual directory, symbolic link or alias.
		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
		Attention: The above 'FileTypesPath' must point to the same directory.
		Attention: It must end with a slash: '/'

	 - QuickUploadPath: the virtual folder relative to the document root where
		these resources will be uploaded using the Upload tab in the resources
		dialogs.
		Attention: It must start and end with a slash: '/'

	 - QuickUploadAbsolutePath: the physical path to the above folder. It must be
		an absolute path.
		If it's an empty string then it will be autocalculated.
		Useful if you are using a virtual directory, symbolic link or alias.
		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
		Attention: The above 'QuickUploadPath' must point to the same directory.
		Attention: It must end with a slash: '/'

	 	NOTE: by default, QuickUploadPath and QuickUploadAbsolutePath point to
	 	"userfiles" directory to maintain backwards compatibility with older versions of FCKeditor.
	 	This is fine, but you in some cases you will be not able to browse uploaded files using file browser.
	 	Example: if you click on "image button", select "Upload" tab and send image
	 	to the server, image will appear in FCKeditor correctly, but because it is placed
	 	directly in /userfiles/ directory, you'll be not able to see it in built-in file browser.
	 	The more expected behaviour would be to send images directly to "image" subfolder.
	 	To achieve that, simply change
			$Config['QuickUploadPath']['Image']			= $Config['UserFilesPath'] ;
			$Config['QuickUploadAbsolutePath']['Image']	= $Config['UserFilesAbsolutePath'] ;
		into:
			$Config['QuickUploadPath']['Image']			= $Config['FileTypesPath']['Image'] ;
			$Config['QuickUploadAbsolutePath']['Image'] 	= $Config['FileTypesAbsolutePath']['Image'] ;

*/

/**
	APPLY MORE RESTRICTIVE SETTINGS FOR WEBSITE BAKER
	+ only allow file types: 	only textfiles (no PHP, Javascript or HTML files per default)
	+ only allows images type: bmp, gif, jpges, jpg and png
	+ only allows flash types: swf, flv (no fla ... flash action script per default)
	+ only allows media types: swf, flv, jpg, gif, jpeg, png, avi, mgp, mpeg
*/
$Config['AllowedExtensions']['File']			= array();
$Config['DeniedExtensions']['File']				= array('html','htm','php','php2','php3','php4','php5','phtml','pwml','inc','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','com','dll','vbs','js','reg','cgi','htaccess','asis') ;
$Config['FileTypesPath']['File']					= $Config['UserFilesPath'];
$Config['FileTypesAbsolutePath']['File']		= $Config['UserFilesAbsolutePath'] ;
$Config['QuickUploadPath']['File']				= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['File']	= $Config['UserFilesAbsolutePath'] ;

$Config['AllowedExtensions']['Image']			= array('bmp','gif','jpeg','jpg','png') ;
$Config['DeniedExtensions']['Image']			= array() ;
$Config['FileTypesPath']['Image'] 				= $Config['UserFilesPath'] ;
$Config['FileTypesAbsolutePath']['Image'] 	= $Config['UserFilesAbsolutePath'];
$Config['QuickUploadPath']['Image'] 			= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['Image']	= $Config['UserFilesAbsolutePath'] ;

$Config['AllowedExtensions']['Flash']			= array('swf','flv') ;
$Config['DeniedExtensions']['Flash']			= array() ;
$Config['FileTypesPath']['Flash']				= $Config['UserFilesPath'];
$Config['FileTypesAbsolutePath']['Flash'] 	= $Config['UserFilesAbsolutePath'];
$Config['QuickUploadPath']['Flash']				= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['Flash']	= $Config['UserFilesAbsolutePath'] ;

$Config['AllowedExtensions']['Media']			= array('swf','flv','jpg','gif','jpeg','png','avi','mpg','mpeg') ;
$Config['DeniedExtensions']['Media']			= array() ;
$Config['FileTypesPath']['Media']				= $Config['UserFilesPath'] . '' ;
$Config['FileTypesAbsolutePath']['Media']		= $Config['UserFilesAbsolutePath'];
$Config['QuickUploadPath']['Media']				= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['Media']	= $Config['UserFilesAbsolutePath'] ;

?>
