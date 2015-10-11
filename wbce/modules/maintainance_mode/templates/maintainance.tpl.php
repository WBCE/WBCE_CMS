<?/*
For automated detection of sent form the submit button needs to have 
name+id ="save_settings". (Optional ($_POST['action']) == 'save')
For return to admintools the responsible butten mus have name+id="admin_tools".

$returnUrl      is used as form Action it sends the form to itself(apeform)
*/?>

    <div class="maintMode">
        <h2><?php echo $MOD_MAINTAINANCE['HEADER']; ?></h2>
        <form id="maintainance_mode_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?php echo $returnUrl; ?>" method="post">
            <?php echo $admin->getFTAN(); ?>
            <table cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td colspan="2"><?php echo $MOD_MAINTAINANCE['DESCRIPTION']; ?>:</td>
                </tr>
                <tr>
                    <td width="20"><input type="checkbox" name="maintMode" id="maintMode" value="true" <?php echo $maintMode ?>/></td>
                    <td><label for="maintMode"><?php echo $MOD_MAINTAINANCE['CHECKBOX']; ?></label></td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="save_settings" id="save_settings" value="<?php echo $TEXT['SAVE']; ?>" />
                    </td>
                    <td class="tdright">
                       <input type="submit" name="admin_tools" id="admin_tools" value="<?php echo $HEADING['ADMINISTRATION_TOOLS']; ?>" />
                    </td>
                </tr>
            </table>
        </form>
    </div><!-- maintMode -->
