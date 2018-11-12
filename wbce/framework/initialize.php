<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright  Ryan Djurovich (2004-2009)
 * @copyright  WebsiteBaker Org. e.V. (2009-2015)
 * @copyright  WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)

 * The main initialization file for WBCE CMS.
 * Takes care of the set up (initialization) of variables and constants,
 * the autoloader class, sessions and settings. 
 * 
 * Usualy this is included by loading the config.php 
 * in the main directory(webroot). 
 * 
 */
 
// no direct file access
if(count(get_included_files()) == 1) header("Location: ../index.php", TRUE, 301);

// Stop execution if PHP version is too old
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die ('PHP ' . PHP_VERSION . ' found, but at least PHP 5.4.0 required !!');
}

// Starting Output buffering 
ob_start();

// SOME EARLY CONSTANT HANDLING
// The absolute minimum needed for autoloader and DB 

// WB_DEBUG can be overwritten via WBCE config.php (if enabled, max. PHP error output is shown)
defined('WB_DEBUG')    or define('WB_DEBUG', false);
// define WB_PATH as it isn't yet defined, installer issue whith missing wbpath 
defined("WB_PATH")     or define("WB_PATH", dirname(__DIR__));
// compatibility fix for old modules, sooner or later better replace the old constants from old mysql driver.
// But for now 
defined("MYSQL_BOTH")  or define('MYSQL_BOTH',  MYSQLI_BOTH);
defined("MYSQL_NUM")   or define('MYSQL_NUM',   MYSQLI_NUM);
defined("MYSQL_ASSOC") or define('MYSQL_ASSOC', MYSQLI_ASSOC);

// INITIALIZE AUTOLOADER
require_once dirname(__FILE__)."/class.autoload.php";

// PREDB MODULES LOADED HERE
// 
// load all predb.php files form module folders that start whith predb_
// These are especially for registering classes that override classes from the main framework
// For example a DB class that uses PDO instead of MYSQLI. 
// This one Loads modules even if they are not installed in the DB.
// 
// load all predb.php files form folders that start whith predb_
$aPreDb = array();
$aPreDb = glob(dirname(__DIR__)."/modules/predb_*");
if ($aPreDb !== false && !empty($aPreDb)){
    foreach ($aPreDb as $sModule)
        if (file_exists($sModule."/predb.php")) require_once ($sModule."/predb.php");
}

// INITIALIZE DATABASE CLASS
//
// You can override this by using a predb module that registers another file as DB class. 
// WbAuto::AddFile("database","/modules/predb_pdo_db/pdo_database.class.php");

$database = new database();


// SYSTEM CONSTANTS
//     
// Now we start definig System constants if not already set
// Lots of compatibility work here, please only use the WB_ constants in future stuff

// first check if someone added crap in the config
if (!preg_match('/xx[a-z0-9_][a-z0-9_\-\.]+/i', 'xx' . ADMIN_DIRECTORY)) {
    die('Invalid admin-directory: ' . ADMIN_DIRECTORY);
}

defined('ADMIN_DIRECTORY')  or define('ADMIN_DIRECTORY', 'admin');
defined('ADMIN_URL')        or define('ADMIN_URL',       WB_URL .'/'. ADMIN_DIRECTORY);
defined('ADMIN_PATH')       or define('ADMIN_PATH',      WB_PATH .'/'. ADMIN_DIRECTORY);

// Load framework functions before preinit files so we can use functions right away.  
require_once(WB_PATH.'/framework/functions.php');


// PRE_INIT MODULES
//    
// Pre init, modules may change everyting as almost nothing is already set here
// Module may hook here to change Page_id Language or whatever. Even most System Constants.
// As DB is available here we reliy on installed modules. 
// @todo check if we need to make more modifications to the core to get this fully running 
// I am not sure ir preinit already allowed in 1.3 core 
//
// @todo check if we better use  MYSQL FIND_IN_SET (http://forum.wbce.org/viewtopic.php?id=84)

// Query gives back false on failure
if (($rSnippets = $database->query("SELECT `directory` FROM `{TP}addons` WHERE function LIKE '%preinit%'"))) {
    while ($rec = $rSnippets->fetchRow()) {
        $sModFilePath = dirname(__DIR__). '/modules/' . $rec['directory'] . '/preinit.php';
        if (file_exists($sModFilePath)) include $sModFilePath;
    }
}

// define DOMAIN_PROTOCOLL constant
$protocoll = "http";
// $_SERVER['HTTPS'] alone is not reliable ... :-(
// https://github.com/dmikusa-pivotal/cf-php-apache-buildpack/issues/6
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
    $protocoll = "https";
}
if (isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT'] == 443) {
    $protocoll = "https";
}
if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] and $_SERVER['HTTPS']!="off"){
    $protocoll = "https";
}
define("DOMAIN_PROTOCOLL", $protocoll);

// MORE AUTOLOADER REGISTRATION
// Registering additional classes that are needed by the core 

// Registering class for idna conversion (needed for email-checks)
WbAuto::AddFile("idna_convert", "/include/idna_convert/idna_convert.class.php");
WbAuto::AddFile("SecureForm", "/framework/SecureForm.php");

// Auto Load the Insert and I Classes
WbAuto::AddFile("Insert", "/framework/Insert.php");
WbAuto::AddFile("I",      "/framework/I.php");

// register TWIG autoloader ---
require WB_PATH . '/include/Sensio/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// register PHPMailer autoloader ---
require WB_PATH . '/include/phpmailer/PHPMailerAutoload.php';

// Create database class
// $database = new database(); // why was the $database instance initiated twice?
                               // let's run it a while and remove if it doesn't break 
                               // the performance

// SETUP SYSTEM CONSTANTS (GLOBAL SETTINGS)
// We use Settings Class to fetch all Settings from DB 
// Then we process all data into the coresponding constants. 

Settings::Setup(); // Fetch all settings whith Settings class from framework 

// RESULTING CONSTANTS
// some resulting constants need to be set manually

// Filemodes
$string_file_mode = STRING_FILE_MODE;
define('OCTAL_FILE_MODE',    (int) octdec($string_file_mode));
define('WB_OCTAL_FILE_MODE', (int) octdec($string_file_mode));

// Dirmodes
$string_dir_mode = STRING_DIR_MODE;
define('OCTAL_DIR_MODE',    (int) octdec($string_dir_mode));
define('WB_OCTAL_DIR_MODE', (int) octdec($string_dir_mode));

switch (true){
    case (WB_DEBUG === true):    
        // Debugging activated
        error_reporting(E_ALL); break; 
    case (intval(ER_LEVEL) > 0): 
        //Historical compatibility stuff
        error_reporting(E_ALL); break;
    case (ER_LEVEL=="-1"):       
        //Historical compatibility stuff
        error_reporting(E_ALL); break;
    case (ER_LEVEL=="E0"):       
        // system default (php.ini)
        error_reporting(ini_get('error_reporting')); break; 
    case (ER_LEVEL=="E1"):       
        // hide all errors and notices
        error_reporting(0); break;
    case (ER_LEVEL=="E2"):       
        // show all errors and notices
        error_reporting(E_ALL); break;
    case (ER_LEVEL=="E3"):       
        // show errors, no notices
        error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE); break;
    default: 
        // system default (php.ini)
        error_reporting(ini_get('error_reporting'));
}
// adapt display_error directive in php.ini to reflect error_reporting level
ini_set('display_errors', (error_reporting() == 0) ? 0 : 1);

// DEFAULT TIMEZONE
// @todo this needs to be replaced by a real locale handling 
// same for the timeformatstuff somewhat below. 
date_default_timezone_set('UTC');


//SESSION
//Initialize Custom Session Handler
//Stores Sessions to DB
//As session table possibly not installed, it may not run whith installer and upgradescript
//We then simply fallback to PHP default Session handling.

// Init custom session handler 
$hCustomSessionHandler = new DbSession();

// Init  special Session handling and Start session.
WSession::Start();


// MODULES INITIALIZE.PHP
// For now we put modules initialize.php here
// Yes! All modules are now allowed to have a initialize.php. function='initialize'
// From now on Twig may be a module :-)
// You can even change the $page_id, or maybe the Language .
// You can log users in or out and do what you like
// Initialize Modules normaly do not distinguish between FE and BE 
        
$sSql = "SELECT `directory` FROM `{TP}addons` WHERE  function LIKE '%initialize%'";
if (($resSnippets = $database->query($sSql))) {
    while ($rec = $resSnippets->fetchRow()) {
        $sFile = WB_PATH . '/modules/' . $rec['directory'] . '/initialize.php';
        if (file_exists($sFile)) include $sFile;
    }
}

// SANITIZE REFERER 
// sanitize $_SERVER['HTTP_REFERER']
// @todo Needs to be repaced ASAP. Currently it's the 
// only way to have a halfway save refrerer string. 
SanitizeHttpReferer();


// LANGUAGES
// Only if no module already did this
if (!defined("LANGUAGE")) {
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
}

// Load default language file so even incomplete languagefiles display at least the english text
if (!file_exists(WB_PATH . '/languages/EN.php')) {
    exit('Error loading default language file (EN), please check configuration and file');
} else {
	// we always load EN language file
    require_once WB_PATH . '/languages/EN.php';
}

// Load LC language file if LANGUAGE != EN
if(LANGUAGE != 'EN'){
	$sLangFile = WB_PATH . '/languages/' . LANGUAGE . '.php';
	if (file_exists($sLangFile)) require_once $sLangFile;
}
// include old languages format  only for compatibility only needed for some old modules
if (file_exists(WB_PATH . '/languages/old.format.inc.php')) {
    include WB_PATH . '/languages/old.format.inc.php';
}
define("LANGUAGE_LOADED", true);

// define more system constants
defined("THEME_URL")        or define('THEME_URL',        WB_URL .'/templates/'. DEFAULT_THEME);
defined("THEME_PATH")       or define('THEME_PATH',       WB_PATH .'/templates/'. DEFAULT_THEME);
defined("EDIT_ONE_SECTION") or define('EDIT_ONE_SECTION', false);
defined("EDITOR_WIDTH")     or define('EDITOR_WIDTH', 0);

// TIMEZONE and DATE/TIME FORMAT constants
define('TIMEZONE',    isset($_SESSION['TIMEZONE'])    ? $_SESSION['TIMEZONE']    : DEFAULT_TIMEZONE);
define('DATE_FORMAT', isset($_SESSION['DATE_FORMAT']) ? $_SESSION['DATE_FORMAT'] : DEFAULT_DATE_FORMAT);
define('TIME_FORMAT', isset($_SESSION['TIME_FORMAT']) ? $_SESSION['TIME_FORMAT'] : DEFAULT_TIME_FORMAT);

// FETCH WBCE VERSION 
// Version should be available not only in admin section(BE)
require_once ADMIN_PATH . '/interface/version.php';

// ////////////////////////////////////////////////////////////////////////////////
//  HELPER FUNCTIONS
//  Moved down here as they need to be removed/reworked sooner or later
// ////////////////////////////////////////////////////////////////////////////////

/**
 * @brief  sanitize $_SERVER['HTTP_REFERER']
 * @todo   Change WBCE so it uses the save referrer and no longer touches the basic referrer 
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
    $_SERVER['WB_SECURE_HTTP_REFERER'] = $sTmpReferer;
}