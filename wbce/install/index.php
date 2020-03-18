<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

if (!defined("WB_INSTALLER"))define ("WB_INSTALLER",  true) ;

// start Session if not already started
if (!defined('SESSION_STARTED')) {
    session_name('wb-installer');
    session_start();
    define('SESSION_STARTED', true);
}


// Create default variables
$mod_path = dirname(str_replace('\\', '/', __FILE__));
$doc_root = rtrim(realpath($_SERVER['DOCUMENT_ROOT']), '/');
$mod_name = basename($mod_path);
$wb_path = dirname(dirname(realpath(__FILE__)));
$wb_root = str_replace(realpath($doc_root), '', $wb_path);

// Require helper functions
require_once("helper_functions.php");

// Require Version Info
require_once("../admin/interface/version.php");

// This is to decide if we display the Install Button on the end of the page
$installFlag = true;


////////////////////////////////////////////
// Session check
////////////////////////////////////////////

// Check if the page has been reloaded
// needed as part of checking if sessions are active
// Reloads page if not already checked for session
if (!isset($_GET['sessions_checked']) or $_GET['sessions_checked'] != 'true') {
    // Set session variable
    $_SESSION['session_support'] = '<span class="good">Enabled</span>';
    // Reload page if not already checked
    header('Location: index.php?sessions_checked=true'); exit;
} else {
    // Check if session variable has been saved after reload
    if (isset($_SESSION['session_support'])) {
        $sSessionSupportClass = 'good';
        $sSessionSupportText = 'Enabled';
    } else {
        $installFlag = false;
        $sSessionSupportClass = 'bad';
        $sSessionSupportText = 'Disabled';
    }
}


////////////////////////////////////////////
// Check charset
////////////////////////////////////////////

// Check if AddDefaultCharset is set
$e_adc = false;
$sapi = php_sapi_name();
if (strpos($sapi, 'apache') !== false || strpos($sapi, 'nsapi') !== false) {
    flush();
    $apache_rheaders = apache_response_headers();
    foreach ($apache_rheaders as $h) {
        if (strpos($h, 'html; charset') !== false) {
            preg_match('/charset\s*=\s*([a-zA-Z0-9- _]+)/', $h, $match);
            $apache_charset = $match[1];
            $e_adc = $apache_charset;
        }
    }
}
$chrval = (($e_adc != '') && (strtolower($e_adc) != 'utf-8') ? "bad" : "good");
$e_adc=$e_adc."";


////////////////////////////////////////////
// PHP version check
////////////////////////////////////////////

// No install button if Version failes
if (version_compare(PHP_VERSION, '7.1.3', '>=')) {
    $sPhpVersion="good";
}
else {
    $sPhpVersion="bad";
    $installFlag = false;
    set_error(d('e30: ').'Your PHP version is too old !');
}


////////////////////////////////////////////
// Check Save Mode
////////////////////////////////////////////

if (
    ini_get('safe_mode') == '' ||
    strpos(strtolower(ini_get('safe_mode')), 'off') !== false ||
    ini_get('safe_mode') == 0)
{
    $sSaveModeClass="good";
    $sSaveModeText="Disabled";
} else {
    $sSaveModeClass="bad";
    $sSaveModeText="Enabled";
}


////////////////////////////////////////////
// Check config.php
////////////////////////////////////////////

$config = '<span class="good">Writeable</span>';
$config_content = "<?php\n";
$configFile = '/config.php';
if (!isset($_SESSION['config_rename'])) {
    if ((file_exists($wb_path . '/config.php.new') == true) && !(file_exists($wb_path . $configFile) == true)) {
        rename($wb_path . '/config.php.new', $wb_path . $configFile);
    }
    if ((file_exists($wb_path . $configFile) == true)) {
        // next operation only if file is writeable
        if (is_writeable($wb_path . $configFile)) {
            // already installed? it's not empty
            if (filesize($wb_path . $configFile) > 128) {
                $installFlag = false;
                $config = '<span class="bad">Not empty! WBCE already installed?</span>';
                // try to open and to write
            } elseif (!$handle = fopen($wb_path . $configFile, 'w')) {
                $installFlag = false;
                $config = '<span class="bad">Not Writeable</span>';
            } else {
                if (fwrite($handle, $config_content) === false) {
                    $installFlag = false;
                    $config = '<span class="bad">Not Writeable</span>';
                } else {
                    $config = '<span class="good">Writeable</span>';
                    $_SESSION['config_rename'] = true;
                }
                // Close file
                fclose($handle);
            }
        } else {
            $installFlag = false;
            $config = '<span class="bad">Not Writeable</span>';
        }
    } else {
        $installFlag = false;
        $config = '<span class="bad">Missing!!?</span>';
    }
}


////////////////////////////////////////////
// Check directories
////////////////////////////////////////////

if (is_writable('../pages/')) {
    $sDirPages= '<span class="good">Writeable</span>';
}
elseif (!file_exists('../pages/')) {
    $sDirPages=  '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirPages=  '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../media/')) {
    $sDirMedia= '<span class="good">Writeable</span>';
}
elseif (!file_exists('../media/')) {
    $sDirMedia= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirMedia= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../templates/')) {
    $sDirTemplates= '<span class="good">Writeable</span>';
}
elseif (!file_exists('../templates/')) {
    $sDirTemplates= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirTemplates= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../modules/')) {
    $sDirModules= '<span class="good">Writeable</span>';
}
elseif (!file_exists('../modules/')) {
    $sDirModules= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirModules= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../languages/')) {
    $sDirLanguages= '<span class="good">Writeable</span>';
}
elseif (!file_exists('../languages/')) {
    $sDirLanguages= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirLanguages= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../temp/')) {
    $sDirTemp= '<span class="good">Writeable</span>';
}
elseif (!file_exists('../temp/')) {
    $sDirTemp= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirTemp= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../config/')) {
    $sDirConfig= '<span class="good">Writeable</span>';
}
elseif (!file_exists('../config/')) {
    $sDirConfig= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirConfig= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../var/')) {
    $sDirVar= '<span class="good">Writeable</span>';
} 
elseif (!file_exists('../var/')) {
    $sDirVar= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirVar= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


if (is_writable('../log/')) {
    $sDirLog= '<span class="good">Writeable</span>';
} 
elseif (!file_exists('../log/')) {
    $sDirLog= '<span class="bad">Directory Not Found</span>';
    $installFlag = false;
}
else {
    $sDirLog= '<span class="bad">Unwriteable</span>';
    $installFlag = false;
}


////////////////////////////////////////////
// Absolute URL
////////////////////////////////////////////

// Try to guess installation URL
$scheme      = (isset($_SERVER['HTTPS']) ? 'https' : 'http');
$guessed_url = $scheme . '://' . $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"];
$guessed_url = rtrim(dirname($guessed_url), 'install');
$sWbUrl= $guessed_url;

// is there is one set in session choose that
if (isset($_SESSION['wb_url'])) {$sWbUrl= $_SESSION['wb_url'];}


////////////////////////////////////////////
// TimeZones
////////////////////////////////////////////

// setting zones manually
$aZones = array(-12, -11, -10, -9, -8, -7, -6, -5, -4, -3.5, -3, -2, -1, 0, 1, 2, 3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 8, 9, 9.5, 10, 11, 12, 13);

// Function for easy templating
function TzSelected($fOffset) {
    if (
        (isset($_SESSION['default_timezone']) and $_SESSION['default_timezone'] == (string) $fOffset) ||
        (!isset($_SESSION['default_timezone']) and $fOffset == 0)
    ) {
        return true;
    }
    return false;
}


////////////////////////////////////////////
// Fetch allowed Languages
////////////////////////////////////////////

//Find all available languages in /language/ folder and build option list from

$sLangDir = str_replace('\\', '/', dirname(dirname(__FILE__)) . '/languages/');
$aAllowedLanguages = preg_replace('/^.*\/([A-Z]{2})\.php$/iU', '\1', glob($sLangDir . '??.php'));
sort($aAllowedLanguages);
$sOutput = PHP_EOL;

foreach ($aAllowedLanguages as $sLangCode) {
    if (is_readable($sLangDir . $sLangCode . '.php')) {
        if (($sContent = file_get_contents($sLangDir . $sLangCode . '.php', false, null)) !== false) {
            if (preg_match('/.*\s*\$language_name\s*=\s*([\'\"])([^\1]*)\1\s*;/siU', $sContent, $aMatches)) {
                $aLangs[$sLangCode]= $aMatches[2];
            }
        }
    }
}
$aAllowedLanguages=$aLangs;
natsort($aAllowedLanguages);

// Function for easy templating
function LangSelected($sLangCode) {
    if (
        (isset($_SESSION['default_language']) and $_SESSION['default_language'] == $sLangCode) ||
        (!isset($_SESSION['default_language']) and $sLangCode == 'EN')
    ) {
        return true;
    }
    return false;
}


////////////////////////////////////////////
// OS Stuff
////////////////////////////////////////////

// for shorter Templating
$sLinux = '';
$sWindows='';
$sPermissionBlock='';
$sWorldWriteableCheck='';
//Linux
if (!isset($_SESSION['operating_system']) or $_SESSION['operating_system'] == 'linux') {$sLinux = ' checked="checked"';}

// Windows
if (isset($_SESSION['operating_system']) and $_SESSION['operating_system'] == 'windows') {$sWindows=' checked="checked"';}

// Permissions Block
if (isset($_SESSION['operating_system']) and $_SESSION['operating_system'] == 'windows') {$sPermissionBlock= 'none';}
else                                                                                     {$sPermissionBlock= 'block';}

// World Writable checkbox
if (isset($_SESSION['world_writeable']) and $_SESSION['world_writeable'] == "true") { $sWorldWriteableCheck= ' checked="checked"';}


////////////////////////////////////////////
// DB Stuff
////////////////////////////////////////////

//for shorter Templating
$sDatabaseHost =     'localhost';
$sDatabaseName =     'DatabaseName';
$sTablePrefix =      'wbce_';
$sDatabaseUsername = '';
$sDatabasePassword = '';


if (isset($_SESSION['database_host']))     {$sDatabaseHost= $_SESSION['database_host'];}
if (isset($_SESSION['database_name']))     {$sDatabaseName= $_SESSION['database_name'];}
if (isset($_SESSION['table_prefix']))      {$sTablePrefix= $_SESSION['table_prefix'];}
if (isset($_SESSION['database_username'])) {$sDatabaseUsername= $_SESSION['database_username'];}
if (isset($_SESSION['database_password'])) {$sDatabasePassword= $_SESSION['database_password'];}

/// @todo reactivate the install Tables settings / better overwrite existing tables


////////////////////////////////////////////
// Page title
////////////////////////////////////////////

//for shorter Templating
$sWebsiteTitle="Enter your website title";

if (isset($_SESSION['website_title']))   {$sWebsiteTitle= $_SESSION['website_title'];}


////////////////////////////////////////////
// Admin Stuff
////////////////////////////////////////////

//for shorter Templating
$sAdminPassword="";
$sAdminRepassword="";
$sAdminUsername="";
$sAdminEmail="";

if (isset($_SESSION['admin_username']))   {$sAdminUsername= $_SESSION['admin_username'];}
if (isset($_SESSION['admin_email']))      {$sAdminEmail= $_SESSION['admin_email'];}
if (isset($_SESSION['admin_password']))   {$sAdminPassword= $_SESSION['admin_password'];}
if (isset($_SESSION['admin_repassword'])) {$sAdminRepassword= $_SESSION['admin_repassword'];}


////////////////////////////////////////////
// Include  Template
////////////////////////////////////////////

// Finally include the template
include "install_form.tpl.php";

