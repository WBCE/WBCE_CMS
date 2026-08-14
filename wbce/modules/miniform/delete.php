<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.6 and higher
 * @version         0.15.0
 * @lastmodified    April 30, 2019
 *
 */


if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
require_once (WB_PATH.'/framework/functions.php');

// Delete page from mod_wrapper — both tables belong to the same section_id,
// so a partial failure (e.g. connection drop between the two statements)
// must not leave orphaned mod_miniform_data rows behind.
$database->transaction(function ($db) use ($section_id) {
    $db->deleteRow('{TP}mod_miniform', 'section_id', $section_id);
    $db->deleteRow('{TP}mod_miniform_data', 'section_id', $section_id);
});

?>