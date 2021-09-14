# Host: localhost  (Version: 5.5.29)
# Date: 2021-09-15 05:53:11
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "sk_certify"
#

DROP TABLE IF EXISTS `sk_certify`;
CREATE TABLE `sk_certify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '实名用户',
  `name` varchar(60) DEFAULT NULL COMMENT '姓名',
  `idcard` varchar(18) DEFAULT NULL COMMENT '身份证号码',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '实名状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='实名认证表';

#
# Data for table "sk_certify"
#

/*!40000 ALTER TABLE `sk_certify` DISABLE KEYS */;
INSERT INTO `sk_certify` VALUES (1,5,'运力','432524200003012514',1631483731,1);
/*!40000 ALTER TABLE `sk_certify` ENABLE KEYS */;

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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='文件夹';

#
# Data for table "sk_folders"
#

/*!40000 ALTER TABLE `sk_folders` DISABLE KEYS */;
INSERT INTO `sk_folders` VALUES (1,4,0,'根目录',0,NULL,0,755,0,0,1631186682,0,NULL),(8,5,0,'根目录',0,NULL,0,755,0,0,1631210019,0,NULL),(14,5,6,'图片资源',13,'会议深入学习贯彻习近平总书记关于文艺工作的重要论述，贯彻落实中宣部关于文娱领域综合治理工作部署要求和广电总局广播电视和网络视听文艺工作者座谈会精神，研究提高网络视听文艺节目质量水平的思路举措和发展方向，廓清网络视听领域风气，切实推动网络视听文艺高质量发展。',0,755,0,0,1631307651,1631307651,NULL),(15,5,39,'aaaa',13,'',0,755,0,0,1631308784,1631308784,NULL),(16,5,40,'vvvvvvvv',14,'',0,755,0,0,1631308789,1631308789,NULL),(17,5,41,'sssss',16,'',0,755,0,0,1631308795,1631308795,NULL),(18,5,42,'ddddd',15,'',0,755,0,0,1631308807,1631308807,NULL),(19,4,44,'等待',1,'a',1,755,0,0,1631402716,1631402716,NULL),(20,7,0,'根目录',0,NULL,0,755,0,0,1631553262,0,NULL);
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
INSERT INTO `sk_groups` VALUES (1,'管理员',1,1073741824,'',1,1),(2,'游客',1,0,'5',0,1),(3,'普通用户',3,104857600,'20',1,1),(4,'VIP用户',1,209715200,'',1,0);
/*!40000 ALTER TABLE `sk_groups` ENABLE KEYS */;

#
# Structure for table "sk_order"
#

DROP TABLE IF EXISTS `sk_order`;
CREATE TABLE `sk_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '开通用户',
  `trade_no` varchar(40) NOT NULL DEFAULT '' COMMENT '订单号',
  `out_trade_no` varchar(40) NOT NULL DEFAULT '' COMMENT '易支付订单号',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '支付方式',
  `profit_id` int(11) NOT NULL DEFAULT '0' COMMENT '收益链接ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `vip_day` int(11) NOT NULL DEFAULT '0' COMMENT 'VIP时长',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付状态 0 等待支付 1支付成功 2订单关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='订单记录';

#
# Data for table "sk_order"
#

/*!40000 ALTER TABLE `sk_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `sk_order` ENABLE KEYS */;

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
INSERT INTO `sk_policys` VALUES (1,'默认存储','local','png',2097152,'{\"save_dir\":\"\\/default\\/\",\"access_token\":\"\",\"server_uri\":\"\"}'),(3,'远程服务器一','remote','',1073741824,'{\"save_dir\":\"\\/uploads\\/\",\"access_token\":\"asdasfasfasfasfasfa\",\"server_uri\":\"http:\\/\\/tp.com:8080\\/server\\/index.php\"}');
/*!40000 ALTER TABLE `sk_policys` ENABLE KEYS */;

#
# Structure for table "sk_profit"
#

DROP TABLE IF EXISTS `sk_profit`;
CREATE TABLE `sk_profit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `file_id` int(11) NOT NULL DEFAULT '0' COMMENT '文件ID',
  `count_view` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `count_down` int(11) NOT NULL DEFAULT '0' COMMENT '下载量',
  `count_reg` int(11) NOT NULL DEFAULT '0' COMMENT '注册量',
  `count_order` int(11) NOT NULL DEFAULT '0' COMMENT '总订单',
  `count_order_yes` int(11) NOT NULL DEFAULT '0' COMMENT '成功订单',
  `count_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `count_profit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收益',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='收益记录';

#
# Data for table "sk_profit"
#

/*!40000 ALTER TABLE `sk_profit` DISABLE KEYS */;
INSERT INTO `sk_profit` VALUES (1,5,90,5,1,2,0,0,0.00,0.00,1631542710),(2,5,91,12,2,6,0,0,0.00,0.00,1631542900),(3,5,92,102,1,0,0,0,0.00,0.00,1631545469),(4,5,90,6,2,6,0,0,0.00,0.00,1631422109),(5,5,91,49,0,0,0,0,0.00,0.00,1631549201),(6,5,90,69,0,0,0,0,0.00,0.00,1631549214),(7,5,92,230,1,0,0,0,0.00,0.00,1631553147),(8,5,48,0,0,4,0,0,0.00,0.00,1631553201),(9,5,95,2,0,0,0,0,0.00,0.00,1631576957),(10,5,133,2,0,0,0,0,0.00,0.00,1631579839),(11,5,134,2,0,0,0,0,0.00,0.00,1631591496),(12,5,137,6,5,0,0,0,0.00,0.00,1631624501),(13,5,138,4,4,0,0,0,0.00,0.00,1631624563),(14,5,139,3,2,0,0,0,0.00,0.00,1631624691),(15,5,140,24,8,0,0,0,0.00,0.00,1631624735),(16,5,141,62,130,0,0,0,0.00,0.00,1631626904),(17,5,142,3,13,0,0,0,0.00,0.00,1631635076),(18,5,142,1,6,0,0,0,0.00,0.00,1631635371),(19,5,141,15,30,0,0,0,0.00,0.00,1631635502),(20,5,150,2,1,0,0,0,0.00,0.00,1631652994);
/*!40000 ALTER TABLE `sk_profit` ENABLE KEYS */;

#
# Structure for table "sk_record"
#

DROP TABLE IF EXISTS `sk_record`;
CREATE TABLE `sk_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 增加 1减少',
  `source` varchar(60) DEFAULT NULL COMMENT '来源',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更金额',
  `after_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前',
  `before_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后金额',
  `remark` varchar(255) DEFAULT NULL COMMENT '操作原因',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='财务记录';

#
# Data for table "sk_record"
#

/*!40000 ALTER TABLE `sk_record` DISABLE KEYS */;
INSERT INTO `sk_record` VALUES (1,5,1,'用户提现',20.00,480.00,500.00,'余额提现20元至支付宝',1631487541),(2,5,1,'用户提现',10.00,470.00,480.00,'余额提现10元至支付宝',1631489052),(3,5,1,'用户提现',100.00,370.00,470.00,'余额提现100元至支付宝',1631537650),(4,5,1,'提现退回',100.00,270.00,370.00,'提现系统退回金额',1631537867),(5,5,1,'用户提现',20.00,250.00,270.00,'余额提现20元至支付宝',1631538068),(6,5,0,'提现退回',20.00,270.00,250.00,'提现系统退回金额',1631538080);
/*!40000 ALTER TABLE `sk_record` ENABLE KEYS */;

#
# Structure for table "sk_reports"
#

DROP TABLE IF EXISTS `sk_reports`;
CREATE TABLE `sk_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shares_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源ID',
  `source_name` varchar(255) DEFAULT NULL COMMENT '文件名',
  `source_url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `source_uid` int(11) NOT NULL DEFAULT '0' COMMENT '所属用户UID',
  `source_username` varchar(60) NOT NULL DEFAULT '' COMMENT '所属用户帐号',
  `source_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 文件 1目录',
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '举报类型',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系方式',
  `content` text COMMENT '详细描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '举报时间',
  `real_ip` varchar(32) DEFAULT NULL COMMENT '举报IP',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '举报处理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文件举报';

#
# Data for table "sk_reports"
#

/*!40000 ALTER TABLE `sk_reports` DISABLE KEYS */;
INSERT INTO `sk_reports` VALUES (1,0,'phpStudy_64.zip','http://192.168.1.39:8080/s/yI7jAvQrtQ',5,'ceshi123',0,'病毒','02132132','0.sada2.3sd',1631625163,'192.168.1.40',1);
/*!40000 ALTER TABLE `sk_reports` ENABLE KEYS */;

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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

#
# Data for table "sk_setting"
#

/*!40000 ALTER TABLE `sk_setting` DISABLE KEYS */;
INSERT INTO `sk_setting` VALUES (1,'site_name','闪客网盘 | 分享赚钱,让资源有价值!','basic'),(2,'site_keywords','闪客网盘 | 闪客云盘 | 网络云盘 | 网盘联盟 | 网赚网盘 | 云盘 | 云存储','basic'),(3,'site_desc','闪客云盘是一款速度快、不打扰、够安全、易于分享的网络云盘,提供了分享下载分成功能,分享赚钱,让资源变的有收益有价值!','basic'),(4,'site_head','','basic'),(5,'site_foot','','basic'),(6,'allow_register','1','register'),(7,'login_captcha','1','register'),(8,'default_group','3','register'),(9,'register_captcha','1','register'),(10,'forget_captcha','0','register'),(11,'site_title','SK网盘','basic'),(12,'site_logo','/assets/logo/logo.gif','basic'),(13,'api_gateway','https://ww.52ypay.com/submit.php','pay'),(14,'api_pid','678679','pay'),(15,'api_type','wxpay,alipay','pay'),(16,'api_key','BC9CC2EC25BE461E7DB6A44E9476D705','pay'),(17,'vip_group','4','vip'),(18,'vip_rule','周付VIP|1|7|周|7.0|立省 <b>12</b> 原价<b>15</b>元|高速多线程下载文件，原价9元。|0\n月付VIP|2|30|月|6.3|立省175元 仅 1.33元/月|高速多线程下载文件，比周付省11%。|1\n半年VIP|3|180|半年|5.2|立省420元 原价450元|高速多线程下载文件，比月付省35%。|1\n一年VIP|4|360|年|4.7|立省420元 原价450元|高速多线程下载文件，比月付省50%。|1\n永久VIP|20|9999|永久|2.7|立省420元 原价450元|高速多线程下载文件，比月付省73%。|0','vip'),(19,'site_email','test@admin.com','basic'),(20,'smtp_host','smtp.qq.com','email'),(21,'smtp_port','465','email'),(22,'nickname','SK网盘官方','email'),(23,'username','zygphper@foxmail.com','email'),(24,'password','xyuufcokuxendbfi','email'),(25,'template_forget','<p>&nbsp;</p>\n<table class=\"body-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;\" bgcolor=\"#f6f6f6\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n<td class=\"container\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;\" valign=\"top\" width=\"600\">\n<div class=\"content\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;\">\n<table class=\"main\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fff\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"alert alert-warning\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #2196f3; margin: 0; padding: 20px;\" align=\"center\" valign=\"top\" bgcolor=\"#FF9F00\">找回密码验证 -【{site_title}】</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;\" valign=\"top\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">亲爱的 <strong style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\"> {username} </strong> ：</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">请点击下方按钮完成密码重设。十分钟之内有效，如果非你本人操作，请忽略此邮件。</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 20px 50px 20px; text-align: center;\" valign=\"top\"><a class=\"btn-primary\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #fff; font-weight: bolder; letter-spacing: 2px; text-decoration: none; height: 45px; line-height: 45px; padding: 0 15px; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #2196f3; margin: 0;\" href=\"{url}\"> 点击设置新密码 </a></td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">感谢您选择<strong>{site_title}</strong>。</td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<div class=\"footer\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"aligncenter content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;\" align=\"center\" valign=\"top\">此邮件由系统自动发送，请不要直接回复。</td>\n</tr>\n</tbody>\n</table>\n<br /><br /></div>\n</div>\n</td>\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n</tr>\n</tbody>\n</table>','email'),(26,'site_kefu','客服QQ：<span style=\'color:red;font-size:15px\'>1665555255</span><br/><br/>客服在线时间：早上9点-晚上22点<br/><br/>如遇到任何问题请联系客服处理<br/>对此给您带来的不便深感抱歉','basic'),(27,'vip_profit','50','vip');
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
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COMMENT='分享表';

#
# Data for table "sk_shares"
#

/*!40000 ALTER TABLE `sk_shares` DISABLE KEYS */;
INSERT INTO `sk_shares` VALUES (1,5,13,1,0,'QVNbueubNW','fmrw',0),(4,5,54,0,0,'aYbA3iNCSk','kHIb',0),(5,5,55,0,0,'JJBjIvUM80','',0),(6,5,14,1,0,'7NVjYnhxwT','jPAT',0),(7,5,56,0,0,'yaYJ3mrsYP','Wt9G',0),(8,5,57,0,0,'qy2a6fp7PW','CkLp',0),(9,5,58,0,0,'7bmANrUqUE','MyFS',0),(10,5,59,0,0,'NJVjEzpLFh','BFaM',0),(11,5,60,0,0,'mmArMvpqNn','5XPd',0),(12,5,61,0,0,'nUjuQz1qb2','98Zl',0),(13,5,62,0,0,'MzUnIjCVom','5ERQ',0),(14,5,63,0,0,'IziqeylQaJ','1Qmi',0),(15,5,64,0,0,'2uAnEr0QFn','FNx2',0),(16,5,65,0,0,'YB7bI3IucT','MCpN',0),(17,5,66,0,0,'qERnMf08KE','iKVU',0),(18,5,67,0,0,'YFJbiySL45','c8Mu',0),(19,5,68,0,0,'UFjIf2QqnV','3G2H',0),(20,5,69,0,0,'EzuYburVJs','qkIU',0),(21,5,70,0,0,'6Vzmmu1ksV','SHdA',0),(22,5,71,0,0,'MbIr2yNNJT','yV7I',0),(23,5,72,0,0,'JzyeInn1Pi','Gmmb',0),(24,5,73,0,0,'imiQraBLcN','Pa2p',0),(25,5,74,0,0,'EJZRZrJMbq','W2IS',0),(26,5,75,0,0,'eqAfaiWH7R','D1B8',0),(27,5,76,0,0,'UFj2uq57f4','SQ9u',0),(28,5,77,0,0,'Bf6vEfdCoU','sgnk',0),(29,5,78,0,0,'imaqyala0O','oTAG',0),(30,5,79,0,0,'VfMV7fnMhl','TWAr',0),(31,5,80,0,0,'QVbeqiRTAZ','eaCW',0),(32,5,81,0,0,'JBBfUvPgwv','uzc5',0),(33,5,82,0,0,'FFNJzmsgjK','NU7M',0),(34,5,83,0,0,'2aiEZbNudq','ynBq',0),(35,5,84,0,0,'3aMFVfKSwN','gkRD',0),(36,5,85,0,0,'uqiYfaxfkv','QxfD',0),(37,5,86,0,0,'VVZzyaLcG0','HRhA',0),(38,5,87,0,0,'FJv2mmUflN','Fg90',0),(39,5,15,1,0,'73eA32Tg7o','4uA5',0),(40,5,16,1,0,'YNfUzmZhx8','nEMH',0),(41,5,17,1,0,'eiiQFjdEpA','sBNK',0),(42,5,18,1,0,'YreIjqrjMS','CdZc',0),(43,5,88,0,0,'QfIzAvSL40','lZcP',0),(44,4,19,1,0,'j6ZnqaMTRo','wAvF',0),(45,4,89,0,0,'VZVnumE7Km','02tC',0),(46,5,90,0,0,'NbUFFfo3sb','J8tF',0),(47,5,91,0,0,'EfQ3QbG4aK','Bctq',0),(48,5,92,0,0,'NZb6B3ic49','jrDc',0),(49,5,93,0,0,'aqUNNnZmR2','632a',0),(50,5,94,0,0,'qeeMFrQTea','adpb',0),(51,5,95,0,0,'v6vyIbYLEW','5Guc',0),(52,5,96,0,0,'FZrIZ3YkZE','7d65',0),(53,5,97,0,0,'FzyqmqBrEb','2xry',0),(54,5,98,0,0,'qim2qmNTqN','2yOS',0),(55,5,99,0,0,'iuQJnuSQHT','UJmM',0),(56,5,100,0,0,'MBbuUrgjwi','lmSD',0),(57,5,101,0,0,'3yUF7vPc7e','I0uV',0),(58,5,102,0,0,'mm6ZRfXZCa','prqi',0),(59,5,103,0,0,'ryqeuivWSs','i5Ly',0),(60,5,104,0,0,'UN3M3mhxHw','ZpfC',0),(61,5,105,0,0,'FRZjyewFnu','SRYT',0),(62,5,106,0,0,'muQ3aeS48W','ic2y',0),(63,5,107,0,0,'qY3yi2Wfc9','nRHo',0),(64,5,108,0,0,'iAfEryWcv1','upsi',0),(65,5,109,0,0,'7VVjUzFoMO','jwTR',0),(66,5,110,0,0,'ENrUZfUfgi','xYVP',0),(67,5,111,0,0,'nMNf2u68pm','idbJ',0),(68,5,112,0,0,'RvINn2qOaE','nFo1',0),(69,5,113,0,0,'F3aYnacuCu','azXJ',0),(70,5,114,0,0,'6FR3mebo4b','IqyQ',0),(71,5,115,0,0,'mi2Qjm9MOT','6tKy',0),(72,5,116,0,0,'U32IvaBozx','JP7x',0),(73,5,117,0,0,'IVRBJbXC8c','ZO9x',0),(74,5,118,0,0,'ANn2muSUlr','fWlr',0),(75,5,119,0,0,'qiYvUv3ERC','GP8y',0),(76,5,120,0,0,'jQVRRftjKV','y8Gz',0),(77,5,121,0,0,'vmyIre9Isy','QQ9G',0),(78,5,122,0,0,'iQ7Nva6kEo','H2P8',0),(79,5,123,0,0,'6BBn2eGfaa','Fngu',0),(80,5,124,0,0,'FZBFBjTu6D','QN63',0),(81,5,125,0,0,'aimiyqgMq7','fIjC',0),(82,5,126,0,0,'q6Nn2iu8v9','cZ6U',0),(83,5,127,0,0,'umy6ZnVIzB','YO7G',0),(84,5,128,0,0,'zqmUZfk2y3','MnYF',0),(85,5,129,0,0,'3eEbUzMnxT','2zTb',0),(86,5,130,0,0,'JZjU3aeep6','6fuE',0),(87,5,131,0,0,'2IF3u25i8Y','LcbB',0),(88,5,132,0,0,'biaiaqZkzS','OqbL',0),(89,5,133,0,0,'FfQRJjr7NK','6btE',0),(90,5,134,0,0,'BRFnQfqGqa','wgTz',0),(91,5,135,0,0,'RzmmYv8Qjg','OJcS',0),(92,5,136,0,0,'nMRvI3Xo05','b8t3',0),(93,5,137,0,0,'y67nEnWzjz','lhK7',0),(94,5,138,0,0,'3YVB3qb5b7','o3GV',0),(95,5,139,0,0,'r6jEJfmGhy','3U6u',0),(96,5,140,0,0,'yI7jAvQrtQ','m3Do',0),(97,5,141,0,0,'yAR7fqlAYT','A1ft',0),(98,5,142,0,0,'VJbai2zHA8','lQjh',0),(99,5,143,0,0,'NrYVvqD4sP','StfT',0),(100,5,144,0,0,'uauIFjPko6','sijd',0),(101,5,21,1,0,'eIRrQnOHwE','RhXA',0),(102,5,145,0,0,'UbIRB3mwAm','lFCk',0),(103,5,146,0,0,'mMVJryGgMx','tSWc',0),(104,5,147,0,0,'jaymMn1wGq','mdRd',0),(105,5,148,0,0,'ZvIJrqS7Le','wT76',0),(106,5,149,0,0,'Vb2UVvXaa4','scFW',0),(107,5,150,0,0,'NjuIn2UYst','FpEu',0);
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
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COMMENT='储存文件';

#
# Data for table "sk_stores"
#

/*!40000 ALTER TABLE `sk_stores` DISABLE KEYS */;
INSERT INTO `sk_stores` VALUES (150,5,107,'Git-2.32.0.2-64-bit.exe','/upload/20210915/5/file_mXIGMXPqys3WNC2h.exe',49960224,'','application/x-dosexec','exe',8,3,1,2,'',NULL,1631652720,1631652720,NULL);
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "sk_users"
#

/*!40000 ALTER TABLE `sk_users` DISABLE KEYS */;
INSERT INTO `sk_users` VALUES (1,'admin','管理员','admin@skpan.net','3e9464c59cc03ef5f3c5ed555e2757e2',NULL,10.00,1,0,0,NULL,1631009197,0,'',1),(4,'1655545174','雷霆嘎巴','1655545174@qq.com','c9c58643bb17b4aaed8a5510139eec0a',NULL,0.00,3,0,0,NULL,1631186682,0,'',1),(5,'ceshi123','ceshi123','1950412285@qq.com','221da9e890927b42c61a73770d331f39',NULL,270.00,3,0,1,NULL,1631210019,0,'',1),(6,'aa1122','AAAA','aa1122@qq.com','221da9e890927b42c61a73770d331f39',NULL,0.00,3,0,0,NULL,1631553201,0,'',1),(7,'aa11223','abc','aa1122d@qq.com','99c0342da49856be2e9f6fbf55116e57',NULL,0.00,3,0,0,NULL,1631553262,0,'',1);
/*!40000 ALTER TABLE `sk_users` ENABLE KEYS */;

#
# Structure for table "sk_withdraw"
#

DROP TABLE IF EXISTS `sk_withdraw`;
CREATE TABLE `sk_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '提现用户',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `alipay_account` varchar(255) DEFAULT NULL COMMENT '支付宝帐号',
  `alipay_name` varchar(60) DEFAULT NULL COMMENT '支付宝姓名',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '提现时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提现状态 0 等待处理 1提现成功',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户提现记录';

#
# Data for table "sk_withdraw"
#

/*!40000 ALTER TABLE `sk_withdraw` DISABLE KEYS */;
INSERT INTO `sk_withdraw` VALUES (1,5,20.00,'1655545174@qq.com','蓝天',1631487541,1),(2,5,10.00,'1655545174@qq.com','蓝天',1631489052,1),(3,5,100.00,'1655545174@qq.com','啊啊',1631537650,2),(4,5,20.00,'1655545174@qq.com','啊啊',1631538068,2);
/*!40000 ALTER TABLE `sk_withdraw` ENABLE KEYS */;
