-- Create table for Menu Link module
DROP TABLE IF EXISTS `{TP}mod_menu_link`;
CREATE TABLE IF NOT EXISTS `{TP}mod_menu_link` (
    `section_id`      INT(11)        NOT NULL   DEFAULT '0',
    `page_id`         INT(11)        NOT NULL   DEFAULT '0',
    `target_page_id`  INT(11)        NOT NULL   DEFAULT '0',
    `redirect_type`   INT(3)         NOT NULL   DEFAULT '301',
    `anchor`          VARCHAR(255)   NOT NULL   DEFAULT '0' ,
    `extern`          VARCHAR(255)   NOT NULL   DEFAULT '' ,
    PRIMARY KEY (`section_id`)
) {TABLE_ENGINE}  {TABLE_COLLATION};
