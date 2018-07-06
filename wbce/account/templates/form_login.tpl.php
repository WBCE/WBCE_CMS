<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
loadCssFile(WB_URL.'/account/templates/forms.css', 'HEAD TOP+');
?>

<h1><?=$TEXT['LOGIN']; ?></h1>
<p class="login-info">
<?php 
  if(isset($thisApp->message)) {
    echo $thisApp->message; 
  }
?>
</p>
<form class="login-box" action="<?=WB_URL ?>/account/login.php" method="post" autocomplete="off">
	<input type="hidden" name="username_fieldname" value="<?=$sUsernameField; ?>" />
	<input type="hidden" name="password_fieldname" value="<?=$sPasswordField; ?>" />
	<input type="hidden" name="redirect" value="<?=$thisApp->redirect_url;?>" />
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
			<input type="submit" name="submit" value="<?=$TEXT['LOGIN']; ?>"  />
		</div>
	</div>
</form>
<p><a href="<?=WB_URL; ?>/account/forgot.php"><?=$TEXT['FORGOTTEN_DETAILS']; ?></a></p>
<script type="text/javascript">
	var ref = document.getElementById("<?=$sUsernameField; ?>");
	if (ref) ref.focus();
</script>