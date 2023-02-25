<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }


// Load Language file
if(LANGUAGE_LOADED) {
	// Load EN always as backup for non translated keys
	require_once WB_PATH.'/modules/sitemap/languages/EN.php'; 
	if(LANGUAGE != 'EN'){
		$sLang = __DIR__.'/languages/'.LANGUAGE.'.php';	
		if(file_exists($sLang)) require $sLang;
	}
}

$db_table = TABLE_PREFIX."mod_sitemap";

//
//	Toggle Settings View
//
if(isset($_GET['show_settings']) && in_array($_GET['show_settings'], array(1,0))){
	if(isset($_GET['page_id']) && $_GET['page_id'] == $page_id){
		if(isset($_GET['section_id']) && $_GET['section_id'] == $section_id){
			$sQuery = "UPDATE `%s` SET `show_settings` = '%s' WHERE `section_id` = %d";
			$database->query(sprintf($sQuery, $db_table, $_GET['show_settings'], $section_id));
		}
	}
}
$sAcpiCheckfile = WB_URL.'/templates/'.DEFAULT_THEME.'/ACPI_READY';
if(!is_file($sAcpiCheckfile)){
	echo '<!--(MOVE) CSS HEAD TOP- -->';
	echo '<link href="'.WB_URL.'/modules/sitemap/cp_content.css" rel="stylesheet" type="text/css">';
	echo '<!--(END)-->';
}
// Get page content
$query = "SELECT * FROM ".$db_table." WHERE section_id = '".$section_id."'";
$get_settings = $database->query($query);
$settings = $get_settings->fetchRow();

$startatroot  = $settings['startatroot'];
$show_hidden  = $settings['show_hidden'];
$header       = stripslashes(htmlspecialchars($settings['header']));
$level_header = stripslashes(htmlspecialchars($settings['level_header']));
$sitemaploop  = stripslashes(htmlspecialchars($settings['sitemaploop']));
$level_footer = stripslashes(htmlspecialchars($settings['level_footer']));
$footer       = stripslashes(htmlspecialchars($settings['footer']));


// determine sitemap type
$selectedtxt  = 'selected="selected"';
$rootselected = '';
$peerselected = '';
$childrenselected = '';

if($startatroot == '1') {
	$rootselected = $selectedtxt;
} else if($startatroot == '2') {
	$childrenselected = $selectedtxt;
} else {
	$peerselected = $selectedtxt;
}

//
// workout the menus we should include for display
//
$menus = $settings['menus'];
$tmpMenus = array();
$tmpMenus[0] = 0;
if(strpos($menus, ',') !== false){
	$tmpMenus = explode(',', $menus);
}else{ 
	$tmpMenus[0] = $menus;
}
$menus = $tmpMenus;
// create a function to get menus from the DEFAULT_TEMPLATE
if(!function_exists("menus_array")){
	function menus_array(){
		global $TEXT;
		$aMenu = array();		
		$sTplFile = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/info.php';
		if (is_file($sTplFile)) {
			include $sTplFile;	
			if(isset($menu)){
				foreach($menu as $key => $sMenuName) {
					$aMenu[$key]['name']  = '['.$key.'] '.(($sMenuName == '') ? $TEXT['DEFAULT'] : $sMenuName );
					$aMenu[$key]['value'] = $key;
				}
			}
		}			
		return $aMenu;
	}
}

if($show_hidden==1) {$show_hidden='checked';}
else {$show_hidden='';}

?>
<div class="cpForm">
    <div class="formHeading">Sitemap</div>
	<form name="edit" action="<?=WB_URL?>/modules/sitemap/save.php" method="post" style="margin: 0;">
		<input type="hidden" name="page_id" value="<?=$page_id?>">
		<input type="hidden" name="section_id" value="<?=$section_id?>">
		<input type="hidden" name="static" value="<?=$settings['static']?>">
		<?=$admin->getFTAN()?>    
		<div class="formRow">
			<label class="settingName"><strong><?=$SMTEXT['MSETTINGS']?></strong></label>
			<div class="settingValue"><a class="button ico-help" href="<?=WB_URL?>/modules/sitemap/help.php?page_id=<?=$page_id?>&section_id=<?=$section_id?>"><?=$MENU['HELP']?></a></div>
		</div>
		<div class="formRow">
			<label class="settingName"><?=$SMTEXT['HIDDEN_CHILDREN_CP']?></label>
			<div class="settingValue"><input type="checkbox" name="show_hidden" value="1" <?=$show_hidden;?> ></div>
		</div>
		<div class="formRow">
			<label class="settingName"><?=$SMTEXT['DISPLAY']?></label>
			<div class="settingValue">
				<select name="startatroot" style="width: 98%;">
					<option value="1" <?=$rootselected?>><?=$SMTEXT['WHOLESITE']?></option>
					<option value="2" <?=$childrenselected?>><?=$SMTEXT['CHILDREN_CP']?></option>
					<option value="3" <?=$peerselected?>><?=$SMTEXT['PEERS_CP']?></option>
				</select>
			</div>
		</div>
		<div class="formRow">
			<label class="settingName"><?=$SMTEXT['PAGE_LEVEL_LIMIT']?></label>
			<div class="settingValue"><input type="text" name="depth" value="<?=$settings['depth']?>"></div>
		</div>
		<?php 
		//
		// menus 
		//
		$aMenus = menus_array();
		if(MULTIPLE_MENUS && !empty($menus) && count($aMenus) > 0): ?>				
			<div class="formRow">
				<label class="settingName"><?=$SMTEXT['MENUS']?></label>
				<div class="settingValue">
					<input type="checkbox" <?=(in_array(0, $menus) == true ? ' checked="checked"':'');?> name="all_menus" id="all_menus" value="0" /><label for="all_menus"><?=$SMTEXT['ALLMENUS']?></label>
					<?php foreach($aMenus as $rec): ?> 
						<br /><input type="checkbox" name="menus[<?=$rec['value'] ?>]" id="menu_<?=$rec['value'] ?>" value="1"
						<?=(in_array($rec['value'], $menus) == true ? ' checked="checked"':'');?> onClick="uncheck('all_menus');" /><label for="menu_<?=$rec['value'] ?>"><?=$rec['name'] ?></label>												
					<?php endforeach ?>
					<p>&nbsp;</p>
				</div>
			</div>
		<?php endif; ?>
		<div class="formRow">
			<label class="settingName"><strong><?=$SMTEXT['LSETTINGS']?></strong></label>
			<div class="settingValue">
				<?php 
				$TEXT['TOGGLE_VIEW'] = $TEXT['SHOW'];
				$toggle = 1;
				if($settings['show_settings'] == 1){
					$TEXT['TOGGLE_VIEW'] = $TEXT['HIDE'];	
					$toggle = 0;			
				}			
				$sLink = ADMIN_URL."/pages/modify.php?page_id=%d&section_id=%d&show_settings=%d#%s";
				$sLink = sprintf($sLink, $page_id, $section_id, $toggle, SEC_ANCHOR.$section_id);
				?>
				<a href="<?=$sLink?>">
					<?=$SMTEXT['LSETTINGS']." ".strtolower($TEXT['TOGGLE_VIEW'])?>
				</a>
			</div>		
		</div>
		<?php if($settings['show_settings'] == 1):?>
		<div class="formRow">
			<label class="settingName"><?=$SMTEXT['HEADER']?></label>
			<div class="settingValue"><textarea class="tabbed" name="header"><?=$header?></textarea></div>
		</div>
		<div class="formRow">
			<label class="settingName"><?=$SMTEXT['LHEADER']?></label>
			<div class="settingValue"><textarea class="tabbed" name="level_header"><?=$level_header?></textarea></div>
		</div>
		<div class="formRow">
			<label class="settingName"><?=$SMTEXT['LLOOP']?></label>
			<div class="settingValue"><textarea class="tabbed" name="sitemaploop"><?=$sitemaploop?></textarea></div>
		</div>
		<div class="formRow">
			<label class="settingName"><?=$SMTEXT['LFOOTER']?></label>
			<div class="settingValue"><textarea class="tabbed" name="level_footer"><?=$level_footer?></textarea></div>
		</div>
		<div class="formRow">
			<label class="settingName"><?=$TEXT['FOOTER']?></label>
			<div class="settingValue"><textarea class="tabbed" name="footer"><?=$footer?></textarea></div>
		</div>
		<?php endif; //$settings['show_settings'] == 1) ?>
		<div class="buttonsRow">
			<input class="button ico-save" name="save" type="submit" value="<?=$TEXT['SAVE']?>">
			<input class="button ico-cancel pos-right" type="button" value="<?=$TEXT['CANCEL']?>" onclick="javascript: window.location = '<?=ADMIN_URL?>/pages/index.php'; return false;" />
		</div>
	</form>
</div>

<script>
function uncheck(){
	var a=uncheck.arguments,z0=0;
	for (;z0<a.length;z0++){
		document.getElementById(a[z0])?document.getElementById(a[z0]).checked=false:null;
	}
}
$(document).delegate('.tabbed', 'keydown', function(e) { 
  var keyCode = e.keyCode || e.which; 

  if (keyCode == 9) { 
    e.preventDefault(); 
    var start = $(this).get(0).selectionStart;
    var end = $(this).get(0).selectionEnd;

    // set textarea value to: text before caret + tab + text after caret
    $(this).val($(this).val().substring(0, start)
                + "\t"
                + $(this).val().substring(end));

    // put caret at right position again
    $(this).get(0).selectionStart = 
    $(this).get(0).selectionEnd = start + 1;
  } 
});
</script>
<?php 