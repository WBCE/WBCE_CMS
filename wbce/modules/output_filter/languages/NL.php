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

 
// Module description 
$module_description = 'A tool to configure the basic output filter of WB(CE)';
 
// Headings and text outputs
$OPF['HEADING']				= 'Beheersinstellingen: Output Filter';
$OPF['HOWTO']				= 'Hier kan je de uitvoer filteren met onderstaande opties.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailto links kunnen gecodeerd worden door een Javascript functie. Om van deze optie gebruik te kunnen maken moet je de PHP code <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> in de &lt;head&gt; sectie van het index.php bestand van je template plaatsen. Zonder deze aanpassing zal enkel het @ teken in het mailto deel vervangen worden.</p>';
$OPF['WARNING']				= '';

// Text and captions of form elements
$OPF['BASIC_CONF']			= 'E-mail Configuratie';
$OPF['SYS_REL'] = 'Frontendoutput with  relative Urls';
$OPF['EMAIL_FILTER']		= 'Filter E-mail adressen in tekst';
$OPF['MAILTO_FILTER']		= 'Filter E-mail adressen in mailto links';
$OPF['ENABLED']				= 'Aan';
$OPF['DISABLED']			= 'Uit';

$OPF['REPLACEMENT_CONF']	= 'Vervang E-mail tekens';
$OPF['AT_REPLACEMENT']		= 'Vervang "@" door';
$OPF['DOT_REPLACEMENT']		= 'Vervang "." door';

$OPF['ALL_ON_OFF'] = 'Enable/Disable all old Outputfilter';
$OPF['DROPLETS'] = 'Droplets filter';
$OPF['WBLINK'] = 'WB-Link Filter';
$OPF['INSERT'] = 'CSS, JS, Meta Insert Filter';
$OPF['JS_MAILTO'] = 'Use Javascript on Mailtofilter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';
