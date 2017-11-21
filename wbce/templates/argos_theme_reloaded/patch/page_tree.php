<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Christian M. Stefan (Stefek)
 * @copyright adapted version Bernd J. Michna (berndjm)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

$use_dragdrop_switch = FALSE; //set 'TRUE' if you'd like to enable drag/drop activation below the pagetree

// prevent this file from being accessed directly
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

/**
 * do the DB query to grab for all pages first
 */

$pages = $database->query("SELECT *	FROM `".TABLE_PREFIX."pages` ORDER BY position ASC");
$number_all_pages = $pages->numRows();

$refs = array();
$list = array();
// check if thorns wysiwyg history & draft is installed
$use_working_copy = (file_exists(WB_PATH.'/modules/wysiwyg/manage_history.php'))?true:false;

// create $list[] Array
while ($page = $pages->fetchRow()) :
    $thisref = &$refs[ $page['page_id'] ];

	$thisref['parent'] 		= $page['parent'];
	$thisref['root_parent']	= $page['root_parent'];
    $thisref['menu_title'] 	= $page['menu_title'];
    $thisref['page_title'] 	= $page['page_title'];
    $thisref['level'] 		= $page['level'];
    $thisref['visibility'] 	= $page['visibility'];
    $thisref['admin_groups'] = $page['admin_groups'];
    $thisref['admin_users'] = $page['admin_users'];
    $thisref['position'] 	= $page['position'];
    $thisref['page_id'] 	= $page['page_id'];
    $thisref['link'] 		= $page['link'];

    if ($page['parent'] == 0) :
		$pages_list[ $page['page_id'] ] = &$thisref;
	else :
		$refs[ $page['parent'] ]['children'][ $page['page_id'] ] = &$thisref;
	endif;
endwhile;

/*** map the status img's to fa's *****************************/
$statusMap = array(
	'public'		=> 'fa-eye',
	'hidden'		=> 'fa-eye-slash',
	'registered'	=> 'fa-key',
	'private'		=> 'fa-user-secret',
	'none'			=> 'fa-ban',
	'deleted'		=> 'fa-trash red',
);

/*** function draw_pagetree() *****************************************/
function draw_pagetree($pages_list) {
	global $admin, $database, $use_working_copy, $icons_dir, $TEXT, $HEADING, $MESSAGE, $statusMap;
	$siblings = count($pages_list);

	$html = "<ul id=\"p%d\" %s>\n";

	foreach ($pages_list as $key=>$p) :

		// Get user perms
		$admin_groups = explode(',', str_replace('_', '', $p['admin_groups']));
		$admin_users = explode(',', str_replace('_', '', $p['admin_users']));
		$in_group = false;

		foreach($admin->get_groups_id() as $cur_gid)
			if (in_array($cur_gid, $admin_groups)) 	$in_group = true;

		if(($in_group) || is_numeric(array_search($admin->get_user_id(), $admin_users))){
			if($p['visibility'] == 'deleted'){
				if (PAGE_TRASH == 'inline')
						$can_modify = true;
				else 	$can_modify = false;
			}
		elseif ($p['visibility'] != 'deleted')
			$can_modify = true;
		} else {
			if ($p['visibility'] == 'private')	continue;
			else $can_modify = false;
		}

		// $expandable plus/minus
		$expandable = (array_key_exists('children', $p))?true:false;
		if ($expandable == true) :
			$plus_minusIcon = 'fa-folder-o'; $plus_minusAlt = '[+]';
			if (isset($_COOKIE['p'.$p['page_id']]) && $_COOKIE['p'.$p['page_id']] == '1') :
				$plus_minusIcon = 'fa-folder-open-o';	$plus_minusAlt = '[-]';
			endif;
			$plus_minusTitle = strtolower($TEXT['EXPAND'].' / '. $TEXT['COLLAPSE']);
		endif;

		// vars to check if we should show specific Options (and Icons) depending on User Permissions
		$canMoveUP = ($p['position'] != 1 && $p['visibility'] != 'deleted' && $admin->get_permission('pages_settings') == true && $can_modify == true)?true:false;

		$canMoveDOWN = ($p['position'] != $siblings && $p['visibility'] != 'deleted' && $admin->get_permission('pages_settings') == true && $can_modify == true)?true:false;

		$canDeleteAndModify = ($admin->get_permission('pages_delete') == true && $can_modify == true)?true:false;

		$canAddChild = ($admin->get_permission('pages_add')) == (true && $can_modify == true) && ($p['visibility'] != 'deleted')?true:false;

		$canModifyPage = ($admin->get_permission('pages_modify') == true && $can_modify == true)?true:false;

		$canModifySettings = ($admin->get_permission('pages_settings') == true && $can_modify == true)?true:false;

		$menu_link = false;

        // manage SECTIONS and DATES Icons -->
		$canManageSections = (MANAGE_SECTIONS == 'enabled' && $admin->get_permission('pages_modify') == true && $can_modify == true)?true:false;

		// query publ_start / publ_end
		if ($canManageSections == true) :
			if ($query_sections = $database->query('SELECT `publ_start`, `publ_end`,`module` FROM `'.TABLE_PREFIX.'sections` WHERE `page_id` = '.$p['page_id'])) :
				$sectionICON = "fa-bars";
				$sectionsURL = '../pages/sections.php?page_id='.$p['page_id'];
				$menu_link = false;
				while ($row = $query_sections->fetchRow()) :
					if ($row['module'] == 'menu_link') :
						$menu_link = true;
					endif;
					if ($row['publ_start']!='0' || $row['publ_end']!='0') :
						$sectionICON = ($admin->page_is_active($p)) ? "fa-clock-o" : "fa-clock-o red";
					endif;
				endwhile;
			endif;
		endif;

		foreach ($statusMap as $img => $fa) :
			if ($p['visibility'] == $img) :
				$status_icon = $fa;
			endif;
		endforeach;

		$placeholders = array(
			'{MENU_TITLE}'	 	=> str_replace("%", "&#037;", $p['menu_title']),
			'{PAGE_TITLE}' 		=> str_replace("%", "&#037;", $p['page_title']),
			'{PAGE_ID}'			=> $p['page_id'],
			'{pageIDKEY}'		=> $admin->getIDKEY($p['page_id']),
			'{PARENT}'			=> $p['parent'],
			'{status_icon}'		=> $status_icon,
			'{status_hint}' 	=> $TEXT['VISIBILITY'].': '.$TEXT[strtoupper($p['visibility'])],
			'{section_icon}' 	=> isset($sectionICON) ? $sectionICON : '',
			'{modifyPageURL}' 	=> '../pages/modify.php?page_id='.$p['page_id'],
			'{frontendViewURL}' => $admin->page_link($p['link']),
			'{modifySettingsURL}' => '../pages/settings.php?page_id='.$p['page_id'],
			'{restoreURL}' 		=> '../pages/restore.php?page_id='.$p['page_id'],
			'{THEME_ICONS}'		=> THEME_URL.'/images', // move_up/_down
			'{padding_left}'	=> 'padding-left: '.(($p['level'] > 0) ? $p['level']*25 + 5 : '5').'px;',
			'{padpix}'          => 400-(($p['level'] > 0) ? $p['level']*28 :'5')
		);

/*** set "Template" ***************************************************/
// ! relevant table cell for drag'n'drop sorting: #7 (0-indexed) !

ob_start();
?>
	<li class="p{PARENT}">
		<table class="pages-view" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class="page-status" style="{padding_left}">
					<?php if ($expandable == true): ?>
						<a href="javascript: toggle_visibility('p{PAGE_ID}');" title="<?php echo $plus_minusTitle; ?>">
							<i class="fa fa-lg <?php echo $plus_minusIcon?> plus-minus-{PAGE_ID} bold"  onclick="togglePlusMinus('{PAGE_ID}');"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; ?>
				</td>

				<td class="page-menu-title">
					<i class="fa fa-lg {status_icon}" style="padding-left: 3px;"></i>
					<a <?php if($canModifyPage) : ?> href="{modifyPageURL}" title="<?php echo $HEADING['MODIFY_PAGE']; ?>"<?php endif; ?>>
						{MENU_TITLE}
					</a>
				</td>

				<td class="page-page-title" >
					<?php if(!$menu_link): ?>
						{PAGE_TITLE}
					<?php endif; ?>
				</td>

				<td class="page-page-id">
					{PAGE_ID}
				</td>

				<?php
				// check if thorns working copy is installed
				if($use_working_copy):
				?>
					<td width="20"><?php
						if ($canModifyPage) :
							// check if we should show the wysiwyg_copy-icon
							if($len = $database->get_one("SELECT MAX(LENGTH(working_content)) FROM `".TABLE_PREFIX."mod_wysiwyg` WHERE `page_id` = '{$p['page_id']}' ")):
								if ($len !=NULL && $len !=0): ?>
									<a href="../pages/modify.php?page_id={PAGE_ID}&status=workingcopy" title="<?php echo "Working-Copy"; ?>">
										<small><img src="{THEME_ICONS}/wysiwyg_copy_16.png" border="0" alt="[<?php echo "Working-Copy"; ?>]" /></small>
									</a>
								<?php endif;
							endif;
						else: ?>
							<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
						<?php endif; // $canModifyPage ?>
					</td>
				<?php endif; //$use_working_copy ?>

				<td class="page-edit">
					<?php if ($canModifyPage) : ?>
						<a href="{modifyPageURL}" title="<?php echo $HEADING['MODIFY_PAGE']; ?>">
							<i class="fa fa-lg fa-edit"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; ?>
				</td>

				<td class="page-settings">
					<?php if ($p['visibility'] != 'deleted') : ?>
						<?php if($canModifySettings) : ?>
							<a href="{modifySettingsURL}" title="<?php echo $HEADING['MODIFY_PAGE_SETTINGS']; ?>">
								<i class="fa fa-lg fa-cog"></i>
							</a>
						<?php else: ?>
							<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
						<?php endif; ?>
					<?php elseif($p['visibility'] == 'deleted') :?>
						<a href="{restoreURL}" title="<?php echo $TEXT['RESTORE']; ?>">
							<i class="fa fa-lg fa-refresh green"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; ?>
				</td>

				<td class="page-sections">
					<?php // only show manageSections Link if we have to!
					//menu-link?
					if (isset ($menu_link) && $menu_link == true) : ?>
						<i class="fa fa-lg fa-link"></i>
					<?php elseif ($canManageSections) : ?>
						<a href="<?php echo $sectionsURL; ?>" title="<?php echo $HEADING['MANAGE_SECTIONS']; ?>">
							<i class="fa fa-lg {section_icon}"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; ?>
				</td>

				<td class="page-move-up">
					<?php if ($canMoveUP) : ?>
						<a href="../pages/move_up.php?page_id={PAGE_ID}" title="<?php echo $TEXT['MOVE_UP']; ?>">
							<i class="fa fa-lg fa-chevron-up"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; ?>
				</td>

				<td class="page-move-down">
					<?php if ($canMoveDOWN) : ?>
						<a href="../pages/move_down.php?page_id={PAGE_ID}" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
							<i class="fa fa-lg fa-chevron-down"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; //$canMoveDOWN ?>
				</td>

				<td class="page-show">
					<?php if ($p['visibility'] != 'deleted' && $p['visibility'] != 'none') : ?>
						<a href="{frontendViewURL}" target="_blank" title="<?php echo $TEXT['VIEW']; ?> (Frontend)">
							<i class="fa fa-lg fa-desktop"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; ?>
				</td>

				<td class="page-delete">
					<?php if ($canDeleteAndModify) : ?>
						<a href="#" onclick="confirm_link('{PAGE_ID}\n\n\t<?php echo $MESSAGE['PAGES_DELETE_CONFIRM']; ?>?','../pages/delete.php?page_id={pageIDKEY}');" title="<?php echo $TEXT['DELETE']; ?>">
							<i class="fa fa-lg fa-remove red"></i>
						</a>
					<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; ?>
				</td>

				<td class="page-add-child">
					<?php if ($canAddChild) : ?>
						<a href="#heading-add-page" onclick="add_child_page('{PAGE_ID}');" title="<?php echo $HEADING['ADD_CHILD_PAGE']; ?>">
							<i class="fa fa-lg fa-copy"></i>
						</a>
					<?php endif; ?>
				</td>
			</tr>
		</table>
	</li>

		<?php
		//fetch the above "template" into variable
		$item_template = ob_get_clean();

		if (array_key_exists('children', $p)) {
			$html .= str_replace(array_keys($placeholders), array_values($placeholders), $item_template);
			$html .= draw_pagetree($p['children']);
			//continue;
		}else{
			$html .= str_replace(array_keys($placeholders), array_values($placeholders), $item_template);
			$html .= sprintf('<ul id="p%d" class="page-list"><li>&nbsp;</li></ul>', $p['page_id'])."\n";
		}
	endforeach;

	$html .= '</ul>';

	// class="page_list" for all UL's larger than parent=0
	$ULpage_listClass =	($p['parent'] != 0) ? ' class="page-list"' : '';

	// display:block depending on cookie (JS Admin Toggle)
	$ULblockStyle 	= 	(isset($_COOKIE['p'.$p['parent']]) && $_COOKIE['p'.$p['parent']]) ? ' style="display: block; "' : '';

	return @sprintf($html, $p['parent'],$ULpage_listClass.$ULblockStyle);
}
/*** END function draw_pagetree() *************************************/
?>

<div id="modification-check" style="display: none;">IN_TEMPLATE</div>

<!--<div class="jsadmin"></div>-->

<h2 id="heading-page-list">
	<?php echo $MENU['PAGES'];?>
	<div class="headline-link">
		<a href="#" title="Seite hinzufügen" onclick="$('html,body').animate({ scrollTop: 9999 }, 'slow'); document.add.title.focus();">
			<i class="fa fa-file-text-o fa-lg"></i>
		</a>
	</div>
</h2>

<div class="pages-list fg12 content-box" >
	<table id="page-list-header" <?php if (empty($pages_list)) : ?> style="display:none;"<?php endif;?> >
			<tr>
				<td class="header-status">
					Status
				</td>
				<td class="header-menu-title">
					<?php echo $TEXT['MENU_TITLE']?>
				</td>
				<td class="header-page-title">
					<?php echo $TEXT['PAGE_TITLE']?>
				</td>
				<td class="header-page-id">
					PageID
				</td>
				<td class="header-modifications">
					<?php echo $TEXT['MODIFY']?>
				</td>
				<td class="header-move-up"></td>
				<td class="header-move-down"></td>
				<td class="header-actions">
					<?php echo $TEXT['ACTIONS']?>
				</td>
			</tr>
	</table>

	<?php if(!empty($pages_list)) : ?>
		<?php echo draw_pagetree($pages_list); ?>
	<?php else : ?>
		<div class="no-page-found">(<?php echo $TEXT['NONE_FOUND']?>)</div>
	<?php endif; ?>

	<div class="pages-legend">
		<b><?php echo $TEXT['VISIBILITY']?> (<?php echo $MENU['PAGES']?>): </b>

		<?php foreach ($statusMap as $icon => $fa) : ?>
			&nbsp;<i class="fa fa-lg <?php echo $fa?>"></i>
			&nbsp;<?php echo ucfirst($TEXT[strtoupper($icon)])?>
		<?php endforeach; ?>

		<?php
		if(isset($use_dragdrop_switch) && $use_dragdrop_switch == TRUE){
			$query_order_pages = "SELECT `value` FROM `".TABLE_PREFIX."mod_jsadmin` WHERE `name` = 'mod_jsadmin_ajax_order_pages'";
			$set_dd = $database->get_one($query_order_pages);
			if($set_dd == 0){
				$set_dd = 1;
				$TXT_ENABLE = $TEXT['DISABLED'];
			}elseif($set_dd == 1){
				$set_dd = 0;
				$TXT_ENABLE = $TEXT['ACTIVE'];
			}
		?>

		<span style="float:right;">
			<button style="width:140px;" title="Drag&amp;Drop" onclick="window.location='<?php echo(ADMIN_URL); ?>/pages/page_tree/activate_dragdrop.php?<?php echo '&dd='.$set_dd; ?>';">
				<img src="{THEME_ICONS}/drag.gif" alt="" border="0" /> <?php echo $TXT_ENABLE; ?>
			</button>
		</span>
		<?php } ?>
		<span style="float:right;">&nbsp;<?php echo $MENU['PAGES']?> total: <?php echo $number_all_pages?></span>
	</div>
</div>

