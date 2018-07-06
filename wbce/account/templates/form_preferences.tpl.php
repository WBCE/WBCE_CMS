<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
loadCssFile(WB_URL.'/account/templates/forms.css', 'HEAD TOP-');
?>


<?php 
// do we have Success Messages to display?
if(empty($success) == false){ ?>
<div class="alert alert-success">
	<?php foreach($success as $k=>$message){ ?>
		<p id="<?=$k?>"><?=$message?></p>
	<?php 
	} 
	?>
</div>
<?php 
} 
// do we have Error Messages to display?
if(empty($error) == false){ ?>
<div class="alert alert-error">
	<?php foreach($error as $k=>$message){ ?>
		<p id="<?=$k?>"><?=$message?></p>
	<?php 
	} 
	?>
</div>
<?php 
} 
?>

<h1><?=$MENU['PREFERENCES'] ?></h1>
<div class="cpForm">
	<form name="details" action="" method="post">
		<?=$admin->getFTAN(); /* Important: keep this in template! */?>
		<h3><?=$HEADING['MY_SETTINGS'] ?></h3>
		<div class="formRow">
			<label class="settingName" for="display_name"><?=$TEXT['DISPLAY_NAME']; ?></label>
			<div class="settingValue">
					<input type="text" id="display_name" name="display_name" value="<?=$sDisplayName ?>" readonly="readonly" />
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="language"><?=$TEXT['LANGUAGE']; ?></label>
			<div class="settingValue">
				<select name="language" id="language">
					<?php foreach($aLanguages as $lang) { ?>
						<option value="<?=$lang['CODE'] ?>"<?=($lang['SELECTED'] == true) ? ' selected' : ''?> style="background: url(<?=$lang['FLAG'] ?>.png) no-repeat center left; padding-left: 20px;"><?=$lang['NAME'] ?> (<?=$lang['CODE'] ?>)</option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="timezone"><?=$TEXT['TIMEZONE']; ?></label>
			<div class="settingValue">
				<select name="timezone" id="timezone">
					<option value="-20"><?=$TXT_ACCOUNT['PLEASE_SELECT'] ?></option>
					<?php foreach($aTimeZones as $rec) { ?>
						<option value="<?=$rec['VALUE'] ?>"<?=($rec['SELECTED'] == true) ? ' selected' : ''?>><?=$rec['NAME'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="date_format"><?=$TEXT['DATE_FORMAT']; ?></label>
			<div class="settingValue">
				<select id="date_format" name="date_format">
					<option value=""><?=$TXT_ACCOUNT['PLEASE_SELECT'] ?></option>
					<?php foreach($aDateFormats as $rec) { ?>
						<option value="<?=$rec['VALUE'] ?>"<?=($rec['SELECTED'] == true) ? ' selected' : ''?>><?=$rec['NAME'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="time_format"><?=$TEXT['TIME_FORMAT']; ?></label>
			<div class="settingValue">
				<select name="time_format" id="time_format">
					<option value=""><?=$TXT_ACCOUNT['PLEASE_SELECT'] ?></option>
					<?php foreach($aTimeFormats as $rec) { ?>
						<option value="<?=$rec['VALUE'] ?>"<?=($rec['SELECTED'] == true) ? ' selected' : ''?>><?=$rec['NAME'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="buttonsRow">
			<button type="reset" name="reset" value="reset" class="button" ><?=$TEXT['RESET'] ?></button>
			<button type="submit" name="action" value="details" class="button" ><?=$TXT_ACCOUNT['SAVE_SETTINGS'] ?></button>
		</div>
	</form>
	<form name="email" action="" method="post">
		<?=$admin->getFTAN(); /* Important: keep this in template! */?>
		<h3><?=$HEADING['MY_EMAIL'] ?></h3>
		<div class="formRow">
			<label class="settingName" for="email"><?=$TEXT['EMAIL']; ?></label>
			<div class="settingValue">
				<input type="text" id="email"  name="email" value="<?=$sEmail ?>" />
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="current_password"><?=$TEXT['CURRENT_PASSWORD']; ?></label>
			<div class="settingValue">
				<input type="password" name="current_password" id="current_password" />
			</div>
		</div>
		
		<div class="buttonsRow">
			<button type="reset" name="reset" value="reset" class="button" ><?=$TEXT['RESET'] ?></button>
			<button type="submit" name="action" value="details" class="button" ><?=$TXT_ACCOUNT['SAVE_EMAIL'] ?></button>
		</div>
	</form>
	
	<form name="password" action="" method="post">
		<?=$admin->getFTAN(); /* Important: keep this in template! */?>
		<h3><?=$HEADING['MY_PASSWORD'] ?></h3>
		<div class="formRow">
			<label class="settingName" for="current_password"><?=$TEXT['CURRENT_PASSWORD']; ?></label>
			<div class="settingValue">
				<input type="password" name="current_password" id="current_password" />
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="new_password"><?=$TEXT['NEW_PASSWORD']; ?></label>
			<div class="settingValue">
				<input type="password" name="new_password"  id="new_password" />
			</div>
		</div>
		
		<div class="formRow">
			<label class="settingName" for="new_password2"><?=$TEXT['RETYPE_NEW_PASSWORD']; ?></label>
			<div class="settingValue">
				<input type="password" name="new_password2"  id="new_password2" />
			</div>
		</div>
			
		<div class="buttonsRow">
			<button type="reset" name="reset" value="reset" class="button" ><?=$TEXT['RESET'] ?></button>
			<button type="submit" name="action" value="details" class="button" ><?=$TXT_ACCOUNT['SAVE_PASSWORD'] ?></button>
		</div>
	</form>
	<div style="margin: 1em auto;">
		<button type="button" value="cancel" onClick="javascript: window.location = '<?=$sHttpReferer ?>';"><?=$TEXT['CANCEL'] ?></button>
	</div>
</div>