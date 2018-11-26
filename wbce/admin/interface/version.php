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

// set WBCE version and release tag
define('NEW_WBCE_VERSION', '1.3.3');
if (!defined('WBCE_VERSION')) {
    define('WBCE_VERSION', NEW_WBCE_VERSION);
}

define('NEW_WBCE_TAG', '1.3.3');
if (!defined('WBCE_TAG')) {
    define('WBCE_TAG', NEW_WBCE_TAG);
}

// Legacy: WB-classic
if (!defined('VERSION')) {
    define('VERSION', '2.8.3');
}

if (!defined('REVISION')) {
    define('REVISION', '1641');
}

if (!defined('SP')) {
    define('SP', 'SP4');
}
