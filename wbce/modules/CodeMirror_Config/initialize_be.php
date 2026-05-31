<?php
/**
 *
 * @category        admintool / initialize 
 * @package         CodeMirror_Config
 * @author          Christian M. Stefan
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.5.4

 *
 */

define('CODEMIRROR', true);

// Register CodeEditor class — available globally after initialize phase
WbAuto::AddFile('CodeEditor', WB_PATH . '/modules/' . basename(__DIR__) . '/CodeEditor.php');

$CodeMirror_dir = WB_URL.'/modules/'.basename(__DIR__).'/codemirror/';


if (!function_exists('registerEditArea')) {
    /**
     * @brief Fallback function for modules that were using EditArea in the past
     *        It's recommendet to adapt modules to the newer function below, but
     *        for the time being, both options are available.
     * 
     * @param string  $id The <textarea id=""> id-attribute
     * @param string  $syntax whyt type of code is it? php, css, js, html?
     * @return renders textarea with above given ID into a CodeMirror instance.
     */
    function registerEditArea(
        $id               = 'code_area',
        $syntax           = 'php',
        $syntax_selection = NULL, // n/a with CodeMirror
        $allow_resize     = NULL, // n/a with CodeMirror
        $allow_toggle     = NULL, // n/a with CodeMirror
        $start_highlight  = NULL, // n/a with CodeMirror
        $min_width        = null, // will leave setting as is
        $min_height       = 650
    ){
        return registerCodeMirror($id, $syntax, ['height' => $min_height]);
    }
}


if (!function_exists('registerCodeMirror')) {
    /**
     * Recommended function to turn textareas into CodeMirror editors.
     * Delegates to CodeEditor::init() which also loads the CodeEditorToolbar.
     *
     * For toolbar + AJAX save, use CodeEditor::init() directly.
     *
     * @param string $id_attr  The <textarea id=""> id attribute
     * @param string $syntax   Syntax: php, twig, css, js, html, htt, sql, ini …
     * @param array  $options  Supported: height, readonly, lineWrapping, staticTheme
     */
    function registerCodeMirror(
        $id_attr = 'code_area', $syntax = 'php', $options = []
    ) {
        // Map old option keys to CodeEditor option keys
        $mapped = [];
        if (isset($options['height']))       $mapped['height']    = (int)$options['height'];
        if (isset($options['readOnly']))     $mapped['readonly']  = (bool)$options['readOnly'];
        if (isset($options['lineWrapping'])) $mapped['line_wrap'] = (bool)$options['lineWrapping'];
        if (isset($options['staticTheme']))  $mapped['theme']     = $options['staticTheme'];

        // toolbar defaults to false for backward compat — callers that used
        // registerCodeMirror() didn't have a toolbar, keep that behaviour unless opted in.
        $mapped['toolbar'] = (bool)($options['toolbar'] ?? false);

        CodeEditor::init((string)$id_attr, (string)$syntax, $mapped);
    }
}

if (!function_exists('getEditAreaSyntax')) {
    function getEditAreaSyntax($file)
    {        
        $sModUrl = WB_URL.'/modules/'.basename(__DIR__).'/codemirror/mode/';
        
        // extract file extension
        switch ($file) {
            case 'htm':
            case 'html':
            case 'htt':
                $syntax = '"text/html"';
                I::insertJsFile($sModUrl.'../lib/beautify.min.js', 'BODY');
                I::insertJsFile($sModUrl.'xml/xml.js', 'BODY');
                I::insertJsFile($sModUrl.'css/css.js', 'BODY');
                I::insertJsFile($sModUrl.'javascript/javascript.js', 'BODY');
                I::insertJsFile($sModUrl.'htmlembedded/htmlembedded.js', 'BODY');
                I::insertJsFile($sModUrl.'htmlmixed/htmlmixed.js', 'BODY');                
                break;

            case 'twig':
                $syntax = '{name: "twig", base: "text/html"}';
                I::insertJsFile($sModUrl.'xml/xml.js', 'BODY');
                I::insertJsFile($sModUrl.'css/css.js', 'BODY');
                I::insertJsFile($sModUrl.'javascript/javascript.js', 'BODY');
                I::insertJsFile($sModUrl.'htmlembedded/htmlembedded.js', 'BODY');
                I::insertJsFile($sModUrl.'htmlmixed/htmlmixed.js', 'BODY');
                I::insertJsFile($sModUrl.'twig/twig.js', 'BODY');
                I::insertJsFile($sModUrl.'../../codemirror/addon/mode/multiplex.js', 'BODY');
                break;

            case 'css':
                $syntax = '"text/css"';
                I::insertJsFile($sModUrl.'css/css.js', 'BODY');
                break;

            case 'js':
            case 'javascript':
                $syntax = '"text/javascript"'; 
                I::insertJsFile($sModUrl.'javascript/javascript.js', 'BODY');
                break;

            case 'xml':
                $syntax = '"text/xml"';
                I::insertJsFile($sModUrl.'xml/xml.js', 'BODY');
                break;

            
            case 'x-php': // for droplets and php without <?php wrapping
                $syntax = '"text/x-php"';                
                I::insertJsFile($sModUrl.'php/php.js', 'BODY');
                I::insertJsFile($sModUrl.'clike/clike.js', 'BODY');
                break;
            
            case 'php': // for php files, uses mixed type with HTML, JS, CSS
            case 'phtml':
                $syntax = '"application/x-httpd-php"';
                I::insertJsFile($sModUrl.'php/php.js', 'BODY');
                I::insertJsFile($sModUrl.'clike/clike.js', 'BODY');
                I::insertJsFile($sModUrl.'htmlmixed/htmlmixed.js', 'BODY');                
                I::insertJsFile($sModUrl.'xml/xml.js', 'BODY');
                I::insertJsFile($sModUrl.'css/css.js', 'BODY');
                I::insertJsFile($sModUrl.'javascript/javascript.js', 'BODY');
                break;

            case 'ini':
            case 'properties':
                $syntax = '"text/x-properties"';
                I::insertJsFile($sModUrl.'properties/properties.js', 'BODY');
                break;

            case 'sql':
                $syntax = '"text/x-properties"';
                I::insertJsFile($sModUrl.'sql/sql.js', 'BODY');
                break;

            default:
                $syntax = '"text"';
                break;
        }
        return $syntax;
    }
}

if (!function_exists('list_files_from_dir')) {
    /**
     * Return a list (array) of files from a specific directory
     * @author   Christian M. Stefan (Stefek)
     * @version  0.0.1 
     * @date     10.01.2023
     * 
     * @param    string $sDirPath   // Location of the files to be listed
     * @param    mixed  $mType      // may be String or Array
     *                                  string: css, js, php, twig . . . 
     *                                  array: ['css', 'twig'] ...
     * @param    bool   $bGroup     // whether to group the list by file type
     * @return   array
     */
    function list_files_from_dir($sDirPath = "", $mType = "", $bGroup = false) {
        $aList = [];
        
        if($sDirPath == '' or $mType == ''){
            trigger_error('$sDirPath or $sType must be set');
            return $aList;
        }
        
        if(is_string($mType)) $mType = array($mType);
        
        foreach($mType as $sType){
            $sExt = '.'.$sType; 
            $iExtLen = strlen($sExt);
            foreach(scandir($sDirPath) as $k => $file){
                if(substr($file, -$iExtLen) != $sExt) continue;
                if($bGroup)
                    $aList[$sType][] = $file;
                else 
                    $aList[] = $file;
            }    
        }
        return $aList;
    }
}

if(!function_exists('cmc_load_font_files')){    
    function cmc_load_font_files(){
        $sCss = '';
        $aFontFiles = list_files_from_dir(__DIR__ . '/codemirror/fonts', ['woff2', 'woff']);
        $sModUrl = WB_URL.'/modules/'.basename(__DIR__).'/codemirror/';
        
        $aCfg = unserialize(Settings::Get("cmc_cfg", ""));
        foreach($aFontFiles as $sFileName){

            $sFam = strstr($sFileName, '.', true);
            $sCss .= "
            @font-face {
                font-family: ".$sFam.";
                src: url(".$sModUrl."fonts/".$sFileName.");
            }";
        }

        $sCss .= " 
            .CodeMirror {
                font-family: ".$aCfg['font'].";
                font-size: ".$aCfg['font_size']."px;
                line-height: 140%;
            }";     
        I::insertCssCode($sCss, 'HEAD TOP+', 'CodeMirror_loadFontCss');
    } 
}
