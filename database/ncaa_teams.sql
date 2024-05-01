CREATE TABLE `ncaa_teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `short_name` varchar(32) DEFAULT NULL,
  `color` varchar(8) DEFAULT NULL,
  `alternate_color` varchar(8) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_all_star` tinyint(1) DEFAULT NULL,
  `venue_name` varchar(64) DEFAULT NULL,
  `venue_city` varchar(64) DEFAULT NULL,
  `venue_state` varchar(64) DEFAULT NULL,
  `venue_zip_code` int(11) DEFAULT NULL,
  `venue_capacity` int(11) DEFAULT NULL,
  `venue_grass` tinyint(1) DEFAULT NULL,
  `venue_indoor` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127955 DEFAULT CHARSET=utf8;