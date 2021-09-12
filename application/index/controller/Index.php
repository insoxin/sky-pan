<?php
namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\Folders;
use app\common\model\Reports;
use app\common\model\Shares;
use app\common\model\Stores;
use app\common\model\Users;
use Qrcode\Qrcode;
use think\facade\Cookie;

class Index extends Home
{
    public function index()
    {
        return $this->fetch();
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
            $store = Folders::withTrashed();
        }else{
            $store = Stores::withTrashed();
        }

        // 获取存储信息
        $storeInfo = $store->where('uid',$info['uid'])
            ->where('id',$info['source_id'])
            ->find();

        // 存储文件/夹不存在
        if(empty($storeInfo)){
            return $this->fetch('404');
        }

        // 获取文件所有者信息
        $user = Users::where('id',$storeInfo['uid'])->field('id,nickname,avatar,status')->find();

        if(empty($user) || $user['status'] == 0){
            return $this->fetch('404');
        }

        $this->assign('share_id',$info['id']);
        $this->assign('is_login',$this->is_login);
        $this->assign('url',getShareUrl($code));

        $share_pwd = Cookie::get('share_key_'.$code);

        // 需要密码
        if($info['pwd_status'] == 1 && !empty($info['pwd']) && $share_pwd != $info['pwd']){
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

            // 获取当前文件夹下所有文件
            $share_list = FileManage::ShareListFile($storeInfo['id'],$user['id']);

            $this->assign('share_list',$share_list);
            $this->assign('info',$base_info);

            if($this->request->isMobile()){
                return $this->fetch('folder_wap');
            }
            return $this->fetch('folder');
        }else{
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


            $this->assign('vip_max',$vip_max);
            $this->assign('vip_rule',$vip_rule);
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

}
