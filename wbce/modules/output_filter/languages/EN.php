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

// Module description 
$module_description = 'A tool to configure the basic output filter of WB(CE)';
 
// Headings and text outputs
$OPF['HEADING']	= 'Options: Output Filter';
$OPF['HOWTO'] = 'You can configure the output filtering with the options below.<p style="line-height:1.5em;"><strong>Tip: </strong>Mailto links can be encrypted by a Javascript function. To make use of this option, one needs to add the PHP code <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> into the &lt;head&gt; section of the index.php of your template. Without this modification, only the @ character in the mailto part will be replaced.</p>';
$OPF['WARNING']	= '';

// Text and captions of form elements
$OPF['BASIC_CONF'] = 'Basic Email Configuration';
$OPF['SYS_REL'] = 'Frontendoutput with  relative Urls';
$OPF['EMAIL_FILTER'] = 'Filter Email addresses in text';
$OPF['MAILTO_FILTER'] = 'Filter Email addresses in mailto links';
$OPF['ENABLED']	= 'Enabled';
$OPF['DISABLED'] = 'Disabled';

$OPF['REPLACEMENT_CONF']= 'Email Replacements';
$OPF['AT_REPLACEMENT']	= 'Replace "@" by';
$OPF['DOT_REPLACEMENT']	= 'Replace "." by';

$OPF['ALL_ON_OFF'] = 'Enable/Disable all old Outputfilter';
$OPF['DROPLETS'] = 'Droplets filter';
$OPF['WBLINK'] = 'WB-Link Filter';
$OPF['INSERT'] = 'CSS, JS, Meta Insert Filter';
$OPF['JS_MAILTO'] = 'Use Javascript on Mailtofilter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';


