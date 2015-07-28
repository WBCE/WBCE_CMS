<?php
/*
 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2010, Ryan Djurovich

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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/sitemap/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/sitemap/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/sitemap/languages/'.LANGUAGE.'.php');
	}
}

// Get page content
$query = "SELECT header,sitemaploop,footer,level_header,level_footer,static,startatroot,depth,show_hidden FROM ".TABLE_PREFIX."mod_sitemap WHERE section_id = '$section_id'";
$get_settings = $database->query($query);
$settings = $get_settings->fetchRow();

$startatroot = $settings['startatroot'];
$show_hidden = $settings['show_hidden'];
// determine sitemap type
$selectedtxt = 'selected="selected"';
$rootselected = '';
$childrenselected = '';
$peerselected = '';

if($startatroot == '1') {
	$rootselected = $selectedtxt;
} else if($startatroot == '2') {
	$childrenselected = $selectedtxt;
} else {
	$peerselected = $selectedtxt;
}

if($show_hidden==1) {$show_hidden='checked';}
else {$show_hidden='';}

?>
<form name="edit" action="<?php echo WB_URL; ?>/modules/sitemap/save.php" method="post" style="margin: 0;">

	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
	<input type="hidden" name="static" value="<?php echo $settings['static']; ?>">

	<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2">
<input type="checkbox" name="show_hidden" value="1" <?php echo $show_hidden;?> ><?php echo $SMTEXT['HIDDEN_CHILDREN_CP']; ?><br>
			</td>
		</tr>	
		<tr>
			<td colspan="2"><strong><?php echo $SMTEXT['MSETTINGS']; ?></strong> (<a href="<?php echo WB_URL; ?>/modules/sitemap/help.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>"><?php echo $MENU['HELP']; ?></a>)</td>
		</tr>		
		<tr>
			<td width="30%" valign="top"><?php echo $SMTEXT['DISPLAY']; ?>:</td>
			<td>
				<select name="startatroot" style="width: 98%;">
					<option value="1" <?php echo $rootselected; ?>><?php echo $SMTEXT['WHOLESITE']; ?></option>
					<option value="2" <?php echo $childrenselected; ?>><?php echo $SMTEXT['CHILDREN_CP']; ?></option>
					<option value="3" <?php echo $peerselected; ?>><?php echo $SMTEXT['PEERS_CP']; ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $SMTEXT['PAGE_LEVEL_LIMIT']; ?>:</td>
			<td><input name="depth" value="<?php echo $settings['depth']; ?>"></td>
		</tr>
	</table>
	
	<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 3px;">
		<tr>
			<td colspan="2"><strong><?php echo $SMTEXT['LSETTINGS']; ?></strong></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $SMTEXT['HEADER']; ?>:</td>
			<td><textarea name="header" style="width: 98%; height: 80px;"><?php echo stripslashes(htmlspecialchars($settings['header'])); ?></textarea></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $SMTEXT['LHEADER']; ?>:</td>
			<td><textarea name="level_header" style="width: 98%; height: 80px;"><?php echo stripslashes(htmlspecialchars($settings['level_header'])); ?></textarea></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $SMTEXT['LLOOP']; ?>:</td>
			<td><textarea name="sitemaploop" style="width: 98%; height: 80px;"><?php echo stripslashes(htmlspecialchars($settings['sitemaploop'])); ?></textarea></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $SMTEXT['LFOOTER']; ?>:</td>
			<td><textarea name="level_footer" style="width: 98%; height: 80px;"><?php echo stripslashes(htmlspecialchars($settings['level_footer'])); ?></textarea></td>
		</tr>
		<tr>
			<td width="30%" valign="top"><?php echo $TEXT['FOOTER']; ?>:</td>
			<td><textarea name="footer" style="width: 98%; height: 80px;"><?php echo stripslashes(htmlspecialchars($settings['footer'])); ?></textarea></td>
		</tr>

	</table>
	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td align="left">
				<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"></form>
			</td>
			<td align="right">
				<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/index.php'; return false;" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>

</form>