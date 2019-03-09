<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright       Ryan Djurovich (2004-2009)
 * @copyright       WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2019)
 * @category        tool
 * @package         OPF E-Mail
 * @version         1.0.10
 * @authors         Martin Hecht (mrbaseman)
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
<form name="store_settings" action="<?php echo $returnUrl; ?>" method="post">
    <?php echo $admin->getFTAN(); ?>
    <input type="hidden" name="action" value="save" />
    <table width="98%" cellspacing="0" cellpadding="5px" class="row_a">

        <tr>
            <td width="35%"><?php echo $OPF['EMAIL_FILTER'];?>:</td>
            <td>
                <input type="radio" <?php echo ($data['email_filter']=='1') ?'checked="checked"' :'';?>
                    name="email_filter" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['email_filter'])=='0') ?'checked="checked"' :'';?>
                    name="email_filter" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td><?php echo $OPF['MAILTO_FILTER'];?>:</td>
            <td>
                <input type="radio" <?php echo ($data['mailto_filter']=='1') ?'checked="checked"' :'';?>
                    name="mailto_filter" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['mailto_filter'])=='0') ?'checked="checked"' :'';?>
                    name="mailto_filter" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>
        <tr>
            <td width="35%"><?php echo $OPF['JS_MAILTO']?>:</td>
            <td>
                <input type="radio" <?php echo ($data['js_mailto']=='1') ?'checked="checked"' :'';?>
                    name="js_mailto" value="1"><?php echo $OPF['ENABLED'];?>
                <input type="radio" <?php echo (($data['js_mailto'])=='0') ?'checked="checked"' :'';?>
                    name="js_mailto" value="0"><?php echo $OPF['DISABLED'];?>
            </td>
        </tr>

        <!-- REPLACEMENTS -->

        <tr><td colspan="2"><br /><strong><?php echo $OPF['REPLACEMENT_CONF'];?>:</strong></td></tr>
        <tr>
            <td><?php echo $OPF['AT_REPLACEMENT'];?>:</td>
            <td><input type="text" style="width: 160px" value="<?php echo $data['at_replacement'];?>"
                name="at_replacement"/></td>
        </tr>
        <tr>
            <td><?php echo $OPF['DOT_REPLACEMENT'];?>:</td>
            <td><input type="text" style="width: 160px" value="<?php echo $data['dot_replacement'];?>"
                name="dot_replacement"/></td>
        </tr>
    </table>
    <input type="submit" style="margin-top:10px; width:140px;" value="<?php echo $TEXT['SAVE']; ?>" />
</form>

