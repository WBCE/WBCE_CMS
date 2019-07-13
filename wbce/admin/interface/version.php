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

/////////////////////////////////////////
//   set WBCE version and release tag
/////////////////////////////////////////

define('NEW_WBCE_VERSION', '1.4.0-RC'); // NEW_WBCE_VERSION
define('NEW_WBCE_TAG',     '1.4.0-RC'); // NEW_WBCE_TAG

defined('WBCE_VERSION') or define('WBCE_VERSION', NEW_WBCE_VERSION); // WBCE_VERSION
defined('WBCE_TAG')     or define('WBCE_TAG',     NEW_WBCE_TAG);     // WBCE_TAG


/////////////////////////////////////////
//   Legacy: WB-classic
/////////////////////////////////////////

defined('VERSION')  or define('VERSION',  '2.8.3'); // VERSION
defined('REVISION') or define('REVISION', '1641');  // REVISION
defined('SP')       or define('SP',       'SP4');   // SP