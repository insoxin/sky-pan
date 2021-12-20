<?php
namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\driver\AliyunOss;
use app\common\model\driver\Local;
use app\common\model\driver\TxyunOss;
use app\common\model\FileManage;
use app\common\model\Folders;
use app\common\model\Order;
use app\common\model\Profit;
use app\common\model\Record;
use app\common\model\Reports;
use app\common\model\Shares;
use app\common\model\Stores;
use app\common\model\Users;
use Qrcode\Qrcode;
use think\Exception;
use think\facade\Cookie;
use think\response\Download;

class Index extends Home
{
    public function index()
    {
        return $this->fetch('index_'.config('basic.index_theme'));
    }

    public function share(){

        $code = $this->request->param('code');
        //分享信息
        $info = Shares::where('code',$code)->find();

        //分享信息不存在
        if(empty($info)){
            return $this->fetch('404');
        }

        // 判断类型
        if($info['type']){
            $storeInfo = Folders::where('uid',$info['uid'])
                ->where('id',$info['source_id'])
                ->find();
        }else{
            $storeInfo = Stores::where('uid',$info['uid'])
                ->where('id',$info['source_id'])
                ->find();
        }

        // 存储文件/夹不存在
        if(empty($storeInfo)){
            return $this->fetch('404');
        }

        // 获取文件所有者信息
        $user = Users::where('id',$storeInfo['uid'])->field('id,nickname,avatar,status,group,desc')->find();

        if(empty($user) || $user['status'] == 0){
            return $this->fetch('404');
        }

        $this->assign('share_id',$info['id']);
        $this->assign('is_login',$this->is_login);
        $this->assign('url',getShareUrl($code));

        $share_pwd = Cookie::get('share_key_'.$code);

        // 需要密码
        if($info['pwd_status'] == 1 && !empty($info['pwd']) && $share_pwd != $info['pwd']){
            // VIP用户组
            $vip_group = config('vip.vip_group');

            $is_vip = $user['group'] == $vip_group ? 1 : 0;

            $desc = empty(trim($user['desc'])) ? '暂无签名' : $user['desc'];

            $this->assign('desc',$desc);
            $this->assign('userinfo',$user);
            $this->assign('is_vip',$is_vip);
            return $this->fetch('pwd');
        }

        // 显示界面
        if($info['type']){

            // 目录显示
            $base_info = [
                'id' => $storeInfo['id'],
                'username' => getSafeNickname($user['nickname']),
                'folder_name' => $storeInfo['folder_name'],
                'desc' => $storeInfo['desc'],
                'is_desc' => empty(trim($storeInfo['desc'])) ? 0 : 1,
                'create_time' => $storeInfo->getData('create_time')
            ];

            Folders::where('id',$storeInfo['id'])->where('uid',$storeInfo['uid'])->setInc('count_open',1);

            // 获取当前文件夹下所有文件
            $share_list = FileManage::ShareListFile($storeInfo['id'],$user['id']);

            $this->assign('share_list',$share_list);
            $this->assign('info',$base_info);

            if($this->request->isMobile()){
                return $this->fetch('folder_wap');
            }
            return $this->fetch('folder');
        }else{

            // 统计数据
            Profit::record($storeInfo['uid'],$storeInfo['id'],'view');

            // 文件显示
            $file_info = [
                'username' => getSafeNickname($user['nickname']),
                'file_name' => $storeInfo['origin_name'],
                'size' => countSize($storeInfo['size']),
                'create_time' => friendDate($storeInfo->getData('create_time'))
            ];

            // 获取VIP价格
            $vip_rule = getVipRule();

            // 获取最高价格
            $vip_max = end($vip_rule)['id'];

            $download = getFileDownloadUrl($storeInfo['shares_id'],$storeInfo['id']);

            $this->assign('vip_max',$vip_max);
            $this->assign('vip_rule',$vip_rule);
            $this->assign('download',$download);
            $this->assign('user',$this->userInfo);
            $this->assign('info',$file_info);
            return $this->fetch('file');
        }
    }

    public function report(){
        $share_id = input('get.share_id');

        $share = Shares::where('id',$share_id)->find();

        if(empty($share)){
            return $this->fetch('user/err',['msg' => '传递参数不正确']);
        }

        // 获取文件信息
        if($share['type'] == 1){
            $files = Folders::where('id',$share['source_id'])->find();
        }else{
            $files = Stores::where('id',$share['source_id'])->find();
        }

        // 文件信息获取失败
        if(empty($files)){
            return $this->fetch('user/err',['msg' => '文件信息获取失败']);
        }

        // 获取用户信息
        $share_user = Users::where('id',$share['uid'])->find();

        if(empty($share_user)){
            return $this->fetch('user/err',['msg' => '举报用户不存在']);
        }

        // 分享信息
        $report = [
            'share_id' => $share['id'],
            'source_name' => $share['type'] ? $files['folder_name'] : $files['origin_name'],
            'source_url' => getShareUrl($share['code']),
            'source_uid' => $share['uid'],
            'source_username' => $share_user['username'],
            'source_type' => $share['type'],
            'create_time' => time(),
            'real_ip' => request()->ip()
        ];

        if($this->request->isPost()){
            $data = input('post.');
            $result = $this->validate($data,[
               'contact|联系方式' => 'require',
                'content|详细描述' => 'require',
                'type|危害类别' => 'require'
            ]);

            if($result !== true) return json(['code' => 0,'msg' => $result]);

            $report_key = 'report_'.$share_id;

            if(!empty(Cookie::get($report_key))){
                return json(['code' => 0,'msg' => '您已经举报过了，请勿重复举报']);
            }

            $report = array_merge($report,$data);

            Cookie::set('report_'.$share_id,'1');

            Reports::create($report);

            return json(['code' => 1,'msg' => '举报反馈成功']);
        }

        return $this->fetch();
    }

    public function qrcode(){
        $url = input('get.url');
        if(!empty($url)){
            Qrcode::createQrcode($url);
        }
    }

    public function share_pass(){
        $id = input('post.id');
        $pass = input('post.pass');

        $id = intval($id);

        $info = Shares::where('id',$id)->find();

        if(empty($info)){
            return json(['status' => 0,'msg' => '分享数据不存在']);
        }

        if($pass != $info['pwd']){
            return json(['status' => 0,'msg' => '提取码不正确']);
        }

        Cookie::set('share_key_'.$info['code'],$info['pwd'],3600);

        return json(['status' => 1,'msg' => '提取码正确']);
    }

    public function download(){
        $file = input('get.file');
        $shares_id = input('get.shares');
        $timestamp = input('get.timestamp');
        $sign = input('get.sign');
        $is_count = input('get.is_count');

        // 数据签名校验
        if(!getDownloadFileSignVerify([
            'file' => $file,
            'shares' => $shares_id,
            'timestamp' => $timestamp
        ],$sign)){
            return $this->fetch('user/err',['msg' => '数据sign签名校验失败']);
        }

        try {

            // 获取存储数据
            $stores = Stores::get($file);

            if(empty($stores)){
                throw new Exception('文件数据不存在');
            }

            if($is_count){
                // 统计数据
                Profit::record($stores['uid'],$stores['id'],'down',1);
                $download_url = getFileDownloadUrl($stores['shares_id'],$stores['id'],0);
                return redirect($download_url);
            }

            // 获取存储策略
            $policy = $stores->getPolicy();

            if(empty($policy)){
                throw new Exception('文件数据存储策略不存在');
            }

            // 禁止下载
            if($this->groupData['speed'] === 0){
                throw new Exception('您当前的用户组禁止下载文件');
            }

            // 远程文件下载
            if($policy->type == 'remote'){
                if(isset($policy->config['download_uri']) && !empty($policy->config['download_uri'])){
                    $down_url = getDownloadRemote($stores['file_name'],$stores['origin_name'],$policy->config['download_uri'],$this->groupData['speed'],$policy->config['access_token']);
                }else{
                    $down_url = getDownloadRemote($stores['file_name'],$stores['origin_name'],$policy->config['server_uri'],$this->groupData['speed'],$policy->config['access_token']);
                }
                $this->redirect($down_url);
            }

            // 存储驱动下载
            switch ($policy->type){
                case 'aliyunoss':
                    $driver = new AliyunOss(0,0,0);
                    break;
                case 'txyunoss':
                    $driver = new TxyunOss(0,0,0);
                    break;
                default:
                    $driver = new Local(0,0,0);
                    break;
            }

            // 下载
            return $driver->download($stores,$this->groupData['speed'],$policy);

        }catch (Exception $e){
            return $this->fetch('user/err',['msg' => $e->getMessage()]);
        }
    }

    public function return_callback(){
        $param = input('get.');
        $sign = input('get.sign');

        if($sign != $this->sign_param($param)){
            $this->error('签名校验失败');
        }

        $order = Order::where('trade_no',$param['out_trade_no'])->find();

        if(empty($order)){
            $this->error('订单数据不存在');
        }

        if($order['status'] != 1){
            $this->error('订单未支付成功');
        }

        $this->success('VIP开通/续费成功','none','请刷新当前页面，然后重新点击下载');
    }

    public function return_notify(){
        $param = input('get.');
        $sign = input('get.sign');

        if($sign != $this->sign_param($param)){
            exit('Fail is sign verify.');
        }

        if($param['trade_status'] != 'TRADE_SUCCESS'){
            exit('Fail is trade status.');
        }

        // 查找订单
        $order = Order::where('trade_no',$param['out_trade_no'])->find();

        if(empty($order)){
            exit('Fail order is not found.');
        }

        // 查找用户
        $user = Users::where('id',$order['uid'])->find();

        if(empty($user)){
            exit('Fail order user is not found.');
        }

        // VIP用户组
        $vip_group = config('vip.vip_group');
        // 开通/续费时长
        $vip_expire = $order['vip_day'] * 86400;
        // 开通/续费VIP
        if($user['group'] == $vip_group){
            // 续费VIP
            $user['group_expire'] = $user['group_expire'] + $vip_expire;
        }else{
            // 开通VIP
            $user['group'] = $vip_group;
            $user['group_expire'] = time() + $vip_expire;
        }

        // 保存数据
        $user->save();

        // 完成订单
        $order['status'] = 1;
        $order['out_trade_no'] = $param['trade_no'];
        $order['pay_time'] = time();

        // 保存订单
        $order->save();

        // 查询分成用户
        $share = Shares::where('id',$order['profit_id'])->find();
        // 参与分成
        if(!empty($share)){
            // 用户分成奖励比例
            $vip_profit = config('vip.vip_profit');
            // 计算用户分成奖励
            $user_profit = ($order['money'] / 100) * $vip_profit;
            // 通用户不奖励
            if($order['uid'] != $share['uid']){
                // 奖励
                Record::addRecord($share['uid'],0,'VIP分成',$user_profit,'用户'.getSafeNickname($user['nickname']).'开通VIP奖励');
                // 记录
                Profit::record($share['uid'],$share['source_id'],'count_order',1);
                Profit::record($share['uid'],$share['source_id'],'count_order_yes',1);
                Profit::record($share['uid'],$share['source_id'],'count_money',$order['money']);
                Profit::record($share['uid'],$share['source_id'],'count_profit',$user_profit);
            }
        }

        // 处理完成
        exit('SUCCESS');
    }

    protected function sign_param($param): string
    {
        unset($param['sign']);
        unset($param['sign_type']);
        ksort($param);
        reset($param);
        return md5(urldecode(http_build_query($param)) . config('pay.api_key'));
    }

}
