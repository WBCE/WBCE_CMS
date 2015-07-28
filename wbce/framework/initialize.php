<?php
/**
 *
 * @category        framework
 * @package         initialize
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: initialize.php 1638 2012-03-13 23:01:47Z darkviper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/framework/initialize.php $
 * @lastmodified    $Date: 2012-03-14 00:01:47 +0100 (Mi, 14. Mrz 2012) $
 *
 */

// Stop execution if PHP version is too old
if (version_compare(PHP_VERSION, '5.3.6', '<')) { 
    throw new Exception('PHP-'.PHP_VERSION.' found, but at last PHP-5.3.6 required !!'); 
}
/* -------------------------------------------------------- */

/**
 * sanitize $_SERVER['HTTP_REFERER']
 * @param string $sWbUrl qualified startup URL of current application
 */
function SanitizeHttpReferer($sWbUrl = WB_URL) {
    $sTmpReferer = '';
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
        $aRefUrl = parse_url($_SERVER['HTTP_REFERER']);
        if ($aRefUrl !== false) {
            $aRefUrl['host'] = isset($aRefUrl['host']) ? $aRefUrl['host'] : '';
            $aRefUrl['path'] = isset($aRefUrl['path']) ? $aRefUrl['path'] : '';
            $aRefUrl['fragment'] = isset($aRefUrl['fragment']) ? '#'.$aRefUrl['fragment'] : '';
            $aWbUrl = parse_url(WB_URL);
            if ($aWbUrl !== false) {
                $aWbUrl['host'] = isset($aWbUrl['host']) ? $aWbUrl['host'] : '';
                $aWbUrl['path'] = isset($aWbUrl['path']) ? $aWbUrl['path'] : '';
                if (strpos($aRefUrl['host'].$aRefUrl['path'], $aWbUrl['host'].$aWbUrl['path']) !== false) {
                    $aRefUrl['path'] = preg_replace('#^'.$aWbUrl['path'].'#i', '', $aRefUrl['path']);
                    $sTmpReferer = WB_URL.$aRefUrl['path'].$aRefUrl['fragment'];
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
    return preg_replace('/^(.*)$/', '/\[$1\]/s', $aList);
}

if (!defined('ADMIN_DIRECTORY')) { define('ADMIN_DIRECTORY', 'admin'); }
if (!preg_match('/xx[a-z0-9_][a-z0-9_\-\.]+/i', 'xx'.ADMIN_DIRECTORY)) {
    throw new RuntimeException('Invalid admin-directory: ' . ADMIN_DIRECTORY);
}

if ( !defined('ADMIN_URL')) { define('ADMIN_URL', WB_URL.'/'.ADMIN_DIRECTORY); }
if ( !defined('WB_PATH')) { define('WB_PATH', dirname(dirname(__FILE__))); }
if ( !defined('ADMIN_PATH')) { define('ADMIN_PATH', WB_PATH.'/'.ADMIN_DIRECTORY); }

if (file_exists(WB_PATH.'/framework/class.database.php')) {
    // sanitize $_SERVER['HTTP_REFERER']
    SanitizeHttpReferer(WB_URL);
    date_default_timezone_set('UTC');
    // register TWIG autoloader ---
    $sTmp = dirname(dirname(__FILE__)).'/include/Sensio/Twig/lib/Twig/Autoloader.php';
    if (!class_exists('Twig_Autoloader') && is_readable($sTmp)){ 
        include($sTmp); 
        Twig_Autoloader::register();
    }
// register PHPMailer autoloader ---
    $sTmp = dirname(dirname(__FILE__)).'/include/phpmailer/PHPMailerAutoload.php';
    if (!function_exists('PHPMailerAutoload') && is_readable($sTmp)) {
        require($sTmp);
    }
    // load database class
    require_once(WB_PATH.'/framework/class.database.php');
    // Create database class
    $database = new database();

    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        @ini_set("magic_quotes_runtime", 0); // Disable magic_quotes_runtime
    }
    // Get website settings (title, keywords, description, header, and footer)
    $sql = 'SELECT `name`, `value` FROM `'.TABLE_PREFIX.'settings`';
    if (($get_settings = $database->query($sql))) {
        $x = 0;
        while ($setting = $get_settings->fetchRow(MYSQL_ASSOC)) {
            $setting_name  = strtoupper($setting['name']);
            $setting_value = $setting['value'];
            if ($setting_value == 'false') {
                $setting_value = false;
            }
            if ($setting_value == 'true') {
                $setting_value = true;
            }
            @define($setting_name, $setting_value);
            $x++;
        }
    } else {
        die($database->get_error());
    }
    if (!$x) { 
        throw new RuntimeException('no settings found');
    }
    @define('DO_NOT_TRACK', (isset($_SERVER['HTTP_DNT'])));
    $string_file_mode = STRING_FILE_MODE;
    @define('OCTAL_FILE_MODE',(int) octdec($string_file_mode));
    $string_dir_mode = STRING_DIR_MODE;
    @define('OCTAL_DIR_MODE',(int) octdec($string_dir_mode));
    $sSecMod = (defined('SECURE_FORM_MODULE') && SECURE_FORM_MODULE != '') ? '.'.SECURE_FORM_MODULE : '';
    $sSecMod = WB_PATH.'/framework/SecureForm'.$sSecMod.'.php';
    require_once($sSecMod);
    if (!defined("WB_INSTALL_PROCESS")) {
    // get CAPTCHA and ASP settings
        $sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_captcha_control`';
        if (($get_settings = $database->query($sql)) &&
            ($setting = $get_settings->fetchRow(MYSQLI_ASSOC))
        ) {
            @define('ENABLED_CAPTCHA', (($setting['enabled_captcha'] == '1') ? true : false));
            @define('ENABLED_ASP', (($setting['enabled_asp'] == '1') ? true : false));
            @define('CAPTCHA_TYPE', $setting['captcha_type']);
            @define('ASP_SESSION_MIN_AGE', (int)$setting['asp_session_min_age']);
            @define('ASP_VIEW_MIN_AGE', (int)$setting['asp_view_min_age']);
            @define('ASP_INPUT_MIN_AGE', (int)$setting['asp_input_min_age']);
        } else {
            throw new RuntimeException('CAPTCHA-Settings not found');
        }
    }
    // set error-reporting
    if (intval(ER_LEVEL) > 0 ) {
        error_reporting(ER_LEVEL);
        if (intval(ini_get ( 'display_errors' )) == 0 ) {
            ini_set('display_errors', 1);
        }
    }
    // Start a session
    if (!defined('SESSION_STARTED')) {
        session_name(APP_NAME.'-sid');
        @session_start();
        define('SESSION_STARTED', true);
    }
    if (defined('ENABLED_ASP') && ENABLED_ASP && !isset($_SESSION['session_started'])) {
        $_SESSION['session_started'] = time();
    }
    // Get users language
    if (
        isset($_GET['lang']) AND 
        $_GET['lang'] != '' AND 
        !is_numeric($_GET['lang']) AND 
        strlen($_GET['lang']) == 2
    ) {
        define('LANGUAGE', strtoupper($_GET['lang']));
        $_SESSION['LANGUAGE']=LANGUAGE;
    } else {
        if (isset($_SESSION['LANGUAGE']) AND $_SESSION['LANGUAGE'] != '') {
            define('LANGUAGE', $_SESSION['LANGUAGE']);
        } else {
            define('LANGUAGE', DEFAULT_LANGUAGE);
        }
    }
    // Load Language file
    if (!defined('LANGUAGE_LOADED')) {
        if (!file_exists(WB_PATH.'/languages/'.LANGUAGE.'.php')) {
            throw new RuntimeException('Error loading language file '.LANGUAGE.', please check configuration');
        } else {
            require_once(WB_PATH.'/languages/'.LANGUAGE.'.php');
        }
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
    define('THEME_URL', WB_URL.'/templates/'.DEFAULT_THEME);
    define('THEME_PATH', WB_PATH.'/templates/'.DEFAULT_THEME);
    // extended wb_settings
    define('EDIT_ONE_SECTION', false);
    define('EDITOR_WIDTH', 0);
}

