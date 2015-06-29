<?php
/**
 *
 * @category        modules
 * @package         wysiwyg
 * @author          WebsiteBaker Project
 * @copyright       2009-2012, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: modify.php 1581 2012-01-17 21:16:25Z darkviper $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/wysiwyg/modify.php $
 * @lastmodified    $Date: 2012-01-17 22:16:25 +0100 (Di, 17. Jan 2012) $
 *
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
	require_once(dirname(dirname(__FILE__)).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$sMediaUrl = WB_URL.MEDIA_DIRECTORY;
// Get page content
$sql = 'SELECT `content` FROM `'.TABLE_PREFIX.'mod_wysiwyg` WHERE `section_id`='.(int)$section_id;
if ( ($content = $database->get_one($sql)) ) {
	$content = htmlspecialchars(str_replace('{SYSVAR:MEDIA_REL}', $sMediaUrl, $content));
}else {
	$content = '';
}
if(!isset($wysiwyg_editor_loaded)) {
	$wysiwyg_editor_loaded=true;

	if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
		function show_wysiwyg_editor($name,$id,$content,$width,$height) {
			echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
		}
	} else {
		$id_list = array();
		$sql  = 'SELECT `section_id` FROM `'.TABLE_PREFIX.'sections` ';
		$sql .= 'WHERE `page_id`='.(int)$page_id.' AND `module`=\'wysiwyg\'';
		if (($query_wysiwyg = $database->query($sql))) {
			while($wysiwyg_section = $query_wysiwyg->fetchRow()) {
				$entry='content'.$wysiwyg_section['section_id'];
				$id_list[] = $entry;
			}
			require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
		}
	}
}

?>
<form name="wysiwyg<?php echo $section_id; ?>" action="<?php echo WB_URL; ?>/modules/wysiwyg/save.php" method="post">
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<?php
echo $admin->getFTAN()."\n"; 
show_wysiwyg_editor('content'.$section_id,'content'.$section_id,$content,'100%','350');
?>
	<table summary="" cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-bottom: 10px;">
		<tr>
			<td align="left">
				<input type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
			</td>
			<td align="right">
				<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = 'index.php';" style="width: 100px; margin-top: 5px;" />
			</td>
		</tr>
	</table>
</form>
<br />
