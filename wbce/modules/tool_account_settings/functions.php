<?php
/**
 * WBCE CMS AdminTool: tool_account_settings
 * 
 * @platform    WBCE CMS 1.3.2 and higher
 * @package     modules/tool_account_settings
 * @author      Christian M. Stefan <stefek@designthings.de>
 * @copyright   Christian M. Stefan
 * @license     see LICENSE.md of this package
 */



if (!function_exists('renderAspHoneypots')){
    function renderAspHoneypots(){
        $sASPFields = '';
        if (ENABLED_ASP) { 
            $sTimeStamp = time();
            $_SESSION['submitted_when'] = $sTimeStamp;
            // add some honeypot-fields
            ob_start();	
        ?>
            <div style="display:none;">
                <input type="hidden" name="submitted_when" value="<?=$sTimeStamp ?>" />
                <p class="nixhier">
                    <label for="email-address" title="Leave this field email-address blank">Email address:</label>
                    <input id="email-address" name="email-address" size="60" value="" />
                </p>
                <p class="nixhier">				
                    <label for="name" title="Leave this field name blank">Username (id):</label>
                    <input id="name" name="name" size="60" value="" /></p>
                <p class="nixhier">
                    <label for="full_name" title="Leave this field full_name blank">Full Name:</label>
                    <input id="full_name" name="full_name" size="60" value="" />
                </p>
            </div>		
        <?php 
            $sASPFields = ob_get_clean();
        } //end:ENABLED_ASP
        return $sASPFields;
    }
}

if (!function_exists('validate_emails_from_csv')){
    /**
     * $sCSV          the CSV string of emails or a single email address 
     * $bMakeArray    if set to true will return an array rather than a CSV string
     */
    function validate_emails_from_csv($sCSV, $bMakeArray = false){
        $aRetVal = array(); // collect validated email addresses	
        // check if is a CSV array of emails
        if (strpos($sCSV, ',') !== false){
            $sCSV = str_replace(' ', '', $sCSV);
            $aTmp = explode(',', $sCSV);
        } else {
            // put single setting into array
            $aTmp = array($sCSV);
        }

        // validate the array
        foreach($aTmp as $sMailAddr){
            if (filter_var($sMailAddr, FILTER_VALIDATE_EMAIL)){
                $aRetVal[] = $sMailAddr;
            }
        }
        if ($bMakeArray){
            return $aRetVal;
        } else {
            return implode(',', $aRetVal);
        }
    }
}

function account_getConfig(){
    // default cfg file
    $sUseCfg = ACCOUNT_TOOL_PATH . '/account/Accounts.cfg.php'; // the original Accounts config file

    // possible override files
    $aOverrides = array(
        // later files in this array will take precedence if they exist
        ACCOUNT_TOOL_PATH.'/account/Accounts.custom.cfg.php',
        WB_PATH.'/modules/UserBase/account/Accounts.cfg.php',
        WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/Accounts.cfg.php',
    );
    foreach($aOverrides as $sCfgFile){
        if (file_exists($sCfgFile)){
            $sUseCfg = $sCfgFile;
        }
    }
    $aConfig = parse_ini_file($sUseCfg, true)['Signup_Account_Settings'];

    // work out if preferences.php and other areas should use own FE Template
    // ======================================================================	
    $aTemplates = array('preferences_template', 'login_template', 'signup_template');

    foreach($aTemplates as $str){
        if (isset($aConfig[$str]) && $aConfig[$str] != ''){
            if(file_exists(WB_PATH.'/templates/'.$aConfig[$str].'/index.php')){
                $aConfig[$str] = $aConfig[$str];
            } 
        }
        if($aConfig[$str] == ''){
            $aConfig[$str] = DEFAULT_TEMPLATE;
        }
    }	

    // work out the E-Mail Adress(es) of AccountsManager(s)
    // ====================================================		
    $sAMEmail = ''; // init AccountsManager E-Mail

    // read SuperAdmin's E-Mail from the DB
    $sSuperadminEmail = $GLOBALS['database']->get_one("SELECT `email` FROM `{TP}users` WHERE `user_id` = 1");	

    // validate E-Mail addresses and use Superadmin's E-Mail if empty or broken		
    if (isset($aConfig['accounts_manager_email']) && $aConfig['accounts_manager_email'] != ''){
        $sAMEmail  = $aConfig['accounts_manager_email'];
        $aAMEmails = validate_emails_from_csv($sAMEmail, true);		
        if (empty($aAMEmails)){			
            $sAMEmail = array($sSuperadminEmail);
        }
    }

    $mEmailFinal = !empty($aAMEmails) ? $aAMEmails : array($sAMEmail);
    if (isset($aConfig['accounts_manager_superadmin']) && $aConfig['accounts_manager_superadmin'] == true){
        if(is_array($mEmailFinal)){
            array_unshift($mEmailFinal, $sSuperadminEmail); // put SuperAdmin E-Mail on top of array
        }else{
            $mEmailFinal = array($sSuperadminEmail);
        }
    }
    $aConfig['accounts_manager_email'] = array_unique($mEmailFinal);

    // work out the Support E-Mail
    // ====================================================	
    $sSupportEmail = ''; // init Support E-Mail
    if (isset($aConfig['support_email']) && $aConfig['support_email'] != ''){
        $sSupportEmail = validate_emails_from_csv($aConfig['support_email']);
    }
    if($sSupportEmail == ''){
        $sSupportEmail = $aConfig['accounts_manager_email'][0];
    }	
    $aConfig['support_email'] = $sSupportEmail;

    return $aConfig;
}


// "AccountsManager" here, in this case, is the one who is responsible for accounts, signups etc.
function account_getAccountsManagerEmail()	{	
    return account_getConfig()['accounts_manager_email'];
}

// "AccountsManager" here, in this case, is the one who is responsible for accounts, signups etc.
function account_getSupportEmail() {
    $aCfg = account_getConfig();
    if(isset($aCfg['accounts_support_email']))
         return $aCfg['accounts_support_email'];
    else return SERVER_EMAIL;
}


/**
 * Returns an array of all possible language files, including overrides
 */
function account_getLanguageFiles(){
    $sLangDir = ACCOUNT_TOOL_PATH . '/languages';
    $aFiles     = array();
    // we need EN array in all cases because other languages may have missing keys
    $aFiles[] = $sLangDir.'/EN.php';
    // TOOL LANGUAGE FILES
    if (LANGUAGE != 'EN'){
        // override with default language if default language is not EN
        $sLangFile = $sLangDir.'/'.LANGUAGE.'.php';
        if (is_readable($sLangFile)) $aFiles[] = $sLangFile;
    }
    
    // DEFAULT_TEMPLATE LANGUAGE FILES
    if (LANGUAGE == 'EN'){
        // override with file from DEFAULT_TEMPLATE only if language is EN
        $sFile = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/languages/EN.php';
        if (is_readable($sFile))     $aFiles[] = $sFile;
    }
    if (LANGUAGE != 'EN'){
        // override with LANGUAGE file from DEFAULT_TEMPLATE
        $sFile = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/languages/'.LANGUAGE.'.php';
        if (is_readable($sFile))     $aFiles[] = $sFile;
    }
    return $aFiles;
	
}
function account_usernameAlreadyTaken($sUsername){
    $retVal = false;
    if ($sUsername != ''){
        $sSql = "SELECT `user_id` FROM `{TP}users` WHERE `username` = '".$sUsername."'";
        $retVal = $GLOBALS['database']->get_one($sSql);
    }
    return $retVal;
}

function account_emailAlreadyTaken($sEmail){
    $retVal = false;
    if ($sEmail != ''){
        $sSql = "SELECT `user_id` FROM `{TP}users` WHERE `email` = '".$sEmail."'";
        $retVal = $GLOBALS['database']->get_one($sSql);
    }
    return $retVal;
}


function account_getUserData($iUserID){
    global $database;
    $aConfig = array();
    $sSql = "SELECT * FROM `{TP}users` WHERE `user_id` = " . intval($iUserID);

    if ($rQuery = $database->query($sSql)){
        $aConfig = $rQuery->fetchRow(MYSQL_ASSOC);
    }

    return $aConfig;
}

function account_getTemplate($sTplName = ''){
    $sRetVal = '<'.$sTplName.'> Template File not found!';
    if ($sTplName != ''){ 
        $sFileName     = $sTplName.'.tpl.php';
        $sFileCore     = ACCOUNT_TOOL_PATH.'/templates/'.$sFileName;		 
        $sFileTemplate = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/templates/'.$sFileName;
        // core 
        if (file_exists($sFileCore))	     $sRetVal = $sFileCore;
        // override from template
        if (file_exists($sFileTemplate))  $sRetVal = $sFileTemplate; 
    }

    return $sRetVal;
}

function getEmailTemplate($sFileLoc = "", $sTag = "body", $aTokens = array()){
    $sRetVal = NULL;
    if ($sFileLoc != ""){
        $sLocParts = pathinfo($sFileLoc);
        //is there a custom version of this file??
        $sCustomFile = str_replace($sLocParts['filename'], $sLocParts['filename'].'.custom', $sFileLoc); 
        $sFileToRead = is_readable($sCustomFile) ? $sCustomFile : (is_readable($sFileLoc) ? $sFileLoc : '');
        if ($sFileToRead != ''){
            $sRetVal = file_get_contents($sFileToRead, true);
        }		
    }
    if ($sTag != 'body'){
        $sTag = trim($sTag);			
        $sRetVal = get_string_between_tags($sRetVal, $sTag);
    } else {
        $sRetVal = preg_replace( '/<!--(.|\s)*?-->/' , '' , $sRetVal ); // remove comments
    }
    if (!empty($aTokens)){
        $aTokens['WB_URL']       = WB_URL;
        $aTokens['MEDIA_URL']    = WB_URL.MEDIA_DIRECTORY;
        $aTokens['TEMPLATE_URL'] = WB_URL.'/templates/'.DEFAULT_TEMPLATE;
        $sRetVal = replace_vars($sRetVal, $aTokens);
    }
    return $sRetVal;
}



function account_sendChangeNotificationEmail($aTokenReplace, $sEmailSubject = ''){
    if(account_getConfig()['notify_on_user_changes'] == true){
        $mMailTo = account_getAccountsManagerEmail();
        $sEmailTemplateName = 'notify_on_changes';
        $sFromName = 'AccountsManagement';
        if($sEmailSubject == ''){
            $sEmailSubject = 'User changes on '.WB_URL;
        }
        return account_sendEmail($mMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject, $sFromName);
    } else {
        return;
    }
}
	
function account_sendEmail($mMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject = '', $sFromName = '', $sReplyTo = ''){
    global $MESSAGE;

    // get PLAIN TEXT-MAIL template 
    $sPlainBody = '';
    $sMailTxtTemplate = account_getCorrectEmailTplPath($sEmailTemplateName);	
    // E-Mail body
    if ($sTmp = getEmailTemplate($sMailTxtTemplate, 'body', $aTokenReplace)){
        $sPlainBody = $sTmp;
    }
    // E-Mail subject
    $sSubject = ($sEmailSubject != '') ? $sEmailSubject : 'E-Mail from "'. WEBSITE_TITLE .'"';
    if ($sTmp = getEmailTemplate($sMailTxtTemplate, 'subject', $aTokenReplace)){
        // The content between [subject] and [/subject] in the template takes preference!
        $sSubject = $sTmp;
    }	

    // $sFromName (overwrite if set in template)
    if ($sTmp = getEmailTemplate($sMailTxtTemplate, 'from_name', $aTokenReplace)){
        // The content between [from_name] and [/from_name] in the template takes preference!
        $sFromName = $sTmp;
    }

    // get HTML-MAIL template 
    $sHtmlBody = '';
    $aCfg = account_getConfig();
    if(isset($aCfg['use_html_email_templates']) && $aCfg['use_html_email_templates'] == true){
        $sMailHTMLTemplate = account_getCorrectEmailTplPath($sEmailTemplateName, true);
        if ( $sTmp = getEmailTemplate($sMailHTMLTemplate, 'body', $aTokenReplace)){
            $sHtmlBody = $sTmp;
        }
    }

    // make array of MailTo Adress(es)
    $aMailsTo = is_array($mMailTo) ? $mMailTo : array($mMailTo);

    // ************************************************ //
    //         PREPARE THE E-MAIL TO BE SENT            //
    // ************************************************ //
    $oMailer = new Mailer();
    if ($sFromName != '') { 
        $oMailer->FromName = $sFromName;                // FROM-NAME:
    } else {
        $oMailer->FromName = WEBSITE_TITLE;   
    }

    $oMailer->From = SERVER_EMAIL;                      // FROM:

    foreach ($aMailsTo as $sToAddr) {
        $oMailer->AddAddress($sToAddr);                 // TO:
    }

    $oMailer->Subject = $sSubject;                      // SUBJECT:

    if ($sHtmlBody != ''){
        $oMailer->Body = $sHtmlBody;                    // BODY: (HTML-MAIL)
    } else {
            // no HTML template, use TEXT-MAIL		
        $oMailer->Body = nl2br($sPlainBody);
    }

    $oMailer->AltBody = strip_tags($sPlainBody, '<a>'); // ALT-BODY: (PLAIN TEXT-MAIL)

    // tell phpmailer not to autouse TLS if not configured
    $oMailer->SMTPAutoTLS = false; 
    $oMailer->SMTPOptions = array( 
        // allow self_signed ssl
        'ssl' => array(
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        )
    ); 

    // ************************************************** //
    //   Check  if  there  are  any  send  mail  errors,  //
    //   otherwise  SEND  the email(s) and  return  true  //
    // ************************************************** //
    return (!$oMailer->Send()) ? $oMailer->ErrorInfo : true;
}

function account_getCorrectEmailTplPath($sEmailTemplateName, $bHtml = false){	
	// get the correct template for this mail
	$sLC = defined('LANGUAGE') ? LANGUAGE : defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN';	
	$sDirPathCore     = ACCOUNT_TOOL_PATH.'/email_templates/';
	$sDirPathTemplate = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/overrides/account/email_templates/';
	$sExtension = ($bHtml == true) ? '.tpl' : '.txt';
	$sMailTemplate = '/'.$sEmailTemplateName.$sExtension.'.php';
	
	$mRetVal = false;
	// core 	
	if (file_exists($sDirPathCore.$sLC.$sMailTemplate)){    
		$mRetVal = $sDirPathCore.$sLC.$sMailTemplate;
	} else {
		$mRetVal = $sDirPathCore.'EN'.$sMailTemplate;
	}
	
	// override from template
	if (file_exists($sDirPathTemplate.$sLC.$sMailTemplate)){
		$mRetVal = $sDirPathTemplate.$sLC.$sMailTemplate;
	} elseif (file_exists($sDirPathTemplate.'EN'.$sMailTemplate)) {
		$mRetVal = $sDirPathTemplate.'EN'.$sMailTemplate;
	}
	
	return $mRetVal;
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
    if ( preg_match('/[0-9a-f]{32}/i', $sConfirmCode) ) {
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
    if ($sDbCheckSum == $sCheckSum){	
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

 // get an associative array of group_id => group_name pairs
function usergroup_names_by_id($iGroupID = 0){
    $aGroups = array();
    $res = $GLOBALS['database']->query( "SELECT `group_id`, `name` FROM `{TP}groups`");
    while ($rec = $res->fetchRow()) {
        $aGroups[$rec['group_id']] = $rec['name'];
    }
    if($iGroupID > 0){
        if(!isset($aGroups[$iGroupID])) return;
        return $aGroups[$iGroupID];
    }
    return $aGroups;
}


function get_userbase_array($bExtendOnly = false){
    $sQueryUsers = ("SELECT * FROM `{TP}users`");
    $aUsers = array();
    if ($res = $GLOBALS['database']->query($sQueryUsers)) {
        for( $i = 0; $i < $res->numRows(); $i++ )
        {
            $aUsers[$i] = $res->fetchRow(MYSQL_ASSOC);	

            // make array of groups_id => group_name
            $aUsers[$i]['groups_id'] = str_replace(' ', '', $aUsers[$i]['groups_id']);
            $aGroupIDs = strpos($aUsers[$i]['groups_id'], ',') !== FALSE 
                ? explode(',', trim($aUsers[$i]['groups_id'])) 
                : $aUsers[$i]['groups_id'] = array($aUsers[$i]['groups_id']);		
            $aTmp = array();
            foreach ($aGroupIDs as $key=>$iGroupID){
                $aTmp[$iGroupID] = usergroup_names_by_id($iGroupID);
            }	
            // make comma separated list of group names
            $aUsers[$i]['user_groups'] = $aTmp; 
        }
    }
    return $aUsers;
}

function get_users_overview($bExtendOnly = false){
    global $TEXT, $TOOL_TXT;
    $sQueryUsers = ("SELECT `user_id` FROM `{TP}users`");
    $sJavaScriptArray = '';
    $aCollection = array();
    $aUsers = get_userbase_array($bExtendOnly);
    ob_start();
?>
<a class="iconlink" href="<?=UB_TOOL_URI?>&amp;pos=detail&amp;id=%d&amp;action=delete">
	<img src="/delete_16.png" alt="<?=$TOOL_TXT['DELETE'] ?>">
</a>
<?php 
$sLinkDelete = ob_get_clean();


ob_start();
?>
<a class="iconlink" href="<?=UB_TOOL_URI?>&amp;pos=detail&amp;id=%d&amp;action=edit">
	<img src="/modify.png" alt="<?=$TEXT['MODIFY'] ?>" />
</a>
<?php 
$sLinkEdit = ob_get_clean();

ob_start();
?>
<a class="pry" title='<?=$TEXT['VIEW'];?> User "%s"' href="<?=UB_TOOL_URI?>&amp;pos=detail&amp;id=%d&amp;action=edit" rel="<?=UB_MOD_URL?>/pry_profile.php?id=%d&action=edit">
	<i class="fa fa-1x fa-address-card"></i>
</a>
<?php 
    $sPryFunc = ob_get_clean();
    foreach ( $aUsers as $rec ) {

            $iID = $rec['user_id'];
            $aCollection[$iID]['language']    = $rec['language'];
            $aCollection[$iID]['user_id']     = $rec['user_id'];
            $aCollection[$iID]['username']    = $rec['display_name'].' <i>('.$rec['username'].')</i>';
            $aCollection[$iID]['usernameCsv'] = $rec['display_name'].' ('.$rec['username'].')';
            $aCollection[$iID]['email']       = $rec['email'];
            $aCollection[$iID]['groups']      = $rec['user_groups'];
            $aCollection[$iID]['actions']     = sprintf($sPryFunc, $rec['display_name'], $iID, $iID).' '; 
            $aCollection[$iID]['login_when']  = $rec['login_when'];
            $aCollection[$iID]['profile_url'] = sprintf(UB_TOOL_URI.'&amp;pos=detail&amp;id=%d&amp;action=edit', $iID);
            $aCollection[$iID]['signup_timestamp'] = isset($rec['signup_timestamp']) ? $rec['signup_timestamp'] : '';
    }
    return $aCollection;
}