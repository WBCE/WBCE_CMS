<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');
?>

<h1><?=$TEXT['LOGIN']; ?></h1>
<?php if(isset($oLogin->message)) { ?>
 <p class="login-info"><?=$oLogin->message; ?></p><br />
<?php } ?>
 
<form class="login-box" action="<?=LOGIN_URL ?>" method="post" autocomplete="off">
    <input type="hidden" name="username_fieldname" value="<?=$sUsernameField; ?>" />
    <input type="hidden" name="password_fieldname" value="<?=$sPasswordField; ?>" />
    <input type="hidden" name="redirect" value="<?=$oLogin->redirect_url;?>" />
    <div class="cpForm">
        <div class="formRow">
            <label class="settingName" for="<?=$sUsernameField; ?>"><?=$TEXT['USERNAME']; ?></label>
            <div class="settingValue">
                <input type="text" id="<?=$sUsernameField; ?>" name="<?=$sUsernameField; ?>" />
            </div>
        </div>

        <div class="formRow">
            <label class="settingName" for="<?=$sPasswordField; ?>"><?=$TEXT['PASSWORD']; ?></label>
            <div class="settingValue">
                <input type="password" id="<?=$sPasswordField; ?>" name="<?=$sPasswordField; ?>" />
            </div>
        </div>

        <div class="buttonsRow">
            <input type="submit" name="submit" class="pos-right" value="<?=$TEXT['LOGIN']; ?>"  />
        </div>
    </div>
</form>
<p><a href="<?=FORGOT_URL ?>"><?=$TEXT['FORGOTTEN_DETAILS']; ?></a></p>
<script type="text/javascript">
	var ref = document.getElementById("<?=$sUsernameField; ?>");
	if (ref) ref.focus();
</script>