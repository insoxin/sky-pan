layui.use(['admin', 'jquery', 'layer','element'], function() {
    var $ = layui.jquery;
    var layer = layui.layer;
    var layelem = layui.element;
    var admin = layui.admin;
    // 框 架 初 始 化
    admin.render({
        "logo": {
            "title": skpan_config.title,
            "image": skpan_config.logo
        },
        "menu": {
            "data": skpan_config.menu,
            "accordion": true,
            "control": false,
            "select": skpan_config.menu_select,
            async: false
        },
        "tab": {
            "muiltTab": false,
            "keepState": false,
            "session": true,
            "tabMax": 30,
            "index": {
                id: "1",
                href: skpan_config.home_url,
                title: "仪表面板"
            }
        },
        "theme": {
            "defaultColor": "3",
            "defaultMenu": "dark-theme",
            "allowCustom": true
        },
        "colors": [
            {
                "id": "3",
                "color": "#1E9FFF"
            }
        ],
        "links": [],
        "other": {
            "keepLoad": 100
        },
        "header":{
            message: false
        }
    });

    layelem.on('nav(layui_nav_right)', function(elem) {
        if ($(elem).hasClass('logout')) {
            layer.confirm('确定退出登录吗?', function(index) {
                layer.close(index);
                $.ajax({
                    url: skpan_config.logout_url,
                    type:"POST",
                    dataType:"json",
                    success: function(res) {
                        if (res.code==200) {
                            layer.msg(res.msg, {
                                icon: 1
                            });
                            setTimeout(function() {
                                location.href = skpan_config.index_url;
                            }, 333)
                        }
                    }
                });
            });
        }else if ($(elem).hasClass('password')) {
            layer.open({
                type: 2,
                maxmin: true,
                title: '修改密码',
                shade: 0.1,
                area: ['300px', '300px'],
                content:skpan_config.pass_url
            });
        }else if ($(elem).hasClass('cache')) {
            $.post(skpan_config.clear_cache_url,
                function(data){
                    layer.msg(data.msg, {time: 1500});
                    location.reload()
                });

        }

    });
})