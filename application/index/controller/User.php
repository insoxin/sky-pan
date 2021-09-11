<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Users;
use think\Exception;
use think\facade\Cache;
use function MongoDB\BSON\toRelaxedExtendedJSON;

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

    public function vip(){
        $rule = getVipRule();
        $this->assign('rule',$rule);
        return $this->fetch();
    }

    public function forget(){
        if($this->request->isPost()){
            $email = input('post.email');
            $result = $this->validate(['email' => $email],['email|安全邮箱' => 'require|email']);
            if($result !== true) return json(['code' => 0,'msg' => $result]);
            $info = Users::where('email',$email)->find();
            if(empty($info)){
                return json(['code' => 0,'msg' => '该邮箱未绑定帐号']);
            }

            // 获取key
            $forget_key = 'forget_'.str_replace(['@','.'],['_','_'],$info['email']);

            // 邮件发送记录
            $forget_info = Cache::get($forget_key);

            if(!empty($forget_info)){
                if($forget_info['time'] >= time()){
                    return json(['code' => 0,'msg' => '发送频繁，请等待'.($forget_info['time'] - time()) . ' 秒后重新发送']);
                }
            }

            $hash_key = bin2hex($forget_key);
            $sign = md5($forget_key . time());
            // 设置找回密码key
            Cache::set($forget_key,[
                'uid' => $info['id'],
                'email' => $info['email'],
                'sign' => $sign,
                'time' => time() + 60
            ],600);

            // 找回密码链接
            $forget_url = url('user/reset','',false,true)."?key={$hash_key}&sign={$sign}";

            // 发送邮件
            if(!sendEmail($info['username'],$forget_url)){
                return json(['code' => 0,'msg' => '找回密码邮件发送失败，请联系管理员处理']);
            }

            return json(['code' => 1,'msg' => '找回密码邮件发送成功']);
        }
        return $this->fetch();
    }

    public function reset(){
        $key = input('get.key');
        $sign = input('get.sign');

        if(empty($key) || empty($sign)){
            return $this->fetch('err',['msg' => '参数缺失']);
        }

        $forget_key = @hex2bin($key);

        if(empty($forget_key)){
            return $this->fetch('err',['msg' => '参数验证失败']);
        }

        $forget_info = Cache::get($forget_key);

        if(empty($forget_info)){
            return $this->fetch('err',['msg' => '访问错误，无效链接']);
        }

        if($sign != $forget_info['sign']){
            return $this->fetch('err',['msg' => '访问错误，数据签名校验失败']);
        }

        if($this->request->isPost()){
            $pwd = input('post.pwd');
            $result = $this->validate(['pwd' => $pwd],['pwd|密码' => 'require|alphaNum|length:6,18']);
            if($result !== true) return json(['code' => 0,'msg' => $result]);

            $password = md5($pwd . config('app.pass_salt'));

            Users::where('id',$forget_info['uid'])->update(['password' => $password]);

            return json(['code' => 1,'msg' => '密码修改成功']);
        }

        $this->assign('key',$key);
        $this->assign('sign',$sign);
        return $this->fetch('reset');
    }

}