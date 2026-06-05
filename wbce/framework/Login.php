<?php
/**
 * WBCE CMS — Login handler
 *
 * Authenticates users, manages login attempts with rate limiting,
 * and renders the login form via Twig.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

defined('WB_PATH') or die('No direct access allowed');

// Captcha class and call_captcha() are registered via
// modules/captcha_control/initialize.php which runs before Login is instantiated.

class Login extends Admin
{
    // =====================================================================
    //  Properties
    // =====================================================================
    private Alerts $alerts;
    private string $username   = '';
    private string $password   = '';
    private int    $user_id    = 0;

    // Config — set from constructor array
    private string $login_url             = '';
    private string $default_url           = '';
    private string $warning_url           = '';
    private string $forgotten_details_app = '';
    private string $redirect_url          = '';
    private string $username_fieldname    = 'username';
    private string $password_fieldname    = 'password';
    private int    $max_attempts          = 6;
    private int    $captcha_threshold     = 2;
    private int    $timeframe             = 600;
    private int    $login_delay           = 60;
    private int    $max_username_len      = 30;
    private int    $max_password_len      = 30;
    private bool   $frontend              = false;

    // Runtime
    private string $url        = '';
    private bool   $nocaptcha  = false;


    // =====================================================================
    //  Constructor
    // =====================================================================

    public function __construct(array $config)
    {
        parent::__construct();

        $this->alerts = new Alerts(useSession: false);

        // Map config array to typed properties
        $this->login_url             = $config['LOGIN_URL']                  ?? '';
        $this->default_url           = $config['DEFAULT_URL']                ?? ADMIN_URL . '/start/index.php';
        $this->warning_url           = $config['WARNING_URL']                ?? '';
        $this->forgotten_details_app = $config['FORGOTTEN_DETAILS_APP']      ?? '';
        $this->redirect_url          = $config['REDIRECT_URL']               ?? '';
        $this->username_fieldname    = $config['USERNAME_FIELDNAME']         ?? 'username';
        $this->password_fieldname    = $config['PASSWORD_FIELDNAME']         ?? 'password';
        $this->max_attempts          = (int) ($config['MAX_ATTEMPTS']        ?? 6);
        $this->captcha_threshold     = (int) ($config['CAPTCHA_THRESHOLD']   ?? 3);
        $this->timeframe             = (int) ($config['TIMEFRAME']           ?? 600);
        $this->login_delay           = (int) ($config['LOGIN_DELAY']         ?? 60);
        $this->max_username_len      = (int) ($config['MAX_USERNAME_LEN']    ?? 30);
        $this->max_password_len      = (int) ($config['MAX_PASSWORD_LEN']    ?? 30);
        $this->frontend              = (bool) ($config['FRONTEND']           ?? false);

        // Determine target URL
        $this->url = $this->get_post('url') ?: '';
        if ($this->redirect_url !== '') {
            $this->url = $this->redirect_url;
        }
        if ($this->url === '') {
            $this->url = $this->default_url;
        }

        // Already authenticated? Redirect immediately.
        if ($this->is_authenticated()) {
            header('Location: ' . $this->url);
            exit;
        }

        // Determine captcha requirement
        $this->nocaptcha = $this->shouldSkipCaptcha();

        // Read POST credentials
        $this->readCredentials();

        // Process login attempt
        $this->processLogin();
    }


    // =====================================================================
    //  Login flow
    // =====================================================================

    private function processLogin(): void
    {
        // Check captcha first (if required and form was submitted)
        $captchaResult = $this->checkCaptcha();

        if ($captchaResult === 'missing') {
            $this->alerts->error('MESSAGE:GENERIC_SECURITY_ACCESS');
            $this->display_login();
            return;
        }

        if ($captchaResult === 'wrong') {
            $this->alerts->error('MESSAGE:MOD_FORM_INCORRECT_CAPTCHA');
            $this->display_login();
            return;
        }

        // No credentials submitted — show blank form
        if ($this->username === '' && $this->password === '') {
            $this->alerts->info('MESSAGE:LOGIN_BOTH_BLANK');
            $this->display_login();
            return;
        }

        if ($this->username === '') {
            $this->alerts->error('MESSAGE:LOGIN_USERNAME_BLANK');
            $this->increase_attempts();
            return;
        }

        if ($this->password === '') {
            $this->alerts->error(L_('MESSAGE:LOGIN_PASSWORD_BLANK'));
            $this->increase_attempts();
            return;
        }

        // Block check BEFORE authentication (GHSA-x789-63j7-2qhw)
        if ($this->isBlocked()) {
            $this->warn();
            return;
        }

        // Authenticate
        if ($this->authenticate()) {
            header('Location: ' . $this->url);
            exit;
        }

        $this->alerts->error('MESSAGE:LOGIN_AUTHENTICATION_FAILED');
        $this->increase_attempts();
    }

    /**
     * Read username and password from POST.
     */
    private function readCredentials(): void
    {
        // The field names may be customized (anti-bot measure)
        $userField = $this->get_post('username_fieldname') ?: $this->username_fieldname;
        $passField = $this->get_post('password_fieldname') ?: $this->password_fieldname;

        if (filter_input(INPUT_POST, $userField)) {
            $this->username = htmlspecialchars(
                strtolower($this->get_post($userField) ?? ''),
                ENT_QUOTES
            );
            $this->password = $this->get_post($passField) ?? '';
        }
    }


    // =====================================================================
    //  Captcha
    // =====================================================================

    /**
     * Determine if captcha should be skipped.
     */
    private function shouldSkipCaptcha(): bool
    {
        if (defined('NO_SESSION_COOKIE') && NO_SESSION_COOKIE) {
            return true;
        }

        if (defined('NO_LOGIN_CAPTCHA') && NO_LOGIN_CAPTCHA) {
            return true;
        }
        // Show captcha from attempt (captcha_threshold + 1) onward for this IP.
        // Uses the same {TP}blocking table as isBlocked() — no extra query overhead.
        $clientIp = md5($this->getClientIp());
        $attempts  = (int) $this->db->fetchValue(
            "SELECT `attempts` FROM `{TP}blocking` WHERE `source_ip` = ? LIMIT 1",
            [$clientIp]
        );
        return ($attempts < $this->captcha_threshold); // captcha appears from attempt (threshold + 1) onward
    }

   /**
     * Check captcha if required.
     *
     * @return string|null  null = ok / not required
     *                      'missing' = form submitted but captcha field absent
     *                      'wrong'   = captcha verification failed
     */
    private function checkCaptcha(): ?string
    {
        if ($this->nocaptcha) {
            return null;
        }
 
        $formSubmitted = isset($_POST[$this->username_fieldname])
                      || isset($_POST[$this->password_fieldname]);
 
        if (!$formSubmitted) {
            return null;
        }
 
        // $_POST['captcha'] is the sync token written by the ALTCHA widget's
        // statechange listener. If it's absent the widget hasn't verified yet.
        if (!isset($_POST['captcha']) || $_POST['captcha'] === '') {
            return 'missing';
        }
 
        // Captcha::verify() handles everything:
        //   - ALTCHA SHA-256 PoW + HMAC check (via $_POST['altcha_login'])
        //   - Sync token comparison (legacy path)
        //   - Honeypot / ASP check (when ENABLED_ASP is true)
        // The sec_id 'login' must match call_captcha('all', '', 'login') below.
        if (!Captcha::verify($_POST['captcha'], 'login')) {
            return 'wrong';
        }
 
        return null;
    }

    /**
     * Render captcha HTML if needed.
     */
    private function getCaptchaHtml(): string
    {
        if ($this->nocaptcha) {
            return '';
        }
 
        ob_start();
        call_captcha('all', '', 'login');
        return ob_get_clean();
    }


    // =====================================================================
    //  Authentication
    // =====================================================================

    /**
     * Authenticate user against database.
     * On success: populates $_SESSION with user data.
     *
     * @return bool  true on successful authentication
     */
    public function authenticate(): bool
    {
        // Reject usernames with special characters
        if (preg_match('/[\;\=\&\|\<\> ]/', $this->username)) {
            return false;
        }

        // Query user
        if (defined('ALLOW_EMAIL_LOGIN') && ALLOW_EMAIL_LOGIN === true) {
            $sql = "SELECT * FROM `{TP}users`
                    WHERE (`username` = ? OR `email` = ?) AND `active` = 1";
            $result = $this->db->query($sql, [$this->username, $this->username]);
        } else {
            $sql = "SELECT * FROM `{TP}users` WHERE `username` = ? AND `active` = 1";
            $result = $this->db->query($sql, [$this->username]);
        }

        $user = $result->fetchRow(MYSQLI_ASSOC);

        if ($result->numRows() !== 1) {
            return false;
        }

        // Verify password
        if (!$this->doCheckPassword($user['user_id'], $this->password)) {
            return false;
        }

        // --- Authentication successful — populate session ---
        $this->user_id = (int) $user['user_id'];
        $_SESSION['USER_ID']      = $this->user_id;
        $_SESSION['GROUP_ID']     = $user['group_id'];
        $_SESSION['GROUPS_ID']    = $user['groups_id'];
        $_SESSION['USERNAME']     = $user['username'];
        $_SESSION['DISPLAY_NAME'] = $user['display_name'];
        $_SESSION['EMAIL']        = $user['email'];
        $_SESSION['HOME_FOLDER']  = $user['home_folder'];

        // Language
        if (!empty($user['language'])) {
            $_SESSION['LANGUAGE'] = $user['language'];
        }

        // Timezone
        if (!empty($user['timezone'])) {
            $_SESSION['TIMEZONE'] = $user['timezone'];
        } else {
            $_SESSION['USE_DEFAULT_TIMEZONE'] = true;
        }

        // Date/time formats
        $_SESSION['DATE_FORMAT'] = $user['date_format'] ?: null;
        $_SESSION['TIME_FORMAT'] = $user['time_format'] ?: null;
        if (empty($user['date_format'])) $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
        if (empty($user['time_format'])) $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;

        // Group permissions
        $this->loadGroupPermissions();

        // Update last login
        $this->db->upsertRow('{TP}users', 'user_id', [
            'user_id'    => $this->user_id,
            'login_when' => time(),
            'login_ip'   => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        return true;
    }

    /**
     * Load system, module, and template permissions from all user groups.
     */
    private function loadGroupPermissions(): void
    {
        $_SESSION['SYSTEM_PERMISSIONS']   = [];
        $_SESSION['MODULE_PERMISSIONS']   = [];
        $_SESSION['TEMPLATE_PERMISSIONS'] = [];
        $_SESSION['GROUP_NAME']           = [];
        $_SESSION['DEFAULT_MODULE']       = '';

        $firstGroup = true;

        foreach (explode(',', $this->get_session('GROUPS_ID')) as $groupId) {
            $group = $this->db->fetchRow(
                "SELECT * FROM `{TP}groups` WHERE `group_id` = ?",
                [(int) $groupId]
            );

            if (!$group) continue;

            $_SESSION['GROUP_NAME'][$groupId] = $group['name'];

            // Default page module — taken from the primary group only
            if ($firstGroup) {
                $_SESSION['DEFAULT_MODULE'] = $group['default_module'] ?? '';
            }

            // System permissions — union of all groups
            if (!empty($group['system_permissions'])) {
                $_SESSION['SYSTEM_PERMISSIONS'] = array_merge(
                    $_SESSION['SYSTEM_PERMISSIONS'],
                    explode(',', $group['system_permissions'])
                );
            }

            // Module permissions — intersection (most restrictive)
            if (!empty($group['module_permissions'])) {
                $modulePerms = explode(',', $group['module_permissions']);
                $_SESSION['MODULE_PERMISSIONS'] = $firstGroup
                    ? $modulePerms
                    : array_intersect($_SESSION['MODULE_PERMISSIONS'], $modulePerms);
            }

            // Template permissions — intersection
            if (!empty($group['template_permissions'])) {
                $templatePerms = explode(',', $group['template_permissions']);
                $_SESSION['TEMPLATE_PERMISSIONS'] = $firstGroup
                    ? $templatePerms
                    : array_intersect($_SESSION['TEMPLATE_PERMISSIONS'], $templatePerms);
            }

            $firstGroup = false;
        }
    }


    // =====================================================================
    //  Brute-force protection
    // =====================================================================

    /**
     * Check if the current IP is blocked due to too many failed attempts.
     * Called before authenticate() to prevent login even with valid credentials
     * while a lockout is active (GHSA-x789-63j7-2qhw).
     */
    private function isBlocked(): bool
    {
        $clientIp = md5($this->getClientIp());
        $attempts = (int) $this->db->fetchValue(
            "SELECT `attempts` FROM `{TP}blocking` WHERE `source_ip` = ? LIMIT 1",
            [$clientIp]
        );

        return $attempts > $this->max_attempts;
    }

    /**
     * Record a login attempt and display the form or warn.
     */
    public function increase_attempts(int $increment = 1): void
    {
        $clientIp  = md5($this->getClientIp());
        $now       = time();
        $attempts  = 0;
        $timestamp = $now;

        $row = $this->db->fetchRow(
            "SELECT `attempts`, `timestamp` FROM `{TP}blocking` WHERE `source_ip` = ? LIMIT 1",
            [$clientIp]
        );

        if ($row) {
            $attempts  = (int) $row['attempts'] + $increment;
            $timestamp = (int) $row['timestamp'];
        } else {
            $attempts = $increment;
            $this->db->insertRow('{TP}blocking', [
                'attempts'  => $attempts,
                'timestamp' => $now,
                'source_ip' => $clientIp,
            ]);
        }

        $interval = $now - $timestamp;

        // Reset if too long ago
        if ($interval > $this->timeframe + 2 * pow(2, ($attempts - $this->max_attempts)) * $this->login_delay) {
            $attempts = $increment;
        }

        // Update DB
        $this->db->query(
            "UPDATE `{TP}blocking` SET `attempts` = ?, `timestamp` = ? WHERE `source_ip` = ?",
            [$attempts, $now, $clientIp]
        );

        // Reduce if long interval but above threshold
        if ($interval > $this->timeframe + pow(2, ($attempts - $this->max_attempts)) * $this->login_delay
            && $attempts > $this->max_attempts
        ) {
            $attempts = $this->max_attempts;
        }

        // Cleanup old entries (> 1 week)
        $this->db->query(
            "DELETE FROM `{TP}blocking` WHERE `timestamp` < ?",
            [$now - 7 * 86400]
        );

        $_SESSION['ATTEMPTS'] = $attempts;

        if ($attempts > $this->max_attempts) {
            $this->warn();
        } else {
            $this->display_login();
        }
    }

    /**
     * Get the client IP address.
     */
    private function getClientIp(): string
    {
        $headers = [
            'REMOTE_ADDR',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
        ];

        foreach ($headers as $key) {
            $val = $_SERVER[$key] ?? getenv($key) ?: null;
            if ($val && $val !== ($_SERVER['SERVER_ADDR'] ?? '')) {
                return $val;
            }
        }

        return 'UNKNOWN';
    }


    // =====================================================================
    //  Display
    // =====================================================================

    /**
     * Redirect to warning page (too many attempts).
     */
    public function warn(): void
    {
        header('Location: ' . $this->warning_url);
        exit;
    }

    /**
     * Return the resolved redirect URL.
     */
    public function getRedirectUrl(): string
    {
        return $this->url;
    }

    /**
     * Render the login form.
     */
    public function display_login(): void
    {
        if (!isset($_SESSION['ATTEMPTS']) || $_SESSION['ATTEMPTS'] > $this->max_attempts) {
            $this->increase_attempts(0);
            return;
        }

        if ($this->frontend) {
            return;
        }

        $toTwig = [
            'WB_URL'                => WB_URL,
            'THEME_URL'             => THEME_URL,
            'ACTION_URL'            => $this->login_url,
            'USERNAME'              => $this->username,
            'USERNAME_FIELDNAME'    => $this->username_fieldname,
            'PASSWORD_FIELDNAME'    => $this->password_fieldname,
            'MAX_USERNAME_LEN'      => $this->max_username_len,
            'MAX_PASSWORD_LEN'      => $this->max_password_len,
            'FORGOTTEN_DETAILS_APP' => $this->forgotten_details_app,
            'CAPTCHA'               => $this->getCaptchaHtml(),
            'MESSAGE'               => $this->alerts->render(),
            'CHARSET'               => defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8',
            'LANGUAGE'              => strtolower(LANGUAGE),
        ];

        $this->getThemeFile('login.twig', $toTwig);
    }
}
