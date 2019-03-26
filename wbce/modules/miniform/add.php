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
 * @version         0.14.0
 * @lastmodified    May 22, 2019
 *
 */


if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
require_once (WB_PATH.'/framework/functions.php');

// Insert an extra row into the database
$database->query("INSERT INTO ".TABLE_PREFIX."mod_miniform (`section_id`) VALUES ('$section_id')");

?>