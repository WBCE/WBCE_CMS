<h2>
	<a href="index.php"><?=$MENU['SETTINGS']?></a> &raquo; <?=$MOD_SET_GENERAL['HEADER']?>
</h2>

<section class="settings fg12 content-box">
	<div class="fg12 top bot desc"><?=$MOD_SET_GENERAL['DESCRIPTION']?></div>

	<hr class="fg12">

	<form id="settings_seo_form" name="store_settings" action="<?=$returnUrl; ?>" method="post">
			<?=$admin->getFTAN()?>

        <!-- WEBSITE_TITLE -->
        <div class="row">
			<div class="fg3">
				<?=$TEXT['WEBSITE_TITLE']?>
			</div>
			<div class="fg9">
				<input type="text" id="website_title" name="website_title" maxlength="30" value="<?=WEBSITE_TITLE?>" class="wdt350">
				<span class="title-counter"></span> Zeichen [<span class="title-remain"></span> Rest]
			</div>
		</div>

        <!-- WEBSITE DESCRIPTION -->
        <div class="row">
			<div class="fg3">
				<?=$TEXT['WEBSITE_DESCRIPTION']?>
			</div>
			<div class="fg9">
				<textarea id="website_description" name="website_description" class="wdt350"><?=WEBSITE_DESCRIPTION?></textarea>
				<span class="desc-counter"></span> Zeichen [<span class="desc-remain"></span> Rest]
			</div>
		</div>

        <!-- WEBSITE KEYWORDS -->
        <div class="row">
			<div class="fg3">
				<?=$TEXT['WEBSITE_KEYWORDS']?>
			</div>
			<div class="fg9">
				<textarea id="website_keywords" name="website_keywords" class="wdt350"><?=WEBSITE_KEYWORDS?></textarea>
			</div>
		</div>

        <!-- WEBSITE HEADER -->
        <div class="row">
			<div class="fg3">
				<?=$TEXT['WEBSITE_HEADER']?>
			</div>
			<div class="fg9">
				<textarea id="website_header" name="website_header" class="wdt350"><?=WEBSITE_HEADER?></textarea>
			</div>
		</div>

         <!-- WEBSITE FOOTER -->
        <div class="row">
			<div class="fg3">
				<?=$TEXT['WEBSITE_FOOTER']?>
			</div>
			<div class="fg9">
				<textarea id="website_footer" name="website_footer" class="wdt350"><?=WEBSITE_FOOTER?></textarea>
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

<script>
	$(document).ready(function() {
		// title char-counter
		var titleLimit = 30;
		var titleInit = $('#website_title').val().length;
		var titleTail = titleLimit - titleInit;
		$('.title-counter').html(titleInit).css('color','#147d14');
		$('.title-remain').html(titleTail).css('color','#147d14');

		$('#website_title').keyup(function(){
			var count = $(this).val().length;
			$('.title-counter').html(count);
			$('.title-remain').html(titleLimit - count);
			if (count > titleLimit) {
				$('.title-counter').css('color','firebrick');
				$('.title-remain').css('color','firebrick').html(0);
			} else {
				$('.title-counter').css('color','#147d14');
				if ($('.title-remain').html() == 0) {
					$('.title-remain').css('color','firebrick');
				} else {
					$('.title-remain').css('color','#147d14');
				}
			}
		});

		// description char-counter
		var descLimit = 150;
		var descInit = $('#website_description').val().length;
		var descTail = descLimit - descInit;
		$('.desc-counter').html(descInit).css('color','#147d14');
		$('.desc-remain').html(descTail).css('color','#147d14');

		$('#website_description').keyup(function(){
			var count = $(this).val().length;
			$('.desc-counter').html(count);
			$('.desc-remain').html(descLimit - count);
			if (count > descLimit) {
				$('.desc-counter').css('color','firebrick');
				$('.desc-remain').css('color','firebrick').html(0);
			} else {
				$('.desc-counter').css('color','#147d14');
				if ($('.desc-remain').html() == 0) {
					$('.desc-remain').css('color','firebrick');
				} else {
					$('.desc-remain').css('color','#147d14');
				}
			}
		});
	});
</script>
