<?php

namespace app\common\behavior;

use app\common\model\Setting;
use think\facade\Cache;
use think\facade\Config;

class LoadConfigRun
{

    public function run(){

        $conf_cache = Cache::get('_setting_config');

        if(empty($conf_cache)){
            $conf_cache = Setting::select()->toArray();
            Cache::set('_setting_config',$conf_cache);
        }

        // 加载配置
        foreach ($conf_cache as $item){
            Config::set([$item['set_name'] => $item['set_value']],$item['set_type']);
        }

    }

}