# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-10 22:52:38
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
  `size` int(11) NOT NULL DEFAULT '0',
  `ext` int(11) NOT NULL DEFAULT '755',
  `count_down` int(11) NOT NULL DEFAULT '0' COMMENT '下载统计',
  `count_open` int(11) NOT NULL DEFAULT '0' COMMENT '浏览统计',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='文件夹';

#
# Data for table "sk_folders"
#

/*!40000 ALTER TABLE `sk_folders` DISABLE KEYS */;
INSERT INTO `sk_folders` VALUES (1,4,0,'根目录',0,'.','/',NULL,0,755,0,0,1631186682,0,NULL),(8,5,0,'根目录',0,'.','/',NULL,0,755,0,0,1631210019,0,NULL),(12,5,0,'ddd',8,'','','',0,755,0,0,1631283938,1631283938,NULL);
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户组';

#
# Data for table "sk_groups"
#

/*!40000 ALTER TABLE `sk_groups` DISABLE KEYS */;
INSERT INTO `sk_groups` VALUES (1,'管理员',1,1073741824,'',1,1),(2,'游客',1,0,'10',0,1),(3,'普通用户',1,1073741824,'10',1,1),(4,'VIP用户',1,5368709120,'',1,0);
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
INSERT INTO `sk_setting` VALUES (1,'site_name','闪客网盘 | 分享赚钱,让资源有价值!','basic'),(2,'site_keywords','闪客网盘 | 闪客云盘 | 网络云盘 | 网盘联盟 | 网赚网盘 | 云盘 | 云存储','basic'),(3,'site_desc','闪客云盘是一款速度快、不打扰、够安全、易于分享的网络云盘,提供了分享下载分成功能,分享赚钱,让资源变的有收益有价值!','basic'),(4,'site_head','','basic'),(5,'site_foot','','basic'),(6,'allow_register','1','register'),(7,'login_captcha','1','register'),(8,'default_group','3','register'),(9,'register_captcha','1','register'),(10,'forget_captcha','0','register'),(11,'site_title','SK网盘','basic'),(12,'site_logo','/assets/logo/logo.gif','basic'),(13,'api_gateway','http://www.eshanpay.com/submit.php','pay'),(14,'api_pid','10001','pay'),(15,'api_type','wxpay,alipay','pay'),(16,'api_key','aaaaaaaa','pay'),(17,'vip_group','4','vip'),(18,'vip_rule','周付VIP|3|7|7.0|立省 12元 原价15元|高速多线程下载文件，原价9元。\n月付VIP|5|30|6.3|立省175元 仅 1.33元/月|高速多线程下载文件，比周付省11%。\n半年VIP|8|180|5.2|立省420元 原价450元|高速多线程下载文件，比月付省35%。\n一年VIP|10|360|4.7|立省420元 原价450元|高速多线程下载文件，比月付省50%。\n永久VIP|20|9999|2.7|立省420元 原价450元|高速多线程下载文件，比月付省73%。','vip');
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
  `delete_time` int(11) DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='储存文件';

#
# Data for table "sk_stores"
#

/*!40000 ALTER TABLE `sk_stores` DISABLE KEYS */;
INSERT INTO `sk_stores` VALUES (3,4,0,'app.ico','20210909\\4\\file_613a2833432371631201331.ico',114595,'','image/x-icon','ico',6,1,0,0,'',NULL,1631201331,1631201331,NULL),(4,4,0,'QQBrowser.exe','20210910\\4\\file_613a489f70caf1631209631.exe',1288264,'','application/x-dosexec','exe',7,1,0,0,'',NULL,1631209631,1631209631,NULL),(29,5,0,'4.ico','20210910\\5\\file_613a4a5a41ac71631210074.ico',22486,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(30,5,0,'5.ico','20210910\\5\\file_613a4a5a5572f1631210074.ico',150146,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(31,5,0,'6.ico','20210910\\5\\file_613a4a5a66c871631210074.ico',298566,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(32,5,0,'8.ico','20210910\\5\\file_613a4a5a785c71631210074.ico',139094,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(33,5,0,'10.ico','20210910\\5\\file_613a4a5a897371631210074.ico',139094,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(34,5,0,'11.ico','20210910\\5\\file_613a4a5a9cbcf1631210074.ico',70124,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(35,5,0,'10102.ico','20210910\\5\\file_613a4a5aaf8971631210074.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(36,5,0,'10102.png','20210910\\5\\file_613a4a5ac15bf1631210074.png',6272,'','image/png','png',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(37,5,0,'10130.ico','20210910\\5\\file_613a4a5ad42871631210074.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(38,5,0,'10130.png','20210910\\5\\file_613a4a5ae677f1631210074.png',6218,'','image/png','png',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(39,5,0,'11004.ico','20210910\\5\\file_613a4a5b052071631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(40,5,0,'11004.png','20210910\\5\\file_613a4a5b182b71631210075.png',7246,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(41,5,0,'11701.ico','20210910\\5\\file_613a4a5b28c571631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(42,5,0,'11701.png','20210910\\5\\file_613a4a5b3b91f1631210075.png',5820,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(43,5,0,'11889.ico','20210910\\5\\file_613a4a5b4e1ff1631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(44,5,0,'11889.png','20210910\\5\\file_613a4a5b60adf1631210075.png',5894,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(45,5,0,'13216.ico','20210910\\5\\file_613a4a5b728071631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(46,5,0,'13216.png','20210910\\5\\file_613a4a5b858b71631210075.png',25338,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(47,5,0,'15700.ico','20210910\\5\\file_613a4a5b989671631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(48,5,0,'15700.png','20210910\\5\\file_613a4a5bab62f1631210075.png',4942,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(49,5,0,'17041.ico','20210910\\5\\file_613a4a5bbeeaf1631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(50,5,0,'17041.png','20210910\\5\\file_613a4a5bcf07f1631210075.png',4837,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL);
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
  `group_expire` int(11) NOT NULL DEFAULT '0' COMMENT '用户组过期时间',
  `is_auth` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否实名认证',
  `wx_openid` varchar(64) DEFAULT NULL COMMENT '微信开放平台ID',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `login_real_ip` varchar(60) NOT NULL DEFAULT '',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户状态 1 正常 0封禁',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "sk_users"
#

/*!40000 ALTER TABLE `sk_users` DISABLE KEYS */;
INSERT INTO `sk_users` VALUES (1,'admin','管理员','admin@skpan.net','3e9464c59cc03ef5f3c5ed555e2757e2',NULL,0.00,1,0,0,NULL,1631009197,0,'',1),(4,'1655545174','雷霆嘎巴','1655545174@qq.com','99c0342da49856be2e9f6fbf55116e57',NULL,0.00,3,0,0,NULL,1631186682,0,'',1),(5,'ceshi123','ceshi123','1950412285@qq.com','221da9e890927b42c61a73770d331f39',NULL,0.00,3,0,0,NULL,1631210019,0,'',1);
/*!40000 ALTER TABLE `sk_users` ENABLE KEYS */;
