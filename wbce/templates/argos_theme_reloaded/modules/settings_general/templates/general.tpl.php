<h2>
	<a href="index.php"><?=$MENU['SETTINGS']; ?></a> &raquo; <?=$MOD_SET_GENERAL['HEADER']?>
</h2>

<section class="settings fg12 content-box">
	<div class="fg12 top bot warning"><?=$MOD_SET_GENERAL['DESCRIPTION']; ?></div>

    <form id="settings_general_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?=$returnUrl; ?>" method="post">
        <?=$admin->getFTAN(); ?>

		<!-- PAGE LEVEL LIMIT -->
        <div class="row">
			<div class="fg3"><?=$TEXT['PAGE_LEVEL_LIMIT']?></div>
			<div class="fg9">
				<select name="page_level_limit" id="page_level_limit" class="wdt50">
					<?php for ($i = 1; $i <= 10; $i++) : ?>
						<option value="<?=$i; ?>" <?php if (PAGE_LEVEL_LIMIT==$i) echo 'selected'; ?> >
							<?=$i?>
						</option>
					<?php endfor; ?>
				</select>
			</div>
		</div>

        <!-- PAGE TRASH -->
        <div class="row">
			<div class="fg3"><?=$TEXT['PAGE_TRASH'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="page_trash" id="page_trash_inline" value="inline" <?php if (PAGE_TRASH=="inline") echo 'checked'; ?> >
					<?=$TEXT['ENABLED']?>
				</label>
				<label>
					<input type="radio" name="page_trash" id="page_trash_disabled" value="disabled" <?php if (PAGE_TRASH=="disabled") echo 'checked'; ?> >
					<?=$TEXT['DISABLED']?>
				</label>
			</div>
		</div>

        <!-- PAGE LANGUAGES -->
        <div class="row">
			<div class="fg3"><?=$TEXT['PAGE_LANGUAGES'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="page_languages" id="page_languages_true" value="true" <?php if (PAGE_LANGUAGES)  echo 'checked';?> >
					<?=$TEXT['ENABLED']?>
				</label>
				<label>
					<input type="radio" name="page_languages" id="page_languages_false" value="false" <?php if (!PAGE_LANGUAGES)  echo 'checked'; ?> >
					<?=$TEXT['DISABLED']?>
				</label>
			</div>
		</div>

        <!-- MULTIPLE MENUS -->
        <div class="row">
			<div class="fg3" ><?=$TEXT['MULTIPLE_MENUS'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="multiple_menus" id="multiple_menus_true" value="true" <?php if (MULTIPLE_MENUS)  echo 'checked';?> >
					<?=$TEXT['ENABLED']?>
				</label>
				<label>
					<input type="radio" name="multiple_menus" id="multiple_menus_false" style="width: 14px; height: 14px;" value="false" <?php if (!MULTIPLE_MENUS)  echo 'checked="checked"'; ?> >
					<?=$TEXT['DISABLED']?>
				</label>
			</div>
		</div>

        <!--HOME_FOLDERS -->
        <div class="row">
			<div class="fg3" ><?=$TEXT['HOME_FOLDERS'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="home_folders" id="home_folders_true"  value="true" <?php if (HOME_FOLDERS)  echo 'checked'; ?> >
					<?=$TEXT['ENABLED'] ?>
				</label>
				<label>
					<input type="radio" name="home_folders" id="home_folders_false"  value="false" <?php if (!HOME_FOLDERS)  echo 'checked'; ?> >
					<?=$TEXT['DISABLED'] ?>
				</label>
			</div>
		</div>

        <!-- MANAGE_SECTIONS -->
        <div class="row">
			<div class="fg3" ><?=$HEADING['MANAGE_SECTIONS'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="manage_sections" id="manage_sections_true" value="true" <?php if (MANAGE_SECTIONS)  echo 'checked' ;?> >
					<?=$TEXT['ENABLED'] ?>
				</label>
				<label>
					<input type="radio" name="manage_sections" id="manage_sections_false" value="false" <?php if (!MANAGE_SECTIONS)  echo 'checked="checked"'; ?> >
					<?=$TEXT['DISABLED'] ?>
				</label>
			</div>
		</div>

        <!-- SECTION_BLOCKS -->
        <div class="row">
			<div class="fg3"><?=$TEXT['SECTION_BLOCKS'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="section_blocks" id="section_blocks_true" value="true" <?php if (SECTION_BLOCKS)  echo 'checked'; ?> >
					<?=$TEXT['ENABLED'] ?>
				</label>
				<label>
					<input type="radio" name="section_blocks" id="section_blocks_false" value="false" <?php if (!SECTION_BLOCKS)  echo 'checked'; ?> >
					<?=$TEXT['DISABLED'] ?>
				</label>
			</div>
		</div>

        <!-- INTRO_PAGE -->
        <div class="row">
			<div class="fg3" ><?=$TEXT['INTRO_PAGE'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="intro_page" id="intro_page_true" value="true" <?php if (INTRO_PAGE)  echo 'checked'; ?> >
					<?=$TEXT['ENABLED'] ?>
				</label>
				<label>
					<input type="radio" name="intro_page" id="intro_page_false" value="false" <?php if (!INTRO_PAGE)  echo 'checked'; ?> >
					<?=$TEXT['DISABLED'] ?>
				</label>
			</div>
		</div>

        <!-- HOMEPAGE_REDIRECTION -->
        <div class="row">
			<div class="fg3" ><?=$TEXT['HOMEPAGE_REDIRECTION'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="homepage_redirection" id="homepage_redirection_true" value="true" <?php if (HOMEPAGE_REDIRECTION)  echo 'checked'; ?> >
					<?=$TEXT['ENABLED'] ?>
				</label>
				<label>
					<input type="radio" name="homepage_redirection" id="homepage_redirection_false" value="false" <?php if (!HOMEPAGE_REDIRECTION)  echo 'checked'; ?> >
					<?=$TEXT['DISABLED'] ?>
				</label>
			</div>
		</div>

        <!-- SMART_LOGIN -->
        <div class="row">
			<div class="fg3" ><?=$TEXT['SMART_LOGIN'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="smart_login" id="smart_login_true" value="true" <?php if (SMART_LOGIN)  echo 'checked'; ?> >
					<?=$TEXT['ENABLED'] ?>
				</label>
				<label>
					<input type="radio" name="smart_login" id="smart_login_false" value="false" <?php if (!SMART_LOGIN)  echo 'checked'; ?> >
					<?=$TEXT['DISABLED'] ?>
				</label>
			</div>
		</div>

         <!-- LOGIN -->
        <div class="row">
			<div class="fg3" ><?=$TEXT['LOGIN'] ?></div>
			<div class="fg9">
				<label>
					<input type="radio" name="frontend_login" id="frontend_login_true" value="true" <?php if (FRONTEND_LOGIN)  echo 'checked' ;?> >
					<?=$TEXT['ENABLED'] ?>
				</label>
				<label>
					<input type="radio" name="frontend_login" id="frontend_login_false" value="false" <?php if (!FRONTEND_LOGIN)  echo 'checked'; ?> >
					<?=$TEXT['DISABLED'] ?>
				</label>
			</div>
		</div>

        <!-- REDIRECT_TIMER -->
        <div class="row">
			<div class="fg3" ><?=$TEXT['REDIRECT_AFTER'] ?></div>
			<div class="fg9">
				<input type="text" id="redirect_timer" name="redirect_timer" value="<?=REDIRECT_TIMER ?>" class="wdt50">
				( <b>0 -10000</b>, <b>-1</b> = <?=$TEXT['DISABLED'] ?> )
			</div>
		</div>

        <!-- FRONTEND SIGNUP -->
        <?php $groups=gs_GetGroupArray(); ?>
        <div class="row">
			<div class="fg3"><?=$TEXT['SIGNUP'] ?></div>
			<div class="fg9">
				<select name="frontend_signup" id="frontend_signup" <?php if (!$groups) echo 'disabled'; ?> class="wdt150">
					<?php if (is_array($groups)) : ?>
						<option value="false"><?=$TEXT['DISABLED'] ?></option>
						<?php foreach (gs_GetGroupArray() as $group) : ?>
							<option value="<?=$group['group_id'] ?>" <?php if(FRONTEND_SIGNUP == $group['group_id']) echo 'selected'; ?> >
								<?=$group['name']?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
		</div>

        <!-- ERROR_LEVEL -->

        <div class="row">
			<div class="fg3"><?=$TEXT['PHP_ERROR_LEVEL'] ?></div>
			<div class="fg9">
				<select name="er_level" id="er_level" class="wdt150">
					<?php require(ADMIN_PATH.'/interface/er_levels.php'); ?>
					<?php foreach ($ER_LEVELS AS $value => $title) : ?>
						<option value="<?=$value ?>" <?php if(ER_LEVEL == $value) echo 'selected'; ?> >
							<?=$title?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

        <!-- WYSIWYG_EDITOR -->
        <?php $groups=gs_GetEditorArray(); ?>
        <div class="row">
			<div class="fg3" for="wysiwyg_editor"><?=$TEXT['WYSIWYG_EDITOR'] ?></div>
			<div class="fg9">
				<select name="wysiwyg_editor" id="wysiwyg_editor" class="wdt150">
					<option value="none" <?php if(WYSIWYG_EDITOR == "none") echo 'selected'; ?> >
						<?=$TEXT['NONE']; ?>
					</option>
					<?php if (is_array($groups)) :?>
						<?php foreach (gs_GetEditorArray() as $addon) : ?>
							<option value="<?=$addon['directory'] ?>" <?php if(WYSIWYG_EDITOR == $addon['directory']) echo 'selected'; ?> >
								<?=$addon['name']?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
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
