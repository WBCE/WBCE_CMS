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

define ("WB_DEBUG", true);
define ("WB_INSTALLER", true);

$_SESSION['ERROR_FIELD']=array();

if (WB_DEBUG === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// needed by class secureform
define ("WB_SECFORM_TIMEOUT",'7200');


// Start a session
if (!defined('SESSION_STARTED')) {
    session_name('wb-installer');
    session_start();
    define('SESSION_STARTED', true);
}
// get random-part for session_name()
// Random name is for later sessions (more secure), not installer session
list($usec, $sec) = explode(' ', microtime());
srand((float) $sec + ((float) $usec * 100000));
$session_rand = rand(1000, 9999);

// Some default settings
$_SESSION['message']=array();
$_SESSION['ERROR_FIELD']=array();

// Trim all entries in $_POST array
$_POST = array_map('trim', $_POST);

// Require needed helper functions
require_once("helper_functions.php");

// Begin check to see if form was even submitted
// Set error if no post vars found
$IsError=false;
if (!isset($_POST['website_title'])) {
    set_error(d('e1: ').'Please fill-in the form below');
    $IsError=true;
}

// End check to see if form was even submitted

// Begin path and timezone details code

// Check if user has entered the installation url
if (!isset($_POST['wb_url']) or $_POST['wb_url'] == '') {
    set_error(d('e2: ').'Please enter an absolute URL', 'wb_url');
    $IsError=true;
} else {
    $wb_url = $_POST['wb_url'];
}
// Remove any slashes at the end of the URL
$wb_url = rtrim($wb_url, '\\/');
// Get the default time zone
if (!isset($_POST['default_timezone']) or !is_numeric($_POST['default_timezone'])) {
    set_error(d('e3: ').'Please select a valid default timezone', 'default_timezone');
    $IsError=true;
} else {
    $default_timezone = $_POST['default_timezone'] * 60 * 60;
}
// End path and timezone details code

// Get the default language

$sLangDir = str_replace('\\', '/', dirname(dirname(__FILE__)) . '/languages/');
$allowed_languages = preg_replace('/^.*\/([A-Z]{2})\.php$/iU', '\1', glob($sLangDir . '??.php'));
if (!isset($_POST['default_language']) or !in_array($_POST['default_language'], $allowed_languages)) {
    set_error(d('e4: ').'Please select a valid default backend language', 'default_language');
    $IsError=true;
} else {
    $default_language = $_POST['default_language'];
    // make sure the selected language file exists in the language folder
    if (!file_exists('../languages/' . $default_language . '.php')) {
        set_error(d('e5: ').'The language file: \'' . $default_language . '.php\' is missing. Upload file to language folder or choose another language', 'default_language');
        $IsError=true;
    }
}
// End default language details code

// Begin operating system specific code
// Get operating system
if (!isset($_POST['operating_system']) or $_POST['operating_system'] != 'linux' and $_POST['operating_system'] != 'windows') {
    set_error(d('e6: ').'Please select a valid operating system');
    $IsError=true;
} else {
    $operating_system = $_POST['operating_system'];
}
// Work-out file permissions
if ($operating_system == 'windows') {
    $file_mode = '0666';
    $dir_mode = '0777';
} elseif (isset($_POST['world_writeable']) and $_POST['world_writeable'] == 'true') {
    $file_mode = '0666';
    $dir_mode = '0777';
} else {
    $file_mode = default_file_mode('../temp');
    $dir_mode = default_dir_mode('../temp');
}
// End operating system specific code


//echo "<pre>"; var_dump($_POST); echo "</pre>";

// Begin database details code
// Check if user has entered a database host
if (!isset($_POST['database_host']) or empty($_POST['database_host'])) {
    set_error(d('e7: ').'Please enter a database host name', 'database_host');
    $IsError=true;
} else {
    $database_host = $_POST['database_host'];
}

// Check if user has entered a database username
if (!isset($_POST['database_username']) or $_POST['database_username'] == '') {
    set_error(d('e8: ').'Please enter a database username', 'database_username');
    $IsError=true;
} else {
    $database_username = $_POST['database_username'];
}
// Check if user has entered a database password
if (!isset($_POST['database_password'])) {
    set_error(d('e9: ').'Please enter a database password', 'database_password');
    $IsError=true;
} else {
    $database_password = $_POST['database_password'];
}
// Check if user has entered a database name
if (!isset($_POST['database_name']) or $_POST['database_name'] == '') {
    set_error(d('e10: ').'Please enter a database name', 'database_name');
    $IsError=true;
} else {
    // make sure only allowed characters are specified
    if (preg_match('/[^a-z0-9_-]+/i', $_POST['database_name'])) {
        // contains invalid characters (only a-z, A-Z, 0-9 and _ allowed to avoid problems with table/field names)
        set_error(d('e11: ').'Only characters a-z, A-Z, 0-9, - and _ allowed in database name.', 'database_name');
        $IsError=true;
    }
    $database_name = $_POST['database_name'];
}
// Get table prefix
if (preg_match('/[^a-z0-9_]+/', $_POST['table_prefix'])) {
    // contains invalid characters (only a-z, A-Z, 0-9 and _ allowed to avoid problems with table/field names)
    set_error(d('e12: ').'Only characters a-z, 0-9 and _ allowed in table_prefix.', 'table_prefix');
    $IsError=true;
} else {
    $table_prefix = $_POST['table_prefix'];
}

$install_tables = true;
// Begin website title code
// Get website title
if (!isset($_POST['website_title']) or $_POST['website_title'] == '') {
    set_error(d('e12a: ').'Please enter a website title', 'website_title');
    $IsError=true;
} else {
    $website_title = add_slashes($_POST['website_title']);
}
// End website title code

// Begin admin user details code
// Get admin username
if (!isset($_POST['admin_username']) or $_POST['admin_username'] == '') {
    set_error(d('e13: ').'Please enter a username for the Administrator account', 'admin_username');
    $IsError=true;
} else {
    $admin_username = $_POST['admin_username'];
}
// Get admin email and validate it
if (!isset($_POST['admin_email']) or $_POST['admin_email'] == '') {
    set_error(d('e14: ').'Please enter an email for the Administrator account', 'admin_email');
    $IsError=true;
} else {
    if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i', $_POST['admin_email'])) {
        $admin_email = $_POST['admin_email'];
    } else {
        set_error(d('e15: ').'Please enter a valid email address for the Administrator account', 'admin_email');
        $IsError=true;
    }
}


// Get the two admin passwords entered, and check that they match
if (!isset($_POST['admin_password']) or $_POST['admin_password'] == '') {
    set_error(d('e16: ').'Please enter a password for the Administrator account', 'admin_password');
    $IsError=true;
} else {
    $admin_password = $_POST['admin_password'];
}

if (!isset($_POST['admin_repassword']) or $_POST['admin_repassword'] == '') {
    set_error(d('e17: ').'Please make sure you re-enter the password for the Administrator account', 'admin_repassword');
    $IsError=true;
} else {
    $admin_repassword = $_POST['admin_repassword'];
    if ($admin_password != $admin_repassword) {
        set_error(d('e18: ').'Sorry, the two Administrator account passwords you entered do not match', 'admin_repassword');
        $IsError=true;
    }
}


// If we got form errors
if ($IsError){
   // Redirect to first page again and exit
   // To see debug output , just uncomment the Location header
   //echo "<h4>Called Error</4>";
   header('Location: index.php?sessions_checked=true');
   exit;
}



// extract port if available
if (isset($database_port)) {unset($database_port);}
$aMatches = preg_split('/:/s', $database_host, -1, PREG_SPLIT_NO_EMPTY);
if (isset($aMatches[1])) {
    $database_host = $aMatches[0];
    $database_port = (int) $aMatches[1];
}

// PDO fix  http://php.net/manual/de/pdo.connections.php#82591
if ($database_host=="localhost")
    $database_host="127.0.0.1";

$sPdoPort="";
if (isset($database_port))
    $sPdoPort=";port=".(string)$database_port;

$database_charset = 'utf8';

//LEts See if we are able to connect to DB  No DB class needed for this on first
// I dont want any files Written before we know the content is worth it

try {
    if(extension_loaded ('PDO' ) AND extension_loaded('pdo_mysql')){
        $dbtest = new pdo(
            "mysql: host=$database_host $sPdoPort;dbname=$database_name",
            $database_username,
            $database_password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    } else {
        if (isset($database_port))
            $dbtest = new mysqli(
                $database_host,
                $database_username,
                $database_password,
                $database_name,
                $database_port
            );
        else
            $dbtest = new mysqli(
                $database_host,
                $database_username,
                $database_password,
                $database_name
            );
    }
}
catch (Exception $e) {
    $sMsg = d('e29: ').'Cannot connect to Database. Check host name, username, DB name and password.<br />MySQL Error:<br />'
        . $e->getMessage();
    // We end right here and dont collect any more errors
    set_error($sMsg,"",true);
}


// End admin user details code

// build name and content of the config file
$config_filename = dirname(dirname(__FILE__)) . '/config.php';
$config_content
= '<?php' . PHP_EOL
. PHP_EOL
. 'define(\'DB_TYPE\', \'mysql\');' . PHP_EOL
. 'define(\'DB_HOST\', \'' . $database_host . '\');' . PHP_EOL
. (isset($database_port) ? 'define(\'DB_PORT\', \'' . $database_port . '\');' . PHP_EOL : '')
. 'define(\'DB_NAME\', \'' . $database_name . '\');' . PHP_EOL
. 'define(\'DB_USERNAME\', \'' . $database_username . '\');' . PHP_EOL
. 'define(\'DB_PASSWORD\', \'' . $database_password . '\');' . PHP_EOL
. 'define(\'DB_CHARSET\', \'' . $database_charset . '\');' . PHP_EOL
. 'define(\'TABLE_PREFIX\', \'' . $table_prefix . '\');' . PHP_EOL
. PHP_EOL
. 'define(\'WB_URL\', \'' . $wb_url . '\');' . PHP_EOL
. 'define(\'ADMIN_DIRECTORY\', \'admin\');'
. '// no leading/trailing slash or backslash!! A simple directory name only!!' . PHP_EOL
;
// Check if the file exists and is writable first.
$sMsg = '';
if (is_writable($config_filename)) {
    // try to write file
    if (file_put_contents($config_filename, $config_content) === false) {
        $sMsg = d('e19: ').'Cannot write to the configuration file <' . $config_filename . '>';
    }
} else {
    $sMsg = d('e20: ').'The configuration file <' . $config_filename . '> is missing or not writable.<br />'
    . 'Change its permissions so it is, then re-run step 4.';
}
if ($sMsg) {set_error($sMsg,"",true);} // if something gone wrong, break with message

// include config file to set constants
include_once $config_filename;

// now we can complete the config file
$config_content
= PHP_EOL . 'require_once(dirname(__FILE__).\'/framework/initialize.php\');' . PHP_EOL
. '// end of file -------------' . PHP_EOL
. PHP_EOL;
// no errorhandling needed. 15 lines before we already wrote to this file successful!
file_put_contents($config_filename, $config_content, FILE_APPEND);

// Define additional configuration constants
define('WB_PATH', dirname(dirname(__FILE__)));
define('ADMIN_PATH', WB_PATH . '/' . ADMIN_DIRECTORY);
define('ADMIN_URL', WB_URL . '/' . ADMIN_DIRECTORY);
require ADMIN_PATH . '/interface/version.php';


// Try connecting to database This time whith our Classes
if (!file_exists(WB_PATH . '/framework/class.database.php')) {
    set_error(d('e21: ').'It appears the Absolute path that you entered is incorrect or file \'class.database.php\' is missing!',"",true);
}
include WB_PATH . '/framework/class.database.php';
try {
    if(extension_loaded ('PDO' ) AND extension_loaded('pdo_mysql')){
        $database = new \Persistence\Database();
    } else {
        $database = new database();
    }
} catch (Exception $e) {
    $sMsg = d('e22: ').'Database host name, username and/or password incorrect.'. d('<br />MySQL Error:<br />')
    . d($e->getMessage());
    set_error($sMsg,"",true);

}


// Cant remember what this was for , but it was important
if (!defined('WB_INSTALL_PROCESS')) {
    define('WB_INSTALL_PROCESS', true);
}





/*****************************
Begin Create Database Tables
 *****************************/
$aSqlFiles = array(
    'install_struct.sql',
    'install_data.sql'
);
foreach ($aSqlFiles as $sFileName){
    $sFile = dirname(__FILE__).'/'.$sFileName;
    $bPreserve = ($sFileName == "install_struct.sql") ? false : true;
    if (is_readable($sFile)) {
        if (!$database->SqlImport($sFile, TABLE_PREFIX, $bPreserve, $bPreserve)) {
            set_error(d('e23: ')."Unable to read import 'install/".$sFileName."'".d($database->get_error(),"",true));
        }
    } else {
        if(file_exists($sFile)){
            set_error(d('e24: ')."Unable to read file 'install/".$sFileName."'","",true);
        }else{
            set_error(d('e25: ')."File 'install/".$sFileName."' doesn't exist!","",true);
        }
    }
}


// add settings from install input
$aSettings = array(
    'wbce_version'     => WBCE_VERSION,
    'wbce_tag'         => WBCE_TAG,
    'wb_version'       => VERSION,   // Legacy: WB-Classic
    'wb_revision'      => REVISION,  // Legacy: WB-Classic
    'wb_sp'            => SP,        // Legacy: WB-Classic
    'website_title'    => $website_title,
    'default_language' => $default_language,
    'app_name'         => 'wb-'.$session_rand,
    'default_timezone' => $default_timezone,
    'operating_system' => $operating_system,
    'string_file_mode' => $file_mode,
    'string_dir_mode'  => $dir_mode,
    'server_email'     => $admin_email
);

require_once WB_PATH.'/framework/classes/class.settings.php';
foreach($aSettings as $name=>$value){
    Settings::Set($name, $value);
}

// add the Admin user Using md5 here should be not a a problem as password is rehashed on first login if possible
$aAdminUser = array(
    'user_id'      => 1,
    'group_id'     => 1,
    'groups_id'    => '1',
    'active'       => '1',
    'username'     => $admin_username,
    'language'     => $default_language,
    'password'     => md5($admin_password),
    'email'        => $admin_email,
    'timezone'     => $default_timezone,
    'display_name' => 'Administrator'
);
//print_r($aAdminUser);

if (!($database->insertRow('{TP}users', $aAdminUser))) {
    set_error(d('e26: ').'unable to write Administrator account into table \'users\'',"",true);
}

// Add  admin errors
if ($IsError){header('Location: index.php?sessions_checked=true'); exit;}


/**********************
END OF TABLES IMPORT
 **********************/

// initialize the system
include WB_PATH . '/framework/initialize.php';

require_once WB_PATH . '/framework/SecureForm.php';

/***********************
// Dummy class to allow modules' install scripts to call $admin->print_error
 ***********************/
class admin_dummy extends admin
{
    public $error = '';
    public function print_error($message, $link = 'index.php', $auto_footer = true)
    {
        $this->error = $message;
    }
}


$admin = new admin_dummy('Start', '', false, false);

// Load addons into DB
$dirs['modules'] = WB_PATH . '/modules/';
$dirs['templates'] = WB_PATH . '/templates/';
$dirs['languages'] = WB_PATH . '/languages/';

//this one needs to go first, so module filter can use the installer.

    load_module( $dirs['modules'] . 'outputfilter_dashboard',true);
    if ($admin->error != '') {
        set_error(d("e26a: /outputfilter_dashboard : ").$admin->error,"",true);
    }
    if ($database->is_error()) {
        set_error(d("e26b: /outputfilter_dashboard : ").$database->get_error(),"",true);
    }


// As some Module need to install Droplets we need Dropletss in next step
    load_module( $dirs['modules'] . 'droplets',true);
    if ($admin->error != '') {
        set_error(d("e26c: /droplets : ").$admin->error,"",true);
    }
    if ($database->is_error()) {
        set_error(d("e26d: /droplets : ").$database->get_error(),"",true);
    }

// Now we go and install all other modules
foreach ($dirs as $type => $dir) {

    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if (
                $file != '' and
                substr($file, 0, 1) != '.' and
                $file != 'admin.php' and
                $file != 'index.php' and
                $file != 'droplets' and
                $file != 'outputfilter_dashboard'
            ) {

                // Get addon type
                if ($type == 'modules') {
                    load_module($dir . '/' . $file, true);
                    // Pretty ugly hack to let modules run $admin->set_error
                    // See dummy class definition admin_dummy above

                    if ($admin->error != '') {
                        set_error(d("e27: /$file : ").$admin->error,"",true);
                    }
                    if ($database->is_error()) {
                        set_error(d("e27a: /$file : ").$database->get_error(),"",true);
                    }
                } elseif ($type == 'templates') {
                    load_template($dir . '/' . $file);
                } elseif ($type == 'languages') {
                    load_language($dir . '/' . $file);
                }
            }
        }
        closedir($handle);
    }

}


// Check if there was a database error
if ($database->is_error()) {
    set_error(d('e28: ').$database->get_error(),"",true);
}


$loc=ADMIN_URL . "/login/index.php";
header("Location: $loc");





