-- Create table for Droplets module
DROP TABLE IF EXISTS `{TP}mod_droplets`;
CREATE TABLE IF NOT EXISTS `{TP}mod_droplets` (
    `id`            INT NOT NULL auto_increment,
    `name`          VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL,
    `code`          LONGTEXT    CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL ,
    `description`   TEXT        CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL,
    `modified_when` INT NOT NULL default '0',
    `modified_by`   INT NOT NULL default '0',
    `active`        INT NOT NULL default '0',
    `admin_edit`    INT NOT NULL default '0',
    `admin_view`    INT NOT NULL default '0',
    `show_wysiwyg`  INT NOT NULL default '0',
    `comments`      TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci  NOT NULL,
    PRIMARY KEY ( `id` )
    )
    ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
