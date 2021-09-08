<?php


// [ 应用入口文件 ]
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象
define('ENTRY_MODULE','admin');
// 执行应用并响应
Container::get('app')->bind('admin')->run()->send();
