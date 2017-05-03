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
	// Load EN always as backup for non translated keys
	require_once WB_PATH.'/modules/sitemap/languages/EN.php'; 
	if(LANGUAGE != 'EN'){
		$sLang = __DIR__.'/languages/'.LANGUAGE.'.php';	
		if(file_exists($sLang)) require_once $sLang;
	}
}

// STEP 2:	Get actual settings from database
$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_sitemap WHERE section_id = '$section_id'");
$settings = $query_settings->fetchRow();

// STEP 3:	Display the help page.
?>
<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
	<tr>
		<td class="setting_name">
			<h2>Help about Sitemap Module</h2>
			
			<p>This file contains help about the sitemap module and how the different fields within the module can be used.</p>
			
			<p>The help is divided in 3 sections: <a href="#main_settings"><?php echo $SMTEXT['MSETTINGS']; ?></a>, <a href="#layout_settings"><?php echo $SMTEXT['LSETTINGS']; ?></a> and the <a href="#changelog">changelog</a>. All this information is described down below.</p>
			
			<a name="main_settings"></a><h2><?php echo $SMTEXT['MSETTINGS']; ?></h2>
			<p>This section contains the required fields that are neccasery to get this module to work.</p>
			<ul>
				<li><b><?php echo $SMTEXT['DISPLAY']; ?></b> With this option you can specify of which part of the website you would like the create a sitemap, the options ares:
					<ul>
						<li><b><?php echo $SMTEXT['WHOLESITE']; ?></b> - This is the default option. When selecting this option all the visible pages will be rendered into the sitemap.</li>
						<li><b><?php echo $SMTEXT['CHILDREN_CP']; ?></b> - As mentioned in the option, this option will only show children (if any) of the current page.</li>
						<li><b><?php echo $SMTEXT['PEERS_CP']; ?></b> - This option means that all the peers (pages at same level and below) will be displayed based upon the current page.</li>
					</ul>
				</li>
				<li><b><?php echo $SMTEXT['PAGE_LEVEL_LIMIT']; ?></b> This fields specifies the maximum depth of the sitemap that should be shown.</li>
			</ul>
				
			<a name="layout_settings"></a><h2><?php echo $SMTEXT['LSETTINGS']; ?></h2>
			<p>With the options down below you can modify the layout of the different sections for the sitemap module.</p>
			<ul>
				<li><b><?php echo $SMTEXT['HEADER']; ?></b> This field is used to render it at the beginning of a list. You can use this e.g. for working with a <kbd>&lt;div&gt;</kbd> or with specific stylesheets definitions.</li>
				<li><b><?php echo $SMTEXT['LHEADER']; ?></b> This field is used to render it at the beginning of a sitemap level. You can use this e.g. for working with a <kbd>&lt;div&gt;</kbd> or with specific stylesheets definitions. </li>
				<li><b><?php echo $SMTEXT['LLOOP']; ?></b> This template is used to display the different levels that are available for the sitemap.
				<br><b>In order to modify this layout you can use the following tags:</b>
					<ul>
						<li><kbd>[PAGE_ID]</kbd> or <kbd>[ID]</kbd> - The page id of the specific page in the sitemap.</li>
						<li><kbd>[PARENT]</kbd>  - The parent of the page that is currently in the loop.</li>
						<li><kbd>[LEVEL]</kbd>  - <i>(New since v4.0.0)</i> The Level of the page in the SiteMap. </li>
						<li><kbd>[LINK]</kbd> - The link to the specific page within the sitemap.</li>
						<li><kbd>[PAGE_TITLE]</kbd> - The title of the page within the sitemap.</li>
						<li><kbd>[MENU_TITLE]</kbd> - The title of the page as it is shown witin the menu.</li>
						<li><kbd>[DESCRIPTION]</kbd> - The description of the page within the sitemap.</li>
						<li><kbd>[KEYWORDS]</kbd> - The keywords that are given for the current page within the sitemap.</li>
						<li><kbd>[TARGET]</kbd> - The target to which the page should be opened.<code> (target="[TARGET]")</code></li>
						<li><kbd>[MODIFIED_WHEN]</kbd> - The date and time when the page has been last modified.</li>
						<li><kbd>[MODIFIED_DATE]</kbd> - The date when the page has been last modified.</li>
						<li><kbd>[MODIFIED_TIME]</kbd> - The time when the page has been last modified.</li>
						<li><kbd>[MODIFIED_BY]</kbd> - The person who made the last modification to the specific page.</li>
					</ul>
				</li>
				<li><kbd><?php echo $SMTEXT['LFOOTER']; ?></kbd> This is used to display the footer for a level. Mostly this is only used for e.g. closing a <kbd>&lt;/div&gt;</kbd>.</li>
				<li><kbd><?php echo $SMTEXT['FOOTER']; ?></kbd> This field is used to render it at the end of the sitemap. You can use this e.g. for working with a <kbd>&lt;div&gt;</kbd> tag.</li>
			</ul>
			
			
			
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
<?php 
	$sChangelog = "";
	$sTag = "changelog";
	$sFileContent = file_get_contents(utf8_decode(dirname(__FILE__)."/info.php"));
	if( preg_match_all("/\[$sTag\](.*?)\[\/$sTag\]/ism", $sFileContent, $match)){
		$sChangelog = $match[1][0];
	}
	echo '<a name="changelog"></a><br><h2>Changelog</h2>';
	echo '<pre class="code" style="font-family: monospace">';
	echo $sChangelog;
	echo '</pre>';
?>
<?php 


$admin->print_footer();