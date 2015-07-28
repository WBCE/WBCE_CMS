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
 * @version         $Id: EN.php 1475 2011-07-12 23:07:10Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/output_filter/languages/EN.php $
 * @lastmodified    $Date: 2011-07-13 01:07:10 +0200 (Mi, 13. Jul 2011) $
 *
 */

// Headings and text outputs
$MOD_MAIL_FILTER['HEADING']	= 'Options: Output Filter';
$MOD_MAIL_FILTER['HOWTO'] = 'You can configure the output filtering with the options below.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailto links can be encrypted by a Javascript function. To make use of this option, one needs to add the PHP code <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> into the &lt;head&gt; section of the index.php of your template. Without this modification, only the @ character in the mailto part will be replaced.</p>';
$MOD_MAIL_FILTER['WARNING']	= '';

// Text and captions of form elements
$MOD_MAIL_FILTER['BASIC_CONF'] = 'Basic Email Configuration';
$MOD_MAIL_FILTER['SYS_REL'] = 'Frontendoutput with  relative Urls';
$MOD_MAIL_FILTER['EMAIL_FILTER'] = 'Filter Email addresses in text';
$MOD_MAIL_FILTER['MAILTO_FILTER'] = 'Filter Email addresses in mailto links';
$MOD_MAIL_FILTER['ENABLED']	= 'Enabled';
$MOD_MAIL_FILTER['DISABLED'] = 'Disabled';

$MOD_MAIL_FILTER['REPLACEMENT_CONF']= 'Email Replacements';
$MOD_MAIL_FILTER['AT_REPLACEMENT']	= 'Replace "@" by';
$MOD_MAIL_FILTER['DOT_REPLACEMENT']	= 'Replace "." by';
