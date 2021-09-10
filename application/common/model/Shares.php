<?php

namespace app\common\model;

use think\Exception;
use think\Model;

class Shares extends Model
{

    public static function addShare($uid,$source,$type){

        $share_code = '';

        for ($i = 0;$i < 5;$i++){
            $code = shortUrl($uid.'_'.$source.'_'.$type.time());
            if(self::where('code',$code)->count() == 0){
                $share_code = $code;
                break;
            }
        }

        if(empty($share_code)){
            throw new Exception('分享连接生成失败，请稍后重试');
        }

        $pwd = getRndSharePwd();

        if(!$type){
            $status = 0;
        }else{
            $status = 1;
        }

        $data = [
            'uid' => $uid,
            'source_id' => $source,
            'type' => $type,
            'speed' => 0,
            'code' => $code,
            'pwd' => $pwd,
            'pwd_status' => $status
        ];

        return self::insertGetId($data);
    }

    public static function getShare($uid,$source,$type){
        return self::where([
            ['uid','=',$uid],
            ['source_id','=',$source],
            ['type','=',$type]
        ])->field('id,code,pwd,pwd_status,speed')->find();
    }

    public function delShare(){

    }

    public static function updateShare($id,$data){
        return self::where('id',$id)->update($data);
    }

}