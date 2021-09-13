<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Users;
use think\captcha\Captcha;

class Auth extends Admin
{

    public function login(){

        if((new Users)->login_auth('admin')){
            return redirect('index/index');
        }

        if($this->request->isPost()){
            $username = input('post.username');
            $password = input('post.password');
            $captcha = input('post.captcha');
            try {
                if(!captcha_check($captcha)){
                    throw new \Exception('验证码错误');
                }

                (new Users)->login($username,$password,'admin');

                return json(['code' => 200,'msg' => '登录成功，正在跳转后台..']);
            }catch (\Throwable $e){
                return json(['code' => 502,'msg' => '登录失败：' . $e->getMessage()]);
            }
        }else{
            return $this->fetch();
        }

    }

    public function logout(){
        (new Users)->logout('admin');
        return json(['code' => 200,'msg' => '退出登录成功']);
    }

    public function verify(){
        $config = [
            'length' => 4
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

}