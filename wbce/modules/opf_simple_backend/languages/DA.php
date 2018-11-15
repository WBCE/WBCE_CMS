<?php
/**
 *
 * @category        backend,hidden
 * @package         OpF Simple Backend
 * @version         1.3.0
 * @authors         Martin Hecht (mrbaseman)
 * @copyright       (c) 2018, Martin Hecht (mrbaseman)
 * @link            https://forum.wbce.org/viewtopic.php?id=176
 * @license         GNU GPL2 (or any later version)
 * @platform        WBCE 1.2.x
 * @requirements    OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */



// Module description
$module_description = 'A tool to configure the basic output filter of WB(CE)';

// Headings and text outputs
$OPF['HEADING'] = 'Indstillinger: Output-filter';
$OPF['HOWTO'] = 'Du kan konfigurere output-filteret med indstillingerne nedenfor.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailadresser kan krypteres vedhj&Atilde;&brvbar;lp af en  Javascript-funktion. For at g&Atilde;¸re brug af denne indstilling, skal du tilf&Atilde;¸je PHP-koden <code style="background:#FFA;color:#900;"><?php register_frontend_modfiles(js);?></code> til <head> sektionnen af  index.php i din template (layout-skabelon). Uden denne &Atilde;&brvbar;ndring vil kun @-tegnet i email-adressen blive erstattet.</p>';
$OPF['WARNING']                                = '';

// Text and captions of form elements
$OPF['BASIC_CONF'] = 'Grundindstillinger';
$OPF['SYS_REL'] = 'Frontendoutput with  relative Urls';
$OPF['ENABLED'] = 'Aktiveret';
$OPF['DISABLED'] = 'Deaktiveret';

$OPF['REPLACEMENT_CONF'] = 'Email erstatninger';

$OPF['DROPLETS'] = 'Droplets filter';
$OPF['WBLINK'] = 'WB-Link Filter';
$OPF['AUTO_PLACEHOLDER'] = 'Try to add placeholder for insert filter if they do not exist';
$OPF['INSERT'] = 'CSS, JS, Meta Insert Filter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';
