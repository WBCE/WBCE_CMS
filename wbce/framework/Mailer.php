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
 */

if (!defined('WB_PATH')) {
    require_once dirname(__FILE__) . '/globalExceptionHandler.php';
    throw new IllegalFileException();
}

date_default_timezone_set('UTC');

// Include PHPMailer class
$sPath = WB_PATH . "/include/PHPMailer/src";
require $sPath . '/Exception.php';
require $sPath . '/OAuth.php';
require $sPath . '/PHPMailer.php';
require $sPath . '/SMTP.php';
require $sPath . '/POP3.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer extends PHPMailer
{
    public function __construct($exceptions = false)
    {
        parent::__construct($exceptions);

        $aCfg = $this->_getMailerCfg();
        $oMailer = $this;
        $oMailer->set('SMTPDebug', ((defined('DEBUG') && DEBUG) ? 2 : 0));   // Enable verbose debug output
        $oMailer->set('Debugoutput', 'error_log');

        // ***************************************************
        //   set routine for sending emails (phpmail or smtp)
        // ***************************************************
        if (isset($aCfg['routine']) && $aCfg['routine'] == "phpmail") {
            $oMailer->IsMail(); // use PHP mail() function for outgoing mails send by the CMS
        } elseif (isset($aCfg['routine']) && $aCfg['routine'] == "smtp") {
            // use SMTP for all outgoing mails send by the CMS
            $oMailer->isSMTP();
            $oMailer->set('SMTPAuth', false); // enable SMTP authentication
            $oMailer->set('Host', $aCfg['smtp_host']); // Set the hostname of the mail server
            $oMailer->set('Port', $aCfg['smtp_port']); // Set the SMTP port number - likely to be 25, 465 or 587
            $oMailer->set('SMTPSecure', $aCfg['smtp_secure']); // Set the encryption system to use - ssl (deprecated) or tls
            $oMailer->set('SMTPKeepAlive', false); // SMTP connection will be close after each email sent
            // check if SMTP authentification is required
            if (
                $aCfg['smtp_auth'] == true
                && (mb_strlen($aCfg['smtp_username']) > 1)
                && (mb_strlen($aCfg['smtp_password']) > 1)
            ) {
                // use SMTP authentification
                $oMailer->set('SMTPAuth', true); // enable SMTP authentication
                $oMailer->set('Username', $aCfg['smtp_username']); // set SMTP username
                $oMailer->set('Password', $aCfg['smtp_password']); // set SMTP password

                $oMailer->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        } else {
            // use SendMail transport
            print_r($this->_getMailerCfg());
            $oMailer->isSendmail();
        }

        // set default sender 'FromName'
        if ($oMailer->FromName == 'Root User') {
            if (isset($_SESSION['DISPLAY_NAME'])) {
                // FROM NAME: display name of user logged in
                $from_name = $_SESSION['DISPLAY_NAME'];
            } else {
                // FROM NAME: set default name
                $from_name = $aCfg['default_sendername'];
            }
        }

        $oMailer->setFrom($aCfg['server_email'], $from_name);

        // ***************************
        //  set default mail formats
        // ***************************
        $oMailer->set('WordWrap', 80);
        $oMailer->set('Timeout', 30);
        $oMailer->set('CharSet', defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8');
        $oMailer->SetLanguage((defined("LANGUAGE") ? strtolower(LANGUAGE) : 'en'), "language");
        $oMailer->IsHTML();   // Sets message type to HTML or plain.
    }

    /**
     * @brief  Gather the Mailer data array.
     *         If there is an array in the WB_PATH.'/config_mail.php'
     *         file, this config will be prefered over the data from
     *         the database.
     *
     * @return array
     */
    public function _getMailerCfg()
    {
        $aCfg = array(
            'routine' => 'phpmail', // required (may be set to 'smtp')
            'server_email' => '', // required
            'default_sendername' => 'WBCE Mailer', // required
            'smtp_host' => '', // required if SMPT
            'smtp_secure' => '', // required if SMPT
            'smtp_port' => 25, // required
            'smtp_auth' => false,
            'smtp_username' => '',
            'smtp_password' => ''
        );

        // ***********************************************************
        // Get mailer settings from file if file exists
        // WB_PATH/include/PHPMailer/config_mail.php file, if present
        // ***********************************************************
        $aCfgOverride = array();
        $sConfigFile = WB_PATH . '/include/PHPMailer/config_mail.php';
        if (is_readable($sConfigFile)) {
            $aCfgOverride = include $sConfigFile;
        }
        if (is_array($aCfgOverride) && !empty($aCfgOverride)) {
            #$aCfg = array();
            $aCfg = $aCfgOverride;
        } else {
            // *******************************************************
            //  Get mailer settings from Database.
            // *******************************************************
            //
            //  The following DB Fields will be retrieved from the DB:
            //
            //   'server_email',
            //   'wbmailer_routine',
            //   'wbmailer_default_sendername',
            //   'wbmailer_smtp_host',
            //   'wbmailer_smtp_auth',
            //   'wbmailer_smtp_username',
            //   'wbmailer_smtp_password',
            //
            //   New settings added with WBCE 1.3.4
            //   ----------------------------------
            //   'wbmailer_smtp_port',
            //   'wbmailer_smtp_secure',
            // *******************************************************
            $sSql = "SELECT * FROM `{TP}settings` "
                . "WHERE `name` LIKE ('wbmailer_%') OR `name` = 'server_email'";
            $rData = $GLOBALS['database']->query($sSql);

            while ($rec = $rData->fetchRow(MYSQLI_ASSOC)) {
                $sKey = str_replace('wbmailer_', '', $rec['name']);
                $aCfg[$sKey] = $rec['value'];

                if ($rec['name'] == 'server_email') {
                    if (filter_var($rec['value'], FILTER_VALIDATE_EMAIL) === false) {
                        $this->setError('Server E-Mail is empty or not valid');
                    }
                }

                if ($aCfg['routine'] == "smtp") {
                    switch ($rec['name']) {
                        case 'server_email':
                            $aCfg['server_email'] = $rec['value'];
                            break;
                        case 'wbmailer_smtp_host':
                            $aCfg['smtp_host'] = $rec['value'];
                            break;
                        case 'wbmailer_smtp_port':
                            $aCfg['smtp_port'] = intval($rec['value']);
                            break;
                        case 'wbmailer_smtp_secure':
                            $aCfg['smtp_secure'] = strtolower($rec['value']);
                            break;
                        case 'wbmailer_smtp_auth':
                            $aCfg['smtp_auth'] = (bool)$rec['value'];
                            break;
                        case 'wbmailer_smtp_username':
                            $aCfg['smtp_username'] = $rec['value'];
                            break;
                        case 'wbmailer_smtp_password':
                            $aCfg['smtp_password'] = $rec['value'];
                            break;
                        case 'wbmailer_default_sendername':
                            $aCfg['default_sendername'] = $rec['value'];
                            break;
                        default:
                            break;
                    }
                }
            } // end while loop
        }
        return $aCfg;
    }

    /**
     * @brief  Send messages using Sendmail.
     *         Will override isSendmail() in parent class.
     * @return void
     */
    public function isSendmail()
    {
        $ini_sendmail_path = ini_get('sendmail_path');
        if (!preg_match('/sendmail$/i', $ini_sendmail_path)) {
            if ($this->exceptions) {
                throw new phpmailerException('no sendmail available');
            }
        } else {
            $this->Sendmail = $ini_sendmail_path;
            $this->Mailer = 'sendmail';
        }
    }
}

// set alias for compatibility with older modules
class_alias('Mailer', 'wbmailer');
