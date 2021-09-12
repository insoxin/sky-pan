<?php

namespace app\common\model;

use think\Model;

class Record extends Model
{

    public static function addRecord($uid,$type,$source,$money,$remark){
        $user = Users::where('id',$uid)->find();

        // 操作前金额
        $before_money = $user['amount'];
        // 操作
        if($type){
            $after_money = floatval($user['amount']) - floatval($money);
        }else{
            $after_money = floatval($user['amount']) + floatval($money);
        }

        if($after_money < 0){
            return false;
        }

        if($type){
            Users::where('id',$uid)->setDec('amount',$money);
        }else{
            Users::where('id',$uid)->setInc('amount',$money);
        }

        return self::create([
            'uid' => $uid,
            'type' => $type,
            'source' => $source,
            'money' => $money,
            'before_money' => $before_money,
            'after_money' => $after_money,
            'remark' => $remark,
            'create_time' => time()
        ]);
    }

}