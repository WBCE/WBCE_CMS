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
define('SM2_BUFFER',       0x0200); // bit 9
define('SM2_CURRTREE',     0x0400); // bit 10
define('SM2_SHOWHIDDEN',   0x0800); // bit 11
define('SM2_XHTML_STRICT', 0x1000); // bit 12
define('SM2_NO_TITLE',     0x2000); // bit 13
define('_SM2_GROUP_1',     0x000F); // exactly one flag from group 1 is required
// Include default formatter
include_once("classes/sm2_formatter.php");

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
    elseif (isset($aOptions['flags'])) {
        $flags = $aOptions['flags'];
    }
    else {
        $flags = SM2_TRIM;
        $aOptions = array();
        @error_logs('show_menu2 error: $aOptions is invalid. No flags supplied!');
    }
    
    // from classic
    if ($flags & 0xF == 0) { $flags |= SM2_TRIM; }

    // ensure we have our group 1 flag, we don't check for the "exactly 1" part, but
    // we do ensure that they provide at least one.
    if (0 == ($flags & _SM2_GROUP_1)) {
        @error_logs('show_menu2 error: $aOptions is invalid. No flags from group 1 supplied!');
        $flags |= SM2_TRIM; // default to TRIM
    }
    
    // search page results don't have any of the page data loaded by WB, so we load it 
    // ourselves using the referrer ID as the current page
    // this is for the pageless pages search, preferences, login ....pageless pages suck
    $CURR_PAGE_ID = defined('REFERRER_ID') ? REFERRER_ID : PAGE_ID;
    if (is_countable($wb->page)){       
        if (count($wb->page) == 0 && defined('REFERRER_ID') && REFERRER_ID > 0) {
            global $database;
            $sSql = 'SELECT * FROM `{TP}pages` WHERE `page_id` = '.REFERRER_ID.'';
            $result = $database->query($sSql);
            if ($result->numRows() == 1) {
                $wb->page = $result->fetchRow();
            }
            unset($result);
        } 
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
        //echo "<h3>StartLEvel:$aStartLevel</h3>";
        $aStart = 0;
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

    // we get the menu data once and store it in a global variable. This allows 
    // multiple calls to show_menu2 in a single page with only a single call to 
    // the database. If this variable exists, then we have already retrieved all
    // of the information and processed it, so we don't need to do it again.
    if (($flags & SM2_NOCACHE) != 0
        || !array_key_exists('show_menu2_data', $GLOBALS)
        || !array_key_exists($aMenu, $GLOBALS['show_menu2_data'])
        
    )
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
		$fields .= '`level`,`visibility`,`viewing_groups`,';
        $fields .= '`viewing_users`';
		
        if ($flags & SM2_ALLINFO) {
            $fields = '*';
        }

        // we request all matching rows from the database for the menu that we
        // are about to create it is cheaper for us to get everything we need
        // from the database once and create the menu from memory then make 
        // multiple calls to the database. 
        $sSql  = 'SELECT '.$fields.' FROM `{TP}pages` '
                . 'WHERE '.$wb->extra_where_sql.' '.$menuLimitSql.' '
		. 'ORDER BY `level` ASC, `position` ASC';
        $sSql = str_replace('hidden', 'IGNOREME', $sSql); // we want the hidden pages
        $oRowset = $database->query($sSql);
        if (is_object($oRowset) && $oRowset->numRows() > 0) {
            // create an in memory array of the database data based on the item's parent. 
            // The array stores all elements in the correct display order.
            while ($page = $oRowset->fetchRow()) {
                // ignore all pages that the current user is not permitted to view
                
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
                
				if(!isset($page['tooltip'])) { $page['tooltip'] = $page['page_title']; }
                // ensure that we have an array entry in the table to add this to
                
                $idx = $page['parent'];
                // netter Versuch , aber macht die Ebenen struktur kaputt
                //if ($aStart==0) $idx =0; 
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
                // mark our current page as being on the maximum level to show
                if ($page['level'] == $aMaxLevel) {
                    $page['sm2_is_max_level'] = true;
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
                    foreach ($rgParent[$mark['page_id']] as $z){
                    //echo "<pre>"; print_r($z); echo "</pre>";
                        if (!isset($z['sm2_hide'])){
                            //echo "nosmhide<br>";
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
       //echo "<pre>"; print_r($rgParent); echo "</pre>";
        unset($rgParent);
    }
	/*
    // Deactivated only display to max level, not sure if its a good idea

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
	*/
	
    // generate the menu
    $retval = false; 
    
    // This was needed for Language based navigation 
    if ($aStart == 0){
        reset($GLOBALS['show_menu2_data'][$aMenu]);
        $aStart =key($GLOBALS['show_menu2_data'][$aMenu]);
    }
    
    //echo "<pre>"; print_r($GLOBALS['show_menu2_data']); echo"</pre>";
    //echo "<pre>START:"; print_r($aStart); echo"</pre>";
    if (array_key_exists($aStart, $GLOBALS['show_menu2_data'][$aMenu])) {
       //echo "<h3>go!!</h3>";
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
    if(defined('SM2_CORRECT_MENU_LINKS') && SM2_CORRECT_MENU_LINKS == true){
        $retval = sm2_correct_menu_links($retval);  
    }
	
    return $retval;
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
{
    //I have removed the comments of this function, the code is the same as the code of the show_menu2 function itself.
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
    if (is_countable($wb->page)){       
        if (count($wb->page) == 0 && defined('REFERRER_ID') && REFERRER_ID > 0) {
            global $database;
            $sSql = 'SELECT * FROM `{TP}pages` WHERE `page_id` = '.REFERRER_ID.'';
            $result = $database->query($sSql);
            if ($result->numRows() == 1) {
                $wb->page = $result->fetchRow();
            }
            unset($result);
        } 
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

        $sSql  = 'SELECT '.$fields.' FROM `{TP}pages` '
                . 'WHERE '.$wb->extra_where_sql.' '.$menuLimitSql.' '
                . 'ORDER BY `level` ASC, `position` ASC';
        $sSql = str_replace('hidden', 'IGNOREME', $sSql);
        $oRowset = $database->query($sSql);
        if (is_object($oRowset) && $oRowset->numRows() > 0) {
            while ($page = $oRowset->fetchRow()) {
                if ($page['visibility'] == 'hidden') {
                    $page['sm2_hide'] = true;
                } elseif (!$wb->page_is_active($page) && $page['link'] != $wb->default_link && !INTRO_PAGE) {
                    continue;
                } elseif (!$wb->page_is_visible($page) && $page['visibility'] != 'registered') {
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
                    if ($flags & SM2_SHOWHIDDEN) { 
                        unset($page['sm2_hide']); 
                    }
                }
                if ($page['level'] == $aMaxLevel) {
                    $page['sm2_is_max_level'] = true;
                }

                if (in_array($page['page_id'], $rgCurrParents)) {
                    $page['sm2_is_parent'] = true;
                    $page['sm2_on_curr_path'] = true;
                    if ($flags & SM2_SHOWHIDDEN) {
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
            $formatter->set($flags, $aItemOpen, $aItemClose, $aMenuOpen, $aMenuClose, $aTopItemOpen, $aTopMenuOpen);
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
            $formatter
        );

        $formatter->finalize();
        
        if (($flags & SM2_BUFFER) != 0) {
            $retval = $formatter->getOutput();
        }
    }

    if (($flags & SM2_NOCACHE) != 0) {
        unset($GLOBALS['show_menu2_data'][$aMenu]);
    }
    if(defined('SM2_CORRECT_MENU_LINKS') && SM2_CORRECT_MENU_LINKS == true){
        $retval = sm2_correct_menu_links($retval);  
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
    foreach ($rgParent[$aStart] as $mKey => $page) {
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
                $url = WB_URL;
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
            while($row = $rMenuLinks->fetchRow(MYSQL_ASSOC)) {		
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