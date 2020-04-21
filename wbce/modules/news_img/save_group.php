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

// Get id
if(!isset($_POST['group_id']) OR !is_numeric($_POST['group_id']))
{
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit( 0 );
}
else
{
	$group_id = intval($_POST['group_id']);
}


// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = FALSE;
require WB_PATH.'/modules/admin.php';
if (!$admin->checkFTAN()){
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']
	 .' (FTAN) '.__FILE__.':'.__LINE__,
         ADMIN_URL.'/pages/index.php');
    $admin->print_footer();
    exit();
} else $admin->print_header();

require_once __DIR__.'/functions.inc.php';

// Validate all fields
if($admin->get_post('title') == '')
{
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/news_img/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$admin->getIDKEY($group_id));
    $admin->print_footer();
    exit();
}
else
{
	$title = $database->escapeString($admin->get_post('title'));
	$active = $database->escapeString($admin->get_post('active'));
	$title = strip_tags($title);
}

// Update row
$database->query("UPDATE `".TABLE_PREFIX."mod_news_img_groups` SET `title` = '$title', `active` = '$active' WHERE `group_id` = '$group_id'");

// Check if the user uploaded an image or wants to delete one
if(isset($_FILES['image']['tmp_name']) AND $_FILES['image']['tmp_name'] != '')
{
	// Get real filename and set new filename
	$filename = $_FILES['image']['name'];
	$new_filename = WB_PATH.MEDIA_DIRECTORY.'/.news_img/image'.$group_id.'.jpg';
	// Make sure the image is a jpg file
	$file4=substr($filename, -4, 4);
	if(($file4 != '.jpg')and($file4 != '.JPG')and($file4 != '.png')and($file4 != '.PNG') and ($file4 !='jpeg') and ($file4 != 'JPEG'))
    {
		$admin->print_error($MESSAGE['GENERIC']['FILE_TYPES'].' JPG, JPEG, PNG', WB_URL.'/modules/news_img/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$admin->getIDKEY($group_id));
	} elseif(
	(($_FILES['image']['type']) != 'image/jpeg' AND mime_content_type($_FILES['image']['tmp_name']) != 'image/jpg')
	and
	(($_FILES['image']['type']) != 'image/png' AND mime_content_type($_FILES['image']['tmp_name']) != 'image/png')
	){
		$admin->print_error($MESSAGE['GENERIC']['FILE_TYPES'].' JPG, JPEG, PNG', WB_URL.'/modules/news_img/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$admin->getIDKEY($group_id));
	}
	// Make sure the target directory exists
	make_dir(WB_PATH.MEDIA_DIRECTORY.'/.news_img');
	// Upload image
	move_uploaded_file($_FILES['image']['tmp_name'], $new_filename);
	// Check if we need to create a thumb
	$query_settings = $database->query("SELECT `resize_preview`,`crop_preview` FROM `".TABLE_PREFIX."mod_news_img_settings` WHERE `section_id` = '$section_id'");
	$fetch_settings = $query_settings->fetchRow();
    $previewwidth = $previewheight = 0;
    if(substr_count($fetch_settings['resize_preview'],'x')>0) {
        list($previewwidth,$previewheight) = explode('x',$fetch_settings['resize_preview'],2);
    }
	if($previewwidth != 0)
    {
		// Resize the image
		$thumb_location = WB_PATH.MEDIA_DIRECTORY.'/.news_img/thumb'.$group_id.'.jpg';
        if (extension_loaded('gd') && list($w, $h) = getimagesize($new_filename)) {
            if ($w>$previewwidth || $h>$previewheight) {
                mod_nwi_image_resize($new_filename, $thumb_location, $previewwidth, $previewheight, $fetch_settings['crop_preview']);
                unlink($new_filename);
                rename($thumb_location,$new_filename);
            }
        } else {
            copy($new_filename,$thumb_location);
        }
	}
}
if(isset($_POST['delete_image']) AND $_POST['delete_image'] != '')
{
	// Try unlinking image
	if(file_exists(WB_PATH.MEDIA_DIRECTORY.'/.news_img/image'.$group_id.'.jpg'))
    {
		unlink(WB_PATH.MEDIA_DIRECTORY.'/.news_img/image'.$group_id.'.jpg');
	}
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/news_img/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$admin->getIDKEY($group_id));
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

