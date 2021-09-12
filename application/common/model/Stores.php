<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Stores extends Model
{

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = true;

    public function getPolicy(){
        $policy = Policys::get($this->policy_id);
        if(empty($policy)){
            return false;
        }
        return $policy;
    }

    public function getLocalSaveFilePath($save_dir,$filename): string
    {
        return env('root_path').'public'.getSafeDirSeparator($save_dir . $filename);
    }

}