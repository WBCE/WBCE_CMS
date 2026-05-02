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

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

// Compatibility mode for older versions.
// (The News module still needs it for example.)
if (isset($wb)) {
    $admin = $wb; // note: this is not a real object of class Admin.
}
if (isset($wb->default_link)) {
    $default_link = $wb->default_link;
}
if (isset($wb->page_trail)) {
    $page_trail = $wb->page_trail;
}
if (isset($wb->page_description)) {
    $page_description = $wb->page_description;
}
if (isset($wb->page_keywords)) {
    $page_keywords = $wb->page_keywords;
}
if (isset($wb->link)) {
    $page_link = $wb->link;
}


// Load Snippet Type modules into the frontend
$snippets = $database->fetchAll("SELECT `directory` FROM `{TP}addons` WHERE `function` LIKE '%snippet%'");
foreach ($snippets as $rec) {
    $file = WB_PATH . '/modules/' . $rec['directory'] . '/include.php';
    if (file_exists($file)) {
        include $file;
    }
}

// Frontend functions
if (!function_exists('page_link')) {

    /**
     * @brief   Generate full page_link based on the
     *          `link` content from the `{TP}pages` table
     *
     * @param unspec $uLinkId
     * @return  string
     */
    function page_link($uLinkId = null)
    {
        return $GLOBALS['wb']->page_link($link);
    }
}

if (!function_exists('get_page_link')) {
    /**
     * @brief  get `link` entry from database `{TP}pages` table using page_id
     *
     * @param int $id
     * @return string
     * @global object $database
     */
    function get_page_link($page_id)
    {
        return $GLOBALS['database']->fetchValue(
            'SELECT `link` FROM `{TP}pages` WHERE `page_id` = ?',
            [(int)$page_id]
        );
    }
}

//function to highlight search results
if (!function_exists('search_highlight')) {
    /**
     *
     * @staticvar  bool    $string_ul_umlaut
     * @staticvar  bool    $string_ul_regex
     * @param string $foo
     * @param array $arr_string
     * @return     string
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
        array_walk($arr_string, function (&$v, $k) {
            $v = preg_quote($v, '\'~\'');
        });
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
                if ($match[0] != "<" && !preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,8}$/i', $match) && !preg_match('~\[\[.*\]\]~', $match)) {
                    $match = str_replace(array('&lt;', '&gt;', '&amp;', '&quot;', '&#039;', '&nbsp;'), array('<', '>', '&', '"', '\'', "\xC2\xA0"), $match);
                    $match = preg_replace('~(' . $string . ')~ui', '_span class=_highlight__$1_/span_', $match);
                    //$match = str_replace(array('&', '<', '>', '"', '\'', "\xC2\xA0"), array('&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '&nbsp;'), $match);
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
     * @param unspec $uMenu may be string or integer
     * @return int
     */
    function get_block_id($uBlock)
    {
        if (is_int($uBlock) or preg_match("/^[0-9]+$/is", $uBlock)) {
            return (int)$uBlock;
        } else {
            require(WB_PATH . "/templates/" . TEMPLATE . "/info.php");
            if ($key = array_search($uBlock, $block)) {
                return (int)$key;
            } else {
                return 1;
            }
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
     * @param unspec $uMenu may be string or integer
     * @return integer
     */
    function get_menu_id($uMenu)
    {
        if (is_int($uMenu) or preg_match("/^[0-9]+$/is", $uMenu)) {
            return (int)$uMenu;
        } else {
            require(WB_PATH . "/templates/" . TEMPLATE . "/info.php");
            if ($key = array_search($uMenu, $menu)) {
                return (int)$key;
            } else {
                return 0;
            }
        }
    }
}


if (!function_exists('get_section_array')) {
    /**
     * @brief  Get Array with all the details of a section by using the section_id.
     *
     * @param integer $iSectionID
     * @return array
     */
    function get_section_array($iSectionID)
    {
        $aSection = array();
        if (isset($iSectionID) && $iSectionID > 0) {
            global $database;
            $sSql = 'SELECT * FROM `{TP}sections` WHERE `section_id` = ?';
            if ($rSections = $database->query($sSql, [(int)$iSectionID])) {
                $aSection = $rSections->fetchRow(MYSQLI_ASSOC);
            }
        }
        return $aSection;
    }
}

/**
 * Registers CSS and JS modfiles for a given module (frontend).
 *
 * @param string $moduleDir
 * @return void
 */
function register_module_modfiles(string $moduleDir): void
{
    if (empty($moduleDir)) {
        return;
    }

    $toInsert = $GLOBALS['wb']->retrieveModfilesFromDir($moduleDir, 'frontend');

    foreach ($toInsert as $assetType => $files) {
        $assetType = strtolower($assetType);

        foreach ($files as $file) {
            // $file[0] contains the actual file URL (backward compatible format)
            $fileUrl = $file[0] ?? $file;   // Sicherheit: falls mal ein String kommt

            match ($assetType) {
                'css'      => I::insertCssFile($fileUrl, 'HEAD MODFILES'),
                'js_head'  => I::insertJsFile($fileUrl, 'HEAD MODFILES'),
                'js_body'  => I::insertJsFile($fileUrl, 'BODY MODFILES'),
                default    => null,
            };
        }
    }
}

/**
 * Renders the content of a specific section by including its module's view.php.
 *
 * Handles output buffering, OPF filters, section anchor and automatic modfile registration.
 *
 * @param int    $sectionId      Section ID to render
 * @param bool   $useSecAnchor   Whether to add the section anchor (if SEC_ANCHOR is defined)
 * @param string $anchorId       Custom anchor ID (overrides automatic anchor)
 * @return string                Rendered section content
 */
function get_section_content(int $sectionId, bool $useSecAnchor = false, string $anchorId = ''): string
{
    $section = get_section_array($sectionId);
    if (empty($section) || empty($section['module'])) {
        return '';
    }

    $moduleViewFile = WB_PATH . '/modules/' . $section['module'] . '/view.php';

    if (!is_readable($moduleViewFile)) {
        return '';
    }

    // Setup environment for legacy module view.php
    ob_start();

    $page_id    = $pageId    = (int)$section['page_id'];
    $section_id = $sectionId;

    global $database, $wb, $globals, $TEXT, $MENU, $HEADING, $MESSAGE;
    $admin = $wb;

    if (isset($globals) && is_array($globals)) {
        foreach ($globals as $globalName) {
            global $$globalName;
        }
    }

    require $moduleViewFile;
    $content = ob_get_clean();

    // Apply OPF section filter
    if (function_exists('opf_apply_filters')) {
        $content = opf_controller('section', $content, $section['module'], $pageId, $sectionId);
    }

    // Add section anchor
    if ($useSecAnchor || $anchorId !== '') {
        $secAnchorLayout = '<a class="section_anchor" id="%s"></a>';

        if ($anchorId !== '') {
            $sectionAnchor = sprintf($secAnchorLayout, $anchorId);
        } elseif (defined('SEC_ANCHOR') && SEC_ANCHOR !== '') {
            $sectionAnchor = sprintf($secAnchorLayout, SEC_ANCHOR . $section['section_id']);
        } else {
            $sectionAnchor = '';
        }

        if ($sectionAnchor !== '') {
            $content = $sectionAnchor . $content;
        }
    }

    // Register module modfiles (CSS/JS)
    register_module_modfiles($section['module']);

    return $content;
}

/**
 * Fetches all sections of a template layout block and returns them as an array.
 *
 * @param int|string $block  Block ID (numeric) or block name
 * @return array|null        Sections array, or null on permission/visibility failure.
 *                           Returns null (not []) so template checks like
 *                           if (!block_contents()) work correctly.
 */
function block_contents($block = 1): ?array
{
    global $database, $wb, $TEXT, $MESSAGE, $HEADING, $MENU;
    $admin = $wb;

    $blockId = is_numeric($block) ? (int)$block : get_block_id($block);

    $blockSections = [];

    // Early exits for permission / visibility issues
    if ($wb->page_access_denied ?? false) {
        echo $MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'] ?? 'No viewing permissions';
        return null;
    }

    if ($wb->page_no_active_sections ?? false) {
        echo $MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'] ?? 'No active sections';
        return null;
    }

    // Special case: full page content via PAGE_CONTENT constant
    if (defined('PAGE_CONTENT')) {
        ob_start();
        require PAGE_CONTENT;
        $blockSections[]['content'] = ob_get_clean();
        return $blockSections;
    }

    // Return null if current page is not visible
    if (($wb instanceof frontend) && !$wb->page_is_visible($wb->page ?? [])) {
        return null;
    }

    // Get sections for this block
    $sql = 'SELECT `section_id` FROM `{TP}sections`
            WHERE `page_id` = ? AND `block` = ?
            ORDER BY `position`';

    $pageId = $page_id = $wb->page_id ?? 0;

    $sectionsResult = $database->query($sql, [$pageId, $blockId]);

    if (!$sectionsResult || $sectionsResult->numRows() === 0) {
        // Try default block content
        if (($wb->default_block_content) === intval(0)) {
            return null;
        }

        $pageId = is_numeric($wb->default_block_content ?? null)
            ? (int)$wb->default_block_content
            : ($wb->default_page_id ?? 0);

        $sectionsResult = $database->query($sql, [$pageId, $blockId]);

        if (!$sectionsResult || $sectionsResult->numRows() === 0) {
            return null;
        }
    }

    // Process each section
    while ($row = $sectionsResult->fetchRow(MYSQLI_ASSOC)) {
        $sectionId = (int)$row['section_id'];
        $section   = get_section_array($sectionId);

        if (empty($section)) continue;

        // Skip section if outside publication period
        $currentTime = time();
        if (!(
            ($currentTime <= ($section['publ_end']   ?? 0) || ($section['publ_end']   ?? 0) == 0) &&
            ($currentTime >= ($section['publ_start'] ?? 0) || ($section['publ_start'] ?? 0) == 0)
        )) {
            continue;
        }

        // Render section content via get_section_content()
        // (handles view.php, OPF 'section' filter, globals, modfile registration)
        $content = get_section_content($sectionId);

        if ($content === '') continue;

        // Search result highlighting — mutually exclusive with OPF 'special' filter
        if (isset($_GET['searchresult']) && is_numeric($_GET['searchresult'])
            && !isset($_GET['nohighlight']) && !empty($_GET['sstring'])) {

            $searchString = filter_input(INPUT_GET, 'sstring', FILTER_SANITIZE_ENCODED);
            $searchString = str_replace('%', '', $searchString);
            $searchTerms  = explode(' ', $searchString);

            if ($_GET['searchresult'] == 2) {
                $searchTerms[0] = str_replace('_', ' ', $searchTerms[0] ?? '');
            }

            $blockSections[]['content'] = search_highlight($content, $searchTerms);
            continue;
        }

        // Apply OPF 'special' filter — only outside search-highlight path
        if (function_exists('opf_apply_filters')) {
            $content = opf_controller('special', $content);
        }

        // Build section anchor
        $sectionAnchor = '';
        if (defined('SEC_ANCHOR') && SEC_ANCHOR !== '') {
            $sectionAnchor = '<a class="section_anchor" id="' . SEC_ANCHOR . $section['section_id'] . '"></a>';
        }

        $blockSections[$sectionId] = [
            'position'     => $section['position']   ?? 0,
            'section_id'   => $section['section_id'] ?? $sectionId,
            'module'       => $section['module']      ?? '',
            'section_name' => $section['namesection'] ?? '',
            'evenodd'      => (($section['position'] ?? 0) % 2 === 1) ? 'even' : 'odd',
            'content'      => $sectionAnchor !== ''
                ? PHP_EOL . $sectionAnchor . PHP_EOL . $content
                : $content,
        ];
    }

    return $blockSections;
}

if (!function_exists('page_content')) {
    /**
     * @brief   print or return (e.g. into a variable for later use)
     *          all the sections of one block into the template
     *          It now alllows to enter block names as well as block numbers.
     *          The second parameter lets the function return the result instead of printing it immediately.
     *
     * @param unspec $uBlock Block ID or Block name
     * @param bool $bPrint echo the contents
     *                      or set to 0 if you want to fetch the contents into a variable
     * @return string
     * @global  object $wb       |   The global variables are
     * @global  object $database |   optional and will be used
     * @global  array $globals   |   only in case PAGE_CONTENT
     * @global  array $TEXT      |   (this are the special pages
     * @global  array $MESSAGE   |   like search, account etc.)
     * @global  array $HEADING   |   is defined.
     * @global  array $MENU      |
     *
     */
    function page_content($uBlock = 1, $bPrint = 1)
    {
        $iBlockID = get_block_id($uBlock);
        $sRetVal = '';
        if (!defined('PAGE_CONTENT')) {
            $aSections = block_contents($iBlockID);
            if (!empty($aSections)) {
                foreach ($aSections as $section) {
                    $sRetVal .= $section['content'];
                }
            }
        } else {
            // PAGE_CONTENT is defined
            // it will go into the block with ID = 1
            if ($iBlockID == 1) {
                ob_start();
                global $wb, $database, $globals, $TEXT, $MESSAGE, $HEADING, $MENU;
                $admin = $wb;
                if (isset($globals) and is_array($globals)) {
                    foreach ($globals as $sGlobalName) {
                        global $$sGlobalName;
                    }
                }
                require PAGE_CONTENT;
                $sRetVal .= ob_get_clean();
            }
        }
        // echo or return
        if ($bPrint == 1) {
            echo $sRetVal;
        } else {
            return $sRetVal;
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
        $values = array(date($date_format), $processtime);
        echo str_replace($vars, $values, WEBSITE_FOOTER);
    }
}


// Function to add optional module Javascript or CSS stylesheets into the <head> section of the frontend
if (!function_exists('register_frontend_modfiles')) {
    function register_frontend_modfiles($sModfileType = "css")
    {
        if ($sModfileType != 'jquery') {
            echo '<!--(PH) ' . strtoupper($sModfileType) . ' HEAD MODFILES -->' . PHP_EOL;
        }
        return $GLOBALS['wb']->registerModfiles($sModfileType, "frontend");
    }
}

// Function to add optional module Javascript into the <body> section of the frontend
if (!function_exists('register_frontend_modfiles_body')) {
    function register_frontend_modfiles_body($sModfileType = "js")
    {
        return;
    }
}