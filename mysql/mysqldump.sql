-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_name` varchar(50) NOT NULL,
  `allow_mails` tinyint(4) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,0) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `organization_form_id` int(10) unsigned NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ads` (`id`, `seller_name`, `allow_mails`, `phone`, `title`, `description`, `price`, `location_id`, `category_id`, `organization_form_id`, `email`) VALUES
(1,	'Моё имя',	1,	'+7895498494',	'asdf',	'',	0,	9,	12,	0,	'Моя почта@mail.ru');

-- 2015-06-07 11:58:17
