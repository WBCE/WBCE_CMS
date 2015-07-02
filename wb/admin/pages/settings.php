<?php
/**
 *
 * @category        admin
 * @package         pages
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: settings.php 5 2015-04-27 08:02:19Z luisehahne $
 * @filesource      $HeadURL: https://localhost:8443/svn/wb283Sp4/SP4/branches/wb/admin/pages/settings.php $
 * @lastmodified    $Date: 2015-04-27 10:02:19 +0200 (Mo, 27. Apr 2015) $
 *
 */

/*
*/
// Create new admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_settings');
// Include the WB functions file
require_once(WB_PATH.'/framework/functions-utf8.php');

// Get page id
if(!isset($_GET['page_id']) || !is_numeric($_GET['page_id']))
{
    header("Location: index.php");
    exit(0);
} else {
    $page_id = $_GET['page_id'];
}

/*
if( (!($page_id = $admin->checkIDKEY('page_id', 0, $_SERVER['REQUEST_METHOD']))) )
{
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    exit();
}
*/
$sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
$results = $database->query($sql);
$results_array = $results->fetchRow();

$old_admin_groups = explode(',', $results_array['admin_groups']);
$old_admin_users = explode(',', $results_array['admin_users']);

// Work-out if we should check for existing page_code
$field_set = $database->field_exists(TABLE_PREFIX.'pages', 'page_code');

$in_old_group = FALSE;
foreach($admin->get_groups_id() as $cur_gid)
{
    if (in_array($cur_gid, $old_admin_groups))
    {
        $in_old_group = TRUE;
    }
}
if((!$in_old_group) && !is_numeric(array_search($admin->get_user_id(), $old_admin_users)))
{
    $admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
}

// Get page details
/* $database = new database();  */
$sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `page_id`='.$page_id;
$results = $database->query($sql);
if($database->is_error()) {
    $admin->print_header();
    $admin->print_error($database->get_error());
}
if($results->numRows() == 0) {
    $admin->print_header();
    $admin->print_error($MESSAGE['PAGES']['NOT_FOUND']);
}
$results_array = $results->fetchRow();

// Get display name of person who last modified the page
$user=$admin->get_user_details($results_array['modified_by']);

// Convert the unix ts for modified_when to human a readable form
if($results_array['modified_when'] != 0)
{
    $modified_ts = gmdate(TIME_FORMAT.', '.DATE_FORMAT, $results_array['modified_when']+TIMEZONE);
} else {
    $modified_ts = 'Unknown';
}

// Setup template object, parse vars to it, then parse it
// Create new template object
$template = new Template(dirname($admin->correct_theme_source('pages_settings.htt')));
// $template->debug = true;
$template->set_file('page', 'pages_settings.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('FTAN', $admin->getFTAN());

$template->set_var(array(
                'PAGE_ID' => $results_array['page_id'],
                // 'PAGE_IDKEY' => $admin->getIDKEY($results_array['page_id']),
                'PAGE_IDKEY' => $results_array['page_id'],
                'PAGE_TITLE' => ($results_array['page_title']),
                'MENU_TITLE' => ($results_array['menu_title']),
                'DESCRIPTION' => ($results_array['description']),
                'KEYWORDS' => ($results_array['keywords']),
                'MODIFIED_BY' => $user['display_name'],
                'MODIFIED_BY_USERNAME' => $user['username'],
                'MODIFIED_WHEN' => $modified_ts,
                'ADMIN_URL' => ADMIN_URL,
                'WB_URL' => WB_URL,
                'THEME_URL' => THEME_URL
                )
        );

// Work-out if we should show the "manage sections" link
$sql = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'sections` WHERE `page_id`='.$page_id.' AND `module`="menu_link"';
$sections_available = (intval($database->get_one($sql)) != 0);
if ($sections_available)
{
    $template->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
} elseif(MANAGE_SECTIONS == 'enabled')
{
    $template->set_var('TEXT_MANAGE_SECTIONS', $HEADING['MANAGE_SECTIONS']);
} else {
    $template->set_var('DISPLAY_MANAGE_SECTIONS', 'display:none;');
}

// Visibility
if($results_array['visibility'] == 'public') {
    $template->set_var('PUBLIC_SELECTED', ' selected="selected"');
} elseif($results_array['visibility'] == 'private') {
    $template->set_var('PRIVATE_SELECTED', ' selected="selected"');
} elseif($results_array['visibility'] == 'registered') {
    $template->set_var('REGISTERED_SELECTED', ' selected="selected"');
} elseif($results_array['visibility'] == 'hidden') {
    $template->set_var('HIDDEN_SELECTED', ' selected="selected"');
} elseif($results_array['visibility'] == 'none') {
    $template->set_var('NO_VIS_SELECTED', ' selected="selected"');
}
// Group list 1 (admin_groups)
    $admin_groups = explode(',', str_replace('_', '', $results_array['admin_groups']));

    $sql = 'SELECT * FROM `'.TABLE_PREFIX.'groups`';
    $get_groups = $database->query($sql);

    $template->set_block('main_block', 'group_list_block', 'group_list');
    // Insert admin group and current group first
    $admin_group_name = $get_groups->fetchRow();
    $template->set_var(array(
                                    'ID' => 1,
                                    'TOGGLE' => '',
                                    'DISABLED' => ' disabled="disabled"',
                                    'LINK_COLOR' => '000000',
                                    'CURSOR' => 'default',
                                    'NAME' => $admin_group_name['name'],
                                    'CHECKED' => ' checked="checked"'
                                    )
                            );
    $template->parse('group_list', 'group_list_block', true);
    while($group = $get_groups->fetchRow()) {
        // check if the user is a member of this group
        $flag_disabled = '';
        $flag_checked =  '';
        $flag_cursor =   'pointer';
        $flag_color =    '';
        if (in_array($group["group_id"], $admin->get_groups_id())) {
            $flag_disabled = ''; //' disabled';
            $flag_checked =  ''; //' checked';
            $flag_cursor =   'default';
            $flag_color =    '000000';
        }

        // Check if the group is allowed to edit pages
        $system_permissions = explode(',', $group['system_permissions']);
        if(is_numeric(array_search('pages_modify', $system_permissions))) {
            $template->set_var(array(
                                            'ID' => $group['group_id'],
                                            'TOGGLE' => $group['group_id'],
                                            'DISABLED' => $flag_disabled,
                                            'LINK_COLOR' => $flag_color,
                                            'CURSOR' => $flag_cursor,
                                            'NAME' => $group['name'],
                                            'CHECKED' => $flag_checked
                                            )
                                    );
            if(is_numeric(array_search($group['group_id'], $admin_groups))) {
                $template->set_var('CHECKED', ' checked="checked"');
            } else {
                if (!$flag_checked) $template->set_var('CHECKED', '');
            }
            $template->parse('group_list', 'group_list_block', true);
        }
    }

// Group list 2 (viewing_groups)
    $viewing_groups = explode(',', str_replace('_', '', $results_array['viewing_groups']));

    $sql = 'SELECT * FROM `'.TABLE_PREFIX.'groups`';
    $get_groups = $database->query($sql);

    $template->set_block('main_block', 'group_list_block2', 'group_list2');
    // Insert admin group and current group first
    $admin_group_name = $get_groups->fetchRow();
    $template->set_var(array(
                                    'ID' => 1,
                                    'TOGGLE' => '',
                                    'DISABLED' => ' disabled="disabled"',
                                    'LINK_COLOR' => '000000',
                                    'CURSOR' => 'default',
                                    'NAME' => $admin_group_name['name'],
                                    'CHECKED' => ' checked="checked"'
                                    )
                            );
    $template->parse('group_list2', 'group_list_block2', true);

    while($group = $get_groups->fetchRow())
    {
        // check if the user is a member of this group
        $flag_disabled = '';
        $flag_checked =  '';
        $flag_cursor =   'pointer';
        $flag_color =    '';
        if (in_array($group["group_id"], $admin->get_groups_id()))
        {
            $flag_disabled = ''; //' disabled';
            $flag_checked =  ''; //' checked';
            $flag_cursor =   'default';
            $flag_color =    '000000';
        }

        $template->set_var(array(
                                        'ID' => $group['group_id'],
                                        'TOGGLE' => $group['group_id'],
                                        'DISABLED' => $flag_disabled,
                                        'LINK_COLOR' => $flag_color,
                                        'CURSOR' => $flag_cursor,
                                        'NAME' => $group['name'],
                                        'CHECKED' => $flag_checked
                                        )
                                );
        if(is_numeric(array_search($group['group_id'], $viewing_groups)))
        {
            $template->set_var('CHECKED', 'checked="checked"');
        } else {
            if (!$flag_checked) {$template->set_var('CHECKED', '');}
        }

        $template->parse('group_list2', 'group_list_block2', true);

    }

// Show private viewers
if($results_array['visibility'] == 'private' OR $results_array['visibility'] == 'registered')
{
    $template->set_var('DISPLAY_VIEWERS', '');
} else {
    $template->set_var('DISPLAY_VIEWERS', 'display:none;');
}

//-- insert page_code 20090904-->
$template->set_var('DISPLAY_CODE_PAGE_LIST', ' id="multi_lingual" style="display:none;"');

// Work-out if page languages feature is enabled
if((defined('PAGE_LANGUAGES') && PAGE_LANGUAGES) && $field_set && file_exists(WB_PATH.'/modules/mod_multilingual/update_keys.php') )
{
    // workout field is set but module missing
    $TEXT['PAGE_CODE'] = empty($TEXT['PAGE_CODE']) ? 'Pagecode' : $TEXT['PAGE_CODE'];
    $template->set_var( array(
            'DISPLAY_CODE_PAGE_LIST' => ' id="multi_lingual"',
            'TEXT_PAGE_CODE' => '<a href="'.WB_URL.'/modules/mod_multilingual/update_keys.php?page_id='.$page_id.'">'.$TEXT['PAGE_CODE'].'</a>'
        )
    );

    // Page_code list
   /*     $database = new database();  */
    function page_code_list($parent)
    {
        global $admin, $database, $template, $results_array, $pageCode;
        $default_language = DEFAULT_LANGUAGE;

        $sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `parent` = '.$parent.' AND `language` = "'.$default_language.'" ORDER BY `position` ASC';
        $get_pages = $database->query($sql);

        while($page = $get_pages->fetchRow())
        {
            if(($admin->page_is_visible($page)==false) && ($page['visibility'] <> 'none') ) { continue; }

            $template->set_var('FLAG_CODE_ICON',' none ');
            if( $page['parent'] == 0 )
            {
                $template->set_var('FLAG_CODE_ICON','url('.THEME_URL.'/images/flags/'.strtolower($page['language']).'.png)');
            }

            // If the current page cannot be parent, then its children neither
            $list_next_level = true;
            // Stop users from adding pages with a level of more than the set page level limit
            if($page['level']+1 < PAGE_LEVEL_LIMIT)
            {
                // Get user perms
                $admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
                $admin_users = explode(',', str_replace('_', '', $page['admin_users']));

                $in_group = FALSE;
                foreach($admin->get_groups_id() as $cur_gid)
                {
                    if (in_array($cur_gid, $admin_groups))
                    {
                        $in_group = TRUE;
                    }
                }

                if(($in_group) OR is_numeric(array_search($admin->get_user_id(), $admin_users)))
                {
                    $can_modify = true;
                } else {
                    $can_modify = false;
                }

                $title_prefix = '';
                for($i = 1; $i <= $page['level']; $i++) { $title_prefix .= ' - - &nbsp;'; }
                // $space = str_repeat('&nbsp;', 3);  $space.'&lt;'..'&gt;'
                $template->set_var(array(
                                        'VALUE' => $page['page_code'],
                                        'PAGE_VALUE' => $title_prefix.$page['menu_title'],
                                        'PAGE_CODE' => $title_prefix.$page['page_id']
                                        )
                                );
                if($results_array['page_code'] == $page['page_code'])
                {
                    $template->set_var('SELECTED', ' selected="selected"');
                } elseif($results_array['page_code'] == $page['page_code'])
                {
                    $template->set_var('SELECTED', ' disabled="disabled" class="disabled"');
                    $list_next_level=false;
                } elseif($can_modify != true)
                {
                    $template->set_var('SELECTED', ' disabled="disabled" class="disabled"');
                } else {
                    $template->set_var('SELECTED', '');
                }
                $template->parse('page_code_list', 'page_code_list_block', true);
            }
            if ($list_next_level)
                page_code_list($page['page_id']);
        }
    }

    // Insert code_page values from page to modify
    $template->set_block('main_block', 'page_code_list_block', 'page_code_list');
    if($admin->get_permission('pages_add_l0') == true OR $results_array['level'] == 0) {
        if($results_array['parent'] == 0) { $selected = ' selected'; } else { $selected = ''; }
        $template->set_var(array(
                                    'VALUE' => '',
                                    'PAGE_CODE' => $TEXT['NONE'],
                                    'PAGE_VALUE' => '',
                                    'SELECTED' => $selected
                                )
                            );
        $template->parse('page_code_list', 'page_code_list_block', true);
    }
    // get pagecode form this page_id
       page_code_list(0);
}
//-- page code -->

// Parent page list
/* $database = new database();  */
function parent_list($parent)
{
    global $admin, $database, $template, $results_array,$field_set;

    $sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `parent` = '.$parent.' ORDER BY `position` ASC';
    $get_pages = $database->query($sql);

    while($page = $get_pages->fetchRow())
    {
        if($admin->page_is_visible($page)==false)
        {
          continue;
        }

        // if parent = 0 set flag_icon
        $template->set_var('FLAG_ROOT_ICON',' none ');
        if( $page['parent'] == 0  && $field_set)
        {
            $template->set_var('FLAG_ROOT_ICON','url('.THEME_URL.'/images/flags/'.strtolower($page['language']).'.png)');
        }
        // If the current page cannot be parent, then its children neither
        $list_next_level = true;
        // Stop users from adding pages with a level of more than the set page level limit
        if($page['level']+1 < PAGE_LEVEL_LIMIT)
        {
            // Get user perms
            $admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
            $admin_users = explode(',', str_replace('_', '', $page['admin_users']));
            $in_group = FALSE;
            foreach($admin->get_groups_id() as $cur_gid)
            {
                if (in_array($cur_gid, $admin_groups))
                {
                    $in_group = TRUE;
                }
            }
            if(($in_group) OR is_numeric(array_search($admin->get_user_id(), $admin_users)))
            {
                $can_modify = true;
            } else {
                $can_modify = false;
            }
            // Title -'s prefix
            $title_prefix = '';
            for($i = 1; $i <= $page['level']; $i++) { $title_prefix .= ' - - &nbsp;'; }
            $template->set_var(array(
                                'ID' => $page['page_id'],
                                'TITLE' => ($title_prefix.$page['menu_title']),
                                'MENU-TITLE' => ($title_prefix.$page['menu_title']),
                                'PAGE-TITLE' => ($title_prefix.$page['page_title']),
                                'FLAG_ICON' => ' none ',
                                ));

            if($results_array['parent'] == $page['page_id'])
            {
                $template->set_var('SELECTED', ' selected="selected"');
            } elseif($results_array['page_id'] == $page['page_id'])
            {
                $template->set_var('SELECTED', ' disabled="disabled" class="disabled"');
                $list_next_level=false;
            } elseif($can_modify != true)
            {
                $template->set_var('SELECTED', ' disabled="disabled" class="disabled"');
            } else {
                $template->set_var('SELECTED', '');
            }
            $template->parse('page_list2', 'page_list_block2', true);
        }
        if ($list_next_level)
        {
          parent_list($page['page_id']);
        }

    }
}

$template->set_block('main_block', 'page_list_block2', 'page_list2');
if($admin->get_permission('pages_add_l0') == true OR $results_array['level'] == 0) {
    if($results_array['parent'] == 0)
    {
        $selected = ' selected="selected"';
    } else { 
        $selected = '';
    }
    $template->set_var(array(
                        'ID' => '0',
                        'TITLE' => $TEXT['NONE'],
                        'SELECTED' => $selected
                        ) );
    $template->parse('page_list2', 'page_list_block2', true);
}
parent_list(0);

if($modified_ts == 'Unknown')
{
    $template->set_var('DISPLAY_MODIFIED', 'hide');
} else {
    $template->set_var('DISPLAY_MODIFIED', '');
}

// Templates list
$template->set_block('main_block', 'template_list_block', 'template_list');

$sql = 'SELECT * FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" AND `function` = "template" order by `name`';
if( ($res_templates = $database->query($sql)) )
{
    while($rec_template = $res_templates->fetchRow())
    {
        // Check if the user has perms to use this template
        if($rec_template['directory'] == $results_array['template'] OR $admin->get_permission($rec_template['directory'], 'template') == true)
        {
            $template->set_var('VALUE', $rec_template['directory']);
            $template->set_var('NAME', $rec_template['name']);
            if($rec_template['directory'] == $results_array['template'])
            {
                $template->set_var('SELECTED', ' selected="selected"');
            } else {
                $template->set_var('SELECTED', '');
            }
            $template->parse('template_list', 'template_list_block', true);
        }
    }
}

// Menu list
if(MULTIPLE_MENUS == false)
{
    $template->set_var('DISPLAY_MENU_LIST', 'display:none;');
}
// Include template info file (if it exists)
if($results_array['template'] != '')
{
    $template_location = WB_PATH.'/templates/'.$results_array['template'].'/info.php';
} else {
    $template_location = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/info.php';
}
if(file_exists($template_location))
{
    require($template_location);
}
// Check if $menu is set
if(!isset($menu[1]) OR $menu[1] == '')
{
    // Make our own menu list
    $menu[1] = $TEXT['MAIN'];
}
// Add menu options to the list
$template->set_block('main_block', 'menu_list_block', 'menu_list');
foreach($menu AS $number => $name)
{
    $template->set_var('NAME', $name);
    $template->set_var('VALUE', $number);
    if($results_array['menu'] == $number)
    {
        $template->set_var('SELECTED', ' selected="selected"');
    } else {
        $template->set_var('SELECTED', '');
    }
    $template->parse('menu_list', 'menu_list_block', true);
}

// Insert language values
$template->set_block('main_block', 'language_list_block', 'language_list');

$sql = 'SELECT * FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "language" ORDER BY `name`';
if( ($res_languages = $database->query($sql)) )
{
    while($rec_language = $res_languages->fetchRow())
    {
        $l_codes[$rec_language['name']] = $rec_language['directory'];
        $l_names[$rec_language['name']] = entities_to_7bit($rec_language['name']); // sorting-problem workaround
    }
    asort($l_names);

    foreach($l_names as $l_name=>$v)
    {
        $langIcons = (empty($l_codes[$l_name])) ? 'none' : strtolower($l_codes[$l_name]);
        // Insert code and name
        $template->set_var(array(
                                'VALUE' => $l_codes[$l_name],
                                'NAME' => $l_name,
                                'FLAG_LANG_ICONS' => 'url('.THEME_URL.'/images/flags/'.$langIcons.'.png)',
                                ));
        // Check if it is selected
        if($results_array['language'] == $l_codes[$l_name])
        {
            $template->set_var('SELECTED', ' selected="selected"');
        } else {
            $template->set_var('SELECTED', '');
        }
        $template->parse('language_list', 'language_list_block', true);
    }
}

// Select disabled if searching is disabled
if($results_array['searching'] == 0)
{
    $template->set_var('SEARCHING_DISABLED', ' selected="selected"');
}
// Select what the page target is
switch ($results_array['target'])
{
    case '_top':
        $template->set_var('TOP_SELECTED', ' selected="selected"');
        break;
    case '_self':
        $template->set_var('SELF_SELECTED', ' selected="selected"');
        break;
    case '_blank':
        $template->set_var('BLANK_SELECTED', ' selected="selected"');
        break;
}

// Insert language text
$template->set_var(array(
                'HEADING_MODIFY_PAGE_SETTINGS' => $HEADING['MODIFY_PAGE_SETTINGS'],
                'TEXT_CURRENT_PAGE' => $TEXT['CURRENT_PAGE'],
                'TEXT_MODIFY' => $TEXT['MODIFY'],
                'TEXT_MODIFY_PAGE' => $HEADING['MODIFY_PAGE'],
                'LAST_MODIFIED' => $MESSAGE['PAGES']['LAST_MODIFIED'],
                'TEXT_PAGE_TITLE' => $TEXT['PAGE_TITLE'],
                'TEXT_MENU_TITLE' => $TEXT['MENU_TITLE'],
                'TEXT_TYPE' => $TEXT['TYPE'],
                'TEXT_MENU' => $TEXT['MENU'],
                'TEXT_PARENT' => $TEXT['PARENT'],
                'TEXT_VISIBILITY' => $TEXT['VISIBILITY'],
                'TEXT_PUBLIC' => $TEXT['PUBLIC'],
                'TEXT_PRIVATE' => $TEXT['PRIVATE'],
                'TEXT_REGISTERED' => $TEXT['REGISTERED'],
                'TEXT_NONE' => $TEXT['NONE'],
                'TEXT_HIDDEN' => $TEXT['HIDDEN'],
                'TEXT_TEMPLATE' => $TEXT['TEMPLATE'],
                'TEXT_TARGET' => $TEXT['TARGET'],
                'TEXT_SYSTEM_DEFAULT' => $TEXT['SYSTEM_DEFAULT'],
                'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
                'TEXT_NEW_WINDOW' => $TEXT['NEW_WINDOW'],
                'TEXT_SAME_WINDOW' => $TEXT['SAME_WINDOW'],
                'TEXT_TOP_FRAME' => $TEXT['TOP_FRAME'],
                'TEXT_ADMINISTRATORS' => $TEXT['ADMINISTRATORS'],
                'TEXT_ALLOWED_VIEWERS' => $TEXT['ALLOWED_VIEWERS'],
                'TEXT_DESCRIPTION' => $TEXT['DESCRIPTION'],
                'TEXT_KEYWORDS' => $TEXT['KEYWORDS'],
                'TEXT_SEARCHING' => $TEXT['SEARCHING'],
                'TEXT_LANGUAGE' => $TEXT['LANGUAGE'],
                'TEXT_ENABLED' => $TEXT['ENABLED'],
                'TEXT_DISABLED' => $TEXT['DISABLED'],
                'TEXT_SAVE' => $TEXT['SAVE'],
                'TEXT_RESET' => $TEXT['RESET'],
                'LAST_MODIFIED' => $MESSAGE['PAGES']['LAST_MODIFIED'],
                'HEADING_MODIFY_PAGE' => $HEADING['MODIFY_PAGE']
            ) );

$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();
