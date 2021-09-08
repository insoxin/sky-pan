<?php

return [

    [
        'id' => 1,
        'title' => '面板首页',
        'icon' => 'layui-icon layui-icon-console',
        'type' => 1,
        'href' => url('dashboard/index'),
    ],
    [
        'id' => 2,
        'title' => '用户管理',
        'icon' => 'layui-icon layui-icon-component',
        'type' => 0,
        'href' => '',
        'children' => [
            [
                'id' => 121,
                'title' => '用户列表',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('user/index')
            ],
            [
                'id' => 122,
                'title' => '用户组管理',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('group/index')
            ],
        ]
    ],
    [
        'id' => 3,
        'title' => '文件管理',
        'icon' => 'layui-icon layui-icon-template-1',
        'type' => 0,
        'href' => '',
        'children' => [
            [
                'id' => 131,
                'title' => '文件列表',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('file/index')
            ],
            [
                'id' => 132,
                'title' => '文件回收站',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('recycle/index')
            ],
        ]
    ],
    [
        'id' => 4,
        'title' => '储存策略',
        'icon' => 'layui-icon layui-icon-util',
        'type' => 1,
        'href' => url('policy/index'),
    ],
    [
        'id' => 5,
        'title' => '订单记录',
        'icon' => 'layui-icon layui-icon-chart',
        'type' => 1,
        'href' => url('order/index'),
    ],
    [
        'id' => 6,
        'title' => '提现列表',
        'icon' => 'layui-icon layui-icon-form',
        'type' => 1,
        'href' => url('withdraw/index'),
    ],
    [
        'id' => 7,
        'title' => '实名认证',
        'icon' => 'layui-icon layui-icon-vercode',
        'type' => 1,
        'href' => url('withdraw/index'),
    ],
    [
        'id' => 8,
        'title' => '举报处理',
        'icon' => 'layui-icon layui-icon-about',
        'type' => 1,
        'href' => url('report/index'),
    ],
    [
        'id' => 9,
        'title' => '模板管理',
        'icon' => 'layui-icon layui-icon-theme',
        'type' => 1,
        'href' => url('template/index'),
    ],
    [
        'id' => 10,
        'title' => '系统设置',
        'icon' => 'layui-icon layui-icon-set-fill',
        'type' => 0,
        'href' => '',
        'children' => [
            [
                'id' => 101,
                'title' => '基本设置',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('setting/basic')
            ],
            [
                'id' => 102,
                'title' => '注册访问',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('setting/register')
            ],
            [
                'id' => 103,
                'title' => '支付配置',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('setting/pay')
            ],
            [
                'id' => 104,
                'title' => '邮件配置',
                'type' => 1,
                'openType' => '_iframe',
                'href' => url('setting/email')
            ]
        ]
    ]

];