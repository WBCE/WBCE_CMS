-- prepare the database to install fresh WBCE CMS tables
--
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- 
-- Drop tables
DROP TABLE IF EXISTS `{TP}addons`;
DROP TABLE IF EXISTS `{TP}groups`;
DROP TABLE IF EXISTS `{TP}pages`;
DROP TABLE IF EXISTS `{TP}search`;
DROP TABLE IF EXISTS `{TP}sections`;
DROP TABLE IF EXISTS `{TP}settings`;
DROP TABLE IF EXISTS `{TP}users`;
DROP TABLE IF EXISTS `{TP}blocking`;