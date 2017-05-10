<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Christian M. Stefan (Stefek)
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
while($page = $pages->fetchRow()) {
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

    if ($page['parent'] == 0){	
		$pages_list[ $page['page_id'] ] = &$thisref;
	}else{
		$refs[ $page['parent'] ]['children'][ $page['page_id'] ] = &$thisref;
	}
}

/**
 *
 *	function draw_pagetree()
 */
function draw_pagetree($pages_list) {
	global $admin, $database, $use_working_copy, $icons_dir, $TEXT, $HEADING, $MESSAGE;			
	$siblings = count($pages_list);	
	
	$html = "<ul id=\"p%d\" %s>\n";
	foreach ($pages_list as $key=>$p) {
		
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
		if($expandable == true) {
			$plus_minusIcon = 'expand'; $plus_minusAlt = '[+]'; 						
			if (isset($_COOKIE['p'.$p['page_id']]) && $_COOKIE['p'.$p['page_id']] == '1'){
				$plus_minusIcon = 'collapse';	$plus_minusAlt = '[-]';	
			}
			$plus_minusTitle = strtolower($TEXT['EXPAND'].' / '. $TEXT['COLLAPSE']);	
		}					
					
		// Vars to check if we should show specific Options (and Icons) depending on User Permissions
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
		if ($canManageSections == true){ 
			if($query_sections = $database->query('SELECT `publ_start`, `publ_end`,`module` FROM `'.TABLE_PREFIX.'sections` WHERE `page_id` = '.$p['page_id'])){ 
				$sectionICON = "noclock_16.png";
				$sectionsURL = '../pages/sections.php?page_id='.$p['page_id'];
				$menu_link = false;				
				while($row = $query_sections->fetchRow()){
					if($row['module'] == 'menu_link')
						$menu_link = true;	
					if($row['publ_start']!='0' || $row['publ_end']!='0')
						$sectionICON = ($admin->page_is_active($p)) ? "clock_16.png" : "clock_red_16.png";	
				}				
			}
		}	
		
		$placeholders = array(
			'{MENU_TITLE}'	 	=> str_replace("%", "&#037;", $p['menu_title']),
			'{PAGE_TITLE}' 		=> str_replace("%", "&#037;", $p['page_title']),
			'{PAGE_ID}'			=> $p['page_id'],
			'{pageIDKEY}'		=> $admin->getIDKEY($p['page_id']),
			'{PARENT}'			=> $p['parent'],
			'{status_icon}' 	=> '{THEME_ICONS}/'.$p['visibility'].'_16.png',
			'{status_hint}' 	=> $TEXT['VISIBILITY'].': '.$TEXT[strtoupper($p['visibility'])],
			'{section_icon}' 	=> isset($sectionICON) ? $sectionICON : '',
			'{modifyPageURL}' 	=> '../pages/modify.php?page_id='.$p['page_id'],
			'{frontendViewURL}' => $admin->page_link($p['link']),
			'{modifySettingsURL}' => '../pages/settings.php?page_id='.$p['page_id'],
			'{restoreURL}' 		=> '../pages/restore.php?page_id='.$p['page_id'],
			'{THEME_ICONS}'		=> THEME_URL.'/images', // move_up/_down
			'{padding_left}'	=> 'padding-left: '.(($p['level'] > 0) ? $p['level']*28 :'5').'px;',			
			'{padpix}'          => 400-(($p['level'] > 0) ? $p['level']*28 :'5')
		);			
		
/**
 *
 *	set "Template"
 */
ob_start();
?>
	<li class="p{PARENT}">
		<table class="pages_view" cellspacing="0" cellpadding="0" border="0">
			<tr>				
				<td style="width:26px; {padding_left}">
					<?php if($expandable == true): ?>
					<a href="javascript: toggle_visibility('p{PAGE_ID}');" title="<?php echo $plus_minusTitle; ?>"><img src="{THEME_ICONS}/<?php echo $plus_minusIcon; ?>.png" onclick="toggle_plus_minus('{PAGE_ID}');" name="plus_minus_{PAGE_ID}" border="0" alt="<?php echo $plus_minusAlt; ?>" /></a>
					<?php else: ?>
					<img src="{THEME_ICONS}/empty.png" border="0" alt="" />	
					<?php endif; ?>
					&nbsp;
				</td>
				<td class="list_menu_title1" style="width:{padpix}px; overflow:hidden;" >					
					<div style="padding-left:25px; background-image:url({status_icon}); background-repeat:no-repeat; background-position:top left; line-height:20px;">
					
					<a<?php if($canModifyPage) : ?> href="{modifyPageURL}" title="<?php echo $HEADING['MODIFY_PAGE']; ?>"<?php endif; ?>>					
					<b>{MENU_TITLE}</b></a>								
					</div>	
				</td>		
				<td class="list_page_title" >
					<?php if(!$menu_link): ?>
					<small>{PAGE_TITLE}</small>
					<?php endif; ?>
				</td>
				<td class="list_page_id"><sup>{PAGE_ID}</sup></td>				
				<?php
				// check if thorns working copy is installed
				if($use_working_copy):
				?>
				<td width="20"><?php 
				if($canModifyPage) :				
				// check if we should show the wysiwyg_copy-icon
					if($len = $database->get_one("SELECT MAX(LENGTH(working_content)) FROM `".TABLE_PREFIX."mod_wysiwyg` WHERE `page_id` = '{$p['page_id']}' ")):
						if($len !=NULL && $len !=0): ?>
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
				
				<td style="width:64px; text-align:center; padding-right:15px;">
				<div style="background-color:#FDFEFF; border:1px solid #869F6D;margin:1px;padding-bottom:2px;text-align:center; white-space:nowrap;">
					<small>
				<?php if($canModifyPage) : ?>				
					<a href="{modifyPageURL}" title="<?php echo $HEADING['MODIFY_PAGE']; ?>"><img src="{THEME_ICONS}/modify_16.png" border="0" alt="[<?php echo $TEXT['MODIFY']; ?>]" /></a>
				<?php else: ?>
					<img src="{THEME_ICONS}/empty.png" border="0" alt="" />						
				<?php endif; //$canModifyPage
				
				if($p['visibility'] != 'deleted') : ?>
					<?php if($canModifySettings) : ?>
					<a href="{modifySettingsURL}" title="<?php echo $HEADING['MODIFY_PAGE_SETTINGS']; ?>"><img src="{THEME_ICONS}/options_16.png" border="0" alt="[<?php echo $TEXT['SETTINGS']; ?>]" /></a>				 			
					<?php else: ?>
					<img src="{THEME_ICONS}/empty.png" border="0" alt="" />	
					<?php endif; //$canModifySettings?
					elseif($p['visibility'] == 'deleted') :?>			
					<a href="{restoreURL}" title="<?php echo $TEXT['RESTORE']; ?>"><img src="{THEME_ICONS}/restore_16.png" border="0" alt="[<?php echo $TEXT['RESTORE']; ?>]" /></a>
				<?php else: ?>
					<img src="{THEME_ICONS}/empty.png" border="0" alt="" />	
				<?php endif; //$p['visibility'] != 'deleted'
					
					// only show manageSections Link if we have to!
					//menu-link?
					if(isset($menu_link) && $menu_link == true):?>
					<img src="{THEME_ICONS}/menu_link.png" border="0" alt="[menu-link]" title="Menu Link" style="cursor:default;" />
					<?php 					
					elseif ($canManageSections): ?>
					<a href="<?php echo $sectionsURL; ?>" title="<?php echo $HEADING['MANAGE_SECTIONS']; ?>"><img src="{THEME_ICONS}/{section_icon}" border="0" alt="[<?php echo $HEADING['MANAGE_SECTIONS']; ?>]" /></a>				
				<?php else: ?>
					<img src="{THEME_ICONS}/empty.png" border="0" alt="" />	
				<?php endif; ?>
					</small>
				</div>
				</td><td>&nbsp;</td>
				<td class="list_actions">
					<small><nobr>&nbsp;
						<?php if($p['visibility'] != 'deleted' && $p['visibility'] != 'none') : ?>
						<a href="{frontendViewURL}" target="_blank" title="<?php echo $TEXT['VIEW']; ?> (Frontend)"><img src="{THEME_ICONS}/view_16.png" border="0" alt="[<?php echo $TEXT['VIEW']; ?>]" /></a>
						<?php else: ?>
						<img src="{THEME_ICONS}/empty.png" border="0" alt="" />	
						<?php endif; //$p['visibility'] != 'deleted' && $p['visibility'] != 'none' ?>
						&nbsp;&nbsp;</nobr>						
					</small>
				</td>
				<td class="list_actions">&nbsp;
					<?php if($canMoveUP) : ?>
					<a href="../pages/move_up.php?page_id={PAGE_ID}" title="<?php echo $TEXT['MOVE_UP']; ?>"><img src="{THEME_ICONS}/up_16.png" border="0" alt="/\" /></a>			
					<?php endif; //$canMoveUP?>	
				</td>
				<td class="list_actions">
					<?php if($canMoveDOWN) : ?>
					<a href="../pages/move_down.php?page_id={PAGE_ID}" title="<?php echo $TEXT['MOVE_DOWN']; ?>"><img src="{THEME_ICONS}/down_16.png" border="0" alt="\/" /></a>
					<?php endif; //$canMoveDOWN ?>
				</td>
				<td class="list_actions">
					<?php if($canDeleteAndModify) : ?>
					<a href="javascript:confirm_link('PageID: {PAGE_ID}\n\n\t<?php echo $MESSAGE['PAGES_DELETE_CONFIRM']; ?>?','../pages/delete.php?page_id={pageIDKEY}');" title="<?php echo $TEXT['DELETE']; ?>"><img src="{THEME_ICONS}/delete_16.png" border="0" alt="[x]" /></a>
					<?php else: ?>
					<img src="{THEME_ICONS}/empty.png" border="0" alt="" />
					<?php endif; //canDeleteAndModify?>
				</td><td></td>
				<td class="list_actions">
				<?php if($canAddChild) : ?>
					<a href="javascript:add_child_page('{PAGE_ID}');" title="<?php echo $HEADING['ADD_CHILD_PAGE']; ?>"><img src="{THEME_ICONS}/add_child.png" name="addpage_{PAGE_ID}" border="0" alt="[+]" /></a>					
				<?php endif; //$canAddChild ?>
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
			$html .= sprintf('<ul id="p%d" class="page_list"><li>&nbsp;</li></ul>', $p['page_id'])."\n";
		}
	}
	$html .= '</ul>';
	// class="page_list" for all UL's larger than parent=0
	$ULpage_listClass =	($p['parent'] != 0) ? ' class="page_list"' : ''; 
	// display:block depending on cookie (JS Admin Toggle)
	$ULblockStyle 	= 	(isset($_COOKIE['p'.$p['parent']]) && $_COOKIE['p'.$p['parent']]) ? ' style="display: block; "' : ''; 	

	return @sprintf($html, $p['parent'],$ULpage_listClass.$ULblockStyle);
}
?>
<div class="jsadmin"></div>
<h2><?php echo $MENU['PAGES'];?></h2>
<div class="pages_list" style="margin: 4px; padding: 4px; border: solid 2px #d5dde7 !important">
	<table cellspacing="0" cellpadding="0" border="0" <?php if(empty($pages_list)):?>style="display:none;"<?php endif;?>>
	    <tbody>
			<tr>
				<td style="width:330px;padding:3px"><small><?php echo $TEXT['VISIBILITY'];?></small> / <?php echo $TEXT['MENU_TITLE'];?>:</td>
				<td style="text-align:left;width:330px;"><tt> &lt;title&gt;</tt> (<?php echo $TEXT['PAGE_TITLE'];?>):</td>
				<td class="header_list_page_id" style="text-align:left;"><small>PageID:</small></td>		
				<td class=""><?php echo $TEXT['MODIFY'];?>:</td>
				<td class=""><?php echo $TEXT['ACTIONS'];?>:</td>				
			</tr>
		</tbody>
	</table>
<?php 
if(!empty($pages_list))
	echo draw_pagetree($pages_list); 
else 
	echo '<div style="background-color:#E6F4D9; border:1px solid;margin:4px;padding:4px;text-align:center;">('.$TEXT['NONE_FOUND'].')</div>';
?>
<span style="float:right;"><?php echo $MENU['PAGES'];?>: <?php echo $number_all_pages; ?>&nbsp;</span>
<?php $visibility_legend = array('public','hidden','registered','private','none','deleted');
	
?>
<div style="border-top: solid 2px #D5DDE7;margin-top:20px;padding:4px;" class="pages_legend">
<strong><?php echo $TEXT['VISIBILITY'] ?> (<?php echo $MENU['PAGES'];?>): </strong>
<?php 
	foreach ($visibility_legend as $icon)
		echo '&nbsp;<img src="'.THEME_URL.'/images/'.$icon.'_16.png" alt=""/>&nbsp;'.ucfirst($TEXT[strtoupper($icon)]).'&nbsp;'; 

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
	<button style="width:140px;" title="Drag&amp;Drop" onclick="javascript:window.location='<?php echo(ADMIN_URL); ?>/pages/page_tree/activate_dragdrop.php?<?php echo '&dd='.$set_dd; ?>';">
		<img src="{THEME_ICONS}/drag.gif" alt="" border="0" /> <?php echo $TXT_ENABLE; ?>
	</button>
</span>
<?php 
}
?>
</div>
</div>
