<?php

namespace app\common\model;

use think\Model;

class Policys extends Model
{

    public function addPolicy($data){
        $data['max_size'] = $data["max_size"] * $data["storage_size"];
        unset($data["storage_size"]);

        //基础参数
        $keys = ['name','type','filetype','max_size'];

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
        $data['max_size'] = $data["max_size"] * $data["storage_size"];
        unset($data["storage_size"]);
        //基础参数
        $keys = ['name','type','filetype','max_size'];
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

    public static function getPolicyAll(): array
    {
        return self::field('id,name')->select()->toArray();
    }

}