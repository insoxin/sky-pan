# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-09 23:51:38
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "sk_folders"
#

DROP TABLE IF EXISTS `sk_folders`;
CREATE TABLE `sk_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '目录所有人',
  `shares_id` int(11) NOT NULL DEFAULT '0' COMMENT '分享id',
  `folder_name` text NOT NULL COMMENT '目录名称',
  `parent_folder` int(11) NOT NULL DEFAULT '0' COMMENT '上级目录',
  `position` text NOT NULL COMMENT '路径',
  `position_absolute` text NOT NULL COMMENT '绝对路径',
  `desc` text COMMENT '描述',
  `count_down` int(11) NOT NULL DEFAULT '0' COMMENT '下载统计',
  `count_open` int(11) NOT NULL DEFAULT '0' COMMENT '浏览统计',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` timestamp NULL DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='文件夹';

#
# Data for table "sk_folders"
#

/*!40000 ALTER TABLE `sk_folders` DISABLE KEYS */;
INSERT INTO `sk_folders` VALUES (1,4,0,'根目录',0,'.','/',NULL,0,0,1631186682,0,NULL),(6,4,0,'源码文件',1,'','','',0,0,1631201152,0,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `sk_folders` ENABLE KEYS */;

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
INSERT INTO `sk_groups` VALUES (1,'管理员',1,10737418240,'10',1,1),(2,'游客',1,10737418240,'',1,1),(3,'普通用户',1,104857600,'10',1,1);
/*!40000 ALTER TABLE `sk_groups` ENABLE KEYS */;

#
# Structure for table "sk_policys"
#

DROP TABLE IF EXISTS `sk_policys`;
CREATE TABLE `sk_policys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL COMMENT '上传策略名称',
  `type` varchar(60) NOT NULL DEFAULT '' COMMENT '上传策略类型',
  `filetype` text COMMENT '允许上传的类型',
  `max_size` bigint(20) NOT NULL DEFAULT '0' COMMENT '单文件最大大小',
  `config` text COMMENT '配置信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='储存策略';

#
# Data for table "sk_policys"
#

/*!40000 ALTER TABLE `sk_policys` DISABLE KEYS */;
INSERT INTO `sk_policys` VALUES (1,'默认存储','local','',10485760,'{\"save_dir\":\"\\/default\\/\",\"access_token\":\"\",\"server_uri\":\"\"}'),(3,'远程服务器一','remote','',512000,'{\"save_dir\":\"\\/uploads\\/\",\"access_token\":\"asdasfasfasfasfasfa\",\"server_uri\":\"http:\\/\\/dev.com\\/\"}');
/*!40000 ALTER TABLE `sk_policys` ENABLE KEYS */;

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

#
# Data for table "sk_setting"
#

/*!40000 ALTER TABLE `sk_setting` DISABLE KEYS */;
INSERT INTO `sk_setting` VALUES (1,'site_name','闪客网盘 | 分享赚钱,让资源有价值!','basic'),(2,'site_keywords','闪客网盘 | 闪客云盘 | 网络云盘 | 网盘联盟 | 网赚网盘 | 云盘 | 云存储','basic'),(3,'site_desc','闪客云盘是一款速度快、不打扰、够安全、易于分享的网络云盘,提供了分享下载分成功能,分享赚钱,让资源变的有收益有价值!','basic'),(4,'site_head','','basic'),(5,'site_foot','','basic'),(6,'allow_register','1','register'),(7,'login_captcha','1','register'),(8,'default_group','3','register'),(9,'register_captcha','1','register'),(10,'forget_captcha','0','register'),(11,'site_title','SK网盘','basic'),(12,'site_logo','/assets/logo/logo.gif','basic');
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
# Structure for table "sk_stores"
#

DROP TABLE IF EXISTS `sk_stores`;
CREATE TABLE `sk_stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '所属用户',
  `shares_id` int(11) NOT NULL DEFAULT '0' COMMENT '分享id',
  `origin_name` varchar(255) NOT NULL DEFAULT '' COMMENT '源文件名',
  `file_name` text NOT NULL COMMENT '存储文件名',
  `size` bigint(20) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `meta` text NOT NULL COMMENT '文件元属性',
  `mime_type` varchar(60) NOT NULL DEFAULT '' COMMENT '文件类型',
  `ext` varchar(60) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `parent_folder` int(11) NOT NULL DEFAULT '0' COMMENT '所属目录',
  `policy_id` int(11) NOT NULL DEFAULT '0' COMMENT '存储策略',
  `count_down` int(11) NOT NULL DEFAULT '0' COMMENT '下载量',
  `count_open` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `dir` text NOT NULL COMMENT '文件目录',
  `desc` text COMMENT '文件描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` timestamp NULL DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='储存文件';

#
# Data for table "sk_stores"
#

/*!40000 ALTER TABLE `sk_stores` DISABLE KEYS */;
INSERT INTO `sk_stores` VALUES (1,4,0,'navi2.ico','20210909\\4\\file_613a2671e5faf1631200881.ico',117271,'','image/x-icon','ico',1,1,0,0,'',NULL,1631200881,1631200881,'0000-00-00 00:00:00'),(3,4,0,'app.ico','20210909\\4\\file_613a2833432371631201331.ico',114595,'','image/x-icon','ico',6,1,0,0,'',NULL,1631201331,1631201331,NULL);
/*!40000 ALTER TABLE `sk_stores` ENABLE KEYS */;

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
  `login_real_ip` varchar(60) NOT NULL DEFAULT '',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户状态 1 正常 0封禁',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "sk_users"
#

/*!40000 ALTER TABLE `sk_users` DISABLE KEYS */;
INSERT INTO `sk_users` VALUES (1,'admin','管理员','admin@skpan.net','3e9464c59cc03ef5f3c5ed555e2757e2',NULL,0.00,1,0,NULL,1631009197,0,'',1),(4,'1655545174','雷霆嘎巴','1655545174@qq.com','99c0342da49856be2e9f6fbf55116e57',NULL,0.00,3,0,NULL,1631186682,0,'',1);
/*!40000 ALTER TABLE `sk_users` ENABLE KEYS */;
