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

function getFileIcon($file_ext,$type = 'admin'): string
{
    $file_types = [
        'video' => ['mp4','avi','mov','rmvb','rm','asf','divx','mpg','mpeg','mpe','wmv','mp4','mkv','vob','swf','flv'],
        'music' => ['cd','mp3','flac','ape','wma','mid','midi','mmf','ncm','wav','dts','dsf'],
        'code' => ['c','php','py','py3','cpp','h','jar','html','hta','chm','css','js','htm','asp','aspx','jsp','dll','cs','go','sql','xml','vb','java','lib','e','ec','db','bat','vbs','cmd','json','vbe','ocx','conf','sh','dat'],
        'zip' => ['zip','tar','gz','rar','7z','arj','z','iso','gho'],
        'ps' => ['ps','psd','pdd','eps','iff','tdi','pcx','raw'],
        'img' => ['png','bmp','rle','dib','gif','ico','jpeg','jpe','jff','jps','jpg','psb','svg','pbm','mp0'],
        'fonts' => ['ttf','eot','woff','otf','woff2'],
        'text' => ['txt','md','rtf','ini'],
        'word' => ['doc','docx','docm'],
        'excel' => ['xls','xlsx','xlsm'],
        'ppt' => ['ppt','pptx','pdf','pdp'],
        'links' => ['url','lnk'],
        'exe' => ['exe','msi'],
        'ipa' => ['ipa'],
        'apk' => ['apk']
    ];

    $file_mime = 'unknown';

    if($file_ext == 'dir'){
        $file_mime = 'folder';
    }else{
        foreach ($file_types as $mime_name => $item){
            foreach ($item as $ext){
                if($ext == $file_ext){
                    $file_mime = $mime_name;
                    break;
                }
            }
        }
    }

    if($type == 'admin'){
        return '/static/admin/images/file_ext/'.$file_mime.'.png';
    }else{
        return '/assets/file_ext/'.$file_mime.'.png';
    }
}


function getUserHead($user_head): string
{
    if(empty($user_head)){
        return '/assets/image/userhead.png';
    }
    return '';
}

function getVipRule(){
    $vip_rule = config('vip.vip_rule');

    $rule_list = explode('<br />',nl2br($vip_rule));

    foreach ($rule_list as $key => $item){
        $keys = ['name', 'money', 'day','day_name', 'discount', 'discount_msg', 'desc','is_top'];
        $rule = array_combine($keys,explode('|',trim($item)));
        $rule['id'] = $key;
        $rule_list[$key] = $rule;
    }

    return $rule_list;
}

function shortUrl($url){

    $hex = md5($url);
    $base32 = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $hexLen = strlen($hex);
    $subHexLen = $hexLen / 8;
    $output = [];
    for( $i = 0; $i < $subHexLen; $i++ ) {
        $subHex = substr($hex, $i*8, 8);
        $idx = 0x3FFFFFFF & (1 * hexdec('0x' . $subHex));
        $out = '';
        for( $j = 0; $j < 6; $j++ )
        {
            $out .= $base32[0x0000003D & $idx];
            $idx = $idx >> 5;
        }
        $output[$i] = $out;
    }

    $code = array_shift($output);

    $suc = substr(microtime(),2,4);

    foreach (str_split($suc,1) as $k){
        $code .= substr(str_shuffle($base32),$k,1);
    }

    return $code;
}

function getRndSharePwd($len = 4){
    $string = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $result = '';
    for ($i = 0;$i < $len;$i++){
        $result .= substr(str_shuffle($string),rand(0,strlen($string) - 1),1);
    }
    return $result;
}

function getShareUrl($code): string
{
    return url('index/share',['code' => $code],false,true);
}

function friendDate($time, $format='Y-m-d'){
    if (!$time)
        return '';

    $nowtime = time();
    if ($time > $nowtime){
        return date($format, $time);
    }

    $Y = date('Y', $time);//年份
    $z = date('z', $time);//当前的第几天
    $nowY = date('Y', $nowtime);
    $nowz = date('z', $nowtime);

    if ($z > $nowz){
        $nowz += 365;
    }
    $diffz = $nowz - $z;//获取差异天
    $diffs = $nowtime - $time;//获取差异秒

    if ($diffz >= 365){
        return ($nowY-$Y).'年前';
    } elseif ($diffz >= 30){
        return floor($diffz / 30).'个月前';
    } elseif ($diffz >= 7){
        return floor($diffz / 7).'个星期前';
    } elseif ($diffz >= 1){
        return $diffz.'天前';
    } elseif ($diffs >= 3600) {
        return floor($diffs / 3600).'小时前';
    } elseif ($diffs >= 300) {
        return floor($diffs / 60).'分钟前';
    } else {//五分钟内
        return '刚刚'.$diffs.'秒';
    }
}

function getSafeNickname($name): string
{
    if(mb_strlen($name) <= 2){
        $name = mb_substr($name,0,1) . '*';
    }else{
        $len = mb_strlen($name);
        $sub_len = $len - 2;
        $name = mb_substr($name,0,1) . '*' . str_repeat('*',$sub_len) .  mb_substr($name,$len - 1,1);
    }
    return $name;
}

function getSafeDirSeparator($dir){
    return str_replace(['/','\\','//','\\\\'],DIRECTORY_SEPARATOR,$dir);
}