# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-06 17:24:58
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "sk_admin"
#

DROP TABLE IF EXISTS `sk_admin`;
CREATE TABLE `sk_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员表';

#
# Data for table "sk_admin"
#

/*!40000 ALTER TABLE `sk_admin` DISABLE KEYS */;
INSERT INTO `sk_admin` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3',1630909184);
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户组';

#
# Data for table "sk_groups"
#

/*!40000 ALTER TABLE `sk_groups` DISABLE KEYS */;
INSERT INTO `sk_groups` VALUES (1,'管理员',0,10737418240,'10',1,0),(2,'管理员',0,10737418240,'',1,0);
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
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱账号',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `salt` varchar(6) NOT NULL DEFAULT '' COMMENT '密码盐',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `grade` int(11) NOT NULL DEFAULT '0' COMMENT '是否为会员',
  `is_auth` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否实名认证',
  `grade_time` int(11) NOT NULL DEFAULT '0' COMMENT '会员到期时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户状态 1 正常 0封禁',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "sk_user"
#

/*!40000 ALTER TABLE `sk_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_user` ENABLE KEYS */;
