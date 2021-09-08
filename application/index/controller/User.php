<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Users;

class User extends Home
{

    public function index(){
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


    public function recycle(){
        return $this->fetch();
    }

    public function shouyi(){
        return $this->fetch();
    }

}