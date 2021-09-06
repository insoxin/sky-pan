<?php

namespace app\common\model;

use think\Model;

class Groups extends Model
{

    public function addGroup($data){
        $data["max_storage"] = $data["max_storage"] * $data["storage_size"];
        unset($data["storage_size"]);
        self::insert($data);
    }

}