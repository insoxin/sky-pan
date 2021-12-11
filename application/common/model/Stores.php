<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Stores extends Model
{

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $autoWriteTimestamp = true;

    public function policy(){
        return $this->hasOne('Policys','id','policy_id');
    }

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

    public function getLocalSaveFile($save_dir,$filename){
        return str_replace(['/','\\','//','\\\\'],'/',($save_dir.$filename));
    }

}