<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

/////////////////////////////////////////
//   set WBCE version and release tag
/////////////////////////////////////////

define('NEW_WBCE_VERSION', '1.5.0-alpha.1'); // NEW_WBCE_VERSION
define('NEW_WBCE_TAG', '1.5.0-alpha.1'); // NEW_WBCE_TAG

defined('WBCE_VERSION') or define('WBCE_VERSION', NEW_WBCE_VERSION); // WBCE_VERSION
defined('WBCE_TAG') or define('WBCE_TAG', NEW_WBCE_TAG);     // WBCE_TAG


/////////////////////////////////////////
//   Legacy: WB-classic
/////////////////////////////////////////

defined('VERSION') or define('VERSION', '2.8.3'); // VERSION
defined('REVISION') or define('REVISION', '1641');  // REVISION
defined('SP') or define('SP', 'SP4');   // SP


/////////////////////////////////////////
//   WBCE Semantic Versioning
/////////////////////////////////////////

// WBCE uses Semantic Versioning (http://semver.org) called SemVer in the following text.
// For core development using SemVer is mandatory for modules development this is recommended.

// Specification and Options
/*
* We use Option 9 (Denoting of pre-release versions)
* Valid pre-release versions are : "alpha", "beta" and "rc" The order those steps is mandatory but you are allowed to ignore one or more steps. For example if you have a bugfix release with only on change you may go to stable instantly.
* pre-release versions always start with version .1 (z.B. 1.0.0-alpha.1 , 1.0.0-beta.1)
*/

// Recommendations
/*
* The function version_compare() from PHP fully supports this versioning scheme, so we recommend using this function.
*/

// Example
/*
* Recent version: 1.4.3
* New release is Version: 1.4.4-alpha.1
* Now the release goes through several test phases:
* 1.4.4-alpha.2, 1.4.4-alpha.3, 1.4.4-beta.1, 1.4.4-rc.1, 1.4.4-rc.2
* After all it becomes a stable release: 1.4.4
*/
