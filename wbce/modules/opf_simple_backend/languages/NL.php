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
$OPF['HEADING']                         = 'Beheersinstellingen: Output Filter';
$OPF['HOWTO']                           = 'Hier kan je de uitvoer filteren met onderstaande opties.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailto links kunnen gecodeerd worden door een Javascript functie. Om van deze optie gebruik te kunnen maken moet je de PHP code <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> in de &lt;head&gt; sectie van het index.php bestand van je template plaatsen. Zonder deze aanpassing zal enkel het @ teken in het mailto deel vervangen worden.</p>';
$OPF['WARNING']                         = '';

// Text and captions of form elements
$OPF['BASIC_CONF']                      = 'Configuratie';
$OPF['SYS_REL'] = 'Frontendoutput with  relative Urls';
$OPF['ENABLED']                         = 'Aan';
$OPF['DISABLED']                        = 'Uit';

$OPF['REPLACEMENT_CONF']        = 'Vervang E-mail tekens';

$OPF['DROPLETS'] = 'Droplets filter';
$OPF['WBLINK'] = 'WB-Link Filter';
$OPF['INSERT'] = 'CSS, JS, Meta Insert Filter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';

