<?php
/**
 * @category        modules
 * @package         Captcha Control
 * @author          WBCE Project
 * @copyright       Thorn, Luise Hahne, Norbert Heimsath
 * @license         GPLv2 or any later
 */

// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);



// check if data was submitted
if(isset($_POST['save_settings'])) {
	if (!$admin->checkFTAN()){
		//3rd param = false =>no auto footer, no exit.
	    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$returnUrl, false); 
	}
	
	// get configuration settings
	$enabled_captcha = ($_POST['enabled_captcha'] == '1') ? 'true' : 'false';
	$enabled_asp = ($_POST['enabled_asp'] == '1') ? 'true' : 'false';
	$captcha_type = $admin->add_slashes($_POST['captcha_type']);
	
	// update settings
    Settings::Set ("enabled_captcha", $enabled_captcha);
    Settings::Set ("enabled_asp", $enabled_asp);
    Settings::Set ("captcha_type", $captcha_type);

	// save text-captchas if they are set , so we dont forget em
	if(isset ($_POST['text_qa'])) { 
        // text question/answer 
		$text_qa=$admin->add_slashes($_POST['text_qa']);
        //check for valid phrases 
		if(!preg_match('/### .*? ###/', $text_qa)) {
            //set value
            Settings::Set ("ct_text", $text_qa);
		}
	}
	
	// check if there is a database error, otherwise say successful
	if($database->is_error()) {
		$admin->print_error($database->get_error(),$returnUrl, false);
	} else {
		$admin->print_success($MESSAGE['PAGES_SAVED'], $returnUrl);
	}

} else {
	
	// include captcha-file from here we get the "$useable_captchas" var
	require_once(WB_PATH .'/include/captcha/captcha.php');

	// load text-captchas
	$text_qa=CT_TEXT;
	if($text_qa == '') {
		$text_qa = $MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'];
    }

	// fetch captcha settings for template
	$enabled_captcha = ENABLED_CAPTCHA;
	$enabled_asp = ENABLED_ASP;
	$captcha_type = CAPTCHA_TYPE;
	
	//Display form
    include($modulePath."templates/captcha_control.tpl.php");
}

 
