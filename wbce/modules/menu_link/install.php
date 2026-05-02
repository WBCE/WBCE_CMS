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

$database->query("DROP TABLE IF EXISTS {TP}mod_menu_link");
$table = "
    CREATE TABLE IF NOT EXISTS {TP}mod_menu_link (
        `section_id`      INT(11)        NOT NULL   DEFAULT '0',
        `page_id`         INT(11)        NOT NULL   DEFAULT '0',
        `target_page_id`  INT(11)        NOT NULL   DEFAULT '0',
        `redirect_type`   INT(3)         NOT NULL   DEFAULT '301',
        `anchor`          VARCHAR(255)   NOT NULL   DEFAULT '0' ,
        `extern`          VARCHAR(255)   NOT NULL   DEFAULT '' ,
        PRIMARY KEY (`section_id`)
    ) {TABLE_ENGINE}  {TABLE_COLLATION};
";
$database->importSql($table);
