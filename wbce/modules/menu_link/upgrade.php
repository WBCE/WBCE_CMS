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

// Prevent this file from being access directly
defined('WB_PATH') or die('Cannot access this file directly');

$msg = '';
$sTable = '{TP}mod_menu_link';
if (($sOldType = $database->getTableEngine($sTable))) {
    if (('myisam' != strtolower($sOldType))) {
        if (!$database->query('ALTER TABLE `'.$sTable.'` Engine = \'MyISAM\' ')) {
            $msg = $database->get_error();
        }
    }
} else {
    $msg = $database->get_error();
}
