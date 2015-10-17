<?php
/**
 * @category        modules
 * @package         Captcha Control
 * @author          WBCE Project
 * @copyright       Luise Hahne, Norbert Heimsath
 * @license         GPLv2 or any later
 */

?>

// script to load image
<script type="text/javascript">
    var pics = new Array();

    pics["ttf_image"] = new Image();
    pics["ttf_image"].src = "<?php echo WB_URL.'/include/captcha/captchas/ttf_image.png'?>";

    pics["calc_image"] = new Image();
    pics["calc_image"].src = "<?php echo WB_URL.'/include/captcha/captchas/calc_image.png'?>";

    pics["calc_ttf_image"] = new Image();
    pics["calc_ttf_image"].src = "<?php echo WB_URL.'/include/captcha/captchas/calc_ttf_image.png'?>";

    pics["old_image"] = new Image();
    pics["old_image"].src = "<?php echo WB_URL.'/include/captcha/captchas/old_image.png'?>";
    
    pics["calc_text"] = new Image();
    pics["calc_text"].src = "<?php echo WB_URL.'/include/captcha/captchas/calc_text.png'?>";
    
    pics["text"] = new Image();
    pics["text"].src = "<?php echo WB_URL.'/include/captcha/captchas/text.png'?>";

    function load_captcha_image() {
        document.captcha_example.src = pics[document.store_settings.captcha_type.value].src;
        toggle_text_qa();
    }
    
    function toggle_text_qa() {
        if(document.store_settings.captcha_type.value == 'text' ) {
            document.getElementById('text_qa').style.display = '';
        } else {
            document.getElementById('text_qa').style.display = 'none';
        }
    }

</script>


<h2><?php echo $MOD_CAPTCHA_CONTROL['HEADING'] ?></h2>
<p><?php echo $MOD_CAPTCHA_CONTROL['HOWTO'] ?></p>

<form name="store_settings" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <?php echo $admin->getFTAN(); ?>
    <table width="98%" cellspacing="0" border="0" cellpadding="5px" class="row_a">
    <tr><td colspan="2"><strong><?php echo $MOD_CAPTCHA_CONTROL['CAPTCHA_CONF'];?>:</strong></td></tr>
    <tr>
        <td width="30%"><?php echo $MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE'];?>:</td>
        <td>
        <select name="captcha_type" id="captcha_type" onchange="load_captcha_image()" style="width: 98%;">
            <?php foreach($useable_captchas AS $key=>$text) {
            echo "<option value=\"$key\" ".($captcha_type==$key ? ' selected="selected"' : '').">$text</option>";
            } ?>
        </select>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td align="left" width="150px">
            <img alt="captcha_example" id="captcha_example" src="<?php echo WB_URL.'/include/captcha/captchas/'.$captcha_type.'.png'?>" />
        </td>
    </tr>
    <tr id="text_qa" style="display:<?php if($captcha_type=='text') echo ''; else echo 'none'; ;?>;">
        <td valign="top" class="setting_name"><?php echo $MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT'];?>:</td>
        <td class="setting_value" colspan="2">
            <textarea name="text_qa" cols="60" rows="10"><?php echo $text_qa; ?></textarea>
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
</form>

