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

class login extends admin
{
    public function __construct($config_array)
    {
        // Get language vars
        global $MESSAGE, $database;
        
        // use admin class constructor 
        parent::__construct();
        
        // Get configuration values, and set them as class vars
        while (list($key, $value) = each($config_array)) {
            $this->{(strtolower($key))} = $value;
        }
        
        // set Redirect url ..
        if (!isset($this->redirect_url)) {$this->redirect_url = '';}
        
        // Get the supplied username and passwordfield if set.
        // this is used by spam protection methods
        if ($this->get_post('username_fieldname') != '') {
            $username_fieldname = $this->get_post('username_fieldname');
            $password_fieldname = $this->get_post('password_fieldname');
        } else {
            $username_fieldname = 'username';
            $password_fieldname = 'password';
        }
        
        // fetch username and Password
        $this->username = htmlspecialchars(strtolower($this->get_post($username_fieldname)), ENT_QUOTES);
        $this->password = $this->get_post($password_fieldname);
        
        // Get the length of the supplied username and password
        if ($this->get_post($username_fieldname) != '') {
            $this->username_len = strlen($this->username);
            $this->password_len = strlen($this->password);
        }
        
        // If the url is blank, set it to the default url
        // We got a posted url here , dont think this is a good idea... 
        $this->url = $this->get_post('url');
        if ($this->redirect_url != '') {
            $this->url = $this->redirect_url;
        }
        if (strlen($this->url) < 2) {
            $this->url = $config_array['DEFAULT_URL'];
        }
        
        // Already logged in ... but we are not sure at all the user is allowed that place ...
        // does not feel good 
        
        
        if ($this->is_authenticated() == true) {
            // User already logged-in, so redirect to default url
            header('Location: ' . $this->url);
            exit();
        } elseif ($this->username == '' and $this->password == '') {
            $this->message = $MESSAGE['LOGIN_BOTH_BLANK'];
            $this->display_login();
        } elseif ($this->username == '') {
            $this->message = $MESSAGE['LOGIN_USERNAME_BLANK'];
            $this->increase_attemps();
        } elseif ($this->password == '') {
            $this->message = $MESSAGE['LOGIN_PASSWORD_BLANK'];
            $this->increase_attemps();
        } else {
            // Check if the user exists (authenticate them)
            $this->password = md5($this->password);
            if ($this->authenticate()) {
                // Authentication successful
                //echo $this->url;exit();
                header("Location: " . $this->url);
                exit(0);
            } else {
                $this->message = $MESSAGE['LOGIN_AUTHENTICATION_FAILED'];
                $this->increase_attemps();
            }
        }
    }

    // Authenticate the user (check if they exist in the database)
    public function authenticate()
    {
        global $database;
        $loginname = (preg_match('/[\;\=\&\|\<\> ]/', $this->username) ? '' : $this->username);
        $sql = 'SELECT * FROM `' . $this->users_table . '` ';
        $sql .= 'WHERE `username`=\'' . $loginname . '\' AND `password`=\'' . $this->password . '\' AND `active`=1';
        $results = $database->query($sql);
        $results_array = $results->fetchRow();
        $num_rows = $results->numRows();
        if ($num_rows == 1) {
            $user_id = $results_array['user_id'];
            $this->user_id = $user_id;
            $_SESSION['USER_ID'] = $user_id;
            $_SESSION['GROUP_ID'] = $results_array['group_id'];
            $_SESSION['GROUPS_ID'] = $results_array['groups_id'];
            $_SESSION['USERNAME'] = $results_array['username'];
            $_SESSION['DISPLAY_NAME'] = $results_array['display_name'];
            $_SESSION['EMAIL'] = $results_array['email'];
            $_SESSION['HOME_FOLDER'] = $results_array['home_folder'];
            // Run remember function if needed
            if ($this->remember == true) {
                $this->remember($this->user_id);
            }
            // Set language
            if ($results_array['language'] != '') {
                $_SESSION['LANGUAGE'] = $results_array['language'];
            }
            // Set timezone
            if ($results_array['timezone'] != '-72000') {
                $_SESSION['TIMEZONE'] = $results_array['timezone'];
            } else {
                // Set a session var so apps can tell user is using default tz
                $_SESSION['USE_DEFAULT_TIMEZONE'] = true;
            }
            // Set date format
            if ($results_array['date_format'] != '') {
                $_SESSION['DATE_FORMAT'] = $results_array['date_format'];
            } else {
                // Set a session var so apps can tell user is using default date format
                $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
            }
            // Set time format
            if ($results_array['time_format'] != '') {
                $_SESSION['TIME_FORMAT'] = $results_array['time_format'];
            } else {
                // Set a session var so apps can tell user is using default time format
                $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
            }

            // Get group information
            $_SESSION['SYSTEM_PERMISSIONS'] = array();
            $_SESSION['MODULE_PERMISSIONS'] = array();
            $_SESSION['TEMPLATE_PERMISSIONS'] = array();
            $_SESSION['GROUP_NAME'] = array();

            $first_group = true;
            foreach (explode(",", $this->get_session('GROUPS_ID')) as $cur_group_id) {
                $sql = 'SELECT * FROM `' . $this->groups_table . '` WHERE `group_id`=\'' . $cur_group_id . '\'';
                $results = $database->query($sql);
                $results_array = $results->fetchRow();
                $_SESSION['GROUP_NAME'][$cur_group_id] = $results_array['name'];
                // Set system permissions
                if ($results_array['system_permissions'] != '') {
                    $_SESSION['SYSTEM_PERMISSIONS'] = array_merge($_SESSION['SYSTEM_PERMISSIONS'], explode(',', $results_array['system_permissions']));
                }
                // Set module permissions
                if ($results_array['module_permissions'] != '') {
                    if ($first_group) {
                        $_SESSION['MODULE_PERMISSIONS'] = explode(',', $results_array['module_permissions']);
                    } else {
                        $_SESSION['MODULE_PERMISSIONS'] = array_intersect($_SESSION['MODULE_PERMISSIONS'], explode(',', $results_array['module_permissions']));
                    }
                }
                // Set template permissions
                if ($results_array['template_permissions'] != '') {
                    if ($first_group) {
                        $_SESSION['TEMPLATE_PERMISSIONS'] = explode(',', $results_array['template_permissions']);
                    } else {
                        $_SESSION['TEMPLATE_PERMISSIONS'] = array_intersect($_SESSION['TEMPLATE_PERMISSIONS'], explode(',', $results_array['template_permissions']));
                    }
                }
                $first_group = false;
            }

            // Update the users table with current ip and timestamp
            $get_ts = time();
            $get_ip = $_SERVER['REMOTE_ADDR'];
            $sql = 'UPDATE `' . $this->users_table . '` ';
            $sql .= 'SET `login_when`=\'' . $get_ts . '\', `login_ip`=\'' . $get_ip . '\' ';
            $sql .= 'WHERE `user_id`=\'' . $user_id . '\'';
            $database->query($sql);
        } else {
            $num_rows = 0;
        }
        // Return if the user exists or not
        return $num_rows;
    }

    // Increase the count for login attemps
    public function increase_attemps()
    {
        if (!isset($_SESSION['ATTEMPS'])) {
            $_SESSION['ATTEMPS'] = 0;
        } else {
            $_SESSION['ATTEMPS'] = $this->get_session('ATTEMPS') + 1;
        }
        $this->display_login();
    }

    // Function to set a "remembering" cookie for the user
    // Only still here for compatibility
    public function remember($user_id)
    {
        return true;

    }

    // Function to check if a user has been remembered
    // Only still here for compatibility
    public function is_remembered()
    {
        return false;
    }

    // Display the login screen
    public function display_login()
    {
        // Get language vars
        global $MESSAGE;
        global $MENU;
        global $TEXT;
        // If attemps more than allowed, warn the user
        if ($this->get_session('ATTEMPS') > $this->max_attemps) {
            $this->warn();
        }
        // Show the login form
        if ($this->frontend != true) {
            // $template = new Template($this->template_dir);
            // Setup template object, parse vars to it, then parse it
            $template = new Template(dirname($this->correct_theme_source($this->template_file)));
            $template->set_file('page', $this->template_file);
            $template->set_block('page', 'mainBlock', 'main');
            if ($this->remember_me_option != true) {
                $template->set_var('DISPLAY_REMEMBER_ME', 'display: none;');
            } else {
                $template->set_var('DISPLAY_REMEMBER_ME', '');
            }
            $template->set_var(array(
                'ACTION_URL' => $this->login_url,
                'ATTEMPS' => $this->get_session('ATTEMPS'),
                'USERNAME' => $this->username,
                'USERNAME_FIELDNAME' => $this->username_fieldname,
                'PASSWORD_FIELDNAME' => $this->password_fieldname,
                'MESSAGE' => $this->message,
                'INTERFACE_DIR_URL' => ADMIN_URL . '/interface',
                'MAX_USERNAME_LEN' => $this->max_username_len,
                'MAX_PASSWORD_LEN' => $this->max_password_len,
                'WB_URL' => WB_URL,
                'THEME_URL' => THEME_URL,
                'VERSION' => VERSION,
                'REVISION' => REVISION,
                'LANGUAGE' => strtolower(LANGUAGE),
                'FORGOTTEN_DETAILS_APP' => $this->forgotten_details_app,
                'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS'],
                'TEXT_USERNAME' => $TEXT['USERNAME'],
                'TEXT_PASSWORD' => $TEXT['PASSWORD'],
                'TEXT_REMEMBER_ME' => $TEXT['REMEMBER_ME'],
                'TEXT_LOGIN' => $TEXT['LOGIN'],
                'TEXT_HOME' => $TEXT['HOME'],
                'PAGES_DIRECTORY' => PAGES_DIRECTORY,
                'SECTION_LOGIN' => $MENU['LOGIN'],
            )
            );
            if (defined('DEFAULT_CHARSET')) {
                $charset = DEFAULT_CHARSET;
            } else {
                $charset = 'utf-8';
            }

            $template->set_var('CHARSET', $charset);

            $template->parse('main', 'mainBlock', false);
            $template->pparse('output', 'page');
        }
    }


    // Warn user that they have had to many login attemps
    public function warn()
    {
        header('Location: ' . $this->warning_url);
        exit(0);
    }

}
