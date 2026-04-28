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

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access');

class Admin extends Wb
{
    public string $section_name       = '';
    public string $section_permission = '';

    // ── Constructor ───────────────────────────────────────────────────────────

    /**
     * Bootstrap the admin context: authenticate, check permissions,
     * handle language redirect, and optionally output the page header.
     *
     * Pass an empty $section_name for a headless/anonymous instance
     * (replaces the legacy '##skip##' sentinel).
     *
     * @param string $section_name       Backend section identifier (e.g. 'Start', 'Pages', 'Addons')
     * @param string $section_permission Permission key to verify (default: 'start' = always allowed)
     * @param bool   $auto_header        Output the backend header automatically
     * @param bool   $auto_auth          Run authentication and permission checks
     * @param bool   $operateBuffer      Use output buffering (required for OutputFilters)
     */
    public function __construct(
        string $section_name       = '',
        string $section_permission = 'start',
        bool   $auto_header        = true,
        bool   $auto_auth          = true,
        bool   $operateBuffer      = true
    ) {
        parent::__construct(SecureForm::BACKEND);

        // Global $wb compatibility shim — legacy modules expect this
        global $wb;
        if (!is_object($wb)) $wb = $this;

        // Empty section_name = headless instantiation, skip all setup
        if ($section_name === '') return;

        $this->section_name       = $section_name;
        $this->section_permission = $section_permission;

        if ($auto_auth) {
            $this->authenticate($section_permission);
        }

        $this->redirectIfLanguageMismatch();

        if ($auto_header) {
            $this->print_header('', $operateBuffer);
        }
    }

    /**
     * Verify the user is logged in and has the required section permission.
     * Redirects to login or terminates with an error message on failure.
     *
     * @param string $section_permission  Permission key to check
     */
    private function authenticate(string $section_permission): void
    {
        global $MESSAGE;

        if (!$this->is_authenticated()) {
            header('Location: ' . ADMIN_URL . '/login/');
            exit;
        }

        if (!$this->get_permission($section_permission)) {
            // Typo in key preserved intentionally — matches the language file
            die($MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES']);
        }
    }

    /**
     * Redirect the browser if the user's preferred language differs from the
     * currently active language. Passes page_id and section_id as GET params
     * so the target page can restore context after the redirect.
     */
    private function redirectIfLanguageMismatch(): void
    {
        $user_language = $this->db->fetchValue(
            'SELECT `language` FROM `{TP}users` WHERE `user_id` = ?',
            [$this->get_user_id()]
        );

        if (!$user_language
            || LANGUAGE === $user_language
            || !file_exists(WB_PATH . '/languages/' . $user_language . '.php')
        ) return;

        $admin_folder = str_replace(WB_PATH, '', ADMIN_PATH);
        if (!str_contains($_SERVER['SCRIPT_NAME'], $admin_folder . '/')) return;

        $location = $_SERVER['SCRIPT_NAME'] . '?lang=' . $user_language
            . (isset($_GET['page_id'])    ? '&page_id='    . (int) $_GET['page_id']    : '')
            . (isset($_GET['section_id']) ? '&section_id=' . (int) $_GET['section_id'] : '');

        if (!empty($_SERVER['QUERY_STRING'])) {
            $location .= '&' . $_SERVER['QUERY_STRING'];
        }

        header('Location: ' . $location);
        exit;
    }

    /**
     * Check whether the current user has a given system, module, or template permission.
     *
     * For system permissions: returns true when the key IS in the list.
     * For module/template permissions: returns true when the key is NOT in the
     * deny-list (i.e. not explicitly blocked), following WBCE's inverted model.
     *
     * @param  string $name  Permission key (e.g. 'pages_add', 'my_module')
     * @param  string $type  'system' | 'module' | 'template'
     * @return bool
     */
    public function get_permission(string $name, string $type = 'system'): bool
    {
        if ($name === 'start') return true;

        $perms = match ($type) {
            'module'   => $this->get_session('MODULE_PERMISSIONS'),
            'template' => $this->get_session('TEMPLATE_PERMISSIONS'),
            default    => $this->get_session('SYSTEM_PERMISSIONS'),
        };

        $found = in_array($name, (array) $perms, true);

        return $type === 'system' ? $found : !$found;
    }

    /**
     * Check whether the current user can access a top-level backend navigation area.
     *
     * @param  string $link  Area key: 'pages', 'media', 'addons', 'settings', etc.
     * @return bool
     */
    public function get_link_permission(string $link): bool
    {
        $link = strtolower(str_replace('_blank', '', $link));

        if ($link === 'start') return true;

        return in_array($link, (array) $this->get_session('SYSTEM_PERMISSIONS'), true);
    }

    /**
     * Check if the authenticated user has admin or viewing permission for a page.
     *
     * Accepts either a page_id (int) or a page row array (already fetched).
     * Passing the array avoids a redundant DB query when the caller already has it.
     *
     * @param  int|array $page    Page ID or associative page row from {TP}pages
     * @param  string    $action  'admin' (default) or 'viewing'
     * @return bool
     */
    public function get_page_permission(int|array $page, string $action = 'admin'): bool
    {
        $action = ($action === 'viewing') ? 'viewing' : 'admin';

        if (is_array($page)) {
            $groups = $page[$action . '_groups'] ?? '0';
            $users  = $page[$action . '_users']  ?? '0';
        } else {
            $row = $this->db->fetchRow(
                "SELECT `{$action}_groups`, `{$action}_users`
                   FROM `{TP}pages`
                  WHERE `page_id` = ?",
                [(int) $page]
            );

            if (!$row) return false;

            $groups = $row[$action . '_groups'] ?? '0';
            $users  = $row[$action . '_users']  ?? '0';
        }

        return $this->ami_group_member($groups)
            || $this->is_group_match($this->get_user_id(), $users);
    }

    /**
     * Output the backend page header (navigation, assets, user info).
     *
     * Starts the output buffer when $operateBuffer is true so that
     * print_footer() can apply output filters to the complete page output.
     *
     * @param string $body_tags     Extra attributes for the <body> tag
     * @param bool   $operateBuffer Start output buffering
     */
    public function print_header(string $body_tags = '', bool $operateBuffer = true): void
    {
        if ($operateBuffer) ob_start();

        global $MENU, $MESSAGE, $TEXT;

        I::insertJsFile(
            WB_URL . '/include/SessionTimeout/SessionTimeout.js',
            'HEAD BTM+',
            'SessionTimeout'
        );

        $wbceTag = in_array(WBCE_TAG, ['', '-'])
            ? '-'
            : '<a href="https://github.com/WBCE/WBCE_CMS/releases/tag/' . WBCE_TAG
              . '" target="_blank">' . WBCE_TAG . '</a>';

        $header_template = new Template(dirname($this->correct_theme_source('header.htt')));
        $header_template->set_file('page', 'header.htt');
        $header_template->set_block('page', 'header_block', 'header');

        $header_template->set_var(array_merge($this->getSharedTemplateVars(), [
            'SECTION_NAME'              => $MENU[strtoupper($this->section_name)] ?? $this->section_name,
            'BODY_TAGS'                 => $body_tags,
            'CHARSET'                   => defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8',
            'LANGUAGE'                  => strtolower(LANGUAGE),
            'WEBSITE_TITLE'             => Settings::get('website_title'),
            'WBCE_TAG'                  => $wbceTag,
            'DISPLAY_NAME'              => $this->get_display_name(),
            'TEXT_ADMINISTRATION'       => $TEXT['ADMINISTRATION'],
            'LOGGED_IN_AS'              => $TEXT['LOGGED_IN'],
            'TITLE_BACK'                => $TEXT['BACK'],
            'TITLE_START'               => $MENU['START'],
            'TITLE_VIEW'                => $MENU['VIEW'],
            'TITLE_HELP'                => $MENU['HELP'],
            'TITLE_LOGOUT'              => $MENU['LOGOUT'],
            'URL_VIEW'                  => $this->getViewUrl(),
            'URL_HELP'                  => 'https://wbce.org/',
            'CURRENT_USER'              => $MESSAGE['START_CURRENT_USER'],
            'SERVER_ADDR'               => $this->get_group_id() == 1
                                             ? ($_SERVER['SERVER_ADDR'] ?? '127.0.0.1')
                                             : '',
            'MAINTAINANCEMODEINDICATOR' => $this->getMaintenanceModeIndicator(),
            'BACKEND_MODULE_CSS'        => $this->register_backend_modfiles('css'),
            'BACKEND_MODULE_JS'         => $this->register_backend_modfiles('js'),
//            'VERSION'                   => VERSION,    // Legacy — still expected by some themes?
//            'SP'                        => SP,         // Legacy
//            'REVISION'                  => REVISION,   // Legacy
        ]));

        $header_template->set_block('header_block', 'linkBlock', 'link');

        foreach ($this->buildMenu() as $item) {
            if ($item['required'] && !$this->get_link_permission($item['permission'])) continue;

            $header_template->set_var([
                'LINK'   => $item['link'],
                'TARGET' => '_self',
                'TITLE'  => $item['title'],
                'CLASS'  => strtolower($this->section_name) === $item['permission']
                              ? $item['permission'] . ' current'
                              : $item['permission'],
            ]);
            $header_template->parse('link', 'linkBlock', true);
        }

        $header_template->parse('header', 'header_block', false);
        $header_template->pparse('output', 'page');
    }

    /**
     * Output the backend page footer and flush the output buffer.
     *
     * When $operateBuffer is true, collects all buffered output (started in
     * print_header()), runs it through the OPF filter pipeline, then echoes it.
     *
     * @param bool $activateJsAdmin  Include the jsadmin backend helper if installed
     * @param bool $operateBuffer    Flush and filter the output buffer
     */
    public function print_footer(bool $activateJsAdmin = false, bool $operateBuffer = true): void
    {
        if ($activateJsAdmin) {
            $jsAdminFile = WB_PATH . '/modules/jsadmin/jsadmin_backend_include.php';
            if (file_exists($jsAdminFile)) {
                @include_once $jsAdminFile;
            }
        }

        $footer_template = new Template(dirname($this->correct_theme_source('footer.htt')));
        $footer_template->set_file('page', 'footer.htt');
        $footer_template->set_block('page', 'footer_block', 'header');
        $footer_template->set_var($this->getSharedTemplateVars());
        $footer_template->parse('header', 'footer_block', false);
        $footer_template->pparse('output', 'page');

        if (!$operateBuffer) return;

        $output = ob_get_clean();
        $output = $this->applyOutputFilters((string) $output);

        $this->sendDirectOutput();
        echo $output;
    }

    // ── Output filter pipeline ────────────────────────────────────────────────

    /**
     * Pass the buffered page output through all registered output filter pipelines.
     *
     * Two systems are supported for backward compatibility:
     *   1. OPF Dashboard (outputfilter_dashboard) — preferred
     *   2. Legacy output_filter module — suppressed by WB_SUPPRESS_OLD_OPF constant
     *
     * @param  string $output  Raw HTML output to filter
     * @return string          Filtered HTML output
     */
    private function applyOutputFilters(string $output): string
    {
        $opfFile = WB_PATH . '/modules/outputfilter_dashboard/functions.php';
        if (file_exists($opfFile)) {
            require_once $opfFile;
            if (function_exists('opf_controller')) {
                $output = opf_controller('backend', $output);
            }
        }

        if (!defined('WB_SUPPRESS_OLD_OPF') || !WB_SUPPRESS_OLD_OPF) {
            $legacyFile = WB_PATH . '/modules/output_filter/filter_routines.php';
            if (file_exists($legacyFile)) {
                include_once $legacyFile;
                if (function_exists('executeBackendOutputFilter')) {
                    $output = executeBackendOutputFilter($output);
                }
            }
        }

        return $output;
    }

    // ── Template helpers ──────────────────────────────────────────────────────

    /**
     * Build the variables shared between the header and footer templates.
     *
     * Includes URLs, version strings, and the session timeout value.
     * Centralised here so header and footer always receive the same values
     * without duplication.
     *
     * @return array<string, string>
     */
    private function getSharedTemplateVars(): array
    {
        $timeout = Settings::get('wb_session_timeout')
                ?? Settings::get('wb_secform_timeout')
                ?? 7200;

        return [
            'WB_URL'             => WB_URL,
            'ADMIN_URL'          => ADMIN_URL,
            'THEME_URL'          => THEME_URL,
            'PHP_VERSION'        => substr(phpversion(), 0, 6),
            'WBCE_VERSION'       => WBCE_VERSION,
            'WB_SESSION_TIMEOUT' => (string) $timeout,
        ];
    }

    /**
     * Build the top-level backend navigation menu entries.
     *
     * Each entry is derived from a key that doubles as the URL segment,
     * the $MENU array key (uppercased), and the permission identifier.
     * 'preferences' has no permission requirement and is always shown.
     *
     * @return array<int, array{link: string, title: string, permission: string, required: bool}>
     */
    private function buildMenu(): array
    {
        $items = [
            'pages', 'media', 'addons', 'preferences',
            'settings', 'admintools', 'access',
        ];

        $menu = [];
        foreach ($items as $key) {
            $menu[] = [
                'link'       => ADMIN_URL . '/' . $key . '/',
                'title'      => Lang::get('MENU', strtoupper($key)) ?? $key,
                'permission' => $key,
                'required'   => $key !== 'preferences',
            ];
        }

        return $menu;
    }

    /**
     * Return the HTML snippet for the maintenance mode wrench indicator,
     * or an empty string when maintenance mode is off.
     *
     * @return string
     */
    private function getMaintenanceModeIndicator(): string
    {
        return Settings::get('wb_maintainance_mode')
            ? ' <span class="fa fa-wrench wbcemm"></span> '
            : '';
    }

    /**
     * Resolve the frontend URL to link to from the backend "View" button.
     *
     * When a page_id GET parameter is present, resolves to that page's URL.
     * Falls back to the site root when no page_id is given or the lookup fails.
     *
     * @return string  Absolute frontend URL
     */
    private function getViewUrl(): string
    {
        if (!isset($_GET['page_id'])) return WB_URL;

        $link = $this->db->fetchValue(
            'SELECT `link` FROM `{TP}pages` WHERE `page_id` = ?',
            [(int) $_GET['page_id']]
        );

        return $link
            ? WB_URL . PAGES_DIRECTORY . $link . PAGE_EXTENSION
            : WB_URL;
    }

    /**
     * Generate the placeholder comment for backend module CSS or JS files.
     *
     * The placeholder is replaced by the actual file tags during output filter
     * processing (I::class or similar mechanism).
     *
     * @param  string $sModfileType  'css' | 'js' | 'jquery' | 'js_vars'
     * @return string                HTML placeholder comment + inline tags
     */
    public function register_backend_modfiles(string $sModfileType = 'css'): string
    {
        return '<!--(PH) ' . strtoupper($sModfileType) . ' HEAD MODFILES -->' . PHP_EOL
             . $this->registerModfiles($sModfileType, 'backend');
    }

    /**
     * Fetch basic user details from the database.
     *
     * Returns a safe default array when the user_id is not found,
     * so callers can always access the keys without null-checks.
     *
     * @param  int   $user_id
     * @return array{username: string, display_name: string, email: string}
     */
    public function get_user_details(int $user_id): array
    {
        $row = $this->db->fetchRow(
            'SELECT `username`, `display_name`, `email` FROM `{TP}users` WHERE `user_id` = ?',
            [$user_id]
        );

        return $row ?? [
            'username'     => 'unknown',
            'display_name' => 'Unknown',
            'email'        => '',
        ];
    }

    /**
     * Fetch a section row from {TP}sections, terminating with an error page if not found.
     *
     * Note: print_header() must have been called before this method, as it may
     * invoke print_error() which outputs into the already-open page shell.
     *
     * @param  int    $section_id
     * @param  string $backLink   URL for the "back" button in the error page
     * @return array              Associative section row
     */
    public function get_section_details(int $section_id, string $backLink = 'index.php'): array
    {
        global $TEXT;

        $row = $this->db->fetchRow(
            'SELECT * FROM `{TP}sections` WHERE `section_id` = ?',
            [$section_id]
        );

        if ($this->db->hasError()) {
            $this->print_error($this->db->getError(), $backLink, true);
        }

        if ($row === null) {
            $this->print_error($TEXT['SECTION'] . ' ' . $TEXT['NOT_FOUND'], $backLink, true);
        }

        return $row;
    }

    /**
     * Fetch a page row from {TP}pages, terminating with an error page if not found.
     *
     * Note: print_header() must have been called before this method, as it may
     * invoke print_error() which outputs into the already-open page shell.
     *
     * @param  int    $page_id
     * @param  string $backLink  URL for the "back" button in the error page
     * @return array             Associative page row
     */
    public function get_page_details(int $page_id, string $backLink = 'index.php'): array
    {
        global $TEXT;

        $row = $this->db->fetchRow(
            'SELECT * FROM `{TP}pages` WHERE `page_id` = ?',
            [$page_id]
        );

        if ($this->db->hasError()) {
            $this->print_error($this->db->getError(), $backLink, true);
        }

        if ($row === null) {
            $this->print_error($TEXT['PAGE'] . ' ' . $TEXT['NOT_FOUND'], $backLink, true);
        }

        return $row;
    }
}