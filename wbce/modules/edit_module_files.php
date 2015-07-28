<?php
/**
 *
 * @category        backend
 * @package         modules
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: edit_module_files.php 1484 2011-07-31 19:42:13Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/edit_module_files.php $
 * @lastmodified    $Date: 2011-07-31 21:42:13 +0200 (So, 31. Jul 2011) $
 *
 */

// include required libraries
// include configuration file
	require('../config.php');
// include edit area wrapper script
	require_once(WB_PATH.'/include/editarea/wb_wrapper_edit_area.php');
// include functions to edit the optional module CSS files (frontend.css, backend.css)
	require_once(WB_PATH.'/framework/module.functions.php');
// $admin_header = false;
// Tells script to update when this page was last updated
	$update_when_modified = false;
// show the info banner
	$print_info_banner = true;
// Include WB admin wrapper script
	require(WB_PATH.'/modules/admin.php');
/*
	$page_id = (isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : 0  );
	$section_id = (isset($_POST['section_id']) ? intval($_POST['section_id']) : 0  );
*/
	$_action = (isset($_POST['action']) ? strtolower($_POST['action']) : '');
	$_action = ($_action != 'save' ? 'edit' : 'save');
	$mod_dir = (isset($_POST['mod_dir']) ? $_POST['mod_dir'] : '');
	$_edit_file = (isset($_POST['edit_file']) ? $_POST['edit_file'] : '');
//check if given mod_dir + edit_file is valid path/file
	$_realpath = realpath(WB_PATH.'/modules/'.$mod_dir.'/'.$_edit_file);
	if($_realpath){
	// realpath is a valid path, now test if it's inside WB_PATH
		$_realpath = str_replace('\\','/', $_realpath);
		$_fileValid = (strpos($_realpath, (str_replace('\\','/', WB_PATH))) !== false);
	}
// check if all needed args are valid 
	if(!$page_id || !$section_id || !$_realpath || !$_fileValid) {
		die('Invalid arguments passed - script stopped.');
	}

	// echo registerEditArea('code_area', 'css');
	echo (function_exists('registerEditArea')) ? registerEditArea('code_area', 'css') : 'none';
// set default text output if varibles are not defined in the global WB language files
	if(!isset($TEXT['HEADING_CSS_FILE'])) { $TEXT['HEADING_CSS_FILE'] = 'Actual module file: '; }
	if(!isset($TEXT['TXT_EDIT_CSS_FILE'])) { $TEXT['TXT_EDIT_CSS_FILE'] = 'Edit the CSS definitions in the textarea below.'; }

// check if action is: save or edit
	if($_action == 'save') {
	// SAVE THE UPDATED CONTENTS TO THE CSS FILE
		$css_content = '';
		if (isset($_POST['css_data']) && strlen($_POST['css_data']) > 0) {
			$css_content = stripslashes($_POST['css_data']);
		}
		$modFileName = WB_PATH .'/modules/' .$mod_dir .'/' .$_edit_file;
		if(($fileHandle = fopen($modFileName, 'wb'))) {
			if(fwrite($fileHandle, $css_content)) {
				fclose($fileHandle);
				$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
				exit;
			}
			fclose($fileHandle);
		}
		$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
		exit;
	} else {
	// MODIFY CONTENTS OF THE CSS FILE VIA TEXT AREA
	// check which module file to edit (frontend.css, backend.css or '')
		$css_file = (in_array($_edit_file, array('frontend.css', 'backend.css'))) ? $_edit_file : '';

	// display output
		if($css_file == '')	{
		// no valid module file to edit; display error message and backlink to modify.php
			echo "<h2>Nothing to edit</h2>";
			echo "<p>No valid module file exists for this module.</p>";
			$output  = "<a href=\"#\" onclick=\"javascript: window.location = '";
			$output .= ADMIN_URL ."/pages/modify.php?page_id=" .$page_id ."'\">back</a>";
			echo $output;
		} else {
		// store content of the module file in variable
		$css_content = @file_get_contents(WB_PATH .'/modules/' .$mod_dir .'/' .$css_file);
		// write out heading
		echo '<h2>' .$TEXT['HEADING_CSS_FILE'] .'"' .$css_file .'"</h2>';
		// include button to switch between frontend.css and backend.css (only shown if both files exists)
		toggle_css_file($mod_dir, $css_file);
		echo '<p>'.$TEXT['TXT_EDIT_CSS_FILE'].'</p>';

		// output content of module file to textareas
	  ?><form name="edit_module_file" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post" style="margin: 0;">
	  	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
	  	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	  	<input type="hidden" name="mod_dir" value="<?php echo $mod_dir; ?>" />
		<input type="hidden" name="edit_file" value="<?php echo $css_file; ?>" />
	  	<input type="hidden" name="action" value="save" />
		<textarea id="code_area" name="css_data" cols="100" rows="25" wrap="VIRTUAL" style="margin:2px;width:100%;">
<?php echo htmlspecialchars($css_content); ?>
		</textarea>
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
}
// Print admin footer
$admin->print_footer();
