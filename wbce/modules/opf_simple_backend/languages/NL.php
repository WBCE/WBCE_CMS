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
$module_description = 'Een tool om de basis outputfilters van het WBCE CMS te configureren';

// Headings and text outputs
$OPF['HEADING'] = 'Opties: Output Filter';
$OPF['HOWTO'] = 'Met de onderstaande opties kunt u de outputfilters configureren.';
$OPF['WARNING'] = '';

// Text and captions of form elements
$OPF['BASIC_CONF'] = 'Basis configuratie';
$OPF['SYS_REL'] = 'Frontend output met relatieve URLs';
$OPF['ENABLED'] = 'Ingeschakeld';
$OPF['DISABLED'] = 'Uitgeschakeld';

$OPF['REPLACEMENT_CONF']= 'Email vervangingen';

$OPF['DROPLETS'] = 'Droplets filter (zonder deze zullen droplets niet werken)';
$OPF['WBLINK'] = 'WB-Link Filter (vervang [wblinkXX] met de URL van de pagina)';
$OPF['AUTO_PLACEHOLDER'] = 'Voeg placeholder toe voor het verplaatsen van JS, CSS en meer';
$OPF['MOVE_STUFF'] = 'CSS, JS, zullen verplaatst worden naar de placeholder';
$OPF['REPLACE_STUFF'] = 'Vervang title, keywords of description door modules';
$OPF['SHORT_URL'] = 'Gebruik short url filter (geen /pages, geen .php - htaccess moet worden voorbereid)';
$OPF['CSS_TO_HEAD'] = 'Verplaats inline CSS naar &lt;head&gt;';
$OPF['REMOVE_SYSTEM_PH'] = 'Verwijder placesholders(PH) gegenereerd door core filters';



