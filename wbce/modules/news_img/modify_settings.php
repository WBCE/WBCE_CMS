<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */
require_once __DIR__.'/functions.inc.php';

// Include WB admin wrapper script
$admin_header = FALSE;
require WB_PATH.'/modules/admin.php';
$source_id = 0;
if(isset($_POST['source_id'])){
    if (!$admin->checkFTAN()){
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	     .' (FTAN) '.__FILE__.':'.__LINE__,
             ADMIN_URL.'/pages/index.php');
	$admin->print_footer();
	exit();
    } else $admin->print_header();
    if(isset($_POST['source_id']) && is_numeric($_POST['source_id']) && ($_POST['source_id'] > 0)) $source_id = $_POST['source_id'];
} else {
    $admin->print_header();
    $section_key = $admin->checkIDKEY('section_key', 0, 'GET');
    if (!$section_key || $section_key != $section_id){
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	     .' (IDKEY) '.__FILE__.':'.__LINE__,
             ADMIN_URL.'/pages/index.php');
	$admin->print_footer();
	exit();
    }
}


// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once WB_PATH .'/framework/module.functions.php';

// Get header and footer
$query_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '".($source_id>0?$source_id:$section_id)."'");
$fetch_content = $query_content->fetchRow();

$previewwidth = $previewheight = $thumbwidth = $thumbheight = '';
if(substr_count($fetch_content['resize_preview'],'x')>0) {
    list($previewwidth,$previewheight) = explode('x',$fetch_content['resize_preview'],2);
}
if(substr_count($fetch_content['imgthumbsize'],'x')>0) {
    list($thumbwidth,$thumbheight) = explode('x',$fetch_content['imgthumbsize'],2);
}

// Set raw html <'s and >'s to be replace by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');

// default image sizes
$SIZES['50'] = '50x50px';
$SIZES['75'] = '75x75px';
$SIZES['100'] = '100x100px';
$SIZES['125'] = '125x125px';
$SIZES['150'] = '150x150px';
$SIZES['220'] = '200x200px';

// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/news_img/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/news_img/backend.css');
	echo "\n</style>\n";
}

$FTAN = $admin->getFTAN();

?>
<div class="mod_news_img">
    <h2><?php echo $MOD_NEWS_IMG['SETTINGS']; ?></h2>
<?php
// include the button to edit the optional module CSS files (function added with WB 2.7)
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css'))
{
	edit_module_css('news_img');
}
?>

    <form name="modify" action="<?php echo WB_URL; ?>/modules/news_img/save_settings.php" method="post" style="margin: 0;">

        <?php echo $FTAN; ?>
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />

    	<table>
		
		<tr><td colspan="2"><h3><?php echo $MOD_NEWS_IMG['OVERVIEW_SETTINGS']?></h3></td></tr>
		
		<tr>
			<td class="setting_name"><?php echo $MOD_NEWS_IMG['ORDERBY']; ?>:</td>
			<td class="setting_value">
				<select name="view_order" style="width: 98%;">
					<?php
					echo '<option value="0"'.(($fetch_content['view_order'] == 0)?' selected="selected"':'').'>'.$TEXT['CUSTOM'].'</option>';
					echo '<option value="1"'.(($fetch_content['view_order'] == 1)?' selected="selected"':'').'>'.$TEXT['PUBL_START_DATE'].'</option>';
					echo '<option value="2"'.(($fetch_content['view_order'] == 2)?' selected="selected"':'').'>'.$TEXT['PUBL_END_DATE'].'</option>';
					echo '<option value="3"'.(($fetch_content['view_order'] == 3)?' selected="selected"':'').'>'.$TEXT['SUBMITTED'].'</option>';
					echo '<option value="4"'.(($fetch_content['view_order'] == 4)?' selected="selected"':'').'>'.$TEXT['SUBMISSION_ID'].'</option>';
					?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="setting_name"><?php echo $TEXT['POSTS_PER_PAGE']; ?>:</td>
			<td class="setting_value">
				<select name="posts_per_page" style="width: 98%;">
					<option value=""><?php echo $TEXT['UNLIMITED']; ?></option>
					<?php
					for($i = 1; $i <= 20; $i++) {
						if($fetch_content['posts_per_page'] == ($i*5)) { $selected = ' selected="selected"'; } else { $selected = ''; }
						echo '<option value="'.($i*5).'"'.$selected.'>'.($i*5).'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="setting_name"><?php echo $TEXT['HEADER']; ?>:</td>
			<td class="setting_value">
				<textarea name="header" rows="10" cols="1" style="width: 98%; height: 80px;"><?php echo ($fetch_content['header']); ?></textarea>
			</td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['POST'].' '.$TEXT['LOOP']; ?>:</td>
			<td class="setting_value">
				<textarea name="post_loop" rows="10" cols="1" style="width: 98%; height: 60px;"><?php echo ($fetch_content['post_loop']); ?></textarea>
			</td>
		</tr>
		
		<tr>
			<td class="setting_name"><?php echo $TEXT['FOOTER']; ?>:</td>
			<td class="setting_value">
				<textarea name="footer" rows="10" cols="1" style="width: 98%; height: 80px;"><?php echo str_replace($raw, $friendly, ($fetch_content['footer'])); ?></textarea>
			</td>
		</tr>

<?php
     
    if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { ?>
        <tr>
			<td class="setting_name"><?php echo $MOD_NEWS_IMG['RESIZE_PREVIEW_IMAGE_TO']; ?>:</td>
			<td class="setting_value">
                <label for="resize_width"><?php echo $TEXT['WIDTH'] ?></label>
                    <input type="text" maxlength="4" name="resize_width" id="resize_width" style="width:80px" value="<?php echo $previewwidth ?>" /> x
                <label for="resize_height"><?php echo $TEXT['HEIGHT'] ?></label>
                    <input type="text" maxlength="4" name="resize_height" id="resize_height" style="width:80px" value="<?php echo $previewheight ?>" /> Pixel<br />
                    <span title="<?php echo $MOD_NEWS_IMG['TEXT_DEFAULTS_CLICK']; ?>"><?php echo $MOD_NEWS_IMG['TEXT_DEFAULTS'] ?>:&nbsp;
<?php
					foreach($SIZES AS $size => $size_name) {
						echo '[<span class="resize_defaults" data-value="'.$size.'">'.$size_name.'</span>] ';
					}
?>
				  </span>
			</td>
		</tr>
<?php } ?>

		<tr><td colspan="2"><h3><?php echo $MOD_NEWS_IMG['POST_SETTINGS']?></h3></td></tr>
		
		<tr>
			<td class="setting_name"><?php echo $TEXT['POST_HEADER']; ?>:</td>
			<td class="setting_value">
				<textarea name="post_header" rows="10" cols="1" style="width: 98%; height: 60px;"><?php echo str_replace($raw, $friendly, ($fetch_content['post_header'])); ?></textarea>
			</td>
		</tr>
        <tr>
			<td class="setting_name"><?php echo $MOD_NEWS_IMG['POST_CONTENT']; ?>:</td>
			<td class="setting_value">
				<textarea name="post_content" rows="10" cols="1" style="width: 98%; height: 60px;"><?php echo str_replace($raw, $friendly, ($fetch_content['post_content'])); ?></textarea>
			</td>
		</tr>
      
		<tr>
			<td class="setting_name"><?php echo $TEXT['POST_FOOTER']; ?>:</td>
			<td class="setting_value">
				<textarea name="post_footer" rows="10" cols="1" style="width: 98%; height: 60px;"><?php echo str_replace($raw, $friendly, ($fetch_content['post_footer'])); ?></textarea>
			</td>
		</tr>
		
<?php
    if(NWI_USE_SECOND_BLOCK){
?>		
		<tr>
			<td class="setting_name"><?php echo $TEXT['BLOCK']; ?> 2:</td>
			<td class="setting_value">
				<textarea name="block2" rows="10" cols="1" style="width: 98%; height: 80px;"><?php echo str_replace($raw, $friendly, ($fetch_content['block2'])); ?></textarea>
			</td>
		</tr>
	<?php } ?>		
		
		
		<tr><td colspan="2"><h3><?php echo $MOD_NEWS_IMG['GALLERY_SETTINGS']?></h3></td></tr>
		
        <tr>
			<td class="setting_name"><?php echo $MOD_NEWS_IMG['GALLERY'] ?>:</td>
			<td class="setting_value">
			<select name="gallery" style="width: 98%;" onfocus="this.setAttribute('PrvSelectedValue',this.value);" onchange="if(confirm('<?php echo $MOD_NEWS_IMG['GALLERY_WARNING']?>')==false){ this.value=this.getAttribute('PrvSelectedValue');return false; }" >     
                    <option value="fotorama"<?php if($fetch_content['gallery']=='fotorama'): ?> selected="selected"<?php endif; ?>>Fotorama</option>
                    <option value="masonry"<?php if($fetch_content['gallery']=='masonry'): ?> selected="selected"<?php endif; ?>>Masonry</option>
                </select>
			</td>
		</tr>
		<tr>
            <td></td>
            <td><i><?php echo $MOD_NEWS_IMG['GALLERY_INFO'] ?></i></td>
        </tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['IMAGE'].' '.$TEXT['LOOP']; ?>:</td>
			<td class="setting_value">
				<textarea name="image_loop" rows="10" cols="1" style="width: 98%; height: 60px;"><?php echo str_replace($raw, $friendly, ($fetch_content['image_loop'])); ?></textarea>
			</td>
		</tr>
        <tr>
			<td class="setting_name"><?php echo $MOD_NEWS_IMG['IMAGE_MAX_SIZE']; ?>:</td>
			<td class="setting_value">
                <input type="text" name="gal_img_max_size" value="<?php echo intval($fetch_content['imgmaxsize'])/1024 ?>" />
			</td>
		</tr>
		
<?php if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */ ?>
        <tr>
			<td class="setting_name"><?php echo $MOD_NEWS_IMG['RESIZE_GALLERY_IMAGES_TO']; ?>:</td>
			<td class="setting_value">
                <label for="gal_img_resize_width"><?php echo $TEXT['WIDTH'] ?></label>
                    <input type="text" maxlength="4" name="gal_img_resize_width" id="gal_img_resize_width" style="width:80px" value="<?php echo $fetch_content['imgmaxwidth'] ?>" /> x
                <label for="gal_img_resize_height"><?php echo $TEXT['HEIGHT'] ?></label>
                    <input type="text" maxlength="4" name="gal_img_resize_height" id="gal_img_resize_height" style="width:80px" value="<?php echo $fetch_content['imgmaxheight'] ?>" /> Pixel
			</td>
		</tr>
        <tr>
			<td class="setting_name"><?php echo $MOD_NEWS_IMG['THUMB_SIZE']; ?>:</td>
			<td class="setting_value">
                <label for="thumb_width"><?php echo $TEXT['WIDTH'] ?></label>
                    <input type="text" maxlength="4" name="thumb_width" id="thumb_width" style="width:80px" value="<?php echo $thumbwidth ?>" /> x
                <label for="thumb_height"><?php echo $TEXT['HEIGHT'] ?></label>
                    <input type="text" maxlength="4" name="thumb_height" id="thumb_height" style="width:80px" value="<?php echo $thumbheight ?>" /> Pixel <br />
                    <span title="<?php echo $MOD_NEWS_IMG['TEXT_DEFAULTS_CLICK']; ?>"><?php echo $MOD_NEWS_IMG['TEXT_DEFAULTS'] ?>:&nbsp;
<?php
					foreach($SIZES AS $size => $size_name) {
						echo '[<span class="resize_defaults_thumb" data-value="'.$size.'">'.$size_name.'</span>] ';
					}
?>
				  </span>
			</td>
		</tr>
        <tr>
            <td class="setting_name"><?php echo $MOD_NEWS_IMG['CROP']; ?>:</td>
			<td class="setting_value">
                <label for="crop_preview"><input type="checkbox" name="crop_preview" id="crop_preview"<?php if($fetch_content['crop_preview']=='Y'):?> checked="checked"<?php endif; ?> title="<?php echo $MOD_NEWS_IMG['TEXT_CROP'] ?>" /> <?php echo $MOD_NEWS_IMG['CROP'] ?></label><br />
                <i><?php echo $MOD_NEWS_IMG['TEXT_CROP']; ?></i>
        </tr>
<?php } ?>
	</table>
    	<table>
		<tr>
			<td class="left">
				<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
			</td>
			<td class="right">
				<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
    </form>    
<?php
    // import settings from other news with images sections
    $query_nwi = $database->query("SELECT `section_id` FROM `".TABLE_PREFIX."sections`"
    . " WHERE `module` = 'news_img' AND `section_id` != '$section_id' ORDER BY `section_id` ASC");
    $importable_sections = $query_nwi->numRows();
    if($importable_sections>0){
?>
    <h2><?php echo $MOD_NEWS_IMG['IMPORT_OPTIONS']; ?></h2>
    <form name="import" action="<?php echo WB_URL; ?>/modules/news_img/modify_settings.php" method="post" enctype="multipart/form-data">
        <?php echo $FTAN; ?>
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
    <table>
    <tr>
        <td class="setting_name"><?php echo $MOD_NEWS_IMG['IMPORT'].' '.$TEXT['FROM']; ?>:</td>
        <td class="setting_value">
            <select name="source_id">
<?php
            // Loop through possible sections
            while ($source = $query_nwi->fetchRow()) {
                echo '<option value="'.$source['section_id'].'">'.$TEXT['SECTION'].' '.$source['section_id'].'</option>';
            }
?>
            </select>
        </td>
    </tr>
    <tr>
    	<td align="left">
    		<input name="save" type="submit" value="<?php echo $MOD_NEWS_IMG['LOAD_VALUES'] ?>" style="width: 100px; margin-top: 5px;" />
    	</td>
    	<td>
    	</td>
    </tr>
    </table>

    </form>
 <?php
    }
?>
   
</div>
<?php

// Print admin footer
$admin->print_footer();
