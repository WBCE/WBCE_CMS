<script>
	$(document).ready(function() {
		// toggle state
		$('#mailer-php').click(function(e) {
			if ($(this).is(':checked') == true) {
				$('#smtp-settings').toggle();
			}
		});
		$('#mailer-smtp').click(function(e) {
			if ($(this).is(':checked') == true) {
				$('#smtp-settings').toggle();
			}
		});
	});
</script>

<h2>
	<?=$MENU['SETTINGS'].' &raquo; '.$MOD_SET_MAIL['HEADER']?>
	<div class="headline-link">
		<i class="fa fa-fw fa-reply"></i>
		<a href="index.php"><?=$MENU['SETTINGS']?></a>
	</div>
</h2>

<section class="settings fg12 content-box">
    <div class="fg12 top bot desc">
		<?=$MOD_SET_MAIL['DESCRIPTION']?>
	</div>

	<hr class="fg12">

    <form id="settings_seo_form" name="store_settings" action="<?=$returnUrl; ?>" method="post">
        <?=$admin->getFTAN(); ?>

        <!-- DEFAULT_SENDER_MAIL -->
        <div class="fg4"><?=$TEXT['WBMAILER_DEFAULT_SENDER_MAIL'] ?></div>
        <div class="fg8">
			<input class="wdt250" type="text" id="server_email" name="server_email" maxlength="255"  value="<?=SERVER_EMAIL?>">
        </div>

        <!-- DEFAULT_SENDER_MAIL -->
        <div class="fg4"><?=$TEXT['WBMAILER_DEFAULT_SENDER_NAME'] ?></div>
        <div class="fg8">
			<input class="wdt250" type="text" id="wbmailer_default_sendername" name="wbmailer_default_sendername" maxlength="255"  value="<?=WBMAILER_DEFAULT_SENDERNAME?>">
		</div>

        <!-- WBMAILER_FUNCTION -->
        <div class="fg4" ><?=$TEXT['WBMAILER_FUNCTION'] ?></div>
        <div class="fg8">
			<label>
				<input type="radio" name="wbmailer_routine" id="mailer-php" value="phpmail" <?php if (WBMAILER_ROUTINE=="phpmail") echo WB_CHECK; ?> >
				<?=$TEXT['WBMAILER_PHP'] ?>
			</label>
			<label>
				<input type="radio" name="wbmailer_routine" id="mailer-smtp" style="width: 14px; height: 14px;" value="smtp" <?php if (WBMAILER_ROUTINE=="smtp") echo WB_CHECK; ?> >
				<?=$TEXT['WBMAILER_SMTP'] ?>
			</label>
        </div>

        <div id="smtp-settings" <?php if (WBMAILER_ROUTINE=="smtp") echo 'style="display:block"'; else echo 'style="display:none"'; ?>>

			<hr class="fg12">

            <div class="fg12 desc"><?=$TEXT['WBMAILER_NOTICE'] ?></div>

 			<hr class="fg12 top bot">

			<!-- WBMAILER_SMTP_HOST -->
            <div class="fg4"><?=$TEXT['WBMAILER_SMTP_HOST'] ?></div>
            <div class="fg8">
				<input class="wdt250" type="text" id="wbmailer_smtp_host" name="wbmailer_smtp_host" maxlength="250"  value="<?=WBMAILER_SMTP_HOST?>" >
            </div>

            <!-- WBMAILER_SMTP_AUTH -->
            <div class="fg4"><?=$TEXT['WBMAILER_SMTP_AUTH'] ?></div>
            <div class="fg8">
				<input type="checkbox" name="wbmailer_smtp_auth" id="wbmailer_smtp_auth" value="true" disabled checked>
            </div>

            <!-- WBMAILER_SMTP_USERNAME -->
            <div class="fg4"><?=$TEXT['WBMAILER_SMTP_USERNAME'] ?></div>
            <div class="fg8">
				<input class="long" type="text" id="wbmailer_smtp_username" name="wbmailer_smtp_username" maxlength="250"  value="<?=WBMAILER_SMTP_USERNAME?>" >
            </div>

            <!-- WBMAILER_SMTP_PASSWORD -->
            <div class="fg4"><?=$TEXT['WBMAILER_SMTP_PASSWORD'] ?></div>
            <div class="fg8">
				<input class="long" type="password" id="wbmailer_smtp_password" name="wbmailer_smtp_password" maxlength="250"  value="<?=WBMAILER_SMTP_PASSWORD?>" >
            </div>

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



