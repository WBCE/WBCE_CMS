<?php if ($msgTxt != '') :?>
    <div class="msg-box inline">
		<?=$msgTxt?>
		<i class="fa fa-remove close"></i>
	</div>
<?php endif; ?>

<?php if ($errorTxt != '') :?>
    <div class="error-box inline">
		<?=$errorTxt?>
		<i class="fa fa-remove close"></i>
	</div>
<?php endif; ?>

<h2><?=$HEADING['MY_SETTINGS']?></h2>

<section class="preferences fg12 content-box">
	<div class="fg12 top bot"><?=$TOOL['DESCRIPTION']; ?></div>

	<hr class="fg12">

	<form name="preferences_save" id="preferences_save" action="<?=$returnUrl?>" method="post">
		<?=$admin->getFTAN()?>

		<div class="fg3">
			<?=$TEXT['USERNAME']?>:
		</div>
		<div class="fg9" style="height: 30px;">
			<?=$userName?>
		</div>

		<div class="fg3">
			<?=$TEXT['DISPLAY_NAME']?>:
		</div>
		<div class="fg9">
			<input type="text" id="display_name" name="display_name" value="<?=$userDisplayName?>" class="wdt200">
		</div>

		<div class="fg3">
			<?=$TEXT['LANGUAGE']?>:
		</div>
		<div class="fg9">
		<select name="language" id="language" class="wdt200">
			<?php foreach ($languageData as $l) :?>
				<option value="<?=$l['code']?>" <?=$l['selected']?> class="hasFlag" style="background-image: url(<?=WB_URL?>/languages/<?=$l['code']?>.png);"><?=$l['name']?> (<?=$l['code']?>)</option>
			<?php endforeach; ?>
			</select>
		</div>

		<div class="fg3">
			<?=$TEXT['TIMEZONE']?>:
		</div>
		<div class="fg9">
			<select name="timezone" id="timezone" class="wdt200">
				<?php foreach ($timezoneData as $t) :?>
					<option value="<?=$t['value']?>" <?=$t['selected']?>><?=$t['name']?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="fg3">
			<?=$TEXT['DATE_FORMAT']?>:
		</div>
		<div class="fg9">
			<select name="date_format" id="date_format" class="wdt200">
				<?php foreach ($dateData as $d) :?>
					<option value="<?=$d['value']?>" <?=$d['selected']?>><?=$d['name']?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="fg3">
			<?=$TEXT['TIME_FORMAT']?>:
		</div>
		<div class="fg9">
			<select name="time_format" id="time_format" class="wdt200">
				<?php foreach ($timeformData as $f) :?>
					<option value="<?=$f['value']?>" <?=$f['selected']?>><?=$f['name']?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="fg3">
			<?=$HEADING['MY_EMAIL']?>:
		</div>
		<div class="fg9">
			<input type="text" id="email" name="email" value="<?=$userMail?>" class="wdt200">
		</div>

		<div class="fg12 bot warning"><?=$TOOL['PASSWORD']?></div>

		<div class="fg3">
			<?=$TEXT['NEW_PASSWORD']?>:
		</div>
		<div class="fg9">
			<input type="password" id="new_password_1" name="new_password_1" value="" class="wdt150">
		</div>

		<div class="fg3">
			<?=$TEXT['RETYPE_NEW_PASSWORD']?>:
		</div>
		<div class="fg9">
			<input type="password" id="new_password_2" name="new_password_2" value="" class="wdt150">
		</div>

		<div class="fg3">
			<?=$TEXT['NEED_CURRENT_PASSWORD']?>:
		</div>
		<div class="fg9">
			<input type="password" id="current_password" name="current_password" value="" class="wdt150">
		</div>

		<hr class="fg12">

		<div class="fg5 push3">
			<button type="submit" name="save_settings" class="save_settings">
				<i class="fa fa-fw fa-save"></i>
				<?=$TEXT['SAVE']; ?>
			</button>
		</div>
		<div class="fg4 right">
			<button type="reset" id="reset" name="reset" class="save_settings">
				<i class="fa fa-fw fa-refresh"></i>
				<?=$TEXT['RESET']; ?>
			</button>
		</div>
	</form>
</section>




