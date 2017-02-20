<h2>
	<?=$MENU['SETTINGS'].' &raquo; '.$MOD_MAINTAINANCE['HEADER']?>
	<div class="headline-link">
		<i class="fa fa-fw fa-reply"></i>
		<a href="index.php"><?=$MENU['SETTINGS']?></a>
	</div>
</h2>

<section class="maintMode fg12 content-box">
	<div class="fg12 top bot desc"><?=$MOD_MAINTAINANCE['DESCRIPTION']; ?></div>

	<hr class="fg12">

	<form id="maintainance_mode_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?=$returnUrl; ?>" method="post">
		<?=$admin->getFTAN(); ?>

		<div class="fg12">
			<label>
				<input type="checkbox" name="maintMode" id="maintMode" value="true" <?=$maintMode ?> >
				<?=$MOD_MAINTAINANCE['CHECKBOX']; ?>
			</label>
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
