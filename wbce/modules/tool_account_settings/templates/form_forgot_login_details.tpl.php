<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');

if( ($errMsg=='') && ($message != '')) {
	// $message = $MESSAGE['FORGOT_PASS_NO_DATA'];
	$message_class = '';
} else {
	$message = $errMsg;
	$message_class = 'alert alert-warning';
}
?>

<h1><?=$MENU['FORGOT']; ?></h1>
<br />
<div class="<?=$message_class; ?>"><?=$message; ?>.</div>

<br />			

<?php if(!isset($display_form) OR $display_form != false) { ?>
    <form  class="login-box" name="forgot_pass" action="<?=WB_URL.'/account/forgot.php'; ?>" method="post">
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
<p><a href="<?=WB_URL; ?>/account/login.php"><?=$TEXT['NEED_TO_LOGIN']; ?></a></p>
<script type="text/javascript">
	var ref = document.getElementById("email");
	if (ref) ref.focus();
</script>


