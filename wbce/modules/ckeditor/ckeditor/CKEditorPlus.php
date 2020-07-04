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
if (count(get_included_files()) == 1) {
    $z = "HTTP/1.0 404 Not Found";
    header($z);
    die($z);
}

class CKEditorPlus extends CKEditor
{
    public $pretty = true;

    private $lookup_html = array(
        '&gt;'   => ">",
        '&lt;'   => "<",
        '&quot;' => "\"",
        '&amp;'  => "&"
    );

    /**
     * Public var to force the editor to use the given params for width and height
     */
    public $force = false;

    public $paths = array(
        'contentsCss' => "",
        'stylesSet'   => ""
    );

    private $templateFolder = '';

    public $files = array(
        'contentsCss' => array(
            '/editor.css',
            '/css/editor.css',
            '/editor/editor.css'
        ),
        'stylesSet' => array(
            '/editor.styles.js',
            '/js/editor.styles.js',
            '/editor/editor.styles.js'
        )
    );

    public function setTemplatePath($templateFolder = '')
    {
        if ($templateFolder == '') {
            return;
        }
        $this->templateFolder = $templateFolder;
        foreach ($this->files as $key => $val) {
            foreach ($val as $temp_path) {
                $base = "/templates/" . $this->templateFolder . $temp_path;
                if (true == file_exists(WB_PATH . $base)) {
                    $this->paths[$key] = (($key == "stylesSet") ? "wb:" : "") . WB_URL . $base;
                    break;
                }
            }
        }
    }

    /**
     * JavaScript handels LF/LB in another way as PHP, even inside an array.
     * So we're in the need of pre-parse the entries.
     */
    public function javascript_clean_str(&$aStr)
    {
        $vars = array(
            '"'  => "\\\"",
            '\'' => "",
            "\n" => "<br />",
            "\r" => ""
        );
        return str_replace(array_keys($vars), array_values($vars), $aStr);
    }

    /**
     * @param string Any HTML-Source, pass by reference
     */
    public function reverse_htmlentities(&$html_source)
    {
        $html_source = str_replace(
            array_keys($this->lookup_html),
            array_values($this->lookup_html),
            $html_source
        );
    }

    /**
     * Looks for an (local) url
     * @param string Key for tha assoc. config array
     * @param string Local file we are looking for
     * @param string Optional file-default-path if it not exists
     * @param string Optional a path_addition, e.g. "wb:"
     */
    public function resolve_path($key = "", $aPath, $aPath_default, $path_addition = "")
    {
        $temp = WB_PATH . $aPath;
        if (true === file_exists($temp)) {
            $aPath = $path_addition . WB_URL . $aPath;
        } else {
            $aPath = $path_addition . WB_URL . $aPath_default;
        }
        if (array_key_exists($key, $this->paths)) {
            $this->config[$key] = (($this->paths[$key] == "") ? $aPath : $this->paths[$key]);
        } else {
            $this->config[$key] = $aPath;
        }
    }

    /**
     * More or less for debugging
     * @param  string Name
     * @param  string Any content. Pass by reference!
     * @return string The "editor"-JS HTML code
     */
    public function to_HTML($name, &$content)
    {
        $old_return         = $this->returnOutput;
        $this->returnOutput = true;
        $temp_HTML          = $this->editor($name, $content);
        $this->returnOutput = $old_return;
        if (true === $this->pretty) {
            $temp_HTML = str_replace(", ", ",\n ", $temp_HTML);
            $temp_HTML = "\n\n\n" . $temp_HTML . "\n\n\n";
        }
        return $temp_HTML;
    }
}
