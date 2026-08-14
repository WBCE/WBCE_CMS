-- Create table for Wrapper module
CREATE TABLE IF NOT EXISTS `{TP}mod_wrapper` (
    `section_id` INT  NOT NULL DEFAULT '0',
    `page_id`    INT  NOT NULL DEFAULT '0',
    `url`        TEXT NOT NULL,
    `height`     INT  NOT NULL DEFAULT '0',
    PRIMARY KEY ( `section_id` )
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
