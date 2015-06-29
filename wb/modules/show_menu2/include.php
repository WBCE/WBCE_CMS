<?php
/**
 *
 * @category        module
 * @package         show_menu2
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.7.0 | 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: include.php 1475 2011-07-12 23:07:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/show_menu2/include.php $
 * @lastmodified    $Date: 2011-07-13 01:07:10 +0200 (Mi, 13. Jul 2011) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

define('SM2_ROOT',          -1000);
define('SM2_CURR',          -2000);
define('SM2_ALLMENU',          -1);
define('SM2_START',          1000);
define('SM2_MAX',            2000);
define('SM2_ALL',          0x0001); // bit 0 (group 1) (Note: also used for max level!)
define('SM2_TRIM',         0x0002); // bit 1 (group 1)
define('SM2_CRUMB',        0x0004); // bit 2 (group 1)
define('SM2_SIBLING',      0x0008); // bit 3 (group 1)
define('SM2_NUMCLASS',     0x0010); // bit 4
define('SM2_ALLINFO',      0x0020); // bit 5
define('SM2_NOCACHE',      0x0040); // bit 6
define('SM2_PRETTY',       0x0080); // bit 7
define('SM2_ESCAPE',       0x0100); // bit 8
define('SM2_NOESCAPE',          0); // NOOP, unnecessary with WB 2.6.7+
define('SM2_BUFFER',       0x0200); // bit 9
define('SM2_CURRTREE',     0x0400); // bit 10
define('SM2_SHOWHIDDEN',   0x0800); // bit 11
define('SM2_XHTML_STRICT', 0x1000); // bit 12
define('SM2_NO_TITLE',     0x2000); // bit 13

define('_SM2_GROUP_1',  0x000F); // exactly one flag from group 1 is required


// Implement support for page_menu and show_menu using show_menu2. If you remove
// the comments characters from the beginning of the following include, all menu
// functions in Website Baker will be implemented using show_menu2. While it is
// commented out, the original WB functions will be used.
//include('legacy.php');

// This class is the default menu formatter for sm2. If desired, you can 
// create your own formatter class and pass the object into show_menu2 
// as $aItemFormat.
define('SM2_CONDITIONAL','if\s*\(([^\)]+)\)\s*{([^}]*)}\s*(?:else\s*{([^}]*)}\s*)?');
define('SM2_COND_TERM','\s*(\w+)\s*(<|<=|==|=|=>|>|!=)\s*([\w\-]+)\s*');
class SM2_Formatter
{
    var $output;
    var $flags;
    var $itemOpen;
    var $itemClose;
    var $menuOpen;
    var $menuClose;
    var $topItemOpen;
    var $topMenuOpen;
    
    var $isFirst;
    var $page;
    var $url;
    var $currSib;
    var $sibCount;
    var $currClass;
    var $prettyLevel;

    // output the data
    function output($aString) {
        if ($this->flags & SM2_BUFFER) {
            $this->output .= $aString;
        }
        else {
            echo $aString;
        }
    }
    
    // set the default values for all of our formatting items
    function set($aFlags, $aItemOpen, $aItemClose, $aMenuOpen, $aMenuClose, $aTopItemOpen, $aTopMenuOpen) {
        $this->flags        = $aFlags;
        $this->itemOpen     = is_string($aItemOpen)    ? $aItemOpen    : '[li][a][menu_title]</a>';
        $this->itemClose    = is_string($aItemClose)   ? $aItemClose   : '</li>';
        $this->menuOpen     = is_string($aMenuOpen)    ? $aMenuOpen    : '[ul]';
        $this->menuClose    = is_string($aMenuClose)   ? $aMenuClose   : '</ul>';
        $this->topItemOpen  = is_string($aTopItemOpen) ? $aTopItemOpen : $this->itemOpen;
        $this->topMenuOpen  = is_string($aTopMenuOpen) ? $aTopMenuOpen : $this->menuOpen;
    }

    // initialize the state of the formatter before anything is output
    function initialize() {
        $this->output = '';
        $this->prettyLevel = 0;
        if ($this->flags & SM2_PRETTY) {
            $this->output("\n<!-- show_menu2 -->");
        }
    }

    // start a menu     
    function startList(&$aPage, &$aUrl) {
        $currClass = '';
        $currItem = $this->menuOpen;
        
        // use the top level menu open if this is the first menu
        if ($this->topMenuOpen) {
            $currItem = $this->topMenuOpen;
            $currClass .= ' menu-top';
            $this->topMenuOpen = false;
        }
        
        // add the numbered menu class only if requested
        if (($this->flags & SM2_NUMCLASS) == SM2_NUMCLASS) {
            $currClass .= ' menu-'.$aPage['level'];
        }
        
        $this->prettyLevel += 1;
        
        // replace all keywords in the output
        if ($this->flags & SM2_PRETTY) {
            $this->output("\n".str_repeat(' ',$this->prettyLevel).
                $this->format($aPage, $aUrl, $currItem, $currClass));
        }
        else {
            $this->output($this->format($aPage, $aUrl, $currItem, $currClass));
        }
        
        $this->prettyLevel += 3;
    }
    
    // start an item within the menu
    function startItem(&$aPage, &$aUrl, $aCurrSib, $aSibCount) {
        // generate our class list
        $currClass = '';
        if (($this->flags & SM2_NUMCLASS) == SM2_NUMCLASS) {
            $currClass .= ' menu-'.$aPage['level'];
        }
        if (array_key_exists('sm2_has_child', $aPage)) {
            // not set if false, so existence = true
            $currClass .= ' menu-expand';
        }
        if (array_key_exists('sm2_is_curr', $aPage)) { 
            $currClass .= ' menu-current';
        }
        elseif (array_key_exists('sm2_is_parent', $aPage)) { 
            // not set if false, so existence = true
            $currClass .= ' menu-parent';
        }
        elseif (array_key_exists('sm2_is_sibling', $aPage)) {
            // not set if false, so existence = true
            $currClass .= ' menu-sibling';
        }
        elseif (array_key_exists('sm2_child_level',$aPage)) {
            // not set if not a child
            $currClass .= ' menu-child';
            if (($this->flags & SM2_NUMCLASS) == SM2_NUMCLASS) {
                $currClass .= ' menu-child-'.($aPage['sm2_child_level']-1);
            }
        }
        if ($aCurrSib == 1) {
            $currClass .= ' menu-first';
        }
        if ($aCurrSib == $aSibCount) {
            $currClass .= ' menu-last';
        }

        // use the top level item if this is the first item
        $currItem = $this->itemOpen;
        if ($this->topItemOpen) {
            $currItem = $this->topItemOpen;
            $this->topItemOpen = false;
        }

        // replace all keywords in the output
        if ($this->flags & SM2_PRETTY) {
            $this->output("\n".str_repeat(' ',$this->prettyLevel));
        }
        $this->output($this->format($aPage, $aUrl, $currItem, $currClass, $aCurrSib, $aSibCount));
    }
    
    // find and replace all keywords, setting the state variables first
    function format(&$aPage, &$aUrl, &$aCurrItem, &$aCurrClass, 
        $aCurrSib = 0, $aSibCount = 0) 
    {
        $this->page      = &$aPage;
        $this->url       = &$aUrl;
        $this->currClass = trim($aCurrClass);
        $this->currSib   = $aCurrSib;
        $this->sibCount  = $aSibCount;
        
        $item = $this->format2($aCurrItem);
        
        unset($this->page);
        unset($this->url);
        unset($this->currClass);
        
        return $item;
    }
    
    // find and replace all keywords
    function format2(&$aCurrItem) {
        if (!is_string($aCurrItem)) return '';
        return preg_replace_callback(
            '@\[('.
                'a|ac|/a|li|/li|ul|/ul|menu_title|menu_icon_0|menu_icon_1|'.
                'page_title|page_icon|url|target|page_id|tooltip|'.
                'parent|level|sib|sibCount|class|description|keywords|'.
                SM2_CONDITIONAL.
            ')\]@',
            array($this, 'replace'),
            $aCurrItem);
    }
    
    // replace the keywords
    function replace($aMatches) {
        $aMatch = $aMatches[1];
        $retval = '['.$aMatch.'=UNKNOWN]';
        switch ($aMatch) {
        case 'a':
            $retval = '<a href="'.$this->url.'"';
			// break; // ignore 'break' to add the rest of <a>-tag
		case 'ac':
			if( substr($retval, 0, 2) != '<a'){
				$retval = '<a href="'.$this->url.'" class="'.$this->currClass.'"';
			}
			if(($this->flags & SM2_NO_TITLE)) {
				$retval .= ' title="'.$this->page['tooltip'].'"';
			}
			if(!($this->flags & SM2_XHTML_STRICT)) {
				$retval .= ' target="'.$this->page['target'].'"';
			}
			$retval .= '>';
			break;
        case '/a':
            $retval = '</a>'; break;
        case 'li':
            $retval = '<li class="'.$this->currClass.'">'; break;
        case '/li':
            $retval = '</li>'; break;
        case 'ul':
            $retval = '<ul class="'.$this->currClass.'">'; break;
        case '/ul':
            $retval = '</ul>'; break;
        case 'url':
            $retval = $this->url; break;
        case 'sib':
            $retval = $this->currSib; break;
        case 'sibCount':
            $retval = $this->sibCount; break;
        case 'class':
            $retval = $this->currClass; break;
        default:
            if (array_key_exists($aMatch, $this->page)) {
                if ($this->flags & SM2_ESCAPE) {
                    $retval = htmlspecialchars($this->page[$aMatch], ENT_QUOTES);
                }
                else {
                    $retval = $this->page[$aMatch];
                }
            }
            if (preg_match('/'.SM2_CONDITIONAL.'/', $aMatch, $rgMatches)) {
                $retval = $this->replaceIf($rgMatches[1], $rgMatches[2], $rgMatches[3]);
            }
        }
        return $retval;
    }
    
    // conditional replacement
    function replaceIf(&$aExpression, &$aIfValue, &$aElseValue) {
        // evaluate all of the tests in the conditional (we don't do short-circuit
        // evaluation) and replace the string test with the boolean result
        $rgTests = preg_split('/(\|\||\&\&)/', $aExpression, -1, PREG_SPLIT_DELIM_CAPTURE);
        for ($n = 0; $n < count($rgTests); $n += 2) {
            if (preg_match('/'.SM2_COND_TERM.'/', $rgTests[$n], $rgMatches)) {
                $rgTests[$n] = $this->ifTest($rgMatches[1], $rgMatches[2], $rgMatches[3]);
            }
            else {
                @error_logs("show_menu2 error: conditional expression is invalid!");
                $rgTests[$n] = false;
            }
        }

        // combine all test results for a final result
        $ok = $rgTests[0];
        for ($n = 1; $n+1 < count($rgTests); $n += 2) {
            if ($rgTests[$n] == '||') {
                $ok = $ok || $rgTests[$n+1];
            }
            else {
                $ok = $ok && $rgTests[$n+1];
            }
        }
        
        // return the formatted expression if the test succeeded
        return $ok ? $this->format2($aIfValue) : $this->format2($aElseValue);
    }

    // conditional test
    function ifTest(&$aKey, &$aOperator, &$aValue) {
        global $wb;
        
        // find the correct operand
        $operand = false;
        switch($aKey) {
        case 'class':
            // we need to wrap the class names in spaces so we can test for a unique
            // class name that will not match prefixes or suffixes. Same must be done
            // for the value we are testing.
            $operand = " $this->currClass "; 
            break;
		case 'target':
			$operand = $this->page['target'];
			break;
        case 'sib':
            $operand = $this->currSib;
            if ($aValue == 'sibCount') {
                $aValue = $this->sibCount;
            }
            break;
        case 'sibCount':
            $operand = $this->sibCount;
            break;
        case 'level':
            $operand = $this->page['level'];
            switch ($aValue) {
            case 'root':    $aValue = 0; break;
            case 'granny':  $aValue = $wb->page['level']-2; break;
            case 'parent':  $aValue = $wb->page['level']-1; break;
            case 'current': $aValue = $wb->page['level'];   break;
            case 'child':   $aValue = $wb->page['level']+1; break;
            }
            if ($aValue < 0) $aValue = 0;
            break;
        case 'id':
            $operand = $this->page['page_id'];
            switch ($aValue) {
            case 'parent':  $aValue = $wb->page['parent'];  break;
            case 'current': $aValue = $wb->page['page_id']; break;
            }
            break;
        default:
            return '';
        }

        // do the test        
        $ok = false;
        switch ($aOperator) { 
        case '<':
            $ok = ($operand < $aValue); 
            break;
        case '<=':
            $ok = ($operand <= $aValue); 
            break;
        case '=':
        case '==':
        case '!=':
            if ($aKey == 'class') {
                $ok = strstr($operand, " $aValue ") !== FALSE;
            }
            else {
                $ok = ($operand == $aValue); 
            }
            if ($aOperator == '!=') {
                $ok = !$ok;
            }
            break;
        case '>=':
            $ok = ($operand >= $aValue); 
        case '>':
            $ok = ($operand > $aValue); 
        }
        
        return $ok;
    }
    
    // finish the current menu item
    function finishItem() {
        if ($this->flags & SM2_PRETTY) {
            $this->output(str_repeat(' ',$this->prettyLevel).$this->itemClose);
        }
        else {
            $this->output($this->itemClose);
        }
    }

    // finish the current menu
    function finishList() {
        $this->prettyLevel -= 3;
        
        if ($this->flags & SM2_PRETTY) {
            $this->output("\n".str_repeat(' ',$this->prettyLevel).$this->menuClose."\n");
        }
        else {
            $this->output($this->menuClose);
        }
        
        $this->prettyLevel -= 1;
    }
    
    // cleanup the state of the formatter after everything has been output
    function finalize() {
        if ($this->flags & SM2_PRETTY) {
            $this->output("\n");
        }
    }

    // return the output
    function getOutput() {
        return $this->output;
    }
};

function error_logs($error_str)
{
                $log_error = true;
                if ( ! function_exists('error_log') )
                        $log_error = false;

                $log_file = @ini_get('error_log');
                if ( !empty($log_file) && ('syslog' != $log_file) && !@is_writable($log_file) )
                        $log_error = false;

                if ( $log_error )
                        @error_log($error_str, 0);
}

function show_menu2(
    $aMenu          = 0,
    $aStart         = SM2_ROOT,
    $aMaxLevel      = -1999, // SM2_CURR+1
    $aOptions       = SM2_TRIM,
    $aItemOpen      = false,
    $aItemClose     = false,
    $aMenuOpen      = false,
    $aMenuClose     = false,
    $aTopItemOpen   = false,
    $aTopMenuOpen   = false
    )
{
    global $wb;

    // extract the flags and set $aOptions to an array
    $flags = 0;
    if (is_int($aOptions)) {
        $flags = $aOptions;
        $aOptions = array();
    }
    else if (isset($aOptions['flags'])) {
        $flags = $aOptions['flags'];
    }
    else {
        $flags = SM2_TRIM;
        $aOptions = array();
        @error_logs('show_menu2 error: $aOptions is invalid. No flags supplied!');
    }
    
    // ensure we have our group 1 flag, we don't check for the "exactly 1" part, but
    // we do ensure that they provide at least one.
    if (0 == ($flags & _SM2_GROUP_1)) {
        @error_logs('show_menu2 error: $aOptions is invalid. No flags from group 1 supplied!');
        $flags |= SM2_TRIM; // default to TRIM
    }
    
    // search page results don't have any of the page data loaded by WB, so we load it 
    // ourselves using the referrer ID as the current page
    $CURR_PAGE_ID = defined('REFERRER_ID') ? REFERRER_ID : PAGE_ID;
    if (count($wb->page) == 0 && defined('REFERRER_ID') && REFERRER_ID > 0) {
        global $database;
        $sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.REFERRER_ID.'';
        $result = $database->query($sql);
        if ($result->numRows() == 1) {
            $wb->page = $result->fetchRow();
        }
        unset($result);
    }
    
    // fix up the menu number to default to the menu number
    // of the current page if no menu has been supplied
    if ($aMenu == 0) {
        $aMenu = $wb->page['menu'] == '' ? 1 : $wb->page['menu'];
    } 

    // Set some of the $wb->page[] settings to defaults if not set
    $pageLevel  = $wb->page['level']  == '' ? 0 : $wb->page['level'];
    $pageParent = $wb->page['parent'] == '' ? 0 : $wb->page['parent'];
    
    // adjust the start level and start page ID as necessary to
    // handle the special values that can be passed in as $aStart
    $aStartLevel = 0;
    if ($aStart < SM2_ROOT) {   // SM2_CURR+N
        if ($aStart == SM2_CURR) {
            $aStartLevel = $pageLevel;
            $aStart = $pageParent;
        }
        else {
            $aStartLevel = $pageLevel + $aStart - SM2_CURR;
            $aStart = $CURR_PAGE_ID; 
        }
    }
    elseif ($aStart < 0) {   // SM2_ROOT+N
        $aStartLevel = $aStart - SM2_ROOT;
        $aStart = 0;
    }

    // we get the menu data once and store it in a global variable. This allows 
    // multiple calls to show_menu2 in a single page with only a single call to 
    // the database. If this variable exists, then we have already retrieved all
    // of the information and processed it, so we don't need to do it again.
    if (($flags & SM2_NOCACHE) != 0
        || !array_key_exists('show_menu2_data', $GLOBALS)
        || !array_key_exists($aMenu, $GLOBALS['show_menu2_data'])) 
    {
        global $database;

        // create an array of all parents of the current page. As the page_trail
        // doesn't include the theoretical root element 0, we add it ourselves.
        $rgCurrParents = explode(",", '0,'.$wb->page['page_trail']);
        array_pop($rgCurrParents); // remove the current page
        $rgParent = array();

        // if the caller wants all menus gathered together (e.g. for a sitemap)
        // then we don't limit our SQL query
        $menuLimitSql = ' AND `menu`='.$aMenu;
        if ($aMenu == SM2_ALLMENU) {
            $menuLimitSql = '';
        }

        // we only load the description and keywords if we have been told to,
        // this cuts the memory load for pages that don't use them. Note that if
        // we haven't been told to load these fields the *FIRST TIME* show_menu2
        // is called (i.e. where the database is loaded) then the info won't
        // exist anyhow.
        $fields  = '`parent`,`page_id`,`menu_title`,`page_title`,`link`,`target`,';
		$fields .= '`level`,`visibility`,`viewing_groups`';
        if (version_compare(WB_VERSION, '2.7', '>=')) { // WB 2.7+
            $fields .= ',`viewing_users`';
        }
		if(version_compare(WB_VERSION, '2.9.0', '>=')) {
            $fields .= ',`menu_icon_0`,`menu_icon_1`,`page_icon`,`tooltip`';
		}
        if ($flags & SM2_ALLINFO) {
            $fields = '*';
        }

        // we request all matching rows from the database for the menu that we
        // are about to create it is cheaper for us to get everything we need
        // from the database once and create the menu from memory then make 
        // multiple calls to the database. 
        $sql  = 'SELECT '.$fields.' FROM `'.TABLE_PREFIX.'pages` ';
		$sql .= 'WHERE '.$wb->extra_where_sql.' '.$menuLimitSql.' ';
		$sql .= 'ORDER BY `level` ASC, `position` ASC';
        $sql = str_replace('hidden', 'IGNOREME', $sql); // we want the hidden pages
        $oRowset = $database->query($sql);
        if (is_object($oRowset) && $oRowset->numRows() > 0) {
            // create an in memory array of the database data based on the item's parent. 
            // The array stores all elements in the correct display order.
            while ($page = $oRowset->fetchRow()) {
                // ignore all pages that the current user is not permitted to view
                if(version_compare(WB_VERSION, '2.7', '>=')) { // WB >= 2.7
                    // 1. hidden pages aren't shown unless they are on the current page
                    if ($page['visibility'] == 'hidden') {
                        $page['sm2_hide'] = true;
                    }
                    
                    // 2. all pages with no active sections (unless it is the top page) are ignored
                    else if (!$wb->page_is_active($page) && $page['link'] != $wb->default_link && !INTRO_PAGE) {
                        continue;
                    }

                    // 3. all pages not visible to this user (unless always visible to registered users) are ignored
                    else if (!$wb->page_is_visible($page) && $page['visibility'] != 'registered') {
                        continue;
                    }
                }
				if(!isset($page['tooltip'])) { $page['tooltip'] = $page['page_title']; }
                // ensure that we have an array entry in the table to add this to
                $idx = $page['parent'];
                if (!array_key_exists($idx, $rgParent)) {
                    $rgParent[$idx] = array();
                }

                // mark our current page as being on the current path
                if ($page['page_id'] == $CURR_PAGE_ID) {
                    $page['sm2_is_curr'] = true;
                    $page['sm2_on_curr_path'] = true;
                    if ($flags & SM2_SHOWHIDDEN) 
					{ 
                        // show hidden pages if active and SHOWHIDDEN flag supplied
                        unset($page['sm2_hide']); 
                    }
                }

                // mark parents of the current page as such
                if (in_array($page['page_id'], $rgCurrParents)) {
                    $page['sm2_is_parent'] = true;
                    $page['sm2_on_curr_path'] = true;
                    if ($flags & SM2_SHOWHIDDEN) 
					{
                        // show hidden pages if active and SHOWHIDDEN flag supplied
						unset($page['sm2_hide']); // don't hide a parent page                
                    }
                }
                
                // add the entry to the array                
                $rgParent[$idx][] = $page;
            }
        }    
        unset($oRowset);

        // mark all elements that are siblings of any element on the current path
        foreach ($rgCurrParents as $x) {
            if (array_key_exists($x, $rgParent)) {
                foreach (array_keys($rgParent[$x]) as $y) {
                    $mark =& $rgParent[$x][$y];
                    $mark['sm2_path_sibling'] = true;
                    unset($mark);
                }
            }
        }

        // mark all elements that have children and are siblings of the current page
        $parentId = $pageParent;
        foreach (array_keys($rgParent) as $x) {
            $childSet =& $rgParent[$x];
            foreach (array_keys($childSet) as $y) {
                $mark =& $childSet[$y];
                if (array_key_exists($mark['page_id'], $rgParent)) {
                    $mark['sm2_has_child'] = true;
                }
                if ($mark['parent'] == $parentId && $mark['page_id'] != $CURR_PAGE_ID) {
                    $mark['sm2_is_sibling'] = true;
                }
                unset($mark);
            }
            unset($childSet);
        }
        
        // mark all children of the current page. We don't do this when 
        // $CURR_PAGE_ID is 0, as 0 is the parent of everything. 
        // $CURR_PAGE_ID == 0 occurs on special pages like search results
        // when no referrer is available.s
        if ($CURR_PAGE_ID != 0) {
            sm2_mark_children($rgParent, $CURR_PAGE_ID, 1);
        }
        
        // store the complete processed menu data as a global. We don't 
        // need to read this from the database anymore regardless of how 
        // many menus are displayed on the same page.
        if (!array_key_exists('show_menu2_data', $GLOBALS)) {
            $GLOBALS['show_menu2_data'] = array();
        }
        $GLOBALS['show_menu2_data'][$aMenu] =& $rgParent;
        unset($rgParent);
    }

    // adjust $aMaxLevel to the level number of the final level that 
    // will be displayed. That is, we display all levels <= aMaxLevel.
    if ($aMaxLevel == SM2_ALL) {
        $aMaxLevel = 1000;
    }
    elseif ($aMaxLevel < 0) {   // SM2_CURR+N
        $aMaxLevel += $pageLevel - SM2_CURR;
    }
    elseif ($aMaxLevel >= SM2_MAX) { // SM2_MAX+N
        $aMaxLevel += $aStartLevel - SM2_MAX;
        if ($aMaxLevel > $pageLevel) {
            $aMaxLevel = $pageLevel;
        }
    }
    else {  // SM2_START+N
        $aMaxLevel += $aStartLevel - SM2_START;
    }

    // generate the menu
    $retval = false;
    if (array_key_exists($aStart, $GLOBALS['show_menu2_data'][$aMenu])) {
        $formatter = $aItemOpen;
        if (!is_object($aItemOpen)) {
            static $sm2formatter;
            if (!isset($sm2formatter)) {
                $sm2formatter = new SM2_Formatter;
            }
            $formatter = $sm2formatter;
            $formatter->set($flags, $aItemOpen, $aItemClose, 
                $aMenuOpen, $aMenuClose, $aTopItemOpen, $aTopMenuOpen);
        }
        
        // adjust the level until we show everything and ignore the SM2_TRIM flag.
        // Usually this will be less than the start level to disable it.
        $showAllLevel = $aStartLevel - 1;
        if (isset($aOptions['notrim'])) {
            $showAllLevel = $aStartLevel + $aOptions['notrim'];
        }
        
        // display the menu
        $formatter->initialize();
        sm2_recurse(
            $GLOBALS['show_menu2_data'][$aMenu],
            $aStart,    // parent id to start displaying sub-menus
            $aStartLevel, $showAllLevel, $aMaxLevel, $flags, 
            $formatter);
        $formatter->finalize();
        
        // if we are returning something, get the data
        if (($flags & SM2_BUFFER) != 0) {
            $retval = $formatter->getOutput();
        }
    }

    // clear the data if we aren't caching it
    if (($flags & SM2_NOCACHE) != 0) {
        unset($GLOBALS['show_menu2_data'][$aMenu]);
    }
    
    return $retval;
}

function sm2_mark_children(&$rgParent, $aStart, $aChildLevel)
{
    if (array_key_exists($aStart, $rgParent)) {
        foreach (array_keys($rgParent[$aStart]) as $y) {
            $mark =& $rgParent[$aStart][$y];
            $mark['sm2_child_level'] = $aChildLevel;
            $mark['sm2_on_curr_path'] = true;
            sm2_mark_children($rgParent, $mark['page_id'], $aChildLevel+1);
        }
    }
}

function sm2_recurse(
    &$rgParent, $aStart, 
    $aStartLevel, $aShowAllLevel, $aMaxLevel, $aFlags, 
    &$aFormatter
    )
{
    global $wb;

    // on entry to this function we know that there are entries for this 
    // parent and all entries for that parent are being displayed. We also 
    // need to check if any of the children need to be displayed too.
    $isListOpen = false;
    $currentLevel = $wb->page['level'] == '' ? 0 : $wb->page['level'];

    // get the number of siblings skipping the hidden pages so we can pass 
    // this in and check if the item is first or last
    $sibCount = 0;
    foreach ($rgParent[$aStart] as $page) {
        if (!array_key_exists('sm2_hide', $page)) $sibCount++;
    }
    
    $currSib = 0;
    foreach ($rgParent[$aStart] as $page) {
        // skip all hidden pages 
        if (array_key_exists('sm2_hide', $page)) { // not set if false, so existence = true
            continue;
        }
        
        $currSib++;

        // skip any elements that are lower than the maximum level
        $pageLevel = $page['level'];
        if ($pageLevel > $aMaxLevel) {
            continue;
        }
        
        // this affects ONLY the top level
        if ($aStart == 0 && ($aFlags & SM2_CURRTREE)) {
            if (!array_key_exists('sm2_on_curr_path', $page)) { // not set if false, so existence = true
                continue;
            }
            $sibCount = 1;
        }
        
        // trim the tree as appropriate
        if ($aFlags & SM2_SIBLING) {
            // parents, and siblings and children of current only
            if (!array_key_exists('sm2_on_curr_path', $page)      // not set if false, so existence = true
                && !array_key_exists('sm2_is_sibling', $page)     // not set if false, so existence = true
                && !array_key_exists('sm2_child_level', $page)) { // not set if false, so existence = true
                continue;
            }
        }
        else if ($aFlags & SM2_TRIM) {
            // parents and siblings of parents
            if ($pageLevel > $aShowAllLevel  // permit all levels to be shown
                && !array_key_exists('sm2_on_curr_path', $page)    // not set if false, so existence = true
                && !array_key_exists('sm2_path_sibling', $page)) {  // not set if false, so existence = true
                continue;
            }
        }
        elseif ($aFlags & SM2_CRUMB) {
            // parents only
            if (!array_key_exists('sm2_on_curr_path', $page)    // not set if false, so existence = true
                || array_key_exists('sm2_child_level', $page)) {  // not set if false, so existence = true
                continue;
            }
        }

        // depth first traverse
        $nextParent = $page['page_id'];

        // display the current element if we have reached the start level
        if ($pageLevel >= $aStartLevel) {
            // massage the link into the correct form
            if(!INTRO_PAGE && $page['link'] == $wb->default_link) {
                $url = WB_URL.'/';
            }
            else {
                $url = $wb->page_link($page['link']);
            }
                    
            // we open the list only when we absolutely need to
            if (!$isListOpen) {
                $aFormatter->startList($page, $url);
                $isListOpen = true;
            }

            $aFormatter->startItem($page, $url, $currSib, $sibCount);
        }
        
        // display children as appropriate
        if ($pageLevel + 1 <= $aMaxLevel 
            && array_key_exists('sm2_has_child', $page)) {  // not set if false, so existence = true
            sm2_recurse(
                $rgParent, $nextParent, // parent id to start displaying sub-menus
                $aStartLevel, $aShowAllLevel, $aMaxLevel, $aFlags, 
                $aFormatter);
        }
        
        // close the current element if appropriate
        if ($pageLevel >= $aStartLevel) {
            $aFormatter->finishItem($pageLevel, $page);
        }
    }

    // close the list if we opened one
    if ($isListOpen) {
        $aFormatter->finishList();
    }
}

