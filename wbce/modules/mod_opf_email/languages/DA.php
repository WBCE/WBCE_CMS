<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright       WBCE Project (2015-2017)
 * @category        opffilter
 * @package         OPF E-Mail
 * @version         1.0.0
 * @authors         Martin Hecht (mrbaseman)
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
$OPF['BASIC_CONF'] = 'Email grundindstillinger';
$OPF['SYS_REL'] = 'Frontendoutput with  relative Urls';
$OPF['EMAIL_FILTER'] = 'Filtrer emailadresser i tekst';
$OPF['MAILTO_FILTER'] = 'Filtrer emailadresser i mailto-links';
$OPF['ENABLED'] = 'Aktiveret';
$OPF['DISABLED'] = 'Deaktiveret';

$OPF['REPLACEMENT_CONF'] = 'Email erstatninger';
$OPF['AT_REPLACEMENT'] = 'Erstat "@" med';
$OPF['DOT_REPLACEMENT'] = 'Erstat "." med';

$OPF['ALL_ON_OFF'] = 'Enable/Disable all old Outputfilter';
$OPF['DROPLETS'] = 'Droplets filter';
$OPF['WBLINK'] = 'WB-Link Filter';
$OPF['INSERT'] = 'CSS, JS, Meta Insert Filter';
$OPF['JS_MAILTO'] = 'Use Javascript on Mailtofilter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';
