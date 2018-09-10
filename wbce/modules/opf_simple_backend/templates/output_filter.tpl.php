<?php
/**
 *
 * @category        backend,hidden
 * @package         OpF Simple Backend
 * @version         1.3.0
 * @authors         Martin Hecht (mrbaseman)
 * @copyright       (c) 2018, Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


if ( $msgTxt != '') :?>
<div class="<?php echo $msgCls?>"><?php echo $msgTxt?></div>
<?php endif; ?>
<h2><?php echo $OPF['HEADING']; ?></h2>
<p><?php echo $OPF['HOWTO']; ?></p>
<form name="store_settings" action="<?php  echo $returnUrl; ?>" method="post">
    <?php echo $admin->getFTAN(); ?>
    <input type="hidden" name="action" value="save" />
    <table width="98%" cellspacing="0" cellpadding="5px" class="row_a">

        <!-- FRONTEND -->

        <tr><td colspan="2"><br /><strong><?php echo $TEXT['FRONTEND'];?>:</strong></td></tr>
        <tr>
            <td width="35%"><?php echo $OPF['DROPLETS']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['droplets']=='1') ? 'checked="checked"' :'';?>
                    name="droplets" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['droplets'])=='0') ? 'checked="checked"' :'';?>
                    name="droplets" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['AUTO_PLACEHOLDER']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['auto_placeholder']=='1') ? 'checked="checked"' :'';?>
                    name="auto_placeholder" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['auto_placeholder'])=='0') ? 'checked="checked"' :'';?>
                    name="auto_placeholder" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['MOVE_STUFF']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['move_stuff']=='1') ? 'checked="checked"' :'';?>
                    name="move_stuff" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['move_stuff'])=='0') ? 'checked="checked"' :'';?>
                    name="move_stuff" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['REPLACE_STUFF']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['replace_stuff']=='1') ? 'checked="checked"' :'';?>
                    name="replace_stuff" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['replace_stuff'])=='0') ? 'checked="checked"' :'';?>
                    name="replace_stuff" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['CSS_TO_HEAD']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['css_to_head']=='1') ?'checked="checked"' :'';?>
                    name="css_to_head" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['css_to_head'])=='0') ?'checked="checked"' :'';?>
                    name="css_to_head" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>

        <tr>
            <td width="35%"><?php echo $OPF['WBLINK']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['wblink']=='1') ? 'checked="checked"' :'';?>
                    name="wblink" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['wblink'])=='0') ? 'checked="checked"' :'';?>
                    name="wblink" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <!--<tr>
            <td width="35%"><?php echo $OPF['SHORT_URL']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['short_url']=='1') ?'checked="checked"' :'';?>
                    name="short_url" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['short_url'])=='0') ?'checked="checked"' :'';?>
                    name="short_url" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>-->
        <tr>
            <td width="35%"><?php echo $OPF['SYS_REL'];?>:</td>
            <td>
                <input type="radio" <?php echo ($data['sys_rel']=='1') ? 'checked="checked"' :'';?>
                    name="sys_rel" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['sys_rel'])=='0') ? 'checked="checked"' :'';?>
                    name="sys_rel" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['REMOVE_SYSTEM_PH'];?>:</td>
            <td>
                <input type="radio" <?php echo ($data['remove_system_ph']=='1') ? 'checked="checked"' :'';?>
                    name="remove_system_ph" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['remove_system_ph'])=='0') ? 'checked="checked"' :'';?>
                    name="remove_system_ph" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>


        <!-- BACKEND -->

        <tr><td colspan="2"><br /><strong><?php echo $TEXT['BACKEND'];?>:</strong></td></tr>
         <tr>
            <td width="35%"><?php echo $OPF['DROPLETS']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['droplets_be']=='1') ? 'checked="checked"' :'';?>
                    name="droplets_be" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['droplets_be'])=='0') ? 'checked="checked"' :'';?>
                    name="droplets_be" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['AUTO_PLACEHOLDER']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['auto_placeholder_be']=='1') ? 'checked="checked"' :'';?>
                    name="auto_placeholder_be" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['auto_placeholder_be'])=='0') ? 'checked="checked"' :'';?>
                    name="auto_placeholder_be" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['MOVE_STUFF']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['move_stuff_be']=='1') ? 'checked="checked"' :'';?>
                    name="move_stuff_be" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['move_stuff_be'])=='0') ? 'checked="checked"' :'';?>
                    name="move_stuff_be" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['REPLACE_STUFF']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['replace_stuff_be']=='1') ? 'checked="checked"' :'';?>
                    name="replace_stuff_be" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['replace_stuff_be'])=='0') ? 'checked="checked"' :'';?>
                    name="replace_stuff_be" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['REMOVE_SYSTEM_PH'];?>:</td>
            <td>
                <input type="radio" <?php echo ($data['remove_system_ph_be']=='1') ? 'checked="checked"' :'';?>
                    name="remove_system_ph_be" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['remove_system_ph_be'])=='0') ? 'checked="checked"' :'';?>
                    name="remove_system_ph_be" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>


        <!-- Email REPLACEMENTS -->

        <tr><td colspan="2"><br /><a href="<?php echo ADMIN_URL.'/admintools/tool.php?tool=mod_opf_email';?>"><?php echo $OPF['REPLACEMENT_CONF'];?></a></td></tr>

        <!-- ADVANCED BACKEND VIEW -->

        <tr><td colspan="2"><br /><strong><?php echo $OPF['BASIC_CONF'];?>:</strong></td></tr>
        <tr >
            <td width="35%"><?php echo $TEXT['SHOW_ADVANCED']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['show_advanced_backend']=='1') ?'checked="checked"' :'';?>
                    name="show_advanced_backend" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['show_advanced_backend'])=='0') ?'checked="checked"' :'';?>
                    name="show_advanced_backend" value="0"><?php echo $OPF['DISABLED'];?>

            </td>
        </tr>
        <tr><td colspan="2" style="border-top: 1px dotted black !important; "></td></tr>


    </table>
    <input type="submit" style="margin-top:10px; width:140px;" value="<?php echo $TEXT['SAVE']; ?>" />
</form>

