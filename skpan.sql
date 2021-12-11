# Host: localhost  (Version: 5.5.29)
# Date: 2021-12-11 23:02:56
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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='文件夹';

#
# Data for table "sk_folders"
#

/*!40000 ALTER TABLE `sk_folders` DISABLE KEYS */;
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
INSERT INTO `sk_groups` VALUES (1,'管理员',1,1073741824,'',1,1),(2,'游客',1,0,'5',0,1),(3,'普通用户',6,104857600,'20',1,1),(4,'VIP用户',1,209715200,'',1,0);
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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='订单记录';

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='储存策略';

#
# Data for table "sk_policys"
#

/*!40000 ALTER TABLE `sk_policys` DISABLE KEYS */;
INSERT INTO `sk_policys` VALUES (1,'默认存储','local','',2097152,'{\"save_dir\":\"\\/default\\/\",\"access_token\":\"\",\"server_uri\":\"\"}'),(6,'远程文件','remote','',0,'{\"save_dir\":\"\\/upload\\/\",\"access_token\":\"asdasfasfasfasfasfa\",\"server_uri\":\"http:\\/\\/zxserver.test.com\\/index.php\"}');
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
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='收益记录';

#
# Data for table "sk_profit"
#

/*!40000 ALTER TABLE `sk_profit` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

#
# Data for table "sk_setting"
#

/*!40000 ALTER TABLE `sk_setting` DISABLE KEYS */;
INSERT INTO `sk_setting` VALUES (1,'site_name','闪客网盘 | 分享赚钱,让资源有价值!','basic'),(2,'site_keywords','闪客网盘 | 闪客云盘 | 网络云盘 | 网盘联盟 | 网赚网盘 | 云盘 | 云存储','basic'),(3,'site_desc','闪客云盘是一款速度快、不打扰、够安全、易于分享的网络云盘,提供了分享下载分成功能,分享赚钱,让资源变的有收益有价值!','basic'),(4,'site_head','','basic'),(5,'site_foot','','basic'),(6,'allow_register','1','register'),(7,'login_captcha','1','register'),(8,'default_group','3','register'),(9,'register_captcha','1','register'),(10,'forget_captcha','0','register'),(11,'site_title','SK网盘','basic'),(12,'site_logo','/assets/logo/logo.gif','basic'),(13,'api_gateway','https://ww.52ypay.com/submit.php','pay'),(14,'api_pid','678679','pay'),(15,'api_type','wxpay,alipay','pay'),(16,'api_key','BC9CC2EC25BE461E7DB6A44E9476D705','pay'),(17,'vip_group','4','vip'),(18,'vip_rule','周付VIP|1|7|周|7.0|立省 <b>12</b> 原价<b>15</b>元|高速多线程下载文件，原价9元。|0\n月付VIP|2|30|月|6.3|立省175元 仅 1.33元/月|高速多线程下载文件，比周付省11%。|1\n半年VIP|3|180|半年|5.2|立省420元 原价450元|高速多线程下载文件，比月付省35%。|1\n一年VIP|4|360|年|4.7|立省420元 原价450元|高速多线程下载文件，比月付省50%。|1\n永久VIP|20|9999|永久|2.7|立省420元 原价450元|高速多线程下载文件，比月付省73%。|0','vip'),(19,'site_email','test@admin.com','basic'),(20,'smtp_host','smtp.qq.com','email'),(21,'smtp_port','465','email'),(22,'nickname','SK网盘官方','email'),(23,'username','zygphper@foxmail.com','email'),(24,'password','xyuufcokuxendbfi','email'),(25,'template_forget','<p>&nbsp;</p>\n<table class=\"body-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;\" bgcolor=\"#f6f6f6\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n<td class=\"container\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;\" valign=\"top\" width=\"600\">\n<div class=\"content\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;\">\n<table class=\"main\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fff\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"alert alert-warning\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #2196f3; margin: 0; padding: 20px;\" align=\"center\" valign=\"top\" bgcolor=\"#FF9F00\">找回密码验证 -【{site_title}】</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;\" valign=\"top\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">亲爱的 <strong style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\"> {username} </strong> ：</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">请点击下方按钮完成密码重设。十分钟之内有效，如果非你本人操作，请忽略此邮件。</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 20px 50px 20px; text-align: center;\" valign=\"top\"><a class=\"btn-primary\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #fff; font-weight: bolder; letter-spacing: 2px; text-decoration: none; height: 45px; line-height: 45px; padding: 0 15px; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #2196f3; margin: 0;\" href=\"{url}\"> 点击设置新密码 </a></td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">感谢您选择<strong>{site_title}</strong>。</td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<div class=\"footer\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"aligncenter content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;\" align=\"center\" valign=\"top\">此邮件由系统自动发送，请不要直接回复。</td>\n</tr>\n</tbody>\n</table>\n<br /><br /></div>\n</div>\n</td>\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n</tr>\n</tbody>\n</table>','email'),(26,'site_kefu','客服QQ：<span style=\'color:red;font-size:15px\'>1665555255</span><br/><br/>客服在线时间：早上9点-晚上22点<br/><br/>如遇到任何问题请联系客服处理<br/>对此给您带来的不便深感抱歉','basic'),(27,'vip_profit','50','vip'),(28,'index_theme','new1','basic'),(29,'template_register','<p>&nbsp;</p>\n<table class=\"body-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;\" bgcolor=\"#f6f6f6\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n<td class=\"container\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;\" valign=\"top\" width=\"600\">\n<div class=\"content\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;\">\n<table class=\"main\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fff\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"alert alert-warning\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #2196f3; margin: 0; padding: 20px;\" align=\"center\" valign=\"top\" bgcolor=\"#FF9F00\">注册邮件验证码 -【{site_title}】</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-wrap\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;\" valign=\"top\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">亲爱的 <strong style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\"> {email} </strong> ：</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">请复制下方邮件验证码完成注册。十分钟之内有效，如果非你本人操作，请忽略此邮件。</td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 32px; vertical-align: top; margin: 0; padding: 20px 20px 50px 20px; text-align: center;\" valign=\"top\"><strong>{code}</strong></td>\n</tr>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;\" valign=\"top\">感谢您选择<strong>{site_title}</strong>。</td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<div class=\"footer\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;\">\n<table style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\" width=\"100%\">\n<tbody>\n<tr style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;\">\n<td class=\"aligncenter content-block\" style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;\" align=\"center\" valign=\"top\">此邮件由系统自动发送，请不要直接回复。</td>\n</tr>\n</tbody>\n</table>\n<br /><br /></div>\n</div>\n</td>\n<td style=\"font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;\" valign=\"top\">&nbsp;</td>\n</tr>\n</tbody>\n</table>','email');
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
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COMMENT='分享表';

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
) ENGINE=MyISAM AUTO_INCREMENT=188 DEFAULT CHARSET=utf8 COMMENT='储存文件';

#
# Data for table "sk_stores"
#

/*!40000 ALTER TABLE `sk_stores` DISABLE KEYS */;
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
  `desc` varchar(255) DEFAULT NULL COMMENT '个性签名',
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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "sk_users"
#

/*!40000 ALTER TABLE `sk_users` DISABLE KEYS */;
INSERT INTO `sk_users` VALUES (1,'admin','管理员','admin@skpan.net','3e9464c59cc03ef5f3c5ed555e2757e2',NULL,NULL,10.00,1,0,0,NULL,1631009197,0,'',1);
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
/*!40000 ALTER TABLE `sk_withdraw` ENABLE KEYS */;
