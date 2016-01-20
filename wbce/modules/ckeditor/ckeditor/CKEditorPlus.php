<?php
/**
 *
 * @category       modules
 * @package        ckeditor
 * @authors        WebsiteBaker Project, Michael Tenschert, Dietrich Roland Pehlke,D. Wöllbrink,Marmot
 * @copyright      WebsiteBaker Org. e.V.
 * @link           http://websitebaker.org/
 * @license        http://www.gnu.org/licenses/gpl.html
 * @platform       WebsiteBaker 2.8.3
 * @requirements   PHP 5.3.6 and higher
 * @version        $Id: CKEditorPlus.php 137 2012-03-17 23:29:07Z Luisehahne $
 * @filesource     $HeadURL: http://webdesign:8080/svn/ckeditor-dev/branches/ckeditor/CKEditorPlus.php $
 * @lastmodified   $Date: 2012-03-18 00:29:07 +0100 (So, 18. Mrz 2012) $
 *
 */

class CKEditorPlus extends CKEditor
{
    public $pretty = true;

    private $lookup_html = array(
        '&gt;'    => ">",
        '&lt;'    => "<",
        '&quot;' => "\"",
        '&amp;'     => "&"
    );

/**
 *    Public var to force the editor to use the given params for width and height
 *
 */
    public $force = false;

    public $paths = Array(
        'contentsCss' => "",
        'stylesSet' => "",
        'templates_files' => "",
        'customConfig' => ""
    );

    private $templateFolder = '';

    public $files = array(
        'contentsCss' => Array(
            '/editor.css',
            '/css/editor.css',
            '/editor/editor.css'
        ),
        'stylesSet' => Array(
            '/editor.styles.js',
            '/js/editor.styles.js',
            '/editor/editor.styles.js'
        ),
        'templates_files' => Array(
            '/editor.templates.js',
            '/js/editor.templates.js',
            '/editor/editor.templates.js'
        ),
        'customConfig' => Array(
            '/wb_ckconfig.js',
            '/js/wb_ckconfig.js',
            '/editor/wb_ckconfig.js'
        )
    );

    public function setTemplatePath ($templateFolder='') {
        if($templateFolder=='') { return; }
        $this->templateFolder = $templateFolder;
        foreach($this->files as $key=>$val) {
            foreach($val as $temp_path) {
                $base = "/templates/".$this->templateFolder.$temp_path;
                if (true == file_exists(WB_PATH.$base) ){
                    $this->paths[$key] = (($key=="stylesSet") ? "wb:" : "").WB_URL.$base;
                    break;
                }
            }
        }
    }

/**
 *    JavaScript handels LF/LB in another way as PHP, even inside an array.
 *    So we're in the need of pre-parse the entries.
 *
 */
    public function javascript_clean_str( &$aStr) {
        $vars = array(
            '"' => "\\\"",
            '\'' => "",
            "\n" => "<br />",
            "\r" => ""
        );

        return str_replace( array_keys($vars), array_values($vars), $aStr);
    }

/**
 *    @param    string    Any HTML-Source, pass by reference
 *
 */
    public function reverse_htmlentities(&$html_source) {

        $html_source = str_replace(
            array_keys( $this->lookup_html ),
            array_values( $this->lookup_html ),
            $html_source
        );
    }

/**    *************************************
 *    Additional test for the wysiwyg-admin
 */

/**
 *    @var    boolean
 *
 */
    public $wysiwyg_admin_exists = false;

/**
 *    Public function to look for the wysiwyg-admin table in the used database
 *
 *    @param    object    Any DB-Connector instance. Must be able to use a "query" method inside.
 *
 */
    public function looking_for_wysiwyg_admin( $db ) {
            if ($db->query("SHOW TABLES LIKE '%mod_editor_admin'")->numRows())
                $this->wysiwyg_admin_exists = true;
        }

/**
 *    Looks for an (local) url
 *
 *    @param    string    Key for tha assoc. config array
 *    @param    string    Local file we are looking for
 *    @param    string    Optional file-default-path if it not exists
 *    @param    string    Optional a path_addition, e.g. "wb:"
 *
 */
    public function resolve_path($key= "", $aPath, $aPath_default, $path_addition="") {
        $temp = WB_PATH.$aPath;
        if (true === file_exists($temp)) {
            $aPath = $path_addition.WB_URL.$aPath;
        } else {
            $aPath = $path_addition.WB_URL.$aPath_default;
        }
        if (array_key_exists($key, $this->paths)) {
            $this->config[$key] = (($this->paths[$key ] == "") ? $aPath : $this->paths[$key]) ;
        } else {
            $this->config[$key] = $aPath;
        }
    }

/**
 *    More or less for debugging
 *
 *    @param    string    Name
 *    @param    string    Any content. Pass by reference!
 *    @return   string    The "editor"-JS HTML code
 *
 */
    public function to_HTML( $name, &$content  ) {
        $old_return = $this->returnOutput;
        $this->returnOutput = true;
        $temp_HTML= $this->editor( $name, $content  );
        $this->returnOutput = $old_return;
        if (true === $this->pretty) {
            $temp_HTML = str_replace (", ", ",\n ", $temp_HTML);
            $temp_HTML = "\n\n\n".$temp_HTML."\n\n\n";
        }
        return $temp_HTML;
    }
}