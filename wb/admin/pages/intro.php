<?php
/**
 *
 * @category        admin
 * @package         pages
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: intro.php 1511 2011-09-14 17:24:09Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/pages/intro.php $
 * @lastmodified    $Date: 2011-09-14 19:24:09 +0200 (Mi, 14. Sep 2011) $
 *
 */

// Create new admin object
require('../../config.php');

require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_intro');
$content = '';

$filename = WB_PATH.PAGES_DIRECTORY.'/intro'.PAGE_EXTENSION;

if(file_exists($filename) && filesize($filename) > 0) {
	$content = file_get_contents( $filename ) ;
} else {
	$content = file_get_contents( ADMIN_PATH.'/pages/html.php' ) ;
}

require_once(WB_PATH . '/include/editarea/wb_wrapper_edit_area.php');
$toolbar = 'search, fullscreen, |, undo, redo, |, select_font, syntax_selection,|,word_wrap, highlight, reset_highlight, |,charmap, |, help';
echo registerEditArea ('content','php',true,'both',true,true,600,450,$toolbar);
function show_wysiwyg_editor($name,$id,$content,$width,$height) {
	echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
}
?>
<form action="intro2.php" method="post">
<?php print $admin->getFTAN(); ?>
<input type="hidden" name="page_id" value="{PAGE_ID}" />
<table cellpadding="0" cellspacing="0" border="0" class="form_submit">
	<tr>
		<td colspan="2">
		<?php
			show_wysiwyg_editor('content','content',$content,'100%','500px');
		?>
		</td>
	</tr>
	<tr>
		<td class="left">
			<input type="submit" value="<?php echo $TEXT['SAVE'];?>" class="submit" />
		</td>
		<td class="right">
			<input type="button" value="<?php echo $TEXT['CANCEL'];?>" onclick="javascript: window.location = 'index.php';" class="submit" />
		</td>
	</tr>
</table>

</form>
<?php
// Print admin footer
$admin->print_footer();
