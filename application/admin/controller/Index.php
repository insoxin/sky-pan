<?php

namespace app\admin\controller;

use app\common\controller\Admin;

class Index extends Admin
{

    public function index(){

        $theme = [
            // 标题
            'title' => 'SkPan网盘',
            // logo
            'logo' => '/static/admin/images/logo.png',
            // 菜单
            'menu' => json_encode(config('menu.')),
            // 管理员
            'admin' => [
                'avatar' => '/static/admin/images/avatar.jpg',
                'nickname' => 'Admin'
            ],
            // 激活菜单
            'menu_select' => 1,
            // 首页url
            'home_url' => url('dashboard/index'),
            // 后台url
            'index_url' => url('index/index'),
            // 退出登录url
            'logout_url' => url('auth/logout'),
            // 修改密码url
            'pass_url' => url('auth/pass'),
            // 清除缓存
            'clear_cache_url' => url('index/cache')
        ];

        $this->assign('theme',$theme);
        return $this->fetch();

    }

    public function cache(){

    }

}