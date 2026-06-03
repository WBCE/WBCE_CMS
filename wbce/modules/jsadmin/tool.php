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

// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

require_once WB_PATH.'/modules/jsadmin/jsadmin.php';

$returnUrl = ADMIN_URL . '/admintools/tool.php?tool=jsadmin';

if (isset($_POST['save_settings'])) {

    if (!$admin->checkFTAN()) {
        (new Alerts())->sessionToast($MESSAGE['GENERIC_SECURITY_ACCESS'], 'error');
        header('Location: ' . $returnUrl);
        exit;
    }

    $settings = [
        'mod_jsadmin_persist_order'       => isset($_POST['persist_order'])       ? 1 : 0,
        'mod_jsadmin_ajax_order_pages'    => isset($_POST['ajax_order_pages'])    ? 1 : 0,
        'mod_jsadmin_ajax_order_sections' => isset($_POST['ajax_order_sections']) ? 1 : 0,
    ];
    foreach ($settings as $name => $value) {
        save_setting($name, $value);
        if ($database->hasError()) {
            (new Alerts())->sessionToast($database->getError(), 'error');
            header('Location: ' . $returnToTools);
            exit;
        }
    }
    (new Alerts())->sessionToast('MESSAGE:CHANGES_SAVE_SUCCESS', 'success');
    header('Location: ' . $returnUrl);
    exit;

} else {


    // Display form
        $persist_order       = (bool) get_setting('mod_jsadmin_persist_order',       true);
        $ajax_order_pages    = (bool) get_setting('mod_jsadmin_ajax_order_pages',    true);
        $ajax_order_sections = (bool) get_setting('mod_jsadmin_ajax_order_sections', true);
    ?>

    <form id="jsadmin_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?=$_SERVER['REQUEST_URI']; ?>" method="post">
        <?=$admin->getFTAN(); ?>

        <div class="cp-settings">
        <div class="cp-toggle-section">
            <div class="cp-card-head">
                <label><i class="fa fa-code"></i> <?=$module_name?></label>
            </div>
            <blockquote><b><?=$TXT['HEADING']; ?>:</b></blockquote>
            <section id="settings">

        <div class="cp-setting-row">
            <div class="cp-setting-name">
                <input type="checkbox" switch="bool" name="ajax_order_pages" id="ajax_order_pages" value="1" <?= $ajax_order_pages ? 'checked' : '' ?>>
                <label for="ajax_order_pages" class="labeled" data-on-label="<?=$TEXT['ENABLED']?>" data-off-label="<?=$TEXT['DISABLED']?>"></label>
            </div>
            <div class="cp-setting-value">
                <?=$TXT['DND']. ' ' . $TXT['PAGES'] ; ?>
            </div>
        </div>
        <div class="cp-setting-row">
            <div class="cp-setting-name">
                <input type="checkbox" switch="bool" name="ajax_order_sections" id="ajax_order_sections" value="1" <?= $ajax_order_sections ? 'checked' : '' ?>>
                <label for="ajax_order_sections" class="labeled" data-on-label="<?=$TEXT['ENABLED']?>" data-off-label="<?=$TEXT['DISABLED']?>"></label>
            </div>
            <div class="cp-setting-value">
                <?=$TXT['DND']. ' ' . $TXT['SECTIONS'] ; ?>
            </div>
        </div>
        <div class="cp-setting-row">
            <div class="cp-setting-name">
                <input type="checkbox" switch="bool" name="persist_order" id="persist_order" value="1" <?= $persist_order ? 'checked' : '' ?>>
                <label for="persist_order" class="labeled" data-on-label="<?=$TEXT['ENABLED']?>" data-off-label="<?=$TEXT['DISABLED']?>"></label>
            </div>
            <div class="cp-setting-value">
                <?=$TXT['REMEMBER_EXPAND'] ?>
            </div>
        </div>
                <div class="cp-buttons-row">
                    <a href="<?=$returnToTools?>" class="button">« Zurück</a>
                    <button type="submit" name="save_settings" class="button ico-save pos-right">
                        <?=$TEXT['SAVE']; ?>
                    </button>
                </div>
            </section>
        </div>
        </div>
    </form>
    <?php
}
