-- Create table for Sitemap module
CREATE TABLE IF NOT EXISTS `{TP}mod_sitemap` (
    `section_id`    INT          NOT NULL DEFAULT '0',
    `page_id`       INT          NOT NULL DEFAULT '0',
    `header`        TEXT         NOT NULL,
    `sitemaploop`   TEXT         NOT NULL,
    `footer`        TEXT         NOT NULL,
    `level_header`  TEXT         NOT NULL,
    `level_footer`  TEXT         NOT NULL,
    `static`        INT          NOT NULL DEFAULT '0',
    `startatroot`   INT          NOT NULL DEFAULT '0',
    `depth`         INT          NOT NULL DEFAULT '0',
    `show_hidden`   INT          NOT NULL DEFAULT '0',
    `show_settings` INT(1)       NOT NULL DEFAULT '1',
    `menus`         VARCHAR(30)  NOT NULL DEFAULT '0',
    `layout`        VARCHAR(128) NOT NULL DEFAULT '0',
    PRIMARY KEY ( `section_id` )
);
