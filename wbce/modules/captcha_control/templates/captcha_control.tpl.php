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
?>

<h2><?php echo $MOD_CAPTCHA_CONTROL['HEADING'] ?></h2>
<p><?php echo $MOD_CAPTCHA_CONTROL['HOWTO'] ?></p>

<form name="store_settings" action="<?php echo $returnUrl; ?>" method="post">
    <?php echo $admin->getFTAN(); ?>
    <table width="98%" cellspacing="0" border="1" cellpadding="5px" class="row_a" >
    <tr><td colspan="2"><strong><?php echo $MOD_CAPTCHA_CONTROL['CAPTCHA_CONF'];?>:</strong></td></tr>
    <tr>
        <td width="30%" style="vertical-align:top; "><?php echo $MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE'];?>:</td>
        <td>
<?php FOREACH ($useable_captchas AS $key=>$text): ?> 
            <div style=""> 
                <img style="padding:0; margin:0;" width="120" height="40" border="0" src="<?php echo WB_URL.'/include/captcha/captchas/'.$key.'.png'?>" />
                <span style= "position:relative; top: -10px; ">
                    <input <?php if ($key==$captcha_type) echo ' checked="checked" '?> type="radio" id="captcha_type" name="captcha_type" value="<?php echo $key ?>" /><?php echo $text?>
                </span> 
            </div> 
<?php ENDFOREACH; ?>                
        </td>
    </tr>
  
    <tr id="text_qa" style="display:<?php if($captcha_type=='text') echo ''; else echo 'none'; ;?>;">
        <td valign="top" style="vertical-align:top; " class="setting_name"><?php echo $MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT'];?>:</td>
        <td class="setting_value" colspan="2">
            <textarea style="height: 300px;" name="text_qa" cols="60" rows="20"><?php echo $text_qa; ?></textarea>
        </td>
    </tr>
    <tr>
        <td><?php echo $MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA'];?>:</td>
        <td>
            <input type="radio" <?php echo ($enabled_captcha=='1') ?'checked="checked"' :'';?>
                name="enabled_captcha" value="1" /><?php echo $MOD_CAPTCHA_CONTROL['ENABLED'];?>
            <input type="radio" <?php echo ($enabled_captcha=='0') ?'checked="checked"' :'';?>
                name="enabled_captcha" value="0" /><?php echo $MOD_CAPTCHA_CONTROL['DISABLED'];?>
        </td>
    </tr>
    <tr><td>&nbsp;</td><td style="font-size:smaller;"><?php echo $MOD_CAPTCHA_CONTROL['CAPTCHA_EXP'];?></td></tr>
    <tr><td colspan="2"><br /><strong><?php echo $MOD_CAPTCHA_CONTROL['ASP_CONF'];?>:</strong></td></tr>
    <tr>
        <td><?php echo $MOD_CAPTCHA_CONTROL['ASP_TEXT'];?>:</td>
        <td>
            <input type="radio" <?php echo ($enabled_asp=='1') ?'checked="checked"' :'';?>
                name="enabled_asp" value="1" /><?php echo $MOD_CAPTCHA_CONTROL['ENABLED'];?>
            <input type="radio" <?php echo ($enabled_asp=='0') ?'checked="checked"' :'';?>
                name="enabled_asp" value="0" /><?php echo $MOD_CAPTCHA_CONTROL['DISABLED'];?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size:smaller;"><?php echo $MOD_CAPTCHA_CONTROL['ASP_EXP'];?></td>
    </tr>
    </table>
    <input type="submit" name="save_settings" style="margin-top:10px; width:140px;" value="<?php echo $TEXT['SAVE']; ?>" />
    <input type="submit" name="admin_tools" id="admin_tools" value="<?php echo $HEADING['ADMINISTRATION_TOOLS']; ?>" />
</form>
