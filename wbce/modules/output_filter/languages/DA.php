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
 * @version         $Id: DA.php 1475 2011-07-12 23:07:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/languages/DA.php $
 * @lastmodified    $Date: 2011-07-13 01:07:10 +0200 (Mi, 13. Jul 2011) $
 *
 */

// Module description 
$module_description = 'A tool to configure the basic output filter of WB(CE)';
 
// Headings and text outputs
$OPF['HEADING'] = 'Indstillinger: Output-filter';
$OPF['HOWTO'] = 'Du kan konfigurere output-filteret med indstillingerne nedenfor.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailadresser kan krypteres vedhj&Atilde;&brvbar;lp af en  Javascript-funktion. For at g&Atilde;¸re brug af denne indstilling, skal du tilf&Atilde;¸je PHP-koden <code style="background:#FFA;color:#900;"><?php register_frontend_modfiles(js);?></code> til <head> sektionnen af  index.php i din template (layout-skabelon). Uden denne &Atilde;&brvbar;ndring vil kun @-tegnet i email-adressen blive erstattet.</p>';
$OPF['WARNING']				= '';

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
