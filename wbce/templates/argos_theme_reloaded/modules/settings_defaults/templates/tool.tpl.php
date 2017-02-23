<h2>
	<a href="index.php"><?=$MENU['SETTINGS']?></a> &raquo; <?=$MOD_SET_GENERAL['HEADER']?>
</h2>

<section class="settings fg12 content-box">
	<div class="fg12 top bot desc"><?=$MOD_SET_GENERAL['DESCRIPTION']?></div>

	<hr class="fg12">

    <form id="settings_seo_form" name="store_settings" action="<?=$returnUrl; ?>" method="post">
        <?=$admin->getFTAN()?>

        <!-- Language -->
        <?php $selects=ds_GetLanguagesArray(); ?>
        <div class="fg2"><?=$TEXT['LANGUAGE']?></div>
        <div class="fg10">
			<select name="default_language" id="default_language">
				<?php if (is_array($selects)) : ?>
					<?php foreach ($selects as $value) : ?>
						<option value="<?=$value['directory'] ?>" <?php if (DEFAULT_LANGUAGE == $value['directory']) echo 'selected'; ?>  class="hasFlag" style="background-image: url(<?=WB_URL?>/languages/<?=$value['directory']?>.png);">
							<?=$value['name']." (".$value['directory'].")"?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>

        <!-- Timezones -->
        <?php $selects=ds_GetTimezonesArray(); ?>
        <div class="fg2"><?=$TEXT['TIMEZONE'] ?></div>
        <div class="fg10">
			<select name="default_timezone" id="default_timezone">
				<?php if (is_array($selects)) : ?>
					<?php foreach ($selects as $key=>$value) : ?>
						<option value="<?=$key ?>" <?php if (DEFAULT_TIMEZONE == $key*60*60) echo 'selected'; ?> >
							<?=$value?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>

        <!-- Dateformats -->
        <?php $selects=ds_GetDateFormatArray(); ?>
        <div class="fg2"><?=$TEXT['DATE_FORMAT'] ?></div>
        <div class="fg10">
			<select name="default_date_format" id="default_date_format">
				<?php if (is_array($selects)) : ?>
					<?php foreach ($selects as $key=>$value) : ?>
						<?php $key = str_replace('|', ' ', $key); ?>
						<option value="<?=$key ?>" <?php if (DEFAULT_DATE_FORMAT == $key) echo 'selected'; ?> >
							<?=$value?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>

        <!-- timeformats -->
        <?php $selects=ds_GetTimeFormatArray(); ?>
        <div class="fg2"><?=$TEXT['TIME_FORMAT'] ?></div>
        <div class="fg10">
			<select name="default_time_format" id="default_time_format">
				<?php if (is_array($selects)) : ?>
					<?php foreach ($selects as $key=>$value) : ?>
						<?php $key = str_replace('|', ' ', $key);?>
						<option value="<?=$key ?>" <?php if (DEFAULT_TIME_FORMAT == $key) echo 'selected'; ?> >
							<?=$value?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>

         <!-- Default FE template -->
        <?php $selects=ds_GetTemplatesArray(); ?>
        <div class="fg2"><?=$TEXT['TEMPLATE'] ?></div>
        <div class="fg10">
			<select name="default_template" id="default_template" class="wdt250">
				<?php if (is_array($selects)) : ?>
					<?php foreach ($selects as $value) : ?>
						<option value="<?=$value['directory'] ?>" <?php if (DEFAULT_TEMPLATE == $value['directory']) echo 'selected'; ?> >
							<?=$value['name']." (".$value['directory'].")"?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>

        <!-- Default BE theme -->
        <?php $selects=ds_GetThemesArray(); ?>
        <div class="fg2"><?=$TEXT['THEME'] ?></div>
        <div class="fg10">
			<select name="default_theme" id="default_theme" class="wdt250">
				<?php if (is_array($selects)) : ?>
					<?php foreach ($selects as $value) : ?>
						<option value="<?=$value['directory'] ?>" <?php if (DEFAULT_THEME == $value['directory']) echo 'selected'; ?> >
							<?=$value['name']." (".$value['directory'].")" ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>

        <hr class="fg12">

        <div class="fg6">
			<button type="submit" name="save_settings" class="save_settings">
				<i class="fa fa-fw fa-save"></i>
				<?=$TEXT['SAVE']?>
			</button>
		</div>
        <div class="fg6 right">
			<button type="submit" onclick="return confirm('<?=$TEXT['ARE_YOU_SURE']?>')" name="save_default" class="save_default danger wdt200">
				<i class="fa fa-fw fa-refresh"></i><?=$TEXT['SYSTEM_DEFAULT']?>
			</button>
		</div>
    </form>
</section>
