<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');

$oMsgBox = new MessageBox();
?>

<h1><?=$MENU['FORGOT']; ?></h1>
<br />
<?php $oMsgBox->display(); ?>
<?php if(!isset($display_form) OR $display_form != false) { ?>
    <form  class="login-box" name="forgot_pass" action="<?=FORGOT_URL ?>" method="post">
        <div class="cpForm">
            <div class="formRow">
                <label class="settingName" for="display_name"><?=$TEXT['EMAIL']; ?></label>
                <div class="settingValue">
                    <input type="text" maxlength="255" name="email" id="email" value="<?=$email; ?>" />
                </div>
            </div>

            <div class="buttonsRow">
                <input type="submit" name="submit" class="pos-right" value="<?=$TEXT['SEND_DETAILS']; ?>" />
            </div>		
        </div>		
    </form>
<?php } ?>
<p><a href="<?=LOGIN_URL ?>"><?=$TEXT['NEED_TO_LOGIN']; ?></a></p>
<script type="text/javascript">
	var ref = document.getElementById("email");
	if (ref) ref.focus();
</script>


