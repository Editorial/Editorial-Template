CREATE TABLE `trial` (
  `trial` char(5) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trial`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;