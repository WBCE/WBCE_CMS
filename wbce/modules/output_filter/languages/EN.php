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
$OPF['AUTO_PLACEHOLDER'] = 'Try to add placeholder for insert filter if they do not exist';
$OPF['INSERT'] = 'CSS, JS, Meta insert filter';
$OPF['JS_MAILTO'] = 'Use Javascript on Mailtofilter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';


