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
        $keys = ['name', 'money', 'day', 'discount', 'discount_msg', 'desc'];
        $rule_list[$key] = array_combine($keys,explode('|',trim($item)));
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