 <br />
<form name="prompt" method="post" action="<?php echo $returnUrl; ?>">
  <input type="radio" name="tables" value="FILES" /><?php echo $MOD_BACKUP['BACKUP_ALL_FILES']; ?><br />
    <input type="radio" checked="checked" name="tables" value="ALL" /><?php echo $MOD_BACKUP['BACKUP_ALL_TABLES']; ?><br />
    <input type="radio" name="tables" value="WB" /><?php echo $MOD_BACKUP['BACKUP_WB_SPECIFIC']; ?><br /><br />
    <input type="checkbox" name="add_drop_table" value="1" id="add_drop_table" checked><label for='add_drop_table'><?php echo $MOD_BACKUP['ADD_DROP']; ?></label><br /><br />

    <input type="hidden" name="no_page" value="no_page" />
    <?php echo $admin->getFTAN(); ?>
    <input type="submit" name="backup" value="<?php echo $TEXT['BACKUP_DATABASE']; ?>" onclick="javascript: if(!confirm('<?php echo $MESSAGE['GENERIC']['PLEASE_BE_PATIENT']; ?>')) { return false; }" />
    <input style="float:right;" type="submit" name="admin_tools" id="admin_tools" value="<?php echo $HEADING['ADMINISTRATION_TOOLS']; ?>" />
</form>
