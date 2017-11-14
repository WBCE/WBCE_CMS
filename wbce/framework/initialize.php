<?php
/**
    @file /framework/initialize.php 
    @brief Contains the complete initialization of the WBCE enviroment.
    
    @author Ryan Djurovich (2004-2009)
    @author WebsiteBaker Org. e.V. (2009-2015) 
    @author Almost full rework by Norbert Heimsath (Heimsath.org)
    
    @copyright Released under GNU GPL2 (or any later version) 
    
    The main initialization file for WBCE . 
    Takes care of setting necessary variables and constants. 
    Initialize autoloader, sessions and settings. 
    
    Usualy this is included by loading the config.php in the main directory(webroot). 
    
    Maybe it would be a nice idea to enclose this in a class... 
    Or maybe attach to class WB 
 */

 
 
// no direct file access
if(count(get_included_files())==1) header("Location: ../index.php",TRUE,301);

// Stop execution if PHP version is too old
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die ('PHP-' . PHP_VERSION . ' found, but at last PHP-5.4.0 required !!');
}

// Starting Output buffering 
ob_start();

/**
    SOME EARLY CONSTANT HANDLING
    
    The absolute minimum needed for autoloader and DB 
*/

// WB_DEBUG can be overwritten via WBCE config.php (if enabled, max. PHP error output is shown)
if (! defined('WB_DEBUG')) { define('WB_DEBUG', false);}

// define WB_PATH as it isn't yet defined, instaaller issue whith missing wbpath 
defined("WB_PATH") OR define("WB_PATH", dirname(__DIR__));

// compatibility fix for old modules, sooner or later better replace the old constants from old mysql driver. 
// But for now 
if (!defined("MYSQL_BOTH")) define('MYSQL_BOTH',MYSQLI_BOTH);
if (!defined("MYSQL_NUM")) define('MYSQL_NUM',MYSQLI_NUM);
if (!defined("MYSQL_ASSOC")) define('MYSQL_ASSOC',MYSQLI_ASSOC);

// One of the first Steps in standarized constants
if (!defined("WB_TABLE_PREFIX")) define("WB_TABLE_PREFIX", TABLE_PREFIX);
if (!defined("TABLE_PREFIX")) define("TABLE_PREFIX", WB_TABLE_PREFIX);




/**
    INITIALIZE AUTOLOADER
*/
require_once dirname(__FILE__)."/class.autoload.php";



/**
    PREDB MODULES LOADED HERE
    
    load all predb.php files form module folders that start whith predb_
    These are especially for registering classes that override classes from the main framework
    For example a DB class that uses PDO instead of MYSQLI. 
    This one Loads modules even if they are not installed in the DB.
*/
// PRE DB
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



/**
    INITIALIZE DATABASE CLASS
    
    You can override this by using a predb module that registers another file as DB class. 
    @code 
        WbAuto::AddFile("database","/modules/predb_pdo_db/pdo_database.class.php");
    @endcode
*/
$database = new database();



/**
    PRE_INIT MODULE
    
    Pre init, modules may change everyting as almost nothing is already set here
    Module may hook here to change Page_id Language or whatever. Even most System Constants.
    As DB is available here we reliy on installed modules. 
    @todo check if we need to make more modifications to the core to get this fully running 
    I am not sure ir preinit already allowed in 1.3 core 
    
    @todo check if we better use  MYSQL FIND_IN_SET (http://forum.wbce.org/viewtopic.php?id=84)
*/
$sql = "
    SELECT 
        `directory` 
    FROM 
        `{TP}addons` 
    WHERE 
        function LIKE '%preinit%' 
";
// Query gives back false on failure
if (($resSnippets = $database->query($sql))) {
    while ($recSnippet = $resSnippets->fetchRow()) {
        $module_dir = $recSnippet['directory'];
        //echo  dirname(dirname(__FILE__)). '/modules/' . $module_dir . '/pre_init.php';
        if (file_exists(dirname(dirname(__FILE__)). '/modules/' . $module_dir . '/preinit.php')) {
            include dirname(dirname(__FILE__)). '/modules/' . $module_dir . '/preinit.php';

        }
    }
}



/**
    SYSTEM CONSTANTS
    
    Now we start definig System constants if not already set
    Lots of compatibility work here, please only use the WB_ constants in future stuff
*/
// WB_ADMIN_DIRECTORY (ADMIN_DIRECTORY)
if (!defined('ADMIN_DIRECTORY') and !defined('WB_ADMIN_DIRECTORY')) {
    define('ADMIN_DIRECTORY', 'admin');
    define('WB_ADMIN_DIRECTORY', 'admin');
}
if (!defined('ADMIN_DIRECTORY') and defined('WB_ADMIN_DIRECTORY')) {
    define('ADMIN_DIRECTORY', WB_ADMIN_DIRECTORY);
}
if (defined('ADMIN_DIRECTORY') and !defined('WB_ADMIN_DIRECTORY')) {
    define('WB_ADMIN_DIRECTORY', ADMIN_DIRECTORY);
}
// check if someone added crap in the config
if (!preg_match('/xx[a-z0-9_][a-z0-9_\-\.]+/i', 'xx' . WB_ADMIN_DIRECTORY)) {
    die('Invalid admin-directory: ' . WB_ADMIN_DIRECTORY);
}

// WB_ADMIN_URL (ADMIN_URL)
if (!defined('ADMIN_URL'))     {define('ADMIN_URL', WB_URL . '/' . WB_ADMIN_DIRECTORY);}
if (!defined('WB_ADMIN_URL'))  {define('WB_ADMIN_URL', WB_URL . '/' . WB_ADMIN_DIRECTORY);}

// WB_PATH
if (!defined('WB_PATH'))       {define('WB_PATH', dirname(dirname(__FILE__)));}

// WB_ADMIN_PATH (ADMIN_PATH)
if (!defined('ADMIN_PATH'))    {define('ADMIN_PATH', WB_PATH . '/' . ADMIN_DIRECTORY);}
if (!defined('WB_ADMIN_PATH')) {define('WB_ADMIN_PATH', WB_PATH . '/' . ADMIN_DIRECTORY);}

// WB_PROTOCOLL (This is a new Constant so no old variant)
$protocoll="http";
// $_SERVER['HTTPS'] alone is not reliable ... :-(
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


// SELECTED AND CHECKED
// are needed in so many forms that i decided to lazy define them here
if (!defined('WB_SELECT')) {define('WB_SELECT',' selected="selected" ');}
if (!defined('WB_CHECK'))  {define('WB_CHECK',' checked="checked" ');}



/**
    MORE AUTOLOADER REGISTRATION
    
    Registering additional classes that are needed by the core 
*/
// Registering class for idna conversion (needed for mailchecks)
WbAuto::AddFile("idna_convert","/include/idna_convert/idna_convert.class.php");

// this silly thing has a filename that does not match autoloader finding patterns
WbAuto::AddFile("SecureForm","/framework/SecureForm.php");

// register TWIG autoloader ---
require WB_PATH . '/include/Sensio/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// register PHPMailer autoloader ---
require WB_PATH . '/include/phpmailer/PHPMailerAutoload.php';

// Create database class
$database = new database();



/**
    SETUP SYSTEM CONSTANTS (GLOBAL SETTINGS)
    
    We use Settings Class to fetch all Settings from DB 
    Then we process all data into the dependent connstants. 
*/
// Fetch alll settings whith Settings class from framework 
Settings::Setup ();

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
if (!defined ("WB_MEDIA_URL")) define ("WB_MEDIA_URL",  WB_URL.MEDIA_DIRECTORY);




/**
    SET WBCE ERROR REPORTING

    @todo remove the historical stuff
*/
if (WB_DEBUG === true)         { // Debugging activated
    error_reporting(E_ALL);
}
elseif (intval(ER_LEVEL) > 0)  { //Historical compatibility stuff
    error_reporting(E_ALL);
}
elseif (ER_LEVEL=="-1")        { //Historical compatibility stuff
    error_reporting(E_ALL);
}
elseif (ER_LEVEL=="E0")        { // system default (php.ini)
    error_reporting(ini_get('error_reporting'));
}
elseif (ER_LEVEL=="E1")        { // hide all errors and notices
    error_reporting(0);
}
elseif (ER_LEVEL=="E2")        { // show all errors and notices
    error_reporting(E_ALL);
}
elseif (ER_LEVEL=="E3")        { // show errors, no notices
    error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
}
else                           { // system default (php.ini)
    error_reporting(ini_get('error_reporting'));
}

// adapt display_error directive in php.ini to reflect error_reporting level
if (error_reporting() == 0) {
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
}




/**
    DEFAULT TIMEZONE

    @todo this needs to be replaced by a real locale handling same for the timeformatstuff somewhat below. 
*/
date_default_timezone_set('UTC');


/** SESSION

    Initialize Custom Session Handler
    Stores Sessions to DB
    As session table possibly not installed, it may not run whith installer and upgradescript
    We then simply fallback to PHP default Session handling.
*/
// Init custom session handler 
$hCustomSessionHandler= new DbSession();

// Init  special Session handling and Start session.
WSession::Start();


/** 
    MODULES INITIALIZE.PHP
    
    For now we put modules initialize.php here
    Yess all modules are now allowed to have a initialize.php. function='initialize'
    From now on Twig may be a module :-)
    you can even change the $Page_id, or maybe the Language .
    You can log users in or out and do what you like
    Initialize Modules normaly do not Decide between FE and BE 
*/
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

/** 
    SANITIZE REFERER
    
    sanitize $_SERVER['HTTP_REFERER']

    @todo Needs to be repaced ASAP as this bullshit makes me sick. But its only way to have a halfway save 
    refrerer string.  
*/
SanitizeHttpReferer();


/**
    LANGUAGES
*/
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



/**
    TIMEZONE AND "LOCALE" SETTINGS
    @todo replace this whith a real mechanism 
*/

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



/**
    MORE CONSTANTS
    @todo some of the constants need to be looked after   
*/
// WB_THEME_URL (THEME_URL)
if (!defined("THEME_URL")) define('THEME_URL', WB_URL . '/templates/' . DEFAULT_THEME);
if (!defined("WB_THEME_URL")) define('WB_THEME_URL', WB_URL . '/templates/' . DEFAULT_THEME);

// WB_THEME_PATH (THEME_PATH)
if (!defined("THEME_PATH")) define('THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);
if (!defined("WB_THEME_PATH")) define('WB_THEME_PATH', WB_PATH . '/templates/' . DEFAULT_THEME);

// extended wb_settings this part really needs some loving as both aren't
// implemented fully functional so this is still work on progress.
if (!defined("EDIT_ONE_SECTION")) define('EDIT_ONE_SECTION', false);
if (!defined("EDITOR_WIDTH")) define('EDITOR_WIDTH', 0);



/**
    FETCH WBCE VERSION
    Version should be available not only in admin section(BE)
*/
require_once ADMIN_PATH . '/interface/version.php';



/** 
    FUNCTIONS.PHP
    finally load framework Funktions so we dont need to include this in almost every file  
*/
require_once(WB_PATH.'/framework/functions.php');



// ///////////////////////////////////////////////////////////////
// HELPER FUNCTIONS
// Moved down here as they need to be removed/reworked sooner or later
// ///////////////////////////////////////////////////////////////


/**
    @brief sanitize $_SERVER['HTTP_REFERER']
    @todo Change WBCE so it uses the save referrer and no longer touches the basic referrer 
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


/**
    @brief makePhExp
    @param array list of names for placeholders
    @return array reformatted list
    
    makes an RegEx-Expression for preg_replace() of each item in $aList
    Example: from 'TEST_NAME' it mades '/\[TEST_NAME\]/s'
    
    @todo Check if this is reallly needed 
 */
function makePhExp($sList)
{
    $aList = func_get_args();
    //return preg_replace('/^(.*)$/', '/\[$1\]/s', $aList);
    return preg_replace('/^(.*)$/', '[$1]', $aList);
}

