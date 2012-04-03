CREATE TABLE IF NOT EXISTS `chat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `id_user` bigint(20) NOT NULL,
  `create_date` datetime NOT NULL,
  `uri` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `twitter_id` varchar(64) DEFAULT NULL,
  `photo` tinyint(1) DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_chat` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_chat` bigint(20) NOT NULL,
  `id_user` bigint(20) NOT NULL,
  `open_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_chat` (`id_chat`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
