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
     * This is the recomended function to turn textareas into CodeMirror editors. 
     * 
     * @param string  $id The <textarea id=""> id-attribute
     * @param string  $syntax whyt type of code is it? php, css, js, html?
     * @param array   $options
     * @return renders textarea with above given ID into a CodeMirror instance.
     */
    function registerCodeMirror( 
        $id_attr = 'code_area', $syntax  = 'php', $options = []
    ){
        $defaults = [
            'readOnly'     => false,
            'lineNumbers'  => true,
            'foldGutter'   => true,
            'simpleScroll' => true,
            'lineWrapping' => true,   // DE:Zeilenumbruch
            'lineHeight'   => '150%',
            'height'       => '450', 
            'width'        => 'null', // null means: dimension should not be changed. 
            'panel'        => '',     // NEXT: integrate Panels https://codemirror.net/5/demo/panel.html
            'staticTheme'  => ''
        ];
        // merge defaults and options array and remove unsupported keys
        $cfg = array_merge($defaults, $options);
        foreach($cfg as $k => $v) {
            if (! array_key_exists($k, $defaults)) {
                unset($cfg[$k]);
            }
        }
                
        $sModUrl = WB_URL.'/modules/'.basename(__DIR__).'/codemirror/';
        
        // get theme from {TP}settings table
        $aCfg = unserialize(Settings::Get("cmc_cfg", ""));
        $sTheme    = isset($aCfg['theme'])     ? $aCfg['theme']     : 'wbce-day';
        $sFont     = isset($aCfg['font'])      ? $aCfg['font']      : 'JetBrains_Mono';
        $sFontSize = isset($aCfg['font_size']) ? $aCfg['font_size'] : 15;

        if($cfg['staticTheme'] != ''){
            $sTheme = $cfg['staticTheme'];
        }
        
        $aCodeMirrorFiles = [  
            $sModUrl.'lib/codemirror.js',            
            $sModUrl.'addon/display/fullscreen.js',
            $sModUrl.'addon/edit/matchbrackets.js'
        ];
        // foldGutter code
        if( $cfg['foldGutter'] == true){
            $aCodeMirrorFiles += [
                $sModUrl.'addon/fold/foldcode.js',
                $sModUrl.'addon/fold/foldgutter.js',
                $sModUrl.'addon/fold/brace-fold.js',
                $sModUrl.'addon/fold/comment-fold.js'
            ];
        }
        if( $cfg['panel'] != ''){
            I::insertJsFile ($sModUrl.'addon/display/panel.js',  'BODY');
            I::insertCssFile($sModUrl.'addon/display/panel.css');
        }
        if( $cfg['simpleScroll'] != ''){
            I::insertJsFile ($sModUrl.'addon/scroll/simplescrollbars.js',  'BODY');
            I::insertCssFile($sModUrl.'addon/scroll/simplescrollbars.css');
        }
        I::insertJsFile($aCodeMirrorFiles, 'BODY');
        I::insertCssFile($sModUrl.'lib/codemirror.css', 'HEAD TOP+');
        I::insertCssFile($sModUrl.'addon/fold/foldgutter.css', 'HEAD TOP+');
        I::insertCssFile($sModUrl.'theme/'.$sTheme.'.css', 'HEAD');
        I::insertCssFile($sModUrl.'addon/display/fullscreen.css', 'HEAD');
        
            

        $sCss = '';
        $aFontFiles = list_files_from_dir(__DIR__ . '/codemirror/fonts', ['woff2', 'woff']);
        foreach($aFontFiles as $sFileName){
            if(!defined('CMC_TOOL_RUNNING') || CMC_TOOL_RUNNING == false){
                // bypass all the fonts that are not the actually selected font
                if($aCfg['font'] != pathinfo($sFileName)['filename']) continue;
            }
            $sFam = strstr($sFileName, '.', true);
            $sCss .= "
            @font-face {
                font-family: ".$sFam.";
                src: url(".$sModUrl."fonts/".$sFileName.");
            }";
        }
        $sFont = ($sFont == 'default') ? 'monospace' : $sFont;
        $sCss .= " 
            .CodeMirror {
                font-family: ".$sFont.";
                font-size:   ".$sFontSize."px;
                line-height: ".$cfg['lineHeight'].";";                    
        $sCss .= "    }";     
        I::insertCssCode($sCss, 'HEAD TOP+');
            
        ob_start();
        ?>

<script>

var  <?=$id_attr?> = CodeMirror.fromTextArea(document.getElementById("<?=$id_attr?>"), {
    mode: "<?=getEditAreaSyntax($syntax, $id_attr); ?>",
    theme: "<?=$sTheme; ?>",
    lineNumbers: <?=($cfg['lineNumbers']) ? 'true':'false'; ?>,
    autoCloseTags: true,
    matchBrackets: true,
    lineWrapping: <?=($cfg['lineWrapping']) ? 'true':'false'; ?>,
    styleActiveLine: true,
    readOnly: <?=($cfg['readOnly']) ? 'true':'false'; ?>,
    <?php if( $cfg['simpleScroll'] == true): ?>
    scrollbarStyle: 'overlay',
    <?php endif; ?>
    <?php if( $cfg['foldGutter'] == true): ?>
    foldGutter: true,    
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter", "CodeMirror-lint-markers"],
    <?php endif; ?>
    extraKeys: {
        "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
    }
  });  
   <?=$id_attr?>.setSize('<?=$cfg['width']?>', '<?=$cfg['height']?>');
  
  $("#<?=$id_attr?>").addClass("cm-stx-<?=$syntax?>");
    $("#<?=$id_attr?>").next().addClass("CodeMirror-<?=$id_attr?>");
    $("#<?=$id_attr?>").next().addClass("cm-syntax-<?=$syntax?>");
<?php if( $cfg['panel'] != ''): ?>
var numPanels = 0;
var panels = {};
function makePanel(where) {
  var node = document.createElement("div");
  var id = ++numPanels;
  var widget, close, label;

  node.id = "cmc-panel-" + id;
  node.className = "cmc-panel cmc-panel-<?=$syntax?> " + where;
  close = node.appendChild(document.createElement("a"));
  close.setAttribute("title", "Remove me!");
  close.setAttribute("class", "remove-panel");
  close.textContent = "✖";
  CodeMirror.on(close, "mousedown", function(e) {
    e.preventDefault()
    panels[node.id].clear();
  });
  label = node.appendChild(document.createElement("span"));
  label.textContent = "<?=$cfg['panel']?>";
  return node;
}
function addPanel(where) {
  var node = makePanel(where);
  panels[node.id] = <?=$id_attr?>.addPanel(node, {position: where, stable: true});
}
addPanel("top");
addPanel("bottom");
<?=$id_attr?>.makePanel('<?=$cfg['panel']?>', {position: 'top', stable: true});
<?php endif; ?>
</script>       
    <?php
        $sCode = ob_get_clean();        
        I::insertJsCode($sCode, 'BODY BTM-');
        return;
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
                $syntax = 'text/html';
                I::insertJsFile($sModUrl.'../lib/beautify.min.js', 'BODY');
                I::insertJsFile($sModUrl.'xml/xml.js', 'BODY');
                I::insertJsFile($sModUrl.'css/css.js', 'BODY');
                I::insertJsFile($sModUrl.'javascript/javascript.js', 'BODY');
                I::insertJsFile($sModUrl.'htmlembedded/htmlembedded.js', 'BODY');
                I::insertJsFile($sModUrl.'htmlmixed/htmlmixed.js', 'BODY');                
                break;

            case 'twig':
                $syntax = 'text/twig';
                I::insertJsFile($sModUrl.'twig/twig.js', 'BODY');
                break;

            case 'css':
                $syntax = 'text/css';
                I::insertJsFile($sModUrl.'css/css.js', 'BODY');
                break;

            case 'js':
            case 'javascript':
                $syntax = 'text/javascript'; 
                I::insertJsFile($sModUrl.'javascript/javascript.js', 'BODY');
                break;

            case 'xml':
                $syntax = 'text/xml';
                I::insertJsFile($sModUrl.'xml/xml.js', 'BODY');
                break;

            case 'php':
                $syntax = 'application/x-httpd-php-open';
                I::insertJsFile($sModUrl.'clike/clike.js', 'BODY');
                I::insertJsFile($sModUrl.'php/php.js', 'BODY');
                break;

            case 'ini':
            case 'properties':
                $syntax = 'text/x-properties';
                I::insertJsFile($sModUrl.'properties/properties.js', 'BODY');
                break;

            case 'sql':
                $syntax = 'text/x-properties';
                I::insertJsFile($sModUrl.'sql/sql.js', 'BODY');
                break;

            default:
                $syntax = 'text';
                break;
        }
        return $syntax;
    }
}

if (!function_exists('list_files_from_dir')) {
    /**
     * Return a list (array) of files from a specific directory
     * @author  Christian M. Stefan
     * @version 0.0.1 
     * @date    10.01.2023
     * 
     * @param   string $sDirPath // Location of the files to be listed
     * @param   mixed  $mType // String or Array
     *                          string: css, js, php, twig . . . 
     *                          array: ['css', 'twig'] ...
     * @return array
     */
    function list_files_from_dir($sDirPath = "", $mType = "", $bGroupByType = false){
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
                if($bGroupByType)
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
