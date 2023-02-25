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



require_once(WB_PATH.'/modules/jsadmin/jsadmin.php');

// Check if user selected what add-ons to reload
if(isset($_POST['save_settings']))  {

	if (!$admin->checkFTAN())
	{
		$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$_SERVER['REQUEST_URI']);
	}

	// Include functions file
	require_once(WB_PATH.'/framework/functions.php');
	save_setting('mod_jsadmin_persist_order', (isset($_POST['persist_order'])) ? 1 : 0);
	save_setting('mod_jsadmin_ajax_order_pages', (isset($_POST['ajax_order_pages'])) ? 1 : 0);
	save_setting('mod_jsadmin_ajax_order_sections', (isset($_POST['ajax_order_sections'])) ? 1 : 0);
	// check if there is a database error, otherwise say successful

	if($database->is_error()) {
		$admin->print_error($database->get_error(), $js_back);
	} else {
		$admin->print_success($MESSAGE['PAGES_SAVED'], ADMIN_URL.'/admintools/tool.php?tool=jsadmin');
	}

} else {


    // Display form
            $persist_order = get_setting('mod_jsadmin_persist_order', true) ? 'checked="checked"' : '';
            $ajax_order_pages = get_setting('mod_jsadmin_ajax_order_pages', true) ? 'checked="checked"' : '';
            $ajax_order_sections = get_setting('mod_jsadmin_ajax_order_sections', true) ? 'checked="checked"' : '';
    ?>

    <form id="jsadmin_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <?php echo $admin->getFTAN(); ?>
        <table cellpadding="4" cellspacing="0" border="0">
            <tr>
                <td colspan="2"><?php echo $MOD_JSADMIN['TXT_HEADING_B']; ?>:</td>
            </tr>
            <tr>
                <td width="20"><input type="checkbox" name="persist_order" id="persist_order" value="true" <?php echo $persist_order; ?>/></td>
                <td><label for="persist_order"><?php echo $MOD_JSADMIN['TXT_PERSIST_ORDER_B']; ?></label></td>
            </tr>
            <tr>
                <td width="20"><input type="checkbox" name="ajax_order_pages" id="ajax_order_pages" value="true" <?php echo $ajax_order_pages; ?>/></td>
                <td><label for="ajax_order_pages"><?php echo $MOD_JSADMIN['TXT_AJAX_ORDER_PAGES_B']; ?></label></td>
            </tr>
            <tr>
                <td width="20"><input type="checkbox" name="ajax_order_sections" id="ajax_order_sections" value="true" <?php echo $ajax_order_sections; ?>/></td>
                <td><label for="ajax_order_sections"><?php echo $MOD_JSADMIN['TXT_AJAX_ORDER_SECTIONS_B']; ?></label></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                      <input type="submit" name="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
                </td>
            </tr>
        </table>
    </form>
    <?php
}
