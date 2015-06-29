<?php
/**
 *
 * @category        modules
 * @package         news
 * @author          WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: modify_comment.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/news/modify_comment.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

$comment_id = $admin->checkIDKEY('comment_id', false, 'GET');
if (!$comment_id) {
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],  ADMIN_URL.'/pages/modify.php?page_id='.$page_id );
}

// Get header and footer
$query_content = $database->query("SELECT post_id,title,comment FROM ".TABLE_PREFIX."mod_news_comments WHERE comment_id = '$comment_id'");
$fetch_content = $query_content->fetchRow();
?>

<h2><?php echo $TEXT['MODIFY'].' '.$TEXT['COMMENT']; ?></h2>

<form name="modify" action="<?php echo WB_URL; ?>/modules/news/save_comment.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="post_id" value="<?php echo $fetch_content['post_id']; ?>" />
<input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>" />
<?php echo $admin->getFTAN(); ?>
<table class="row_a" cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td width="80"><?php echo $TEXT['TITLE']; ?>:</td>
	<td>
		<input type="text" name="title" value="<?php echo (htmlspecialchars($fetch_content['title'])); ?>" style="width: 98%;" maxlength="255" />
	</td>
</tr>
<tr>
	<td valign="top"><?php echo $TEXT['COMMENT']; ?>:</td>
	<td>
		<textarea name="comment" rows="10" cols="1" style="width: 98%; height: 150px;"><?php echo (htmlspecialchars($fetch_content['comment'])); ?></textarea>
	</td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php
			echo WB_URL; ?>/modules/news/modify_post.php?page_id=<?php
			echo $page_id; ?>&amp;section_id=<?php echo $section_id; ?>&amp;post_id=<?php
			echo $admin->getIDKEY($fetch_content['post_id']); ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>
</form>

<?php

// Print admin footer
$admin->print_footer();
