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

/*
 * This file provides backward compatibility between show_menu2 and the old functions show_menu() and menu(). 
 * Note that it is highly recommended for you to update your templates to use show_menu2 directly.
 */    

/*
 * show_menu
 * 
 * By calling it multiple times, you can have one menu just showing the root level, one for the sub-pages, and so on.
 * The order of the arguments has been changed compared to the page_menu() function, so read carefully the list of arguments!
 * To just display the standard menu, use <?php show_menu(); ?> within your template's html code.
 * You don't normally need anymore than the first four arguments.
 * Usual calls would be (inside php code!)
 *
 * show_menu(1,0,-1,false); - displays the complete page tree
 * show_menu(1,1,1); - show only first sub level
 * show_menu(1,1,-1); - show an expanding/collapsing menu tree starting at level 1
 *
 * Full list of arguments:
 * 1. $menu_number:    With activitated "multiple menu" feature you can choose which menu will be displayed default: 1
 * 2. $start_level:    The depth level of the root of the displayed menu tree. Defaults to '0', which is the top level. '1' will show all pages starting from the first sub level.
 * 3. $recurse:        Gives the maximum number of levels to be displayed. Default is '-1' which means 'all'.
 * 4. $collapse:       Specifies, whether the menu tree shall be expandable/collapsible (if set to 'true') or complete (all pages being displayed) if set to 'false'
 * 5. $item_template:  Gives the possibility to specify the html code that is displayed before displaying sub-pages
 * 6. $item_footer:    The html code to appear after sub-pages were displayed.
 * 7. $menu_header:    The html code to appear before the entire menu code and each sub tree.
 * 8. $menu_footer:    The html code to appear after the entire menu code and each sub tree.
 * 9. $default_class:  The (CSS) class of every menu item except the currently viewed page
 * 10. $current_class: The class of the currently viewed page
 * 11. $parent:        (used internally) The page_id of the menu's root node, defaults is '0' (root level)
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

function show_menu(
    $aMenu          = 1, 
    $aStartLevel    = 0, 
    $aRecurse       = -1, 
    $aCollapse      = true,
    $aItemTemplate  = '<li><span[class]>[a][menu_title][/a]</span>',
    $aItemFooter    = '</li>',
    $aMenuHeader    = '<ul>',
    $aMenuFooter    = '</ul>',
    $aDefaultClass  = ' class="menu_default"',
    $aCurrentClass  = ' class="menu_current"',
    $aParent        = 0
    )
{
    static $formatter;
    if (!isset($formatter)) {
        $formatter = new SM2_ShowMenuFormatter;
    }
    
    $formatter->itemTemplate  = $aItemTemplate;
    $formatter->itemFooter    = $aItemFooter;  
    $formatter->menuHeader    = $aMenuHeader;  
    $formatter->menuFooter    = $aMenuFooter;  
    $formatter->defaultClass  = $aDefaultClass;
    $formatter->currentClass  = $aCurrentClass;
    
    $start = SM2_ROOT + $aStartLevel;
    if ($aParent != 0) {
        $start = $aParent;
    }

    $maxLevel = 0;
    if ($aRecurse == 0) {
        return;
    }
    if ($aRecurse < 0) {
        $maxLevel = SM2_ALL;
    }
    else {
        $maxLevel = SM2_START + $aRecurse - 1;
    }
    
    $flags = $aCollapse ? SM2_TRIM : SM2_ALL;
    
    // special case for default case
    if ($aStartLevel == 0 && $aRecurse == -1 && $aCollapse) {
        $maxLevel = SM2_CURR + 1;
    }

    show_menu2($aMenu, $start, $maxLevel, $flags, $formatter);
}

function show_breadcrumbs(
    $aMenu = 0,
    $aStart = SM2_ROOT,
    $aMaxLevel = SM2_CURR,
    $aOptions = SM2_CRUMB,
    $aItemOpen = '<span class="[class]"> > [a][menu_title]</a>',
    $aItemClose = '</span>',
    $aMenuOpen = '',
    $aMenuClose = '',
    $aTopItemOpen = false,
    $aTopMenuOpen = false
    )
{ //I have removed the comments of this function because the code is the same as the code of the show_menu2 function itself.
    global $wb;
    
    $flags = 0;
    if (is_int($aOptions)) {
        $flags = $aOptions;
        $aOptions = array();
    }
    elseif (isset($aOptions['flags'])) {
        $flags = $aOptions['flags'];
    }
    else {
        $flags = SM2_TRIM;
        $aOptions = array();
        @error_logs('show_menu2 error: $aOptions is invalid. No flags supplied!');
    }
    
    if ($flags & 0xF == 0) { $flags |= SM2_TRIM; }

    if (0 == ($flags & _SM2_GROUP_1)) {
        @error_logs('show_menu2 error: $aOptions is invalid. No flags from group 1 supplied!');
        $flags |= SM2_TRIM;
    }
    
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
    
    if ($aMenu == 0) {
        $aMenu = $wb->page['menu'] == '' ? 1 : $wb->page['menu'];
    } 

    $pageLevel  = $wb->page['level']  == '' ? 0 : $wb->page['level'];
    $pageParent = $wb->page['parent'] == '' ? 0 : $wb->page['parent'];
    
    $aStartLevel = 0;
    if ($aStart < SM2_ROOT) {
        if ($aStart == SM2_CURR) {
            $aStartLevel = $pageLevel;
            $aStart = $pageParent;
        }
        else {
            $aStartLevel = $pageLevel + $aStart - SM2_CURR;
            $aStart = $CURR_PAGE_ID; 
        }
    }
    elseif ($aStart < 0) {
        $aStartLevel = $aStart - SM2_ROOT;
        $aStart = 0;
    }

    if ($aMaxLevel == SM2_ALL) {
        $aMaxLevel = 1000;
    }
    elseif ($aMaxLevel < 0) {
        $aMaxLevel += $pageLevel - SM2_CURR;
    }
    elseif ($aMaxLevel >= SM2_MAX) {
        $aMaxLevel += $aStartLevel - SM2_MAX;
        if ($aMaxLevel > $pageLevel) {
            $aMaxLevel = $pageLevel;
        }
    }
    else {
        $aMaxLevel += $aStartLevel - SM2_START;
    }

    if (($flags & SM2_NOCACHE) != 0
        || !array_key_exists('show_menu2_data', $GLOBALS)
        || !array_key_exists($aMenu, $GLOBALS['show_menu2_data'])
        
    )
    {
        global $database;
        
        $rgCurrParents = explode(",", '0,'.$wb->page['page_trail']);
        array_pop($rgCurrParents);
        $rgParent = array();

        $menuLimitSql = ' AND `menu`='.$aMenu;
        if ($aMenu == SM2_ALLMENU) {
            $menuLimitSql = '';
        }

        $fields  = '`parent`,`page_id`,`menu_title`,`page_title`,`link`,`target`,';
		$fields .= '`level`,`visibility`,`viewing_groups`,';
        $fields .= '`viewing_users`';
		
        if ($flags & SM2_ALLINFO) {
            $fields = '*';
        }

        $sql  = 'SELECT '.$fields.' FROM `'.TABLE_PREFIX.'pages` ';
		$sql .= 'WHERE '.$wb->extra_where_sql.' '.$menuLimitSql.' ';
		$sql .= 'ORDER BY `level` ASC, `position` ASC';
        $sql = str_replace('hidden', 'IGNOREME', $sql);
        $oRowset = $database->query($sql);
        if (is_object($oRowset) && $oRowset->numRows() > 0) {
            while ($page = $oRowset->fetchRow()) {
                if ($page['visibility'] == 'hidden') {
                    $page['sm2_hide'] = true;
                }
                
                else if (!$wb->page_is_active($page) && $page['link'] != $wb->default_link && !INTRO_PAGE) {
                    continue;
                }

                else if (!$wb->page_is_visible($page) && $page['visibility'] != 'registered') {
                    continue;
                }
                
				if(!isset($page['tooltip'])) { $page['tooltip'] = $page['page_title']; }
                
                $idx = $page['parent'];
                if (!array_key_exists($idx, $rgParent)) {
                    $rgParent[$idx] = array();
                }

                if ($page['page_id'] == $CURR_PAGE_ID) {
                    $page['sm2_is_curr'] = true;
                    $page['sm2_on_curr_path'] = true;
                    if ($flags & SM2_SHOWHIDDEN) 
					{ 
                        unset($page['sm2_hide']); 
                    }
                }
                if ($page['level'] == $aMaxLevel) {
                    $page['sm2_is_max_level'] = true;
                }

                if (in_array($page['page_id'], $rgCurrParents)) {
                    $page['sm2_is_parent'] = true;
                    $page['sm2_on_curr_path'] = true;
                    if ($flags & SM2_SHOWHIDDEN) 
					{
						unset($page['sm2_hide']);
                    }
                }
                
                $rgParent[$idx][] = $page;
            }
        }    
        unset($oRowset);
        
        foreach ($rgCurrParents as $x) {
            if (array_key_exists($x, $rgParent)) {
                foreach (array_keys($rgParent[$x]) as $y) {
                    $mark =& $rgParent[$x][$y];
                    $mark['sm2_path_sibling'] = true;
                    unset($mark);
                }
            }
        }

        $parentId = $pageParent;
        foreach (array_keys($rgParent) as $x) {
            $childSet =& $rgParent[$x];
            
            foreach (array_keys($childSet) as $y) {
                $mark =& $childSet[$y];
                if (array_key_exists($mark['page_id'], $rgParent)) {
                    $mark['sm2_has_child'] = true;
                    foreach ($rgParent[$mark['page_id']] as $z){
                        if (!isset($z['sm2_hide'])){
                            $mark['sm2_has_unhidden_child'] = true;
                        }
                    }
                }
                if ($mark['parent'] == $parentId && $mark['page_id'] != $CURR_PAGE_ID) {
                    $mark['sm2_is_sibling'] = true;
                }
                
                unset($mark);
            }
            
            unset($childSet);
        }
        
        if ($CURR_PAGE_ID != 0) {
            sm2_mark_children($rgParent, $CURR_PAGE_ID, 1);
        }
        
        if (!array_key_exists('show_menu2_data', $GLOBALS)) {
            $GLOBALS['show_menu2_data'] = array();
        }
        $GLOBALS['show_menu2_data'][$aMenu] =& $rgParent;
        unset($rgParent);
    }

    $retval = false; 
    
    if ($aStart == 0){
        reset($GLOBALS['show_menu2_data'][$aMenu]);
        $aStart =key($GLOBALS['show_menu2_data'][$aMenu]);
    }
    
    if (array_key_exists($aStart, $GLOBALS['show_menu2_data'][$aMenu])) {
        $formatter = $aItemOpen;
        if (!is_object($aItemOpen)) {
            static $sm2formatter;
            if (!isset($sm2formatter)) {
                $sm2formatter = new SM2_Formatter();
            }
            $formatter = $sm2formatter;
            $formatter->set($flags, $aItemOpen, $aItemClose, 
                $aMenuOpen, $aMenuClose, $aTopItemOpen, $aTopMenuOpen);
        }
        
        $showAllLevel = $aStartLevel - 1;
        if (isset($aOptions['notrim'])) {
            $showAllLevel = $aStartLevel + $aOptions['notrim'];
        }
        
        $formatter->initialize();

        sm2_recurse(
            $GLOBALS['show_menu2_data'][$aMenu],
            $aStart,
            $aStartLevel, $showAllLevel, $aMaxLevel, $flags, 
            $formatter);

        $formatter->finalize();
        
        if (($flags & SM2_BUFFER) != 0) {
            $retval = $formatter->getOutput();
        }
    }

    if (($flags & SM2_NOCACHE) != 0) {
        unset($GLOBALS['show_menu2_data'][$aMenu]);
    }
    
    return $retval;
}

function page_menu(
    $aParent = 0, 
    $menu_number = 1, 
    $item_template = '<li[class]>[a][menu_title][/a]</li>', 
    $menu_header = '<ul>', 
    $menu_footer = '</ul>', 
    $default_class = ' class="menu_default"', 
    $current_class = ' class="menu_current"', 
    $recurse = LEVEL    // page['level']
    ) 
{
    show_menu($menu_number, 0, $recurse+2, true, $item_template, '', 
        $menu_header, $menu_footer, $default_class, $current_class, $aParent);
}

//As Always formater goes to the end of the show

class SM2_ShowMenuFormatter
{
    var $output;
    var $itemTemplate;
    var $itemFooter;
    var $menuHeader;
    var $menuFooter;
    var $defaultClass;
    var $currentClass;
    
    function output($aString) {
        if ($this->flags & SM2_BUFFER) {
            $this->output .= $aString;
        }
        else {
            echo $aString;
        }
    }
    function initialize() { $this->output = ''; }
    function startList($aPage, $aUrl) { 
        echo $this->menuHeader;
    }
    function startItem($aPage, $aUrl, $aCurrSib, $aSibCount) { 
        // determine the class string to use
        $thisClass = $this->defaultClass;
        if ($aPage['page_id'] == PAGE_ID) {
            $thisClass = $this->currentClass;
        }
        
        // format and display this item
        $item = str_replace( 
                array(
                    '[a]','[/a]','[menu_title]','[page_title]','[url]',
                    '[target]','[class]'
                    ),
                array(
                    "<a href='$aUrl' target='".$aPage['target']."'>", '</a>',
                    $aPage['menu_title'], $aPage['page_title'], $aUrl, 
                    $aPage['target'], $thisClass
                    ),
                $this->itemTemplate);
        echo $item;
    }
    function finishItem() { 
        echo $this->itemFooter;
    }
    function finishList() { 
        echo $this->menuFooter;
    }
    function finalize() { }
    function getOutput() {
        return $this->output;
    }
}
