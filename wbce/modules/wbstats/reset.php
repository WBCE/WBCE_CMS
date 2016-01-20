<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         0.1.9
 * @lastmodified    January 11, 2016
 * @file reset      Krzysztof Winnicki
 * 
 */

if (isset($_GET['rstc'])) {

require('../../config.php');
  
$table_day   = TABLE_PREFIX .'mod_wbstats_day';
$table_ips   = TABLE_PREFIX .'mod_wbstats_ips';
$table_pages = TABLE_PREFIX .'mod_wbstats_pages';
$table_ref   = TABLE_PREFIX .'mod_wbstats_ref';
$table_key   = TABLE_PREFIX .'mod_wbstats_keywords';
$table_lang  = TABLE_PREFIX .'mod_wbstats_lang';

$database->query("TRUNCATE TABLE " .$table_day); 
$database->query("TRUNCATE TABLE " .$table_ips);
$database->query("TRUNCATE TABLE " .$table_pages);
$database->query("TRUNCATE TABLE " .$table_ref);
$database->query("TRUNCATE TABLE " .$table_key);
$database->query("TRUNCATE TABLE " .$table_lang);

$module_history_link = ADMIN_URL .'/admintools/tool.php?tool=wbstats&history';  
header("Location: ".$module_history_link); 
exit();
} else {
	die(header('Location: index.php'));
} 
?>