<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\common\model\Admin;
use think\captcha\Captcha;

class Auth extends AdminController
{

    public function login(){
        if($this->request->isPost()){
            $username = input('post.username');
            $password = input('post.password');
            $captcha = input('post.captcha');
            try {
                if(!captcha_check($captcha)){
                    throw new \Exception('验证码错误');
                }
                (new Admin)->login($username,$password);

                return json(['code' => 200,'msg' => '登录成功，正在跳转后台..']);
            }catch (\Throwable $e){
                return json(['code' => 502,'msg' => $e->getMessage()]);
            }
        }else{
            return $this->fetch();
        }
    }

    public function logout(){

    }

    public function pass(){

    }

    public function verify(){
        $config = [
            'length' => 4
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

}