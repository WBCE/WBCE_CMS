<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright    Ryan Djurovich (2004-2009)
 * @copyright    WebsiteBaker Org. e.V. (2009-2015)
 * @copyright    WBCE Project (2015-)
 * @category     tool
 * @package      OPF E-Mail
 * @version      1.1.7
 * @authors      Martin Hecht (mrbaseman)
 * @link         https://forum.wbce.org/viewtopic.php?id=176
 * @license      GNU GPL2 (or any later version)
 * @platform     WBCE 1.x
 * @requirements OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
    // Stop this file being access directly
    if (!headers_sent()) {
        header("Location: ../index.php", true, 301);
    }
    die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

// Module description
$module_description = 'A tool to configure the basic output filter of WB(CE)';

// Headings and text outputs
$OPF['HEADING'] = 'Indstillinger: Output-filter';
$OPF['HOWTO'] = 'Du kan konfigurere output-filteret med indstillingerne nedenfor.'; 
$OPF['WARNING'] = '';

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
