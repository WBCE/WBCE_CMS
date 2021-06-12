<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright    Ryan Djurovich (2004-2009)
 * @copyright    WebsiteBaker Org. e.V. (2009-2015)
 * @copyright    WBCE Project (2015-)
 * @category     tool
 * @package      OPF E-Mail
 * @version      1.1.7
 * @authors      Martin Hecht (mrbaseman)
 * @link         https://forum.wbce.org/viewtopic.php?id=176
 * @license      GNU GPL2 (or any later version)
 * @platform     WBCE 1.x
 * @requirements OutputFilter Dashboard 1.5.x and PHP 5.4 or higher
 *
 **/

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if (!defined('WB_PATH')) {
    // Stop this file being access directly
    if (!headers_sent()) {
        header("Location: ../index.php", true, 301);
    }
    die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */

// Module description
$module_description = 'A tool to configure the basic output filter of WB(CE)';

// Headings and text outputs
$OPF['HEADING'] = '&#1053;&#1072;&#1089;&#1090;&#1088;&#1086;&#1081;&#1082;&#1080;: Output Filter';
$OPF['HOWTO'] = '&#1042;&#1099; &#1084;&#1086;&#1078;&#1077;&#1090;&#1077; &#1085;&#1072;&#1089;&#1090;&#1088;&#1086;&#1080;&#1090;&#1100; &#1092;&#1080;&#1083;&#1100;&#1090;&#1088;&#1072;&#1094;&#1080;&#1102;, &#1080;&#1089;&#1087;&#1086;&#1083;&#1100;&#1079;&#1091;&#1103; &#1085;&#1072;&#1089;&#1090;&#1088;&#1086;&#1081;&#1082;&#1080; &#1085;&#1080;&#1078;&#1077;.<p style="line-height:1.5em;"><strong>&#1042;&#1072;&#1078;&#1085;&#1086;: </strong>Mailto &#1089;&#1089;&#1099;&#1083;&#1082;&#1080; &#1084;&#1086;&#1075;&#1091;&#1090; &#1073;&#1099;&#1090;&#1100; &#1089;&#1082;&#1088;&#1099;&#1090;&#1099; &#1086;&#1090; &#1089;&#1087;&#1072;&#1084;&#1077;&#1088;&#1086;&#1074; &#1089; &#1087;&#1086;&#1084;&#1086;&#1097;&#1100;&#1102; Javascript. &#1063;&#1090;&#1086;&#1073;&#1099; &#1080;&#1089;&#1087;&#1086;&#1083;&#1100;&#1079;&#1086;&#1074;&#1072;&#1090;&#1100; &#1101;&#1090;&#1091; &#1074;&#1086;&#1079;&#1084;&#1086;&#1078;&#1085;&#1086;&#1089;&#1090;&#1100;, &#1076;&#1086;&#1073;&#1072;&#1074;
&#1100;&#1090;&#1077; &#1089;&#1083;&#1077;&#1076;&#1091;&#1102;&#1097;&#1080;&#1081; PHP &#1082;&#1086;&#1076; <code style="background:#FFA;color:#900;">&lt;?php register_frontend_modfiles(\'js\');?&gt;</code> &#1074; &lt;head&gt; &#1089;&#1077;&#1082;&#1094;&#1080;&#1102; index.php &#1092;&#1072;&#1081;&#1083;&#1072; &#1074;&#1072;&#1096;&#1077;&#1075;&#1086; &#1096;&#1072;&#1073;&#1083;&#1086;&#1085;&#1072;. &#1048;&#1085;&#1072;&#1095;&#1077; &#1090;&#1086;&#1083;&#1100;&#1082;&#1086; &#1089;&#1080;&#1084;&#1074;&#1086;&#1083; @ &#1073;&#1091;&#1076;&#1077;&#1090; &#1079;&#1072;&#1084;&#1077;&#1085;&#1077;&#1085; &#1074; mailto &#1089;&#1089;&#1099;&#1083;&#1082;&#1072;&#1093;.</p>';
$OPF['WARNING'] = '';

// Text and captions of form elements
$OPF['BASIC_CONF'] = '&#1054;&#1089;&#1085;&#1086;&#1074;&#1085;&#1099;&#1077; &#1085;&#1072;&#1089;&#1090;&#1088;&#1086;&#1081;&#1082;&#1080; Email';
$OPF['SYS_REL'] = 'Frontendoutput with relative Urls';
$OPF['EMAIL_FILTER'] = '&#1057;&#1082;&#1088;&#1099;&#1074;&#1072;&#1090;&#1100; Email &#1072;&#1076;&#1088;&#1077;&#1089;&#1072; &#1074; &#1090;&#1077;&#1082;&#1089;&#1090;&#1077;';
$OPF['MAILTO_FILTER'] = '&#1057;&#1082;&#1088;&#1099;&#1074;&#1072;&#1090;&#1100; Email &#1072;&#1076;&#1088;&#1077;&#1089;&#1072; &#1074; mailto &#1089;&#1089;&#1099;&#1083;&#1082;&#1072;&#1093;';
$OPF['ENABLED'] = '&#1042;&#1082;&#1083;&#1102;&#1095;&#1077;&#1085;&#1086;';
$OPF['DISABLED'] = '&#1042;&#1099;&#1082;&#1083;&#1102;&#1095;&#1077;&#1085;&#1086;';

$OPF['REPLACEMENT_CONF']= '&#1047;&#1072;&#1084;&#1077;&#1085;&#1099; &#1074; &#1072;&#1076;&#1088;&#1077;&#1089;&#1072;&#1093; Email';
$OPF['AT_REPLACEMENT'] = '&#1047;&#1072;&#1084;&#1077;&#1085;&#1103;&#1090;&#1100; "@" &#1085;&#1072;';
$OPF['DOT_REPLACEMENT'] = '&#1047;&#1072;&#1084;&#1077;&#1085;&#1103;&#1090;&#1100; "." &#1085;&#1072;';

$OPF['ALL_ON_OFF'] = 'Enable/Disable all old Outputfilter';
$OPF['DROPLETS'] = 'Droplets filter';
$OPF['WBLINK'] = 'WB-Link Filter';
$OPF['INSERT'] = 'CSS, JS, Meta Insert Filter';
$OPF['JS_MAILTO'] = 'Use Javascript on Mailtofilter';
$OPF['SHORT_URL'] = 'Use short url filter';
$OPF['CSS_TO_HEAD'] = 'Use CSS to head';
