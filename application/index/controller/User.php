<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Certify;
use app\common\model\Profit;
use app\common\model\Record;
use app\common\model\Shares;
use app\common\model\Users;
use app\common\model\Withdraw;
use think\Exception;
use think\facade\Cache;
use function MongoDB\BSON\toRelaxedExtendedJSON;

class User extends Home
{

    public function index(){
        return $this->fetch();
    }

    public function login(){
        $layer = input('get.layer',0);
        $share_id = input('get.share_id',0);

        if(empty($layer)) $layer = 0;

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

        $this->assign('share_id',$share_id);
        $this->assign('layer',$layer);
        return $this->fetch();
    }

    public function register(){
        $data = input('post.');
        $share_id = input('get.share_id');

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

        // 获取注册来源
        $share_info = Shares::where('id',$share_id)->find();

        try {
            (new Users)->register(
                $data['username'],
                $data['email'],
                $data['password'],
                $default_group,
                ['nickname' => $data['nickname']]
            );

            // 统计数据
            if(!empty($share_info)){
                Profit::record($share_info['uid'],$share_info['source_id'],'reg',1);
            }

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
        if($this->request->isMobile()){
            return $this->fetch('vip_wap');
        }
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

    public function auth(){
        if($this->request->isPost()){
            $data = input('post.');
            $result = $this->validate($data,[
               'name|姓名' => 'require|chs|length:2,20',
                'idcard|身份证号码' => 'require|idCard'
            ]);

            if($result !== true) return json(['code' => 0,'msg' => $result]);

            if($this->userInfo['is_auth']){
                return json(['code' => 0,'msg' => '您已完成实名认证，请勿重复提交']);
            }

            if(Certify::where('uid',$this->userInfo['id'])->where('status',0)->count() > 0){
                return json(['code' => 0,'msg' => '您提交的实名资料正在审核中，请勿重复提交']);
            }

            Certify::create([
                'uid' => $this->userInfo['id'],
                'name' => $data['name'],
                'idcard' => $data['idcard'],
                'create_time' => time()
            ]);

            return json(['code' => 1,'msg' => '实名资料已提交，请耐心等待审核']);
        }

        $this->assign('info',$this->userInfo->getCertify());
        $this->assign('is_auth',$this->userInfo['is_auth']);
        return $this->fetch();
    }

    public function withdraw(){
        if($this->request->isPost()){
            $data = input('post.');
            $result = $this->validate($data,[
               'tx_money|提现金额' => 'require|float',
                'alipay_account|支付宝帐号' => 'require|max:255',
                'alipay_name|支付宝姓名' => 'require|chs|max:60'
            ]);

            if($result !== true) return json(['code' => 0,'msg' => $result]);

            $withdraw_count = Withdraw::where('uid',$this->userInfo['id'])
                ->where('status',0)
                ->whereTime('create_time','d')
                ->count();

            if($withdraw_count > 0){
                return json(['code' => 0,'msg' => '您有一条提现申请还在审核中，请勿重复提交']);
            }

            if(floatval($this->userInfo['amount']) - floatval($data['tx_money']) < 0){
                return json(['code' => 0,'msg' => '您的账户余额不足']);
            }

            // 扣钱
            Record::addRecord($this->userInfo['id'],1,'用户提现',$data['tx_money'],'余额提现'.$data['tx_money'].'元至支付宝');

            // 添加记录
            Withdraw::create([
                'uid' => $this->userInfo['id'],
                'money' => $data['tx_money'],
                'alipay_account' => $data['alipay_account'],
                'alipay_name' => $data['alipay_name'],
                'create_time' => time()
            ]);

            return json(['code' => 1,'msg' => '申请成功，请等待处理']);
        }
        $this->assign('amount',$this->userInfo['amount']);
        return $this->fetch();
    }

}