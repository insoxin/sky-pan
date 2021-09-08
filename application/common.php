<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function getNotNullTime($time,$default = '',$format = 'Y-m-d H:i:s'){
    if($time > 0){
        return date($format,$time);
    }else{
        return $default;
    }
}

function countSize($bit,$array=false){
    $type = array('Bytes','KB','MB','GB','TB');
    $box = array('1','1024','1048576','1073741824','TB');
    for($i = 0; $bit >= 1024; $i++) {
        $bit/=1024;
    }
    if($array){
        return [(floor($bit*100)/100),$box[$i]];
    }
    return (floor($bit*100)/100).$type[$i];
}

function PolicyType($d): string
{
    $type = [
        'local' => '本地',
        'remote' => '远程'
    ];

    return $type[$d] ?? '未知';
}