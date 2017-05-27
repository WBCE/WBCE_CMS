<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// set PHP error reporting to the user defined level
// Note: define('WB_DEBUG', true); in WBCE config.php forces error_reporting(E_ALL|E_STRICT)
$ER_LEVELS = array(
    'E0' => $TEXT['ERR_USE_SYSTEM_DEFAULT'],        // system default (php.ini)
    'E1' => $TEXT['ERR_HIDE_ERRORS_NOTICES'],       // error_reporting(0)
    'E2' => $TEXT['ERR_SHOW_ERRORS_NOTICES'],       // error_reporting(E_ALL|E_STRICT)
    'E3' => $TEXT['ERR_SHOW_ERRORS_HIDE_NOTICES']   // error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE)
);
