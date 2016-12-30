CREATE TABLE `img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `horse_id` int(11) DEFAULT NULL,
  `big` varchar(255) DEFAULT NULL,
  `small` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;