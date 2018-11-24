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
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Hey we are on a frontend Page
if (!defined('WB_FRONTEND')) define('WB_FRONTEND', true);

// compatibility mode for versions before 2.8.1
// the news modul still needs it
if (isset($wb)) {$admin = $wb;}
if (isset($wb->default_link)) {$default_link = $wb->default_link;}
if (isset($wb->page_trail)) {$page_trail = $wb->page_trail;}
if (isset($wb->page_description)) {$page_description = $wb->page_description;}
if (isset($wb->page_keywords)) {$page_keywords = $wb->page_keywords;}
if (isset($wb->link)) {$page_link = $wb->link;}

$include_head_link_css = '';
$include_body_links = '';
$include_head_links = '';

// Extra run for include.php now available to ALL modules. 
// Yess all modules are now allowed to have a include.php. 

$sql = 'SELECT `directory` FROM `' . TABLE_PREFIX . 'addons` ';
$sql .= 'WHERE function LIKE \'%snippet%\' ';
if (($resSnippets = $database->query($sql))) {
    while ($recSnippet = $resSnippets->fetchRow()) {
        $module_dir = $recSnippet['directory'];
        if (file_exists(WB_PATH . '/modules/' . $module_dir . '/include.php')) {
            include WB_PATH . '/modules/' . $module_dir . '/include.php';
        }
    }
}

// workout to included frontend.css, fronten.js and frontend_body.js in snippets
// this old thing continues to load the old Frontend CSS/JS
// This possibly moves to a module thats why i seperated it !
// still this needs to call the new Library to store the  JS!
$sql = 'SELECT `directory` FROM `' . TABLE_PREFIX . 'addons` ';
$sql .= 'WHERE  function LIKE \'%snippet%\'';
if (($resSnippets = $database->query($sql))) {
    while ($recSnippet = $resSnippets->fetchRow()) {
        $module_dir = $recSnippet['directory'];
        if (file_exists(WB_PATH . '/modules/' . $module_dir . '/include.php')) {
            // check if frontend.css file needs to be included into the <head></head> of index.php
            if (file_exists(WB_PATH . '/modules/' . $module_dir . '/frontend.css')) {
                $include_head_link_css .= '<link href="' . WB_URL . '/modules/' . $module_dir . '/frontend.css"';
                $include_head_link_css .= ' rel="stylesheet" type="text/css" media="screen" />' . "\n";
                $include_head_file = 'frontend.css';
            }
            // check if frontend.js file needs to be included into the <body></body> of index.php
            if (file_exists(WB_PATH . '/modules/' . $module_dir . '/frontend.js')) {
                $include_head_links .= '<script src="' . WB_URL . '/modules/' . $module_dir . '/frontend.js" type="text/javascript"></script>' . "\n";
                $include_head_file = 'frontend.js';
            }
            // check if frontend_body.js file needs to be included into the <body></body> of index.php
            if (file_exists(WB_PATH . '/modules/' . $module_dir . '/frontend_body.js')) {
                $include_body_links .= '<script src="' . WB_URL . '/modules/' . $module_dir . '/frontend_body.js" type="text/javascript"></script>' . "\n";
                $include_body_file = 'frontend_body.js';
            }
        }
    }
}

// Frontend functions
if (!function_exists('page_link')) {
    /**
     * generate full qualified URL from relative link based on pages_dir
     * @param string $link
     * @return string
     */
    function page_link($link)
    {
        return $GLOBALS['wb']->page_link($link);
    }
}

if (!function_exists('get_page_link')) {
    /**
     * get relative link from database based on pages_dir
     * @global <type> $database
     * @param <type> $id
     * @return <type>
     */
    function get_page_link($id)
    {
        global $database;
        $sql = 'SELECT `link` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . (int)$id;
        $link = $database->get_one($sql);
        return $link;
    }
}

//function to highlight search results
if (!function_exists('search_highlight')) {
    /**
     *
     * @staticvar boolean $string_ul_umlaut
     * @staticvar boolean $string_ul_regex
     * @param string $foo
     * @param array $arr_string
     * @return string
     */
    function search_highlight($foo = '', $arr_string = array())
    {
        require_once WB_PATH . '/framework/functions.php';
        static $string_ul_umlaut = false;
        static $string_ul_regex = false;
        if ($string_ul_umlaut === false || $string_ul_regex === false) {
            require WB_PATH . '/search/search_convert.php';
        }
        $foo = entities_to_umlauts($foo, 'UTF-8');
        array_walk($arr_string, function(&$v, $k){ $v = preg_quote($v, '\'~\''); });
        $search_string = implode("|", $arr_string);
        $string = str_replace($string_ul_umlaut, $string_ul_regex, $search_string);
        // the highlighting
        // match $string, but not inside <style>...</style>, <script>...</script>, <!--...--> or HTML-Tags
        // Also droplet tags are now excluded from highlighting.
        // split $string into pieces - "cut away" styles, scripts, comments, HTML-tags and eMail-addresses
        // we have to cut <pre> and <code> as well.
        // for HTML-Tags use <(?:[^<]|<.*>)*> which will match strings like <input ... value="<b>value</b>" >
        $matches = preg_split("~(\[\[.*\]\]|<style.*</style>|<script.*</script>|<pre.*</pre>|<code.*</code>|<!--.*-->|<(?:[^<]|<.*>)*>|\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,8}\b)~iUs", $foo, -1, (PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY));
        if (is_array($matches) && $matches != array()) {
            $foo = "";
            foreach ($matches as $match) {
                if ($match{0} != "<" && !preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,8}$/i', $match) && !preg_match('~\[\[.*\]\]~', $match)) {
                    $match = str_replace(array('&lt;', '&gt;', '&amp;', '&quot;', '&#039;', '&nbsp;'), array('<', '>', '&', '"', '\'', "\xC2\xA0"), $match);
                    $match = preg_replace('~(' . $string . ')~ui', '_span class=_highlight__$1_/span_', $match);
                    $match = str_replace(array('&', '<', '>', '"', '\'', "\xC2\xA0"), array('&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '&nbsp;'), $match);
                    $match = str_replace(array('_span class=_highlight__', '_/span_'), array('<span class="highlight">', '</span>'), $match);
                }
                $foo .= $match;
            }
        }

        if (DEFAULT_CHARSET != 'utf-8') {
            $foo = umlauts_to_entities($foo, 'UTF-8');
        }
        return $foo;
    }
}


if (!function_exists('get_block_id')) {
/**
    @brief function to extract Block ID from Blockname 
    
    If fed whith an integer or numeric string it simply returns this as an integer.
    If fed whith a string it tries to find this string in the $block[] array of the recent template.
    It returns the id if found otherwise it returns 1 (default template block)
    
    @param unknown $uBlock may be string or integer
    @return integer 
*/
    function get_block_id ($uBlock) {
        if (is_int($uBlock) OR preg_match("/^[0-9]+$/is", $uBlock))
            return (int)$uBlock;
        else {
            require (WB_PATH."/templates/".TEMPLATE."/info.php");
            if ($key = array_search($uBlock, $block))
                return (int) $key ;
            else 
                return 1;
        }
    }
}

if (!function_exists('get_menu_id')) {
/**
    @brief function to extract Block ID from Blockname 
    
    If fed whith an integer or numeric string it simply returns this as an integer.
    If fed whith a string it tries to find this string in the $block[] array of the recent template.
    It returns the id if found otherwise it returns 1 (default template block)
    
    @param unknown $uBlock may be string or integer
    @return integer 
*/
    function get_menu_id ($uMenu) {
        if (is_int($uMenu) OR preg_match("/^[0-9]+$/is", $uMenu))
            return (int)$uMenu;
        else {
            require (WB_PATH."/templates/".TEMPLATE."/info.php");
            if ($key = array_search($uMenu, $menu))
                return (int) $key ;
            else 
                return 0;
        }
    }
}


if (!function_exists('page_content')) {
/**
    @brief  This functions fetches the page content for the different blocks of a page/template. 
    
    It now alllows to enter block names as well as block numbers.
    The second parameter lets the function return the result instead of printing it immediately. 

    @global array $TEXT
    @global array $MENU
    @global array $HEADING
    @global array $MESSAGE
    @global array $globals several global vars
    @global datadase $database
    @global wb $wb
    @global string $global_name
    
    @param int/string $block   Template/page block, name or id 
    @return void/string returns either nothing or the whole block content as returnvalue
*/
    function page_content($block = 1, $echo=true)
    {
        $block =  get_block_id ($block);

        // We don't want to display the page content 
        // We want it as a return value  
        if ($echo===false) {
            ob_start();		
            page_content($uBlock);
            $out = ob_get_contents();
            ob_end_clean();
            return $out; 
        }

        // Get outside objects
        global $TEXT, $MENU, $HEADING, $MESSAGE;
        global $globals;
        global $database;
        global $wb;
        $admin = $wb;
        if ($wb->page_access_denied == true) {
            echo $MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'];
            return;
        }
        if ($wb->page_no_active_sections == true) {
            echo $MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'];
            return;
        }
        if (isset($globals) and is_array($globals)) {
            foreach ($globals as $global_name) {
                global $$global_name;
            }
        }

        // Include page content
        if (!defined('PAGE_CONTENT') or $block != 1) {
            $page_id = intval($wb->page_id);
            
            // page is invisible ??
            if (($wb instanceof frontend) && !$wb->page_is_visible($wb->page)) {
                // SOLVED dw2015
                return;
            }

            // First get all sections for this page
            $sql = 'SELECT `section_id`, `module`, `publ_start`, `publ_end` ';
            $sql .= 'FROM `' . TABLE_PREFIX . 'sections` ';
            $sql .= 'WHERE `page_id`=' . $page_id . ' AND `block`=' . $block . ' ';
            $sql .= 'ORDER BY `position`';
            if (!($query_sections = $database->query($sql))) {return;}
            // If none were found, check if default content is supposed to be shown
            if ($query_sections->numRows() == 0) {
                if ($wb->default_block_content == 'none') {return;}
                if (is_numeric($wb->default_block_content)) {
                    $page_id = $wb->default_block_content;
                } else {
                    $page_id = $wb->default_page_id;
                }
                $sql = 'SELECT `section_id`, `module`, `publ_start`, `publ_end` ';
                $sql .= 'FROM `' . TABLE_PREFIX . 'sections` ';
                $sql .= 'WHERE `page_id`=' . $page_id . ' AND `block`=' . $block . ' ';
                $sql .= 'ORDER BY `position`';
                if (!($query_sections = $database->query($sql))) {return;}
                // Still no cotent found? Give it up, there's just nothing to show!
                if ($query_sections->numRows() == 0) {return;}
            }
            
            // Loop through them and include their module file
            while ($section = $query_sections->fetchRow()) {
                // skip this section if it is out of publication-date
                $now = time();
                if (!(($now <= $section['publ_end'] || $section['publ_end'] == 0) && ($now >= $section['publ_start'] || $section['publ_start'] == 0))) {
                    continue;
                }
                $section_id = $section['section_id'];
                $module = $section['module'];
                $sec_anchor = '';
                if (defined('SEC_ANCHOR') && SEC_ANCHOR != '') {
                    $sec_anchor = '<a class="section_anchor" id="' . SEC_ANCHOR . $section_id . '" ></a>';
                }
                // check if module exists - feature: write in errorlog
                if (file_exists(WB_PATH . '/modules/' . $module . '/view.php')) {
                                // make a anchor for every section.
                                // fetch content -- this is where to place possible output-filters (before highlighting)
                    ob_start(); // fetch original content
                    require WB_PATH . '/modules/' . $module . '/view.php';
                    $content = ob_get_clean();

                    //OPF hook
                    if(function_exists('opf_apply_filters')) {
                        $content = opf_controller('section', $content, $module, $page_id, $section_id);
                    }

                } else {
                    continue;
                }
                // highlights searchresults
                if (isset($_GET['searchresult']) && is_numeric($_GET['searchresult']) && !isset($_GET['nohighlight']) && isset($_GET['sstring']) && !empty($_GET['sstring'])) {
                    $arr_string = explode(" ", $_GET['sstring']);
                    if ($_GET['searchresult'] == 2) {
                        // exact match
                        $arr_string[0] = str_replace("_", " ", $arr_string[0]);
                    }
                    // OPF Hook, Apply Filters
                    if(function_exists('opf_apply_filters')) {
                       $content = opf_controller('special', $content);
                    }
                    echo search_highlight($content, $arr_string);
                } else {
		    // no search result, OPF filters have been applied already above
                    echo PHP_EOL . $sec_anchor . PHP_EOL . $content;
                }                                  
            }
        } else {
                   // Searchresults! But also some special pages, e.g. guestbook (add entry), news (add comment) uses this
                   ob_start(); // fetch original content
                   require(PAGE_CONTENT);
                   $content = ob_get_contents();
                   ob_end_clean();
                   // Apply Filters
                   if(function_exists('opf_apply_filters')) {
                       $content = opf_controller('special', $content);
                   }
                   // Print Content
                   echo $content;
        }
    }
}

// Function for page title
if (!function_exists('page_title')) {
    function page_title($spacer = ' - ', $template = '[WEBSITE_TITLE][SPACER][PAGE_TITLE]')
    {
        $vars = array('[WEBSITE_TITLE]', '[PAGE_TITLE]', '[MENU_TITLE]', '[SPACER]');
        $values = array(WEBSITE_TITLE, PAGE_TITLE, MENU_TITLE, $spacer);
        echo str_replace($vars, $values, $template);
    }
}

// Function for page description
if (!function_exists('page_description')) {
    function page_description()
    {
        global $wb;
        if ($wb->page_description != '') {
            echo $wb->page_description;
        } else {
            echo WEBSITE_DESCRIPTION;
        }
    }
}

// Function for page keywords
if (!function_exists('page_keywords')) {
    function page_keywords()
    {
        global $wb;
        if ($wb->page_keywords != '') {
            echo $wb->page_keywords;
        } else {
            echo WEBSITE_KEYWORDS;
        }
    }
}

// Function for page header
if (!function_exists('page_header')) {
    function page_header($date_format = 'Y')
    {
        echo WEBSITE_HEADER;
    }
}

// Function for page footer
if (!function_exists('page_footer')) {
    function page_footer($date_format = 'Y')
    {
        global $starttime;
        $vars = array('[YEAR]', '[PROCESS_TIME]');
        $processtime = array_sum(explode(" ", microtime())) - $starttime;
        $values = array(gmdate($date_format), $processtime);
        echo str_replace($vars, $values, WEBSITE_FOOTER);
    }
}

function bind_jquery($file_id = 'jquery')
{

    $jquery_links = '';
    /* include the Javascript jquery api  */
    if ($file_id == 'jquery' and file_exists(WB_PATH . '/include/jquery/jquery-min.js')) {
        $wbpath = str_replace('\\', '/', WB_PATH); // fixed localhost problem with ie
        $jquery_links .= "<script type=\"text/javascript\">\n"
        . "var URL = '" . WB_URL . "';\n"
        /* ."var WB_PATH = '".$wbpath."';\n" */
        . "var WB_URL = '" . WB_URL . "';\n"
        . "var TEMPLATE_DIR = '" . TEMPLATE_DIR . "';\n"
        . "</script>\n";

        $jquery_links .= '<script src="' . WB_URL . '/include/jquery/jquery-min.js" type="text/javascript"></script>' . "\n";
        $jquery_links .= '<script src="' . WB_URL . '/include/jquery/jquery-insert.js" type="text/javascript"></script>' . "\n";
        $jquery_links .= '<script src="' . WB_URL . '/include/jquery/jquery-include.js" type="text/javascript"></script>' . "\n";
        $jquery_links .= '<script src="' . WB_URL . '/include/jquery/jquery-migrate-min.js" type="text/javascript"></script>' . "\n";
        
        /* workout to insert ui.css and theme */
        $jquery_theme = WB_PATH . '/modules/jquery/jquery_theme.js';
        $jquery_links .= file_exists($jquery_theme)
        ? '<script src="' . WB_URL . '/modules/jquery/jquery_theme.js" type="text/javascript"></script>' . "\n"
        : '<script src="' . WB_URL . '/include/jquery/jquery_theme.js" type="text/javascript"></script>' . "\n";
        /* workout to insert plugins functions, set in templatedir */
        $jquery_frontend_file = TEMPLATE_DIR . '/jquery_frontend.js';
        $jquery_links .= file_exists(str_replace(WB_URL, WB_PATH, $jquery_frontend_file))
        ? '<script src="' . $jquery_frontend_file . '" type="text/javascript"></script>' . "\n"
        : '';
    }
    return $jquery_links;
}

// Function to add optional module Javascript into the <body> section of the frontend
if (!function_exists('register_frontend_modfiles_body')) {
    function register_frontend_modfiles_body($file_id = "js")
    {
        // sanity check of parameter passed to the function
        $file_id = strtolower($file_id);
        if ($file_id !== "css" && $file_id !== "javascript" && $file_id !== "js" && $file_id !== "jquery") {
            return;
        }

        global $wb, $database, $include_body_links;
        // define default baselink and filename for optional module javascript files
        $body_links = "";

        /* include the Javascript jquery api  */
        $body_links .= bind_jquery($file_id);

        if ($file_id !== "css" && $file_id == "js" && $file_id !== "jquery") {
            $base_link = '<script src="' . WB_URL . '/modules/{MODULE_DIRECTORY}/frontend_body.js" type="text/javascript"></script>';
            $base_file = "frontend_body.js";

            // ensure that frontend_body.js is only added once per module type
            if (!empty($include_body_links)) {
                if (strpos($body_links, $include_body_links) === false) {
                    $body_links .= $include_body_links;
                }
                $include_body_links = '';
            }

            // gather information for all models embedded on actual page
            $page_id = $wb->page_id;
            $sql = 'SELECT `module` FROM `' . TABLE_PREFIX . 'sections` ';
            $sql .= 'WHERE `page_id` = ' . (int) $page_id . ' AND `module`<>\'wysiwyg\'';
            if (($query_modules = $database->query($sql))) {
                while ($row = $query_modules->fetchRow()) {
                    // check if page module directory contains a frontend_body.js file
                    if (file_exists(WB_PATH . "/modules/" . $row['module'] . "/$base_file")) {
                        // create link with frontend_body.js source for the current module
                        $tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);

                        // ensure that frontend_body.js is only added once per module type
                        if (strpos($body_links, $tmp_link) === false) {
                            $body_links .= $tmp_link;
                        }
                    }
                }
            }
        }

        print $body_links . "\n";
    }
}

// Function to add optional module Javascript or CSS stylesheets into the <head> section of the frontend
if (!function_exists('register_frontend_modfiles')) {
    function register_frontend_modfiles($file_id = "css")
    {
        // sanity check of parameter passed to the function
        $file_id = strtolower($file_id);
        if ($file_id !== "css" && $file_id !== "javascript" && $file_id !== "js" && $file_id !== "jquery") {
            return;
        }

        global $wb, $database, $include_head_link_css, $include_head_links;
        // define default baselink and filename for optional module javascript and stylesheet files
        $head_links = "";

        switch ($file_id) {
        case 'css':
            $base_link = '<link href="' . WB_URL . '/modules/{MODULE_DIRECTORY}/frontend.css"';
            $base_link .= ' rel="stylesheet" type="text/css" media="screen" />';
            $base_file = "frontend.css";
            if (!empty($include_head_link_css)) {
                $head_links .= !strpos($head_links, $include_head_link_css) ? $include_head_link_css : '';
                $include_head_link_css = '';
            }
            break;
        case 'jquery':
            $head_links .= bind_jquery($file_id);
            break;
        case 'js':
            $base_link = '<script src="' . WB_URL . '/modules/{MODULE_DIRECTORY}/frontend.js" type="text/javascript"></script>';
            $base_file = "frontend.js";
            if (!empty($include_head_links)) {
                $head_links .= !strpos($head_links, $include_head_links) ? $include_head_links : '';
                $include_head_links = '';
            }
            break;
        default:
            break;
        }

        if ($file_id != 'jquery') {
            // gather information for all models embedded on actual page
            $page_id = $wb->page_id;
            $sql = 'SELECT `module` FROM `' . TABLE_PREFIX . 'sections` ';
            $sql .= 'WHERE `page_id` = ' . (int) $page_id . ' AND `module`<>\'wysiwyg\'';
            if (($query_modules = $database->query($sql))) {
                while ($row = $query_modules->fetchRow()) {
                    // check if page module directory contains a frontend.js or frontend.css file
                    if (file_exists(WB_PATH . "/modules/" . $row['module'] . "/$base_file")) {
                        // create link with frontend.js or frontend.css source for the current module
                        $tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);

                        // ensure that frontend.js or frontend.css is only added once per module type
                        if (strpos($head_links, $tmp_link) === false) {
                            $head_links .= $tmp_link . "\n";
                        }
                    }
                    ;
                }
            }
           
        }
        print $head_links;
    }
}
