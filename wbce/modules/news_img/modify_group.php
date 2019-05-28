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
require(WB_PATH.'/modules/admin.php');
$group_id = $admin->checkIDKEY('group_id', 0, 'GET');
if (!$group_id){
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (IDKEY) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
}

$FTAN = $admin->getFTAN();

// Get header and footer
$query_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_img_groups` WHERE `group_id` = '$group_id'");
$fetch_content = $query_content->fetchRow();

?>

<div class="mod_news_img">
    <h2><?php echo $TEXT['ADD'].'/'.$TEXT['MODIFY'].' '.$TEXT['GROUP']; ?></h2>

    <form name="modify" action="<?php echo WB_URL; ?>/modules/news_img/save_group.php" method="post" enctype="multipart/form-data" style="margin: 0;">
    <?php echo $FTAN; ?>
    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>" />

    <table>
    <tr>
    	<td style="width:80px"><?php echo $TEXT['TITLE']; ?>:</td>
    	<td colspan="2">
    		<input type="text" name="title" value="<?php echo (htmlspecialchars($fetch_content['title'])); ?>" style="width: 98%;" maxlength="255" />
    	</td>
    </tr>
    <tr>
    	<td><?php echo $TEXT['ACTIVE']; ?>:</td>
    	<td colspan="2">
    		<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_content['active'] == 1) { echo ' checked="checked"'; } ?> />
    		<a href="#" onclick="javascript: document.getElementById('active_true').checked = true;">
    		<?php echo $TEXT['YES']; ?>
    		</a>
    		-
    		<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_content['active'] == 0) { echo ' checked="checked"'; } ?> />
    		<a href="#" onclick="javascript: document.getElementById('active_false').checked = true;">
    		<?php echo $TEXT['NO']; ?>
    		</a>
    	</td>
    </tr>

    <tr>
    	<td><?php echo $TEXT['IMAGE']; ?>:</td>
    	<?php if(file_exists(WB_PATH.MEDIA_DIRECTORY.'/.news_img/image'.$group_id.'.jpg')) { ?>
        <td>
            <img class="preview" src="<?php echo WB_URL.MEDIA_DIRECTORY; ?>/.news_img/image<?php echo $group_id; ?>.jpg" alt="<?php echo $TEXT['IMAGE']; ?>" /></div>
    		<input type="checkbox" name="delete_image" id="delete_image" value="true" />
    		<label for="delete_image"><?php echo $TEXT['DELETE'] ?></label>
    	</td>

    	<?php } else { ?>
    	<td colspan="2">
    		<input type="file" name="image" />
    	</td>
    	<?php } ?>
    </tr>
    </table>

    <table>
    <tr>
    	<td align="left">
    		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
    	</td>
    	<td align="right">
    		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
    	</td>
    </tr>
    </table>
    </form>
</div>
<?php

// Print admin footer
$admin->print_footer();

