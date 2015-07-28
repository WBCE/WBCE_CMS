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

// Headings and text outputs
$MOD_MAIL_FILTER['HEADING']				= 'Beheersinstellingen: Output Filter';
$MOD_MAIL_FILTER['HOWTO']				= 'Hier kan je de uitvoer filteren met onderstaande opties.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailto links kunnen gecodeerd worden door een Javascript functie. Om van deze optie gebruik te kunnen maken moet je de PHP code <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> in de &lt;head&gt; sectie van het index.php bestand van je template plaatsen. Zonder deze aanpassing zal enkel het @ teken in het mailto deel vervangen worden.</p>';
$MOD_MAIL_FILTER['WARNING']				= '';

// Text and captions of form elements
$MOD_MAIL_FILTER['BASIC_CONF']			= 'E-mail Configuratie';
$MOD_MAIL_FILTER['SYS_REL'] = 'Frontendoutput with  relative Urls';
$MOD_MAIL_FILTER['EMAIL_FILTER']		= 'Filter E-mail adressen in tekst';
$MOD_MAIL_FILTER['MAILTO_FILTER']		= 'Filter E-mail adressen in mailto links';
$MOD_MAIL_FILTER['ENABLED']				= 'Aan';
$MOD_MAIL_FILTER['DISABLED']			= 'Uit';

$MOD_MAIL_FILTER['REPLACEMENT_CONF']	= 'Vervang E-mail tekens';
$MOD_MAIL_FILTER['AT_REPLACEMENT']		= 'Vervang "@" door';
$MOD_MAIL_FILTER['DOT_REPLACEMENT']		= 'Vervang "." door';
