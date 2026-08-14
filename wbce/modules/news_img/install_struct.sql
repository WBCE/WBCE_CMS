-- Create tables for News with Images module
CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_posts` (
    `post_id`         INT          NOT NULL AUTO_INCREMENT,
    `section_id`      INT          NOT NULL DEFAULT '0',
    `group_id`        INT          NOT NULL DEFAULT '0',
    `active`          INT          NOT NULL DEFAULT '0',
    `position`        INT          NOT NULL DEFAULT '0',
    `title`           VARCHAR(255) NOT NULL DEFAULT '',
    `link`            TEXT         NOT NULL ,
    `image`           VARCHAR(256) NOT NULL DEFAULT '',
    `content_short`   TEXT         NOT NULL ,
    `content_long`    TEXT         NOT NULL ,
    `content_block2`  TEXT         NOT NULL ,
    `published_when`  INT          NOT NULL DEFAULT '0',
    `published_until` INT          NOT NULL DEFAULT '0',
    `posted_when`     INT          NOT NULL DEFAULT '0',
    `posted_by`       INT          NOT NULL DEFAULT '0',
    PRIMARY KEY (post_id)
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_groups` (
    `group_id`   INT          NOT NULL AUTO_INCREMENT,
    `section_id` INT          NOT NULL DEFAULT '0',
    `active`     INT          NOT NULL DEFAULT '0',
    `position`   INT          NOT NULL DEFAULT '0',
    `title`      VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (group_id)
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_settings` (
    `section_id`       INT         NOT NULL DEFAULT '0',
    `header`           TEXT        NOT NULL ,
    `post_loop`        TEXT        NOT NULL ,
    `view_order`       INT         NOT NULL DEFAULT '0',
    `footer`           TEXT        NOT NULL ,
    `block2`           TEXT        NOT NULL ,
    `posts_per_page`   INT         NOT NULL DEFAULT '0',
    `post_header`      TEXT        NOT NULL,
    `post_content`     TEXT        NOT NULL,
    `image_loop`       TEXT        NOT NULL,
    `post_footer`      TEXT        NOT NULL,
    `resize_preview`   VARCHAR(50) NULL,
    `crop_preview`     CHAR(1)     NOT NULL DEFAULT 'N',
    `gallery`          TEXT        NOT NULL,
    `imgthumbsize`     VARCHAR(50) NULL     DEFAULT NULL,
    `imgmaxwidth`      VARCHAR(50) NULL     DEFAULT NULL,
    `imgmaxheight`     VARCHAR(50) NULL     DEFAULT NULL,
    `imgmaxsize`       VARCHAR(50) NULL     DEFAULT NULL,
    `use_second_block` CHAR(1) NOT NULL     DEFAULT 'N',
    `view`             VARCHAR(50) NOT NULL DEFAULT 'default',
    `mode`             VARCHAR(50) NULL     DEFAULT 'default',
    `show_settings_only_admins` CHAR(1) NOT NULL DEFAULT 'N',
    PRIMARY KEY (section_id)
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_img` (
    `id`       INT          NOT NULL AUTO_INCREMENT,
    `picname`  VARCHAR(255) NOT NULL DEFAULT '',
    `picdesc`  VARCHAR(255) NOT NULL DEFAULT '',
    `post_id`  INT          NOT NULL DEFAULT '0',
    `position` INT(11)      NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_posts_img` (
    `post_id`   INT(11) NOT NULL,
    `pic_id`    INT(11) NOT NULL,
    `position`  INT(11) NOT NULL,
    UNIQUE KEY `post_id_pic_id` (`post_id`,`pic_id`),
    KEY `FK_{TP}mod_news_img_posts_img_{TP}mod_news_img_img` (`pic_id`),
    CONSTRAINT `FK_{TP}mod_news_img_posts_img_{TP}mod_news_img_img` FOREIGN KEY (`pic_id`) REFERENCES `{TP}mod_news_img_img` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_{TP}mod_news_img_posts_img_{TP}mod_news_img_posts` FOREIGN KEY (`post_id`) REFERENCES `{TP}mod_news_img_posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_tags` (
    `tag_id`    INT(11) NOT NULL AUTO_INCREMENT,
    `tag`       VARCHAR(255) NOT NULL,
    `tag_color` VARCHAR(7) NULL DEFAULT NULL,
    PRIMARY KEY (`tag_id`)
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_tags_posts` (
    `post_id`  INT(11) NOT NULL,
    `tag_id`   INT(11) NOT NULL,
    UNIQUE KEY `post_id_tag_id` (`post_id`,`tag_id`)
    ) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{TP}mod_news_img_tags_sections` (
    `section_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `tag_id`     INT(11) UNSIGNED NOT NULL,
    UNIQUE INDEX `section_id_tag_id` (`section_id`, `tag_id`)
    ) ENGINE=InnoDB;
