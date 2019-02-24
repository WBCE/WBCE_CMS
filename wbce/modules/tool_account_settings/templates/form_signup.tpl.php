<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');
?>


<h1><?=$TEXT['SIGNUP']; ?></h1>
<?php if(empty($errors) == false){ ?>
<div class="alert alert-danger">
	<?php foreach($errors as $k=>$error_message){ ?>
		<p id="<?=$k?>"><?=$error_message?></p>
	<?php 
	} 
	?>
</div>
<?php 
} 
?>
<div class="cpForm">
	<form name="user" action="<?=SIGNUP_URL ?>" method="post">
		<input type="hidden" name="signup_form_sent">
		<?=$admin->getFTAN();    /* Important: keep this in template! */ ?>
		<?=renderAspHoneypots(); /* Important: keep this in template! */ ?>
		<div class="formRow">
			<label class="settingName" for="username"><?=$TEXT['USERNAME']; ?></label>
			<div class="settingValue">
				<input type="text" id="username" name="username" maxlength="30" value="<?=$username?>" <?=($username_error) ? ' class="input-error"' : ''; ?> />
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="display_name"><?=$TEXT['DISPLAY_NAME']; ?></label>
			<div class="settingValue">
				<input type="text" id="display_name" name="display_name" maxlength="255" value="<?=$display_name?>"<?=($display_name_error) ? ' class="input-error"' : ''; ?> />
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="email"><?=$TEXT['EMAIL']; ?></label>
			<div class="settingValue">
				<input type="text" id="email" name="email" maxlength="255" value="<?=$email?>" <?=($email_error) ? ' class="input-error"' : ''; ?> />
			</div>
		</div>
		
		<div class="formRow">
			<div class="settingName" for="gdpr_check">
			</div>
			<div class="settingValue">
				<input type="checkbox" id="gdpr_check" name="gdpr_check" maxlength="255" value="1" <?=($gdpr_error) ? ' class="input-error"' : ''; ?><?=($gdpr_check == 1) ? ' checked' : ''; ?> />
				<?=$TEXT['REGISTER_GDPR_PHRASE']?>
			</div>
		</div>
		
		<?php if(ENABLED_CAPTCHA) {			
				// Use captcha
		?>
			<div class="formRow">
				<label class="settingName" for="field_title"><?=$TEXT['VERIFICATION']; ?></label>
				<div class="settingValue">
					<?php call_captcha(); ?>
				</div>
			</div>
		<?php 
		} //end:ENABLED_CAPTCHA
		?>
		
		<div class="buttonsRow">
			<input type="submit" name="submit" class="button" value="<?=$TEXT['SIGNUP']; ?>" />
			<input type="reset"  name="reset"  class="button pos-right" value="<?=$TEXT['RESET']; ?>" />
		</div>
	</form>
</div>