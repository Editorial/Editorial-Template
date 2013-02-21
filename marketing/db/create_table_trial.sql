CREATE TABLE `trial` (
  `trial` char(5) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar( 10 ) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trial`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ALTER TABLE  `trial` ADD  `password` VARCHAR( 10 ) NOT NULL AFTER  `email`;