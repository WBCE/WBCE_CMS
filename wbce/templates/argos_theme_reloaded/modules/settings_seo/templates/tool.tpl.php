<h2>
	<?=$MENU['SETTINGS'].' &raquo; '.$MOD_SET_GENERAL['HEADER']?>
	<div class="headline-link">
		<i class="fa fa-fw fa-reply"></i>
		<a href="index.php"><?=$MENU['SETTINGS']?></a>
	</div>
</h2>

<section class="settings fg12 content-box">
	<div class="fg12 top bot desc"><?=$MOD_SET_GENERAL['DESCRIPTION']?></div>

	<hr class="fg12">

	<form id="settings_seo_form" name="store_settings" action="<?=$returnUrl; ?>" method="post">
			<?=$admin->getFTAN()?>

        <!-- WEBSITE_TITLE -->
        <div class="fg3">
			<?=$TEXT['WEBSITE_TITLE']?>
		</div>
        <div class="fg9">
			<input type="text" id="website_title" name="website_title" maxlength="30" value="<?=WEBSITE_TITLE?>" class="wdt350">
		</div>

        <!-- WEBSITE DESCRIPTION -->
        <div class="fg3">
			<?=$TEXT['WEBSITE_DESCRIPTION']?>
		</div>
        <div class="fg9">
			<textarea id="website_description" name="website_description" class="wdt350"><?=WEBSITE_DESCRIPTION?></textarea>
		</div>

        <!-- WEBSITE KEYWORDS -->
        <div class="fg3">
			<?=$TEXT['WEBSITE_KEYWORDS']?>
		</div>
        <div class="fg9">
			<textarea id="website_keywords" name="website_keywords" class="wdt350"><?=WEBSITE_KEYWORDS?></textarea>
		</div>

        <!-- WEBSITE HEADER -->
        <div class="fg3">
			<?=$TEXT['WEBSITE_HEADER']?>
		</div>
        <div class="fg9">
			<textarea id="website_header" name="website_header" class="wdt350"><?=WEBSITE_HEADER?></textarea>
		</div>

         <!-- WEBSITE FOOTER -->
        <div class="fg3">
			<?=$TEXT['WEBSITE_FOOTER']?>
		</div>
        <div class="fg9">
			<textarea id="website_footer" name="website_footer" class="wdt350"><?=WEBSITE_FOOTER?></textarea>
		</div>

		<hr class="fg12">

		<div class="fg6">
			<button type="submit" name="save_settings" class="save_settings">
				<i class="fa fa-fw fa-save"></i>
				<?=$TEXT['SAVE']?>
			</button>
		</div>
		<div class="fg6 right">
			<button type="submit" onclick="return confirm('<?=$TEXT['ARE_YOU_SURE']?>');" name="save_default" class="save_default danger wdt200">
				<i class="fa fa-fw fa-refresh"></i>
				<?=$TEXT['SYSTEM_DEFAULT']?>
			</button>
		</div>

    </form>
</section>
