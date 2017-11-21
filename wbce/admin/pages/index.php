<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages');

$admin->clearIDKEY();

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Include page tree and define output
ob_start();
if (file_exists(THEME_PATH.'/patch/page_tree.php')) {
	require_once(THEME_PATH.'/patch/page_tree.php');
} else {
	require_once(dirname(__FILE__).'/page_tree/page_tree.php');
}
$pageTreeOutput = ob_get_clean();

// Setup template object
$template = new Template(dirname($admin->correct_theme_source('pages.htt')));

// Disable removing of unknown vars
$template->set_unknowns('keep');

$template->set_file('page', 'pages.htt');
$template->set_block('page', 'main_block', 'main');
$template->set_var('FTAN', $admin->getFTAN());

// Set page tree as var
$template->set_var('PAGE_TREE', $pageTreeOutput);


/**
 *	Insert values into the add page form
 *
 */

// Group list 1

    $query = "SELECT * FROM ".TABLE_PREFIX."groups";
    $get_groups = $database->query($query);
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
            $flag_checked =  ' checked="checked"';
            $flag_cursor =   'default';
            $flag_color =    '000000';
        }

        // Check if the group is allowed to edit pages
        $system_permissions = explode(',', $group['system_permissions']);
        if(is_numeric(array_search('pages_modify', $system_permissions))) {
            $template->set_var(array(
                                            'ID' => $group['group_id'],
                                            'TOGGLE' => $group['group_id'],
                                            'CHECKED' => $flag_checked,
                                            'DISABLED' => $flag_disabled,
                                            'LINK_COLOR' => $flag_color,
                                            'CURSOR' => $flag_checked,
                                            'NAME' => $group['name'],
                                            )
                                    );
            $template->parse('group_list', 'group_list_block', true);
        }
    }
// Group list 2

    $query = "SELECT * FROM ".TABLE_PREFIX."groups";

    $get_groups = $database->query($query);
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

    while($group = $get_groups->fetchRow()) {
        // check if the user is a member of this group
        $flag_disabled = '';
        $flag_checked =  '';
        $flag_cursor =   'pointer';
        $flag_color =    '';
        if (in_array($group["group_id"], $admin->get_groups_id())) {
            $flag_disabled = ''; //' disabled';
            $flag_checked =  ' checked="checked"';
            $flag_cursor =   'default';
            $flag_color =    '000000';
        }

        $template->set_var(array(
                                        'ID' => $group['group_id'],
                                        'TOGGLE' => $group['group_id'],
                                        'CHECKED' => $flag_checked,
                                        'DISABLED' => $flag_disabled,
                                        'LINK_COLOR' => $flag_color,
                                        'CURSOR' => $flag_cursor,
                                        'NAME' => $group['name'],
                                        )
                                );
        $template->parse('group_list2', 'group_list_block2', true);
    }


// Parent page list
// $database = new database();
function parent_list($parent){
    global $admin, $database, $template, $field_set;
    $query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' AND visibility!='deleted' ORDER BY position ASC";
    $get_pages = $database->query($query);
    while($page = $get_pages->fetchRow()) {
        if($admin->page_is_visible($page)==false)
            continue;
        // if parent = 0 set flag_icon
        $template->set_var('FLAG_ROOT_ICON',' none ');
        if( $page['parent'] == 0 && $field_set) {
            $template->set_var('FLAG_ROOT_ICON','url('.WB_URL.'/languages/'.strtoupper($page['language']).'.png)');
        }
        // Stop users from adding pages with a level of more than the set page level limit
        if($page['level']+1 < PAGE_LEVEL_LIMIT) {
            // Get user perms
            $admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
            $admin_users = explode(',', str_replace('_', '', $page['admin_users']));

            $in_group = FALSE;
            foreach($admin->get_groups_id() as $cur_gid) {
                if (in_array($cur_gid, $admin_groups)) {
                    $in_group = TRUE;
                }
            }
			if(($in_group) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
                $can_modify = true;
            } else {
                $can_modify = false;
            }
            // Title -'s prefix
            $title_prefix = '';
			for($i = 1; $i <= $page['level']; $i++) { $title_prefix .= ' - '; }
                $template->set_var(array(
                                        'ID' => $page['page_id'],
                                        'TITLE' => ($title_prefix.$page['menu_title']),
                                        'MENU-TITLE' => ($title_prefix.$page['menu_title']),
                                        'PAGE-TITLE' => ($title_prefix.$page['page_title'])
					)
				);
                if($can_modify == true) {
                    $template->set_var('DISABLED', '');
                } else {
                    $template->set_var('DISABLED', ' disabled="disabled" class="disabled"');
                }
                $template->parse('page_list2', 'page_list_block2', true);
        }
        parent_list($page['page_id']);
    }
}
$template->set_block('main_block', 'page_list_block2', 'page_list2');
if($admin->get_permission('pages_add_l0') == true) {
    $template->set_var(array(
                        'ID' => '0',
                        'TITLE' => $TEXT['NONE'],
                        'SELECTED' => ' selected="selected"',
                        'DISABLED' => ''
                    )
                );
    $template->parse('page_list2', 'page_list_block2', true);
}
parent_list(0);

// Explode module permissions
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];
// Modules list
$template->set_block('main_block', 'module_list_block', 'module_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function LIKE '%page%' order by name");
if($result->numRows() > 0) {
    while ($module = $result->fetchRow()) {
        // Check if user is allowed to use this module
        if(!is_numeric(array_search($module['directory'], $module_permissions))) {
            $template->set_var('VALUE', $module['directory']);
            $template->set_var('NAME', $module['name']);
            if($module['directory'] == 'wysiwyg') {
                $template->set_var('SELECTED', ' selected="selected"');
            } else {
                $template->set_var('SELECTED', '');
            }
            $template->parse('module_list', 'module_list_block', true);
        }
    }
}

// Insert urls
$template->set_var(array(
                                'THEME_URL' => THEME_URL,
                                'WB_URL' => WB_URL,
	'WB_PATH' => WB_PATH,
                                'ADMIN_URL' => ADMIN_URL,
                                )
                        );

// Insert language headings
$template->set_var(array(
                                'HEADING_ADD_PAGE' => $HEADING['ADD_PAGE'],
                                'HEADING_MODIFY_INTRO_PAGE' => $HEADING['MODIFY_INTRO_PAGE']
                                )
                        );
// Insert language text and messages
$template->set_var(array(
								'TEXT_PAGES' => $MENU['PAGES'],
                                'TEXT_TITLE' => $TEXT['TITLE'],
                                'TEXT_TYPE' => $TEXT['TYPE'],
                                'TEXT_PARENT' => $TEXT['PARENT'],
                                'TEXT_VISIBILITY' => $TEXT['VISIBILITY'],
                                'TEXT_PUBLIC' => $TEXT['PUBLIC'],
                                'TEXT_PRIVATE' => $TEXT['PRIVATE'],
                                'TEXT_REGISTERED' => $TEXT['REGISTERED'],
                                'TEXT_HIDDEN' => $TEXT['HIDDEN'],
                                'TEXT_NONE' => $TEXT['NONE'],
                                'TEXT_NONE_FOUND' => $TEXT['NONE_FOUND'],
                                'TEXT_ADD' => $TEXT['ADD'],
                                'TEXT_RESET' => $TEXT['RESET'],
                                'TEXT_ADMINISTRATORS' => $TEXT['ADMINISTRATORS'],
                                'TEXT_PRIVATE_VIEWERS' => $TEXT['PRIVATE_VIEWERS'],
                                'TEXT_REGISTERED_VIEWERS' => $TEXT['REGISTERED_VIEWERS'],
                                'INTRO_LINK' => $MESSAGE['PAGES_INTRO_LINK'],
                                )
                        );

// Insert permissions values
if($admin->get_permission('pages_add') != true) {
    $template->set_var('DISPLAY_ADD', 'hide');
} elseif($admin->get_permission('pages_add_l0') != true AND $editable_pages == 0) {
    $template->set_var('DISPLAY_ADD', 'hide');
}
if($admin->get_permission('pages_intro') != true OR INTRO_PAGE != 'enabled') {
    $template->set_var('DISPLAY_INTRO', 'hide');
}

// Include JavaScript backend includes and define output
ob_start();
$jsadminFile = WB_PATH.'/modules/jsadmin/jsadmin_backend_include.php';
if(is_file($jsadminFile)) {
	include($jsadminFile);
}
$jsAdminOutput = ob_get_clean();

// Oadd eggsurplus Javascript to output
$jsAdminOutput .= PHP_EOL . '<script type="text/javascript" src="eggsurplus.js"></script>';

// Set JavaScript backend as var
$template->set_var('JS_ADMIN', $jsAdminOutput);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin
$admin->print_footer();
?>
