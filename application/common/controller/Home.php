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

        // 是否登录
        $this->is_login = empty($this->userInfo) ? 0 : 1;

        // 用户组信息
        if($this->is_login){
            // 登录用户组
            $this->groupData = Groups::where('id',$this->userInfo['group'])->find();
        }else{
            // 游客用户组
            $this->groupData = Groups::where('id',2)->find();
        }

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
                $this->vip_info['expire_time'] = date('Y-m-d',$this->userInfo['group_expire']);
            }
        }else{
            $this->vip_info['is_vip'] = 0;
            $this->vip_info['expire_time'] = 0;
        }

        $this->assign('group',$this->groupData);
        $this->assign('info',$this->userInfo);
        $this->assign('is_login',$this->is_login);
        $this->assign('vip_info',$this->vip_info);
        $this->assign('url_path',$this->request->path());
    }

    protected function getPolicy(): array
    {
        $policy = Policys::where('id',$this->groupData['policy_id'])->find()->toArray();

        return $policy;
    }

    protected function getPolicyUrl($policy,$param = []): string
    {
        if($policy['type'] == 'local'){
            return url('upload/file');
        }

        $data = [
            'uid' => $this->userInfo['id'],
            'policy_id' => $policy['id'],
            'save_dir' => $policy['config']['save_dir']
        ];

        $data = array_merge($data,$param);

        $data['sign'] = $this->remote_sign_params($data,$policy['config']['access_token']);

        return $policy['config']['server_uri'].'?'.urldecode(http_build_query($data));
    }

    protected function remote_sign_params($params,$key): string
    {
        // 过滤参数
        $params = array_filter($params,function($key) use ($params){
            if(empty($params[$key]) || $key == 'sign'){
                return false;
            }
            return true;
        },ARRAY_FILTER_USE_KEY);
        // ascii排序
        ksort($params);
        reset($params);
        // 签名
        return md5(urldecode(http_build_query($params)) . $key);
    }


}