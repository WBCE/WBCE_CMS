<?php
/**
 *
 * @category        backend
 * @package         install
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: save.php 1638 2012-03-13 23:01:47Z darkviper $
 * @filesource      $HeadURL:  $
 * @lastmodified    $Date: $
 *
 */

$debug = true;

if (true === $debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
// Start a session
if (!defined('SESSION_STARTED')) {
    session_name('wb-installer');
    session_start();
    define('SESSION_STARTED', true);
}
// get random-part for session_name()
list($usec,$sec) = explode(' ',microtime());
srand((float)$sec+((float)$usec*100000));
$session_rand = rand(1000,9999);

// Function to set error
function set_error($message, $field_name = '') {
//    global $_POST;
    if (isset($message) AND $message != '') {
        // Copy values entered into session so user doesn't have to re-enter everything
        if (isset($_POST['website_title'])) {
            $_SESSION['wb_url'] = $_POST['wb_url'];
            $_SESSION['default_timezone'] = $_POST['default_timezone'];
            $_SESSION['default_language'] = $_POST['default_language'];
            if (!isset($_POST['operating_system'])) {
                $_SESSION['operating_system'] = 'linux';
            } else {
                $_SESSION['operating_system'] = $_POST['operating_system'];
            }
            if (!isset($_POST['world_writeable'])) {
                $_SESSION['world_writeable'] = false;
            } else {
                $_SESSION['world_writeable'] = true;
            }
            $_SESSION['database_host'] = $_POST['database_host'];
            $_SESSION['database_username'] = $_POST['database_username'];
            $_SESSION['database_password'] = $_POST['database_password'];
            $_SESSION['database_name'] = $_POST['database_name'];
            $_SESSION['table_prefix'] = $_POST['table_prefix'];
            if (!isset($_POST['install_tables'])) {
                $_SESSION['install_tables'] = false;
            } else {
                $_SESSION['install_tables'] = true;
            }
            $_SESSION['website_title'] = $_POST['website_title'];
            $_SESSION['admin_username'] = $_POST['admin_username'];
            $_SESSION['admin_email'] = $_POST['admin_email'];
            $_SESSION['admin_password'] = $_POST['admin_password'];
            $_SESSION['admin_repassword'] = $_POST['admin_repassword'];
        }
        // Set the message
        $_SESSION['message'] = $message;
        // Set the element(s) to highlight
        if ($field_name != '') {
            $_SESSION['ERROR_FIELD'] = $field_name;
        }
        // Specify that session support is enabled
        $_SESSION['session_support'] = '<font class="good">Enabled</font>';
        // Redirect to first page again and exit
        header('Location: index.php?sessions_checked=true');
        exit();
    }
}
/* */

// Function to workout what the default permissions are for files created by the webserver
function default_file_mode($temp_dir) {
    if (version_compare(PHP_VERSION, '5.3.6', '>=') && is_writable($temp_dir)) {
        $filename = $temp_dir.'/test_permissions.txt';
        $handle = fopen($filename, 'w');
        fwrite($handle, 'This file is to get the default file permissions');
        fclose($handle);
        $default_file_mode = '0'.substr(sprintf('%o', fileperms($filename)), -3);
        unlink($filename);
    } else {
        $default_file_mode = '0777';
    }
    return $default_file_mode;
}

// Function to workout what the default permissions are for directories created by the webserver
function default_dir_mode($temp_dir) {
    if (version_compare(PHP_VERSION, '5.3.6', '>=') && is_writable($temp_dir)) {
        $dirname = $temp_dir.'/test_permissions/';
        mkdir($dirname);
        $default_dir_mode = '0'.substr(sprintf('%o', fileperms($dirname)), -3);
        rmdir($dirname);
    } else {
        $default_dir_mode = '0777';
    }
    return $default_dir_mode;
}

function add_slashes($input) {
    if (get_magic_quotes_gpc() || ( !is_string($input) )) {
        return $input;
    }
    $output = addslashes($input);
    return $output;
}

// Begin check to see if form was even submitted
// Set error if no post vars found
if (!isset($_POST['website_title'])) {
    set_error('Please fill-in the form below');
}
// End check to see if form was even submitted

// Begin path and timezone details code

// Check if user has entered the installation url
if (!isset($_POST['wb_url']) OR $_POST['wb_url'] == '') {
    set_error('Please enter an absolute URL', 'wb_url');
} else {
    $wb_url = $_POST['wb_url'];
}
// Remove any slashes at the end of the URL
$wb_url = rtrim($wb_url, '\\/');
// Get the default time zone
if (!isset($_POST['default_timezone']) OR !is_numeric($_POST['default_timezone'])) {
    set_error('Please select a valid default timezone', 'default_timezone');
} else {
    $default_timezone = $_POST['default_timezone']*60*60;
}
// End path and timezone details code

// Get the default language



$sLangDir = str_replace('\\', '/', dirname(dirname(__FILE__)).'/languages/');
$allowed_languages = preg_replace('/^.*\/([A-Z]{2})\.php$/iU', '\1', glob($sLangDir.'??.php'));
if (!isset($_POST['default_language']) OR !in_array($_POST['default_language'], $allowed_languages)) {
    set_error('Please select a valid default backend language','default_language');
} else {
    $default_language = $_POST['default_language'];
    // make sure the selected language file exists in the language folder
    if (!file_exists('../languages/' .$default_language .'.php')) {
        set_error('The language file: \'' .$default_language .'.php\' is missing. Upload file to language folder or choose another language','default_language');
    }
}
// End default language details code

// Begin operating system specific code
// Get operating system
if (!isset($_POST['operating_system']) OR $_POST['operating_system'] != 'linux' AND $_POST['operating_system'] != 'windows') {
    set_error('Please select a valid operating system');
} else {
    $operating_system = $_POST['operating_system'];
}
// Work-out file permissions
if ($operating_system == 'windows') {
    $file_mode = '0666';
    $dir_mode = '0777';
} elseif (isset($_POST['world_writeable']) AND $_POST['world_writeable'] == 'true') {
    $file_mode = '0666';
    $dir_mode = '0777';
} else {
    $file_mode = default_file_mode('../temp');
    $dir_mode = default_dir_mode('../temp');
}
// End operating system specific code

// Begin database details code
// Check if user has entered a database host
if (!isset($_POST['database_host']) OR $_POST['database_host'] == '') {
    set_error('Please enter a database host name', 'database_host');
} else {
    $database_host = trim($_POST['database_host']);
}
// extract port if available
if (isset($database_port)) { unset($database_port); }
$aMatches = preg_split('/:/s', $database_host, -1, PREG_SPLIT_NO_EMPTY);
if (isset($aMatches[1])) {
    $database_host = $aMatches[0];
    $database_port = (int)$aMatches[1];
}

// Check if user has entered a database username
if (!isset($_POST['database_username']) OR $_POST['database_username'] == '') {
    set_error('Please enter a database username','database_username');
} else {
    $database_username = $_POST['database_username'];
}
// Check if user has entered a database password
if (!isset($_POST['database_password'])) {
    set_error('Please enter a database password', 'database_password');
} else {
    $database_password = $_POST['database_password'];
}
// Check if user has entered a database name
if (!isset($_POST['database_name']) OR $_POST['database_name'] == '') {
    set_error('Please enter a database name', 'database_name');
} else {
    // make sure only allowed characters are specified
    if(preg_match('/[^a-z0-9_-]+/i', $_POST['database_name'])) {
        // contains invalid characters (only a-z, A-Z, 0-9 and _ allowed to avoid problems with table/field names)
        set_error('Only characters a-z, A-Z, 0-9, - and _ allowed in database name.', 'database_name');
    }
    $database_name = $_POST['database_name'];
}
// Get table prefix
if (preg_match('/[^a-z0-9_]+/i', $_POST['table_prefix'])) {
    // contains invalid characters (only a-z, A-Z, 0-9 and _ allowed to avoid problems with table/field names)
    set_error('Only characters a-z, A-Z, 0-9 and _ allowed in table_prefix.', 'table_prefix');
} else {
    $table_prefix = $_POST['table_prefix'];
}

$install_tables = true;
// Begin website title code
// Get website title
if (!isset($_POST['website_title']) OR $_POST['website_title'] == '') {
    set_error('Please enter a website title', 'website_title');
} else {
    $website_title = add_slashes($_POST['website_title']);
}
// End website title code

// Begin admin user details code
// Get admin username
if (!isset($_POST['admin_username']) OR $_POST['admin_username'] == '') {
    set_error('Please enter a username for the Administrator account','admin_username');
} else {
    $admin_username = $_POST['admin_username'];
}
// Get admin email and validate it
if (!isset($_POST['admin_email']) OR $_POST['admin_email'] == '') {
    set_error('Please enter an email for the Administrator account','admin_email');
} else {
    if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i', $_POST['admin_email'])) {
        $admin_email = $_POST['admin_email'];
    } else {
        set_error('Please enter a valid email address for the Administrator account','admin_email');
    }
}
// Get the two admin passwords entered, and check that they match
if (!isset($_POST['admin_password']) OR $_POST['admin_password'] == '') {
    set_error('Please enter a password for the Administrator account','admin_password');
} else {
    $admin_password = $_POST['admin_password'];
}
if (!isset($_POST['admin_repassword']) OR $_POST['admin_repassword'] == '') {
    set_error('Please make sure you re-enter the password for the Administrator account','admin_repassword');
} else {
    $admin_repassword = $_POST['admin_repassword'];
}
if ($admin_password != $admin_repassword) {
    set_error('Sorry, the two Administrator account passwords you entered do not match','admin_repassword');
}

$database_charset = 'utf8';
// End admin user details code

// build name and content of the config file
$config_filename = dirname(dirname(__FILE__)).'/config.php';
$config_content 
    = '<?php'.PHP_EOL
    . PHP_EOL
    . 'define(\'DB_TYPE\', \'mysqli\');'.PHP_EOL
    . 'define(\'DB_HOST\', \''.$database_host.'\');'.PHP_EOL
    . (isset($database_port) ? 'define(\'DB_PORT\', \''.$database_port.'\');'.PHP_EOL : '')
    . 'define(\'DB_NAME\', \''.$database_name.'\');'.PHP_EOL
    . 'define(\'DB_USERNAME\', \''.$database_username.'\');'.PHP_EOL
    . 'define(\'DB_PASSWORD\', \''.$database_password.'\');'.PHP_EOL
    . 'define(\'DB_CHARSET\', \''.$database_charset.'\');'.PHP_EOL
    . 'define(\'TABLE_PREFIX\', \''.$table_prefix.'\');'.PHP_EOL
    . PHP_EOL
    . 'define(\'WB_URL\', \''.$wb_url.'\');'.PHP_EOL
    . 'define(\'ADMIN_DIRECTORY\', \'admin\');'
    . '// no leading/trailing slash or backslash!! A simple directory name only!!'.PHP_EOL
    ;
// Check if the file exists and is writable first.
$sMsg = '';
if (is_writable($config_filename)) {
    // try to write file
    if (file_put_contents($config_filename, $config_content) === false) {
        $sMsg = 'Cannot write to the configuration file <'.$config_filename.'>';
    }
} else {
    $sMsg = 'The configuration file <'.$config_filename.'> is missing or not writable.<br />'
          . 'Change its permissions so it is, then re-run step 4.';
}
if ($sMsg) { set_error($sMsg); } // if something gone wrong, break with message
// include config file to set constants
include_once($config_filename);
// now we can complete the config file
$config_content 
    = PHP_EOL.'require_once(dirname(__FILE__).\'/framework/initialize.php\');'.PHP_EOL
    . '// end of file -------------'.PHP_EOL
    . PHP_EOL;
// no errorhandling needed. 15 lines before we already wrote to this file successful!
file_put_contents($config_filename, $config_content, FILE_APPEND);

// Define additional configuration constants
define('WB_PATH', dirname(dirname(__FILE__)));
define('ADMIN_PATH', WB_PATH.'/'.ADMIN_DIRECTORY);
define('ADMIN_URL', WB_URL.'/'.ADMIN_DIRECTORY);
require(ADMIN_PATH.'/interface/version.php');

// Try connecting to database
if (!file_exists(WB_PATH.'/framework/class.database.php')) {
    set_error('It appears the Absolute path that you entered is incorrect or file \'class.database.php\' is missing!');
}
include(WB_PATH.'/framework/class.database.php');
try {
    $database = new database();
} catch (DatabaseException $e) {
    $sMsg = 'Database host name, username and/or password incorrect.<br />MySQL Error:<br />'
          . $e->getMessage();
    set_error($sMsg);
}
if (!defined('WB_INSTALL_PROCESS')) { 
    define ('WB_INSTALL_PROCESS', true); 
}

/*****************************
Begin Create Database Tables
*****************************/
$sInstallDir = dirname(__FILE__);
if (is_readable($sInstallDir.'/install_struct.sql')) {
    if (! $database->SqlImport($sInstallDir.'/install_struct.sql', TABLE_PREFIX, false)) {
        set_error('unable to import \'install/install_struct.sql\'');
    }
} else {
    set_error('unable to read file \'install/install_struct.sql\'');
}
if (is_readable($sInstallDir.'/install_data.sql')) {
    if (! $database->SqlImport($sInstallDir.'/install_data.sql', TABLE_PREFIX)) {
        set_error('unable to import \'install/install_data.sql\'');
    }
} else {
    set_error('unable to read file \'install/install_data.sql\'');
}
$sql = // add settings from install input
'INSERT INTO `'.TABLE_PREFIX.'settings` (`name`, `value`) VALUES '
    .'(\'wb_version\', \''.VERSION.'\'),'
    .'(\'wb_revision\', \''.REVISION.'\'),'
    .'(\'wb_sp\', \''.SP.'\'),'
    .'(\'website_title\', \''.$website_title.'\'),'
    .'(\'default_language\', \''.$default_language.'\'),'
    .'(\'app_name\', \'wb-'.$session_rand.'\'),'
    .'(\'default_timezone\', \''.$default_timezone.'\'),'
    .'(\'operating_system\', \''.$operating_system.'\'),'
    .'(\'string_file_mode\', \''.$file_mode.'\'),'
    .'(\'string_dir_mode\', \''.$dir_mode.'\'),'
    .'(\'server_email\', \''.$admin_email.'\')';
if (! ($database->query($sql))) {
    set_error('unable to write \'install presets\' into table \'settings\'');
}
$sql = // add the Admin user
     'INSERT INTO `'.TABLE_PREFIX.'users` '
    .'SET `user_id`=1, '
    .    '`group_id`=1, '
    .    '`groups_id`=\'1\', '
    .    '`active`=\'1\', '
    .    '`username`=\''.$admin_username.'\', '
    .    '`language`=\''.$default_language.'\', '
    .    '`password`=\''.md5($admin_password).'\', '
    .    '`email`=\''.$admin_email.'\', '
    .    '`timezone`=\''.$default_timezone.'\', '
    .    '`display_name`=\'Administrator\'';
if (! ($database->query($sql))) {
    set_error('unable to write Administrator account into table \'users\'');
}
/**********************
END OF TABLES IMPORT
**********************/

// initialize the system
include(WB_PATH.'/framework/initialize.php');

$sSecMod = (defined('SECURE_FORM_MODULE') && SECURE_FORM_MODULE != '') ? '.'.SECURE_FORM_MODULE : '';
$sSecMod = WB_PATH.'/framework/SecureForm'.$sSecMod.'.php';
require_once($sSecMod);

require_once(WB_PATH.'/framework/class.admin.php');
/***********************
// Dummy class to allow modules' install scripts to call $admin->print_error
***********************/
class admin_dummy extends admin
{
    public $error='';
    public function print_error($message, $link = 'index.php', $auto_footer = true)
    {
        $this->error=$message;
    }
}

// Include WB functions file
require_once(WB_PATH.'/framework/functions.php');
// Re-connect to the database, this time using in-build database class
require_once(WB_PATH.'/framework/class.login.php');
// reconnect database if needed
//if (!(isset($database) & ($database instanceof database))) {
//    $database = new database();
//}
// Include the PclZip class file (thanks to
require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
$admin = new admin_dummy('Start','',false,false);

// Load addons into DB
$dirs['modules']   = WB_PATH.'/modules/';
$dirs['templates'] = WB_PATH.'/templates/';
$dirs['languages'] = WB_PATH.'/languages/';

foreach ($dirs as $type => $dir) {
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '' AND substr($file, 0, 1) != '.' AND $file != 'admin.php' AND $file != 'index.php') {
                // Get addon type
                if ($type == 'modules') {
                    load_module($dir.'/'.$file, true);
                    // Pretty ugly hack to let modules run $admin->set_error
                    // See dummy class definition admin_dummy above
                    if ($admin->error!='') {
                        set_error($admin->error);
                    }
                } elseif ($type == 'templates') {
                    load_template($dir.'/'.$file);
                } elseif ($type == 'languages') {
                    load_language($dir.'/'.$file);
                }
            }
        }
    closedir($handle);
    }
}
// Check if there was a database error
if ($database->is_error()) {
    set_error($database->get_error());
}

$ThemeUrl = WB_URL.$admin->correct_theme_source('warning.html');
// Setup template object, parse vars to it, then parse it
$ThemePath = realpath(WB_PATH.$admin->correct_theme_source('login.htt'));

// Log the user in and go to Website Baker Administration
$thisApp = new Login(
        array(
                "MAX_ATTEMPS" => "50",
                "WARNING_URL" => $ThemeUrl."/warning.html",
                "USERNAME_FIELDNAME" => 'admin_username',
                "PASSWORD_FIELDNAME" => 'admin_password',
                "REMEMBER_ME_OPTION" => SMART_LOGIN,
                "MIN_USERNAME_LEN" => "2",
                "MIN_PASSWORD_LEN" => "3",
                "MAX_USERNAME_LEN" => "30",
                "MAX_PASSWORD_LEN" => "30",
                'LOGIN_URL' => ADMIN_URL."/login/index.php",
                'DEFAULT_URL' => ADMIN_URL."/start/index.php",
                'TEMPLATE_DIR' => $ThemePath,
                'TEMPLATE_FILE' => 'login.htt',
                'FRONTEND' => false,
                'FORGOTTEN_DETAILS_APP' => ADMIN_URL."/login/forgot/index.php",
                'USERS_TABLE' => TABLE_PREFIX."users",
                'GROUPS_TABLE' => TABLE_PREFIX."groups",
        )
);
