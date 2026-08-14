-- Create table for Output Filter Dashboard module
DROP TABLE IF EXISTS `{TP}mod_outputfilter_dashboard`;
CREATE TABLE {TP}mod_outputfilter_dashboard (
    `id`                INT            NOT NULL AUTO_INCREMENT,
    `userfunc`          TINYINT        NOT NULL DEFAULT '0',
    `position`          INT            NOT NULL DEFAULT '0',
    `active`            TINYINT        NOT NULL DEFAULT '1',
    `allowedit`         TINYINT        NOT NULL DEFAULT '0',
    `allowedittarget`   TINYINT        NOT NULL DEFAULT '0',
    `name`              VARCHAR(249)   NOT NULL,
    `func`              TEXT           NOT NULL,
    `type`              VARCHAR(255)   NOT NULL,
    `file`              VARCHAR(255)   NOT NULL,
    `csspath`           VARCHAR(255)   NOT NULL,
    `funcname`          VARCHAR(255)   NOT NULL,
    `configurl`         VARCHAR( 255 ) NOT NULL,
    `plugin`            VARCHAR( 255 ) NOT NULL,
    `helppath`          TEXT           NOT NULL,
    `modules`           TEXT           NOT NULL,
    `desc`              LONGTEXT       NOT NULL,
    `pages`             TEXT           NOT NULL,
    `pages_parent`      TEXT           NOT NULL,
    `additional_values` LONGTEXT       NOT NULL,
    `additional_fields` TEXT           NOT NULL,
    `additional_fields_languages` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`name`),
    INDEX (`type`)
) ENGINE = InnoDB;
