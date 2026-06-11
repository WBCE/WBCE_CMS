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
 * @brief     Frontend signup, login and user-account management.
 *            Also used by the tool_account_settings AdminTool (since 1.4.0).
 *
 * @author  Christian M. Stefan (https://www.wbEasy.de)
 * @copyright http://www.gnu.org/licenses/lgpl.html (GNU LGPLv2 or any later)
 */
declare(strict_types=1);

defined('WB_PATH') or die('No direct access allowed');

defined('ACCOUNT_TOOL_PATH') or define('ACCOUNT_TOOL_PATH', WB_PATH . '/modules/tool_account_settings');

class Accounts extends Frontend
{
    public string $accountAdminPath = '';
    public array  $cfg              = [];
    public string $cfgFile          = '';

    /** Boot: locate config INI and populate $this->cfg. */
    public function __construct()
    {
        parent::__construct(SecureForm::FRONTEND);
        $this->prepareConfigIniFile();
        $this->cfg = $this->getConfig();
    }

    /** Ensure the config INI file exists, copying the .NEW template on first run. */
    public function prepareConfigIniFile(): void
    {
        $this->cfgFile = ACCOUNT_TOOL_PATH . '/account/Accounts.cfg.php';
        if (!file_exists($this->cfgFile)) {
            rename(
                ACCOUNT_TOOL_PATH . '/account/Accounts.cfg.NEW.php',
                $this->cfgFile
            );
        }
    }

    /**
     * Parse Accounts.cfg.php and resolve template paths, manager e-mail(s)
     * and support e-mail into a ready-to-use config array.
     */
    public function getConfig(): array
    {
        $config = parse_ini_file($this->cfgFile, true)['Signup_Account_Settings'];

        // Resolve frontend template keys; fall back to DEFAULT_TEMPLATE when unset.
        foreach (['preferences_template', 'login_template', 'signup_template'] as $key) {
            if (empty($config[$key])) {
                $config[$key] = DEFAULT_TEMPLATE;
            }
        }

        // Resolve AccountsManager e-mail address(es).
        $superadminEmail = $this->db->fetchValue("SELECT `email` FROM `{TP}users` WHERE `user_id` = 1");
        $managerEmails   = [];
        $managerEmail    = '';

        if (!empty($config['accounts_manager_email'])) {
            $managerEmail  = $config['accounts_manager_email'];
            $managerEmails = $this->validateEmailsFromCsv($managerEmail, true);
            if (empty($managerEmails)) {
                $managerEmails = [$superadminEmail];
            }
        }

        $finalEmails = !empty($managerEmails) ? $managerEmails : [$managerEmail];
        if (!empty($config['accounts_manager_superadmin'])) {
            array_unshift($finalEmails, $superadminEmail);
        }
        $config['accounts_manager_email'] = array_unique($finalEmails);

        // Resolve support e-mail, falling back to the first manager address.
        $supportEmail = '';
        if (!empty($config['support_email'])) {
            $supportEmail = $this->validateEmailsFromCsv($config['support_email']);
        }
        if ($supportEmail === '') {
            $supportEmail = $config['accounts_manager_email'][0];
        }
        $config['support_email'] = $supportEmail;

        return $config;
    }

    /**
     * Validate a single e-mail address or comma-separated list.
     * Returns an array when $asArray is true, otherwise a CSV string.
     *
     * @return array|string
     */
    public function validateEmailsFromCsv(string $csv, bool $asArray = false): array|string
    {
        $result = [];
        $parts  = str_contains($csv, ',')
            ? explode(',', str_replace(' ', '', $csv))
            : [$csv];

        foreach ($parts as $address) {
            if ($this->validate_email($address)) {
                $result[] = $address;
            }
        }

        return $asArray ? $result : implode(',', $result);
    }

    /**
     * Return an ordered list of language file paths for the accounts module:
     * EN first, then the active locale, with theme-level overrides appended last.
     */
    public function getLanguageFiles(): array
    {
        $langDir = ACCOUNT_TOOL_PATH . '/languages';
        $files   = [$langDir . '/EN.php'];

        if (LANGUAGE !== 'EN') {
            $langFile = $langDir . '/' . LANGUAGE . '.php';
            if (is_readable($langFile)) {
                $files[] = $langFile;
            }
        }

        // Theme-level override: EN when active language is EN, active language otherwise.
        $overrideBase = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/languages/';
        $file = $overrideBase . (LANGUAGE === 'EN' ? 'EN' : LANGUAGE) . '.php';
        if (is_readable($file)) {
            $files[] = $file;
        }

        return $files;
    }

    /**
     * Return the user_id if $username is already registered, false/null otherwise.
     */
    public function usernameAlreadyTaken(string $username): mixed
    {
        if ($username === '') {
            return false;
        }
        return $this->db->fetchValue(
            'SELECT `user_id` FROM `{TP}users` WHERE `username` = ?',
            [$username]
        );
    }

    /**
     * Return the user_id if $email is already registered, false/null otherwise.
     */
    public function emailAlreadyTaken(string $email): mixed
    {
        if ($email === '') {
            return false;
        }
        return $this->db->fetchValue(
            'SELECT `user_id` FROM `{TP}users` WHERE `email` = ?',
            [$email]
        );
    }

    /** Return all columns for a single user row, or [] if not found. */
    public function getUserData(int $userId): array
    {
        return $this->db->fetchAll(
            'SELECT * FROM `{TP}users` WHERE `user_id` = ?',
            [$userId]
        )[0] ?? [];
    }

    /**
     * Render an accounts Twig template, resolving theme-level overrides
     * over the core template directory.
     */
    public function useTwigTemplate(string $tplName = '', array $toTwig = []): void
    {
        $templateLocs = [];
        $checkDirs    = [
            WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/templates/',
            ACCOUNT_TOOL_PATH . '/templates/',
        ];
        foreach ($checkDirs as $dir) {
            if (is_dir($dir)) {
                $templateLocs[] = $dir;
            }
        }
        $oTwig = getTwig($templateLocs);
        $oTwig->addGlobal('CURRENT_DIR', get_url_from_path(dirname($this->getTemplate($tplName))));
        $oTemplate = $oTwig->load($tplName);
        $oTemplate->display($toTwig);
    }

    /**
     * Resolve the file-system path of an accounts template,
     * preferring a theme-level override over the core template.
     */
    public function getTemplate(string $tplName = ''): string
    {
        if ($tplName === '') {
            return '';
        }
        $fileCore     = ACCOUNT_TOOL_PATH . '/templates/' . $tplName;
        $fileOverride = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/templates/' . $tplName;

        $result = file_exists($fileCore)     ? $fileCore : '<' . $tplName . '> Template File not found!';
        $result = file_exists($fileOverride) ? $fileOverride : $result;

        return $result;
    }

    /**
     * Send a user-change notification to the AccountsManager,
     * if the notify_on_user_changes setting is enabled.
     */
    public function sendChangeNotificationEmail(array $tokenReplace, string $emailSubject = ''): mixed
    {
        if (!$this->cfg['notify_on_user_changes']) {
            return null;
        }
        if ($emailSubject === '') {
            $emailSubject = 'User changes on ' . WB_URL;
        }
        return $this->sendEmail(
            $this->cfg['accounts_manager_email'],
            $tokenReplace,
            'notify_on_changes',
            $emailSubject,
            'AccountsManagement'
        );
    }

    /**
     * Compose and dispatch a plain-text (and optionally HTML) e-mail.
     * Returns true on success, or the mailer error string on failure.
     */
    public function sendEmail(
        mixed  $mailTo,
        array  $tokenReplace,
        string $templateName,
        string $emailSubject = '',
        string $fromName     = '',
        string $replyTo      = ''
    ): bool|string {
        $txtTemplate = $this->getCorrectEmailTplPath($templateName);
        $plainBody   = $this->getEmailTemplate($txtTemplate, 'body',      $tokenReplace);
        $subject     = ($emailSubject !== '') ? $emailSubject : 'E-Mail from "' . WEBSITE_TITLE . '"';

        if ($tmp = $this->getEmailTemplate($txtTemplate, 'subject',   $tokenReplace)) { $subject  = $tmp; }
        if ($tmp = $this->getEmailTemplate($txtTemplate, 'from_name', $tokenReplace)) { $fromName = $tmp; }

        $htmlBody = '';
        if (!empty($this->cfg['use_html_email_templates'])) {
            $htmlTemplate = $this->getCorrectEmailTplPath($templateName, true);
            $htmlBody     = $this->getEmailTemplate($htmlTemplate, 'body', $tokenReplace);
        }

        $mailRecipients = is_array($mailTo) ? $mailTo : [$mailTo];

        $oMailer           = new Mailer();
        $oMailer->FromName = ($fromName !== '') ? $fromName : WEBSITE_TITLE;
        $oMailer->From     = SERVER_EMAIL;

        foreach ($mailRecipients as $address) {
            $oMailer->AddAddress($address);
        }

        $oMailer->Subject = $subject;
        $oMailer->Body    = ($htmlBody !== '') ? $htmlBody : nl2br($plainBody);
        $oMailer->AltBody = strip_tags($plainBody, '<a>');

        $oMailer->SMTPAutoTLS = false;
        $oMailer->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

        return $oMailer->Send() ? true : $oMailer->ErrorInfo;
    }

    /**
     * Resolve the correct e-mail template file path for the active language,
     * falling back to EN; theme-level overrides take precedence over core templates.
     *
     * @return string|false  Absolute path, or false if no core file exists.
     */
    public function getCorrectEmailTplPath(string $templateName, bool $isHtml = false): string|false
    {
        $langCode     = defined('LANGUAGE') ? LANGUAGE : (defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'EN');
        $coreDir      = ACCOUNT_TOOL_PATH . '/email_templates/';
        $templateDir  = WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/overrides/account/email_templates/';
        $ext          = $isHtml ? '.tpl' : '.txt';
        $mailTemplate = '/' . $templateName . $ext . '.php';

        // Core: active language with EN fallback.
        $path = file_exists($coreDir . $langCode . $mailTemplate)
            ? $coreDir . $langCode . $mailTemplate
            : $coreDir . 'EN' . $mailTemplate;

        // Theme override.
        if (file_exists($templateDir . $langCode . $mailTemplate)) {
            $path = $templateDir . $langCode . $mailTemplate;
        } elseif (file_exists($templateDir . 'EN' . $mailTemplate)) {
            $path = $templateDir . 'EN' . $mailTemplate;
        }

        return $path;
    }

    /**
     * Read an e-mail template file, extract the named tag section,
     * substitute token placeholders, and return the processed string.
     */
    public function getEmailTemplate(string $filePath = '', string $tag = 'body', array $tokens = []): string
    {
        $result = '';
        if ($filePath !== '') {
            $pathInfo   = pathinfo($filePath);
            $customFile = str_replace($pathInfo['filename'], $pathInfo['filename'] . '.custom', $filePath);
            $fileToRead = is_readable($customFile)
                ? $customFile
                : (is_readable($filePath) ? $filePath : '');
            if ($fileToRead !== '') {
                $result = file_get_contents($fileToRead, true) ?: '';
            }
        }
        if ($tag !== 'body') {
            $result = get_string_between_tags($result, trim($tag)) ?? '';
        } else {
            $result = preg_replace('/<!--(.|\s)*?-->/', '', $result) ?? '';
        }
        if (!empty($tokens)) {
            $tokens['WB_URL']       = WB_URL;
            $tokens['MEDIA_URL']    = WB_URL . MEDIA_DIRECTORY;
            $tokens['TEMPLATE_URL'] = WB_URL . '/templates/' . DEFAULT_TEMPLATE;
            $result = replace_vars($result, $tokens);
        }
        return $result;
    }

    /** Wrap a URL in an HTML anchor tag. */
    public function genEmailLinkFromUrl(string $url): string
    {
        return '<a href="' . $url . '">' . $url . '</a>';
    }

    /**
     * Look up the user_id matching the given confirmation code.
     * Returns the user_id on success, or false if the code is invalid.
     */
    public function userIdFromConfirmcode(string $confirmCode): mixed
    {
        if (!preg_match('/[0-9a-f]{32}/i', $confirmCode)) {
            return false;
        }
        return $this->db->fetchValue(
            'SELECT `user_id` FROM `{TP}users` WHERE `signup_confirmcode` = ?',
            [$confirmCode]
        );
    }

    /** Verify that the supplied checksum matches the one stored for this user. */
    public function checkConfirmSum(string $checksum, int $userId): bool
    {
        $dbChecksum = $this->db->fetchValue(
            'SELECT `signup_checksum` FROM `{TP}users` WHERE `user_id` = ?',
            [$userId]
        );
        return $dbChecksum == $checksum;
    }

    /** Generate a random 13-character password from a safe character set. */
    public function GenerateRandomPassword(): string
    {
        $charset  = 'abcdefghjklmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!_-:#*+$@&';
        $password = '';
        for ($i = 0; $i <= 12; $i++) {
            $password .= $charset[random_int(0, 70)];
        }
        return $password;
    }

    /**
     * Build the users-overview collection for the AccountsTool data table.
     * Returns an array keyed by user_id with display-ready fields.
     */
    public function getUsersOverview(bool $extendOnly = false): array
    {
        global $TEXT, $TOOL_TXT;

        $collection = [];
        $users      = $this->getUserbaseArray($extendOnly);
        $toolUrl    = ADMIN_URL . '/admintools/tool.php?tool=' . ADMIN_TOOL_DIR;

        ob_start(); ?>
        <a class="iconlink" href="<?= $toolUrl ?>&amp;pos=detail&amp;id=%d&amp;action=delete">
            <img src="/delete_16.png" alt="<?= $TOOL_TXT['DELETE'] ?>">
        </a>
        <?php $linkDelete = ob_get_clean();

        ob_start(); ?>
        <a class="iconlink" href="<?= $toolUrl ?>&amp;pos=detail&amp;id=%d&amp;action=edit">
            <img src="/modify.png" alt="<?= $TEXT['MODIFY'] ?>"/>
        </a>
        <?php $linkEdit = ob_get_clean();

        $pryFunc = '';
        if (defined('UB_MOD_URL')) {
            ob_start(); ?>
            <a class="pry" title='<?= $TEXT['VIEW']; ?> User "%s"'
               href="<?= $toolUrl ?>&amp;pos=detail&amp;id=%d&amp;action=edit"
               rel="<?= UB_MOD_URL ?>/pry_profile.php?id=%d&action=edit">
                <i class="fa fa-1x fa-address-card"></i>
            </a>
            <?php $pryFunc = ob_get_clean();
        }

        foreach ($users as $row) {
            $id = $row['user_id'];
            $collection[$id] = [
                'active'           => $row['active'],
                'reg_method'       => $this->_getRegMethod($row['signup_confirmcode']),
                'language'         => $row['language'],
                'user_id'          => $row['user_id'],
                'username'         => remove_special_characters($row['display_name']) . ' <i>(' . $row['username'] . ')</i>',
                'usernameCsv'      => remove_special_characters($row['display_name']) . ' (' . $row['username'] . ')',
                'email'            => $row['email'],
                'groups'           => $row['user_groups'],
                'actions'          => sprintf($pryFunc, $row['display_name'], $id, $id) . ' ',
                'login_when'       => ($row['login_when'] != 0) ? $row['login_when'] + TIMEZONE : '',
                'profile_url'      => sprintf($toolUrl . '&amp;pos=detail&amp;id=%d&amp;action=edit', $id),
                'signup_timestamp' => ($row['signup_timestamp'] != 0) ? $row['signup_timestamp'] + TIMEZONE : '',
            ];
        }
        return $collection;
    }

    /**
     * Fetch all users and enrich each row with a group_id => group_name
     * map stored under the 'user_groups' key.
     */
    public function getUserbaseArray(bool $extendOnly = false): array
    {
        $users = $this->db->fetchAll("SELECT * FROM `{TP}users`");
        foreach ($users as &$row) {
            $groupsId = str_replace(' ', '', (string)($row['groups_id'] ?? ''));
            $groupIds = ($groupsId !== '' && str_contains($groupsId, ','))
                ? explode(',', $groupsId)
                : [$groupsId];

            $groupMap = [];
            foreach ($groupIds as $groupId) {
                if ($groupId !== '') {
                    $groupMap[$groupId] = $this->usergroupNameFromId((int)$groupId);
                }
            }
            $row['groups_id']   = $groupsId;
            $row['user_groups'] = $groupMap;
        }
        unset($row);
        return $users;
    }

    /**
     * Return a group name by ID, or an associative group_id => name map
     * for all groups when $groupId is 0.
     */
    public function usergroupNameFromId(int $groupId = 0): array|string|null
    {
        $rows   = $this->db->fetchAll('SELECT `group_id`, `name` FROM `{TP}groups`');
        $groups = array_column($rows, 'name', 'group_id');
        if ($groupId > 0) {
            return $groups[$groupId] ?? null;
        }
        return $groups;
    }

    /** Derive a human-readable registration method label from the confirmcode field. */
    private function _getRegMethod(string $confirmCode): string
    {
        if ($confirmCode === 'install-script') {
            return 'on System Setup';
        }
        if (str_contains($confirmCode, 'by')) {
            $adminId = (int) explode(': ', $confirmCode)[1];
            return ($adminId === 1) ? 'by SuperAdmin' : 'by Admin (uID: ' . $adminId . ')';
        }
        if (str_contains($confirmCode, 'signup')) {
            $groupId   = (int) explode(': ', $confirmCode)[1];
            $groupName = $this->db->fetchValue(
                'SELECT `name` FROM `{TP}groups` WHERE `group_id` = ?',
                [$groupId]
            );
            return 'on Signup (' . $groupName . ')';
        }
        return 'N/A';
    }
}

/**
 * Build the tab-navigation array for AdminTools and backend tool pages.
 *
 * @param array $tabs  Associative: pos_key => [LinkName, IconCode]
 * @return array       Ready-to-render tab descriptors.
 */
function renderToolTabs(array $tabs): array
{
    $pos    = $_GET['pos'] ?? key($tabs);
    $tabs   = array_reverse($tabs);
    $result = [];
    $i      = 0;
    foreach ($tabs as $key => $values) {
        $result[$i++] = [
            'pos'       => $key,
            'a_class'   => ($pos === $key) ? ' sel' : '',
            'li_class'  => ($pos === $key) ? ' class="actionSel"' : '',
            'link_name' => $values[0],
            'icon'      => $values[1],
        ];
    }
    return $result;
}
