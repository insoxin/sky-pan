# SkyPan

#### 介绍
一款多用户赚钱网盘程序，接入了多种存储系统`阿里云OSS` `腾讯云COS` `远程服务器存储` `本地存储`

#### 关于服务器方面的问题

服务器如果安装的是宝塔面板的话必须使用linux系统，因为window的ngnix版本最高只有1.2，而系统要求nginx版本>=1.5 因为使用了nginx文件下载转发限速功能，所以系统必须要nginx才能运行

在强调一遍，必须要用nginx >= 1.5

#### 关于问题处理
统一回复一下，有问题的可以在 https://gitee.com/tobugnet/sky-pan/issues 里面提问，最近在开发新项目可能回复的不是很及时，
如遇到很急的问题可以联系我的QQ 1655545174

#### 软件架构
前端
* Pear-admin
* Layui
* Jquery

后端
* PHP 7.3
* Thinkphp5.1
* Mysql

`服务器 nginx`

#### 环境依赖
* `fileinfo`

#### 常见问题

* 解决composer安装失败
https://gitee.com/tobugnet/sky-pan/issues/I4H6WR

* 如何配置远程服务器
https://gitee.com/tobugnet/sky-pan/issues/I4KPMY


#### 安装教程

* 下载源码包 skpan-1.xxx.zip
* 解压到网站目录
* 通过`composer`安装相关依赖，在应用根目录运行`composer install`
* 导入数据库文件skpan.sql
* 后台帐号密码`admin`

composer 加速
```
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```


#### nginx伪静态规则
```
location / { 
   if (!-e $request_filename) {
   	rewrite  ^(.*)$  /index.php?s=/$1  last;
   }
}
```
#### nginx下载规则
```
location /下载目录名 {
    internal;
}
```
#### 捐赠我们
如果您对我们的成果表示认同或者觉得对您有所帮助可以给我们捐赠。

**支付宝捐赠**

![输入图片说明](runtime/1639174033.jpg)

**微信捐赠**

![输入图片说明](runtime/mm_facetoface_collect_qrcode_1639173945300.png)
