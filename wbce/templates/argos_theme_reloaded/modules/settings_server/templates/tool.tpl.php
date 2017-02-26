<script>
    $(document).ready(function() {
		/* toggle the acces-settings tables */
		$('#operating_system_linux').click(function() {
			$('#access_settings').show('slow');
		});
		$('#operating_system_windows').click(function() {
			$('#access_settings').hide('slow');
		});

		/* update the mode strings on the fly */
		var initFileMode = $('#string-file-mode').html();
		var initDirMode  = $('#string-dir-mode').html();

		$('input[type="checkbox"]').change(function() {
			var fileMode = parseInt($('#string-file-mode').html());
			var dirMode = parseInt($('#string-dir-mode').html());
			var which = $(this).attr('id');
			var val = ($(this).is(':checked')) ? 1 : 0;

			if (which.substr(0,4) == 'file') {
				// file mode calculation
				if (which == 'file_u_r')
					newFileMode = (val == 0) ? fileMode - 400 : fileMode + 400;
				if (which == 'file_u_w')
					newFileMode = (val == 0) ? fileMode - 200 : fileMode + 200;
				if (which == 'file_u_e')
					newFileMode = (val == 0) ? fileMode - 100 : fileMode + 100;
				if (which == 'file_g_r')
					newFileMode = (val == 0) ? fileMode - 40 : fileMode + 40;
				if (which == 'file_g_w')
					newFileMode = (val == 0) ? fileMode - 20 : fileMode + 20;
				if (which == 'file_g_e')
					newFileMode = (val == 0) ? fileMode - 10 : fileMode + 10;
				if (which == 'file_o_r')
					newFileMode = (val == 0) ? fileMode - 4 : fileMode + 4;
				if (which == 'file_o_w')
					newFileMode = (val == 0) ? fileMode - 2 : fileMode + 2;
				if (which == 'file_o_e')
					newFileMode = (val == 0) ? fileMode - 1 : fileMode + 1;
				// prepare new file mode string (leading zeros!)
				if (newFileMode < 100) newFileMode = '0' + newFileMode;
				if (newFileMode < 10) newFileMode = '0' + newFileMode;
				newFileMode = '0' + newFileMode;
				newFileMode = newFileMode.toString();
				// change color by changed string
				if (newFileMode != initFileMode) {
					$('#string-file-mode').css('color', 'firebrick');
				} else {
					$('#string-file-mode').css('color', '#000');
				}
				// show new mode
				$('#string-file-mode').html(newFileMode);
			} else {
				// dir mode calculation
				if (which == 'dir_u_r')
					newDirMode = (val == 0) ? dirMode - 400 : dirMode + 400;
				if (which == 'dir_u_w')
					newDirMode = (val == 0) ? dirMode - 200 : dirMode + 200;
				if (which == 'dir_u_e')
					newDirMode = (val == 0) ? dirMode - 100 : dirMode + 100;
				if (which == 'dir_g_r')
					newDirMode = (val == 0) ? dirMode - 40 : dirMode + 40;
				if (which == 'dir_g_w')
					newDirMode = (val == 0) ? dirMode - 20 : dirMode + 20;
				if (which == 'dir_g_e')
					newDirMode = (val == 0) ? dirMode - 10 : dirMode + 10;
				if (which == 'dir_o_r')
					newDirMode = (val == 0) ? dirMode - 4 : dirMode + 4;
				if (which == 'dir_o_w')
					newDirMode = (val == 0) ? dirMode - 2 : dirMode + 2;
				if (which == 'dir_o_e')
					newDirMode = (val == 0) ? dirMode - 1 : dirMode + 1;
				// prepare new dir mode string (leading zeros!)
				if (newDirMode < 100) newDirMode = '0' + newDirMode;
				if (newDirMode < 10) newDirMode = '0' + newDirMode;
				newDirMode = '0' + newDirMode;
				newDirMode = newDirMode.toString();
				// change color by changed string
				if (newDirMode != initDirMode) {
					$('#string-dir-mode').css('color', 'firebrick');
				} else {
					$('#string-dir-mode').css('color', '#000');
				}
				// show new mode
				$('#string-dir-mode').html(newDirMode);
			}
		});
	});
</script>

<h2>
	<a href="index.php"><?=$MENU['SETTINGS']?></a> &raquo; <?=$HEADING['SERVER_SETTINGS']?>
</h2>

<section class="settings fg12 content-box">
	<div class="fg12 top bot desc"><?=$module_description?></div>

    <hr class="fg12">

    <form id="settings_seo_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?=$returnUrl; ?>" method="post">
        <?=$admin->getFTAN()?>

        <!-- filesystem permissions-->
        <div class="row">
			<div class="fg3" ><?=$TEXT['SERVER_OPERATING_SYSTEM']?></div>
			<div class="fg9" >
				<label>
					<input type="radio" name="operating_system" id="operating_system_linux" value="linux" <?php if (OPERATING_SYSTEM=="linux") echo WB_CHECK; ?> >
					<?=$TEXT['LINUX_UNIX_BASED'] ?>
				</label>
				<label>
					<input type="radio" name="operating_system" id="operating_system_windows" value="windows" <?php if (OPERATING_SYSTEM=="windows") echo WB_CHECK; ?> >
					<?=$TEXT['WINDOWS'] ?>
				</label>
			</div>
        </div>

		<!-- access settings -->
        <div id="access_settings" <?php if (OPERATING_SYSTEM=="linux") echo 'style="display:block"'; else echo 'style="display:none"'; ?> >
            <div class="row">
				<div class="fg3"><?=$TEXT['FILESYSTEM_PERMISSIONS']?></div>
				<div class="fg9 permissionTable bot">
					<table style="float:left; margin-right:20px;">
						<tr>
							<th class="header" colspan="3">
								<?=$TEXT['FILES'].'&nbsp;<span id="string-file-mode">'.STRING_FILE_MODE.'</span>'?>
							</th>
						</tr>
						<tr>
							<th class="left"><?=$TEXT['USER']?></th>
							<th class="left"><?=$TEXT['GROUP']?></th>
							<th class="left"><?=$TEXT['OTHERS']?></th>
						</tr>
						<tr>
							<td><label><input type="checkbox" name="file_u_r" id="file_u_r" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'u', 'r')) echo WB_CHECK?>><?=$TEXT['READ']?></label></td>
							<td><label><input type="checkbox" name="file_g_r" id="file_g_r" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'g', 'r')) echo WB_CHECK?>><?=$TEXT['READ']?></label></td>
							<td><label><input type="checkbox" name="file_o_r" id="file_o_r" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'o', 'r')) echo WB_CHECK?>><?=$TEXT['READ']?></label></td>
						</tr>
						<tr>
							<td><label><input type="checkbox" name="file_u_w" id="file_u_w" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'u', 'w')) echo WB_CHECK?>><?=$TEXT['WRITE']?></label></td>
							<td><label><input type="checkbox" name="file_g_w" id="file_g_w" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'g', 'w')) echo WB_CHECK?>><?=$TEXT['WRITE']?></label></td>
							<td><label><input type="checkbox" name="file_o_w" id="file_o_w" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'o', 'w')) echo WB_CHECK?>><?=$TEXT['WRITE']?></label></td>
						</tr>
						<tr>
							<td><label><input type="checkbox" name="file_u_e" id="file_u_e" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'u', 'e')) echo WB_CHECK?>><?=$TEXT['EXECUTE']?></label></td>
							<td><label><input type="checkbox" name="file_g_e" id="file_g_e" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'g', 'e')) echo WB_CHECK?>><?=$TEXT['EXECUTE']?></label></td>
							<td><label><input type="checkbox" name="file_o_e" id="file_o_e" value="true" <?php if (extract_permission(STRING_FILE_MODE, 'o', 'e')) echo WB_CHECK?>><?=$TEXT['EXECUTE']?></label></td>
						</tr>
					</table>

					<table>
						<tr>
							<th class="header" colspan="3">
								<?=$TEXT['DIRECTORIES'].'&nbsp;<span id="string-dir-mode">'.STRING_DIR_MODE.'</span>'?>
							</th>
						</tr>
						<tr>
						   <th class="left"><?=$TEXT['USER']?></th>
						   <th class="left"><?=$TEXT['GROUP']?></th>
						   <th class="left"><?=$TEXT['OTHERS']?></th>
						</tr>
						<tr>
							<td><label><input type="checkbox" name="dir_u_r" id="dir_u_r" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'u', 'r')) echo WB_CHECK?>><?=$TEXT['READ'] ?></label></td>
							<td><label><input type="checkbox" name="dir_g_r" id="dir_g_r" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'g', 'r')) echo WB_CHECK?>><?=$TEXT['READ'] ?></label></td>
							<td><label><input type="checkbox" name="dir_o_r" id="dir_o_r" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'o', 'r')) echo WB_CHECK?>><?=$TEXT['READ'] ?></label></td>
						</tr>
						<tr>
							<td><label><input type="checkbox" name="dir_u_w" id="dir_u_w" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'u', 'w')) echo WB_CHECK?>><?=$TEXT['WRITE'] ?></label></td>
							<td><label><input type="checkbox" name="dir_g_w" id="dir_g_w" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'g', 'w')) echo WB_CHECK?>><?=$TEXT['WRITE'] ?></label></td>
							<td><label><input type="checkbox" name="dir_o_w" id="dir_o_w" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'o', 'w')) echo WB_CHECK?>><?=$TEXT['WRITE'] ?></label></td>
						</tr>
						<tr>
							<td><label><input type="checkbox" name="dir_u_e" id="dir_u_e" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'u', 'e')) echo WB_CHECK?>><?=$TEXT['EXECUTE'] ?></label></td>
							<td><label><input type="checkbox" name="dir_g_e" id="dir_g_e" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'g', 'e')) echo WB_CHECK?>><?=$TEXT['EXECUTE'] ?></label></td>
							<td><label><input type="checkbox" name="dir_o_e" id="dir_o_e" value="true" <?php if (extract_permission(STRING_DIR_MODE, 'o', 'e')) echo WB_CHECK?>><?=$TEXT['EXECUTE'] ?></label></td>
						</tr>
					</table>
				</div>
			</div>
        </div>

        <!-- PAGES_DIRECTORY -->
        <div class="row">
        <div class="fg3"><?=$TEXT['PAGES_DIRECTORY']?></div>
        <div class="fg9">
			<input type="text" id="pages_directory" name="pages_directory" maxlength="255" value="<?=PAGES_DIRECTORY?>" class="wdt100">
		</div>
		</div>

        <!-- MEDIA_DIRECTORY -->
        <div class="row">
        <div class="fg3"><?=$TEXT['MEDIA_DIRECTORY']?></div>
        <div class="fg9">
			<input type="text" id="media_directory" name="media_directory" maxlength="255" value="<?=MEDIA_DIRECTORY?>" class="wdt100">
		</div>
		</div>

        <!-- PAGE_EXTENSION -->
        <div class="row">
        <div class="fg3"><?=$TEXT['PAGE_EXTENSION']?></div>
        <div class="fg9">
			<input type="text" id="page_extension" name="page_extension" maxlength="255" value="<?=PAGE_EXTENSION?>" class="wdt100">
		</div>
		</div>

        <!-- PAGE_SPACER-->
        <div class="row">
        <div class="fg3"><?=$TEXT['PAGE_SPACER']?></div>
        <div class="fg9">
			<input type="text" id="page_spacer" name="page_spacer" maxlength="255"  value="<?=PAGE_SPACER?>" class="wdt100">
		</div>
		</div>

        <!-- RENAME_FILES_ON_UPLOAD -->
        <div class="row">
        <div class="fg3"><?=$TEXT['RENAME_FILES_ON_UPLOAD']?></div>
        <div class="fg9">
			<input type="text" id="rename_files_on_upload" name="rename_files_on_upload" maxlength="255"  value="<?=RENAME_FILES_ON_UPLOAD?>" class="wdt500">
		</div>
		</div>

        <!-- SESSION_IDENTIFIER -->
        <div class="row">
        <div class="fg3"><?=$TEXT['SESSION_IDENTIFIER'] ?></div>
        <div class="fg9">
			<input type="text" id="app_name" name="app_name" maxlength="255"  value="<?=APP_NAME ?>" class="wdt100">
		</div>
		</div>

        <!-- SEC_ANCHOR -->
        <div class="row">
        <div class="fg3"><?=$TEXT['SEC_ANCHOR'] ?></div>
        <div class="fg9">
			<input type="text" id="sec_anchor" name="sec_anchor" maxlength="255"  value="<?=SEC_ANCHOR ?>" class="wdt100">
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



