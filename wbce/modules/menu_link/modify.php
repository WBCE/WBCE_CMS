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
// Prevent this file from being access directly
defined('WB_PATH') or die('Cannot access this file directly');

// Load Language
Lang::loadLanguage();

include __DIR__ . '/functions.pageTree.php';

// get All the data of this MenuLink instance
$aData = $database->fetchRow(
    "SELECT 
        mml.*,                                              # all of `{TP}mod_menu_link`
        p.target                                            # target from `{TP}pages`
     FROM `{TP}mod_menu_link` mml
     INNER JOIN `{TP}pages` p ON p.page_id = mml.page_id
     WHERE mml.section_id = ?",
    [$section_id]
);

// Get list of all visible pages and build a page-tree
// get list of all page_ids and page_titles
global $aMenulinkTitles;
$aMenulinkTitles = array();
if ($query_page = $database->query("SELECT `page_id`, `menu_title` FROM `{TP}pages`")) {
    while ($page = $query_page->fetchRow(MYSQLI_ASSOC)) {
        $aMenulinkTitles[$page['page_id']] = $page['menu_title'];
    }
}

// Get list of targets
$aTargets = array();
$aLinks = pageTreeCombobox(nestedPagesArray(), $page_id);
#debug_dump($aLinks);
foreach ($aLinks as $p) {
    if ($query_section = $database->query(
            "SELECT `section_id`, `namesection` 
                FROM `{TP}sections` 
                WHERE `page_id` = ?
                    ORDER BY `position`",
            [$p['page_id']]
            )
    ) {
        while ($section = $query_section->fetchRow(MYSQLI_ASSOC)) {
            // get section-anchor
            if (defined('SEC_ANCHOR') && SEC_ANCHOR != '') {
                if (isset($section['namesection'])) {
                    $aTargets[$p['page_id']][] = "[#" . SEC_ANCHOR . $section['section_id'] . "]  " . $section['namesection'];
                    continue;
                }
                $aTargets[$p['page_id']][] = '[#' . SEC_ANCHOR . $section['section_id'] . '] ';
            } else {
                $aTargets[$p['page_id']] = array();
            }
        }
    }
}
?>
<form name="menulink" action="<?= WB_URL ?>/modules/menu_link/save.php" method="post">
    <input type="hidden" name="page_id" value="<?= $page_id ?>" />
    <input type="hidden" name="section_id" value="<?= $section_id ?>" />
<?= $admin->getFTAN(); ?>
    <table cellpadding="0" cellspacing="0" border="0" class="menuLinkTable">
        <tr>
            <th><?= $TEXT['LINK'] . '-' . $TEXT['TYPE'] ?></th>
            <td>
                <input id="external_link" type="radio" name="linktype" value="ext" <?= ($aData['target_page_id'] == '-1') ? 'checked' : '' ?> /><label for="external_link"><?= $MOD_MENU_LINK['EXTERNAL_LINK']; ?></label>
                <input id="internal_link" type="radio" name="linktype" value="int" <?= ($aData['target_page_id'] == '-1') ? '' : 'checked' ?> /><label for="internal_link"><?= $MOD_MENU_LINK['INTERNAL_LINK']; ?></label>
            </td>
        </tr>
        <tr id="page_link_selection">
            <th><?= $TEXT['PAGE'] ?></th>
            <td><?php
    $sel = ' selected="selected"';
?>
                <select class="menuLink" name="menu_link" id="menu_link"  style="font-weight:bold;font-size:15px; min-width:350px;" style="width:250px;" >
                    <option value="0"<?= $aData['target_page_id'] == '0' ? $sel : '' ?>><?= $TEXT['PLEASE_SELECT']; ?> &hellip;</option>
                <?php
                foreach ($aLinks as $p) {
                    ?>
                        <option style="font-size:15px" value="<?= $p['page_id'] ?>" title="<?= $p['page_title'] ?>"
                        <?= ($p['page_id'] == $aData['target_page_id']) ? ' selected' : '' ?>
                        <?= ($p['page_id'] == $page_id) ? ' disabled' : '' ?>	data-right="<?= $p['page_id'] ?>" data-title="<?= $p['page_title'] ?>"
                                ><?= $p['menu_title'] ?></option>
                        <?php
                            }
                            ?>
                </select>
                &nbsp;
            </td>
        </tr>
        <tr id="external">
            <th>URL:</th>
            <td>
                <input type="text" name="extern" id="extern" value="<?= $aData['extern']; ?>" style="width:80%;" <?php
                            if ($aData['target_page_id'] != '-1') {
                                echo 'disabled="disabled"';
                            }
                            ?> />
            </td>
        </tr>
        <tr id="sec_anchor">
            <th><?= $TEXT['ANCHOR'] ?></th>
            <td>
                <input type="hidden" name="anchor" value="0">
                <select class="menuLink" name="anchor" id="page_target" style="width:350px;" >
                    <?php
                    $sAnchor = $aData['anchor'] == '0' ? ' ' : '[#' . $aData['anchor'] . ']';
                    if ((SEC_ANCHOR != "") && (strpos($aData['anchor'], SEC_ANCHOR) !== false)) {
                        $aTmp1 = explode(SEC_ANCHOR, $aData['anchor']);
                        $iSectionID = $aTmp1[1];
                        if ($sNameSection = $database->fetchValue(
                                "SELECT `namesection` 
                                    FROM `{TP}sections` 
                                    WHERE `section_id`= ?",
                                [$iSectionID]
                                )
                        ) {
                            $sAnchor = $sAnchor . ' ' . $sNameSection;
                        }
                    }
                    ?>
                    <option value="<?= $aData['anchor'] ?>" selected="selected"><?= $sAnchor ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><?= $TEXT['TARGET'] ?></th>            
            <td>
                <?php 
                $options = [
                    '_blank' => ($TEXT['NEW_WINDOW'] ?? 'New Window') . ' (_blank)',
                    '_self'  => ($TEXT['SAME_WINDOW'] ?? 'Same Window') . ' (_self)',
                    '_top'   => ($TEXT['TOP_FRAME'] ?? 'Top Frame') . ' (_top)',
                ];
                renderSelect('target', $aData['target'] ?? '', $options);
                ?>
            </td>
        </tr>
        <tr>
            <th><?= $MOD_MENU_LINK['R_TYPE'] ?></th>
            <td>
                <?php
                    $options = [
                        '301' => '301 (Moved Permanently)',
                        '302' => '302 (Found / Temporary Redirect)',
                        '200' => '200 (OK - No Redirect)',
                    ];
                    renderSelect('r_type', $aData['redirect_type'] ?? '', $options);
                ?>
            </td>
        </tr>
    </table>
    <br />
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td align="left">
                <input type="submit" value="<?= $TEXT['SAVE'] ?>" class="button ico-save" style="width: 100px; margin-top: 5px;" />
            </td>
            <td align="right">
                <input type="reset" value="<?= $TEXT['RESET'] ?>" class="button ico-reset"  style="width: 100px; margin-top: 5px;" />
                <input type="button" value="<?= $TEXT['CANCEL'] ?>" class="button ico-cancel"  onclick="javascript: window.location = 'index.php';" style="width: 100px; margin-top: 5px;" />
            </td>
        </tr>
    </table>
</form>

<?php
function renderSelect($name, $currentValue, $options, $class = 'menuLink', $style = 'width:350px;')
{
    echo '<select class="' . htmlspecialchars($class) . '" name="' . htmlspecialchars($name) . '" style="' . htmlspecialchars($style) . '">';
    
    foreach ($options as $value => $label) {
        $selected = ($currentValue ?? '') === (string)$value ? ' selected="selected"' : '';
        echo '<option value="' . htmlspecialchars($value) . '"' . $selected . '>';
        echo htmlspecialchars($label);
        echo '</option>';
    }
    
    echo '</select>';
}



$sModDirUrl = str_replace(WB_PATH, WB_URL, __DIR__);
?>
<script src="<?= $sModDirUrl ?>/selectator/fm.selectator.jquery.min.js"></script>
<script type="text/javascript">
$(function () {
    $('#menu_link').selectator({
        labels: {
            search: '<?= $TEXT['SEARCH'] ?> ... [<?= $TEXT['PAGE'] ?>-ID, <?= $TEXT['MENU_TITLE'] ?>]'
        },
        searchFields: 'value text subtitle right',
    });

    var countries = {
<?php
foreach ($aLinks as $p) {
    $sToJS = "\t\t'{$p['page_id']}':{ '{$TEXT['PLEASE_SELECT']} ...':'0',";

    if (is_array($aTargets) && is_array($aTargets[$p['page_id']])) {
        foreach ($aTargets[$p['page_id']] as $value) {
            $aTmp1 = explode('[#' . SEC_ANCHOR, $value);
            $aTmp2 = explode(']', $aTmp1[1]);
            $sAnchor = SEC_ANCHOR . $aTmp2[0];
            $sToJS .= "'" . addslashes($value) . "':";
            $sToJS .= "'$sAnchor',";
        }
        $sToJS = rtrim($sToJS, ',');
        $sToJS .= "}," . PHP_EOL;
    }
    $sToJS .= "\t\t";
    echo $sToJS;
}
?>
    };

    var $locations = $('#page_target');

    $('#menu_link').change(function () {
        var country = $(this).val(), locs = countries[country] || [];

        var html = $.map(locs, function (id, name) {
            return '<option value="' + id + '"' + (id == "<?= $aData['anchor'] ?>" ? "selected" : "") + '>' + name + '</option>'
        }).join('');
        $locations.html(html);
    });

    $('#menu_link').trigger("change");
    $('.selectator_value_<?= $page_id ?>').addClass('disabled-selection');

    $('#page_link_selection').hide();
    $('#sec_anchor').hide();
    $('#external').hide();
    $('input:radio[name="linktype"]').change(function () {
        if ($(this).is(':checked') && $(this).val() == 'int') {
            $('#sec_anchor').show();
            $('#page_link_selection').show();
            $('#external').hide();
            $('#extern').prop('disabled', false);
        } else {
            $('#sec_anchor').hide();
            $('#page_link_selection').hide();
            $('#external').show();
            $('#extern').prop('disabled', false);
        }
    });

    $('input:radio[name="linktype"]').trigger('change');
});
</script>
