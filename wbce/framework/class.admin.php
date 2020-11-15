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

//no direct file access
if (count(get_included_files()) == 1) {
    die(header("Location: ../index.php", true, 301));
}


// Get WB version
require_once ADMIN_PATH . '/interface/version.php';

class Admin extends Wb
{
    /**
     * @brief  Authenticate user then auto print the header
     */
    public function __construct($section_name = '##skip##', $section_permission = 'start', $auto_header = true, $auto_auth = true, $operateBuffer = true)
    {
        parent::__construct(SecureForm::BACKEND);
        if ($section_name != '##skip##') {
            global $MESSAGE;
            // Specify the current applications name
            $this->section_name = $section_name;
            $this->section_permission = $section_permission;
            // Authenticate the user for this application
            if ($auto_auth == true) {
                // First check if the user is logged-in
                if ($this->is_authenticated() == false) {
                    header('Location: ' . ADMIN_URL . '/login/');
                    exit(0);
                }

                // Now check if they are allowed in this section
                if ($this->get_permission($section_permission) == false) {
                    die($MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES']);
                }
            }

            // Check if the backend language is also the selected language. If not, send headers again.
            $sql = 'SELECT `language` FROM `{TP}users` WHERE `user_id`=' . (int)$this->get_user_id();
            $user_language = $this->_oDb->get_one($sql);
            $admin_folder = str_replace(WB_PATH, '', ADMIN_PATH);
            if ((LANGUAGE != $user_language) && file_exists(WB_PATH . '/languages/' . $user_language . '.php')
                && strpos($_SERVER['SCRIPT_NAME'], $admin_folder . '/') !== false) {
                // check if page_id is set
                $page_id_url = (isset($_GET['page_id'])) ? '&page_id=' . (int)$_GET['page_id'] : '';
                $section_id_url = (isset($_GET['section_id'])) ? '&section_id=' . (int)$_GET['section_id'] : '';
                $sHeaderLocation = $_SERVER['SCRIPT_NAME'] . '?lang=' . $user_language . $page_id_url . $section_id_url;
                if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') {
                    // check if there is an query-string
                    header('Location: ' . $sHeaderLocation . '&' . $_SERVER['QUERY_STRING']);
                } else {
                    header('Location: ' . $sHeaderLocation);
                }
                exit();
            }

            // Auto header code
            if ($auto_header == true) {
                $this->print_header($body_tags = '', $operateBuffer);
            }
        }

        // I know it's not the prettiest solution, but some old stuff really needs this for compatibility
        global $wb;
        if (!is_object($wb)) {
            $wb = $this;
        }
    }

    /**
     * @brief   Return a system permission
     *
     * @param string $name Name of the area in the backend
     * @param string $type tpe of permission asked for (system|module|template)
     * @return  bool    true or false depending on whether or not user has permission in the area
     */
    public function get_permission($name, $type = 'system')
    {
        // Append to permission type
        $type .= '_permissions';
        // Check if we have a section to check for
        if ($name == 'start') {
            return true;
        } else {
            // Set system permissions var
            $system_permissions = $this->get_session('SYSTEM_PERMISSIONS');

            // Set module permissions var
            $module_permissions = $this->get_session('MODULE_PERMISSIONS');

            // Set template permissions var
            $template_permissions = $this->get_session('TEMPLATE_PERMISSIONS');

            // Return true if system perm = 1
            if (isset($$type) && is_array($$type) && is_numeric(array_search($name, $$type))) {
                return ($type == 'system_permissions') ? true : false;
            } else {
                return ($type == 'system_permissions') ? false : true;
            }
        }
    }

    /**
     * @brief   Print the admin header
     *
     * @param string $body_tags
     * @param bool $operateBuffer
     * @return  string  generate admin header based on the header template
     */
    public function print_header($body_tags = '', $operateBuffer = true)
    {
        // this buffer is needed so we can later apply output filters to BE Output
        if ($operateBuffer) {
            ob_start();
        }

        // Get vars from the language file
        global $MENU, $MESSAGE, $TEXT;
        // Connect to database and get website title
        // $GLOBALS['FTAN'] = $this->getFTAN();
        $this->createFTAN();
        $sql = 'SELECT `value` FROM `{TP}settings` WHERE `name`=\'website_title\'';
        $get_title = $this->_oDb->query($sql);
        $title = $get_title->fetchRow();

        // Setup template object, parse vars to it, then parse it
        $header_template = new Template(dirname($this->correct_theme_source('header.htt')));
        $header_template->set_file('page', 'header.htt');
        $header_template->set_block('page', 'header_block', 'header');
        if (defined('DEFAULT_CHARSET')) {
            $charset = DEFAULT_CHARSET;
        } else {
            $charset = 'utf-8';
        }

        // work out the URL for the 'View menu' link in the WB backend
        // if the page_id is set, show this page otherwise show the root directory of WB
        $view_url = WB_URL;
        if (isset($_GET['page_id'])) {
            // extract page link from the database
            $sql = 'SELECT `link` FROM `{TP}pages` WHERE `page_id`=' . intval($_GET['page_id']);
            $result = @$this->_oDb->query($sql);
            $row = @$result->fetchRow();
            if ($row) {
                $view_url .= PAGES_DIRECTORY . $row['link'] . PAGE_EXTENSION;
            }
        }
        I::insertJsFile(WB_URL . '/include/SessionTimeout/SessionTimeout.js', "HEAD BTM+", 'SessionTimeout');

        $sTempMMI = (string)Settings::Get("wb_maintainance_mode");
        $sMaintModeIndict = ($sTempMMI) ? ' <span class="fa fa-wrench wbcemm"></span> ' : '';

        $maintModeIndicator = (string)Settings::Get("wb_maintainance_mode");
        if ($maintModeIndicator) {
            $maintModeIndicator = ' <span class="fa fa-wrench wbcemm"></span> ';
        } else {
            $maintModeIndicator = '';
        }

        $header_template->set_var(
            array(
                'WB_SESSION_TIMEOUT' => $this->get_session_timeout(),
                'SECTION_NAME' => $MENU[strtoupper($this->section_name)],
                'BODY_TAGS' => $body_tags,
                'WEBSITE_TITLE' => ($title['value']),
                'TEXT_ADMINISTRATION' => $TEXT['ADMINISTRATION'],
                'CURRENT_USER' => $MESSAGE['START_CURRENT_USER'],
                'DISPLAY_NAME' => $this->get_display_name(),
                'CHARSET' => $charset,
                'LANGUAGE' => strtolower(LANGUAGE),
                'WBCE_VERSION' => WBCE_VERSION,
                'WBCE_TAG' => (
                in_array(WBCE_TAG, array('', '-'))
                    ? '-'
                    : '<a href="https://github.com/WBCE/WBCE_CMS/releases/tag/' . WBCE_TAG . '" target="_blank">' . WBCE_TAG . '</a>'
                ),
                'VERSION' => VERSION,   // Legacy: WB-classic
                'SP' => SP,             // Legacy: WB-classic
                'REVISION' => REVISION, // Legacy: WB-classic
                'SERVER_ADDR' => ($this->get_group_id() == 1
                    ? (!isset($_SERVER['SERVER_ADDR'])
                        ? '127.0.0.1'
                        : $_SERVER['SERVER_ADDR'])
                    : ''),
                'WB_URL' => WB_URL,
                'ADMIN_URL' => ADMIN_URL,
                'THEME_URL' => THEME_URL,
                'PHP_VERSION' => substr(phpversion(), 0, 6),
                'TITLE_START' => $MENU['START'],
                'TITLE_BACK' => $TEXT['BACK'],
                'TITLE_VIEW' => $MENU['VIEW'],
                'TITLE_HELP' => $MENU['HELP'],
                'TITLE_LOGOUT' => $MENU['LOGOUT'],
                'LOGGED_IN_AS' => $TEXT['LOGGED_IN'],
                'URL_VIEW' => $view_url,
                'URL_HELP' => 'https://wbce.org/',
                'BACKEND_MODULE_CSS' => $this->register_backend_modfiles('css'), // adds backend.css
                'BACKEND_MODULE_JS' => $this->register_backend_modfiles('js'),  // adds backend.js
                'MAINTAINANCEMODEINDICATOR' => $maintModeIndicator,
            )
        );

        // Create the menu
        $menu = array(
            array(ADMIN_URL . '/pages/index.php', '', $MENU['PAGES'], 'pages', 1),
            array(ADMIN_URL . '/media/', '', $MENU['MEDIA'], 'media', 1),
            array(ADMIN_URL . '/addons/', '', $MENU['ADDONS'], 'addons', 1),
            array(ADMIN_URL . '/preferences/', '', $MENU['PREFERENCES'], 'preferences', 0),
            array(ADMIN_URL . '/settings/', '', $MENU['SETTINGS'], 'settings', 1),
            array(ADMIN_URL . '/admintools/', '', $MENU['ADMINTOOLS'], 'admintools', 1),
            array(ADMIN_URL . '/access/', '', $MENU['ACCESS'], 'access', 1),
        );

        $header_template->set_block('header_block', 'linkBlock', 'link');
        foreach ($menu as $menu_item) {
            $link = $menu_item[0];
            $target = ($menu_item[1] == '') ? '_self' : $menu_item[1];
            $title = $menu_item[2];
            $permission_title = $menu_item[3];
            $required = $menu_item[4];
            $replace_old = array(ADMIN_URL, WB_URL, '/', 'index.php');
            if ($required == false or $this->get_link_permission($permission_title)) {
                $header_template->set_var('LINK', $link);
                $header_template->set_var('TARGET', $target);
                // If link is the current section apply a class name
                if ($permission_title == strtolower($this->section_name)) {
                    $header_template->set_var('CLASS', $menu_item[3] . ' current');
                } else {
                    $header_template->set_var('CLASS', $menu_item[3]);
                }
                $header_template->set_var('TITLE', $title);
                // Print link
                $header_template->parse('link', 'linkBlock', true);
            }
        }
        $header_template->parse('header', 'header_block', false);
        $header_template->pparse('output', 'page');
    }

    /**
     * @brief   Function to add optional module JavaScript or CSS stylesheets
     *          into the <head> section of the backend
     *
     * @param string $sModfileType css|js|jquery|js_vars
     * @return  void
     */
    public function register_backend_modfiles($sModfileType = "css")
    {
        return '<!--(PH) ' . strtoupper($sModfileType) . ' HEAD MODFILES -->' . PHP_EOL .
            $this->register_modfiles($sModfileType, "backend");
    }

    /**
     * @brief   Returns a system permission for a specified backend area
     *
     * @param string $sArea This may be a backend area like start|addons|settings etc.
     *                          or a specific module
     * @return  bool    true if user has given permissions
     */
    public function get_link_permission($sArea)
    {
        $sArea = str_replace('_blank', '', $sArea);
        $sArea = strtolower($sArea);
        // Set system permissions var
        $aSystemPerms = $this->get_session('SYSTEM_PERMISSIONS');
        // Set module permissions var
        $aModulePerms = $this->get_session('MODULE_PERMISSIONS');
        if ($sArea == 'start') {
            return true;
        } else {
            // Return true if system perm = 1
            if (is_numeric(array_search($sArea, $aSystemPerms))) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @brief   Print the admin footer
     *
     * @param bool $activateJsAdmin
     * @param bool $operateBuffer
     * @return  string  generate admin footer based on the header template
     */
    public function print_footer($activateJsAdmin = false, $operateBuffer = true)
    {
        // include the required file for Javascript admin
        if ($activateJsAdmin != false) {
            $sJsAdminFile = WB_PATH . '/modules/jsadmin/jsadmin_backend_include.php';
            if (file_exists($sJsAdminFile)) {
                @include_once $sJsAdminFile;
            }
        }

        // Setup template object, parse vars to it, then parse it
        $footer_template = new Template(dirname($this->correct_theme_source('footer.htt')));
        $footer_template->set_file('page', 'footer.htt');
        $footer_template->set_block('page', 'footer_block', 'header');

        // Session Timeout possibly not Defined on Upgrade
        if ($sSessionTimeout = Settings::get("wb_session_timeout")) {
        } elseif ($sSessionTimeout = Settings::get("wb_secform_timeout")) {
        } else {
            $sSessionTimeout = "7200";
        }

        $footer_template->set_var(array(
            'WB_SESSION_TIMEOUT' => $sSessionTimeout,
            'WB_URL' => WB_URL,
            'ADMIN_URL' => ADMIN_URL,
            'THEME_URL' => THEME_URL,
            'PHP_VERSION' => substr(phpversion(), 0, 6),
            'WBCE_VERSION' => WBCE_VERSION
        ));
        $footer_template->parse('header', 'footer_block', false);
        $footer_template->pparse('output', 'page');
        // If we operate on buffer in BE load all necessary filter
        if ($operateBuffer) {
            // fetch all output
            $allOutput = ob_get_clean();

            // OPF dashboard
            // is it installed ?
            if (file_exists(WB_PATH . '/modules/outputfilter_dashboard/functions.php')) {
                require_once(WB_PATH . '/modules/outputfilter_dashboard/functions.php');
            }
            if (function_exists('opf_controller')) {
                // then apply backend filter
                $allOutput = opf_controller('backend', $allOutput);
            }

            // Conventional output filter
            // if not deactivated
            if (!defined("WB_SUPPRESS_OLD_OPF") or !WB_SUPPRESS_OLD_OPF) {
                // Module is installed, filter file in place?
                $file = WB_PATH . '/modules/output_filter/filter_routines.php';
                if (file_exists($file)) {
                    include_once($file);
                    // Correct module ? Check it .
                    if (function_exists('executeBackendOutputFilter')) {
                        // call the backend filter
                        $allOutput = executeBackendOutputFilter($allOutput);
                    }
                }
            }

            // Process direct Output if set. This ends the script here and regular output is not put out.
            $this->DirectOutput();

            // finally output everything as if nothing happened
            echo $allOutput;
        }
    }

    /**
     * @brief   Get basic user details (`username`,`display_name`,`email`) as array
     *
     * @param int $user_id
     * @return  array
     */
    public function get_user_details($user_id)
    {
        $aRetVal = array(
            'username' => 'unknown',
            'display_name' => 'Unknown',
            'email' => ''
        );
        $sSql = 'SELECT `username`,`display_name`,`email` FROM `{TP}users` WHERE `user_id`= %d';
        if (($resUsers = $this->_oDb->query(sprintf($sSql, $user_id)))) {
            if (($recUser = $resUsers->fetchRow())) {
                $aRetVal = $recUser;
            }
        }
        return $aRetVal;
    }

    /**
     * @brief   get section details from `{TP}sections` database table
     *
     * @param int $section_id
     * @param string $backLink
     * @return  array   assoc array with section details
     * @global  array $TEXT
     */
    public function get_section_details($section_id, $backLink = 'index.php')
    {
        global $TEXT;
        $sSql = 'SELECT * FROM `{TP}sections` WHERE `section_id`= %d';
        if (($resSection = $this->_oDb->query(sprintf($sSql, $section_id)))) {
            if (!($recSection = $resSection->fetchRow())) {
                $this->print_header();
                $this->print_error($TEXT['SECTION'] . ' ' . $TEXT['NOT_FOUND'], $backLink, true);
            }
        } else {
            $this->print_header();
            $this->print_error($this->_oDb->get_error(), $backLink, true);
        }
        return $recSection;
    }

    /**
     * @brief   get page details from `{TP}pages` database table
     *
     * @param int $page_id
     * @param string $backLink
     * @return  array   assoc array with page details
     * @global  array $TEXT
     */
    public function get_page_details($page_id, $backLink = 'index.php')
    {
        global $TEXT;
        $sSql = 'SELECT * FROM `{TP}pages` WHERE `page_id`= %d';
        if (($resPages = $this->_oDb->query(sprintf($sSql, $page_id)))) {
            if (!($recPage = $resPages->fetchRow())) {
                $this->print_header();
                $this->print_error($TEXT['PAGE'] . ' ' . $TEXT['NOT_FOUND'], $backLink, true);
            }
        } else {
            $this->print_header();
            $this->print_error($this->_oDb->get_error(), $backLink, true);
        }
        return $recPage;
    }

    /**
     * @brief   Check if authenticated (logged in) user
     *          has permissions for specified page
     *
     * @param array $page
     * @param string $action (viewing|admin)
     * @return  bool    true if user has given permissions
     */
    public function get_page_permission($page, $action = 'admin')
    {
        if ($action != 'viewing') {
            $action = 'admin';
        }
        $action_groups = $action . '_groups';
        $action_users = $action . '_users';
        $groups = $users = '0';
        if (is_array($page)) {
            $groups = $page[$action_groups];
            $users = $page[$action_users];
            $iPageID = $page['page_id'];
        } else {
            $iPageID = $page;
            $sSql = 'SELECT `%s`,`%s` FROM `{TP}pages` WHERE `page_id`= %d';
            if (($res = $this->_oDb->query(sprintf($sSql, $action_groups, $action_users, $iPageID)))) {
                if (($rec = $res->fetchRow())) {
                    $groups = $rec[$action_groups];
                    $users = $rec[$action_users];
                }
            }
        }
        return ($this->ami_group_member($groups) || $this->is_group_match($this->get_user_id(), $users));
    }
}
