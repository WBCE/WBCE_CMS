-- install WBCE CMS system tables
-- 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
--
-- `addons`
CREATE TABLE IF NOT EXISTS `{TP}addons` (
  `addon_id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `type`         VARCHAR(255) NOT NULL DEFAULT '',
  `directory`    VARCHAR(255) NOT NULL DEFAULT '',
  `name`         VARCHAR(255) NOT NULL DEFAULT '',
  `description`  TEXT         NOT NULL,
  `function`     VARCHAR(255) NOT NULL DEFAULT '',
  `version`      VARCHAR(255) NOT NULL DEFAULT '',
  `platform`     VARCHAR(255) NOT NULL DEFAULT '',
  `author`       VARCHAR(255) NOT NULL DEFAULT '',
  `license`      VARCHAR(255) NOT NULL DEFAULT '',
-- new cols since 1.7.0 (core, updated_when, updated_by)
  `core`         TINYINT(1)   NOT NULL DEFAULT 0,
  `updated_when` INT(11)      NOT NULL DEFAULT 0,
  `updated_by`   INT(11)      NOT NULL DEFAULT 0,
  PRIMARY KEY (`addon_id`)
){TABLE_ENGINE};
-- 
-- `groups`
CREATE TABLE IF NOT EXISTS `{TP}groups` (
  `group_id`             INT(11)      NOT NULL AUTO_INCREMENT,
  `name`                 VARCHAR(255) NOT NULL DEFAULT '',
  `system_permissions`   TEXT         NOT NULL,
  `module_permissions`   TEXT         NOT NULL,
  `template_permissions` TEXT         NOT NULL,
  PRIMARY KEY (`group_id`)
){TABLE_ENGINE};
-- 
-- `pages`
CREATE TABLE IF NOT EXISTS `{TP}pages` (
  `page_id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `parent`            INT(11)      NOT NULL DEFAULT '0',
  `root_parent`       INT(11)      NOT NULL DEFAULT '0',
  `level`             INT(11)      NOT NULL DEFAULT '0',
  `link`              TEXT         NOT NULL,
  `slug`              VARCHAR(255)     NULL DEFAULT NULL,
  `target`            VARCHAR(7)   NOT NULL DEFAULT '',
  `page_title`        VARCHAR(255) NOT NULL DEFAULT '',
  `menu_title`        VARCHAR(255) NOT NULL DEFAULT '',
  `description`       TEXT         NOT NULL,
  `keywords`          TEXT         NOT NULL,
  `page_trail`        TEXT         NOT NULL,
  `template`          VARCHAR(255) NOT NULL DEFAULT '',
  `visibility`        VARCHAR(255) NOT NULL DEFAULT '',
  `visibility_backup` VARCHAR(255) NOT NULL DEFAULT '',
  `position`          INT(11)      NOT NULL DEFAULT '0',
  `menu`              INT(11)      NOT NULL DEFAULT '0',
  `language`          VARCHAR(5)   NOT NULL DEFAULT '',
  `searching`         INT(11)      NOT NULL DEFAULT '0',
  `admin_groups`      TEXT         NOT NULL,
  `admin_users`       TEXT         NOT NULL,
  `viewing_groups`    TEXT         NOT NULL,
  `viewing_users`     TEXT         NOT NULL,
  `modified_when`     INT(11)      NOT NULL DEFAULT '0',
  `modified_by`       INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
-- use CONSTRAINT to be idempotent for other SQL dialects
  CONSTRAINT `uniq_slug` UNIQUE (`slug`)
){TABLE_ENGINE};
-- 
-- `search`
CREATE TABLE IF NOT EXISTS `{TP}search` (
  `search_id` INT(11)       NOT NULL AUTO_INCREMENT,
  `name`      VARCHAR(255)  NOT NULL DEFAULT '',
  `value`     TEXT          NOT NULL,
  `extra`     TEXT          NOT NULL,
  PRIMARY KEY (`search_id`)
){TABLE_ENGINE};
--
-- `sections`
CREATE TABLE IF NOT EXISTS `{TP}sections` (
  `section_id`  INT(11)      NOT NULL AUTO_INCREMENT,
  `page_id`     INT(11)      NOT NULL DEFAULT '0',
  `position`    INT(11)      NOT NULL DEFAULT '0',
  `module`      VARCHAR(255) NOT NULL DEFAULT '',
  `block`       VARCHAR(255) NOT NULL DEFAULT '',
  `publ_start`  VARCHAR(255) NOT NULL DEFAULT '0',
  `publ_end`    VARCHAR(255) NOT NULL DEFAULT '0',
  `namesection` VARCHAR(255) NULL,
  PRIMARY KEY (`section_id`)
){TABLE_ENGINE};
--
-- `settings`
CREATE TABLE IF NOT EXISTS `{TP}settings` (
  `setting_id` INT(11)       NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255)  NOT NULL DEFAULT '',
  `value`      TEXT          NOT NULL,
  PRIMARY KEY (`setting_id`)
){TABLE_ENGINE};
--
-- `users` (new fields since WBCE v.1.4.0)
CREATE TABLE IF NOT EXISTS `{TP}users` (
  `user_id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `group_id`           INT(11)      NOT NULL DEFAULT '0',
  `groups_id`          VARCHAR(255) NOT NULL DEFAULT '0',
  `username`           VARCHAR(255) NOT NULL DEFAULT '',
  `display_name`       VARCHAR(255) NOT NULL DEFAULT '',
  `language`           VARCHAR(5)   NOT NULL DEFAULT 'DE',
  `email`              TEXT         NOT NULL,
  `signup_checksum`    VARCHAR(64)  NOT NULL DEFAULT '',
  `active`             INT(11)      NOT NULL DEFAULT '0',
  `gdpr_check`         INT(1)       NOT NULL DEFAULT '0',  
  `password`           VARCHAR(255) NOT NULL DEFAULT '',
  `remember_key`       VARCHAR(255) NOT NULL DEFAULT '',
  `last_reset`         INT(11)      NOT NULL DEFAULT '0',
  `timezone`           VARCHAR(11)  NOT NULL DEFAULT '',
  `date_format`        VARCHAR(255) NOT NULL DEFAULT '',
  `time_format`        VARCHAR(255) NOT NULL DEFAULT '',
  `home_folder`        TEXT         NOT NULL,
  `login_when`         INT(11)      NOT NULL DEFAULT '0',
  `login_ip`           VARCHAR(50)  NOT NULL DEFAULT '',
  `signup_timestamp`   INT(11)      NOT NULL DEFAULT '0',
  `signup_timeout`     INT(11)      NOT NULL DEFAULT '0',
  `signup_confirmcode` VARCHAR(64)  NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
){TABLE_ENGINE};
--
-- `blocking` (new table since WBCE v.1.4.0)
CREATE TABLE IF NOT EXISTS `{TP}blocking` (
  `source_ip` VARCHAR(50)  NOT NULL DEFAULT '',
  `timestamp` INT(11)      NOT NULL DEFAULT '0',
  `attempts`  INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (`source_ip`)
){TABLE_ENGINE};