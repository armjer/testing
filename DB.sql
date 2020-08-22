CREATE TABLE `task_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_user_book_user_id` (`user_id`),
  CONSTRAINT `fk_user_book_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `task_book` */

insert  into `task_book`(`id`,`user_id`,`email`,`text`,`image`,`status`) values (11,23,'test@mail.ru','test test test','slide.png',1),(14,23,'gayanar1972@mail.ru','jklljklj','site-logo.png',1);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthdate` timestamp NULL DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`email`,`username`,`password`,`firstname`,`lastname`,`gender`,`birthdate`,`picture`,`admin`) values (22,'test@mail.ru','test','901318b7998f12f8b70a37d408cd262b79a223f7','test','test','1','1996-05-31 00:00:00','',0),(23,'admin@mail.ru','admin','40bd001563085fc35165329ea1ff5c5ecbdbbeef','Miqayel','Ishkhanyan','1','1996-05-31 00:00:00','',1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oauth_provider` varchar(10) DEFAULT NULL,
  `oauth_uid` text,
  `username` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`oauth_provider`,`oauth_uid`,`username`) values (1,'11','1','111');

/*Table structure for table `users_online` */

DROP TABLE IF EXISTS `users_online`;

CREATE TABLE `users_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users_online` */

insert  into `users_online`(`id`,`id_user`,`date`) values (1,18,'2020-08-22 10:33:21'),(2,15,'2020-08-22 10:24:18'),(3,17,'2020-08-22 10:14:18'),(4,20,'2020-08-22 10:13:23'),(5,21,'2020-08-22 10:08:00');