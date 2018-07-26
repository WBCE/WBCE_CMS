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

//no direct file access
if(count(get_included_files()) ==1){$z="HTTP/1.0 404 Not Found";header($z);die($z);}

$debug = false;

if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

/**
 * Function called by parent, default by the wysiwyg-module
 *
 * @param    string    The name of the textarea to watch
 * @param    mixed    The "id" - some other modules handel this param differ
 * @param    string    Optional the width, default "100%" of given space.
 * @param    string    Optional the height of the editor - default is '250px'
 */
function show_wysiwyg_editor(
    $name, 
    $id, 
    $content, 
    $width='100%', 
    $height='350px', 
    $toolbar = false
    ) {
	global $database;
	$oApp = isset($GLOBALS['admin']) ? $GLOBALS['admin'] : $GLOBALS['wb'];

	$modAbsPath = str_replace('\\','/',dirname(__FILE__));
	$ckeAbsPath = $modAbsPath.'/ckeditor/';
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$realPath = str_replace('\\','/',dirname($_SERVER['SCRIPT_FILENAME']));
	} else {
        /**
         * realpath - Returns canonicalized absolute pathname
         */
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
	 * Create new CKeditor instance.
	 * But first - we've got to revamp this pretty old class a little bit.
	 */
	require_once ( $modAbsPath.'/wbce_ckeditor.php' ); // $ckeAbsPath ends with /
	$ckeditor = new CKEditorPlus( $ckeRelPath );

	$temp = '';
	if (isset($oApp->page_id)) {
		$query = "SELECT `template` from `{TP}pages` where `page_id`='".(int)$oApp->page_id."'";
		$temp = $database->get_one( $query );
	}
	$templateFolder = ($temp == "") ? DEFAULT_TEMPLATE : $temp;
	$ckeditor->setTemplatePath($templateFolder);

	/**
     * Looking for the styles
     */
	$ckeditor->resolve_path(
		'contentsCss',
		$tplPath.'/wb_config/editor.css',
		$ModPath.'/wb_config/editor.css'
	);

	/**
     * Looking for the editor.styles at all ...
     */
	$ckeditor->resolve_path(
		'stylesSet',
		$tplPath.'/wb_config/editor.styles.js',
		$ModPath.'/wb_config/editor.styles.js',
		'wb:'
	);

	/**
     * The filebrowser are called in the include, because later on we can make switches, use WB_URL and so on
     */
	$connectorPath = $ckeditor->basePath.'filemanager/connectors/php/connector.php';
	$ckeditor->config['filebrowserBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Connector='.$connectorPath;
	$ckeditor->config['filebrowserImageBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Image&Connector='.$connectorPath;
	$ckeditor->config['filebrowserFlashBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Flash&Connector='.$connectorPath;

	/**	
     * The Uploader has to be called, too
     */
	$ckeditor->config['uploader'] = false; // disabled for security reasons
	if($ckeditor->config['uploader']==true) {
		$uploadPath = $ckeditor->basePath.'filemanager/connectors/php/upload.php?Type=';
		$ckeditor->config['filebrowserUploadUrl'] = $uploadPath.'File';
		$ckeditor->config['filebrowserImageUploadUrl'] = $uploadPath.'Image';
		$ckeditor->config['filebrowserFlashUploadUrl'] = $uploadPath.'Flash';
	}

	/**	
     * Define all extra CKEditor plugins here
     * This version contains the image2 plugin, to enable it, add simply image2 to the extraPlugins list
     */
	$ckeditor->config['extraPlugins'] = 'wbdroplets,wblink,wbsave,wbshybutton,autolink,ckawesome,codemirror,lineutils,oembed,textselection,widgetselection,colorbutton,copyformatting,flash,font,indentblock,justify,panelbutton';
	$ckeditor->config['removePlugins'] = 'wsc,link,save';
    $ckeditor->config['removeButtons'] = 'Font';
    $ckeditor->config['fontawesomePath'] = WB_URL.'/include/font-awesome/css/font-awesome.min.css';

    if ($toolbar) $ckeditor->config['toolbar'] = $toolbar;

    $ckeditor->config['height'] = $height;
    $ckeditor->config['width'] = $width;

	/**
	 * Force the object to print/echo direct instead of returning the HTML source string.
	 */
	$ckeditor->returnOutput = false;

    $ckeditor->reverse_htmlentities($content);

	echo $ckeditor->to_HTML( $name, $content, $ckeditor->config);
}