# SkyPan

#### 介绍
一款多用户赚钱网盘程序，接入了多种存储系统`阿里云OSS` `腾讯云COS` `远程服务器存储` `本地存储`

#### 关于服务器方面的问题

推荐服务器环境 linux  + nginx 1.15 + Mysql 5.6 + PHP 7.3

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