-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `full_page_text`;
CREATE TABLE `full_page_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_text` varchar(150) NOT NULL,
  `link` varchar(250) NOT NULL,
  `bg_color` varchar(7) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `status` enum('ok','del') NOT NULL DEFAULT 'ok',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2016-06-16 18:48:17
