<?php

namespace app\common\model;

use think\Model;

class Policys extends Model
{

    public function addPolicy($data){
        //基础参数
        $keys = ['name','type','filetype'];

        // 附加参数
        $field = [];

        foreach ($data as $key => $item){
            if(!in_array($key,$keys)){
                $field[$key] = $item;
                unset($data[$key]);
            }
        }

        $data['config'] = json_encode($field);

        self::insert($data);
    }

    public function editPolicy($id,$data){
        //基础参数
        $keys = ['name','type','filetype'];
        // 附加参数
        $field = [];
        foreach ($data as $key => $item){
            if(!in_array($key,$keys)){
                $field[$key] = $item;
                unset($data[$key]);
            }
        }
        $data['config'] = json_encode($field);
        self::where('id',$id)->update($data);
    }

    public function getConfigAttr($value){
        return json_decode($value,true);
    }

    public static function getPolicyAll(): array
    {
        return self::field('id,name')->select()->toArray();
    }

}