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
defined('WB_PATH') or die('Cannot access this file directly');

// MyISAM enforcement is MySQL-only — `ALTER TABLE ... ENGINE = 'MyISAM'` is
// invalid on SQLite, and SQLite has no storage engines to begin with
// (getTableEngine() already returns a fixed 'SQLite' sentinel there). Skip
// entirely for that driver instead of letting the ALTER fail.
$msg = '';
if ($database->getDriver() !== 'sqlite') {
    $sTable = TABLE_PREFIX.'mod_wrapper';
    if(($sOldType = $database->getTableEngine($sTable))) {
        if(('myisam' != strtolower($sOldType))) {
            $database->query('ALTER TABLE `'.$sTable.'` Engine = \'MyISAM\' ');
            if ($database->hasError()) {
                $msg = $database->getError();
            }
        }
    } else {
        $msg = $database->getError();
    }
}
// ------------------------------------