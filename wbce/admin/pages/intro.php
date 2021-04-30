<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Create new admin object
require('../../config.php');

require_once(WB_PATH . '/framework/class.admin.php');
$admin = new admin('Pages', 'pages_intro');
$content = '';

$filename = WB_PATH . PAGES_DIRECTORY . '/intro' . PAGE_EXTENSION;

if (file_exists($filename) && filesize($filename) > 0) {
    $content = file_get_contents($filename);
} else {
    $content = file_get_contents(ADMIN_PATH . '/pages/html.php');
}

include_once WB_PATH . '/include/editarea/wb_wrapper_edit_area.php';
echo registerEditArea('content', 'html', true, 'both', true, true, 600, 450, $toolbar = 'default');
function show_wysiwyg_editor($name, $id, $content, $width, $height)
{
    echo '<textarea name="' . $name . '" id="' . $id . '" style="width: ' . $width . '; height: ' . $height . ';">' . $content . '</textarea>';
}

?>
    <form action="intro2.php" method="post">
        <?php print $admin->getFTAN(); ?>
        <input type="hidden" name="page_id" value="{PAGE_ID}"/>
        <table cellpadding="0" cellspacing="0" border="0" class="form_submit">
            <tr>
                <td colspan="2">
                    <?php
                    show_wysiwyg_editor('content', 'content', $content, '100%', '500px');
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left">
                    <input type="submit" value="<?php echo $TEXT['SAVE']; ?>" class="submit"/>
                </td>
                <td class="right">
                    <input type="button" value="<?php echo $TEXT['CANCEL']; ?>"
                           onclick="javascript: window.location = 'index.php';" class="submit"/>
                </td>
            </tr>
        </table>

    </form>
<?php
// Print admin footer
$admin->print_footer();
