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

//
// Function to build sitemap
//
if (!function_exists("sitemap")) {
    function sitemap(
        $level_header,
        $smloop,
        $level_footer,
        $show_hidden,
        $menus,
        $parent = 0,
        $to_depth = 0,
        $curr_level = 0
    ) {
        global $database;

        // visibility
        $where_sql = " visibility = 'public'";
        if ($show_hidden == 1) {
            $where_sql = " visibility != 'deleted'";
        }

        // menus
        $sWhereMenus = '';
        $menuParams = [];
        if (isset($menus[0]) && $menus[0] != 0) {
            $menuParams = array_values($menus);
            $sWhereMenus = " AND (`menu` = " . implode(' OR `menu` = ', array_fill(0, count($menuParams), '?')) . ")";
        }
        // Query pages
        $query_menu = $database->query(
            "SELECT * FROM `{TP}pages` WHERE ".$where_sql." AND `parent` = ? ".$sWhereMenus." ORDER BY `position` ASC",
            [$parent, ...$menuParams]
        );

        // resolve menu_link target URLs once per request (memoized in show_menu2)
        $menuLinkData = sm2_get_menulink_data();

        //
        // start collecting the output string
        //
        $sOutput = "";
        // Check if there are any pages to show
        if ($query_menu->numRows() > 0) {
            $curr_level++;
            $sOutput .= str_replace('[LEVEL]', $curr_level - 1, $level_header); // include level header to output

            // Loop through pages
            while ($page = $query_menu->fetchRow()) {

                //get username from user id
                $user = $database->fetchRow("SELECT `display_name` FROM `{TP}users` WHERE `user_id` = ?", [$page['modified_by']]);

                // menu_link pages: link straight at the resolved target (internal or external);
                // structure-only nodes (no target at all) get '#'
                if (isset($menuLinkData['none'][$page['page_id']])) {
                    $this_page_link = '#';
                } else {
                    $this_page_link = $menuLinkData['internal'][$page['page_id']]
                        ?? $menuLinkData['external'][$page['page_id']]
                        ?? page_link($page['link']);
                }

                $aReplacements = array(
                    '[PAGE_ID]'       => $page['page_id'],
                    '[ID]'            => $page['page_id'],
                    '[PARENT]'        => $page['parent'],
                    '[LEVEL]'         => $page['level'],
                    '[LINK]'          => $this_page_link,
                    '[PAGE_TITLE]'    => stripslashes($page['page_title']),
                    '[MENU_TITLE]'    => $page['menu_title'],
                    '[DESCRIPTION]'   => stripslashes($page['description']),
                    '[KEYWORDS]'      => stripslashes($page['keywords']),
                    '[TARGET]'        => $page['target'],
                    '[MODIFIED_BY]'   => isset($user['display_name']) ? $user['display_name'] : '',
                    '[MODIFIED_DATE]' => date(DATE_FORMAT, $page['modified_when']),
                    '[MODIFIED_TIME]' => date(TIME_FORMAT, $page['modified_when']),
                    '[MODIFIED_WHEN]' => "[MODIFIED_DATE] [MODIFIED_TIME]"
                );
                $sOutput .= strtr($smloop, $aReplacements);

                // determine if we may descend further in the subpages
                if ($curr_level != $to_depth) {
                    // pass on the maximum and current depth
                    $sOutput .= sitemap($level_header, $smloop, $level_footer, $show_hidden, $menus, $page['page_id'], $to_depth, $curr_level);
                }
            }

            $sOutput .= $level_footer;
            return $sOutput;
        }
    }
}

//
// Work out the output
//

// Get settings
$settings                 = $database->fetchRow("SELECT * FROM `{TP}mod_sitemap` WHERE `section_id` = ?", [$section_id]);
$menus 					  = stripslashes($settings['menus']);
$settings['header']       = stripslashes($settings['header']);
$settings['smloop']       = stripslashes($settings['sitemaploop']);
$settings['footer']       = stripslashes($settings['footer']);
$settings['level_header'] = stripslashes($settings['level_header']);
$settings['level_footer'] = stripslashes($settings['level_footer']);
extract($settings);

//
// Start collecting the final output
//
$sOutput = $header;
if ($static == true or $static == false) {

    // Set private sql extra code
    $private_sql       = "";
    $private_where_sql = "visibility = 'public'";
    if (FRONTEND_LOGIN == 'enabled' and is_numeric($admin->get_session('USER_ID'))) {
        $private_sql       = ",viewing_groups,viewing_users";
        $private_where_sql = "visibility != 'none'";
    }

    // determine sitemap starting point
    global $page_id;
    switch ($startatroot) {
        case 2: // value 2 means start at current page
            $parent = $page_id;
            break;

        case 3: // value 3 means start at parent of current page
            global $database;
            $parentrow = $database->fetchRow("SELECT `parent` FROM `{TP}pages` WHERE page_id = ?", [$page_id]);
            if ($parentrow !== null) {
                $parent = $parentrow['parent'];
            }
            break;

        case 1:
        default: // start at site root
            $parent = 0;
            break;
    }

    // sanity check on parent, check for smaller than 1 because of string cast
    $parent = (trim($parent) == "") || ($parent < 1) ? 0 : $parent;

    // workout the menus we should include for display
    if (!isset($menus)) { $menus = ''; }
    $aMenus    = array();
    $aMenus[0] = 0;
    if (strpos($menus, ',') !== false) {
        $aMenus = explode(',', $menus);
    } else {
        $aMenus[0] = $menus;
    }
    $menus = $aMenus;

    // build the sitemap
    $sOutput .= sitemap($level_header, $smloop, $level_footer, $show_hidden, $menus, $parent, $depth);
}
$sOutput .= $footer;

//
// Apply custom RegEx to the ouput string if exists for this section
//
if (file_exists(__DIR__ . '/regex/')) {
    $sRegExAllFile = dirname(__FILE__) . '/regex/regex_section_all.php';
    if (is_readable($sRegExAllFile)) {
        $aRegEx = include $sRegExAllFile;
    }
    $sRegExSectionFile = dirname(__FILE__) . '/regex/regex_section_' . $section_id . '.php';
    if (is_readable($sRegExSectionFile)) {
        $aRegEx = array_merge($aRegEx, include $sRegExSectionFile);
    }
    $sOutput = do_regex_array($aRegEx, $sOutput);
}

//
// echo final output
//
echo $sOutput;
