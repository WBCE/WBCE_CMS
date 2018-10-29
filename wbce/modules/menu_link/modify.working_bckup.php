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

// Prevent this file from being access directly
defined('WB_PATH') or die('Cannot access this file directly');

// Load Language Array
if (LANGUAGE_LOADED) {
	include __DIR__ .'/languages/EN.php';
	if (LANGUAGE != 'EN'){
		$sLangFile = __DIR__.'/languages/'.LANGUAGE.'.php';
		if (is_readable($sLangFile)) include $sLangFile;
	}
}

include __DIR__ .'/functions.pageTree.php';
include __DIR__ .'/wb_dump.function.php';

// get target page_id
$sql_result = $database->query("SELECT * FROM `{TP}mod_menu_link` WHERE `section_id` = ".$section_id);
$aData      = $sql_result->fetchRow(MYSQL_ASSOC);

// Get list of all visible pages and build a page-tree
// get list of all page_ids and page_titles
global $aMenulinkTitles;
$aMenulinkTitles = array();
if ($query_page = $database->query("SELECT `page_id`, `menu_title` FROM `{TP}pages`")) {
	while($page = $query_page->fetchRow(MYSQL_ASSOC))
		$aMenulinkTitles[$page['page_id']] = $page['menu_title'];
}

// Get list of targets
$aTargets = array();
$aLinks = pageTreeCombobox(nestedPagesArray(), $page_id);
#wb_dump($aLinks);
foreach($aLinks as $p) {
	if ($query_section = $database->query("SELECT `section_id`, `namesection` FROM `{TP}sections` WHERE `page_id` = ".$p['page_id']." ORDER BY `position`")) {
		while($section = $query_section->fetchRow(MYSQL_ASSOC)) {
			// get section-anchor
			if (defined('SEC_ANCHOR') && SEC_ANCHOR != '') {
				if (isset($section['namesection'])) {
					$aTargets[$p['page_id']][] = "[#".SEC_ANCHOR.$section['section_id']."]  ".$section['namesection'];
					continue;
				}
				$aTargets[$p['page_id']][] = '[#'.SEC_ANCHOR.$section['section_id'].'] ';
			} else {
				$aTargets[$p['page_id']] = array();
			}			
		}
	}
}

?>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
	function populate() {
		o = document.getElementById('menu_link');
		d = document.getElementById('page_target');
		e = document.getElementById('extern');
		if (!d){
			return;
		}
		var mitems = new Array();
		mitems['0']  = [' ','0'];
		//mitems['-1'] = [' ','0'];
<?php foreach($aLinks as $p) {
			$sToJS  = "\t\tmitems['{$p['page_id']}']  = [";
			$sToJS .= "' ',";
			$sToJS .= "'0',";
			if (is_array($aTargets) && is_array($aTargets[$p['page_id']])) {
				foreach($aTargets[$p['page_id']] as $value) {
					$aTmp1 = explode('[#'.SEC_ANCHOR, $value); 
					$aTmp2 = explode(']', $aTmp1[1]); 
					$sAnchor = SEC_ANCHOR.$aTmp2[0];
					$sToJS .= "'".addslashes($value)."',";
					$sToJS .= "'$sAnchor',";
				}
				$sToJS  = rtrim($sToJS, ',');
				$sToJS .= "];".PHP_EOL;
			}
			echo $sToJS;
		}
		?>
		d.options.length = 0;
		cur = mitems[o.options[o.selectedIndex].value];
		if (!cur){
			return;
		}
		
		d.options.length = cur.length / 2;
		
		j = 0;
		for (var i=0; i < cur.length; i=i+2) {
			d.options[j].text    = cur[i];
			d.options[j++].value = cur[i+1];
		}
		

		if (o.value == '-1') {
			e.disabled = false;
		} else {
			e.disabled = true;
		}
	}
	
	$(function() {
		$('#page_link_selection').hide(); 
		$('#sec_anchor').hide(); 
		$('#external').hide(); 
		$('input:radio[name="linktype"]').change(function(){
			if ($(this).is(':checked') && $(this).val() == 'int') {
				$('#sec_anchor').show(); 
				$('#page_link_selection').show(); 
				$('#external').hide(); 
				$('#extern').prop('disabled', false);
			} else {
				$('#sec_anchor').hide(); 
				$('#page_link_selection').hide(); 
				$('#external').show(); 
				$('#extern').prop('disabled', false);
			} 
		});
	
		$('input:radio[name="linktype"]').trigger('change');
	});
/*]]>*/
</script>
<form name="menulink" action="<?=WB_URL ?>/modules/menu_link/save.php" method="post">
	<input type="hidden" name="page_id" value="<?=$page_id ?>" />
	<input type="hidden" name="section_id" value="<?=$section_id ?>" />
	<?=$admin->getFTAN(); ?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="160"><?=$TEXT['LINK'].'-'.$TEXT['TYPE'] ?>:</td>
			<td>
				<input id="external_link" type="radio" name="linktype" value="ext" <?=($aData['target_page_id'] == '-1') ? 'checked' : ''?> /><label for="external_link"><?=$MOD_MENU_LINK['EXTERNAL_LINK']; ?></label>
				<input id="internal_link" type="radio" name="linktype" value="int" <?=($aData['target_page_id'] == '-1') ? '' : 'checked'?> /><label for="internal_link"><?=$MOD_MENU_LINK['INTERNAL_LINK']; ?></label>
			</td>
		</tr>
		<tr id="page_link_selection">
			<td width="160"><?=$TEXT['LINK'] ?>:</td>
			<td><?php
					$sel = ' selected="selected"'; 
				?>
				<select  style="font-weight:bold;font-size:15px; min-width:350px;" name="menu_link" id="menu_link" onchange="populate()" style="width:250px;" >			
					<option value="0"<?= $aData['target_page_id'] == '0'  ? $sel : ''?>><?=$TEXT['PLEASE_SELECT']; ?> &hellip;</option>
					<?php
					foreach ($aLinks as $p){ 
					?>
							<option style="font-size:15px" value="<?=$p['page_id']?>" title="<?=$p['page_title'] ?>"
							<?=($p['page_id'] == $aData['target_page_id']) ? ' selected' : ''?>
							<?=($p['page_id'] == $page_id) ? ' disabled' : ''?>	data-right="<?=$p['page_id']?>"						
						><?=$p['menu_title']?></option>
					<?php 
					}					
					?>
				</select>
				&nbsp;
			</td>
		</tr>
		<tr id="external">
			<td>URL:</td>
			<td>
				<input type="text" name="extern" id="extern" value="<?=$aData['extern']; ?>" style="width:80%;" <?php if ($aData['target_page_id'] != '-1') echo 'disabled="disabled"'; ?> />
			</td>
		</tr>
		<tr id="sec_anchor">
			<td><?=$TEXT['ANCHOR'] ?>:</td>
			<td>
				<select name="anchor" id="page_target" onfocus="populate()" style="width:350px;" >
					<?php 
						$sAnchor = $aData['anchor'] == '0' ? ' ':'[#'.$aData['anchor'].']';
						if (strpos($aData['anchor'], SEC_ANCHOR) !== false){
							$aTmp1 = explode(SEC_ANCHOR, $aData['anchor']); 
							$iSectionID = $aTmp1[1]; 
							if ($sNameSection = $database->get_one("SELECT `namesection` FROM `{TP}sections` WHERE `section_id`=".$iSectionID)){						
								$sAnchor = $sAnchor.' '.$sNameSection;
							}
						}
					?>
					<option value="<?=$aData['anchor'] ?>" selected="selected"><?=$sAnchor ?></option>
				</select>
			</td>
		</tr>
		<?php
			// get target-window for actual page
			$sTarget = $database->get_one("SELECT `target` FROM `{TP}pages` WHERE `page_id` = '$page_id'");
		?>
		<tr>
			<td><?=$TEXT['TARGET'] ?>:</td>
			<td>
				<select name="target" style="width:350px;" >
					<option value="_blank"<?php if ($sTarget == '_blank') echo ' selected="selected"'; ?>><?=$TEXT['NEW_WINDOW'] ?> (_blank)</option>
					<option value="_self"<?php  if ($sTarget == '_self')  echo ' selected="selected"'; ?>><?=$TEXT['SAME_WINDOW'] ?> (_self)</option>
					<option value="_top"<?php   if ($sTarget == '_top')   echo ' selected="selected"'; ?>><?=$TEXT['TOP_FRAME'] ?> (_top)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?=$MOD_MENU_LINK['R_TYPE'] ?>:</td>
			<td>
				<select name="r_type" style="width:350px;" >
					<option value="301"<?php if ($aData['redirect_type'] == '301') echo ' selected="selected"'; ?>>301</option>
					<option value="302"<?php if ($aData['redirect_type'] == '302') echo ' selected="selected"'; ?>>302</option>
				</select>
			</td>
		</tr>
	</table>
	<br />
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="left">
				<input type="submit" value="<?=$TEXT['SAVE'] ?>" style="width: 100px; margin-top: 5px;" />
			</td>
			<td align="right">
				<input type="reset" value="<?=$TEXT['RESET'] ?>" style="width: 100px; margin-top: 5px;" />
				<input type="button" value="<?=$TEXT['CANCEL'] ?>" onclick="javascript: window.location = 'index.php';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
</form>