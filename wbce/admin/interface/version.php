<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * More Baking. Less Struggling.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later)
 */

if(!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}

// check if defined to avoid errors during installation (redirect to admin panel fails if PHP error/warnings are enabled)
if(!defined('VERSION')) define('VERSION', '1.0.0');
if(!defined('TAG')) define('TAG', 'WBCE1Beta2');

// Legacy: WB-classic
if(!defined('REVISION')) define('REVISION', '');
if(!defined('SP')) define('SP', '');


