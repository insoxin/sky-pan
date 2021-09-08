<?php

namespace app\common\model;

use app\common\exception\LoginError;
use think\Exception;
use think\facade\Session;
use think\Model;

class Users extends Model
{

    /**
     * 用户注册方法
     * @param $username
     * @param $email
     * @param $password
     * @throws Exception
     */
    public function register($username,$email,$password,$group){

        if(self::where('username',$username)->count() > 0){
            throw new Exception('当前帐号已被注册');
        }

        if(self::where('email',$email)->count() > 0){
            throw new Exception('当前安全邮箱已被绑定');
        }

        $en_password = md5($password . config('app.pass_salt'));

        $user = [
            'nickname' => ucfirst($username),
            'username' => $username,
            'email' => $email,
            'group' => $group,
            'password' => $en_password,
            'create_time' => time(),
            'status' => 1
        ];

        self::insert($user);

    }


    /**
     * 用户登录登录方法
     * @param $username
     * @param $password
     * @param string $login_type
     * @return bool
     * @throws LoginError
     */
    public function login($username,$password,$login_type = 'default'){
        // 登录用户组类型
        $group = $login_type == 'admin' ? 1 : 3;
        // 查找用户
        $user = self::where('username',$username)->where('group',$group)->find();
        // 用户不存在
        if(empty($user)){
            throw new LoginError('登录帐号或者密码错误，请重试');
        }
        // 加密密码
        $password = md5($password . config('app.pass_salt'));

        if($user['password'] != $password){
            throw new LoginError('登录帐号或者密码错误，请重试');
        }

        // 不允许登录
        if($login_type == 'default' && $user['status'] == 0){
            throw new LoginError('登录帐号已被管理员封禁，请联系管理员处理！');
        }

        // 登录成功
        Session::set($login_type .'_uid',$user['id']);
        Session::set($login_type .'_lkey',md5($username . $password));

        return true;
    }


    /**
     * 退出登录方法
     * @param string $type
     */
    public function logout(string $type = 'default'){
        Session::delete($type .'_uid');
        Session::delete($type .'_lkey');
    }


    /**
     *登录验证方法
     * @param string $type
     * @return bool
     */
    public function login_auth(string $type = 'default'): bool
    {
        $uid = Session::get($type .'_uid');
        $key = Session::get($type .'_lkey');

        $user = self::where('id',$uid)->find();

        if(empty($user)){
            return false;
        }

        if($key != md5($user['username'] . $user['password'])){
            return false;
        }

        return true;
    }


    /**
     * 获取当前登录用户信息
     * @param string $type
     * @return Users|false
     */
    public function login_info(string $type = 'default'){
        $uid = Session::get($type .'_uid');

        $user = self::where('id',$uid)->find();

        if(empty($user)){
            return false;
        }

        return $user;
    }


    public function forget(){

    }

}