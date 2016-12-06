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
if (version_compare(PHP_VERSION, '5.3.6', '>=')) {
    $sPhpVersion="good";
} 
else {
    $sPhpVersion="bad";
    $installFlag = false;
    set_error('e30: Your PHP version is too old !');
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

include "install_form.tpl.php";