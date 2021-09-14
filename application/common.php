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


function getNotNullTime($time, $default = '', $format = 'Y-m-d H:i:s'){
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
        return '刚刚';
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

function sendEmail($username,$url){
    // 邮件配置
    $config = config('email.');

    // 获取模板变量
    $temp_var = [
        '{username}' => $username,
        '{site_title}' => config('basic.site_title'),
        '{url}' => $url
    ];

    // 邮件正文
    $email_body = str_replace(array_keys($temp_var),array_values($temp_var),$config['template_forget']);

    // php mail客户端
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    // debug调试
    $mail->SMTPDebug = 0;
    //  使用smtp鉴权方式
    $mail->isSMTP();
    // SMTP鉴权
    $mail->SMTPAuth = true;
    // 邮箱服务器地址
    $mail->Host = $config['smtp_host'];
    // SSL加密
    $mail->SMTPSecure = 'ssl';
    // 端口
    $mail->Port = $config['smtp_port'];
    // 邮件的编码
    $mail->CharSet = 'UTF-8';
    // smtp账号
    $mail->Username = $config['username'];
    // smtp密码
    $mail->Password = $config['password'];
    // 设置发件人昵称
    $mail->FromName = $config['nickname'];
    // 设置发件人邮箱
    $mail->From = $config['username'];
    // 邮件正文为html编码
    $mail->isHTML(true);
    // 收件人邮箱
    $mail->addAddress('1655545174@qq.com');
    // 邮件标题
    $mail->Subject = '【'.config('basic.site_title').'】找回密码邮件';

    // 邮件正文
    $mail->Body = $email_body;

    // 发送邮件 返回状态
    return $mail->send();
}

function getFileDownloadUrl($shares_id,$file_id,$is_count = 1){

    $params = [
        'file' => $file_id,
        'shares' => $shares_id,
        'timestamp' => time(),
    ];

    $sign = md5(urldecode(http_build_query($params)) . config('app.pass_salt'));

    $params['sign'] = $sign;
    $params['is_count'] = $is_count;

    return url('Index/download','',false,true).'?'.urldecode(http_build_query($params));
}

function getDownloadFileSignVerify($param,$sign): bool
{
    $sign_key = md5(urldecode(http_build_query($param)) . config('app.pass_salt'));
    return $sign_key == $sign;
}

function getFileName($field_id){
    return \app\common\model\Stores::where('id',$field_id)->value('origin_name');
}

function getWeekDay($time){
    $week = ["日","一","二","三","四","五","六"];
    return '星期'.$week[date('w',$time)];
}

function getTimeLastDay($time){
    $now_time = strtotime(date('Y-m-d'));
    $the_time = strtotime(date('Y-m-d',$time));
    $day_lazy = $now_time - $the_time;
    return floor($day_lazy / 86400);
}

