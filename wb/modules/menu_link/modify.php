<?php
/**
 *
 * @category        modules
 * @package         menu_link
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: modify.php 1537 2011-12-10 11:04:33Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/menu_link/modify.php $
 * @lastmodified    $Date: 2011-12-10 12:04:33 +0100 (Sa, 10. Dez 2011) $
 *
*/

// Must include code to stop this file being access directly
/* -------------------------------------------------------- */
if(defined('WB_PATH') == false)
{
	// Stop this file being access directly
		die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/menu_link/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/menu_link/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/menu_link/languages/'.LANGUAGE .'.php');
}

// get target page_id
$table = TABLE_PREFIX.'mod_menu_link';
$sql_result = $database->query("SELECT * FROM $table WHERE section_id = '$section_id'");
$sql_row = $sql_result->fetchRow();
$target_page_id = $sql_row['target_page_id'];
$r_type = $sql_row['redirect_type'];
$extern = $sql_row['extern'];
$anchor = $sql_row['anchor'];
$sel = ' selected="selected"';

// Get list of all visible pages and build a page-tree

// this function will fetch the page_tree, recursive
if(!function_exists('menulink_make_tree')) {
function menulink_make_tree($parent, $link_pid, $tree) {
	global $database, $admin, $menulink_titles;
	$table_p = TABLE_PREFIX."pages";
	// get list of page-trails, recursive
	if($query_page = $database->query("SELECT * FROM `$table_p` WHERE `parent`=$parent ORDER BY `position`")) {
		while($page = $query_page->fetchRow()) {
			if($admin->page_is_visible($page) ) {
				$pids = explode(',', $page['page_trail']);
				$entry = '';
				foreach($pids as $pid)
					$entry .= $menulink_titles[$pid].' / ';
				$tree[$page['page_id']] = rtrim($entry, '/ ');
				$tree = menulink_make_tree($page['page_id'], $link_pid, $tree);
			}
		}
	}
	return($tree);
}
}

// get list of all page_ids and page_titles
global $menulink_titles;
$menulink_titles = array();
$table_p = TABLE_PREFIX."pages";
if($query_page = $database->query("SELECT `page_id`,`menu_title` FROM `$table_p`")) {
	while($page = $query_page->fetchRow())
		$menulink_titles[$page['page_id']] = $page['menu_title'];
}
// now get the tree
$links = array();
$links = menulink_make_tree(0, $page_id, $links);

// Get list of targets (id=... or <a name ...>) from pages in $links
$targets = array();
$table_mw = TABLE_PREFIX."mod_wysiwyg";
$table_s = TABLE_PREFIX."sections";
foreach($links as $pid=>$l) {
	if($query_section = $database->query("SELECT section_id, module FROM $table_s WHERE page_id = '$pid' ORDER BY position")) {
		while($section = $query_section->fetchRow()) {
			// get section-anchor
			if(defined('SEC_ANCHOR') && SEC_ANCHOR!='') {
				$targets[$pid][] = SEC_ANCHOR.$section['section_id'];
			} else {
				$targets[$pid] = array();
			}
			if($section['module'] == 'wysiwyg') {
				if($query_page = $database->query("SELECT content FROM $table_mw WHERE section_id = '{$section['section_id']}' LIMIT 1")) {
					$page = $query_page->fetchRow();
					if(preg_match_all('/<(?:a[^>]+name|[^>]+id)\s*=\s*"([^"]+)"/i',$page['content'], $match)) {
						foreach($match[1] AS $t) {
							$targets[$pid][$t] = $t;
						}
					}
				}
			}
		}
	}
}
// get target-window for actual page
$table = TABLE_PREFIX."pages";
$query_page = $database->query("SELECT target FROM $table WHERE page_id = '$page_id'");
$page = $query_page->fetchRow();
$target = $page['target'];


// script for target-select-box
?>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
	function populate() {
		o=document.getElementById('menu_link');
		d=document.getElementById('page_target');
		e=document.getElementById('extern');
		if(!d){return;}
		var mitems=new Array();
		mitems['0']=[' ','0'];
		mitems['-1']=[' ','0'];
		<?php
		foreach($links AS $pid=>$link) {
			$str="mitems['$pid']=[";
			$str.="' ',";
			$str.="'0',";
			if(is_array($targets) && is_array($targets[$pid])) {
				foreach($targets[$pid] AS $value) {
					$str.="'#$value',";
					$str.="'$value',";
				}
				$str=rtrim($str, ',');
				$str.="];\n";
			}
			echo $str;
		}
		?>
		d.options.length=0;
		cur=mitems[o.options[o.selectedIndex].value];
		if(!cur){return;}
		d.options.length=cur.length/2;
		j=0;
		for(var i=0;i<cur.length;i=i+2)
		{
			d.options[j].text=cur[i];
			d.options[j++].value=cur[i+1];
		}

		if(o.value=='-1') {
			e.disabled = false;
		} else {
			e.disabled = true;
		}
	}

/*]]>*/
</script>
<form name="menulink" action="<?php echo WB_URL ?>/modules/menu_link/save.php" method="post">
<input type="hidden" name="page_id" value="<?php echo $page_id ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id ?>" />
<?php echo $admin->getFTAN(); ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td>
		<?php echo $TEXT['LINK'].':' ?>
	</td>
	<td>
		<select name="menu_link" id="menu_link" onchange="populate()" style="width:250px;" >
			<option value="0"<?php echo $target_page_id=='0'?$sel:''?>><?php echo $TEXT['PLEASE_SELECT']; ?></option>
			<option value="-1"<?php echo $target_page_id=='-1'?$sel:''?>><?php echo $MOD_MENU_LINK['EXTERNAL_LINK']; ?></option>
			<?php foreach($links AS $pid=>$link) {
				if ($pid == $page_id)  // Display current page with selection disabled
					echo "<option value=\"$pid\" disabled=\"disabled\">$link *</option>\n";
				else
					echo "<option value=\"$pid\" ".($target_page_id==$pid?$sel:'').">$link</option>\n";
			} ?>
		</select>
		&nbsp;
		<input type="text" name="extern" id="extern" value="<?php echo $extern; ?>" style="width:250px;" <?php if($target_page_id!='-1') echo 'disabled="disabled"'; ?> />
	</td>
</tr>
<tr>
	<td>
		<?php echo $TEXT['ANCHOR'].':' ?>
	</td>
	<td>
		<select name="page_target" id="page_target" onfocus="populate()" style="width:250px;" >
			<option value="<?php echo $anchor ?>" selected="selected"><?php echo $anchor=='0'?' ':'#'.$anchor ?></option>
		</select>
	</td>
</tr>
<tr>
	<td>
		<?php echo $TEXT['TARGET'].':' ?>
	</td>
	<td>
		<select name="target" style="width:250px;" >
			<option value="_blank"<?php if($target=='_blank') echo ' selected="selected"'; ?>><?php echo $TEXT['NEW_WINDOW'] ?></option>
			<option value="_self"<?php if($target=='_self') echo ' selected="selected"'; ?>><?php echo $TEXT['SAME_WINDOW'] ?></option>
			<option value="_top"<?php if($target=='_top') echo ' selected="selected"'; ?>><?php echo $TEXT['TOP_FRAME'] ?></option>
		</select>
	</td>
</tr>
<tr>
	<td>
		<?php echo $MOD_MENU_LINK['R_TYPE'].':' ?>
	</td>
	<td>
		<select name="r_type" style="width:250px;" >
			<option value="301"<?php if($r_type=='301') echo ' selected="selected"'; ?>>301</option>
			<option value="302"<?php if($r_type=='302') echo ' selected="selected"'; ?>>302</option>
		</select>
	</td>
</tr>
</table>

<br />

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input type="submit" value="<?php echo $TEXT['SAVE'] ?>" style="width: 100px; margin-top: 5px;" />
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL'] ?>" onclick="javascript: window.location = 'index.php';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

</form>
