<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / Dev4me
 * @link			http://www.dev4me.nl/modules-snippets/opensource/miniform/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.10.0
 * @lastmodified    april 10, 2017
 *
 */


// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$database->query("DROP TABLE ".TABLE_PREFIX."mod_miniform");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_miniform_data");


?>