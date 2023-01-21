<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 *
 * @brief     This file contains class Account which is used
 *            for handling frontend signup, login and user accounts.
 *            This class is also being used with the tool_account_settings
 *            AdminTool introduced with WBCE 1.4.0
 *
 * @author    Christian M. Stefan <stefek@designthings.de>
 *
 * @copyright http://www.gnu.org/licenses/lgpl.html (GNU LGPLv2 or any later)
 */

//no direct file access
if (count(get_included_files()) == 1) {
    header("Location: ../index.php", true, 301);
}

defined('ACCOUNT_TOOL_PATH') or define('ACCOUNT_TOOL_PATH', WB_PATH . '/modules/tool_account_settings');

class Accounts extends Frontend
{
    public $sAccountAdminPath = '';
    public $cfg = array();
    public $sCfgFile = '';

    public function __construct()
    {
        parent::__construct(SecureForm::FRONTEND);
        $this->prepareConfigIniFile();
        $this->cfg = $this->getConfig();
    }

    public function prepareConfigIniFile()
    {
        $this->sCfgFile = ACCOUNT_TOOL_PATH . '/account/Accounts.cfg.php';
        if (!file_exists($this->sCfgFile)) {
            rename(
                ACCOUNT_TOOL_PATH . '/account/Accounts.cfg.NEW.php',
                $this->sCfgFile
            );
        }
    }

    public function getConfig()
    {
        $aConfig = parse_ini_file($this->sCfgFile, true)['Signup_Account_Settings'];

        // work out if preferences.php and other areas should use own FE Template
        // ======================================================================
        $aTemplates = array('preferences_template', 'login_template', 'signup_template');

        foreach ($aTemplates as $str) {
            if (isset($aConfig[$str]) && $aConfig[$str] != '') {
                if (file_exists(WB_PATH . '/templates/' . $aConfig[$str] . '/index.php')) {
                    $aConfig[$str] = $aConfig[$str];
                }
            }
            if ($aConfig[$str] == '') {
                $aConfig[$str] = DEFAULT_TEMPLATE;
            }
        }

        // work out the E-Mail Adress(es) of AccountsManager(s)
        // ====================================================
        $sAMEmail = ''; // init AccountsManager E-Mail

        // read SuperAdmin's E-Mail from the DB
        $sSuperadminEmail = $GLOBALS['database']->get_one("SELECT `email` FROM `{TP}users` WHERE `user_id` = 1");

        // validate E-Mail addresses and use Superadmin's E-Mail if empty or broken
        if (isset($aConfig['accounts_manager_email']) && $aConfig['accounts_manager_email'] != '') {
            $sAMEmail = $aConfig['accounts_manager_email'];
            $aAMEmails = $this->validate_emails_from_csv($sAMEmail, true);
            if (empty($aAMEmails)) {
                $sAMEmail = array($sSuperadminEmail);
            }
        }

        $mEmailFinal = !empty($aAMEmails) ? $aAMEmails : array($sAMEmail);
        if (isset($aConfig['accounts_manager_superadmin']) && $aConfig['accounts_manager_superadmin'] == true) {
            if (is_array($mEmailFinal)) {
                array_unshift($mEmailFinal, $sSuperadminEmail); // put SuperAdmin E-Mail on top of array
            } else {
                $mEmailFinal = array($sSuperadminEmail);
            }
        }
        $aConfig['accounts_manager_email'] = array_unique($mEmailFinal);

        // work out the Support E-Mail
        // ====================================================
        $sSupportEmail = ''; // init Support E-Mail
        if (isset($aConfig['support_email']) && $aConfig['support_email'] != '') {
            $sSupportEmail = $this->validate_emails_from_csv($aConfig['support_email']);
        }
        if ($sSupportEmail == '') {
            $sSupportEmail = $aConfig['accounts_manager_email'][0];
        }
        $aConfig['support_email'] = $sSupportEmail;

        return $aConfig;
    }

    /**
     * $sCSV          the CSV string of emails or a single email address
     * $bMakeArray    if set to true will return an array rather than a CSV string
     */
    public function validate_emails_from_csv($sCSV, $bMakeArray = false)
    {
        $aRetVal = array(); // collect validated email addresses
        // check if is a CSV array of emails
        if (strpos($sCSV, ',') !== false) {
            $sCSV = str_replace(' ', '', $sCSV);
            $aTmp = explode(',', $sCSV);
        } else {
            // put single setting into array
            $aTmp = array($sCSV);
        }

        // validate the array
        foreach ($aTmp as $sMailAddr) {
            if (filter_var($sMailAddr, FILTER_VALIDATE_EMAIL)) {
                $aRetVal[] = $sMailAddr;
            }
        }
        if ($bMakeArray) {
            return $aRetVal;
        } else {
            return implode(',', $aRetVal);
        }
    }

    /**
     * Returns an array of all possible language files, including overrides
     */
    public function getLanguageFiles()
    {
        $sLangDir = ACCOUNT_TOOL_PATH . '/languages';
        $aFiles = array();
        // we need EN array in all cases because other languages may have missing keys
        $aFiles[] = $sLangDir . '/EN.php';
        // TOOL LANGUAGE FILES
        if (LANGUAGE != 'EN') {
            // override with default language if default language is not EN
            $sLangFile = $sLangDir . '/' . LANGUAGE . '.php';
            if (is_readable($sLangFile)) {
                $aFiles[] = $sLangFile;
            }
        }

        // DEFAULT_TEMPLATE LANGUAGE FILES
        if (LANGUAGE == 'EN') {
            // override with file from DEFAULT_TEMPLATE only if language is EN
            $sFile = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/languages/EN.php';
            if (is_readable($sFile)) {
                $aFiles[] = $sFile;
            }
        }
        if (LANGUAGE != 'EN') {
            // override with LANGUAGE file from DEFAULT_TEMPLATE
            $sFile = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/languages/' . LANGUAGE . '.php';
            if (is_readable($sFile)) {
                $aFiles[] = $sFile;
            }
        }
        return $aFiles;
    }

    public function usernameAlreadyTaken($sUsername)
    {
        $retVal = false;
        if ($sUsername != '') {
            $sSql = "SELECT `user_id` FROM `{TP}users` WHERE `username` = '" . $sUsername . "'";
            $retVal = $GLOBALS['database']->get_one($sSql);
        }
        return $retVal;
    }

    public function emailAlreadyTaken($sEmail)
    {
        $retVal = false;
        if ($sEmail != '') {
            $sSql = "SELECT `user_id` FROM `{TP}users` WHERE `email` = '" . $sEmail . "'";
            $retVal = $GLOBALS['database']->get_one($sSql);
        }
        return $retVal;
    }

    public function getUserData($iUserID)
    {
        global $database;
        $aConfig = array();
        $sSql = "SELECT * FROM `{TP}users` WHERE `user_id` = " . intval($iUserID);

        if ($rQuery = $database->query($sSql)) {
            $aConfig = $rQuery->fetchRow(MYSQLI_ASSOC);
        }

        return $aConfig;
    }

    public function useTwigTemplate($sTplName = '', $aToTwig = array())
    {
        $aTemplateLocs = array();
        $aCheckDirs = array(
            WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/templates/',
            ACCOUNT_TOOL_PATH . '/templates/',
        );
        foreach ($aCheckDirs as $dir) {
            if (is_dir($dir)) {
                $aTemplateLocs[] = $dir;
            }
        }
        $oTwig = getTwig($aTemplateLocs);
        $oTwig->addGlobal('CURRENT_DIR', get_url_from_path(dirname($this->getTemplate($sTplName))));
        $oTemplate = $oTwig->load($sTplName);
        $oTemplate->display($aToTwig);
    }

    public function getTemplate($sTplName = '')
    {
        $sRetVal = '<' . $sTplName . '> Template File not found!';
        if ($sTplName != '') {
            $sFileName = $sTplName;
            $sFileCore = ACCOUNT_TOOL_PATH . '/templates/' . $sFileName;
            $sFileTemplate = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/templates/' . $sFileName;
            // core
            if (file_exists($sFileCore)) {
                $sRetVal = $sFileCore;
            }
            // override from template
            if (file_exists($sFileTemplate)) {
                $sRetVal = $sFileTemplate;
            }
        }

        return $sRetVal;
    }

    public function sendChangeNotificationEmail($aTokenReplace, $sEmailSubject = '')
    {
        if ($this->cfg['notify_on_user_changes'] == true) {
            $mMailTo = $this->cfg['accounts_manager_email'];
            $sEmailTemplateName = 'notify_on_changes';
            $sFromName = 'AccountsManagement';
            if ($sEmailSubject == '') {
                $sEmailSubject = 'User changes on ' . WB_URL;
            }
            return $this->sendEmail($mMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject, $sFromName);
        } else {
            return;
        }
    }

    public function sendEmail($mMailTo, $aTokenReplace, $sEmailTemplateName, $sEmailSubject = '', $sFromName = '', $sReplyTo = '')
    {
        global $MESSAGE;

        // get PLAIN TEXT-MAIL template
        $sPlainBody = '';
        $sMailTxtTemplate = $this->getCorrectEmailTplPath($sEmailTemplateName);
        // E-Mail body
        if ($sTmp = $this->getEmailTemplate($sMailTxtTemplate, 'body', $aTokenReplace)) {
            $sPlainBody = $sTmp;
        }
        // E-Mail subject
        $sSubject = ($sEmailSubject != '') ? $sEmailSubject : 'E-Mail from "' . WEBSITE_TITLE . '"';
        if ($sTmp = $this->getEmailTemplate($sMailTxtTemplate, 'subject', $aTokenReplace)) {
            // The content between [subject] and [/subject] in the template takes preference!
            $sSubject = $sTmp;
        }

        // $sFromName (overwrite if set in template)
        if ($sTmp = $this->getEmailTemplate($sMailTxtTemplate, 'from_name', $aTokenReplace)) {
            // The content between [from_name] and [/from_name] in the template takes preference!
            $sFromName = $sTmp;
        }

        // get HTML-MAIL template
        $sHtmlBody = '';
        $aCfg = $this->cfg;
        if (isset($aCfg['use_html_email_templates']) && $aCfg['use_html_email_templates'] == true) {
            $sMailHTMLTemplate = $this->getCorrectEmailTplPath($sEmailTemplateName, true);
            if ($sTmp = $this->getEmailTemplate($sMailHTMLTemplate, 'body', $aTokenReplace)) {
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

        if ($sHtmlBody != '') {
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
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // ************************************************** //
        //   Check  if  there  are  any  send  mail  errors,  //
        //   otherwise  SEND  the email(s) and  return  true  //
        // ************************************************** //
        return ($oMailer->Send()) ? true : $oMailer->ErrorInfo;
    }

    public function getCorrectEmailTplPath($sEmailTemplateName, $bHtml = false)
    {
        // get the correct template for this mail
        $sLC = defined('LANGUAGE') ? LANGUAGE : (defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN');
        $sDirPathCore = ACCOUNT_TOOL_PATH . '/email_templates/';
        $sDirPathTemplate = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/email_templates/';
        $sExtension = ($bHtml == true) ? '.tpl' : '.txt';
        $sMailTemplate = '/' . $sEmailTemplateName . $sExtension . '.php';

        $mRetVal = false;
        // core
        if (file_exists($sDirPathCore . $sLC . $sMailTemplate)) {
            $mRetVal = $sDirPathCore . $sLC . $sMailTemplate;
        } else {
            $mRetVal = $sDirPathCore . 'EN' . $sMailTemplate;
        }

        // override from template
        if (file_exists($sDirPathTemplate . $sLC . $sMailTemplate)) {
            $mRetVal = $sDirPathTemplate . $sLC . $sMailTemplate;
        } elseif (file_exists($sDirPathTemplate . 'EN' . $sMailTemplate)) {
            $mRetVal = $sDirPathTemplate . 'EN' . $sMailTemplate;
        }

        return $mRetVal;
    }

    public function getEmailTemplate($sFileLoc = "", $sTag = "body", $aTokens = array())
    {
        $sRetVal = null;
        if ($sFileLoc != "") {
            $sLocParts = pathinfo($sFileLoc);
            //is there a custom version of this file??
            $sCustomFile = str_replace($sLocParts['filename'], $sLocParts['filename'] . '.custom', $sFileLoc);
            $sFileToRead = is_readable($sCustomFile) ? $sCustomFile : (is_readable($sFileLoc) ? $sFileLoc : '');
            if ($sFileToRead != '') {
                $sRetVal = file_get_contents($sFileToRead, true);
            }
        }
        if ($sTag != 'body') {
            $sTag = trim($sTag);
            $sRetVal = get_string_between_tags($sRetVal, $sTag);
        } else {
            $sRetVal = preg_replace('/<!--(.|\s)*?-->/', '', $sRetVal); // remove comments
        }
        if (!empty($aTokens)) {
            $aTokens['WB_URL'] = WB_URL;
            $aTokens['MEDIA_URL'] = WB_URL . MEDIA_DIRECTORY;
            $aTokens['TEMPLATE_URL'] = WB_URL . '/templates/' . DEFAULT_TEMPLATE;
            $sRetVal = replace_vars($sRetVal, $aTokens);
        }
        return $sRetVal;
    }

    public function genEmailLinkFromUri($sUri)
    {
        return '<a href="' . $sUri . '">' . $sUri . '</a>';
    }

    /**
     * retrieve user_id from signup_confirmcode $_GET parameter provided via the confirmation link
     */
    public function userIdFromConfirmcode($sConfirmCode)
    {
        global $database;
        $retVal = false;
        if (preg_match('/[0-9a-f]{32}/i', $sConfirmCode)) {
			$sConfirmCode = addslashes($sConfirmCode);
            $sSql = "SELECT `user_id` FROM `{TP}users` WHERE `signup_confirmcode` = '" . $sConfirmCode . "'";
            $retVal = $database->get_one($sSql);
        }
        return $retVal;
    }

    /**
     * verify and validate the check_sum $_GET parameter provided via the confirmation link
     */
    public function checkConfirmSum($sCheckSum, $iUserID)
    {
        global $database;
        $bRetVal = false;
        $sDbCheckSum = $database->get_one("SELECT `signup_checksum` FROM `{TP}users` WHERE `user_id` = " . $iUserID);
        if ($sDbCheckSum == $sCheckSum) {
            $bRetVal = true;
        }
        return $bRetVal;
    }

    // get an associative array of group_id => group_name pairs

    /**
     * Generate a random password and hash it
     */
    public function GenerateRandomPassword()
    {
        $sPassword = '';
        $salt = "abcdefghjklmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!_-:#*+$@&";
        $i = 0;
        while ($i <= 10) {
            $num = rand(0,70);
            $tmp = substr($salt, $num, 1);
            $sPassword = $sPassword . $tmp;
            $i++;			
        }
        return $sPassword;
    }

    public function get_users_overview($bExtendOnly = false)
    {
        global $TEXT, $TOOL_TXT;
        $sJavaScriptArray = '';
        $aCollection = array();
        $aUsers = $this->get_userbase_array($bExtendOnly);
        $sToolUri = ADMIN_URL . '/admintools/tool.php?tool=' . ADMIN_TOOL_DIR;
        ob_start(); ?>
        <a class="iconlink" href="<?= $sToolUri ?>&amp;pos=detail&amp;id=%d&amp;action=delete">
            <img src="/delete_16.png" alt="<?= $TOOL_TXT['DELETE'] ?>">
        </a>
        <?php
        $sLinkDelete = ob_get_clean();


        ob_start(); ?>
        <a class="iconlink" href="<?= $sToolUri ?>&amp;pos=detail&amp;id=%d&amp;action=edit">
            <img src="/modify.png" alt="<?= $TEXT['MODIFY'] ?>"/>
        </a>
        <?php
        $sLinkEdit = ob_get_clean();

        $sPryFunc = '';
        if (defined('UB_MOD_URL')) {
            ob_start(); ?>
            <a class="pry" title='<?= $TEXT['VIEW']; ?> User "%s"'
               href="<?= $sToolUri ?>&amp;pos=detail&amp;id=%d&amp;action=edit"
               rel="<?= UB_MOD_URL ?>/pry_profile.php?id=%d&action=edit">
                <i class="fa fa-1x fa-address-card"></i>
            </a>
            <?php
            $sPryFunc = ob_get_clean();
        }

        foreach ($aUsers as $rec) {
            $iID = $rec['user_id'];
            $aCollection[$iID]['active'] = $rec['active'];
            $aCollection[$iID]['reg_method'] = $this->_getRegMethod($rec['signup_confirmcode']);
            $aCollection[$iID]['language'] = $rec['language'];
            $aCollection[$iID]['user_id'] = $rec['user_id'];
            $aCollection[$iID]['username'] = $rec['display_name'] . ' <i>(' . $rec['username'] . ')</i>';
            $aCollection[$iID]['usernameCsv'] = $rec['display_name'] . ' (' . $rec['username'] . ')';
            $aCollection[$iID]['email'] = $rec['email'];
            $aCollection[$iID]['groups'] = $rec['user_groups'];
            $aCollection[$iID]['actions'] = sprintf($sPryFunc, $rec['display_name'], $iID, $iID) . ' ';
            $aCollection[$iID]['login_when'] = ($rec['login_when'] != 0) ? $rec['login_when'] + TIMEZONE : '';
            $aCollection[$iID]['profile_url'] = sprintf($sToolUri . '&amp;pos=detail&amp;id=%d&amp;action=edit', $iID);
            $aCollection[$iID]['signup_timestamp'] = ($rec['signup_timestamp'] != 0) ? $rec['signup_timestamp'] + TIMEZONE : '';
        }
        return $aCollection;
    }

    public function get_userbase_array($bExtendOnly = false)
    {
        $sQueryUsers = ("SELECT * FROM `{TP}users`");
        $aUsers = array();
        if ($res = $GLOBALS['database']->query($sQueryUsers)) {
            for ($i = 0; $i < $res->numRows(); $i++) {
                $aUsers[$i] = $res->fetchRow(MYSQLI_ASSOC);

                // make array of groups_id => group_name
                $aUsers[$i]['groups_id'] = str_replace(' ', '', $aUsers[$i]['groups_id']);
                $aGroupIDs = strpos($aUsers[$i]['groups_id'], ',') !== false
                    ? explode(',', trim($aUsers[$i]['groups_id']))
                    : $aUsers[$i]['groups_id'] = array($aUsers[$i]['groups_id']);
                $aTmp = array();
                foreach ($aGroupIDs as $key => $iGroupID) {
                    $aTmp[$iGroupID] = $this->usergroup_names_by_id($iGroupID);
                }
                // make comma separated list of group names
                $aUsers[$i]['user_groups'] = $aTmp;
            }
        }
        return $aUsers;
    }

    public function usergroup_names_by_id($iGroupID = 0)
    {
        $aGroups = array();
        $res = $GLOBALS['database']->query("SELECT `group_id`, `name` FROM `{TP}groups`");
        while ($rec = $res->fetchRow()) {
            $aGroups[$rec['group_id']] = $rec['name'];
        }
        if ($iGroupID > 0) {
            if (!isset($aGroups[$iGroupID])) {
                return;
            }
            return $aGroups[$iGroupID];
        }
        return $aGroups;
    }

    private function _getRegMethod($sConfirmCode)
    {
        $sRetVal = 'N/A';
        if ($sConfirmCode == 'install-script') {
            $sRetVal = 'on System Setup';
        } elseif (strpos($sConfirmCode, 'by') !== false) {
            $iAdminID = (int)explode(': ', $sConfirmCode)[1];
            if ($iAdminID == 1) {
                $sRetVal = 'by SuperAdmin';
            } else {
                $sRetVal = 'by Admin (uID: ' . $iAdminID . ')';
            }
        } elseif (strpos($sConfirmCode, 'signup') !== false) {
            $iGroupID = (int)explode(': ', $sConfirmCode)[1];
            $sGroup = $this->_oDb->get_one("SELECT `name` FROM `{TP}groups`WHERE `group_id` = " . $iGroupID);
            $sRetVal = 'on Signup (' . $sGroup . ')';
        }
        return $sRetVal;
    }
}

/**
 * @brief  Build the Tab Navigation used in AdminTools
 *         and other parts of the Backend
 *
 *         The provided array should consist of
 *         pos_parameter => array(LinkName, IconCode)
 *         for each tab element
 *
 *         example:
 *         ====================================================================
 *         array(
 *             'tool_overview' => array($TOOL_TXT['OVERVIEW'], 'user-circle'),
 *             'config'        => array($TOOL_TXT['CONFIG'], 'calendar'),
 *         );
 *  *
 * @param array $aTabs
 * @return array
 */
function renderToolTabs(array $aTabs)
{
    $sPos = isset($_GET["pos"]) ? $_GET["pos"] : key($aTabs);
    $aTabs = array_reverse($aTabs);
    $aRetVal = array();
    $i = 0;
    foreach ($aTabs as $sKey => $aValues) {
        $aRetVal[$i]['pos'] = $sKey;
        $aRetVal[$i]['a_class'] = ($sPos == $sKey) ? ' sel' : '';
        $aRetVal[$i]['li_class'] = ($sPos == $sKey) ? ' class="actionSel"' : '';
        $aRetVal[$i]['link_name'] = $aValues[0];
        $aRetVal[$i]['icon'] = $aValues[1];
        $i++;
    }
    return $aRetVal;
}
