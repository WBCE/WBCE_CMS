<?php
/**
 *
 * @category        admin
 * @package         pages
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: trash.php 1457 2011-06-25 17:18:50Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/pages/trash.php $
 * @lastmodified    $Date: 2011-06-25 19:18:50 +0200 (Sa, 25. Jun 2011) $
 *
 */

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages');

?>
<script type="text/javascript" language="javascript">
function toggle_viewers() {
	if(document.add.visibility.value == 'private') {
		document.getElementById('private_viewers').style.display = 'block';
		document.getElementById('registered_viewers').style.display = 'none';
	} else if(document.add.visibility.value == 'registered') {
		document.getElementById('private_viewers').style.display = 'none';
		document.getElementById('registered_viewers').style.display = 'block';
	} else {
		document.getElementById('private_viewers').style.display = 'none';
		document.getElementById('registered_viewers').style.display = 'none';
	}
}
function toggle_visibility(id){
	if(document.getElementById(id).style.display == "block") {
		document.getElementById(id).style.display = "none";
	} else {
		document.getElementById(id).style.display = "block";
	}
}
var plus = new Image;
plus.src = "<?php echo THEME_URL; ?>/images/plus_16.png";
var minus = new Image;
minus.src = "<?php echo THEME_URL; ?>/images/minus_16.png";
function toggle_plus_minus(id) {
	var img_src = document.images['plus_minus_' + id].src;
	if(img_src == plus.src) {
		document.images['plus_minus_' + id].src = minus.src;
	} else {
		document.images['plus_minus_' + id].src = plus.src;
	}
}
</script>

<?php

function make_list($parent, $editable_pages) {
	// Get objects and vars from outside this function
	global $admin, $template, $database, $TEXT, $MESSAGE;
	?>
	<ul id="p<?php echo $parent; ?>" <?php if($parent != 0) { echo 'class="page_list"'; } ?>>
	<?php	
	// Get page list from database
	// $database = new database();
	$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' AND visibility = 'deleted' ORDER BY position ASC";
	$get_pages = $database->query($query);
	
	// Insert values into main page list
	if($get_pages->numRows() > 0)	{
		while($page = $get_pages->fetchRow()) {
			// Get user perms
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));
			$in_old_group = FALSE;
			foreach($admin->get_groups_id() as $cur_gid){
			    if (in_array($cur_gid, $old_admin_groups)) {
				$in_old_group = TRUE;
			    }
			}
			if((!$in_old_group) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
				if($page['visibility'] == 'deleted') {
					$can_modify = true;
					$editable_pages = $editable_pages+1;
				} else {
					$can_modify = false;
				}
			} else {
				$can_modify = false;
			}
						
			// Work out if we should show a plus or not
			$get_page_subs = $database->query("SELECT page_id,admin_groups,admin_users FROM ".TABLE_PREFIX."pages WHERE parent = '".$page['page_id']."'");
			if($get_page_subs->numRows() > 0) {
				$display_plus = true;
			} else {
				$display_plus = false;
			}
			
			// Work out how many pages there are for this parent
			$num_pages = $get_pages->numRows();
			?>
			
			<li id="p<?php echo $page['parent']; ?>">
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="20" style="padding-left: <?php echo $page['level']*20; ?>px;">
					<?php
					if($display_plus == true) {
					?>
					<a href="javascript: toggle_visibility('p<?php echo $page['page_id']; ?>');" title="<?php echo $TEXT['EXPAND'].'/'.$TEXT['COLLAPSE']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/plus_16.png" onclick="toggle_plus_minus('<?php echo $page['page_id']; ?>');" name="plus_minus_<?php echo $page['page_id']; ?>" border="0" alt="+" />
					</a>
					<?php
					}
					?>
				</td>
				<?php if($admin->get_permission('pages_modify') == true AND $can_modify == true AND $page['visibility'] != 'heading') { ?>
				<td>
					<a href="<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>"><?php echo ($page['page_title']); ?></a>
				</td>
				<?php } else { ?>
				<td>
					<?php
					if($page['visibility'] != 'heading') {
						echo ($page['page_title']);
					} else {
						echo '<b>'.($page['page_title']).'</b>';
					}
					?>
				</td>
				<?php } ?>
				<td align="left" width="232">
					<font color="#999999"><?php echo $page['menu_title']; ?></font>
				</td>
				<td align="right" valign="middle" width="30" class="icon_col">
				<?php if($page['visibility'] == 'public') { ?>
					<img src="<?php echo THEME_URL; ?>/images/visible_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['PUBLIC']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'private') { ?>
					<img src="<?php echo THEME_URL; ?>/images/private_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['PRIVATE']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'registered') { ?>
					<img src="<?php echo THEME_URL; ?>/images/keys_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['REGISTERED']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'none') { ?>
					<img src="<?php echo THEME_URL; ?>/images/hidden_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['NONE']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'deleted') { ?>
					<img src="<?php echo THEME_URL; ?>/images/deleted_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['DELETED']; ?>" border="0" />
				<?php } ?>
				</td>
				<td width="20">
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true AND $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/settings.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['SETTINGS']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/modify_16.png" border="0" alt="<?php echo $TEXT['SETTINGS']; ?>" />
						</a>
						<?php } ?>
					<?php } else { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/restore.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['RESTORE']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/restore_16.png" border="0" alt="<?php echo $TEXT['RESTORE']; ?>" />
						</a>
					<?php } ?>
				</td>
				<td width="20">
				<?php if($page['position'] != 1) { ?>
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true AND $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/move_up.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/up_16.png" border="0" alt="^" />
						</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</td>
				<td width="20">
				<?php if($page['position'] != $num_pages) { ?>
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true AND $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/move_down.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/down_16.png" border="0" alt="v" />
						</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</td>
				<td width="20">
					<?php if($admin->get_permission('pages_delete') == true AND $can_modify == true) { ?>
					<a href="javascript: confirm_link('<?php echo $MESSAGE['PAGES']['DELETE_CONFIRM']; ?>?', '<?php echo ADMIN_URL; ?>/pages/delete.php?page_id=<?php echo $page['page_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/delete_16.png" border="0" alt="X" />
					</a>
					<?php } ?>
				</td>
			</tr>
			</table>
			</li>
							
			<?php
			// Get subs
			make_list($page['page_id'], $editable_pages);
		}

	}
	?>
	</ul>
	<?php
	return $editable_pages;
}

// Generate pages list
if($admin->get_permission('pages_view') == true) {
	?>
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td>
			<h2><?php echo $HEADING['DELETED_PAGES']; ?></h2>
		</td>
		<td align="right">
				<a href="<?php echo ADMIN_URL; ?>/pages/empty_trash.php">
				<img src="<?php echo THEME_URL; ?>/images/delete_16.png" alt="<?php echo $TEXT['PAGE_TRASH']; ?>" border="0" />
				<?php echo $TEXT['EMPTY_TRASH']; ?></a>
		</td>
	</tr>
	</table>
	<div class="pages_list">
	<table cellpadding="1" cellspacing="0" width="720" border="0">
	<tr>
		<td width="20">
			&nbsp;
		</td>
		<td>
			<?php echo $TEXT['PAGE_TITLE']; ?>:
		</td>
		<td width="198" align="left">
			<?php echo $TEXT['MENU_TITLE']; ?>:
		</td>
		<td width="80" align="center">
			<?php echo $TEXT['VISIBILITY']; ?>:
		</td>
		<td width="90" align="center">
			<?php echo $TEXT['ACTIONS']; ?>:
		</td>		
	</tr>
	</table>
	<?php
	$editable_pages = make_list(0, 0);
	?>
	</div>
	<div class="empty_list">
		<?php echo $TEXT['NONE_FOUND']; ?>
	</div>
	<?php
} else {
	$editable_pages = 0;
}

// Figure out if the no pages found message should be shown or not
if($editable_pages == 0) {
	?>
	<style type="text/css">
	.pages_list {
		display: none;
	}
	</style>
	<?php
} else {
	?>
	<style type="text/css">
	.empty_list {
		display: none;
	}
	</style>
	<?php
}

?>
<br />< <a href="<?php echo ADMIN_URL; ?>/pages/index.php"><?php echo $MESSAGE['PAGES']['RETURN_TO_PAGES']; ?></a>
<?php

// Print admin 
$admin->print_footer();

?>