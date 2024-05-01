CREATE TABLE `4_universities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Athlete Name` varchar(255) DEFAULT NULL,
  `JERSEY` int(11) DEFAULT NULL,
  `Position` varchar(4) DEFAULT NULL,
  `HEIGHT` varchar(16) DEFAULT NULL,
  `WEIGHT` int(11) DEFAULT NULL,
  `YR` varchar(2) DEFAULT NULL,
  `AGE` int(11) DEFAULT NULL,
  `HIGH SCHOOL` varchar(255) DEFAULT NULL,
  `RATING` float DEFAULT NULL,
  `University Name` varchar(255) DEFAULT NULL,
  `Skin Tone` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=828 DEFAULT CHARSET=utf8;