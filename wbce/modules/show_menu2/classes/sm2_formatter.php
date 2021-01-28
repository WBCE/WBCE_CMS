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
        if(defined('SM2_CORRECT_MENU_LINKS') && SM2_CORRECT_MENU_LINKS == true && stristr($aString, 'sm2-is-menulink')){
            $aString = sm2_correct_menu_links($aString);
        }
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
        if (
            array_key_exists('sm2_has_child', $aPage) &&
           !array_key_exists('sm2_is_max_level', $aPage) &&
            array_key_exists('sm2_has_unhidden_child', $aPage)
        ) {
        // if item has child(ren) and is not topmost level
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

        if (array_key_exists('sm2_is_menulink', $aPage) && $aPage['sm2_is_menulink'] === true) {
            $currClass .= ' sm2-is-menulink';
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
                'parent|level|sib|sibCount|class|description|keywords|[a-z][a-z0-9\_]*?[a-z0-9]|'.
                SM2_CONDITIONAL.
            ')\]@',
            array($this, 'replace'),
            $aCurrItem);
    }

    // replace the keywords
    function replace($aMatches) {
        $aMatch = $aMatches[1];
        $retval = '['.$aMatch.'=UNKNOWN]';
        $retval_1 = '';
        switch ($aMatch) {
        case 'a':
            $retval_1 = '<a href="'.$this->url.'"';
        case 'ac':
                $retval = '<a href="'.$this->url.'" class="'.$this->currClass.'"';
            $retval = ($retval_1 == '') ? $retval : $retval_1;
            if(($this->flags & SM2_XHTML_STRICT)) {
                $retval .= ' title="'.(($this->flags & SM2_NO_TITLE) ? '&nbsp;' : $this->page['tooltip']).'"';
            }
            else {
                $retval .= ' target="'.$this->page['target'].'"';
                $retval .= ($this->flags & SM2_NO_TITLE) ? '' : ' title="'.$this->page['tooltip'].'"';
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
            // Simply look if there is a matching element in the array
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
	    break;
        case '>':
            $ok = ($operand > $aValue);
	    break;
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
}

/**
 * sm2_correct_menu_links()
 * ======================================================================
 *
 * @author  Christian M. Stefan <stefek@designthings.de>
 * @license GNU/GPL v.2 or any later
 * ----------------------------------------------------------------------
 *
 * @param  string  $sMenu  the prepopulated menu string
 * @return string          the menu string with correctly replaced URLs
 * ----------------------------------------------------------------------
 *
 */
function sm2_correct_menu_links($sMenu){
    if(defined('SM2_CORRECT_MENU_LINKS') && true){
        global $database;

        $aMenuLinks = array();
        $rMenuLinks = $database->query("SELECT * FROM `{TP}mod_menu_link`");
        $i = 0;
        if($rMenuLinks->numRows() > 0) {
            while($row = $rMenuLinks->fetchRow(MYSQLI_ASSOC)) {
                //$aMenuLinks[$i] = $row;
                if(!empty($row['target_page_id'])){
                    $aMenuLinks[$i]['replace_url'] = get_page_link($row['target_page_id']).''.PAGE_EXTENSION;
                    if(!empty($row['anchor'])){
                            $aMenuLinks[$i]['replace_url'] .= '#'.str_replace('#', '', $row['anchor']);
                    }
                    $aMenuLinks[$i]['replace_url'] = WB_URL.PAGES_DIRECTORY.$aMenuLinks[$i]['replace_url'];
                }
                if(!empty($row['extern'])){
                    $sTargetUrl = str_replace('[WB_URL]', WB_URL, $row['extern']);
                    $aMenuLinks[$i]['replace_url'] = $sTargetUrl;
                }
                if(isset($aMenuLinks[$i]['replace_url'])){
                    $aMenuLinks[$i]['pagetree_url'] = $database->get_one("SELECT `link` FROM `{TP}pages` WHERE `page_id` = ".$row['page_id']);
                    $aMenuLinks[$i]['pagetree_url'] = WB_URL.PAGES_DIRECTORY.$aMenuLinks[$i]['pagetree_url'].PAGE_EXTENSION;
                }
                $i++;
            }
        }
        if(!empty($aMenuLinks)){
            $aReplacements = array();
            foreach($aMenuLinks as $k => $link){
                    $aReplacements[$link['pagetree_url']] = $link['replace_url'];
            }
            $sMenu = strtr($sMenu, $aReplacements);
        }
    }
    return $sMenu;
}
