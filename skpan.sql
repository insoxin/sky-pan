# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-07 19:54:36
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

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
# Structure for table "sk_groups"
#

DROP TABLE IF EXISTS `sk_groups`;
CREATE TABLE `sk_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(120) DEFAULT '' COMMENT '用户组名称',
  `policy_id` int(11) NOT NULL DEFAULT '0' COMMENT '存储策略ID',
  `max_storage` bigint(20) NOT NULL DEFAULT '0' COMMENT '最大存储',
  `speed` varchar(20) DEFAULT NULL COMMENT '下载限速',
  `allow_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '允许分享',
  `is_sys` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为系统用户组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户组';

#
# Data for table "sk_groups"
#

/*!40000 ALTER TABLE `sk_groups` DISABLE KEYS */;
INSERT INTO `sk_groups` VALUES (1,'管理员',0,10737418240,'10',1,1),(2,'游客',0,10737418240,'',1,1),(3,'普通用户',0,104857600,'10',1,1);
/*!40000 ALTER TABLE `sk_groups` ENABLE KEYS */;

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
# Structure for table "sk_setting"
#

DROP TABLE IF EXISTS `sk_setting`;
CREATE TABLE `sk_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_name` varchar(255) DEFAULT NULL COMMENT '配置名称',
  `set_value` text NOT NULL COMMENT '配置值',
  `set_type` varchar(60) NOT NULL DEFAULT '' COMMENT '配置类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';

#
# Data for table "sk_setting"
#

/*!40000 ALTER TABLE `sk_setting` DISABLE KEYS */;
INSERT INTO `sk_setting` VALUES (1,'site_name','','basic'),(2,'site_keywords','','basic'),(3,'site_desc','','basic'),(4,'site_head','','basic'),(5,'site_foot','','basic');
/*!40000 ALTER TABLE `sk_setting` ENABLE KEYS */;

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
# Structure for table "sk_users"
#

DROP TABLE IF EXISTS `sk_users`;
CREATE TABLE `sk_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(60) DEFAULT NULL COMMENT '用户昵称',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱账号',
  `password` varchar(40) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `group` int(11) NOT NULL DEFAULT '0' COMMENT '用户组',
  `is_auth` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否实名认证',
  `wx_openid` varchar(64) DEFAULT NULL COMMENT '微信开放平台ID',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户状态 1 正常 0封禁',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "sk_users"
#

/*!40000 ALTER TABLE `sk_users` DISABLE KEYS */;
INSERT INTO `sk_users` VALUES (1,'admin','管理员','admin@skpan.net','3e9464c59cc03ef5f3c5ed555e2757e2',NULL,0.00,1,0,NULL,1631009197,0,1);
/*!40000 ALTER TABLE `sk_users` ENABLE KEYS */;
