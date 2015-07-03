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

// STEP 1:	Initialize
require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/sitemap/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/sitemap/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/sitemap/languages/'.LANGUAGE.'.php');
	}
}

// STEP 2:	Get actual settings from database
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_sitemap WHERE section_id = '$section_id'");
$settings = $query_settings->fetchRow();

// STEP 3:	Display the help page.
?>

<style type="text/css">
.setting_name {
	vertical-align: top;
}

.row_a a {
	 border-bottom: 1px dashed #000;
}

.row_a a:hover {
	 border-bottom: 1px solid #000;
}
</style>
<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
	<tr>
		<td class="setting_name">
			<h3>Help about Sitemap Module</h3>
			
			<p>This file contains help about the sitemap module and how the different fields within the module can be used.</p>
			
			<p>The help is divided in 3 sections: <a href="#main_settings"><?php echo $SMTEXT['MSETTINGS']; ?></a>, <a href="#layout_settings"><?php echo $SMTEXT['LSETTINGS']; ?></a> and the <a href="#changelog">changelog</a>. All this information is described down below.</p>
			
			<a name="main_settings"></a><h4><?php echo $SMTEXT['MSETTINGS']; ?></h4>
			<p>This section contains the required fields that are neccasery to get this module to work.</p>
			<ul>
				<li><code><?php echo $SMTEXT['DISPLAY']; ?></code> With this option you can specify of which part of the website you would like the create a sitemap, the options ares:
					<ul>
						<li><code><?php echo $SMTEXT['WHOLESITE']; ?></code> - This is the default option. When selecting this option all the visible pages will be rendered into the sitemap.</li>
						<li><code><?php echo $SMTEXT['CHILDREN_CP']; ?></code> - As mentioned in the option, this option will only show children (if any) of the current page.</li>
						<li><code><?php echo $SMTEXT['PEERS_CP']; ?></code> - This option means that all the peers (pages at same level and below) will be displayed based upon the current page.</li>
					</ul>
				</li>
				<li><code><?php echo $SMTEXT['PAGE_LEVEL_LIMIT']; ?></code> This fields specifies the maximum depth of the sitemap that should be shown.</li>
			</ul>
				
			<a name="layout_settings"></a><h4><?php echo $SMTEXT['LSETTINGS']; ?></h4>
			<p>With the options down below you can modify the layout of the different sections for the sitemap module.</p>
			<ul>
				<li><code><?php echo $SMTEXT['HEADER']; ?></code> This field is used to render it at the beginning of a list. You can use this e.g. for working with a <code>&lt;div&gt;</code> or with specific stylesheets definitions.</li>
				<li><code><?php echo $SMTEXT['LHEADER']; ?></code> This field is used to render it at the beginning of a sitemap level. You can use this e.g. for working with a <code>&lt;div&gt;</code> or with specific stylesheets definitions. </li>
				<li><code><?php echo $SMTEXT['LLOOP']; ?></code> This template is used to display the different levels that are available for the sitemap. In order to modify this layout you can use the following tags:
					<ul>
						<li><code>[PAGE_ID]</code> - The page id of the specific page in the sitemap.</li>
						<li><code>[PARENT]</code>  - The parent of the page that is currently in the loop.</li>
						<li><code>[LINK]</code> - The link to the specific page within the sitemap.</li>
						<li><code>[PAGE_TITLE]</code> - The title of the page within the sitemap.</li>
						<li><code>[MENU_TITLE]</code> - The title of the page as it is shown witin the menu.</li>
						<li><code>[DESCRIPTION]</code> - The description of the page within the sitemap.</li>
						<li><code>[KEYWORDS]</code> - The keywords that are given for the current page within the sitemap.</li>
						<li><code>[TARGET]</code> - The target to which the page should be opened.</li>
						<li><code>[MODIFIED_WHEN]</code> - The date and time when the page has been last modified.</li>
						<li><code>[MODIFIED_DATE]</code> - The date when the page has been last modified.</li>
						<li><code>[MODIFIED_TIME]</code> - The time when the page has been last modified.</li>
						<li><code>[MODIFIED_BY]</code> - The person who made the last modification to the specific page.</li>
					</ul>
				</li>
				<li><code><?php echo $SMTEXT['LFOOTER']; ?></code> This is used to display the footer for a level. Mostly this is only used for e.g. closing a <code>&lt;/div&gt;</code>.</li>
				<li><code><?php echo $SMTEXT['FOOTER']; ?></code> This field is used to render it at the end of the sitemap. You can use this e.g. for working with a <code>&lt;div&gt;</code> tag.</li>
			</ul>
			
			<a name="changelog"></a><h4>Changelog</h4>
      <p>Change log has been moved to info.php.</p>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">	
	<tr>
		<td>
			<input type="button" value="<?php echo $TEXT['BACK']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>	
</table>