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
 * @version         $Id: FR.php 1475 2011-07-12 23:07:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/languages/FR.php $
 * @lastmodified    $Date: 2011-07-13 01:07:10 +0200 (Mi, 13. Jul 2011) $
 *
 */

//Module Description
$module_description = 'Ce module g&egrave;re le filtrage des donn&eacute;es avant affichage &agrave; l&apos;utilisateur. Permets de filtrer les liens mailto et les adresses emails.';

// Headings and text outputs
$MOD_MAIL_FILTER['HEADING'] = 'Options: Output Filter';
$MOD_MAIL_FILTER['HOWTO'] = 'Vous pouvez configurer le filtrage des donn&eacute;es avant affichage gr&acirc;ce aux options ci-dessous.<p style="line-height:1.5em;"><strong>Conseil: </strong>Les liens Mailto peuvent &ecirc;tre crypt&eacute;s &agrave; l&apos;aide d&apos;une fonction Javascript. Pour utiliser cette fonctionnalit&eacute;, vous devez ajouter le code PHP <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(&apos;js&apos;);?&gt;</code> dans la partie &lt;head&gt; de index.php de votre fichier mod&egrave;le. Sans cette modification, seulement le caract&egrave;re @ sera remplac&eacute; dans le champ mailto.</p>';
$MOD_MAIL_FILTER['WARNING'] = '';

// Text and captions of form elements
$MOD_MAIL_FILTER['BASIC_CONF'] = 'Configuration de base des Emails';
$MOD_MAIL_FILTER['SYS_REL'] = 'Frontendoutput with  relative Urls';
$MOD_MAIL_FILTER['EMAIL_FILTER'] = 'Filtrer le texte des Emails';
$MOD_MAIL_FILTER['MAILTO_FILTER'] = 'Filtrer les liens mailto des Emails';
$MOD_MAIL_FILTER['ENABLED'] = 'Activ&eacute;';
$MOD_MAIL_FILTER['DISABLED'] = 'D&eacute;sactiv&eacute;';

$MOD_MAIL_FILTER['REPLACEMENT_CONF']= 'Remplacements';
$MOD_MAIL_FILTER['AT_REPLACEMENT']	= 'Remplacer "@" par';
$MOD_MAIL_FILTER['DOT_REPLACEMENT']	= 'Remplacer "." par';
