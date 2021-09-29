<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Certify;
use app\common\model\Order;
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
        $share_id = input('get.share_id',0);
        $is_layer = input('get.layer',0);

        $rule = getVipRule();

        $this->assign('rule',$rule);
        $this->assign('share_id',$share_id);
        $this->assign('is_layer',$is_layer);

        if($this->request->isMobile()){
            return $this->fetch('vip_wap');
        }
        return $this->fetch();
    }

    public function payment(){
        $vip_type = input('get.vip',0);
        $share_id = input('get.share_id',0);
        $pay_type = input('get.pay_type',0);

        if($pay_type != 'alipay' && $pay_type != 'wxpay'){
            $this->error('支付方式错误');
        }

        // 获取VIP规则
        $rule = getVipRule();

        // 判断VIP类型是否正确
        if(!isset($rule[$vip_type])){
            $this->error('VIP类型不存在');
        }

        // 获取VIP价格
        $vip_config = $rule[$vip_type];

        // 获取支付配置信息
        $config = config('pay.');

        // 本地创建订单号
        $trade_no = strtoupper('VIP_'.date('YmdHis').substr(md5(uniqid()),0,10));

        // 订单数据
        $order = [
            'uid' => $this->userInfo['id'],
            'trade_no' => $trade_no,
            'type' => $pay_type,
            'profit_id' => $share_id,
            'money' => $vip_config['money'],
            'vip_day' => $vip_config['day'],
            'create_time' => time(),
            'status' => 0
        ];

        Order::create($order);

        // 获取支付参数
        $pay_params = [
            'pid' => $config['api_pid'],
            'type' => $pay_type,
            'out_trade_no' => $trade_no,
            'notify_url' => url('index/return_notify','',false,true),
            'return_url' => url('index/return_callback','',false,true),
            'name' => 'VIP会员',
            'money' => $vip_config['money']
        ];

        ksort($pay_params);
        reset($pay_params);

        $pay_params['sign'] = md5(urldecode(http_build_query($pay_params)) . $config['api_key']);
        $pay_params['sign_type'] = strtoupper('MD5');


        $pay_html = '<html><head><title>正在跳转支付</title><meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $pay_html .= '<style>body{background-color: #e6e6e6;}.pay_btn{border: 0;display: block;width: 100%;font-size: 22px;font-weight: bold;outline: none;height: 250px;color: #989898;line-height: 250px;background-color: transparent;}</style>';
        $pay_html .= '</head><body>';
        // 拼接支付表单
        $pay_html .= '<form id="alipaysubmit" name="alipaysubmit" action="'.$config['api_gateway'].'" method="POST">';
        foreach ($pay_params as $key => $val) {
            $pay_html .= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
        }
        $pay_html .= '<input class="pay_btn" type="submit" value="订单创建中..."></form>';
        $pay_html .= "<script>document.forms['alipaysubmit'].submit();</script>";
        $pay_html .= '</body></html>';

        exit($pay_html);
    }

    public function vip_close(){
        $lock_time = time() - 7200;
        Order::where('status',0)
            ->limit(50)
            ->whereTime('create_time','<=',$lock_time)
            ->update(['status' => 2]);

        return json(['code' => 1,'msg' => '关闭订单成功']);
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

    public function info(){
        return $this->fetch();
    }

    public function change_pass(){
        $data = input('post.');
        $result = $this->validate($data,[
            'old_password|原密码' => 'require|alphaNum|length:6,18',
            'password|新密码' => 'require|alphaNum|length:6,18'
        ]);

        if($result !== true){
            return json(['code' => 0,'msg' => $result]);
        }


        $en_password = md5($data['old_password'] . config('app.pass_salt'));

        if($en_password != $this->userInfo['password']){
            return json(['code' => 0,'msg' => '修改失败，原密码不正确']);
        }

        $en_new_password = md5($data['password'] . config('app.pass_salt'));

        Users::where('id',$this->userInfo['id'])->update([
           'password' => $en_new_password
        ]);

        return json(['code' => 1,'msg' => '密码修改成功，请重新登录']);
    }

    public function set_userinfo(){
        $data = input('post.');
        $result = $this->validate($data,[
           'nickname|昵称' => 'require|max:30|min:1',
           'desc|个性签名' => 'max:255'
        ]);

        if($result !== true){
            return json(['code' => 0,'msg' => $result]);
        }

        Users::where('id',$this->userInfo['id'])->update([
            'nickname' => $data['nickname'],
            'desc' => $data['desc']
        ]);

        return json(['code' => 1,'msg' => '更新用户资料成功']);
    }

    public function upload_avatar(){
        $file = request()->file('file');

        if(!$file){
            return json(['code' => 0,'msg' => '请选择上传的文件','data' => []]);
        }

        // 根目录
        $root_path = getSafeDirSeparator(realpath(env('root_path') . './public').'/avatar');

        $info = $file->validate(['size'=> 2097152,'ext'=>'jpg,png,gif'])->move($root_path);
        if($info){
            $avatar = '/avatar/'.str_replace('\\','/',$info->getSaveName());
            Users::where('id',$this->userInfo['id'])->update([
                'avatar' => $avatar
            ]);
            return json(['code' => 1,'msg' => '头像上传成功','data' => []]);
        }else{
            // 上传失败获取错误信息
            return json(['code' => 0,'msg' => $file->getError(),'data' => []]);
        }
    }

}