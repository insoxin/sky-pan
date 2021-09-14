<?php

namespace app\common\model;

use think\Model;

class Profit extends Model
{


    public static function record($uid,$file_id,$field,$value = 1): bool
    {

        $fp = fopen('record_lock.txt', "w+");

        if(flock($fp,LOCK_EX | LOCK_NB)){
            // 判断今日是否统计
            $rec = self::where('uid',$uid)->where('file_id',$file_id)->whereTime('create_time','d')->find();

            // 补全字段
            $field = 'count_'.$field;

            if($field == 'count_view'){
                Stores::where('id',$file_id)->where('uid',$uid)->setInc('count_open',1);
            }

            if($field == 'count_down'){
                Stores::where('id',$file_id)->where('uid',$uid)->setInc('count_down',1);
            }


            if(!empty($rec)){

                self::where('uid',$uid)->where('file_id',$file_id)->setInc($field,$value);

                flock($fp,LOCK_UN);
                return true;
            }

            // 插入新统计
            $data = [
                'uid' => $uid,
                'file_id' => $file_id,
                'create_time' => time(),
                $field => $value
            ];

            // 插入统计
            self::insert($data);

            flock($fp,LOCK_UN);
            return true;
        } else{
            fclose($fp);
            return false;
        }
    }


}