<?php

namespace app\common\model;

use app\common\exception\AdminLoginError;
use think\facade\Session;
use think\Model;

class Admin extends Model
{

    /**
     * 管理员登录
     * @param $username
     * @param $password
     * @return bool
     * @throws AdminLoginError
     */
    public function login($username,$password){
        $user = self::where('username',$username)->find();

        if(empty($user)){
            throw new AdminLoginError('账号不存在');
        }

        if($user['password'] != md5($password)){
            throw new AdminLoginError('账号或密码不正确');
        }

        //登录成功
        self::where('id',$user['id'])->update(['last_login_time' => time()]);

        $auth_token = md5($user['username'] . $user['password']);

        Session::set('admin_auth_token',$auth_token);
        Session::set('admin_user_name',$user['username']);

        return true;
    }


    /**
     * 退出登录
     * @return bool
     */
    public function logout(){
        Session::clear('admin_auth_token');
        Session::clear('admin_user_name');
        return true;
    }


    /**
     * 判断是否登录
     * @return bool
     */
    public function auth_login(){
        $token = Session::get('admin_auth_token');
        $username = Session::get('admin_user_name');

        if(empty($token) || empty($username)){
            return false;
        }

        return true;
    }

    /**
     * 修改密码
     * @param $key
     * @param $value
     * @param $new_value
     * @return Admin
     */
    public function edit($key,$value,$new_value){
        return self::where($key,$value)->update([$key => $new_value]);
    }

}