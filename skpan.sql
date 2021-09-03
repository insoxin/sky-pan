# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-03 16:53:57
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "sk_admin"
#

DROP TABLE IF EXISTS `sk_admin`;
CREATE TABLE `sk_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';

#
# Data for table "sk_admin"
#

/*!40000 ALTER TABLE `sk_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_admin` ENABLE KEYS */;

#
# Structure for table "sk_folder"
#

DROP TABLE IF EXISTS `sk_folder`;
CREATE TABLE `sk_folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件夹';

#
# Data for table "sk_folder"
#

/*!40000 ALTER TABLE `sk_folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_folder` ENABLE KEYS */;

#
# Structure for table "sk_store"
#

DROP TABLE IF EXISTS `sk_store`;
CREATE TABLE `sk_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='储存文件';

#
# Data for table "sk_store"
#

/*!40000 ALTER TABLE `sk_store` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_store` ENABLE KEYS */;

#
# Structure for table "sk_strategy"
#

DROP TABLE IF EXISTS `sk_strategy`;
CREATE TABLE `sk_strategy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='储存策略';

#
# Data for table "sk_strategy"
#

/*!40000 ALTER TABLE `sk_strategy` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_strategy` ENABLE KEYS */;

#
# Structure for table "sk_user"
#

DROP TABLE IF EXISTS `sk_user`;
CREATE TABLE `sk_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "sk_user"
#

/*!40000 ALTER TABLE `sk_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_user` ENABLE KEYS */;
