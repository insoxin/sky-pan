<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Groups;
use app\common\model\Setting as SettingModel;
use think\facade\Cache;

class Setting extends Admin
{


    public function basic(){
        $this->saveOptions();
        $this->assign('option',$this->getOptions());
        return $this->fetch();
    }


    public function register(){
        $this->saveOptions();
        $group = Groups::column('group_name','id');
        $this->assign('group',$group);
        $this->assign('option',$this->getOptions());
        return $this->fetch();
    }


    protected function saveOptions(){
        if($this->request->isPost()){
            $type = $this->request->action();
            $post = input('post.');

            try {
                // 更新数据库
                foreach ($post as $key => $item){
                    SettingModel::where('set_name',$key)
                        ->where('set_type',$type)
                        ->update(['set_value' => $item]);
                }
            }catch (\Throwable $e){
                $this->returnError($e->getMessage());
            }

            // 更新缓存
            $setting = SettingModel::select()->toArray();
            Cache::set('_setting_config',$setting);

            $this->returnSuccess('保存成功');
        }
    }

    protected function getOptions(): array
    {
        $type = $this->request->action();
        $settings = SettingModel::where('set_type',$type)->select();
        $options = [];
        foreach ($settings as $item){
            $options[$item['set_name']] = $item['set_value'];
        }
        return $options;
    }

}