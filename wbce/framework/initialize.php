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

// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// Stop execution if PHP version is too old
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die ('PHP-' . PHP_VERSION . ' found, but at last PHP-5.4.0 required !!');
}

if (!defined('ADMIN_DIRECTORY')) {define('ADMIN_DIRECTORY', 'admin');}
if (!preg_match('/xx[a-z0-9_][a-z0-9_\-\.]+/i', 'xx' . ADMIN_DIRECTORY)) {
    die('Invalid admin-directory: ' . ADMIN_DIRECTORY);
}

if (!defined('ADMIN_URL')) {define('ADMIN_URL', WB_URL . '/' . ADMIN_DIRECTORY);}
if (!defined('WB_PATH')) {define('WB_PATH', dirname(dirname(__FILE__)));}
if (!defined('ADMIN_PATH')) {define('ADMIN_PATH', WB_PATH . '/' . ADMIN_DIRECTORY);}

$protocoll="http";
// $_SERVER['HTTPS'] is not reliable ... :-(
//https://github.com/dmikusa-pivotal/cf-php-apache-buildpack/issues/6
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
    $protocoll="https"; 
}
if (isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT'] == 443) {
    $protocoll="https";
}
if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] and $_SERVER['HTTPS']!="off"){ 
    $protocoll="https";    
}
define ("WB_PROTOCOLL", $protocoll);

// sanitize $_SERVER['HTTP_REFERER']
SanitizeHttpReferer();
date_default_timezone_set('UTC');

// register WB Autoloader 
require_once WB_PATH . "/framework/class.autoload.php"; 


// register TWIG autoloader ---
require WB_PATH . '/include/Sensio/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// register PHPMailer autoloader ---
require WB_PATH . '/include/phpmailer/PHPMailerAutoload.php';

// Create database class
$database = new database();


// get all settings as constants
Settings::Setup ();

// some resulting constants need to be set manually 
define('DO_NOT_TRACK', (isset($_SERVER['HTTP_DNT'])));
$string_file_mode = STRING_FILE_MODE;
define('OCTAL_FILE_MODE', (int) octdec($string_file_mode));
$string_dir_mode = STRING_DIR_MODE;
define('OCTAL_DIR_MODE', (int) octdec($string_dir_mode));

// GLOBAL WBCE ERROR REPORTING
if (intval(ER_LEVEL) > 0 or ER_LEVEL=="-1") {
    // Note: define('WB_DEBUG', true) in WBCE config.php forces max. PHP error output for debugging
    error_reporting(E_ALL);
} else {
    // set PHP error reporting level to user defined values (admin/interface/er_levels.php)
    switch (ER_LEVEL) {
        case 'E0':    // system default (php.ini)
            error_reporting(ini_get('error_reporting'));
            break;
        case 'E1':    // hide all errors and notices
            error_reporting(0);
            break;
        case 'E2':    // show all errors and notices
            error_reporting(E_ALL);
            break;
        case 'E3':    // show errors, no notices
            error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
            break;
        default:      // system default (php.ini)
            error_reporting(ini_get('error_reporting'));
    }
}
// adapt display_error directive in php.ini to reflect error_reporting level
if (error_reporting() == 0) {
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
}

// SESSION

// Initialize Custom Session Handler
// Stores Sessions to DB
// As session table possibly not installed , it may not run whith installer and upgradescript
// We then simply fallback to PHP default Session handling.
$hCustomSessionHandler= new DbSession();

// Init  special Session handling and Start session.
WSession::Start();

// Get users language
if (
    isset($_GET['lang']) and
    $_GET['lang'] != '' and
    !is_numeric($_GET['lang']) and
    strlen($_GET['lang']) == 2
) {
    define('LANGUAGE', strtoupper($_GET['lang']));
    $_SESSION['LANGUAGE'] = LANGUAGE;
} else {
    if (isset($_SESSION['LANGUAGE']) and $_SESSION['LANGUAGE'] != '') {
        define('LANGUAGE', $_SESSION['LANGUAGE']);
    } else {
        define('LANGUAGE', DEFAULT_LANGUAGE);
    }
}

// Load default language file so even incomplete languagefiles display at least the english text
if (!file_exists(WB_PATH . '/languages/EN.php')) {
    exit('Error loading default language file (EN), please check configuration and file');
} else {
    require_once WB_PATH . '/languages/EN.php';
}

// Load Language file
if (!file_exists(WB_PATH . '/languages/' . LANGUAGE . '.php')) {
    exit('Error loading language file ' . LANGUAGE . ', please check configuration and file');
} else {
    require_once WB_PATH . '/languages/' . LANGUAGE . '.php';
    define("LANGUAGE_LOADED", true);
}

//include old languages format  only for compatibility only needed for some old modules
if (file_exists(WB_PATH . '/languages/old.format.inc.php')) {
    include WB_PATH . '/languages/old.format.inc.php';
}

// Get users timezone
if (isset($_SESSION['TIMEZONE'])) {
    define('TIMEZONE', $_SESSION['TIMEZONE']);
} else {
    define('TIMEZONE', DEFAULT_TIMEZONE);
}

// Get users date format
if (isset($_SESSION['DATE_FORMAT'])) {
    define('DATE_FORMAT', $_SESSION['DATE_FORMAT']);
} else {
    define('DATE_FORMAT', DEFAULT_DATE_FORMAT);
}

// Get users time format
if (isset($_SESSION['TIME_FORMAT'])) {
    define('TIME_FORMAT', $_SESSION['TIME_FORMAT']);
} else {
    define('TIME_FORMAT', DEFAULT_TIME_FORMAT);
}

// Set Theme dir
define('THEME_URL', WB_URL . '/templates/' . DEFAULT_THEME);
define('THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);

// extended wb_settings this part really needs some loving as both aren't implemented fully functional
define('EDIT_ONE_SECTION', false);
define('EDITOR_WIDTH', 0);

require_once WB_PATH . "/framework/functions.php"; 

/////////////////////////////////////////////////////////////////
// Helper Functions
/////////////////////////////////////////////////////////////////
// needs some loving too !!!!

/**
 * sanitize $_SERVER['HTTP_REFERER']
 */
function SanitizeHttpReferer()
{
    $sTmpReferer = '';
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
        $aRefUrl = parse_url($_SERVER['HTTP_REFERER']);
        if ($aRefUrl !== false) {
            $aRefUrl['host'] = isset($aRefUrl['host']) ? $aRefUrl['host'] : '';
            $aRefUrl['path'] = isset($aRefUrl['path']) ? $aRefUrl['path'] : '';
            $aRefUrl['fragment'] = isset($aRefUrl['fragment']) ? '#' . $aRefUrl['fragment'] : '';
            $aWbUrl = parse_url(WB_URL);
            if ($aWbUrl !== false) {
                $aWbUrl['host'] = isset($aWbUrl['host']) ? $aWbUrl['host'] : '';
                $aWbUrl['path'] = isset($aWbUrl['path']) ? $aWbUrl['path'] : '';
                if (strpos($aRefUrl['host'] . $aRefUrl['path'], $aWbUrl['host'] . $aWbUrl['path']) !== false) {
                    $aRefUrl['path'] = preg_replace('#^' . $aWbUrl['path'] . '#i', '', $aRefUrl['path']);
                    $sTmpReferer = WB_URL . $aRefUrl['path'] . $aRefUrl['fragment'];
                }
                unset($aWbUrl);
            }
            unset($aRefUrl);
        }
    }
    $_SERVER['HTTP_REFERER'] = $sTmpReferer;
}

/**
 * makePhExp
 * @param array list of names for placeholders
 * @return array reformatted list
 * @description makes an RegEx-Expression for preg_replace() of each item in $aList
 *              Example: from 'TEST_NAME' it mades '/\[TEST_NAME\]/s'
 */
function makePhExp($sList)
{
    $aList = func_get_args();
    //return preg_replace('/^(.*)$/', '/\[$1\]/s', $aList);
    return preg_replace('/^(.*)$/', '[$1]', $aList);
}
