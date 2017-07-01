<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.11.0
 * @lastmodified    june 30, 2017
 *
 */

if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

require_once(WB_PATH.'/include/editarea/wb_wrapper_edit_area.php');
require_once(WB_PATH.'/framework/module.functions.php');
$backlink = ADMIN_URL.'/pages/modify.php?page_id='.(int)$page_id;

$nwdata = '';
if(isset($_POST['remote'])) {
	$tmpremote = $_POST['remote'];
	if(stripos($tmpremote,'load=') !== false) {
		$tmpremote = substr($tmpremote,stripos($tmpremote,'load=')+5,32);
	}
	if(strlen($tmpremote) == 32) {
		$remote = $tmpremote;
		echo "<div class='mf-loading'>".$MF['LOADING'].": $remote</div>";
		$remotelink = 'https://miniform.dev4me.com/form-creator/?load='.$remote.'&clean';
		$nwdata = base64_decode($mform->remote_data($remotelink));
	}
	if(!$nwdata) {
		echo "<div class='mf-error'>".$MF['LOADERROR']."</div>";
	} else {
		$database->query("UPDATE `".TABLE_PREFIX."mod_miniform` SET `remote_id`= '$remote' WHERE `section_id` = '$section_id'");
		echo "<div class='mf-success'>".$MF['LOADSUCCESS']."</div>";
	}	
}

$_action = (isset($_POST['action']) ? $_POST['action'] : '');
$_action = ($_action != 'save' ? 'edit' : 'save');
if ($_action == 'save') {
	$template = $admin->add_slashes($_POST['name']);
	if (get_magic_quotes_gpc()) {
		$data = stripslashes($_POST['template_data']);
	}
	else {
		$data = $_POST['template_data'];
	}
	$filename = dirname(__FILE__).'/templates/form_'.$template.'.htt';
	if (false !== file_put_contents($filename,$data)) {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	} else { 
		$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
} else {
	
	$template = $admin->add_slashes($_GET['name']);
	$filename = dirname(__FILE__).'/templates/form_'.$template.'.htt';
	$data = '';
	if (file_exists($filename)) $data = file_get_contents($filename) ;
	if($nwdata) $data = $nwdata;
	echo (function_exists('registerEditArea')) ? registerEditArea('code_area', 'html') : 'none';
	?>
	<form name="edit_module_file" action="<?php echo $manage_url.$template;?>" method="post" style="margin: 0;">
			<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
			<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
			<input type="hidden" name="action" value="save" />
			<span class="sleft"><?php echo $MF['SAVEAS']; ?>: </span><input required type="text" class="mf-input" name="name" value="<?php echo $template; ?>" />
			<span style="float:right"><a class="mf-button" href="http://miniform.dev4me.nl/template-help/basic-structure/" target="_blank"><?php echo $MF['HELP']; ?></a></span>
			<span style="float:right"><a class="mf-button" href="http://miniform.dev4me.nl/form-creator/<?php echo $remote ? '?load='.$remote:''; ?>" target="_blank"><?php echo $MF['TPL_GEN']; ?></a></span>
			<span style="float:right"><a class="doremote mf-button" href="#" ><?php echo $MF['DOREMOTE']; ?></a></span>
			<div class="mf_editor">
			<br><br>
			<textarea id="code_area" name="template_data" cols="100" rows="25" wrap="VIRTUAL" style="margin:2px;width:100%;"><?php echo htmlspecialchars($data); ?></textarea>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td class="left">
					<input name="save" type="submit" value="<?php echo $TEXT['SAVE'];?>" style="width: 100px; margin-top: 5px;" />
					</td>
					<td class="right">
					<input type="button" value="<?php echo $TEXT['CANCEL']; ?>"
							onclick="javascript: window.location = '<?php echo ADMIN_URL;?>/pages/modify.php?page_id=<?php echo $page_id; ?>';"
							style="width: 100px; margin-top: 5px;" />
					</td>
				</tr>
			</table>
			</div>
	</form>
	<div class="mf_remote" style="display:none;">
		<br><br>
		<form name="load_remote" action="<?php echo $manage_url.$template;?>" method="post" > 
			<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
			<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
			<span class="sleft"><?php echo $MF['REMOTE']; ?>: </span><input type="text" class="mf-input" name="remote" value="<?php echo $remote; ?>" />
			<input class="mf-button" name="load" type="submit" value="<?php echo $MF['LOAD'];?>" style="width: 100px; margin-top: 5px;" />
			<input class="mf-button" type="button" value="<?php echo $TEXT['CANCEL']; ?>"
								onclick="javascript: window.location = '<?php echo ADMIN_URL;?>/pages/modify.php?page_id=<?php echo $page_id; ?>';"
								style="width: 100px; margin-top: 5px;" />

			
		</form>
		<?php if($remote) { ?>
		<br>
		<span class="sleft">&nbsp;</span><a class="mf-button" href="http://miniform.dev4me.nl/form-creator/?load=<?php echo $remote ?>" target="_blank"><?php echo $MF['EDITREMOTE'];?></a><br>
		<?php } ?>

	</div>
	<?php 
}
// Print admin footer
$admin->print_footer();

