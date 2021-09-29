<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\FileUpload;
use app\common\model\Groups;
use app\common\model\Policys;
use app\common\model\Shares;
use app\common\model\Stores;
use think\Exception;
use think\facade\Cache;

class Upload extends Home
{

    public function index(){

        // 上传策略
        $policy = $this->getPolicy();

        // 上传文件数量限制
        $upload_limit = 20;

        // 上传限制
        $upload_rule = [
            'upsize_byte' => $this->groupData['max_storage'],
            'upsize' => countSize($this->groupData['max_storage']),
            'fsize_byte' => $this->groupData['max_storage'] * $upload_limit,
            'fsize' => countSize($this->groupData['max_storage'] * $upload_limit),
        ];

        $folder_id = FileManage::getFolderAllowPid(0,$this->userInfo['id']);

        $upload_api = $this->getPolicyUrl($policy,[
            'md' => 'upload',
            'size' => $this->groupData['max_storage'],
            'notify' => url('upload/notify','',false,true),
            'root_folder_id' => $folder_id
        ]);

        $this->assign('upload_api',$upload_api);

        $this->assign('upload_limit',$upload_limit);
        $this->assign('upload_rule',$upload_rule);

        return $this->fetch();
    }

    public function notify(){
        $data = input('get.');
        $policy_id = input('get.policy_id');
        $sign = input('get.sign');

        $policy = Policys::where('id',$policy_id)->find();

        if(empty($policy)){
            exit('Fail policy not found.');
        }

        if(empty($sign)){
            exit('Fail is no params a sign.');
        }

        $remote_sign = $this->remote_sign_params($data,$policy['config']['access_token']);

        if($remote_sign != $sign){
            exit('Fail is sign verify.');
        }

        // 加入数据库
        $data = [
            'uid' => $data['uid'],
            'origin_name' => $data['name'],
            'file_name' => $data['path'],
            'size' => $data['size'],
            'meta' => '',
            'mime_type' => $data['mime'],
            'ext' => $data['ext'],
            'parent_folder' => $data['folder_id'],
            'policy_id' => $policy['id'],
            'dir' => '',
            'create_time' => time(),
            'update_time' => time()
        ];

        $file_id = (new Stores)->insertGetId($data);

        $share_id = Shares::addShare($data['uid'],$file_id,0);

        Stores::where('id',$file_id)->update(['shares_id' => $share_id]);

        exit('UPLOAD_SUCCESS');
    }

    public function file(){

        // 存储策略
        $policy = $this->getPolicy();

        try {
            // 上传文件
            $result = (new FileUpload())
                ->source($this->userInfo['id'],$policy,$this->groupData['max_storage'])
                ->upload();

            return json($result);

        }catch (Exception $e){
            return json(['code' => 0,'msg' => $e->getMessage()]);
        }

    }

}