# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-04 17:00:18
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
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '目录所有人',
  `folder_name` text NOT NULL COMMENT '目录名称',
  `parent_folder` int(11) NOT NULL DEFAULT '0' COMMENT '上级目录',
  `position` text NOT NULL COMMENT '路径',
  `position_absolute` text NOT NULL COMMENT '绝对路径',
  `desc` text COMMENT '文件夹描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件夹';

#
# Data for table "sk_folder"
#

/*!40000 ALTER TABLE `sk_folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_folder` ENABLE KEYS */;

#
# Structure for table "sk_policy"
#

DROP TABLE IF EXISTS `sk_policy`;
CREATE TABLE `sk_policy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='储存策略';

#
# Data for table "sk_policy"
#

/*!40000 ALTER TABLE `sk_policy` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_policy` ENABLE KEYS */;

#
# Structure for table "sk_shares"
#

DROP TABLE IF EXISTS `sk_shares`;
CREATE TABLE `sk_shares` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享数据';

#
# Data for table "sk_shares"
#

/*!40000 ALTER TABLE `sk_shares` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_shares` ENABLE KEYS */;

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
