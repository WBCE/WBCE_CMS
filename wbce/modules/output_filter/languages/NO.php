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
 * @version         $Id: NO.php 1475 2011-07-12 23:07:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/languages/NO.php $
 * @lastmodified    $Date: 2011-07-13 01:07:10 +0200 (Mi, 13. Jul 2011) $
 *
 */

 
// Module description 
$module_description = 'A tool to configure the basic output filter of WB(CE)';
 
// Headings and text outputs
$OPF['HEADING']	= 'Valg: Filtrering av ut data';
$OPF['HOWTO']	= 'Du kan gj&oslash;re innstillinger for utdatafitreringen i valgene nedenfor.<p style="line-height:1.5em;"><strong>Tips: </strong>Mailto linker kan krypteres av en Javascript funksjon. For &aring; f&aring; benyttet denne funksjonen, m&aring; det legges til f&oslash;lgende PHP kode <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> inn i &lt;head&gt; seksjonen i index.php p&aring; design malen din. Uten denne modifikasjonen, vil kun @ karakterer i mailto linker bli erstattet.</p>';
$OPF['WARNING']	= '';

// Text and captions of form elements
$OPF['BASIC_CONF']	= 'Enkel Epost konfigurasjon';
$OPF['SYS_REL'] = 'Frontendoutput with relative Urls';
$OPF['EMAIL_FILTER']	= 'Filtrer Epost adresser i tekst';
$OPF['MAILTO_FILTER']	= 'Filtrer Epost adresser i mailto linker';
$OPF['ENABLED']	= 'P&aring;sl&aring;tt';
$OPF['DISABLED']	= 'Avsl&aring;tt';

$OPF['REPLACEMENT_CONF']= 'Endringe i Epost adresser';
$OPF['AT_REPLACEMENT']	= 'Bytt "@" med';
$OPF['DOT_REPLACEMENT']	= 'Bytt "." med';


$OPF['ALL_ON_OFF'] = 'Enable/Disable all old Outputfilter';
$OPF['DROPLETS'] = 'Droplets filter';
$OPF['WBLINK'] = 'WB-Link Filter';
$OPF['INSERT'] = 'CSS, JS, Meta Insert Filter';
$OPF['JS_MAILTO'] = 'Use Javascript on Mailtofilter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';