-- Create table for WYSIWYG module
CREATE TABLE IF NOT EXISTS `{TP}mod_wysiwyg` (
  `section_id` INT       NOT NULL DEFAULT 0,
  `page_id`    INT       NOT NULL DEFAULT 0,
  `content`    LONGTEXT  NOT NULL,
  `text`       LONGTEXT  NOT NULL,
  PRIMARY KEY (`section_id`)
){TABLE_ENGINE};
