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
// DEFAULT SYSTEM SETTINGS
// remove all unwanted php fancy stuff
// Set debug, but allow override in config.
if (!defined("WB_DEBUG"))
    define("WB_DEBUG", false);

// no direct file access
if (count(get_included_files()) == 1)
    die(header("Location: ../index.php", TRUE, 301));

// Stop execution if PHP version is too old
if (version_compare(PHP_VERSION, '5.3.6', '<') AND !defined("WB_IGNORE_PHP_VERSION")) {
    die ('PHP-' . PHP_VERSION . ' found, but at last PHP-5.3.6 required !!');
}

// disable MAgic quotes if php version is below 5.4.0.
// Since  5.4.0 magic quotes is removed entirely
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    ini_set("magic_quotes_runtime", 0); // Disable magic_quotes_runtime
}

/*
// PRE DB MODULES
// load all predb.php files form folders that start whith predb_
$aPreDb=array();
$p= dirname(dirname(__FILE__))."/modules/predb_*";
$aPreDb=glob($p);
//print_r($aPreDb);
if ($aPreDb!==false AND !empty($aPreDb)){
    foreach ($aPreDb as $m){
        $f=$m."/predb.php";
        if (file_exists($f)) {
            require_once ($f);
       
        }
    }
}
*/

// DATABASE
// Load database class
require_once (dirname(__FILE__) . '/classes/Persistence/Database.php');
require_once (dirname(__FILE__) . '/classes/Persistence/Result.php');
require_once (dirname(__FILE__) . '/classes/Persistence/DatabaseException.php');
require_once (dirname(__FILE__) . '/classes/Persistence/WDO.php');

// Create database class
$database = new \Persistence\Database();


// PRE INIT
//// BESSER MYSQL FIND_IN_SET()?
//http://forum.wbce.org/viewtopic.php?id=84
// Pre init, modules may change everyting as almost nothing is already set here
// Module may hook here to change Page_id Language or whatever. Even System Constants.
$sql = 'SELECT `directory` FROM `' . TABLE_PREFIX . 'addons` ';
$sql .= 'WHERE function LIKE \'%preinit%\' ';
if (($resSnippets = $database->query($sql))) {
    while ($recSnippet = $resSnippets->fetchRow()) {
        $module_dir = $recSnippet['directory'];
        //echo  dirname(dirname(__FILE__)). '/modules/' . $module_dir . '/pre_init.php';
        if (file_exists(dirname(dirname(__FILE__)) . '/modules/' . $module_dir . '/preinit.php')) {
            include dirname(dirname(__FILE__)) . '/modules/' . $module_dir . '/preinit.php';
        }
    }
}



// SYSTEM CONSTANTS
// Now we start definig System constants if not already set
// Lots of compatibility work here, please only use the WB_ constants in future stuff
// WB_ADMIN_DIRECTORY (ADMIN_DIRECTORY)
if (!defined('ADMIN_DIRECTORY') and ! defined('WB_ADMIN_DIRECTORY')) {
    define('ADMIN_DIRECTORY', 'admin');
    define('WB_ADMIN_DIRECTORY', 'admin');
}
if (!defined('ADMIN_DIRECTORY') and defined('WB_ADMIN_DIRECTORY')) {
    define('ADMIN_DIRECTORY', WB_ADMIN_DIRECTORY);
}
if (defined('ADMIN_DIRECTORY') and ! defined('WB_ADMIN_DIRECTORY')) {
    define('WB_ADMIN_DIRECTORY', ADMIN_DIRECTORY);
}
// check if someone added crap in the config
if (!preg_match('/xx[a-z0-9_][a-z0-9_\-\.]+/i', 'xx' . WB_ADMIN_DIRECTORY)) {
    die('Invalid admin-directory: ' . WB_ADMIN_DIRECTORY);
}

// WB_ADMIN_URL (ADMIN_URL)
if (!defined('ADMIN_URL')) {
    define('ADMIN_URL', WB_URL . '/' . WB_ADMIN_DIRECTORY);
}
if (!defined('WB_ADMIN_URL')) {
    define('WB_ADMIN_URL', WB_URL . '/' . WB_ADMIN_DIRECTORY);
}

// WB_PATH
if (!defined('WB_PATH')) {
    define('WB_PATH', dirname(dirname(__FILE__)));
}

// WB_ADMIN_PATH (ADMIN_PATH)
if (!defined('ADMIN_PATH')) {
    define('ADMIN_PATH', WB_PATH . '/' . ADMIN_DIRECTORY);
}
if (!defined('WB_ADMIN_PATH')) {
    define('WB_ADMIN_PATH', WB_PATH . '/' . ADMIN_DIRECTORY);
}

// WB_PROTOCOLL (This is a new Constant so no old variant)
$protocoll = "http";
// $_SERVER['HTTPS'] alone is not reliable ... :-(
//https://github.com/dmikusa-pivotal/cf-php-apache-buildpack/issues/6
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $protocoll = "https";
}
if (isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT'] == 443) {
    $protocoll = "https";
}
if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] and $_SERVER['HTTPS'] != "off") {
    $protocoll = "https";
}
define("WB_PROTOCOLL", $protocoll);


// SELECTED AND CHECKED
// are needed in so many forms that i decided to lazy define them here
if (!defined('WB_SELECT')) {
    define('WB_SELECT', ' selected="selected" ');
}
if (!defined('WB_CHECK')) {
    define('WB_CHECK', ' checked="checked" ');
}


// AUTOLOADERS
// register WB Autoloader
require WB_PATH . "/framework/class.autoload.php";
WbAuto::AddDir("/framework/");
WbAuto::AddFile("idna_convert", "/include/idna_convert/idna_convert.class.php");
WbAuto::AddFile("SecureForm", "/framework/SecureForm.php");
WbAuto::AddFile("PclZip", "/include/pclzip/pclzip.lib.php");

// register PHPMailer autoloader ---
require WB_PATH . '/include/phpmailer/PHPMailerAutoload.php';



// GLOBAL SETTINGS
// Get all global settings as constants from DB Settings
Settings::Setup();



// RESULTING CONSTANTS
// some resulting constants need to be set manually
// DO_NOT_TRACK (deprecated, not used and we remove this soon)
define('DO_NOT_TRACK', (isset($_SERVER['HTTP_DNT'])));

// Filemodes
$string_file_mode = STRING_FILE_MODE;
define('OCTAL_FILE_MODE', (int) octdec($string_file_mode));
define('WB_OCTAL_FILE_MODE', (int) octdec($string_file_mode));

//Dirmodes
$string_dir_mode = STRING_DIR_MODE;
define('OCTAL_DIR_MODE', (int) octdec($string_dir_mode));
define('WB_OCTAL_DIR_MODE', (int) octdec($string_dir_mode));

// WB_MEDIA_URL (there is no old couterpart)
if (!defined("WB_MEDIA_URL"))
    define("WB_MEDIA_URL", WB_URL . MEDIA_DIRECTORY);



// ERROR REPORTING
// set error-reporting
if (intval(ER_LEVEL) > 0 or ER_LEVEL == "-1") {
    error_reporting(ER_LEVEL);
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}
// If we are in Debug mode we use Max settings
if (WB_DEBUG === true) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}



//DEFAULT TIMEZONE
date_default_timezone_set('UTC');


// SANITIZE REFERER
// sanitize $_SERVER['HTTP_REFERER']
// NeeDS TO BE REMOVES ASAP
SanitizeHttpReferer();



// SESSION
// WB_SECFORM_TIMEOUT we use this for now later we get seperate settings
// Later we should get a nice session class instead of this improvised stuff.
ini_set('session.gc_maxlifetime', intval(WB_SECFORM_TIMEOUT));
//ini_set('session.cookie_lifetime', intval(WB_SECFORM_TIMEOUT));
ini_set('session.cookie_httponly', 1);
if (WB_PROTOCOLL == "https") {
    ini_set('session.cookie_secure', 1);
}
session_name(APP_NAME . '-sid');
session_set_cookie_params(0);
//session_set_cookie_params(WB_SECFORM_TIMEOUT);
// Start a session
if (!defined('SESSION_STARTED')) {
    session_start();

    // this is used by only by installer in index.php and save.php we will remove this later
    define('SESSION_STARTED', true);

    // New way for check if session exists
    $_SESSION['WB']['SessionStarted'] = true;
}

// make sure session never exeeds lifetime
/**
  //That will set the session cookie with a fresh ttl.
  setcookie( ini_get("session.name"), session_id(),
  time()+ini_get("session.cookie_lifetime"),
  ini_get("session.cookie_path"),
  ini_get("session.cookie_domain"),
  ini_get("session.cookie_secure"),
  ini_get("session.cookie_httponly"));
 */
$now = time();
//echo "Now: $now <br>";
//echo "discard_after:".$_SESSION['WB']['discard_after']."<br>";
//echo "Secform timeout:".WB_SECFORM_TIMEOUT."<br>";
if (isset($_SESSION['WB']['discard_after']) && $now > $_SESSION['WB']['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    session_unset();
    session_destroy();
    session_start();
    echo "Run out , killing session";
}
$_SESSION['WB']['discard_after'] = $now + WB_SECFORM_TIMEOUT;
//echo "discard_after2:".$_SESSION['WB']['discard_after']."<br>";


if (defined('ENABLED_ASP') && ENABLED_ASP && !isset($_SESSION['session_started'])) {
    $_SESSION['session_started'] = time();
}



// MODULES INITIALIZE.PHP
// For now we put modules initialize.php here
// Yess all modules are now allowed to have a initialize.php. `function`=\'snippet\'
// From now on Twig may be a module :-)
// you can even change the $Page_id, or maybe the Language .
// You can log users in or out and do what you like
$sql = 'SELECT `directory` FROM `' . TABLE_PREFIX . 'addons` ';
$sql .= 'WHERE  function LIKE \'%initialize%\' ';
if (($resSnippets = $database->query($sql))) {
    while ($recSnippet = $resSnippets->fetchRow()) {
        $module_dir = $recSnippet['directory'];
        if (file_exists(WB_PATH . '/modules/' . $module_dir . '/initialize.php')) {
            include WB_PATH . '/modules/' . $module_dir . '/initialize.php';
        }
    }
}



// LANGUAGES
// Only if no module already did this
if (!defined("LANGUAGE")) {
    // Get users language
    if (
        isset($_GET['lang']) and
        $_GET['lang'] != '' and ! is_numeric($_GET['lang']) and
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




// TIMEZONE, DATE, USERTIME
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


// MORE SYSTEM CONSTANTS
// WB_THEME_URL (THEME_URL)
if (!defined("THEME_URL"))
    define('THEME_URL', WB_URL . '/templates/' . DEFAULT_THEME);
if (!defined("WB_THEME_URL"))
    define('WB_THEME_URL', WB_URL . '/templates/' . DEFAULT_THEME);

// WB_THEME_PATH (THEME_PATH)
if (!defined("THEME_PATH"))
    define('THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);
if (!defined("WB_THEME_PATH"))
    define('WB_THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);

// extended wb_settings this part really needs some loving as both aren't
// implemented fully functional so this is still work on progress.
define('EDIT_ONE_SECTION', false); // allow to edit just one section whithout all other
define('EDITOR_WIDTH', 0);         // define a basic editor width
// Version should be avaaiable not only in admin secction(BE)
// Get WB version
require_once ADMIN_PATH . '/interface/version.php';


// FUNCTIONS.PHP
// finally load framework Funktions so we dont need to include this in almost every file
require_once(WB_PATH . '/framework/functions.php');

/////////////////////////////////////////////////////////////////
// Helper Functions
/////////////////////////////////////////////////////////////////
// Moved down here as they need to be removed/reworked sooner or later
// Maybe change this so it produces a $_SERVER['HTTP_REFERER_SAVE'] or $_SERVER['HTTP_REFERER_LOCAL']
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

// This one is very questionable too
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
