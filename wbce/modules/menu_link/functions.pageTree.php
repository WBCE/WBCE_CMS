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

/**
 * nestedPagesArray
 *
 * @global object $database
 * @global object $admin
 * @param  int    $iParent
 * @return boolean|array
 */
function nestedPagesArray($iParent = 0)
{
    global $database, $admin;
    $iParent = intval($iParent);
    $sWhereClause = (PAGE_TRASH != 'inline') ? " WHERE `visibility` <> 'deleted'" : '';
    $sPageCode =  isPageCodeUsed() ? "p.`page_code`, " : '';
    $sUseTrash = '';
    // prepare SQL Query, build the query string first
    $sQuery = 'SELECT  s.`module`, MAX(s.`publ_start` + s.`publ_end`) published, p.`link`, '
         .        '(SELECT MAX(`position`) FROM `{TP}pages` WHERE `parent` = p.`parent`) siblings, '
         .        'p.`position`, p.`page_id`, p.`parent`, p.`level`, p.`language`, p.`admin_groups`, '
         .        'p.`admin_users`, p.`viewing_groups`, p.`viewing_users`, p.`visibility`, '
         .        'p.`menu_title`, p.`page_title`, p.`page_trail`, p.`description`, p.`keywords`, ' . $sPageCode
                  // create json type of string of all the sections
                 .        'GROUP_CONCAT(CAST(CONCAT(\' "\', s.`section_id`, \'" : "\', s.`module`, \'" \') AS CHAR) '
         .          'ORDER BY s.`position` SEPARATOR \', \') `sections` '

         . 'FROM `{TP}pages` p '
         .    'INNER JOIN `{TP}sections` s '
         .    'ON p.`page_id`=s.`page_id` '
         . $sWhereClause.' '
         . 'GROUP BY p.`page_id` '
         . 'ORDER BY p.`position` ASC';

    $oPages = $database->query($sQuery);
    $aPages = array();
    if (!$oPages) {
        return $aPages;
    } // no pages in DB yet; return empty array
    $refs = array();
    $aQueryKeys = array(
        'page_id','siblings','position','parent','menu_title','page_title','level','page_trail',
        'admin_groups','admin_users','position','module','sections', 'visibility', 'language'
    );
    if (isPageCodeUsed()) {
        $aQueryKeys[] = 'page_code';
    }
    while ($p = $oPages->fetchRow(MYSQLI_ASSOC)) {
        $thisref = &$refs[ $p['page_id'] ];
        foreach ($aQueryKeys as $key) {
            $thisref[$key] = $p[$key];
        }
        $thisref['root_parent']   = isset($p['root_parent']) ? $p['root_parent'] : 0;
        $thisref['pageIDKEY']     = $admin->getIDKEY($p['page_id']);
        $thisref['frontend_link'] = PAGES_DIRECTORY. $p['link'] .PAGE_EXTENSION;

        // Admin Groups (get user permissions)
        $aAdminGroups = explode(',', str_replace('_', '', $p['admin_groups']));
        $admin_users  = explode(',', str_replace('_', '', $p['admin_users']));
        $in_group = false;
        foreach ($admin->get_groups_id() as $cur_gid) {
            if (in_array($cur_gid, $aAdminGroups)) {
                $in_group = true;
            }
        }
        // check modify permissions
        $thisref['can_modify'] = false;
        if (($in_group) || is_numeric(array_search($admin->get_user_id(), $admin_users))) {
            if ($p['visibility'] == 'deleted') {
                $thisref['can_modify'] = (PAGE_TRASH == 'inline');
            } elseif ($p['visibility'] != 'deleted') {
                $thisref['can_modify'] = true;
            }
        } else {
            if ($p['visibility'] == 'private') {
                continue;
            } else {
                $thisref['can_modify'] = false;
            }
        }
        $thisref['canManageSections']  = ((MANAGE_SECTIONS == 'enabled' || MANAGE_SECTIONS == 1) && $admin->get_permission('pages_modify') == true && $thisref['can_modify'] == true);
        $thisref['pageIsMovable']      = ($admin->get_permission('pages_settings') == true && $thisref['can_modify']  == true);
        $thisref['pageIsMovable']      = ($thisref['pageIsMovable'] && $thisref['siblings'] != 1);
        $thisref['canDeleteAndModify'] = ($admin->get_permission('pages_delete')   == true && $thisref['can_modify']  == true);
        $thisref['canAddChild']        = ($admin->get_permission('pages_add'))     == (true && $thisref['can_modify'] == true) && ($thisref['visibility'] != 'deleted');
        $thisref['canModifyPage']      = ($admin->get_permission('pages_modify')   == true && $thisref['can_modify']  == true);
        $thisref['canModifySettings']  = ($admin->get_permission('pages_settings') == true && $thisref['can_modify']  == true);
        $thisref['canMoveUP']          = (isset($thisref['pageIsMovable']) && $thisref['position'] != 1);
        $thisref['canMoveDOWN']        = (isset($thisref['pageIsMovable']) && $thisref['position'] != $thisref['siblings']);

        /**
         * sectionCase: we can have several conditions for the section ICON
         * (clock, noclock, clock_red and menu_link)
         */
        $thisref['sectionCase'] = "noclock";
        if ($p['published'] != 0) {
            $thisref['sectionCase'] = ($admin->page_is_active($thisref)) ? "clock" : "clock_red";
        }
        if ($p['module'] == 'menu_link') {
            $thisref['sectionCase'] = "menu_link"; // menu_link doesn't display sections (it's a special case)
            $thisref['menu_link']   = true;
        }

        // reiterate the tree
        if ($p['parent'] == $iParent) {
            $aPages[ $p['page_id'] ] = &$thisref;
        } else {
            $refs[ $p['parent'] ]['children'][ $p['page_id'] ] = &$thisref;
        }
        unset($p);
    }
    return $aPages;
}

/**
 * @global object $admin
 * @global object $database
 * @global type?? $field_set
 * @param  array  $nestedPagesArray
 * @param  int    $iCurrentPageId
 * @param  bool   $bAllLevels
 * @return array
 */
function parentPageList($nestedPagesArray, $iCurrentPageId = 0, $bAllLevels = false)
{
    global $admin, $database, $field_set;
    $iParentId  = $database->get_one("SELECT `parent` FROM `{TP}pages` WHERE `page_id` = ".$iCurrentPageId);
    $sPageTrash = $database->get_one("SELECT `value`  FROM `{TP}settings` WHERE `name` = 'page_trash'");

    $aPages = array();
    foreach ($nestedPagesArray as $p) {
        // skip some pages where the user shouldn't have access

        if ($iCurrentPageId != 0 && $p['parent'] == $iCurrentPageId) {
            continue;
        }
        if ($p['visibility'] == 'deleted' && $sPageTrash != 'inline') {
            continue;
        }
        #if ($p['visibility'] == 'none') continue;

        // get childs smaller than PAGE_LEVEL_LIMIT only!
        if ($p['level'] + 1 < PAGE_LEVEL_LIMIT || $bAllLevels == true) {

                        // space_trail
            if (defined('DB_CHARSET') && DB_CHARSET == 'utf8') {
                $sSpacer = str_repeat(
                    ($p['level'] == 1 ? "&nbsp;" : "&nbsp;&nbsp;"),
                    (($p['level']))
                ).(
                    ($p['level'] > 0) ? ($p['canMoveDOWN'] ? ' ├─ ' : ' └─ ') : ""
                );
                $sSpacer = str_repeat(
                    "".(($p['canMoveDOWN'] || $p['canMoveUP']) && $p['level'] != 0 ? '&nbsp;·&nbsp;' : '&nbsp;'),
                    (($p['level']))
                ).(
                    ($p['level'] > 0) ? ($p['canMoveDOWN'] ? ' ├─ ' : ' └─ ') : " "
                                     //  the characters should actually look like in the above line, however it didn't work well
                                     //  wenn saved from NetBeans IDE.
                                     //  Also the variants chr(195).chr(238).' ' : ' '.chr(192).chr(238)
                                     //  and sprintf(" %c%c ", 195, 238) sprintf(" %c%c ", 192, 238) didn't work well
                );
            } else {
                // fallback if charset is not UTF-8
                $sSpacer = str_repeat(
                    "&nbsp;",
                    (($p['level'])*2*2)
                ).(
                    ($p['level'] > 0) ? ($p['canMoveDOWN'] ? ' |-- ' : " '-- ") : ""
                );
            }

            $aPages[$p['page_id']] = $p;
            $aPages[$p['page_id']]['space_trail'] = $sSpacer;
            $aPages[$p['page_id']]['icon']        = ($p['parent'] == 0 && $field_set)? $p['language'] : 'none';
            $aPages[$p['page_id']]['selected']    = ($p['page_id'] == $iParentId) ? ' selected="selected" ' : '';
            $aPages[$p['page_id']]['disabled']    = ($p['page_id'] == $iCurrentPageId || $p['can_modify'] == false)
                                ? ' disabled="disabled" ' : '';
            if (isset($p['children'])) {
                $aPages = array_merge($aPages, parentPageList($p['children'], $iCurrentPageId, $bAllLevels));
            }
            unset($p);
        }
    }
    return($aPages);
}


/**
 * @param  array  $nestedPagesArray
 * @return array
 */

function pageTreeCombobox($nestedPagesArray, $iCurrentPageID)
{
    $aPages = array();
    foreach ($nestedPagesArray as $p) {
        $aPages[] = array(
            'page_id'     => $p['page_id'],
            'menu_title'  => drawSpacer($p).$p['menu_title'],
            'page_title'  => $p['page_title'],
            'level'       => $p['level'],
            'visibility'  => $p['visibility'],
        );
        if (isset($p['children'])) {
            $aPages = array_merge($aPages, pageTreeCombobox($p['children'], $iCurrentPageID));
        }
        unset($p);
    }
    return($aPages);
}

function drawSpacer($aPage)
{
    $sSpacer = '';
    $iLevel = $aPage['level'];
    $aPage['canMoveDOWN'] = isset($aPage['canMoveDOWN']) ? $aPage['canMoveDOWN'] : false;
    // space_trail
    if (defined('DB_CHARSET') && DB_CHARSET == 'utf8') {
        $sSpacer = str_repeat(
            ($iLevel == 1 ? "&nbsp;" : "&nbsp;&nbsp;"),
            (($iLevel))
        ).(
            ($iLevel > 0) ? ($aPage['canMoveDOWN'] ? ' ├─ ' : ' └─ ') : ""
        );
    } else {

        // fallback if charset is not UTF-8
        $sSpacer = str_repeat(
            "&nbsp;",
            (($iLevel)*2*2)
        ).(
            ($iLevel > 0) ? ($aPage['canMoveDOWN'] ? ' |-- ' : " '-- ") : ""
        );
    }
    return $sSpacer;
}
/**
 * Check if Page is of Type menu_link
 *
 * @param int $iPageID
 * @return int|bool (false if not menu_link)
 */
function isPageMenuLink($iPageID)
{
    $sSql = "SELECT COUNT(*) FROM `{TP}sections`
                WHERE `page_id` = ".$iPageID." AND `module` = 'menu_link'";
    return $GLOBALS['database']->get_one($sSql);
}

 /**
  * check whether or not we have the mod_multilingal installed
  * ==========================================================
  *
  * @return bool
  */
function isPageCodeUsed()
{
    $bUsePageCodes = false;
    if (is_file(WB_PATH.'/modules/mod_multilingual/update_keys.php')) {
        $bUsePageCodes = $GLOBALS['database']->field_exists('{TP}pages', 'page_code');
    }
    return $bUsePageCodes;
}

 /**
  * check whether or not we have the mod_multilingal installed
  * ==========================================================
  *
  * @return bool
  */
function isWysiwygHistoryUsed()
{
    $bUseHistory = false;
    if (is_file(WB_PATH.'/modules/mod_multilingual/update_keys.php')) {
        $bUseHistory = $GLOBALS['database']->field_exists('{TP}pages', 'page_code');
    }
    return $bUseHistory;
}
