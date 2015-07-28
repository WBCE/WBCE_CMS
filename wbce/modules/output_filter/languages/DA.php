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

// Headings and text outputs
$MOD_MAIL_FILTER['HEADING'] = 'Indstillinger: Output-filter';
$MOD_MAIL_FILTER['HOWTO'] = 'Du kan konfigurere output-filteret med indstillingerne nedenfor.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailadresser kan krypteres vedhj&Atilde;&brvbar;lp af en  Javascript-funktion. For at g&Atilde;¸re brug af denne indstilling, skal du tilf&Atilde;¸je PHP-koden <code style="background:#FFA;color:#900;"><?php register_frontend_modfiles(js);?></code> til <head> sektionnen af  index.php i din template (layout-skabelon). Uden denne &Atilde;&brvbar;ndring vil kun @-tegnet i email-adressen blive erstattet.</p>';
$MOD_MAIL_FILTER['WARNING']				= '';

// Text and captions of form elements
$MOD_MAIL_FILTER['BASIC_CONF'] = 'Email grundindstillinger';
$MOD_MAIL_FILTER['SYS_REL'] = 'Frontendoutput with  relative Urls';
$MOD_MAIL_FILTER['EMAIL_FILTER'] = 'Filtrer emailadresser i tekst';
$MOD_MAIL_FILTER['MAILTO_FILTER'] = 'Filtrer emailadresser i mailto-links';
$MOD_MAIL_FILTER['ENABLED'] = 'Aktiveret';
$MOD_MAIL_FILTER['DISABLED'] = 'Deaktiveret';

$MOD_MAIL_FILTER['REPLACEMENT_CONF'] = 'Email erstatninger';
$MOD_MAIL_FILTER['AT_REPLACEMENT'] = 'Erstat "@" med';
$MOD_MAIL_FILTER['DOT_REPLACEMENT'] = 'Erstat "." med';

