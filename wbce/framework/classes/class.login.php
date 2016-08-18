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

    public $username_fieldname="";
    public $password_fieldname="";

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
      
        // manually set by configuration array
        if ($this->username_fieldname!="" AND $this->password_fieldname!=""){
            // all ok 
            
        }
        // this is used by some spam protection methods
        elseif ($this->get_post('username_fieldname') != '' AND $this->get_post('password_fieldname'!= '')) {
            $this->username_fieldname = $this->get_post('username_fieldname');
            $this->password_fieldname = $this->get_post('password_fieldname');
        } 
        // Default field names 
        else {
            $this->username_fieldname = 'username';
            $this->password_fieldname = 'password';
        }
        
        // fetch username and Password
        $this->username = $this->get_post($this->username_fieldname);
        $this->password = $this->get_post($this->password_fieldname);
        
        
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
        
        
        // Hey no input ok just display login
        if ($this->username == '' and $this->password == '') {
            $this->message = $MESSAGE['LOGIN_BOTH_BLANK'];
            $this->display_login();
        } 
        else {
            // Check if the user exists 
            // (authenticate them, load session vars and more this does all the work)
            $uUserOk=WbAuth::Authenticate ($this->password,$this->username); 
             
            // Authentication successful
            if ($uUserOk===false) {
            
                //echo "Login says successfull";  
                
                // User logged-in, so redirect to default $this->url whatever it is 
                header("Location: " . $this->url);
                exit(0);
            } else {
                $this->message = $uUserOk;
                $this->increase_attemps();
            }
        }
    }

 
    // Increase the count for login attemps
    public function increase_attemps()
    {
        $_SESSION['ATTEMPS'] = 0;
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
