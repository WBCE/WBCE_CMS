<?php
/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * modify_config.php
 * This file provides a mask to change and update the configuration settings of the Tool
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if(!defined('WB_PATH')) exit("Cannot access this file directly ".__FILE__);

// user needs permission for admintools OR pages
if(!$admin->get_permission('admintools') || !$admin->get_permission('pages')) {
	exit("insuficient privileges");
}

$sFunctionsFile = dirname(__FILE__).'/functions.php';	
if(file_exists($sFunctionsFile)){
	require_once($sFunctionsFile);
}
$sTable = TABLE_PREFIX . 'mod_page_seo_tool';
if(isset($_POST['save_config'])){	
	$aSettings = array(
		'iTitleCount' => array(
			'use' => isset($_POST['iTitleCount_use']) ? 1 : 0,
			'optimum' => (int) $admin->add_slashes($admin->get_post('iTitleCount_optimum')),
			'minimum' => (int) $admin->add_slashes($admin->get_post('iTitleCount_minimum'))
		),
		'iDescriptionCount' => array(
			'use' => isset($_POST['iDescriptionCount_use']) ? 1 : 0,
			'optimum' => (int) $admin->add_slashes($admin->get_post('iDescriptionCount_optimum')),
			'minimum' => (int) $admin->add_slashes($admin->get_post('iDescriptionCount_minimum'))
		),
		'keywordsConfig' => array(
			'use' => isset($_POST['keywordsConfig_use']) ? 1 : 0,		
			'wordReplace' => $admin->add_slashes($admin->get_post('keywordsConfig_wordReplace'))
		),
		'rewriteUrl' => array(
			'use' => isset($_POST['rewriteUrl_use']) ? 1 : 0,
			'dbString' => $admin->add_slashes($admin->get_post('rewriteUrl_dbString'))
		),
		'bUseRemainingChars' => isset($_POST['bUseRemainingChars']) ? 1 : 0,
		'bUseFlags' => isset($_POST['bUseFlags']) ? 1 : 0
	);	
	$sSqlInsert = "UPDATE `".$sTable."` SET `settings_json` = '". json_encode($aSettings) ."'";
	if($database->query($sSqlInsert)) {
		$admin->print_success('Changes saved successfully', false);
	}
}
$jsonSettings = $database->get_one("SELECT `settings_json` FROM `".$sTable."`");
$config = json_decode($jsonSettings, TRUE);
#debug_dump($config, '$config array');
?>
<h2><?php echo $TOOL_TEXT['ADVANCED_SETTINGS'] ?></h2>
<form action="<?php echo $toolUrl.'&pos=config' ?>"  method="post" name="config_editor">
<table class="settings_table" cellpadding="2" cellspacing="0" border="0" width="100%">
	<caption><?php echo $TOOL_TEXT['TOOL_CONFIG'] ?></caption>	
	<tr>
		<th>Title Counter:</th>
		<td>
			<input type="checkbox" id="iTitleCount_use" name="iTitleCount_use"<?php echo ($config['iTitleCount']['use'] == TRUE) ? ' checked="checked"' : ''; ?>/>
			<label for="iTitleCount_use"><?php echo $TOOL_TEXT['USE'] ?></label>
			<div id="iTitleCount">
				<label for="iTitleCount_minimum">minimum:</label>
				<input type="number" id="iTitleCount_minimum" name="iTitleCount_minimum"  value="<?php echo $config['iTitleCount']['minimum'] ?>"/><br />
				<label for="iTitleCount_optimum">optimum:</label>
				<input type="number" id="iTitleCount_optimum" name="iTitleCount_optimum"  value="<?php echo $config['iTitleCount']['optimum'] ?>"/>
			</div>
		</td>
	</tr>	
	<tr>
		<th>Description Counter:</th>
		<td>
			<input type="checkbox" id="iDescriptionCount_use" name="iDescriptionCount_use"<?php echo ($config['iDescriptionCount']['use'] == TRUE) ? ' checked="checked"' : ''; ?>/>
			<label for="iDescriptionCount_use"><?php echo $TOOL_TEXT['USE'] ?></label>
			<div id="iDescriptionCount">
				<label for="iDescriptionCount_minimum">minimum:</label>
				<input type="number" id="iDescriptionCount_minimum" name="iDescriptionCount_minimum" value="<?php echo $config['iDescriptionCount']['minimum'] ?>"/><br />
				<label for="iDescriptionCount_optimum">optimum:</label>
				<input type="number" id="iDescriptionCount_optimum" name="iDescriptionCount_optimum" value="<?php echo $config['iDescriptionCount']['optimum'] ?>"/>
			</div>
		</td>
	</tr>
	<tr>
		<th>Keywords:</th>
		<td>
			<input type="checkbox" id="keywordsConfig_use" name="keywordsConfig_use"<?php echo ($config['keywordsConfig']['use'] == TRUE) ? ' checked="checked"' : ''; ?>/>
			<label for="keywordsConfig_use"><?php echo $TOOL_TEXT['USE'] ?></label>
				<div id="keywordsConfig">
				<label for="keywordsConfig_wordReplace"><?php echo $TOOL_TEXT['CUSTOM_KEYWORDS_STRING'] ?>:</label>
				<input type="text" id="keywordsConfig_wordReplace" name="keywordsConfig_wordReplace" value="<?php echo $config['keywordsConfig']['wordReplace'] ?>"/>
				<br />
				<small><?php echo $TOOL_TEXT['CUSTOM_KEYWORDS_STRING_HINT'] ?></small>
			</div>
		</td>
	</tr>	
	<tr>
		<th><?php echo $TOOL_TEXT['USE_REMAINING_CHARS'] ?>:</th>
		<td>
			<input type="checkbox" id="bUseRemainingChars_use" name="bUseRemainingChars"<?php echo ($config['bUseRemainingChars'] == TRUE) ? ' checked="checked"' : ''; ?>/>
			<label for="bUseRemainingChars_use"><?php echo $TOOL_TEXT['USE'] ?></label>		
			<div id="bUseRemainingChars">
				<small><?php echo $TOOL_TEXT['USE_REMAINING_CHARS_HINT'] ?></small>
			</div>
		</td>
	</tr>	
	<tr>
		<th><?php echo $TOOL_TEXT['USE_FLAGS'] ?>:</th>
		<td>
			<input type="checkbox" id="bUseFlags_use" name="bUseFlags"<?php echo (isset($config['bUseFlags']) && $config['bUseFlags'] == TRUE) ? ' checked="checked"' : ''; ?>/>
			<label for="bUseFlags_use"><?php echo $TOOL_TEXT['USE'] ?></label>		
			<div id="bUseFlags">
				<small><?php echo $TOOL_TEXT['USE_FLAGS_HINT'] ?></small>
			</div>
		</td>
	</tr>
	<tr class="usewithcaution">
		<th>URL Rewrite:</th>
		<td>
			<input type="checkbox" id="rewriteUrl_use" name="rewriteUrl_use"<?php echo ($config['rewriteUrl']['use'] == TRUE) ? ' checked="checked"' : ''; ?>/>
			<label for="rewriteUrl_use"><?php echo $TOOL_TEXT['USE'] ?></label>
			<div id="rewriteUrl">
				<?php
					$sInputClass = '';
					$sNotice = '';
					if(defined("REWRITE_URL") == TRUE){
						//  CHECK if the COLUMN `rewrite_url` exists in the `pages` TABLE
						$oCheckDbTable = $database->query("SHOW COLUMNS FROM `".TABLE_PREFIX."pages` LIKE '".REWRITE_URL."'");
						$bRewriteUrlExists = $oCheckDbTable->numRows() > 0 ? TRUE : FALSE;
						
						if($bRewriteUrlExists == FALSE || $config['rewriteUrl']['dbString'] == ''){
							$sInputClass = 'ui-nestedSortable-error';
							$sNotice = '<p class="warning">'.sprintf($TOOL_TEXT['REWRITE_URL_WARNING_HINT'], $config['rewriteUrl']['dbString']).'</p>';
						}
					}
				?>
				<label for="rewriteUrl_dbString"><?php echo $TOOL_TEXT['USED_DB_FIELD'] ?>:</label>
				<input class="<?php echo $sInputClass ?>" type="text" id="rewriteUrl_dbString" name="rewriteUrl_dbString" value="<?php echo $config['rewriteUrl']['dbString'] ?>" /><br />
				<?php echo $sNotice ?>
				<small style="color:red;"><?php echo $TOOL_TEXT['USE_REWRITE_URL_HINT']; ?></small>
			</div>			
		</td>
	</tr>		
	<tfoot>
		<tr>
			<th><a href="<?php echo $toolUrl ?>">&laquo; <?php echo $TEXT['BACK'] ?></a></th>
			<td><input type="submit" name="save_config" value="<?php echo $TEXT['SAVE'] ?>" /></td>
		</tr>
	</tfoot>
</table>
</form>

<script type="text/javascript">
	$(document).ready(function() {	
		
		// handle checkboxes to show hidden div when checkbox is checked
		var aElements = [
			'iTitleCount', 
			'iDescriptionCount',
			'keywordsConfig', 
			'rewriteUrl',
			'bUseFlags',
			'bUseRemainingChars'
		];
		$.each(aElements, function(index, value) {
			var ELEMENT = $('#' + value +'_use');
			if (!ELEMENT.is(":checked")) $('#' + value).hide();		  
			ELEMENT.on("click", function() {
			  if ($(this).is(":checked")) $('#' + value).show();
			  else $('#' + value).hide();
			});		
		});
		
	});
</script>