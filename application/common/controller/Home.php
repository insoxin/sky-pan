<?php

namespace app\common\controller;

use app\common\model\Groups;
use app\common\model\Policys;
use app\common\model\Users;
use think\Controller;

class Home extends Controller
{

    protected $middleware = ['UserLoginCheck'];

    protected $userInfo = [];

    protected $groupData = [];

    protected $vip_info = [];

    protected $is_login = 0;

    public function initialize(){
        parent::initialize(); // TODO: Change the autogenerated stub

        $this->userInfo = (new Users())->login_info('default');

        $this->groupData = Groups::where('id',$this->userInfo['group'])->find();

        // 是否登录
        $this->is_login = empty($this->userInfo) ? 0 : 1;

        // 获取VIP用户组ID
        $vip_group = config('vip.vip_group');

        // 获取普通用户组ID
        $default_group = config('register.default_group');

        // 判断是否VIP
        if($this->userInfo['group'] == $vip_group){
            // VIP过期
            if(intval($this->userInfo['group_expire']) < time()){
                Users::where('id',$this->userInfo['id'])->update([
                    'group' => $default_group,
                    'group_expire' => 0
                ]);
                $this->vip_info['is_vip'] = 0;
                $this->vip_info['expire_time'] = 0;
            }else{
                $this->vip_info['is_vip'] = 1;
                $this->vip_info['expire_time'] = date('Y-m-d H:i',$this->userInfo['group_expire']);
            }
        }else{
            $this->vip_info['is_vip'] = 0;
            $this->vip_info['expire_time'] = 0;
        }

        $this->assign('group',$this->groupData);
        $this->assign('info',$this->userInfo);
        $this->assign('url_path',$this->request->path());
    }

    protected function getPolicy(): array
    {
        $policy = Policys::where('id',$this->groupData['policy_id'])->find()->toArray();

        $policy['config'] = json_decode($policy['config'],true);

        return $policy;
    }

}