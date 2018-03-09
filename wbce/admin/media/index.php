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

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Media', 'media');

$noPage=false;
$modulePath=WB_PATH.'/modules/el_finder/';
if (file_exists('../../modules/el_finder/tool.php')) {
include ('../../modules/el_finder/tool.php');
} else {
	$admin->print_error($MESSAGE['MISSING_EL_FINDER'], '../index.php', false);
	die;
}

$admin->print_footer();