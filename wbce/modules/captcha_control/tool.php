<?php
/**
 * @category        modules
 * @package         Captcha Control
 * @author          WBCE Project
 * @copyright       Luise Hahne, Norbert Heimsath
 * @license         GPLv2 or any later
 */

// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);


$table = TABLE_PREFIX.'mod_captcha_control';
$js_back = ADMIN_URL.'/admintools/tool.php?tool=captcha_control';

// check if data was submitted
if(isset($_POST['save_settings'])) {
	if (!$admin->checkFTAN())
	{
		$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back );
	}
	
	// get configuration settings
	$enabled_captcha = ($_POST['enabled_captcha'] == '1') ? '1' : '0';
	$enabled_asp = ($_POST['enabled_asp'] == '1') ? '1' : '0';
	$captcha_type = $admin->add_slashes($_POST['captcha_type']);
	
	// update database settings
	$database->query("UPDATE $table SET
		enabled_captcha = '$enabled_captcha',
		enabled_asp = '$enabled_asp',
		captcha_type = '$captcha_type'
	");

	// save text-captchas
	if($captcha_type == 'text') { // ct_text
		$text_qa=$admin->add_slashes($_POST['text_qa']);
		if(!preg_match('/### .*? ###/', $text_qa)) {
			$database->query("UPDATE $table SET ct_text = '$text_qa'");
		}
	}
	
	// check if there is a database error, otherwise say successful
	if($database->is_error()) {
		$admin->print_error($database->get_error(), $js_back);
	} else {
		$admin->print_success($MESSAGE['PAGES_SAVED'], $js_back);
	}

} else {
	
	// include captcha-file
	require_once(WB_PATH .'/include/captcha/captcha.php');

	// load text-captchas
	$text_qa='';
	if($query = $database->query("SELECT ct_text FROM $table")) {
		$data = $query->fetchRow();
		$text_qa = $data['ct_text'];
	}
	if($text_qa == '')
		$text_qa = $MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'];

	// connect to database and read out captcha settings
	if($query = $database->query("SELECT * FROM $table")) {
		$data = $query->fetchRow();
		$enabled_captcha = $data['enabled_captcha'];
		$enabled_asp = $data['enabled_asp'];
		$captcha_type = $data['captcha_type'];
	} else {
		// something went wrong, use dummy value
		$enabled_captcha = '1';
		$enabled_asp = '1';
		$captcha_type = 'calc_text';
	}
	
	//Display form
    include($modulePath."templates/captcha_control.tpl.php");
}

 