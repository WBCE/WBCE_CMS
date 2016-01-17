<?php
/**
 *
 * @category       modules
 * @package        ckeditor
 * @authors        WebsiteBaker Project, Michael Tenschert, Dietrich Roland Pehlke, Marmot, Luisehahne
 * @copyright      WebsiteBaker Org. e.V.
 * @link           http://websitebaker.org/
 * @license        http://www.gnu.org/licenses/gpl.html
 * @platform       WebsiteBaker 2.8.3
 * @requirements   PHP 5.3.6 and higher
 * @version        $Id: include.php 138 2012-03-17 23:37:27Z Luisehahne $
 * @filesource     $HeadURL: http://webdesign:8080/svn/ckeditor-dev/branches/include.php $
 * @lastmodified   $Date: 2012-03-18 00:37:27 +0100 (So, 18. Mrz 2012) $
 *
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(defined('WB_PATH') == false) { die('Cannot access '.basename(__DIR__).'/'.basename(__FILE__).' directly'); }
/* -------------------------------------------------------- */
$debug = false;

if (true === $debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL|E_STRICT);
}

/**
 *    Function called by parent, default by the wysiwyg-module
 *
 *    @param    string    The name of the textarea to watch
 *    @param    mixed    The "id" - some other modules handel this param differ
 *    @param    string    Optional the width, default "100%" of given space.
 *    @param    string    Optional the height of the editor - default is '250px'
 *
 *
 */
function show_wysiwyg_editor($name, $id, $content, $width = '100%', $height = '250px', $toolbar = false) {
    global $database,$admin;

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
    $ModPath = str_replace($url['path'],'',$modRelPath).'/';
    $ckeModPath = str_replace($url['path'],'',$ckeRelPath);
    $tplPath = str_replace($url['path'],'',$tplRelPath).'/';
    require ( $modAbsPath.'/info.php' );
/**
 * http://docs.ckeditor.com/#!/api/CKEDITOR.config
 * 
 * @param boolean
 * true: set some config.index by wb_config.js
 * false: set some config['index'] by include.php
 * 
 * possible config.indexes for setting in wb_config.js
 * that were normaly set in include.php
 * format_tags, resize_dir, autoParagraph, skin, toolbar,
 * extraPlugins, removePlugins, browserContextMenuOnCtrl, entities,
 * scayt_autoStartup, 
 * 
 * 
 */
    $bWbConfigSetting = false;

/**
 *    Create new CKeditor instance.
 *    But first - we've got to revamp this pretty old class a little bit.
 *
 */
    if ( !class_exists('CKEditor', false ))     { require ($ckeAbsPath.'ckeditor.php' ); }
    if ( !class_exists('CKEditorPlus', false )) { require ($ckeAbsPath.'CKEditorPlus.php' ); }   // $ckeAbsPath ends with /

    $ckeditor = new CKEditorPlus( $ckeRelPath );
/******************************************************************************************/

/******************************************************************************************/

    $ckeditor->config['ModulVersion'] = isset($module_version) ? $module_version :  'none';
    $temp = '';
    if (isset($admin->page_id)) {
        $query = "SELECT `template` from `".TABLE_PREFIX."pages` where `page_id`='".$page_id."'";
        $temp = $database->get_one( $query );
    }
    $templateFolder = ($temp == "") ? DEFAULT_TEMPLATE : $temp;
    $ckeditor->setTemplatePath($templateFolder);

  // The language to be used if config.language is empty and it's not possible to localize the editor to the user language.
//     $ckeditor->config['defaultLanguage']  = strtolower(DEFAULT_LANGUAGE);
  $ckeditor->config['defaultLanguage']  = 'en';

/**
 *    Setup the CKE language
 *
 */
$ckeditor->config['language'] = strtolower( (@LANGUAGE ?: 'en') );

/**
 * A list of semi colon separated style names (by default tags) representing
 * the style definition for each entry to be displayed in the Format combo in
 * the toolbar. Each entry must have its relative definition configuration in a
 * setting named "format_(tagName)". For example, the "p" entry has its
 * definition taken from config.format_p.
 * @type String
 * @default 'p;h1;h2;h3;h4;h5;h6;pre;address;div'
 */
if( !$bWbConfigSetting ) { $ckeditor->config['format_tags'] = 'p;div;h1;h2;h3;h4;h5;h6;pre'; }

if( !$bWbConfigSetting ) { $ckeditor->config['resize_dir'] = 'vertical'; }

if( !$bWbConfigSetting ) { $ckeditor->config['autoParagraph'] = true; }

/**
* The skin to load. It may be the name of the skin folder inside the editor installation path,
* or the name and the path separated by a comma.
* Available skins: moono, moonocolor
* 
*/
if( !$bWbConfigSetting ) { $ckeditor->config['skin'] = 'moono'; }

/**
 *    Additional test for wysiwyg-admin
 *
 */
$ckeditor->looking_for_wysiwyg_admin( $database );

/**
 *    Define all extra CKEditor plugins in _yourwb_/modules/ckeditor067/ckeditor/plugins here
 *
 */
if( !$bWbConfigSetting ) { 
    $ckeditor->config['extraPlugins'] = 'justify,find,flash,colorbutton,colordialog,dialogadvtab,'
                                      . 'div,font,forms,iframe,indentblock,language,bidi,liststyle,pagebreak,save,'
                                      . 'selectall,showblocks,smiley,templates,codemirror,syntaxhighlight,'
                                      . 'wblink,wbdroplets,youtube,oembed,backup,wbsave';

    $ckeditor->config['removePlugins'] = 'wsc,link,save,newpage,print,shybutton,preview';
 }

if ($toolbar) $ckeditor->config['toolbar'] = $toolbar;

/**
 *  Whether to show the browser native context menu when the Ctrl
 *  or Meta (Mac) key is pressed on opening the context menu with the right mouse button click or the Menu key.
 *
 */
if( !$bWbConfigSetting ) { $ckeditor->config['browserContextMenuOnCtrl'] = true; }

/**
 *    Force the object to print/echo direct instead of returning the
 *    HTML source string.
 *
 */
$ckeditor->returnOutput = false;

if( !$bWbConfigSetting ) { $ckeditor->config['entities'] = false; }

/**
 *    SCAYT
 *    Spellchecker settings.
 *
 */
    $ckeditor->config['scayt_sLang'] = strtolower(LANGUAGE)."_".(LANGUAGE == "EN" ? "US" : LANGUAGE);
if( !$bWbConfigSetting ) { 
    $ckeditor->config['scayt_autoStartup'] = false;
 }
/**
 *    The filebrowser are called in the include, because later on we can make switches, use WB_URL and so on
 */
$connectorPath = $ckeditor->basePath.'filemanager/connectors/php/connector.php';
$ckeditor->config['filebrowserBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Connector='.$connectorPath;
$ckeditor->config['filebrowserImageBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Image&Connector='.$connectorPath;
$ckeditor->config['filebrowserFlashBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Flash&Connector='.$connectorPath;

/**
 *    The Uploader has to be called, too.
 *
 */
$ckeditor->config['uploader'] = false; // disabled for security reasons
if($ckeditor->config['uploader']==true) {
    $uploadPath = $ckeditor->basePath.'filemanager/connectors/php/upload.php?Type=';
    $ckeditor->config['filebrowserUploadUrl'] = $uploadPath.'File';
    $ckeditor->config['filebrowserImageUploadUrl'] = $uploadPath.'Image';
    $ckeditor->config['filebrowserFlashUploadUrl'] = $uploadPath.'Flash';
}
/******************************************************************************************/
/**
 *    Looking for the styles
 *
 */
$ckeditor->resolve_path(
    'contentsCss',
    $tplPath.'wb_config/editor.css',
    $ModPath.'wb_config/editor.css'
);

/**
 *    Looking for the editor.styles at all ...
 *
 */
$ckeditor->resolve_path(
    'stylesSet',
    $tplPath.'wb_config/editor.styles.js',
    $ModPath.'wb_config/editor.styles.js',
    'wb:'
);

/**
 *    The list of templates definition files to load.
 *
 */
$ckeditor->resolve_path(
    'templates_files',
    $tplPath.'wb_config/editor.templates.js',
    $ModPath.'wb_config/editor.templates.js'
);

/**
 *    Bugfix for the template files as the ckeditor want an array instead a string ...
 *
 */
$ckeditor->config['templates_files'] = array($ckeditor->config['templates_files']);

/**
 *    Get the config file
 *
 */
$ckeditor->resolve_path(
    'customConfig',
    $tplPath.'wb_config/wb_ckconfig.js',
    $ModPath.'wb_config/wb_ckconfig.js'
);
/******************************************************************************************/

/**
 *    To avoid a double "else" inside the following condition, we set the
 *    default toolbar here to "WB_Full". Keep in mind, that the config will overwrite all
 *    settings inside the config.js or wb_config.js BUT you will have to define the toolbar inside
 *    them at all!
 *
 */
if (isset($database) && $ckeditor->wysiwyg_admin_exists ) {
    $data = null;
    $query = "SELECT * from `".TABLE_PREFIX."mod_editor_admin` where `editor`='ckeditor'";
    if (method_exists($database,'doQuery') ) {
        if (($result = $database->query($query))) {
            $data = $result->fetchArray( MYSQLI_ASSOC );
        }
    } else {
        if (($result = $database->query($query))) {
            $data = $result->fetchRow( MYSQLI_ASSOC );
        }
    }
// import data into $ckeditor->config
    if ( $data ) {
        foreach ($data as $key => $value) {
            $ckeditor->config[$key] = $value; 
            $ckeditor->config['toolbar'] = $ckeditor->config['menu'];
        }
    }
}

if ( (!$ckeditor->wysiwyg_admin_exists) || ($ckeditor->force) )    {
    $ckeditor->config['height'] = $height;
    $ckeditor->config['width']  = $width;
}

$ckeditor->reverse_htmlentities($content);

echo $ckeditor->to_HTML( $name, $content, $ckeditor->config);
}
