<?php if ( $msgTxt != '') :?>
<div class="<?php echo $msgCls?>"><?php echo $msgTxt?></div>
<?php endif; ?>
<form name="store_settings" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
    <?php echo $admin->getFTAN(); ?>
    <input type="hidden" name="action" value="save" />
    <table width="98%" cellspacing="0" cellpadding="5px" class="row_a">
    
        <!-- ALL OLD FILTER -->
        
        <tr><td colspan="2"><strong><?php echo $OPF['BASIC_CONF'];?>:</strong></td></tr>
        <tr >
            <td width="35%"><?php echo $TEXT['SHOW_ADVANCED']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['show_advanced_backend']=='1') ?'checked="checked"' :'';?>
                    name="show_advanced_backend" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['show_advanced_backend'])=='0') ?'checked="checked"' :'';?>
                    name="show_advanced_backend" value="0"><?php echo $OPF['DISABLED'];?>
                    
            </td>
        </tr>
    </table>
    <input type="submit" style="margin-top:10px; width:140px;" value="<?php echo $TEXT['SAVE']; ?>" />
</form>

