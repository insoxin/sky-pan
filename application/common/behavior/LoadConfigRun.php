<?php

namespace app\common\behavior;

use think\facade\Cache;

class LoadConfigRun
{

    public function run(){

        $conf_cache = Cache::get('_setting_config');

        if(empty($conf_cache)){

        }

    }

}