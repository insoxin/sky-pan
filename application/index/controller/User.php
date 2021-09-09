<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Users;
use think\Exception;

class User extends Home
{

    public function index(){
        $this->assign('group',$this->groupData);
        $this->assign('info',$this->userInfo);
        return $this->fetch();
    }

    public function login(){

        if((new Users)->login_auth('default')){
            return redirect('user/index');
        }

        if($this->request->isPost()){
            $username = input('post.username');
            $password = input('post.password');

            try {
                (new Users)->login($username,$password,'default');

                return json(['status' => 1,'msg' => '登录成功']);
            }catch (\Throwable $e){
                return json(['status' => 0,'msg' => '登录失败：' . $e->getMessage()]);
            }
        }
        return $this->fetch();
    }

    public function register(){
        $data = input('post.');

        if(config('register.allow_register') != 1){
            return json(['code' => 0,'msg' => '管理员已关闭用户注册功能']);
        }

        $result = $this->validate($data,[
            'nickname|昵称' => 'require|chsAlphaNum|length:2,18',
            'username|用户帐号' => 'require|alphaNum|length:6,26',
            'password|登录密码' => 'require|alphaNum|length:6,18',
            'email|安全邮箱' => 'require|email'
        ]);

        if($result !== true) return json(['code' => 0,'msg' => $result]);

        $default_group = config('register.default_group');

        try {
            (new Users)->register(
                $data['username'],
                $data['email'],
                $data['password'],
                $default_group,
                ['nickname' => $data['nickname']]
            );
            return json(['code' => 1,'msg' => '注册帐号成功']);
        }catch (Exception $e){
            return json(['code' => 0,'msg' => $e->getMessage()]);
        }
    }

    public function logout(){
        (new Users)->logout('default');
        $this->success('退出登录成功','index/index');
    }

    public function recycle(){
        return $this->fetch();
    }

    public function shouyi(){
        return $this->fetch();
    }

}