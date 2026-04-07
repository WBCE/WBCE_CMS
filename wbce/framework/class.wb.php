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

// Prevent this file from being accessed directly
defined('WB_PATH') or die("Access Denied");

class Wb extends SecureForm
{
    // DirectOutput: private state replaces the old public $sDirectOutput property.
    // __set() below intercepts any legacy $wb->sDirectOutput = '...' assignments.
    private string  $directOutput      = '';
    private bool    $directPending     = false;
    private ?string $directContentType = null;

    public $page_sections = array();
    public $password_chars = 'a-zA-Z0-9\_\-\!\#\*\+\@\$\&\:\.';
    public $iPassMinLength = 12;

    /**
     * @brief  General initialization function performed
     *          when frontend or backend is loaded.
     */
    public function __construct($mode = SecureForm::FRONTEND)
    {
        parent::__construct($mode);
    }

    /**
     * @brief  Check if the Password matches the needed pattern and length
     *         and encode it correctly if true.
     *         This method will return an error message if false.
     *
     * @param string $sPassword
     * @param string $sNewPasswordRetyped
     * @return mixed   may be array if error message
     *                 or string with the correctly encoded password
     * @global array $MESSAGE
     */
    public function checkPasswordPattern($sPassword, $sNewPasswordRetyped = null)
    {
        global $MESSAGE;
        $iMinPassLength = 6;
        $bPasswordOk = false;
        $aErrMsg = array();
        if ($sPassword != '') {
            $sPattern = '/[^' . $this->password_chars . ']/';
            if (preg_match($sPattern, $sPassword)) {
                $aErrMsg[] = $MESSAGE['PREFERENCES_INVALID_CHARS'] . '[1]';
            } else {
                $bPasswordOk = true;
            }
            if ($sNewPasswordRetyped != null) {
                if ($sPassword != $sNewPasswordRetyped) {
                    // $bPasswordOk = false;
                    $aErrMsg[] = $MESSAGE['USERS_PASSWORD_MISMATCH'] . '[2]';
                }
            }
            if (strlen($sPassword) < $iMinPassLength) {
                // $bPasswordOk = false;
                $aErrMsg[] = $MESSAGE['USERS_PASSWORD_TOO_SHORT'] . '[3]';
            }
        }

        return (!empty($aErrMsg)) ? $aErrMsg : $this->doPasswordEncode($sPassword);
    }

    /**
     * @param string $sPassword
     */
    public function doPasswordEncode($sPassword)
    {
        if (function_exists('password_hash')) {
            return password_hash($sPassword, PASSWORD_DEFAULT);
        } else { // fallback to old behavior, hopefully never needed
            md5($sPassword);
        }
    }

    /**
     * @brief  Check if the Password is the correct one for a given user.
     *         This method will true if it matches, otherwise false false.
     *
     * @param int $iUserID
     * @param string $sPassword
     */
        /**
     * Checks if the supplied password is correct for the given user.
     * If it is an old md5 hash, it will be automatically upgraded to a modern hash.
     *
     * @param int    $userId
     * @param string $password
     * @return bool
     */
    public function doCheckPassword(int $userId, string $password): bool
    {
        // Get the password hash from database
        $sql = 'SELECT `password` FROM `{TP}users` 
                WHERE `user_id` = ? AND `active` = 1';

        $dbPasswordHash = $this->db->fetchValue($sql, [$userId]);

        if ($dbPasswordHash === '') {   // fetchValue returns '' on no result / error
            return false;
        }

        // Check if it's still an old md5 hash
        if (strpos($dbPasswordHash, '$') === false) {
            // Old md5 hash → verify with md5
            if ($dbPasswordHash === md5($password)) {
                // Password correct → upgrade to modern hash
                $newHash = $this->doPasswordEncode($password);

                $updateSql = 'UPDATE `{TP}users` 
                              SET `password` = ? 
                              WHERE `user_id` = ? AND `active` = 1';

                $this->db->query($updateSql, [$newHash, $userId]);

                return true;
            }

            return false; // wrong password (old md5)
        }

        // Modern password hash (recommended way)
        return password_verify($password, $dbPasswordHash);
    }

    /**
     * @brief  returns a password input field and prepares the insert queue
     *         for the password strength meter
     *
     * @param string $sNameAttr
     */
    public function passwordField($sNameAttr = '')
    {
        $sRetVal = '';
        if ($sNameAttr != '') {
            $sRetVal = '<input type="password" id="' . $sNameAttr . '" name="' . $sNameAttr . '" value="" class="wdt250" autocomplete="new-password" />';
            I::insertCssFile(WB_URL . '/include/password-strength-meter/password.min.css', 'HEAD BTM-', 'PwStrenght');
            I::insertJsFile(WB_URL . '/include/password-strength-meter/password.min.js', 'HEAD BTM-', 'PwStrenght');

            // Get String Translations
            $sLanguagesDir = WB_PATH . '/include/password-strength-meter/languages/';
            $sLanguageFile = $sLanguagesDir . strtolower(LANGUAGE) . '.js';
            if (file_exists($sLanguageFile)) {
                $sLangFile = get_url_from_path($sLanguageFile);
            } else {
                $sLangFile = get_url_from_path($sLanguagesDir . '/en.js');
            }
            I::insertJsFile($sLangFile, 'HEAD BTM-', 'PwStrenghtLang');
            $sToJs = <<<_JsCode
           jQuery(document).ready(function($) {
                $('#$sNameAttr').password({
                    minimumLength: $this->iPassMinLength,
                    enterPass:  PwStrenghtLang.enterPass,
                    shortPass:  PwStrenghtLang.shortPass,
                    badPass:    PwStrenghtLang.badPass,
                    goodPass:   PwStrenghtLang.goodPass,
                    strongPass: PwStrenghtLang.strongPass
                });
            });
            
_JsCode;
            I::insertJsCode($sToJs, 'HEAD BTM-', 'PwStrenght');
        }
        return $sRetVal;
    }

    /**
     * Queue content for direct output, bypassing normal page rendering.
     *
     * The full index.php pipeline (modules, template, OutputFilter) runs
     * to completion first. At the very end, sendDirectOutput() flushes all
     * output buffers, sends this content, and exits.
     *
     * Calls are additive: multiple setDirectOutput() calls append content.
     *
     * @param string      $content      The response body to send
     * @param string|null $contentType  Optional Content-Type header value
     */
    public function setDirectOutput(string $content, ?string $contentType = null): void
    {
        $this->directOutput  .= $content;
        $this->directPending  = true;
        if ($contentType !== null) {
            $this->directContentType = $contentType;
        }
    }

    /** Returns true if direct output has been queued. */
    public function hasDirectOutput(): bool
    {
        return $this->directPending;
    }

    /**
     * Send queued direct output and exit. No-op if nothing was queued.
     * Called once at the end of index.php 
     * and once in class Admin::print_footer()
     * in place of the old DirectOutput().
     */
    public function sendDirectOutput(): void
    {
        if (!$this->directPending) return;

        // Send Content-Type header if specified by the caller
        if ($this->directContentType !== null) {
            header('Content-Type: ' . $this->directContentType);
        }

        // Discard all buffered page output
        while (ob_get_level()) ob_end_clean();

        echo $this->directOutput;
        exit;
    }

    /**
     * @deprecated Use setDirectOutput() to queue content, sendDirectOutput() to send.
     * Kept for backward compatibility with older modules.
     */
    public function DirectOutput(string|false $content = false): void
    {
        if (is_string($content)) {
            $this->setDirectOutput($content);
        } else {
            $this->sendDirectOutput();
        }
    }

    /**
     * Intercept legacy $wb->sDirectOutput = '...' assignments.
     * Redirects them to setDirectOutput() so old modules keep working.
     */
    public function __set(string $name, mixed $value): void
    {
        if ($name === 'sDirectOutput' && is_string($value)) {
            $this->setDirectOutput($value);
            return;
        }
        $this->$name = $value;
    }

    /**
     * Am I Group Member (of the following groups)?
     * @brief   Check if current user is member of at least one of given groups
     *          NOTE: SuperAdmin (user_id = 1) is always member of ALL groups
     *
     * @param unspec $groups_list An array or a comma seperated list of group-ids
     * @return  bool   true: if current user is member of one of this groups, otherwise false
     */
    public function ami_group_member($mGroups = '')
    {
        if ($this->get_group_id() == 1) {
            return true;
        }
        return $this->is_group_match($mGroups, $this->get_groups_id());
    }

    /**
     * @brief   Get the current users main GROUP_ID.
     *         NOTE: a user may be member in differend user groups.
     *
     * @return  int
     */
    public function get_group_id()
    {
        return $this->get_session('GROUP_ID');
    }

    /**
     * @brief   Get SESSION data
     *
     * @param string $field
     * @return  string
     */
    public function get_session($field)
    {
        return (isset($_SESSION[$field]) ? $_SESSION[$field] : null);
    }

    /**
     * @brief   Check if one or more group_ids are in both group lists
     *
     * @param unspec $mGroups_1 : an array or a coma seperated list of group-ids
     * @param unspec $mGroups_2 : an array or a coma seperated list of group-ids
     * @param array  &$matches : an array-var whitch will return possible matches
     * @return  bool   true there is a match, otherwise false
     */
    public function is_group_match($mGroups_1 = '', $mGroups_2 = '', &$matches = null)
    {
        if ($mGroups_1 == '' || $mGroups_2 == '') {
            return false;
        }
        if (!is_array($mGroups_1)) {
            // it's either a single value or a CSV
            $mGroups_1 = explode(',', $mGroups_1);
        }
        if (!is_array($mGroups_2)) {
            // it's either a single value or a CSV
            $mGroups_2 = explode(',', $mGroups_2);
        }
        $matches = array_intersect($mGroups_1, $mGroups_2);
        return (sizeof($matches) != 0);
    }

    /**
     * @brief   Get the current users GROUPS_IDs.
     *          NOTE: a user may be member in differend user groups.
     *
     * @return  array
     */
    public function get_groups_id() : array
    {
        $session_groups = $this->get_session('GROUPS_ID');
        $groups = (
            (!empty($session_groups))
            ? explode(",", $session_groups)
            : []
        );
        return $groups;
    }

    /**
     * @brief   Check whether we should show a page or not (for front-end)
     *
     * @param array $page
     * @return  bool
     */
    public function show_page($page)
    {
        $retval = ($this->page_is_visible($page) && $this->page_is_active($page));
        return $retval;
    }

    /**
     * @brief   Check whether a page is visible or not.
     *          This will check page-visibility and user- and group-rights.
     *
     * @param array $page
     * @return  bool   false: If page-visibility is 'none' or 'deleted', or page-vis. is 'registered'
     *                        or 'private' and user isn't allowed to see the page.
     *                 true:  If page-visibility is 'public' or 'hidden', or page-vis. is 'registered'
     *                        or 'private' and user _is_ allowed to see the page.
     */
    public function page_is_visible($page)
    {
        $show_it = false; // shall we show the page_link?
        $page_id = $page['page_id'];
        $visibility = $page['visibility'];
        $viewing_groups = $page['viewing_groups'];
        $viewing_users = $page['viewing_users'];

        // First check if visibility is 'none', 'deleted'
        if ($visibility == 'none') {
            return (false);
        } elseif ($visibility == 'deleted') {
            return (false);
        }

        // Now check if visibility is 'hidden', 'private' or 'registered'
        if ($visibility == 'hidden') {
            // hidden: hide the menu-link, but show the page
            $show_it = true;
        } elseif ($visibility == 'private' || $visibility == 'registered') {
            // Check if the user is logged in
            if ($this->is_authenticated() == true) {
                // Now check if the user has perms to view the page
                $in_group = false;
                foreach ($this->get_groups_id() as $cur_gid) {
                    if (in_array($cur_gid, explode(',', $viewing_groups))) {
                        $in_group = true;
                    }
                }
                if ($in_group || in_array($this->get_user_id(), explode(',', $viewing_users))) {
                    $show_it = true;
                } else {
                    $show_it = false;
                }
            } else {
                $show_it = false;
            }
        } elseif ($visibility == 'public') {
            $show_it = true;
        } else {
            $show_it = false;
        }
        return ($show_it);
    }

    /**
     * @brief   Check if user is already authenticated (logged in)
     *          since vers. 1.4 it should be prefered to use
     *          the method isLoggedIn() instead.
     *
     * @return  bool
     */
    public function is_authenticated()
    {
        return $this->isLoggedIn();
    }

    /**
     * @brief   Check if the user is logged in
     *
     * @return  bool
     */
    public function isLoggedIn()
    {
        $iSessionUserID = $this->get_session('USER_ID');
        return ($iSessionUserID != null && $iSessionUserID != "" && is_numeric($iSessionUserID));
    }

    /**
     * @brief   Get the current users USER_ID
     *
     * @return  int
     */
    public function get_user_id()
    {
        return $this->get_session('USER_ID');
    }

    /**
     * @brief   Check if there is at least one active section on this page.
     *
     * @param array $page
     * @return  bool
     */
    public function page_is_active($page)
    {
        $has_active_sections = false;
        $now = time();
        $sql = "SELECT `publ_start`, `publ_end` FROM `{TP}sections` WHERE `page_id`= ?";
        $query_sections = $this->db->query($sql, [$page['page_id']]);
        if ($query_sections->numRows() != 0) {
            while ($section = $query_sections->fetchRow()) {
                if ($now < $section['publ_end'] && ($now > $section['publ_start'] || (int)$section['publ_start'] == 0) || $now > $section['publ_start'] && (int)$section['publ_end'] == 0) {
                    $has_active_sections = true;
                    break;
                }
            }
        }
        return ($has_active_sections);
    }

    /**
     * @brief   Check if the user is SuperAdmin(UserID = 1)
     *
     * @return  bool
     */
    public function isSuperAdmin()
    {
        return ($this->get_session('USER_ID') == 1);
    }

    /**
     * @brief   Check if the user is Admin (GroupID = 1)
     *
     * @return  bool
     */
    public function isAdmin()
    {
        if ($this->get_session('GROUP_ID') == 1) {
            return true;
        }
        return (in_array(1, $this->get_groups_id()));
    }

    /**
     * @brief   a dummy function left over from gpc
     *          we keep it just in case modules rely on it even if it does nothing anymore
     *
     * @param string $input
     * @return  string
     */
    public function strip_magic($input)
    {
        return $input;
    }

    /**
     * @brief   Escape backslashes for use with mySQL LIKE strings
     *
     * @param string $input
     * @return  string
     */
    public function escape_backslashes($input)
    {
        return str_replace("\\", "\\\\", $input);
    }

    /**
     * Generate full page_link based on the `link` content from the `{TP}pages` table.
     *
     * @param int|string|null $linkId   Page ID or link string
     * @return string                   Full URL to the page
     */
    public function page_link($linkId = null): string
    {
        // If no linkId is given, try to determine current page
        if ($linkId === null) {
            if (defined('PAGE_ID')) {
                $linkId = (int) PAGE_ID;
            } elseif (isset($_GET['page_id']) && is_numeric($_GET['page_id'])) {
                $linkId = (int) $_GET['page_id'];
            }
        }

        // If we have a numeric page ID → get link from database
        if (is_numeric($linkId)) {
            $sql = 'SELECT `link` FROM `{TP}pages` WHERE `page_id` = ?';
            $pageLink = $this->db->fetchValue($sql, [(int)$linkId]);

            if ($pageLink === '') {
                return ''; // page not found
            }

            return WB_URL . PAGES_DIRECTORY . $pageLink . PAGE_EXTENSION;
        }

        // Otherwise treat it as a manual link (e.g. external URL or mailto)
        $linkId = (string)$linkId;

        // Check for :// (external URL) or mailto:
        if (str_contains($linkId, '://') || str_starts_with($linkId, 'mailto:')) {
            return $linkId;
        }

        // Assume it's a relative page link
        return WB_URL . PAGES_DIRECTORY . $linkId . PAGE_EXTENSION;
    }

    /**
     * @brief   Get POST data and escape it
     *
     * @param string $field
     * @return  string
     */
    public function get_post_escaped($field)
    {
        return $this->get_post($field);
    }

    /**
     * @brief   Get POST data
     *
     * @param string $field
     * @return  string
     */
    public function get_post($field)
    {
        return (isset($_POST[$field]) ? $_POST[$field] : null);
    }

    /**
     * @brief   Get GET data
     *
     * @param string $field
     * @return  string
     */
    public function get_get($field)
    {
        return (isset($_GET[$field]) ? $_GET[$field] : null);
    }

    /**
     * @brief   Get SERVER data
     *
     * @param string $field
     * @return  string
     */
    public function get_server($field)
    {
        return (isset($_SERVER[$field]) ? $_SERVER[$field] : null);
    }

    /**
     * @brief   Get the current users GROUP_NAMEs as CSV string.
     *          NOTE: a user may be member in differend user groups.
     *
     * @return  string
     */
    public function get_group_name()
    {
        return implode(",", $this->get_session('GROUP_NAME'));
    }

    /**
     * @brief   Get the current users GROUP_NAMEs as array.
     *          NOTE: a user may be member in differend user groups.
     *
     * @return  array
     */
    public function get_groups_name()
    {
        return $this->get_session('GROUP_NAME');
    }

    /**
     * @brief   Get the current users USERNAME
     *
     * @return  string
     */
    public function get_username()
    {
        return $this->get_session('USERNAME');
    }

    /**
     * @brief   Get the current users DISPLAY_NAME
     *
     * @return  string
     */
    public function get_display_name()
    {
        return remove_special_characters($this->get_session('DISPLAY_NAME'));
    }

    /**
     * @brief   Get the current users EMAIL address
     *
     * @return  string
     */
    public function get_email()
    {
        return $this->get_session('EMAIL');
    }

    /**
     * @brief   Get the current users HOME_FOLDER
     *
     * @return  string
     */
    public function get_home_folder()
    {
        return $this->get_session('HOME_FOLDER');
    }

    /**
     * @brief   Validate the supplied email address
     *
     * @param string
     * @return  string
     */
    public function validate_email($email)
    {
        if (function_exists('idn_to_ascii')) {
            // use pear if available
            $email = @idn_to_ascii($email);
        } else {
            require_once WB_PATH . '/include/idna_convert/idna_convert.class.php';
            $IDN = new idna_convert();
            $email = $IDN->encode($email);
            unset($IDN);
        }
        // regex from NorHei 2011-01-11
        $retval = preg_match("/^((([!#$%&'*+\\-\/\=?^_`{|}~\w])|([!#$%&'*+\\-\/\=?^_`{|}~\w][!#$%&'*+\\-\/\=?^_`{|}~\.\w]{0,}[!#$%&'*+\\-\/\=?^_`{|}~\w]))[@]\w+(([-.]|\-\-)\w+)*\.\w+(([-.]|\-\-)\w+)*)$/", $email);
        return ($retval != false);
    }

    /**
     * @brief   set one or more bit in a integer value
     *
     * @param int $value Reference to the integer, containing the value
     * @param int $bits2set The bitmask which should be added to the value
     * @return  void
     */
    public function bit_set(&$value, $bits2set)
    {
        $value |= $bits2set;
    }

    /**
     * @brief   reset one or more bit from a integer value
     *
     * @param int $value Reference to the integer, containing the value
     * @param int $bits2reset The bitmask which should be removed from value
     * @return  void
     */
    public function bit_reset(&$value, $bits2reset)
    {
        $value &= ~$bits2reset;
    }

    /**
     * @brief   check if one or more bit in a integer value is set
     *
     * @param int $value Reference to the integer, containing the value
     * @param int $bits2set The bitmask which should be added to value
     * @return  void
     */
    public function bit_isset($value, $bits2test)
    {
        return (($value & $bits2test) == $bits2test);
    }

    /**
     * @brief   Print a success message which then automatically redirects
     *          the user to a specified page
     *
     * @param mixed $uMsg may be a single string or an array
     * @param string $sRedirectUri URI to the redirect page
     * @return  string
     */
    public function print_success($uMsg, $sRedirectUri = 'index.php', $bAutoFooter = false)
    {
        $this->messageBox($uMsg, 'success', $sRedirectUri, $bAutoFooter);
    }

    /**
     * since vers. 1.4.0
     * @brief   Print a modal box
     *
     * @param mixed $uMsg may be a single string or an array
     * @param string $sRedirectUri URI for the redirect
     * @return  string
     */
    public function messageBox($uMsg, $sType = 'info', $sRedirectUri = 'index.php', $bAutoFooter = false, $bUseRedirect = true)
    {
        if (!is_array($uMsg)) {
            $uMsg = array(
                $uMsg
            );
        }

        // get correct redirect time
        $iRedirectTime = (defined('REDIRECT_TIMER')) ? REDIRECT_TIMER : 0;
        $iRedirectTime = ($iRedirectTime > 10000) ? 10000 : $iRedirectTime;

        $aToTwig = array(
            'MESSAGE_TYPE' => $sType,
            'MESSAGES' => $uMsg,
            'REDIRECT_URL' => $sRedirectUri,
            'REDIRECT_TIME' => $iRedirectTime,
            'USE_REDIRECT' => $bUseRedirect
        );
        $this->getThemeFile('message_box.twig', $aToTwig);
        if ($bAutoFooter == true) {
            if (method_exists($this, "print_footer")) {
                $this->print_footer();
            }
            exit();
        }
    }

    public function getThemeFile($sTplName = '', $aToTwig = array())
    {
        $aTemplateLocs = array();
        $aCheckDirs = array(
            THEME_PATH . '/templates/',
            WB_PATH . '/templates/theme_fallbacks/templates/'
        );
        foreach ($aCheckDirs as $dir) {
            if (is_dir($dir)) {
                $aTemplateLocs[] = $dir;
            }
        }
        $oTwig = getTwig($aTemplateLocs);
        $oTemplate = $oTwig->load($sTplName);
        $oTemplate->display($aToTwig);
    }

    /**
     * @brief   Print an error message with a "back" link/button to a specified page
     *
     * @param mixed $uMsg may be a single string or an array
     * @param string $sRedirectUri URI for the "back" link
     * @return  string
     */
    public function print_error($uMsg, $sRedirectUri = 'index.php', $bAutoFooter = true)
    {
        $this->messageBox($uMsg, 'error', $sRedirectUri, $bAutoFooter);
    }

    /**
     * @brief Validate and send a mail
     *
     * @param string $fromaddress // FROM:
     * @param string $toaddress // TO:
     * @param string $subject // SUBJECT
     * @param string $message // The Message to be send
     * @param string $fromname // From Name
     * @return boolean
     */
    public function mail($fromaddress, $toaddress, $subject, $message, $fromname = '')
    {
        // INTEGRATED OPEN SOURCE PHPMAILER CLASS FOR SMTP SUPPORT AND MORE.
        // SOME SERVICE PROVIDERS DO NOT SUPPORT SENDING MAIL VIA PHP AS IT DOES NOT PROVIDE SMTP AUTHENTICATION.
        // NEW WBMAILER CLASS IS ABLE TO SEND OUT MESSAGES USING SMTP WHICH RESOLVE THESE ISSUE. (C. Sommer)

        $fromaddress = preg_replace('/[\r\n]/', '', $fromaddress);
        $toaddress = preg_replace('/[\r\n]/', '', $toaddress);
        $subject = preg_replace('/[\r\n]/', '', $subject);

        // create PHPMailer object and define default settings
        $myMail = new Mailer();

        // set user defined FROM address
        if ($fromaddress != '') {
            if ($fromname != '') {
                $myMail->FromName = $fromname;
            }

            $myMail->From = $fromaddress; // FROM:
            $myMail->AddReplyTo($fromaddress); // REPLY TO:
        }

        // define recepient and information to send out
        $myMail->AddAddress($toaddress); // TO:
        $myMail->Subject = $subject; // SUBJECT
        $myMail->Body = nl2br($message); // CONTENT (HTML)
        $myMail->AltBody = strip_tags($message); // CONTENT (TEXT)

        // check if there are any send mail errors and return accordingly
        if (!$myMail->Send()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @brief   Checks if there is an override Backend Theme template file
     *
     * @param string $sThemeFile set the template file name including the file extension
     * @return  string the relative theme path
     *
     */
    public function correct_theme_source($sThemeFile = 'start.htt')
    {
        $sSysThemeFile = WB_PATH . '/templates/theme_fallbacks/templates/' . $sThemeFile;
        $sOverrideFile = THEME_PATH . '/templates/' . $sThemeFile;
        if (file_exists($sOverrideFile)) {
            return $sOverrideFile;
        } elseif (file_exists($sSysThemeFile)) {
            return $sSysThemeFile;
        } else {
            die('Following Backend Theme file is missing: ' . $sThemeFile);
        }
    }

    /**
     * @brief   Check if a foldername doesn't have invalid characters
     *
     * @param string $str to check
     * @return  bool
     */
    public function checkFolderName($str)
    {
        return !(preg_match('#\^|\\\|\/|\.|\?|\*|"|\'|\<|\>|\:|\|#i', $str) ? true : false);
    }

    /**
     * @brief   Check the given path to make sure current path
     *          is within given basedir normally document root
     *
     * @param string $sCurrentPath
     * @param string $sBaseDir
     * @return string $sCurrentPath or bool FALSE
     */
    public function checkpath($sCurrentPath, $sBaseDir = WB_PATH)
    {
        // Clean the cuurent path
        $sCurrentPath = rawurldecode($sCurrentPath);
        $sCurrentPath = realpath($sCurrentPath);
        $sBaseDir = realpath($sBaseDir);

        // $sBaseDir needs to exist in the $sCurrentPath
        $pos = stripos($sCurrentPath, $sBaseDir);

        if ($pos === false) {
            return false;
        } elseif ($pos == 0) {
            return $sCurrentPath;
        } else {
            return false;
        }
    }

   /**
    * Registers module files (CSS, JS, jQuery, system variables) and inserts them into the DOM
    * using the Insert class (I::) methods.
    *
    * @param string $modfileType  css|js|jquery|js_sysvars
    * @param string $context      frontend|backend  (determines which modfiles are collected)
    * @return void
    */
    /**
     * Registers module files (CSS, JS, jQuery, system variables) and inserts them 
     * into the DOM using the Insert class (I::) methods.
     *
     * This is the main public entry point for modules/themes to register their assets.
     *
     * @param string $modfileType  css|js|jquery|js_sysvars
     * @param string $context      'frontend' or 'backend'
     * @return void
     */
    public function registerModfiles(string $modfileType = 'css', string $context = 'frontend'): void
    {
        // Collect all relevant modfiles for the given context
        $toInsert = $this->collectModfiles($context);

        $modfileType = strtolower(trim($modfileType));

        switch ($modfileType) {

            case 'css':
                if (!empty($toInsert['css']) && is_array($toInsert['css'])) {
                    foreach ($toInsert['css'] as $cssFile) {
                        I::insertCssFile($cssFile, 'HEAD MODFILES');
                    }
                }
                break;

            case 'jquery':
                $jqueryFiles = [
                    WB_URL . '/include/jquery/jquery-min.js',
                    WB_URL . '/include/jquery/jquery-insert.js',
                    WB_URL . '/include/jquery/jquery-migrate-min.js',
                ];

                // Add jQuery UI theme (from module or fallback to include)
                $themeFile = WB_URL . '/modules/jquery/jquery_theme.js';
                if (file_exists(str_replace(WB_URL, WB_PATH, $themeFile))) {
                    $jqueryFiles[] = $themeFile;
                } else {
                    $jqueryFiles[] = WB_URL . '/include/jquery/jquery_theme.js';
                }

                // Add template-specific jQuery frontend file if it exists
                $templateJquery = TEMPLATE_DIR . '/jquery_frontend.js';
                if (file_exists(str_replace(WB_URL, WB_PATH, $templateJquery))) {
                    $jqueryFiles[] = $templateJquery;
                }

                foreach ($jqueryFiles as $jsFile) {
                    I::insertJsFile($jsFile, 'HEAD MODFILES');
                }
                break;

            case 'js':
                // Insert system variables first (always at the top)
                $jsSysvars = $this->buildJsSystemVariables();
                I::insertJsCode($jsSysvars, 'HEAD TOP+', 'js_sysvars');

                // JS files for <head>
                if (!empty($toInsert['js_head']) && is_array($toInsert['js_head'])) {
                    foreach ($toInsert['js_head'] as $jsFile) {
                        I::insertJsFile($jsFile, 'HEAD MODFILES');
                    }
                }

                // JS files before </body>
                if (!empty($toInsert['js_body']) && is_array($toInsert['js_body'])) {
                    foreach ($toInsert['js_body'] as $jsFile) {
                  #      I::insertJsFile($jsFile, 'BODY BTM-');
                    }
                }
                break;

            case 'js_sysvars':
                        // System variables are already handled in the 'js' case.
                        // This case exists only for backward compatibility.
                break;

            default:
                        // Unknown modfile type → do nothing (silent)
                break;
        }
    }
    
    /**
     * Builds the JavaScript system variables block that will be inserted into the <head>.
     *
     * These variables are available globally for all JavaScript code on the page.
     *
     * @return string JavaScript code with variable declarations
     */
    private function buildJsSystemVariables(): string
    {
        $js = "\t\t"; // indentation for clean HTML output

        // WB_URL (and optional URL alias for compatibility)
        if (defined('URL_VAR_COMPATIBILITY_MODE') && URL_VAR_COMPATIBILITY_MODE === true) {
            $js .= "var URL = WB_URL = '" . WB_URL . "';\n";
        } else {
            $js .= "var WB_URL = '" . WB_URL . "';\n";
        }

        // Language
        if (defined('LANGUAGE')) {
            $js .= "\t\tvar LANGUAGE     = '" . strtolower(LANGUAGE) . "';\n";
        }

        // Page ID
        if (defined('PAGE_ID')) {
            $js .= "\t\tvar PAGE_ID      = " . (int) PAGE_ID . ";\n";
        } elseif (isset($_REQUEST['page_id']) && is_numeric($_REQUEST['page_id'])) {
            $js .= "\t\tvar PAGE_ID      = " . (int) $_REQUEST['page_id'] . ";\n";
        }

        // Section ID
        if (isset($_REQUEST['section_id']) && is_numeric($_REQUEST['section_id'])) {
            $js .= "\t\tvar SECTION_ID   = " . (int) $_REQUEST['section_id'] . ";\n";
        }

        // Template directory
        if (defined('TEMPLATE_DIR')) {
            $js .= "\t\tvar TEMPLATE_DIR = '" . TEMPLATE_DIR . "';\n";
        }

        // Backend-only variables
        if (!defined('WB_FRONTEND')) {
            if (defined('THEME_URL')) {
                $js .= "\t\tvar THEME_URL    = '" . THEME_URL . "';\n";
            }
            if (defined('ADMIN_URL')) {
                $js .= "\t\tvar ADMIN_URL    = '" . ADMIN_URL . "';\n";
            }
        }

        // Session timeout
        $sessionTimeout = $this->get_session_timeout();
        $js .= "\t\tvar SESSION_TIMEOUT = '" . $sessionTimeout . "';\n";

        return $js;
    }

    /**
     * Collects module directories and their modfiles for registerModfiles().
     *
     * @param string $context  'frontend' or 'backend'
     * @return array
     */
    private function collectModfiles(string $context = 'frontend'): array
    {
        $toInsert = [];
        $modules = [];

        // 1. Snippets – only in frontend
        if (defined('WB_FRONTEND')) {
            $modules = array_merge($modules, $this->db->fetchAll(
                "SELECT `directory` AS `module_dir` 
                 FROM `{TP}addons` 
                 WHERE `function` LIKE '%snippet%'",
                []
            ));
        }

        // 2. Page-type modules (backend only)
        $pageId = null;
        if (defined('PAGE_ID')) {
            $pageId = PAGE_ID;
        } elseif (isset($_REQUEST['page_id']) &&
                  strposm($_SERVER['PHP_SELF'], ['pages/sections.php', 'pages/settings.php']) === false) {
            $pageId = (int) $_REQUEST['page_id'];
        }

        if ($pageId !== null && !defined('WB_FRONTEND')) {
            $modules = array_merge($modules, $this->db->fetchAll(
                "SELECT `module` AS `module_dir` 
                 FROM `{TP}sections` 
                 WHERE `page_id` = ?",
                [$pageId]
            ));
        }

        // 3. Tools (backend)
        if (!empty($_GET['tool'])) {
            $modules = array_merge($modules, $this->db->fetchAll(
                "SELECT `directory` AS `module_dir` 
                 FROM `{TP}addons` 
                 WHERE `function` LIKE '%tool%' 
                   AND `directory` = ?",
                [$_GET['tool']]
            ));
        }

        // Retrieve modfiles from each module
        foreach ($modules as $row) {
            $moduleDir = $row['module_dir'] ?? '';
            if (empty($moduleDir)) {
                continue;
            }

            $filesByType = $this->retrieveModfilesFromDir($moduleDir, $context);

            foreach ($filesByType as $type => $wrappedFiles) {
                foreach ($wrappedFiles as $wrapped) {
                    $toInsert[$type][] = $wrapped[0];   // extract the actual URL (string)
                }
            }
        }

        return $toInsert;
    }
    
    /**
     * Retrieves modfiles (css, js_head, js_body) from a single module directory.
     *
     * Returns files in a backward-compatible format so that existing code
     * like block_contents() and get_section_content() continues to work.
     *
     * Each file is wrapped in an array → $sFile[0] contains the actual URL.
     *
     * @param string $moduleDir Module directory name
     * @param string $context   'frontend' or 'backend'
     * @return array
     */
    public function retrieveModfilesFromDir(string $moduleDir, string $context = 'frontend'): array
    {
        $collection = [
            'css'     => [],
            'js_head' => [],
            'js_body' => []
        ];

        $modPath = '/modules/' . $moduleDir . '/';

        $addFile = function (string $relativePath, string $type) use (&$collection, $modPath): void {
            $fullPath = WB_PATH . $modPath . $relativePath;
            $fullUrl  = WB_URL  . $modPath . $relativePath;

            if (file_exists(str_replace('%s', '.override', $fullPath))) {
                $collection[$type][] = str_replace('%s', '.override', $fullUrl);
            }

            if (file_exists(str_replace('%s', '', $fullPath))) {
                $collection[$type][] = str_replace('%s', '', $fullUrl);
            }
        };

        $addFile($context . '%s.css',     'css');
        $addFile($context . '%s.js',      'js_head');
        $addFile($context . '_body%s.js', 'js_body');

        // Backward compatible format: each file wrapped in array
        $result = [];
        foreach ($collection as $type => $files) {
            $result[$type] = array_map(fn($url) => [$url], $files);
        }

        return $result;
    }

    /**
     * @brief   get the correct session timeout value
     *
     * @return  string
     */
    public function get_session_timeout()
    {
        if ($sSessionTimeout = Settings::get("wb_session_timeout")) {
        } elseif ($sSessionTimeout = Settings::get("wb_secform_timeout")) {
        } else {
            $sSessionTimeout = "7200";
        }
        return $sSessionTimeout;
    }

    /**
     * introduced with WBCE 1.4.0
     * (Will reduce a lot of redundand code, in FE and BE alike.)
     *
     * @brief  Return an array of all the sections of the page
     *
     * @param bool $bExcludeNonPublicised
     * @return array
     * @global int $page_id
     */
    public function get_page_sections($iPageID = null, $bExcludeNonPublicised = false)
    {
        $aSections = array();

        if ($iPageID == null) {
            global $page_id;
            $iPageID = defined('PAGE_ID') ? PAGE_ID : $page_id;
        } else {
            $iPageID = (int)$iPageID;
        }

        if ($iPageID > 0) {
            // Get all sections for this page
            $sSql = 'SELECT * FROM `{TP}sections` WHERE `page_id` = ? ORDER BY `position`';
            if ($resSections = $this->db->query($sSql, [$iPageID])) {
                while ($rec = $resSections->fetchRow(MYSQLI_ASSOC)) {
                    if ($bExcludeNonPublicised == true) {
                        // skip sections that are not publicised
                        $iNowTime = time();
                        if (!(($iNowTime <= $rec['publ_end'] || $rec['publ_end'] == 0) && ($iNowTime >= $rec['publ_start'] || $rec['publ_start'] == 0))) {
                            continue;
                        }
                    }
                    $aSections[$rec['section_id']] = $rec;
                    $aSections[$rec['section_id']]['module_name'] = $this->get_module_name($rec['module']);
                }
            }
        }
        return $aSections;
    }
    
    /**
     * Resolve a string value (name or description) for a module.
     * Improved handling to make use of the new 
     * Lang class single languages.php file solution.
     *
     * Lookup order:
     *   1. languages/LANGUAGE.php — variable extraction via get_variable_content()
     *   2. language.php (single-file format) — array key lookup
     *   3. info.php — variable extraction
     *   4. DB`{TP}addons` table column fallback
     *
     * @param string   $modDir    Module directory name
     * @param string[] $varNames  Variable names to try in order (first match wins)
     * @param string   $dbColumn  Column to read from the addons table as last resort
     * @return string
     */
    private function _resolveModuleString(string $modDir, array $varNames, string $dbColumn): string
    {
        $modBase = WB_PATH . '/modules/' . $modDir;
        $value   = '';

        // ── 1. languages/LANGUAGE.php ─────────────────────────────────────────
        $langFile = $modBase . '/languages/' . LANGUAGE . '.php';
        if (file_exists($langFile)) {
            $data  = @file_get_contents($langFile);
            foreach ($varNames as $var) {
                $temp = get_variable_content($var, $data, true, false);
                if ($temp !== false && $temp !== '') { $value = $temp; break; }
            }
        }

        // ── 2. language.php (single-file format) ──────────────────────────────
        // Format: ['EN' => ['module_name' => '...'], 'DE' => ['module_name' => '...']]
        if ($value === '') {
            $singleFile = $modBase . '/languages.php';
            if (is_file($singleFile)) {
                $all = include $singleFile;
                if (is_array($all)) {
                    // Active locale overrides EN
                    $langData = array_merge($all['EN'] ?? [], $all[LANGUAGE] ?? []);
                    foreach ($varNames as $var) {
                        if (!empty($langData[$var])) { $value = (string)$langData[$var]; break; }
                    }
                }
            }
        }

        // ── 3. info.php ───────────────────────────────────────────────────────
        if ($value === '') {
            $infoFile = $modBase . '/info.php';
            if (file_exists($infoFile)) {
                $data = @file_get_contents($infoFile);
                foreach ($varNames as $var) {
                    $temp = get_variable_content($var, $data, true, false);
                    if ($temp !== false && $temp !== '') { $value = $temp; break; }
                }
            }
        }

        // ── 4. addons table ───────────────────────────────────────────────────
        if ($value === '') {
            $value = (string)($this->db->fetchValue(
                "SELECT `$dbColumn` FROM `{TP}addons` WHERE `directory` = ?",
                [$modDir]
            ) ?? '');
        }

        return $value;
    }

    /**
     * Get the module name from language file, single language.php,
     * info.php or the addons table as fallback.
     *
     * @param string $modDir        Module directory name
     * @param bool   $showOriginal  Append the original DB name in parentheses
     * @param string $hem           Format string for original name (default: ' (%s)')
     * @param string $modType       'page', 'tool', 'snippet', ...
     * @return string
     */
    public function get_module_name(
        string $modDir = '',
        bool   $showOriginal = false,
        string $hem          = ' (%s)',
        string $modType      = 'page'
    ): string {
        if (empty($modDir)) return '';

        // Type-specific name takes priority over generic module_name
        $varNames = $modType !== 'page'
            ? [$modType . '_name', 'module_name']
            : ['module_name'];

        $moduleName = $this->_resolveModuleString($modDir, $varNames, 'name');

        // Last resort: use directory name
        if ($moduleName === '') $moduleName = $modDir;

        if ($showOriginal) {
            $original = (string)($this->db->fetchValue(
                "SELECT `name` FROM `{TP}addons` WHERE `directory` = ?",
                [$modDir]
            ) ?? $modDir);
            $moduleName .= sprintf($hem, $original);
        }

        return $moduleName;
    }

    /**
     * Get the module description from language file, single language.php,
     * info.php or the addons table as fallback.
     *
     * @param string $modDir    Module directory name
     * @param string $modType   'page', 'tool', 'snippet', ...
     * @return string
     */
    public function get_module_description(string $modDir = '', string $modType = 'page'): string
    {
        if (empty($modDir)) return '';

        $varNames = ['module_description'];
        if ($modType !== 'page') $varNames[] = $modType . '_description';

        $description = $this->_resolveModuleString($modDir, $varNames, 'description');

        return str_replace('{WB_URL}', WB_URL, $description);
    }
    
    /**
     * @brief   Legacy method, kept in place to maintain compatibility with older modules
     *
     * @param   string $input
     * @return  string -> unchanged
     */
    public function add_slashes($input)
    {       
        if (defined('SQL_DEBUG') && SQL_DEBUG) {
            trigger_error(
                'add_slashes() was called but ignored because the new PDO database class is active. ' .
                'PDO handles escaping automatically.', 
                E_USER_NOTICE
            );
        }
        return $input; // return without escaping 
    }
    
    /**
     * @brief   Legacy method, kept in place to maintain compatibility with older modules
     *
     * @param   string $input
     * @return  string -> unchanged
     */
    public function strip_slashes($input)
    {
        return $input;
    }    
}