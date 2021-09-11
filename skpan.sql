# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-12 01:31:08
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
  `desc` text COMMENT '描述',
  `size` int(11) NOT NULL DEFAULT '0',
  `ext` int(11) NOT NULL DEFAULT '755',
  `count_down` int(11) NOT NULL DEFAULT '0' COMMENT '下载统计',
  `count_open` int(11) NOT NULL DEFAULT '0' COMMENT '浏览统计',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='文件夹';

#
# Data for table "sk_folders"
#

/*!40000 ALTER TABLE `sk_folders` DISABLE KEYS */;
INSERT INTO `sk_folders` VALUES (1,4,0,'根目录',0,NULL,0,755,0,0,1631186682,0,NULL),(8,5,0,'根目录',0,NULL,0,755,0,0,1631210019,0,NULL),(13,5,1,'源码文件AAA',8,'啊啊啊啊',0,755,0,0,1631292295,1631297667,NULL),(14,5,6,'图片资源',13,'会议深入学习贯彻习近平总书记关于文艺工作的重要论述，贯彻落实中宣部关于文娱领域综合治理工作部署要求和广电总局广播电视和网络视听文艺工作者座谈会精神，研究提高网络视听文艺节目质量水平的思路举措和发展方向，廓清网络视听领域风气，切实推动网络视听文艺高质量发展。',0,755,0,0,1631307651,1631307651,NULL),(15,5,39,'aaaa',13,'',0,755,0,0,1631308784,1631308784,NULL),(16,5,40,'vvvvvvvv',14,'',0,755,0,0,1631308789,1631308789,NULL),(17,5,41,'sssss',16,'',0,755,0,0,1631308795,1631308795,NULL),(18,5,42,'ddddd',15,'',0,755,0,0,1631308807,1631308807,NULL);
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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

#
# Data for table "sk_setting"
#

/*!40000 ALTER TABLE `sk_setting` DISABLE KEYS */;
INSERT INTO `sk_setting` VALUES (1,'site_name','闪客网盘 | 分享赚钱,让资源有价值!','basic'),(2,'site_keywords','闪客网盘 | 闪客云盘 | 网络云盘 | 网盘联盟 | 网赚网盘 | 云盘 | 云存储','basic'),(3,'site_desc','闪客云盘是一款速度快、不打扰、够安全、易于分享的网络云盘,提供了分享下载分成功能,分享赚钱,让资源变的有收益有价值!','basic'),(4,'site_head','','basic'),(5,'site_foot','','basic'),(6,'allow_register','1','register'),(7,'login_captcha','1','register'),(8,'default_group','3','register'),(9,'register_captcha','1','register'),(10,'forget_captcha','0','register'),(11,'site_title','SK网盘','basic'),(12,'site_logo','/assets/logo/logo.gif','basic'),(13,'api_gateway','http://www.eshanpay.com/submit.php','pay'),(14,'api_pid','10001','pay'),(15,'api_type','wxpay,alipay','pay'),(16,'api_key','aaaaaaaa','pay'),(17,'vip_group','4','vip'),(18,'vip_rule','周付VIP|3|7|周|7.0|立省 <b>12</b> 原价<b>15</b>元|高速多线程下载文件，原价9元。|0\n月付VIP|5|30|月|6.3|立省175元 仅 1.33元/月|高速多线程下载文件，比周付省11%。|1\n半年VIP|8|180|半年|5.2|立省420元 原价450元|高速多线程下载文件，比月付省35%。|1\n一年VIP|10|360|年|4.7|立省420元 原价450元|高速多线程下载文件，比月付省50%。|1\n永久VIP|20|9999|永久|2.7|立省420元 原价450元|高速多线程下载文件，比月付省73%。|0','vip'),(19,'site_email','test@admin.com','basic'),(20,'smtp_host','smtp.qq.com','email'),(21,'smtp_port','465','email'),(22,'nickname','SK网盘官方','email'),(23,'username','12345678@qq.com','email'),(24,'password','123456451561','email'),(25,'template_forget','<p>&nbsp;</p>\n<table class=\"body-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;\" bgcolor=\"#f6f6f6\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n<td class=\"container\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;\" valign=\"top\" width=\"600\">\n<div class=\"content\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;\">\n<table class=\"main\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fff\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"alert alert-warning\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #2196f3; margin: 0; padding: 20px;\" align=\"center\" valign=\"top\" bgcolor=\"#FF9F00\">找回密码验证码 {site_title}</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;\" valign=\"top\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">亲爱的 <strong style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\"> {username} </strong> ：</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">请复制下方验证码完成密码重设。如果非你本人操作，请忽略此邮件。</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">\n<p>您的验证码为：</p>\n<div class=\"btn-primary\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 24px; color: #000; text-decoration: none; height: 80px; line-height: 80px; font-weight: bold; text-align: center; cursor: default; display: block; border-radius: 5px; text-transform: capitalize; background-color: #dcdcdc; margin: 0; width: 100%;\">{code}</div>\n</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">感谢您选择{site_title}。</td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<div class=\"footer\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"aligncenter content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;\" align=\"center\" valign=\"top\">此邮件由系统自动发送，请不要直接回复。</td>\n</tr>\n</tbody>\n</table>\n</div>\n</div>\n</td>\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n</tr>\n</tbody>\n</table>','email');
/*!40000 ALTER TABLE `sk_setting` ENABLE KEYS */;

#
# Structure for table "sk_shares"
#

DROP TABLE IF EXISTS `sk_shares`;
CREATE TABLE `sk_shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `source_id` int(11) NOT NULL DEFAULT '0' COMMENT '文件 / 目录ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 文件 1目录',
  `speed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '限速开关',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '短网址代码',
  `pwd` varchar(8) DEFAULT NULL COMMENT '分享密码',
  `pwd_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '密码状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='分享表';

#
# Data for table "sk_shares"
#

/*!40000 ALTER TABLE `sk_shares` DISABLE KEYS */;
INSERT INTO `sk_shares` VALUES (1,5,13,1,0,'QVNbueubNW','fmrw',0),(4,5,54,0,0,'aYbA3iNCSk','kHIb',0),(5,5,55,0,0,'JJBjIvUM80','',0),(6,5,14,1,0,'7NVjYnhxwT','jPAT',0),(7,5,56,0,0,'yaYJ3mrsYP','Wt9G',0),(8,5,57,0,0,'qy2a6fp7PW','CkLp',0),(9,5,58,0,0,'7bmANrUqUE','MyFS',0),(10,5,59,0,0,'NJVjEzpLFh','BFaM',0),(11,5,60,0,0,'mmArMvpqNn','5XPd',0),(12,5,61,0,0,'nUjuQz1qb2','98Zl',0),(13,5,62,0,0,'MzUnIjCVom','5ERQ',0),(14,5,63,0,0,'IziqeylQaJ','1Qmi',0),(15,5,64,0,0,'2uAnEr0QFn','FNx2',0),(16,5,65,0,0,'YB7bI3IucT','MCpN',0),(17,5,66,0,0,'qERnMf08KE','iKVU',0),(18,5,67,0,0,'YFJbiySL45','c8Mu',0),(19,5,68,0,0,'UFjIf2QqnV','3G2H',0),(20,5,69,0,0,'EzuYburVJs','qkIU',0),(21,5,70,0,0,'6Vzmmu1ksV','SHdA',0),(22,5,71,0,0,'MbIr2yNNJT','yV7I',0),(23,5,72,0,0,'JzyeInn1Pi','Gmmb',0),(24,5,73,0,0,'imiQraBLcN','Pa2p',0),(25,5,74,0,0,'EJZRZrJMbq','W2IS',0),(26,5,75,0,0,'eqAfaiWH7R','D1B8',0),(27,5,76,0,0,'UFj2uq57f4','SQ9u',0),(28,5,77,0,0,'Bf6vEfdCoU','sgnk',0),(29,5,78,0,0,'imaqyala0O','oTAG',0),(30,5,79,0,0,'VfMV7fnMhl','TWAr',0),(31,5,80,0,0,'QVbeqiRTAZ','eaCW',0),(32,5,81,0,0,'JBBfUvPgwv','uzc5',0),(33,5,82,0,0,'FFNJzmsgjK','NU7M',0),(34,5,83,0,0,'2aiEZbNudq','ynBq',0),(35,5,84,0,0,'3aMFVfKSwN','gkRD',0),(36,5,85,0,0,'uqiYfaxfkv','QxfD',0),(37,5,86,0,0,'VVZzyaLcG0','HRhA',0),(38,5,87,0,0,'FJv2mmUflN','Fg90',0),(39,5,15,1,0,'73eA32Tg7o','4uA5',0),(40,5,16,1,0,'YNfUzmZhx8','nEMH',0),(41,5,17,1,0,'eiiQFjdEpA','sBNK',0),(42,5,18,1,0,'YreIjqrjMS','CdZc',0),(43,5,88,0,0,'QfIzAvSL40','lZcP',0);
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
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COMMENT='储存文件';

#
# Data for table "sk_stores"
#

/*!40000 ALTER TABLE `sk_stores` DISABLE KEYS */;
INSERT INTO `sk_stores` VALUES (3,4,0,'app.ico','20210909\\4\\file_613a2833432371631201331.ico',114595,'','image/x-icon','ico',6,1,0,0,'',NULL,1631201331,1631201331,NULL),(4,4,0,'QQBrowser.exe','20210910\\4\\file_613a489f70caf1631209631.exe',1288264,'','application/x-dosexec','exe',7,1,0,0,'',NULL,1631209631,1631209631,NULL),(29,5,0,'4.ico','20210910\\5\\file_613a4a5a41ac71631210074.ico',22486,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(30,5,0,'5.ico','20210910\\5\\file_613a4a5a5572f1631210074.ico',150146,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(31,5,0,'6.ico','20210910\\5\\file_613a4a5a66c871631210074.ico',298566,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(32,5,0,'8.ico','20210910\\5\\file_613a4a5a785c71631210074.ico',139094,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(33,5,0,'10.ico','20210910\\5\\file_613a4a5a897371631210074.ico',139094,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(34,5,0,'11.ico','20210910\\5\\file_613a4a5a9cbcf1631210074.ico',70124,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(35,5,0,'10102.ico','20210910\\5\\file_613a4a5aaf8971631210074.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(36,5,0,'10102.png','20210910\\5\\file_613a4a5ac15bf1631210074.png',6272,'','image/png','png',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(37,5,0,'10130.ico','20210910\\5\\file_613a4a5ad42871631210074.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(38,5,0,'10130.png','20210910\\5\\file_613a4a5ae677f1631210074.png',6218,'','image/png','png',9,1,0,0,'',NULL,1631210074,1631210074,NULL),(39,5,0,'11004.ico','20210910\\5\\file_613a4a5b052071631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(40,5,0,'11004.png','20210910\\5\\file_613a4a5b182b71631210075.png',7246,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(41,5,0,'11701.ico','20210910\\5\\file_613a4a5b28c571631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(42,5,0,'11701.png','20210910\\5\\file_613a4a5b3b91f1631210075.png',5820,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(43,5,0,'11889.ico','20210910\\5\\file_613a4a5b4e1ff1631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(44,5,0,'11889.png','20210910\\5\\file_613a4a5b60adf1631210075.png',5894,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(45,5,0,'13216.ico','20210910\\5\\file_613a4a5b728071631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(46,5,0,'13216.png','20210910\\5\\file_613a4a5b858b71631210075.png',25338,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(47,5,0,'15700.ico','20210910\\5\\file_613a4a5b989671631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(48,5,0,'15700.png','20210910\\5\\file_613a4a5bab62f1631210075.png',4942,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(49,5,0,'17041.ico','20210910\\5\\file_613a4a5bbeeaf1631210075.ico',9662,'','image/x-icon','ico',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(50,5,0,'17041.png','20210910\\5\\file_613a4a5bcf07f1631210075.png',4837,'','image/png','png',9,1,0,0,'',NULL,1631210075,1631210075,NULL),(54,5,4,'bbb.html','20210911\\5\\file_613b92dcc1d2c1631294172.html',4776,'','text/html','html',13,1,0,0,'',NULL,1631294172,1631369402,NULL),(55,5,5,'aaa.html','20210911\\5\\file_613b932f6412c1631294255.html',6417,'','text/html','html',13,1,0,0,'',NULL,1631294255,1631297160,NULL),(56,5,7,'[324].png','20210911\\5\\file_613bc7d4e15141631307732.png',29791,'','image/png','png',14,1,0,0,'',NULL,1631307732,1631307732,NULL),(57,5,8,'[325].png','20210911\\5\\file_613bc7d5026ac1631307733.png',31309,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(58,5,9,'[326].png','20210911\\5\\file_613bc7d517a841631307733.png',31257,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(59,5,10,'[327].png','20210911\\5\\file_613bc7d52ce5c1631307733.png',31182,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(60,5,11,'[328].png','20210911\\5\\file_613bc7d540eac1631307733.png',29723,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(61,5,12,'[329].png','20210911\\5\\file_613bc7d5543441631307733.png',152942,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(62,5,13,'[330].png','20210911\\5\\file_613bc7d5673f41631307733.png',171122,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(63,5,14,'[331].png','20210911\\5\\file_613bc7d57b05c1631307733.png',88725,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(64,5,15,'[332].png','20210911\\5\\file_613bc7d59004c1631307733.png',50479,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(65,5,16,'[333].png','20210911\\5\\file_613bc7d5a4c541631307733.png',51556,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(66,5,17,'[334].png','20210911\\5\\file_613bc7d5ba4141631307733.png',144947,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(67,5,18,'[335].png','20210911\\5\\file_613bc7d5cffbc1631307733.png',208408,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(68,5,19,'[336].png','20210911\\5\\file_613bc7d5e2c841631307733.png',210265,'','image/png','png',14,1,0,0,'',NULL,1631307733,1631307733,NULL),(69,5,20,'[337].png','20210911\\5\\file_613bc7d603e1c1631307734.png',81453,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(70,5,21,'[338].png','20210911\\5\\file_613bc7d616ae41631307734.png',206283,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(71,5,22,'[339].png','20210911\\5\\file_613bc7d62ca741631307734.png',119293,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(72,5,23,'[390].png','20210911\\5\\file_613bc7d63e79c1631307734.png',7634,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(73,5,24,'[391].png','20210911\\5\\file_613bc7d652bd41631307734.png',101748,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(74,5,25,'[392].png','20210911\\5\\file_613bc7d667bc41631307734.png',143373,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(75,5,26,'[393].png','20210911\\5\\file_613bc7d67b4441631307734.png',102665,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(76,5,27,'[394].png','20210911\\5\\file_613bc7d6bfa041631307734.png',64897,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(77,5,28,'[395].png','20210911\\5\\file_613bc7d6d49f41631307734.png',159856,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(78,5,29,'[396].png','20210911\\5\\file_613bc7d6ea59c1631307734.png',147882,'','image/png','png',14,1,0,0,'',NULL,1631307734,1631307734,NULL),(79,5,30,'[397].png','20210911\\5\\file_613bc7d70af641631307735.png',138878,'','image/png','png',14,1,0,0,'',NULL,1631307735,1631374190,NULL),(80,5,31,'[398].png','20210911\\5\\file_613bc7d71efb41631307735.png',42313,'','image/png','png',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(81,5,32,'[399].png','20210911\\5\\file_613bc7d7337d41631307735.png',50371,'','image/png','png',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(82,5,33,'[400].jpg','20210911\\5\\file_613bc7d74b2bc1631307735.jpg',26139,'','image/jpeg','jpg',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(83,5,34,'[401].jpg','20210911\\5\\file_613bc7d761e041631307735.jpg',21476,'','image/jpeg','jpg',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(84,5,35,'[402].jpg','20210911\\5\\file_613bc7d77817c1631307735.jpg',19499,'','image/jpeg','jpg',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(85,5,36,'[403].png','20210911\\5\\file_613bc7d78c1cc1631307735.png',98457,'','image/png','png',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(86,5,37,'[404].png','20210911\\5\\file_613bc7d79def41631307735.png',232597,'','image/png','png',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(87,5,38,'[405].png','20210911\\5\\file_613bc7d7b2afc1631307735.png',152091,'','image/png','png',14,1,0,0,'',NULL,1631307735,1631307735,NULL),(88,5,43,'aavv.txt','20210912\\5\\file_613cd2d97faf91631376089.txt',26,'','text/plain','txt',8,1,0,0,'',NULL,1631376089,1631376089,NULL);
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
