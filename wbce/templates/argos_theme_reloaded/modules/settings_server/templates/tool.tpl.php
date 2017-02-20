<script>
    $(document).ready(function() {
		$('#operating_system_linux').click(function() {
			$('#access_settings').show('slow');
		});
		$('#operating_system_windows').click(function() {
			$('#access_settings').hide('slow');
		});
	});
</script>

<h2>
	<?=$MENU['SETTINGS'].' &raquo; '.$HEADING['SERVER_SETTINGS']?>
	<div class="headline-link">
		<i class="fa fa-fw fa-reply"></i>
		<a href="index.php"><?=$MENU['SETTINGS']?></a>
	</div>
</h2>

<section class="settings fg12 content-box">
	<div class="fg12 top bot desc"><?=$module_description?></div>

    <hr class="fg12">

    <form id="settings_seo_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?=$returnUrl; ?>" method="post">
        <?=$admin->getFTAN()?>

        <!-- filesystem permissions-->
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

		<!-- access settings -->
        <div id="access_settings" <?php if (OPERATING_SYSTEM=="linux") echo 'style="display:block"'; else echo 'style="display:none"'; ?> >
            <div class="fg3"><?=$TEXT['FILESYSTEM_PERMISSIONS']?></div>
            <div class="fg4 permissionTable bot">
                <table>
                    <tr>
                        <th class="header" colspan="3">
							<?=$TEXT['FILES'].'&nbsp;'.STRING_FILE_MODE?>
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
            </div>
            <div class="fg5 permissionTable bot">
                <table>
                    <tr>
                        <th class="header" colspan="3">
							<?=$TEXT['DIRECTORIES']."&nbsp;".STRING_DIR_MODE ?>
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

        <!-- PAGES_DIRECTORY -->
        <div class="fg3"><?=$TEXT['PAGES_DIRECTORY']?></div>
        <div class="fg9">
			<input type="text" id="pages_directory" name="pages_directory" maxlength="255" value="<?=PAGES_DIRECTORY?>">
		</div>

        <!-- MEDIA_DIRECTORY -->
        <div class="fg3"><?=$TEXT['MEDIA_DIRECTORY']?></div>
        <div class="fg9">
			<input type="text" id="media_directory" name="media_directory" maxlength="255" value="<?=MEDIA_DIRECTORY?>">
		</div>

        <!-- PAGE_EXTENSION -->
        <div class="fg3"><?=$TEXT['PAGE_EXTENSION']?></div>
        <div class="fg9">
			<input class="long" type="text" id="page_extension" name="page_extension" maxlength="255" value="<?=PAGE_EXTENSION?>">
		</div>

        <!-- PAGE_SPACER-->
        <div class="fg3"><?=$TEXT['PAGE_SPACER']?></div>
        <div class="fg9">
			<input type="text" id="page_spacer" name="page_spacer" maxlength="255"  value="<?=PAGE_SPACER?>">
		</div>

        <!-- RENAME_FILES_ON_UPLOAD -->
        <div class="fg3"><?=$TEXT['RENAME_FILES_ON_UPLOAD']?></div>
        <div class="fg9">
			<input class="wdt500" type="text" id="rename_files_on_upload" name="rename_files_on_upload" maxlength="255"  value="<?=RENAME_FILES_ON_UPLOAD?>">
		</div>

        <!-- SESSION_IDENTIFIER -->
        <div class="fg3"><?=$TEXT['SESSION_IDENTIFIER'] ?></div>
        <div class="fg9">
			<input type="text" id="app_name" name="app_name" maxlength="255"  value="<?=APP_NAME ?>">
		</div>

        <!-- SEC_ANCHOR -->
        <div class="fg3"><?=$TEXT['SEC_ANCHOR'] ?></div>
        <div class="fg9">
			<input type="text" id="sec_anchor" name="sec_anchor" maxlength="255"  value="<?=SEC_ANCHOR ?>">
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



