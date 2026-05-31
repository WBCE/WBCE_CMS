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

$raw = $database->fetchValue(
    'SELECT `content` FROM `{TP}mod_wysiwyg` WHERE `section_id` = ?',
    [(int) $section_id]
) ?? '';

$content = htmlspecialchars(str_replace('{SYSVAR:MEDIA_REL}', $sMediaUrl, $raw));

if (!isset($wysiwyg_editor_loaded)) {
    $wysiwyg_editor_loaded = true;

    if (!defined('WYSIWYG_EDITOR') || WYSIWYG_EDITOR === 'none'
        || !file_exists(WB_PATH . '/modules/' . WYSIWYG_EDITOR . '/include.php')) {

        function show_wysiwyg_editor($name, $id, $content, $width, $height) {
            include_once WB_PATH . '/include/editarea/wb_wrapper_edit_area.php';
            echo registerEditArea($name, 'html', true, 'both', true, true, 600, 450, 'default');
            echo '<textarea name="' . $name . '" id="' . $id
               . '" style="width:' . $width . ';height:' . $height . ';">'
               . $content . '</textarea>';
        }

    } else {
        $id_list = $database->fetchAll(
            "SELECT `section_id`
                FROM `{TP}sections`
             WHERE `page_id` = ? AND `module` = 'wysiwyg'",
            [(int) $page_id]
        );
        $id_list = array_map(fn($r) => 'content' . $r['section_id'], $id_list);

        require WB_PATH . '/modules/' . WYSIWYG_EDITOR . '/include.php';
    }
}

// Ensure window.showToast() is available for AJAX toast feedback
Alerts::ensureToastAssets();
// Load AjaxSave JS once (I:: deduplicates across multiple sections on the same page)
I::insertJsFile(WB_URL . '/modules/wysiwyg/ajax_save.js', 'BODY BTM-');
?>
<form name="wysiwyg<?= $section_id ?>"
      id="wysiwyg_form_<?= $section_id ?>"
      action="<?= WB_URL ?>/modules/wysiwyg/save.php"
      method="post"
      data-ajax-url="<?= WB_URL ?>/modules/wysiwyg/ajax_save.php">
    <input type="hidden" name="page_id"    value="<?= $page_id ?>">
    <input type="hidden" name="section_id" value="<?= $section_id ?>">
    <?= $admin->getFTAN() ?>
    <input type="hidden" name="idKey" value="<?= $admin->getIDKEY($section_id) ?>">
    <?php show_wysiwyg_editor('content' . $section_id, 'content' . $section_id, $content, '100%', '350') ?>
    <table style="padding-bottom:10px;width:100%">
        <tr>
            <td style="text-align:left;margin-left:1em">
                <input name="modify"   type="submit" value="<?= $TEXT['SAVE'] ?>" style="min-width:100px;margin-top:5px">
                <input name="pagetree" type="submit" id="wysiwyg_saveback_<?= $section_id ?>"
                       value="<?= $TEXT['SAVE'] . ' &amp; ' . $TEXT['BACK'] ?>" style="min-width:100px;margin-top:5px">
                <label id="wysiwyg_ajaxlabel_<?= $section_id ?>"
                       style="margin-left:1.2em;cursor:pointer;user-select:none"
                       title="Save without page reload (Ctrl+S)">
                    <input type="checkbox" id="wysiwyg_ajaxsave_<?= $section_id ?>"
                           style="vertical-align:middle;margin-right:3px">AjaxSave
                </label>
            </td>
            <td style="text-align:right;margin-right:1em">
                <input name="cancel" type="button" value="<?= $TEXT['CANCEL'] ?>"
                       onclick="window.location='index.php'" style="min-width:100px;margin-top:5px">
            </td>
        </tr>
    </table>
</form>
