CREATE TABLE IF NOT EXISTS `promo` (
  `promo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `code` varchar(20) NOT NULL,
  `discount` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `date_valid` datetime NOT NULL,
  `used` int(10) unsigned NOT NULL,
  PRIMARY KEY (`promo_id`),
  KEY `code` (`code`),
  KEY `count` (`count`),
  KEY `date_valid` (`date_valid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;