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

global $section_id;

require_once __DIR__.'/functions.inc.php';
require_once WB_PATH .'/framework/module.functions.php';

$admin_header = false;
require WB_PATH.'/modules/admin.php';

$source_id = 0;
if (isset($_POST['source_id']) && is_numeric($_POST['source_id']) && ($_POST['source_id'] > 0)) {
    $source_id = $_POST['source_id'];
}

if(!defined('CAT_PATH')) {
    if (isset($_POST['source_id'])) {
        if(!$admin->checkFTAN()) {
            $admin->print_header();
            $admin->print_error(
                $MESSAGE['GENERIC_SECURITY_ACCESS']
                 .' (FTAN) '.__FILE__.':'.__LINE__,
                     ADMIN_URL.'/pages/index.php'
            );
            $admin->print_footer();
            exit();
        } else {
            $admin->print_header();
        }
    } else {
        $admin->print_header();
        $section_key = $admin->checkIDKEY('section_key', 0, 'GET');
        if (!$section_key || $section_key != $section_id) {
            $admin->print_error(
                $MESSAGE['GENERIC_SECURITY_ACCESS']
                 .' (IDKEY) '.__FILE__.':'.__LINE__,
                     ADMIN_URL.'/pages/index.php'
            );
            $admin->print_footer();
            exit();
        }
    }
}

// get settings
$settings = mod_nwi_settings_get($source_id>0?$source_id:$section_id);

// Check if user has permission to modify the page settings
if (!$admin->isAdmin() && $settings['show_settings_only_admins'] == 'Y') {
	$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}


// extract width and height
$previewwidth = $previewheight = $thumbwidth = $thumbheight = '';
if (substr_count($settings['resize_preview'], 'x')>0) {
    list($previewwidth, $previewheight) = explode('x', $settings['resize_preview'], 2);
}
if (substr_count($settings['imgthumbsize'], 'x')>0) {
    list($thumbwidth, $thumbheight) = explode('x', $settings['imgthumbsize'], 2);
}

// Set raw html <'s and >'s to be replaced by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');

// default image sizes
$SIZES['50'] = '50x50px';
$SIZES['75'] = '75x75px';
$SIZES['100'] = '100x100px';
$SIZES['125'] = '125x125px';
$SIZES['150'] = '150x150px';
$SIZES['220'] = '200x200px';

$FTAN = $admin->getFTAN();

// get available views
if(defined('CAT_PATH')) {
    $views = CAT_Helper_Directory::getDirectories(__DIR__.'/views',__DIR__.'/views/');
} else {
    $dirs = array_filter(glob(__DIR__.'/views/*'), 'is_dir');
    foreach($dirs as $dir) {
        $views[] = pathinfo($dir,PATHINFO_FILENAME);
    }
}

include __DIR__.'/templates/default/modify_settings.phtml';

// Print admin footer
$admin->print_footer();
