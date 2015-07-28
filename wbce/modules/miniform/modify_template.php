<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.7
 * @lastmodified    april 7, 2014
 *
 */


require('../../config.php');

// $admin_header = true;
// Tells script to update when this page was last updated
// show the info banner
$print_info_banner = true;
// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');
if(!file_exists(WB_PATH.'/modules/miniform/languages/'.LANGUAGE.'.php')) {
	require_once(WB_PATH.'/modules/miniform/languages/EN.php');
} else {
	require_once(WB_PATH.'/modules/miniform/languages/'.LANGUAGE.'.php');
}
require_once(WB_PATH.'/include/editarea/wb_wrapper_edit_area.php');
	require_once(WB_PATH.'/framework/module.functions.php');
$backlink = ADMIN_URL.'/pages/modify.php?page_id='.(int)$page_id;


$_action = (isset($_POST['action']) ? strtolower($_POST['action']) : '');
$_action = ($_action != 'save' ? 'edit' : 'save');
if ($_action == 'save') {
	$template = strtolower($admin->add_slashes($_POST['name']));
	if (get_magic_quotes_gpc()) {
		$data = stripslashes($_POST['template_data']);
	}
	else {
		$data = $_POST['template_data'];
	}
	$filename = dirname(__FILE__).'/templates/form_'.$template.'.htt';
	if (!false == file_put_contents($filename,$data)) {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	} else { 
		$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
} else {
	
	$template = $admin->add_slashes($_GET['name']);
	$filename = dirname(__FILE__).'/templates/form_'.$template.'.htt';
	$data = '';
	if (file_exists($filename)) $data = file_get_contents($filename) ;
	echo (function_exists('registerEditArea')) ? registerEditArea('code_area', 'html') : 'none';
	?>
	<form name="edit_module_file" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post" style="margin: 0;">
			<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
			<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
			<input type="hidden" name="action" value="save" />
			<span><?php echo $MF['SAVEAS']; ?>: </span><input type="text" name="name" value="<?php echo $template; ?>" />
			<span style="float:right"><a href="http://www.dev4me.nl/modules-snippets/opensource/miniform/help/" target="blank">Help</a></span>
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
	</form>
	<?php 
}
// Print admin footer
$admin->print_footer();
