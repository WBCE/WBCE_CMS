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

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$debug = false;

if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

/**
 *	Function called by parent, default by the wysiwyg-module
 *	@param	string	The name of the textarea to watch
 *	@param	mixed	The "id" - some other modules handel this param differ
 *	@param	string	Optional the width, default "100%" of given space.
 *	@param	string	Optional the height of the editor - default is '250px'
 */
function show_wysiwyg_editor($name, $id, $content, $toolbar = false) {
	global $database,$admin;

	$modAbsPath = str_replace('\\','/',dirname(__FILE__));
	$ckeAbsPath = $modAbsPath.'/ckeditor/';
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$realPath = str_replace('\\','/',dirname($_SERVER['SCRIPT_FILENAME']));
	} else {
		/** realpath - Returns canonicalized absolute pathname */
		$realPath = str_replace('\\','/',realpath( './' )) ;
	}

	$selfPath = str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']));
	$documentRoot = str_replace('\\','/',realpath(substr($realPath, 0, strlen($realPath) - strlen($selfPath))));
	$tplAbsPath = str_replace('\\','/',$documentRoot.'/templates');
	$tplRelPath = str_replace($documentRoot,'',$tplAbsPath);

	$modRelPath = str_replace($documentRoot,'',$modAbsPath);
	$ckeRelPath = $modRelPath.'/ckeditor/';

	$url = parse_url(WB_URL);
	$url['path'] = (isset($url['path']) ? $url['path'] : '');
	$ModPath = str_replace($url['path'],'',$modRelPath);
	$ckeModPath = str_replace($url['path'],'',$ckeRelPath);
	$tplPath = str_replace($url['path'],'',$tplRelPath);

	/**
	 *	Create new CKeditor instance.
	 *	But first - we've got to revamp this pretty old class a little bit.
	 */
	require_once ( $modAbsPath.'/wbce_ckeditor.php' ); // $ckeAbsPath ends with /
	$ckeditor = new CKEditorPlus( $ckeRelPath );

	$temp = '';
	if (isset($admin->page_id)) {
		$query = "SELECT `template` from `".TABLE_PREFIX."pages` where `page_id`='".(int)$page_id."'";
		$temp = $database->get_one( $query );
	}
	$templateFolder = ($temp == "") ? DEFAULT_TEMPLATE : $temp;
	$ckeditor->setTemplatePath($templateFolder);

	/**	Looking for the styles */
	$ckeditor->resolve_path(
		'contentsCss',
		$tplPath.'/wb_config/editor.css',
		$ModPath.'/wb_config/editor.css'
	);

	/**	Looking for the editor.styles at all ... */
	$ckeditor->resolve_path(
		'stylesSet',
		$tplPath.'/wb_config/editor.styles.js',
		$ModPath.'/wb_config/editor.styles.js',
		'wb:'
	);

	/**	The list of templates definition files to load. */
	$ckeditor->resolve_path(
		'templates_files',
		$tplPath.'/wb_config/editor.templates.js',
		$ModPath.'/wb_config/editor.templates.js'
	);

	/**	Bugfix for the template files as the ckeditor want an array instead a string ... */
	$ckeditor->config['templates_files'] = array($ckeditor->config['templates_files']);

	/**	The filebrowser are called in the include, because later on we can make switches, use WB_URL and so on */
	$connectorPath = $ckeditor->basePath.'filemanager/connectors/php/connector.php';
	$ckeditor->config['filebrowserBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Connector='.$connectorPath;
	$ckeditor->config['filebrowserImageBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Image&Connector='.$connectorPath;
	$ckeditor->config['filebrowserFlashBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Flash&Connector='.$connectorPath;

	/**	The Uploader has to be called, too. */
	$ckeditor->config['uploader'] = false; // disabled for security reasons
	if($ckeditor->config['uploader']==true) {
		$uploadPath = $ckeditor->basePath.'filemanager/connectors/php/upload.php?Type=';
		$ckeditor->config['filebrowserUploadUrl'] = $uploadPath.'File';
		$ckeditor->config['filebrowserImageUploadUrl'] = $uploadPath.'Image';
		$ckeditor->config['filebrowserFlashUploadUrl'] = $uploadPath.'Flash';
	}

	/**	Define all extra CKEditor plugins here */
	$ckeditor->config['extraPlugins'] = 'wblink,wbdroplets,wbshybutton,wbsave,autolink,codemirror,colorbutton,copyformatting,flash,font,indentblock,justify,lineutils,oembed,panelbutton,templates,textselection,widget,widgetselection';
	$ckeditor->config['removePlugins'] = 'wsc,link,about';
    $ckeditor->config['removeButtons'] = 'Font';

    $ckeditor->config['height'] = '350px';
    $ckeditor->config['width'] = '100%';

	echo $ckeditor->to_HTML( $name, $content, $ckeditor->config);
}