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

    <form id="settings_seo_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?=$returnUrl; ?>" method="post">
        <?=$admin->getFTAN(); ?>

        <!-- search visibility ---------------------------------------->
        <div class="fg3"><?=$TEXT['VISIBILITY']?></div>
        <div class="fg9">
        <select class="long" name="search" id="search">
            <option value="public" <?php if (SEARCH=="private") echo WB_SELECT; ?> >
				<?=$TEXT['PUBLIC']     ?>
			</option>
            <option value="private" <?php if (SEARCH=="private") echo WB_SELECT; ?> >
				<?=$TEXT['PRIVATE']    ?>
			</option>
            <option value="registered" <?php if (SEARCH=="private") echo WB_SELECT; ?> >
				<?=$TEXT['REGISTERED'] ?>
			</option>
            <option value="none"<?php if (SEARCH=="private") echo WB_SELECT; ?>>
				<?=$TEXT['NONE']       ?>
			</option>
        </select>
        </div>

        <!-- search FE template -->
        <?php $selects=se_GetTemplatesArray(); ?>
        <div class="fg3"><?=$TEXT['TEMPLATE']?></div>
        <div class="fg9">
        <select class="wdt250" name="search_template" id="search_template">
            <option value="" <?php if ($SEARCH_TEMPLATE=="") echo WB_SELECT; ?> >
				<?=$TEXT['SYSTEM_DEFAULT'] ?>
			</option>
			<?php if (is_array($selects)) :?>
				<?php foreach ($selects as $value): ?>
					<option value="<?=$value['directory'] ?>" <?php if ($SEARCH_TEMPLATE == $value['directory']) echo WB_SELECT; ?> >
						<?=$value['name']." (".$value['directory'].")" ?>
					</option>
				<?php endforeach; ?>
			<?php endif; ?>
        </select>
        </div>

        <!-- search header -->
        <div class="fg3">
			<?=$TEXT['HEADER'] ?>
		</div>
        <div class="fg9">
        <textarea class="code tabbed wdt550" id="search_header" name="search_header"    ><?=$SEARCH_HEADER ?></textarea>
        </div>

        <!-- search results header -->
        <div class="fg3"><?=$TEXT['RESULTS_HEADER']?></div>
        <div class="fg9">
			<textarea class="code tabbed wdt550" id="search_results_header" name="search_results_header"><?=$SEARCH_RESULTS_HEADER?></textarea>
        </div>

        <!-- search results loop -->
        <div class="fg3"><?=$TEXT['RESULTS_LOOP']?></div>
        <div class="fg9">
			<textarea class="code tabbed wdt550" id="search_results_loop" name="search_results_loop"><?=$SEARCH_RESULTS_LOOP?></textarea>
        </div>

        <!-- search results footer -->
        <div class="fg3"><?=$TEXT['RESULTS_FOOTER']?></div>
        <div class="fg9">
			<textarea class="code tabbed wdt550" id="search_results_footer" name="search_results_footer"><?=$SEARCH_RESULTS_FOOTER ?></textarea>
        </div>

        <!-- search no results -->
        <div class="fg3"><?=$TEXT['NO_RESULTS']?></div>
        <div class="fg9">
			<textarea class="code tabbed wdt550" id="search_no_results" name="search_no_results"><?=$SEARCH_NO_RESULTS?></textarea>
        </div>

        <!-- search footer -->
        <div class="fg3"><?=$TEXT['FOOTER']?></div>
        <div class="fg9">
			<textarea class="code tabbed wdt550" id="search_footer" name="search_footer"><?=$SEARCH_FOOTER ?></textarea>
        </div>

        <!-- module order -->
        <div class="fg3"><?=$TEXT['MODULE_ORDER']?></div>
        <div class="fg9">
			<input class="wdt550" type="text" id="search_module_order" name="search_module_order" maxlength="30" value="<?=$SEARCH_MODULE_ORDER ?>">
        </div>

        <!-- max excerpt -->
        <div class="fg3"><?=$TEXT['MAX_EXCERPT']?></div>
        <div class="fg9">
			<input class="wdt50" type="text" id="search_max_excerpt" name="search_max_excerpt" maxlength="30"  value="<?=$SEARCH_MAX_EXCERPT ?>">
        </div>

        <!-- time limit for each module -->
        <div class="fg3"><?=$TEXT['TIME_LIMIT']?></div>
        <div class="fg9">
			<input class="wdt50" type="text" id="search_time_limit" name="search_time_limit" maxlength="30"  value="<?=$SEARCH_TIME_LIMIT ?>">
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





