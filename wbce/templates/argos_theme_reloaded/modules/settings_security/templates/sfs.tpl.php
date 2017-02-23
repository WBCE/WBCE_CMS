<h2>
	<a href="index.php"><?=$MENU['SETTINGS']?></a> &raquo; <?=$SFS['HEADER']?>
</h2>

<section class="sfs fg12 content-box">
	<div class="fg12 top bot desc"><?=$SFS['DESCRIPTION']; ?>:</div>

	<hr class="fg12">

	<form id="sfs_form" name="store_settings" action="<?=$returnUrl; ?>" method="post">
		<?=$admin->getFTAN()?>

		<div class="fg3">
			<input type="checkbox" name="useFP" id="useFP" value="true" <?=$useFP ?> >
		</div>
		<div class="fg9">
			<label for="useFP">
				<?=$SFS['USEFP']?>
				<a class="tooltip" href="#">
					<i class="fa fa-question-circle"></i>
					<span class="custom help">
						<?=$SFS['USEFP_TTIP']?>
					</span>
				</a>
			</label>
		</div>

		<div class="fg3">
			<select name="ipOctets" >
				<?php for ($i = 1; $i <= 4; $i++) : ?>
				<option value="<?=$i?>" <?php if ($ipOctets==$i) echo $selected ?> >
					<?=$i?>
				</option>
				<?php endfor; ?>
			</select>
		</div>
		<div class="fg9">
			<label for="ipOctets">
				<?=$SFS['USEIP']?>
				<a class="tooltip" href="#">
					<i class="fa fa-question-circle"></i>
					<span class="custom help">
						<?=$SFS['USEIP_TTIP']?>
					</span>
				</a>
			</label>
		</div>

		<div class="fg3">
			<input type="text" name="tokenName" id="tokenName" value="<?=$tokenName ?>">
		</div>
		<div class="fg9">
			<label for="tokenName">
				<?=$SFS['TOKENNAME']?>
				<a class="tooltip" href="#">
					<i class="fa fa-question-circle"></i>
					<span class="custom help">
						<?=$SFS['TOKENNAME_TTIP']?>
					</span>
				</a>
			</label>
		</div>

		<div class="fg3">
			<input type="text" name="timeout" id="timeout" value="<?=$timeout ?>">
		</div>
		<div class="fg9">
			<label for="timeout">
				<?=$SFS['TIMEOUT']?>
				<a class="tooltip" href="#">
					<i class="fa fa-question-circle"></i>
					<span class="custom help">
						<?=$SFS['TIMEOUT_TTIP']?>
					</span>
				</a>
			</label>
		</div>

		<div class="fg3">
			<input type="text" name="secret" id="secret" value="<?=$secret ?>">
		</div>
		<div class="fg9">
			<label for="secret"><?=$SFS['SECRET']?>
				<a class="tooltip" href="#">
					<i class="fa fa-question-circle"></i>
					<span class="custom help">
						<?=$SFS['SECRET_TTIP']?>
					</span>
				</a>
			</label>
		</div>

		<div class="fg3">
			<input type="text" name="secretTime" id="secretTime" value="<?=$secretTime ?>">
		</div>
		<div class="fg9">
			<label for="secretTime"><?=$SFS['SECRETTIME']?>
				<a class="tooltip" href="#">
					<i class="fa fa-question-circle"></i>
					<span class="custom help">
						<?=$SFS['SECRETTIME_TTIP']?>
					</span>
				</a>
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
