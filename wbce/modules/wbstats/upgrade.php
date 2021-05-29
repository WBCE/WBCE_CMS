<?php
/**
 *
 * @category        admintools
 * @package         wbstats
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x / WBCE 1.4
 * @requirements    PHP 5.6 and higher
 * @version         0.2.1
 * @lastmodified    November 15, 2019
 *
 */

defined('WB_PATH') or die(header('Location: ../index.php'));

function _wbs_db_add_field($field, $table, $desc)
{
    global $database;
    $table = TABLE_PREFIX.$table;
    $database->field_add($table, $field, $desc, false);
}

$database->query("ALTER TABLE `".TABLE_PREFIX."mod_wbstats_ips` MODIFY `ip` VARCHAR(32)");

_wbs_db_add_field("refspam", "mod_wbstats_day", "int(1) NOT NULL default '0' AFTER `view`");
_wbs_db_add_field("spam", "mod_wbstats_ref", "int(1) NOT NULL default '0' AFTER `view`");
_wbs_db_add_field("last_page", "mod_wbstats_ips", "varchar(512) NOT NULL default '' AFTER `page`");
_wbs_db_add_field("pages", "mod_wbstats_ips", "int(11) NOT NULL default '0' AFTER `last_page`");
