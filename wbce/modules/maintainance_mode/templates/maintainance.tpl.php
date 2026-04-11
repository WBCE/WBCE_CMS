<?php 
/*
For automated detection if form has benn sent the submit button needs to have 
name+id ="save_settings". (Optional ($_POST['action']) == 'save')
For return to admintools the responsible button must have name+id="admin_tools".
And to activate default setting it heed to have name+id="save_default".

$returnUrl      Is used as form Action it sends the form to itself(apeform)

Language vars whit preceding MOD_ can be found in the launguage file of this module
Other language vars are from the default WB (e.g. $TEXT or $HEADING are from the 
WBCE language files)

The default button uses a simple Javascript return confirm()for a simple "Are you sure?"
*/

$isEnabled = Settings::get("wb_maintainance_mode") == true ? 'checked' : '';
?>

<div class="maintMode">
	<h2><?=$MOD_MAINTAINANCE['HEADER']; ?></h2>
		<?=$MOD_MAINTAINANCE['DESCRIPTION']; ?>:
	<form id="maintainance_mode_form" name="store_settings" style="margin-top: 1em; display: true;" action="<?=$returnUrl; ?>" method="post">
		<?=$admin->getFTAN(); ?>
		<p>
			<b><?=$MOD_MAINTAINANCE['CHECKBOX']; ?></b>
		</p>
		<p>
			<input type="checkbox" name="enabled" id="enabled" value="true" <?=$isEnabled ? 'checked' : ''?>>
			<label for="enabled"><?=$TEXT['ENABLED']; ?></label>
		</p>
		<p style="text-align: right;">
			<input type="submit" name="save_settings" id="save_settings" value="<?=$TEXT['SAVE']; ?>">			 
			<input type="submit" onclick="return confirm('<?=$TEXT['ARE_YOU_SURE']?>'); " name="save_default" id="save_default" value="<?=$HEADING['DEFAULT_SETTINGS']; ?>">
		</p>
	</form>
</div>
	