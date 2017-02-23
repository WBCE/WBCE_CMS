<?php if ( $msgTxt != '') : ?>
	<div class="<?=$msgCls?> inline">
		<p><?=$msgTxt?></p>
		<i class="fa fa-remove close"></i>
	</div>
<?php endif; ?>

<h2>
	<a href="index.php"><?=$MENU['SETTINGS']?></a> &raquo; <?=$OPF['HEADING']?>
</h2>

<section class="output-filter fg12 content-box">
	<div class="fg12 top bot desc"><?=$OPF['HOWTO']; ?></div>

	<hr class="fg12">

	<form name="store_settings" action="<?=$returnUrl; ?>" method="post">
		<?=$admin->getFTAN(); ?>
		<input type="hidden" name="action" value="save">

		<!-- frontend ------------------------------------------------->
		<h3 class="fg12"><?=$HEADING['FRONTEND'];?></h3>

		<div class="fg4"><?=$OPF['DROPLETS']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['droplets']=='1') ? 'checked="checked"' :'';?>
				name="droplets" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['droplets'])=='0') ? 'checked="checked"' :'';?>
				name="droplets" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['WBLINK']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['wblink']=='1') ? 'checked="checked"' :'';?>
				name="wblink" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['wblink'])=='0') ? 'checked="checked"' :'';?>
				name="wblink" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['AUTO_PLACEHOLDER']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['auto_placeholder']=='1') ? 'checked="checked"' :'';?>
				name="auto_placeholder" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['auto_placeholder'])=='0') ? 'checked="checked"' :'';?>
				name="auto_placeholder" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['INSERT']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['insert']=='1') ? 'checked="checked"' :'';?>
				name="insert" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['insert'])=='0') ? 'checked="checked"' :'';?>
				name="insert" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['SYS_REL'];?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['sys_rel']=='1') ? 'checked="checked"' :'';?>
				name="sys_rel" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['sys_rel'])=='0') ? 'checked="checked"' :'';?>
				name="sys_rel" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['EMAIL_FILTER'];?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['email_filter']=='1') ?'checked="checked"' :'';?>
				name="email_filter" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['email_filter'])=='0') ?'checked="checked"' :'';?>
				name="email_filter" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['MAILTO_FILTER'];?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['mailto_filter']=='1') ?'checked="checked"' :'';?>
				name="mailto_filter" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['mailto_filter'])=='0') ?'checked="checked"' :'';?>
				name="mailto_filter" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['JS_MAILTO']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['js_mailto']=='1') ?'checked="checked"' :'';?>
				name="js_mailto" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['js_mailto'])=='0') ?'checked="checked"' :'';?>
				name="js_mailto" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['SHORT_URL']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['short_url']=='1') ?'checked="checked"' :'';?>
				name="short_url" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['short_url'])=='0') ?'checked="checked"' :'';?>
				name="short_url" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['CSS_TO_HEAD']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['css_to_head']=='1') ?'checked="checked"' :'';?>
				name="css_to_head" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['css_to_head'])=='0') ?'checked="checked"' :'';?>
				name="css_to_head" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<!-- backend -------------------------------------------------->
		<h3 class="fg12"><?=$HEADING['BACKEND'];?></h3>

		<div class="fg4"><?=$OPF['DROPLETS']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['droplets_be']=='1') ? 'checked="checked"' :'';?>
				name="droplets_be" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['droplets_be'])=='0') ? 'checked="checked"' :'';?>
				name="droplets_be" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['INSERT']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['insert_be']=='1') ? 'checked="checked"' :'';?>
				name="insert_be" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['insert_be'])=='0') ? 'checked="checked"' :'';?>
				name="insert_be" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<div class="fg4"><?=$OPF['CSS_TO_HEAD']?>:</div>
		<div class="fg8">
			<label>
				<input type="radio" <?=($data['css_to_head_be']=='1') ?'checked="checked"' :'';?>
				name="css_to_head_be" value="1"><?=$OPF['ENABLED'];?>
			</label>
			<label>
				<input type="radio" <?=(($data['css_to_head_be'])=='0') ?'checked="checked"' :'';?>
				name="css_to_head_be" value="0"><?=$OPF['DISABLED'];?>
			</label>
		</div>

		<!-- replacements --------------------------------------------->
		<h3 class="fg12"><?=$OPF['REPLACEMENT_CONF'];?></h3>

		<div class="fg4"><?=$OPF['AT_REPLACEMENT'];?>:</div>
		<div class="fg8">
			<input type="text" style="width: 160px" value="<?=$data['at_replacement'];?>"
			name="at_replacement"/>
		</div>

		<div class="fg4"><?=$OPF['DOT_REPLACEMENT'];?>:</div>
		<div class="fg8">
			<input type="text" style="width: 160px" value="<?=$data['dot_replacement'];?>"
			name="dot_replacement"/>
		</div>

		<hr class="fg12">

		<div class="fg12">
			<button type="submit">
				<i class="fa fa-fw fa-save"></i>
				<?=$TEXT['SAVE']?>
			</button>
		</div>
	</form>
</section>
