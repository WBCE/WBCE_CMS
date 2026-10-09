-- Version-history table for the wbce_history TinyMCE plugin.
-- One row per editor instance; `versions` holds a rotating JSON array
-- (newest first, max N) of { ts, user_id, user_name, content }.
CREATE TABLE IF NOT EXISTS `{TP}mod_tinymce_history` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `instance_key`  VARCHAR(191) NOT NULL,
    `context`       VARCHAR(255) NOT NULL DEFAULT '',
    `versions`      LONGTEXT NOT NULL,
    `updated_at`    DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_instance` (`instance_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
