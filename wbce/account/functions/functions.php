<?php 
require __DIR__ .'/wb_dump.function.php';
	


// get a string between a [tag] your string [/tag]
if(!function_exists('getStringBetweenTags')){
	function getStringBetweenTags($sContent, $sTag){
		$sRetVal = '';
		if(strpos($sContent, $sTag.']') !== false){
			$sOpeningTag = '['.$sTag.']';
			$sClosingTag = '[/'.$sTag.']';
			$sContent = " ".$sContent;
			$ini = strpos($sContent, $sOpeningTag);
			if($ini !== 0){
				$ini += strlen($sOpeningTag);
				$len = strpos($sContent, $sClosingTag, $ini) - $ini;
				$sRetVal = substr($sContent, $ini, $len);
			}
		}
		return $sRetVal;
	}
}

if(!function_exists('loadJsFile')){
	/**
	 * Make use of output filter "(MOVE) JS" using a PHP function
	 * possible DOM Placements:
	 *   HEAD TOP-
	 *   HEAD TOP+
	 *   HEAD BTM-
	 *   HEAD BTM+
	 *	 
	 *   BODY TOP-
	 *   BODY TOP+
	 *   BODY BTM-
	 *   BODY BTM+
	 */
	function loadJsFile($sFileLoc = '', $sDomPlacement = 'HEAD BTM+'){	
		$sRetVal      = '';
		$sFileLocUrl  = '';
		$sFileLocPath = str_replace(WB_URL, WB_PATH, $sFileLoc);
		if(file_exists($sFileLocPath)){
			// make use of the "freshest (most recent) file version" technique
			$sFileLocUrl = $sFileLoc.'?'.filemtime ($sFileLocPath);
		}
		if($sFileLocUrl != ''){	
			$sRetVal .= '<!--(MOVE) JS '.$sDomPlacement.' -->';
			$sRetVal .= '<script type="text/javascript" src="'.$sFileLocUrl.'"></script>';
			$sRetVal .= '<!--(END)-->';
		}
		echo $sRetVal;	
	}
}

if(!function_exists('loadCssFile')){
	/**
	 * Make use of output filter ("MOVE) CSS" using a PHP function 
	 * possible DOM Placements:
	 *   HEAD TOP-
	 *   HEAD TOP+
	 *   HEAD BTM-
	 *   HEAD BTM+
	 */
	function loadCssFile($sFileLoc = '', $sDomPlacement = 'HEAD BTM+', $sMedia = 'screen'){	
		$sRetVal      = '';
		$sFileLocUrl  = '';
		$sFileLocPath = str_replace(WB_URL, WB_PATH, $sFileLoc);
		if(file_exists($sFileLocPath)){
			// make use of the "freshest (most recent) file version" technique
			$sFileLocUrl = $sFileLoc.'?'.filemtime ($sFileLocPath);
		}
		if($sFileLocUrl != ''){	
			$sRetVal .= '<!--(MOVE) CSS '.$sDomPlacement.' -->';
			$sRetVal .= '<link href="'.$sFileLocUrl.'" rel="stylesheet" type="text/css" media="'.$sMedia.'">';
			$sRetVal .= '<!--(END)-->';
		}
		echo $sRetVal;	
	}
}



if(!function_exists('replaceTitle')){
	/**
	 * Make use of output filter "(REPLACE) TITLE" using a PHP function 
	 */
	function replaceTitle($sTitle){	
		$sRetVal      = '';
		if($sTitle != ''){	
			$sRetVal .= '<!--(REPLACE) TITLE -->';
			$sRetVal .= '<title>'.$sTitle.'</title>';
			$sRetVal .= '<!--(END)-->';
		}
		echo $sRetVal;	
	}
}

function account_getConfig(){
	// default cfg file
	$sUseCfg = WB_PATH.'/account/Accounts.cfg.php';
	// possible override files
	$aOverrides = array(
		WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/Accounts.cfg.php',
		WB_PATH.'/modules/UserBase/account/Accounts.cfg.php'
	);
	foreach($aOverrides as $sCfgFile){
		if(is_readable($sCfgFile)){
			$sUseCfg = $sCfgFile;
		}
	}
	$aConfig = parse_ini_file($sUseCfg, true)['Signup_Account_Settings'];
	return $aConfig;
}

/**
 * Returns an array of all possible language files, including overrides
 */
function account_getLanguageFiles(){
	$aFiles     = array();
	// we need EN array in all cases because other languages may have missing keys
	$aFiles[] = WB_PATH .'/account/languages/EN.php';
	if(LANGUAGE != 'EN'){
		// override with default language if default language is not EN
		$sLangFile = WB_PATH .'/account/languages/'.DEFAULT_LANGUAGE.'.php';
		if(is_readable($sLangFile))	$aFiles[] = $sLangFile;
	}
	if(LANGUAGE == 'EN'){
		// override with file from template only if language is EN
		$sFile = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/languages/EN.php';
		if(is_readable($sFile))	$aFiles[] = $sFile;
	}
	if(LANGUAGE != 'EN'){
		// override with LANGUAGE file from template
		$sFile = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/languages/'.LANGUAGE.'.php';
		if(is_readable($sFile))	$aFiles[] = $sFile;
	}
	return $aFiles;
	
}
function account_usernameAlreadyTaken($sUsername){
	$retVal = false;
	if($sUsername != ''){
		$sSql = "SELECT `user_id` FROM `{TP}users` WHERE `username` = '".$sUsername."'";
		$retVal = $GLOBALS['database']->get_one($sSql);
	}
	return $retVal;
}

function account_emailAlreadyTaken($sEmail){
	$retVal = false;
	if($sEmail != ''){
		$sSql = "SELECT `user_id` FROM `{TP}users` WHERE `email` = '".$sEmail."'";
		$retVal = $GLOBALS['database']->get_one($sSql);
	}
	return $retVal;
}


function account_getUserData($iUserID){
	global $database;
	$aConfig = array();
	$sSql = "SELECT * FROM `{TP}users` WHERE `user_id` = " . intval($iUserID);
	
	if($rQuery = $database->query($sSql)){
		$aConfig = $rQuery->fetchRow(MYSQL_ASSOC);
	}

	return $aConfig;
}

function account_getUserEmail($iUserID)	{
	$sSql  = "SELECT `email` FROM `{TP}users` WHERE `user_id`= ".$iUserID;
	return $GLOBALS['database']->get_one($sSql);
}

// "AccountsManager" here, in this case, is the one who is responsible for accounts, signups etc.
//    at the moment we use the Email of the Admin or the SERVER_EMAIL
//    but we will have a custom setting for this
function account_getAccountsManagerEmail()	{
	return (defined('SERVER_EMAIL') && SERVER_EMAIL != '') ? SERVER_EMAIL : account_getUserEmail(1);
}


function account_getTemplate($sTplName = ''){
	 $sRetVal = '<'.$sTplName.'> Template File not found!';
	 if($sTplName != ''){ 
		$sFileName     = $sTplName.'.tpl.php';
		$sFileCore     = WB_PATH.'/account/templates/'.$sFileName;		 
		$sFileTemplate = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/templates/'.$sFileName;
		// core 
		if(file_exists($sFileCore))	     $sRetVal = $sFileCore;
		// override from template
		if(file_exists($sFileTemplate))  $sRetVal = $sFileTemplate; 
	 }
	 
	 return $sRetVal;
}

function getEmailTemplate($sFileLoc = "", $sTag = "body", $aTokens = array()){
	$sRetVal = NULL;
	if($sFileLoc != ""){
		$sLocParts = pathinfo($sFileLoc);
		//is there a custom version of this file??
		$sCustomFile = str_replace($sLocParts['filename'], $sLocParts['filename'].'.custom', $sFileLoc); 
		$sFileToRead = is_readable($sCustomFile) ? $sCustomFile : (is_readable($sFileLoc) ? $sFileLoc : '');
		if($sFileToRead != ''){
			$sRetVal = file_get_contents($sFileToRead, true);
		}		
	}
	if($sTag != 'body'){
		$sTag = trim($sTag);			
		$sRetVal = getStringBetweenTags($sRetVal, $sTag);
	} else {
		$sRetVal = preg_replace( '/<!--(.|\s)*?-->/' , '' , $sRetVal ); // remove comments
	}
	if(!empty($aTokens)){
		$sRetVal = replace_vars($sRetVal, $aTokens);
	}
	return $sRetVal;
}


function account_sendEmail($sMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject = ''){
	global $wb, $MESSAGE;
	
	$sMailFrom = SERVER_EMAIL;
	$sMailTemplate = account_getCorrectEmailTplPath($sEmailTemplateName);
	
	// E-Mail body
	if( $string = getEmailTemplate($sMailTemplate, 'body', $aTokenReplace)){
		$sMailMessage = $string;
	}

	// E-Mail subject
	$sSubject = ($sEmailSubject != '') ? $sEmailSubject : 'E-Mail from "'. WEBSITE_TITLE .'"';
	if( $string = getEmailTemplate($sMailTemplate, 'subject', $aTokenReplace)){
		// The content between [subject] and [/subject] in the template takes preference!
		$sSubject = $string;
	}
	
	if( $wb->mail($sMailFrom, $sMailTo, $sSubject, $sMailMessage) ){
		return true;
	} else {
		#return $sMailTemplate;
		return false;
	}
}

function account_getCorrectEmailTplPath($sEmailTemplateName){	
	// get the correct template for this mail
	$sLC = defined('LANGUAGE') ? LANGUAGE : defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN';	
	$sDirPathCore     = WB_PATH .'/account/email_templates/';
	$sDirPathTemplate = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/email_templates/';
	$sMailTemplate = '/'.$sEmailTemplateName.'.txt.php';
	
	// core 
	if (file_exists($sDirPathCore.$sLC.$sMailTemplate)){    
		$sTmp = $sDirPathCore.$sLC.$sMailTemplate;
	} else {
		$sTmp = $sDirPathCore.'EN'.$sMailTemplate;
	}
	
	// override from template
	if (file_exists($sDirPathTemplate.$sLC.$sMailTemplate)){
		$sTmp = $sDirPathTemplate.$sLC.$sMailTemplate;
	} elseif (file_exists($sDirPathTemplate.'EN'.$sMailTemplate)) {
		$sTmp = $sDirPathTemplate.'EN'.$sMailTemplate;
	}
	
	return $sTmp;
}

function account_genEmailLinkFromUri($sUri){
	return '<a href="'.$sUri.'">'.$sUri.'</a>';
}

/**
 * retrieve user_id from signup_confirmcode $_GET parameter provided via the confirmation link
 */
function account_userIdFromConfirmcode($sConfirmCode) {
	global $database;
	$retVal = false;
	if( preg_match('/[0-9a-f]{32}/i', $sConfirmCode) ) {
		$sSql = "SELECT `user_id` FROM `{TP}users` WHERE `signup_confirmcode` = '".$sConfirmCode."'";
		$retVal = $database->get_one($sSql);
	}
	return $retVal;
}

/**
 * verify and validate the check_sum $_GET parameter provided via the confirmation link
 */
function account_checkConfirmSum($sCheckSum, $iUserID) {
	global $database;
	$bRetVal = false;
	$sDbCheckSum = $database->get_one("SELECT `signup_checksum` FROM `{TP}users` WHERE `user_id` = ".$iUserID);
	if($sDbCheckSum == $sCheckSum){	
		$bRetVal = true;
	}
	return $bRetVal;
}

	
/** 
 * Generate a random password and hash it
 */
 
function account_GenerateRandomPassword() {
	$sPassword = '';
	$salt = "abchefghjkmnpqrstuvwxyz0123456789";
	srand((double)microtime() * 1000000);
	$i = 0;
	while ($i <= 7) {
		$num = rand() % 33;
		$tmp = substr($salt, $num, 1);
		$sPassword = $sPassword . $tmp;
		$i++;
	}	
	return $sPassword;	
 }
