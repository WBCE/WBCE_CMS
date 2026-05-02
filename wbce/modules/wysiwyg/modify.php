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

$sMediaUrl = WB_URL . MEDIA_DIRECTORY;

// Get page content htmlspecialchars
$sql = 'SELECT `content` FROM `' . TABLE_PREFIX . 'mod_wysiwyg` WHERE `section_id`=' . (int) $section_id;
if (($content = $database->get_one($sql))) {
    $content = (str_replace('{SYSVAR:MEDIA_REL}', $sMediaUrl, $content));
    $content = htmlspecialchars($content);
} else {
    $content = '';
}

if (!isset($wysiwyg_editor_loaded)) {
    $wysiwyg_editor_loaded = true;
    
    if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR == "none" OR !file_exists(WB_PATH . '/modules/' . WYSIWYG_EDITOR . '/include.php')) {
        function show_wysiwyg_editor($name, $id, $content, $width, $height) {
            include_once WB_PATH . '/include/editarea/wb_wrapper_edit_area.php';
            echo registerEditArea ($name,'html',true,'both',true,true,600,450,$toolbar = 'default');
            echo '<textarea name="' . $name . '" id="' . $id . '" style="width: ' . $width . '; height: ' . $height . ';">' . $content . '</textarea>';
        }
    } else {
        $id_list = array();
        $sql = 'SELECT `section_id` FROM `' . TABLE_PREFIX . 'sections` ';
        $sql .= 'WHERE `page_id`=' . (int) $page_id . ' AND `module`=\'wysiwyg\'';
        if (($query_wysiwyg = $database->query($sql))) {
            while ($wysiwyg_section = $query_wysiwyg->fetchRow()) {
                $entry     = 'content' . $wysiwyg_section['section_id'];
                $id_list[] = $entry;
            }
            require(WB_PATH . '/modules/' . WYSIWYG_EDITOR . '/include.php');
        }
    }
}
?>

<form name="wysiwyg<?php echo $section_id; ?>" action="<?php echo WB_URL; ?>/modules/wysiwyg/save.php" method="post">
    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<?php
echo $admin->getFTAN() . "\n";
show_wysiwyg_editor('content' . $section_id, 'content' . $section_id, $content, '100%', '350');
?>
   <table style="padding-bottom: 10px; width: 100%;">
        <tr>
            <td style="text-align: left;margin-left: 1em;">
                <input name="modify" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="min-width: 100px; margin-top: 5px;" />
                <input name="pagetree" type="submit" value="<?php echo $TEXT['SAVE'] . ' &amp; ' . $TEXT['BACK'];?>" style="min-width: 100px; margin-top: 5px;" />
            </td>
            <td style="text-align: right;margin-right: 1em;">
                <input name="cancel" type="button" value="<?php echo $TEXT['CANCEL'];?>" onclick="javascript: window.location = 'index.php';" style="min-width: 100px; margin-top: 5px;" />
            </td>
        </tr>
    </table>
</form>
<br />