<?php
/**
 *
 * @category        modules
 * @package         output_filter
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: NL.php 1475 2011-07-12 23:07:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/languages/NL.php $
 * @lastmodified    $Date: 2011-07-13 01:07:10 +0200 (Mi, 13. Jul 2011) $
 *
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