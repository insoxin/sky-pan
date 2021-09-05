<?php

namespace app\common\model;

use think\Exception;
use think\Model;

class User extends Model
{

    /**
     * 用户注册方法
     * @param $phone
     * @param $email
     * @param $password
     * @throws Exception
     */
    public function register($phone,$email,$password){

        if(self::where('phone',$phone)->count() > 0){
            throw new Exception('当前手机号码已被注册');
        }

        if(self::where('email',$email)->count() > 0){
            throw new Exception('当前安全邮箱已被绑定');
        }

        $salt = substr(md5(uniqid()),0,6);

        $en_password = md5($password.$salt);

        $user = [
            'phone' => $phone,
            'email' => $email,
            'password' => $en_password,
            'salt' => $salt,
            'create_time' => time(),
            'status' => 1
        ];

        self::insert($user);

    }

    public function login(){

    }

    public function logout(){

    }

    public function forget(){

    }

    
}