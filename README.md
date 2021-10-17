# SkyPan


# 代码被恶意盗用销售，所以选择开源代码

#### 介绍
一款多用户赚钱网盘程序



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


