-- Create tables for MiniForm module
DROP TABLE IF EXISTS `{TP}mod_miniform`;
CREATE TABLE IF NOT EXISTS `{TP}mod_miniform` (
     `section_id` INT NOT NULL DEFAULT '0',
     `email` VARCHAR(128) NOT NULL DEFAULT '',
     `emailfrom` VARCHAR(128) NOT NULL DEFAULT '',
     `subject` VARCHAR(255) NOT NULL DEFAULT '',
     `confirm_user` INT NOT NULL DEFAULT '0',
     `confirm_subject` VARCHAR(255) NOT NULL DEFAULT '',
     `template` VARCHAR(64) NOT NULL DEFAULT 'form',
     `successpage` INT NOT NULL DEFAULT '0',
     `use_ajax` INT NOT NULL DEFAULT '1',
     `use_recaptcha` INT NOT NULL DEFAULT '0',
     `recaptcha_key` VARCHAR(64) NOT NULL DEFAULT '',
     `recaptcha_secret` VARCHAR(64) NOT NULL DEFAULT '',
     `remote_id` VARCHAR(64) NOT NULL DEFAULT '',
     `remote_name` VARCHAR(64) NOT NULL DEFAULT '',
     `disable_tls` INT NOT NULL DEFAULT '0',
     `no_store` INT NOT NULL DEFAULT '0',
     PRIMARY KEY ( `section_id` )
     )
    COLLATE='utf8_unicode_ci'
    ENGINE=MyISAM;

DROP TABLE IF EXISTS `{TP}mod_miniform_data`;
CREATE TABLE IF NOT EXISTS `{TP}mod_miniform_data` (
     `message_id` INT NOT NULL auto_increment,
     `section_id` INT NOT NULL DEFAULT '0',
     `user_id` INT NOT NULL DEFAULT '0',
     `data` MEDIUMTEXT NOT NULL,
     `guid` VARCHAR(64) NOT NULL DEFAULT '',
     `session_data` MEDIUMTEXT NOT NULL,
     `submitted_when` INT NOT NULL DEFAULT '0',
     PRIMARY KEY ( `message_id` )
     )
    COLLATE='utf8_unicode_ci'
    ENGINE=MyISAM;
