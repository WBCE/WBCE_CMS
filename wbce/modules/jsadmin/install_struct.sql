-- Create table for JsAdmin module
DROP TABLE IF EXISTS `{TP}mod_jsadmin`;
CREATE TABLE IF NOT EXISTS `{TP}mod_jsadmin` (
    `id`    INT(11)      NOT NULL DEFAULT '0',
    `name`  VARCHAR(255) NOT NULL DEFAULT '0',
    `value` INT(11)      NOT NULL DEFAULT '0',
    PRIMARY KEY ( `id` )
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
