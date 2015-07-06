<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

// make sure that a page to clone was specified
$pagetodo = isset($_GET['pagetoclone']) ? (int) $_GET['pagetoclone'] : 0;

// check if specified page exists in the database
$query = "SELECT * FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '$pagetodo'";
$get_pagetodo = $database->query($query);   
$is_pagetodo = $get_pagetodo->fetchRow();

// check website baker platform (with WB 2.7, Admin-Tools were moved out of settings dialogue)
$admintool_link = ADMIN_URL .'/settings/index.php?advanced=yes#administration_tools"';
$pageclone_link = ADMIN_URL .'/settings/tool.php?tool=pagecloner';
if(file_exists(ADMIN_PATH .'/admintools/tool.php')) {
	$admintool_link = ADMIN_URL .'/admintools/index.php';
	$pageclone_link = ADMIN_URL .'/admintools/tool.php?tool=pagecloner';
}

// redirect to pageclone main page if no valid page was specified
if ($pagetodo < 1 || !$is_pagetodo) {
	die(header('Location: '.$pageclone_link));
} 

// create admin object depending on platform (admin tools were moved out of settings with WB 2.7)
if(file_exists(ADMIN_PATH .'/admintools/tool.php')) {
	// since Website Baker 2.7
	$admin = new admin('admintools', 'admintools');
} else {
	// Website Baker prior to 2.7
	$admin = new admin('Settings', 'settings_advanced');
}

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/pagecloner/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/pagecloner/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/pagecloner/languages/'.LANGUAGE.'.php');
	}
}
// And... action
if ($pagetodo > 0 && $is_pagetodo) {
	// write out admint tool header
	?>
	<h4 style="margin: 0; border-bottom: 1px solid #DDD; padding-bottom: 5px;">
		<a href="<?php echo $admintool_link;?>"><?php echo $HEADING['ADMINISTRATION_TOOLS']; ?></a>
		->
		<a href="<?php echo $pageclone_link;?>">Page Cloner Tree</a>
		-> <?php echo $is_pagetodo['menu_title'];?>
	</h4>
	<?php


// Setup template object
$template = new Template(WB_PATH.'/modules/pagecloner');
$template->set_file('page', 'template.html');
$template->set_block('page', 'main_block', 'main');

// Parent page list
$database = new database();
function parent_list($parent) {
	global $admin, $database, $template;
	$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' AND visibility!='deleted' ORDER BY position ASC";
	$get_pages = $database->query($query);
	while($page = $get_pages->fetchRow()) {
		// Stop users from adding pages with a level of more than the set page level limit
		if($page['level']+1 < PAGE_LEVEL_LIMIT) {
			// Get user perms
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));
			if(is_numeric(array_search($admin->get_group_id(), $admin_groups)) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
				$can_modify = true;
			} else {
				$can_modify = false;
			}
			// Title -'s prefix
			$title_prefix = '';
			for($i = 1; $i <= $page['level']; $i++) { $title_prefix .= ' - '; }
				$template->set_var(array(
												'ID' => $page['page_id'],
												'TITLE' => ($title_prefix.$page['page_title'])
												)
										);
				if($can_modify == true) {
					$template->set_var('DISABLED', '');
				} else {
					$template->set_var('DISABLED', ' disabled');
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
									'SELECTED' => ' selected',
									'DISABLED' => ''
									)
							);
	$template->parse('page_list2', 'page_list_block2', true);
}
parent_list(0);
// Insert language headings
$template->set_var(array(
								'HEADING_ADD_PAGE' => $PCTEXT['CLONE_PAGETO'],
								'HEADING_MODIFY_INTRO_PAGE' => $HEADING['MODIFY_INTRO_PAGE']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'TEXT_TITLE' => $TEXT['TITLE'],
								'TEXT_DEFAULT' => $is_pagetodo['menu_title'],
								'TEXT_TYPE' => $TEXT['TYPE'],
								'TEXT_PARENT' => $TEXT['PARENT'],
                                'TEXT_INCLUDE_PAGETITLE' => $PCTEXT['INCLUDE_PAGETITLE'],
                                'TEXT_INCLUDE_PAGETITLE_HELP' => $PCTEXT['INCLUDE_PAGETITLE_HELP'],
								'TEXT_INCLUDE_SUBS' => $PCTEXT['INCLUDE_SUBS'],
								'TEXT_VISIBILITY' => $TEXT['VISIBILITY'],
								'TEXT_PUBLIC' => $TEXT['PUBLIC'],
								'TEXT_PRIVATE' => $TEXT['PRIVATE'],
								'TEXT_REGISTERED' => $TEXT['REGISTERED'],
								'TEXT_HIDDEN' => $TEXT['HIDDEN'],
								'TEXT_NONE' => $TEXT['NONE'],
								'TEXT_NONE_FOUND' => $TEXT['NONE_FOUND'],
								'TEXT_PAGETODO' => $pagetodo,
								'TEXT_ADD' => $PCTEXT['ADD'],
								'TEXT_RESET' => $TEXT['RESET'],
								'TEXT_ADMINISTRATORS' => $TEXT['ADMINISTRATORS'],								
								'TEXT_PRIVATE_VIEWERS' => $TEXT['PRIVATE_VIEWERS'],
								'TEXT_REGISTERED_VIEWERS' => $TEXT['REGISTERED_VIEWERS'],
								'INTRO_LINK' => $MESSAGE['PAGES']['INTRO_LINK'],
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


// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');
}	
$admin->print_footer();

?>