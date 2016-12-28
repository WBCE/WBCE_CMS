<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Get WB version
require_once ADMIN_PATH . '/interface/version.php';

class admin extends wb
{
    // Authenticate user then auto print the header
    public function __construct($section_name = '##skip##', $section_permission = 'start', $auto_header = true, $auto_auth = true,$operateBuffer=true)
    {
        parent::__construct(SecureForm::BACKEND);   
        if ($section_name != '##skip##') {
            global $database, $MESSAGE;
            // Specify the current applications name
            $this->section_name = $section_name;
            $this->section_permission = $section_permission;
            // Authenticate the user for this application
            if ($auto_auth == true) {
                // First check if the user is logged-in
                if ($this->is_authenticated() == false) {
                    header('Location: ' . ADMIN_URL . '/login/index.php');
                    exit(0);
                }

                // Now check if they are allowed in this section
                if ($this->get_permission($section_permission) == false) {
                    echo $section_permission."<br>";
                    die($MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES']);
                }
            }

            // Check if the backend language is also the selected language. If not, send headers again.
            $sql = 'SELECT `language` FROM `' . TABLE_PREFIX . 'users` ';
            $sql .= 'WHERE `user_id`=' . (int) $this->get_user_id();
            $user_language = $database->get_one($sql);
            $admin_folder = str_replace(WB_PATH, '', ADMIN_PATH);
            if ((LANGUAGE != $user_language) && file_exists(WB_PATH . '/languages/' . $user_language . '.php')
                && strpos($_SERVER['SCRIPT_NAME'], $admin_folder . '/') !== false) {
                // check if page_id is set
                $page_id_url = (isset($_GET['page_id'])) ? '&page_id=' . (int) $_GET['page_id'] : '';
                $section_id_url = (isset($_GET['section_id'])) ? '&section_id=' . (int) $_GET['section_id'] : '';
                if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') {
                    // check if there is an query-string
                    header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?lang=' . $user_language . $page_id_url . $section_id_url . '&' . $_SERVER['QUERY_STRING']);
                } else {
                    header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?lang=' . $user_language . $page_id_url . $section_id_url);
                }
                exit();
            }

            // Auto header code
            if ($auto_header == true) {
                $this->print_header($body_tags = '',$operateBuffer);
            }
        }
        // i know this sucks but some old stuff really need this
        global $wb;
        $wb = $this;
    }

    // Print the admin header
    public function print_header($body_tags = '', $operateBuffer=true)
    {
        if ($operateBuffer){
            ob_start();
        }
    
        // Get vars from the language file
        global $MENU, $MESSAGE, $TEXT, $database;
        // Connect to database and get website title
        // $GLOBALS['FTAN'] = $this->getFTAN();
        $this->createFTAN();
        $sql = 'SELECT `value` FROM `' . TABLE_PREFIX . 'settings` '
        . 'WHERE `name`=\'website_title\'';
        $get_title = $database->query($sql);
        $title = $get_title->fetchRow(MYSQLI_ASSOC);
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
            $sql = 'SELECT `link` FROM `' . TABLE_PREFIX . 'pages` '
            . 'WHERE `page_id`=' . intval($_GET['page_id']);
            $result = @$database->query($sql);
            $row = @$result->fetchRow(MYSQLI_ASSOC);
            if ($row) {
                // wblink    
                if(preg_match ("/^\[wblink\d+\]$/",  $row['link'] )) {
                    $view_url = $row['link'];
                    $this->preprocess($view_url);
                } 
                // direkt link
                elseif (preg_match ("/\:\/\//",  $row['link'] )) {
                    $view_url = $row['link'];
                } 
                // normal link
                else {
                    $view_url .= PAGES_DIRECTORY . $row['link'] . PAGE_EXTENSION;
                    if (OPF_SHORT_URL) {$view_url = WB_URL . $row ['link']; }
                }
                
            }

        }

        $header_template->set_var(array(
            'SECTION_NAME' => $MENU[strtoupper($this->section_name)],
            'BODY_TAGS' => $body_tags,
            'WEBSITE_TITLE' => ($title['value']),
            'TEXT_ADMINISTRATION' => $TEXT['ADMINISTRATION'],
            'CURRENT_USER' => $MESSAGE['START_CURRENT_USER'],
            'DISPLAY_NAME' => $this->get_display_name(),
            'CHARSET' => $charset,
            'LANGUAGE' => strtolower(LANGUAGE),
            'WBCE_VERSION' => WBCE_VERSION,
            'PHP_VERSION' => phpversion (),           
            'WBCE_TAG' => (in_array(WBCE_TAG, array('', '-'))
                ? '-'
                : '<a href="https://github.com/WBCE/WebsiteBaker_CommunityEdition/releases/tag/' . WBCE_TAG . '" target="_blank">' . WBCE_TAG . '</a>'
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
            'TITLE_START' => $MENU['START'],
            'TITLE_BACK' => $TEXT['BACK'],
            'TITLE_VIEW' => $MENU['VIEW'],
            'TITLE_HELP' => $MENU['HELP'],
            'TITLE_LOGOUT' => $MENU['LOGOUT'],
            'URL_VIEW' => $view_url,
            'URL_HELP' => 'http://websitebaker.org/en/help.php',
            'BACKEND_MODULE_CSS' => $this->register_backend_modfiles('css'), // adds backend.css
            'BACKEND_MODULE_JS' => $this->register_backend_modfiles('js'),   // adds backend.js
        )
        );

        // Create the menu
        $menu = array(
            array(ADMIN_URL . '/pages/index.php', '', $MENU['PAGES'], 'pages', 1),
            array(ADMIN_URL . '/media/index.php', '', $MENU['MEDIA'], 'media', 1),
            array(ADMIN_URL . '/addons/index.php', '', $MENU['ADDONS'], 'addons', 1),
            array(ADMIN_URL . '/preferences/index.php', '', $MENU['PREFERENCES'], 'preferences', 1),
            array(ADMIN_URL . '/settings/index.php', '', $MENU['SETTINGS'], 'settings', 1),
            array(ADMIN_URL . '/admintools/index.php', '', $MENU['ADMINTOOLS'], 'admintools', 1),
            array(ADMIN_URL . '/access/index.php', '', $MENU['ACCESS'], 'access', 1),
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

    // Print the admin footer
    public function print_footer($activateJsAdmin = false,$operateBuffer=true)
    {
        // include the required file for Javascript admin
        if ($activateJsAdmin != false) {
            if (file_exists(WB_PATH . '/modules/jsadmin/jsadmin_backend_include.php')) {
                @include_once WB_PATH . '/modules/jsadmin/jsadmin_backend_include.php';
            }
        }

        // Setup template object, parse vars to it, then parse it
        $footer_template = new Template(dirname($this->correct_theme_source('footer.htt')));
        $footer_template->set_file('page', 'footer.htt');
        $footer_template->set_block('page', 'footer_block', 'header');
        $footer_template->set_var(array(
            'WBCE_VERSION' => WBCE_VERSION,
            'PHP_VERSION' => phpversion (),           
            'WBCE_TAG' => (in_array(WBCE_TAG, array('', '-'))
                ? '-'
                : '<a href="https://github.com/WBCE/WebsiteBaker_CommunityEdition/releases/tag/' . WBCE_TAG . '" target="_blank">' . WBCE_TAG . '</a>'
            ),
            'BACKEND_BODY_MODULE_JS' => $this->register_backend_modfiles_body('js'),
            'WB_URL' => WB_URL,
            'ADMIN_URL' => ADMIN_URL,
            'THEME_URL' => THEME_URL,
        ));
        $footer_template->parse('header', 'footer_block', false);
        $footer_template->pparse('output', 'page');
        if ($operateBuffer){
            // OPF dashboard
            $allOutput = ob_get_clean ();
            if(function_exists('opf_controller')) { 
                $allOutput = opf_controller('backend', $allOutput);
            }
            // konventional output filter 
            if (!defined("WB_SUPPRESS_OLD_OPF") or !WB_SUPPRESS_OLD_OPF){
                // Module is installed, filter file in place?
                $file=WB_PATH . '/modules/output_filter/filter_routines.php';
                if (file_exists($file)) {
                    include_once ($file);
                    if (function_exists('executeBackendOutputFilter')) {
                        $allOutput = executeBackendOutputFilter($allOutput);
                    }
                }
            }
            
            // Process direct Output if set. This ends the script here and regular output is not put out. 
            $this->DirectOutput();
            
            //Output all regular pagecontent
            echo $allOutput;
        }
        
        
        
    }


    //
    public function get_section_details($section_id, $backLink = 'index.php')
    {
        global $database, $TEXT;
        $sql = 'SELECT * FROM `' . TABLE_PREFIX . 'sections` ';
        $sql .= 'WHERE `section_id`=' . intval($section_id);
        if (($resSection = $database->query($sql))) {
            if (!($recSection = $resSection->fetchRow(MYSQLI_ASSOC))) {
                $this->print_header();
                $this->print_error($TEXT['SECTION'] . ' ' . $TEXT['NOT_FOUND'], $backLink, true);
            }
        } else {
            $this->print_header();
            $this->print_error($database->get_error(), $backLink, true);
        }
        return $recSection;
    }

    public function get_page_details($page_id, $backLink = 'index.php')
    {
        global $database, $TEXT;
        $sql = 'SELECT * FROM `' . TABLE_PREFIX . 'pages` ';
        $sql .= 'WHERE `page_id`=' . intval($page_id);
        if (($resPages = $database->query($sql))) {
            if (!($recPage = $resPages->fetchRow(MYSQLI_ASSOC))) {
                $this->print_header();
                $this->print_error($TEXT['PAGE'] . ' ' . $TEXT['NOT_FOUND'], $backLink, true);
            }
        } else {
            $this->print_header();
            $this->print_error($database->get_error(), $backLink, true);
        }
        return $recPage;
    }

  

    // Returns a system permission for a menu link
    public function get_link_permission($title)
    {
        $title = str_replace('_blank', '', $title);
        $title = strtolower($title);
        // Set system permissions var
        $system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
        // Set module permissions var
        $module_permissions = $this->get_session('MODULE_PERMISSIONS');
        if ($title == 'start') {
            return true;
        } else {
            // Return true if system perm = 1
            if (is_numeric(array_search($title, $system_permissions))) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Function to add optional module Javascript into the <body> section of the backend
    public function register_backend_modfiles_body($file_id = "js")
    {
        // Set return value as we need to add up values
        $retval="";//N
        
        // sanity check of parameter passed to the function
        $file_id = strtolower($file_id);
        if ($file_id !== "javascript" && $file_id !== "js") {
            return;
        }
        
        global $database;
        
        // empty collector var
        $body_links = "";
        
        // no css variant here in body
        // define default baselink and filename for optional module javascript and stylesheet files  
               
        // new theme position
        $theme_link = '<script src="' . WB_URL . '/templates/'.DEFAULT_THEME.'/modules/{MODULE_DIRECTORY}/backend_body.js" type="text/javascript"></script>';

        // old position of file
        $base_link = '<script src="' . WB_URL . '/modules/{MODULE_DIRECTORY}/backend_body.js" type="text/javascript"></script>';
        
        // filename
        $base_file = "backend_body.js";
        
        
        
        // check if backend_body.js files needs to be included to the <body></body> section of the backend
        if (isset($_GET['tool'])) {
        
            //prevent any injections
            $_GET['tool']=preg_replace("/[^a-z0-9_]/isu","", $_GET['tool']); 
            
            // check if displayed page contains a installed admin tool
            $sql = 'SELECT * FROM `' . TABLE_PREFIX . 'addons` ';
            $sql .= 'WHERE `type`=\'module\'  AND `directory`=\'' . addslashes($_GET['tool']) . '\'';
            $result = $database->query($sql);
            if ($result->numRows()) { // Yess it does 
            
                // check if admin tool directory contains a backend_body.js file to include
                $tool = $result->fetchRow(MYSQLI_ASSOC);
                
                // Test for override in template
                if (file_exists(WB_PATH . '/templates/'.DEFAULT_THEME.'/modules/'. $tool['directory'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $retval.= str_replace("{MODULE_DIRECTORY}", $tool['directory'], $theme_link)."\n";
                }
              
                // Test for old position
                else if (file_exists(WB_PATH . '/modules/' . $tool['directory'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $retval.= str_replace("{MODULE_DIRECTORY}", $tool['directory'], $base_link)."\n";
                }                            
            }
        } 
        
        if (isset($_GET['page_id']) || isset($_POST['page_id'])) {
        
            // check if displayed page in the backend contains a page module
            // post and get both are allowed
            if (isset($_GET['page_id'])) {
                $page_id = (int) addslashes($_GET['page_id']);
            } else {
                $page_id = (int) addslashes($_POST['page_id']);
            }
            
            // gather information for all models embedded on actual page
            $sql = 'SELECT DISTINCT `module` FROM `' . TABLE_PREFIX . 'sections` WHERE `page_id`=' . (int) $page_id;
            $query_modules = $database->query($sql);
            while ($row = $query_modules->fetchRow(MYSQLI_ASSOC)) {
                $tmp_link ="";
                // Test for override in template
                if (file_exists(WB_PATH . '/templates/'.DEFAULT_THEME.'/modules/'. $row['module'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $theme_link)."\n";
                } 
                // or maybe old version old position
                else if(file_exists(WB_PATH . '/modules/' . $row['module'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link)."\n";
                }
                
                // ensure that backend_body.js is only added once per module type
                if (!empty($tmp_link) AND strpos($body_links, $tmp_link) === false) {
                    $body_links .= $tmp_link . "\n";
                }
            }
            // write out links with all external module javascript/CSS files, remove last line feed
            $retval.= rtrim($body_links);
        }
        
        return $retval; //N
    }

    // Function to add optional module Javascript or CSS stylesheets into the <head> section of the backend
    public function register_backend_modfiles($file_id = "css")
    {   
        // Set return value as we need to add up values
        $retval="";//N
        
        // sanity check of parameter passed to the function
        $file_id = strtolower($file_id);
        if ($file_id !== "css" && $file_id !== "javascript" && $file_id !== "js") {
            return;
        }

        global $database;
        // define default baselink and filename for optional module javascript and stylesheet files
        $head_links = "";
        if ($file_id == "css") {
        
             // old position of file
            $base_link = '<link href="' . WB_URL . '/modules/{MODULE_DIRECTORY}/backend.css"';
            $base_link .= ' rel="stylesheet" type="text/css" media="screen" />';
            
            // new theme position
            $theme_link = '<link href="' . WB_URL . '/templates/'.DEFAULT_THEME.'/modules/{MODULE_DIRECTORY}/backend.css"';
            $theme_link .= ' rel="stylesheet" type="text/css" media="screen" />';
            
            //filename
            $base_file = "backend.css";
            
        } else {
        
            // old position of file
            $base_link = '<script src="' . WB_URL . '/modules/{MODULE_DIRECTORY}/backend.js" type="text/javascript"></script>';
            
            // new theme position
            $theme_link = '<script src="' . WB_URL . '/templates/'.DEFAULT_THEME.'/modules/{MODULE_DIRECTORY}/backend.js" type="text/javascript"></script>';

            //filename
            $base_file = "backend.js";
           
        }

        // check if backend.js or backend.css files needs to be included to the <head></head> section of the backend
        // this is for admintools and backend modules as same as settings , all have $_GET['tool'] or $_POST['tool'] set
        if (isset($_GET['tool'])) {
        
            //prevent any injections 
            $_GET['tool']=preg_replace("/[^a-z0-9_]/isu","", $_GET['tool']); 
            
            // check if displayed page contains a installed admin tool
            $sql = 'SELECT * FROM `' . TABLE_PREFIX . 'addons` ';
            $sql .= 'WHERE `type`=\'module\'  AND `directory`=\'' . addslashes($_GET['tool']) . '\'';
            $result = $database->query($sql);
            if ($result->numRows()) {
            
                // check if admin tool directory contains a backend.js or backend.css file to include
                $tool = $result->fetchRow(MYSQLI_ASSOC);

                // Test for override in template
                if (file_exists(WB_PATH . '/templates/'.DEFAULT_THEME.'/modules/'. $tool['directory'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $retval.= str_replace("{MODULE_DIRECTORY}", $tool['directory'], $theme_link)."\n";
                }
              
                // Test for old position
                else if (file_exists(WB_PATH . '/modules/' . $tool['directory'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $retval.= str_replace("{MODULE_DIRECTORY}", $tool['directory'], $base_link)."\n";
                }
                
            }
        } 
        
        // Now we got modules that maybe tools , but maybe page editors too 
        // post and get both are allowed
        if (isset($_GET['page_id']) || isset($_POST['page_id'])) {
            // check if displayed page in the backend contains a page module
            if (isset($_GET['page_id'])) {
                $page_id = (int) $_GET['page_id'];
            } else {
                $page_id = (int) $_POST['page_id'];
            }

            // gather information for all moduls embedded on actual page
            $sql = 'SELECT `module` FROM `' . TABLE_PREFIX . 'sections` WHERE `page_id`=' . (int) $page_id;
            $query_modules = $database->query($sql);
            while ($row = $query_modules->fetchRow(MYSQLI_ASSOC)) {
                $tmp_link ="";
                
                // Test for override in template
                if (file_exists(WB_PATH . '/templates/'.DEFAULT_THEME.'/modules/'. $row['module'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $theme_link)."\n";//N
                } 
                // or maybe old version old position
                else if(file_exists(WB_PATH . '/modules/' . $row['module'] . '/' . $base_file)) {
                    // return link to the backend.js or backend.css file of tools 
                    $tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link)."\n";
                }
                
                // ensure that backend.js or backend.css is only added once per module type
                if (!empty ($tmp_link) AND strpos($head_links, $tmp_link) === false) {
                    $head_links .= $tmp_link . "\n";
                }
 
            }
            
            // write out links with all external module javascript/CSS files, remove last line feed
            //echo htmlentities("HL:".$head_links);
            $retval.= rtrim($head_links);
        }
        
        return $retval; //N
    }
}



