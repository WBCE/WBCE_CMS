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

$PRECHECK = array();

$PRECHECK['WBCE_VERSION'] = array(
    'VERSION' => '1.4',
    'OPERATOR' => '>='
);

$PRECHECK['WB_ADDONS'] = array(
    'outputfilter_dashboard'=>array(
        'VERSION' => '1.5.1',
        'OPERATOR' => '>='
    )
);

$status = defined('WBCE_VERSION');
$required = $TEXT['INSTALLED'];
$actual = ($status) ? $TEXT['INSTALLED'] : $TEXT['NOT_INSTALLED'];

$PRECHECK['CUSTOM_CHECKS'] = array(
    'WBCE' => array(
        'REQUIRED' => $required,
        'ACTUAL' => $actual,
        'STATUS' => $status
    )
);
