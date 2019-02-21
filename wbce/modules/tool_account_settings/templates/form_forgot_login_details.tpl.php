<?php 
// prevent this template file from being accessed directly
defined('WB_PATH') or die("Cannot access this file directly"); 
I::insertCssFile(get_url_from_path(__DIR__).'/forms.css', 'HEAD BTM+');

if( ($errMsg=='') && ($message != '')) {
	// $message = $MESSAGE['FORGOT_PASS_NO_DATA'];
	$message_color = 'alert alert-success';
} else {
	$message = $errMsg;
	$message_color = 'alert alert-warning';
}
?>

<h1><?=$MENU['FORGOT']; ?></h1>
<div class="<?=$message_class; ?>">
	<strong><?=$message; ?></strong>
</div>
			

<?php if(!isset($display_form) OR $display_form != false) 
{ 
?>
	<form name="forgot_pass" action="<?=WB_URL.'/account/forgot.php'; ?>" method="post">
		<div class="cpForm">
		<div class="formRow">
			<label class="settingName" for="display_name"><?=$TEXT['EMAIL']; ?></label>
			<div class="settingValue">
				<input type="text" maxlength="255" name="email" value="<?=$email; ?>" style="width: 180px;" />
			</div>
		</div>
		
		<div class="buttonsRow">
			<input type="submit" name="submit" value="<?=$TEXT['SEND_DETAILS']; ?>" />
		</div>		
	</form>
<?php 
} 
?>


