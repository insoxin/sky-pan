<?php

namespace app\common\model;

use think\Model;

class Profit extends Model
{

    // 用户ID
    protected $uid = 0;

    // 文件ID
    protected $file_id = 0;

    // 统计字段
    protected $field_count = [];


    public function source($uid,$file_id): Profit
    {
        $this->uid = $uid;
        $this->file_id = $file_id;

        return $this;
    }


    public function addCount($field = '',$value = 0): Profit
    {
        $this->field_count[] = [
            'name' => $field,
            'value' => $value
        ];

        return $this;
    }

    public function record(){
        // 判断今日是否统计
        $rec = self::where('uid',$this->uid)->where('file_id',$this->file_id)->whereTime('create_time','d')->find();

        // 更新统计
        if(!empty($rec)){
            foreach ($this->field_count as $item){
                $rec->setInc('count_'.$item['name'],$item['value']);
            }
            //保存
            return $rec->save();
        }

        // 插入新统计
        $data = [
            'uid' => $this->uid,
            'file_id' => $this->file_id,
            'create_time' => time()
        ];

        foreach ($this->field_count as $item){
            $data['count_'.$item['name']] = $item['value'];
        }

        // 插入统计
        return self::insert($data);
    }

}