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
if(count(get_included_files())==1) die(header("Location: ../index.php", TRUE, 301));

// since we are in the FRONTEND set WB_FRONTEND constant
defined('WB_FRONTEND') or define('WB_FRONTEND', true);

// Compatibility mode for older versions.
// (The News module still needs it for example.)
if (isset($wb)) 
	$admin = $wb;
if (isset($wb->default_link)) 
	$default_link = $wb->default_link;
if (isset($wb->page_trail)) 
	$page_trail = $wb->page_trail;
if (isset($wb->page_description)) 
	$page_description = $wb->page_description;
if (isset($wb->page_keywords)) 
	$page_keywords = $wb->page_keywords;
if (isset($wb->link)) 
	$page_link = $wb->link;


// Load Snippet Type modules into the frontend
$sSql = 'SELECT `directory` FROM `{TP}addons` WHERE function LIKE \'%snippet%\' ';
if (($resSnippets = $database->query($sSql))) {
    while ($rec = $resSnippets->fetchRow()) {
        $sFile = WB_PATH . '/modules/' . $rec['directory'] . '/include.php';
        if (file_exists($sFile)) include $sFile;
    }
}

// Frontend functions
if (!function_exists('page_link')) {

    /**
     * @brief   Generate full page_link based on the 
     *          `link` content from the `{TP}pages` table
     * 
     * @param   unspec $uLinkId  
     * @return  string
     */
    function page_link($uLinkId = NULL)
    {
        return $GLOBALS['wb']->page_link($link);
    }
}

if (!function_exists('get_page_link')) {
    /**
     * @brief  get `link` entry from database `{TP}pages` table using page_id
     * 
     * @global object $database
     * @param  int $id
     * @return string
     */
    function get_page_link($page_id)
    {
        $sSql = 'SELECT `link` FROM `{TP}pages` WHERE `page_id` = %d';
        return $GLOBALS['database']->get_one(sprintf($sSql, $page_id));
    }
}

//function to highlight search results
if (!function_exists('search_highlight')) {
    /**
     *
     * @staticvar  bool    $string_ul_umlaut
     * @staticvar  bool    $string_ul_regex
     * @param      string  $foo
     * @param      array   $arr_string
     * @return     string
     */
    function search_highlight($foo = '', $arr_string = array())
    {
        require_once WB_PATH . '/framework/functions.php';
        static $string_ul_umlaut = false;
        static $string_ul_regex  = false;
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
     * @brief  Function to extract Block ID from Blockname 
     *         If fed whith an integer or numeric string it simply returns this as an integer.
     *         If fed whith a string it tries to find this string in the $block[] array of the current template.
     *         It returns the id if found otherwise it returns 1 (default template block)
     *
     * @param  unspec $uMenu may be string or integer
     * @return int
     */
    function get_block_id ($uBlock) {
        if (is_int($uBlock) OR preg_match("/^[0-9]+$/is", $uBlock)){
            return (int)$uBlock;
		} else {
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
     * @brief  Function to extract Menu ID from Menuname 
     *         If fed whith an integer or numeric string it simply returns this as an integer.
     *         If fed whith a string it tries to find this string in the $menu[] array of the current template.
     *         It returns the id if found otherwise it returns 0 (default menu id)
     *
     * @param  unspec $uMenu may be string or integer
     * @return integer
     */
    function get_menu_id ($uMenu) {
        if (is_int($uMenu) OR preg_match("/^[0-9]+$/is", $uMenu)){
            return (int)$uMenu;
		} else {
            require (WB_PATH."/templates/".TEMPLATE."/info.php");
            if ($key = array_search($uMenu, $menu))
                return (int) $key ;
            else 
                return 0;
        }
    }
}


if (!function_exists('get_section_array')) {

    function get_section_array($iSectionID){
        $aSection = array();
        if(isset($iSectionID) && $iSectionID > 0){
            global $database;			
            $sSql = 'SELECT * FROM `{TP}sections` WHERE `section_id`=%d';
            if($rSections = $database->query(sprintf($sSql, (int) $iSectionID))){
                $aSection  = $rSections->fetchRow(MYSQL_ASSOC);
            }
        }		
        return $aSection; 
    }
}

if (!function_exists('get_section_content')) {	
    function get_section_content($iSectionID, $bUseSecAnchor = false, $sAnchorID = ""){
        $sContent = '';
        $aSection = get_section_array($iSectionID);

        // check if module exists
        $sModuleViewFile = WB_PATH . '/modules/' . $aSection['module'] . '/view.php';
        if (is_readable($sModuleViewFile)) {
            ob_start(); 
            $page_id    = $iPageID = $aSection['page_id'];
            $section_id = $iSectionID;
            // we need those global vars to correctly operate the view.php
            global $database, $wb, $TEXT, $MENU, $HEADING, $MESSAGE;
            $admin = $wb;
            require $sModuleViewFile; // fetch content using the view.php
            $sContent = ob_get_clean();

            // use OPF hook
            if(function_exists('opf_apply_filters')) {
                $sContent = opf_controller('section', $sContent, $aSection['module'], $iPageID, $iSectionID);
            }
            if($bUseSecAnchor == true || $sAnchorID != ''){
                $sAnchorTPL = '<a class="section_anchor" id="%s" ></a>';
                if (defined('SEC_ANCHOR') && SEC_ANCHOR != '') {
                    $sSecAnchor = sprintf($sAnchorTPL, SEC_ANCHOR . $aSection['section_id']);
                }
                if($sAnchorID != ''){
                    $sSecAnchor = sprintf($sAnchorTPL, $sAnchorID);
                }
                $sContent = $sSecAnchor.$sContent;
            }

            $aToInsert = $GLOBALS['wb']->retrieve_modfiles_from_dir($aSection['module'], "frontend");
            foreach($aToInsert as $sModfileType=>$sFile){
                switch ($sModfileType) {
                    case 'css':     I::insertCssFile($sFile[0], 'HEAD TOP-'); break;					
                    case 'js_head':	I::insertJsFile($sFile[0],  'HEAD BTM-'); break;					
                    case 'js_body':	I::insertJsFile($sFile[0],  'BODY BTM-'); break;
                    default: break;
                }
            }
        }
        return $sContent;
    }
}
echo get_section_content(8);
if (!function_exists('block_contents')) {
    /**
     *	@brief  This functions fetches the sections of a block as array.  
     *
     *	@global  array   $globals several global vars
     *	@global  object  $database
     *	@global  object  $wb
     *	@global  array   $TEXT
     *	@global  array   $MENU
     *	@global  array   $HEADING
     *	@global  array   $MESSAGE
     *	@global  string  $global_name
     *	@param   unspec  $uBlock   Template layout block-name or block-id 
     *	@return  array   whole array of sections contained in a block
     */
    function block_contents($uBlock = 1){
        global $globals, $database, $wb, $TEXT, $MENU, $HEADING, $MESSAGE;
        $admin = $wb;
        $iBlockID =  is_numeric($uBlock) ? $uBlock : get_block_id($uBlock);

        $aBlockSections = array();
        if ($wb->page_access_denied == true) {
            echo $MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'];
            return;
        }
        if ($wb->page_no_active_sections == true) {
            echo $MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'];
            return;
        }
        if (isset($globals) and is_array($globals)) {
            foreach ($globals as $sGlobalName) {
                global $$sGlobalName;
            }
        }

        // Include page content
        if (!defined('PAGE_CONTENT')) {

            // return if page is not visible
            if (($wb instanceof frontend) && !$wb->page_is_visible($wb->page)) {
                return;
            }

            // First get all sections for this page
            $sSql = 'SELECT `section_id` FROM `{TP}sections` 
                        WHERE `page_id`=%d AND `block`=%d
                        ORDER BY `position`';

            $iPageID = $page_id = $wb->page_id;
            if (!($rSections = $database->query(sprintf($sSql, $iPageID, $iBlockID)))) {
                return; // return if no results
            }

            // If no sections were found, check if default content is supposed to be shown
            if ($rSections->numRows() == 0) {
                if ($wb->default_block_content == 'none') {
                    return;
                }
                $iPageID = $wb->default_page_id;
                if (is_numeric($wb->default_block_content)) {
                    $iPageID = $wb->default_block_content;
                }

                if (!($rSections = $database->query(sprintf($sSql, $iPageID, $iBlockID)))) {
                    return;
                }
                // Still no cotent found? Give it up, there's just nothing to show!
                if ($rSections->numRows() == 0) {
                    return;
                }
            }

            // Loop through sections
            while ($row = $rSections->fetchRow(MYSQL_ASSOC)) {
                $iSectionID = $row['section_id'];
                $aSection = get_section_array($iSectionID);

                // skip this section if it is out of publication date
                $now = time();
                if (!(
                        ($now <= $aSection['publ_end'] || $aSection['publ_end'] == 0) 
                                && 
                        ($now >= $aSection['publ_start'] || $aSection['publ_start'] == 0)
                    )) {
                    continue;
                }
                $sContent = get_section_content($aSection['section_id']);
                if($sContent == '') {
                    continue;
                }
                $sec_anchor = '';
                if (defined('SEC_ANCHOR') && SEC_ANCHOR != '') {
                    $sec_anchor = '<a class="section_anchor" id="' . SEC_ANCHOR . $aSection['section_id'] . '" ></a>';
                }
                // highlights searchresults
                if (isset($_GET['searchresult']) && is_numeric($_GET['searchresult']) && !isset($_GET['nohighlight']) && isset($_GET['sstring']) && !empty($_GET['sstring'])) {
                    $arr_string = explode(" ", $_GET['sstring']);
                    if ($_GET['searchresult'] == 2) {
                            // exact match
                            $arr_string[0] = str_replace("_", " ", $arr_string[0]);
                    }
                    $aBlockSections[]['content'] = search_highlight($sContent, $arr_string);
                } else {
                    // OPF Hook, Apply Filters
                    if(function_exists('opf_apply_filters')) {
                       $sContent = opf_controller('special', $sContent);
                    }
                    $aBlockSections[$iSectionID]['position']     = $aSection['position'];
                    $aBlockSections[$iSectionID]['section_id']   = $aSection['section_id'];
                    $aBlockSections[$iSectionID]['module']       = $aSection['module'];
                    $aBlockSections[$iSectionID]['section_name'] = $aSection['namesection'];
                    $aBlockSections[$iSectionID]['evenodd']      = ($aSection['position'] %2 == 1) ? 'even' : 'odd';
                    if($sec_anchor != ''){
                        $aBlockSections[$iSectionID]['content'] = PHP_EOL . $sec_anchor . PHP_EOL . $sContent;
                    } else {
                        $aBlockSections[$iSectionID]['content'] = $sContent;
                    }
                }                                  
            }
        } else {
            ob_start();
            require PAGE_CONTENT;
            $aBlockSections[]['content'] = ob_get_clean();
        }
        return $aBlockSections;
    }
}

if (!function_exists('page_content')) {
    /**
     * @brief   return or echo all the sections of one block into the template		   
     *	        It now alllows to enter block names as well as block numbers.
     *	        The second parameter lets the function return the result instead of printing it immediately.
     *
     * @param  unspec  $uBlock   Block ID or Block name
     * @param  bool    $bEcho    echo the contents 
     *                           or set to 0 if you want to fetch the contents into a variable
     * @return string 
     */
    function page_content($uBlock = 1, $bEcho = 1){		
        $iBlockID =  get_block_id($uBlock);
        $sRetVal = '';
        if (!defined('PAGE_CONTENT')) {
            $aSections = block_contents($iBlockID);
            if(!empty($aSections)) {
                foreach($aSections as $section){
                    $sRetVal .= $section['content'];
                }
            }
        } else {
            ob_start();
            require PAGE_CONTENT;
            $sRetVal .= ob_get_clean();
        }
        // echo or return
        if($bEcho == 1) 
            echo $sRetVal;	
        else 
            return $sRetVal;
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


// Function to add optional module Javascript or CSS stylesheets into the <head> section of the frontend
if (!function_exists('register_frontend_modfiles')) {
    function register_frontend_modfiles($sModfileType = "css"){        
        return $GLOBALS['wb']->register_modfiles($sModfileType, "frontend");
    }
}

// Function to add optional module Javascript into the <body> section of the frontend
if (!function_exists('register_frontend_modfiles_body')) {
    function register_frontend_modfiles_body($sModfileType = "js"){
        return;
    }
}