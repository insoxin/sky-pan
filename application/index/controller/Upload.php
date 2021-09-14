<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\Shares;
use app\common\model\Stores;

class Upload extends Home
{

    public function index(){

        return $this->fetch();
    }

    public function file(){

        // 超时时间5分钟
        @set_time_limit(5 * 60);

        //目录校验获取
        $folder_id = input('post.folder_id',0);

        $folder_id = FileManage::getFolderAllowPid($folder_id,$this->userInfo['id']);

        if(empty($folder_id)){
            return json(['code' => 0,'msg' => '上传目标文件夹不存在']);
        }


        //文件获取
        $files = request()->file('file');

        if(empty($files)){
            return json(['code' => 0,'msg' => '请选择上传的文件']);
        }

        // 存储策略
        $policy = $this->getPolicy();


        // 判断存储类型
        if($policy['type'] != 'local'){
            return json(['code' => 0,'msg' => '您不可以使用该存储策略']);
        }

        //判断存储类型
        $paths = $this->getUploadPaths($policy);

        $file_validate = [];

        if(!empty($policy['max_size'])){
            $file_validate['size'] = $policy['max_size'];
        }

        if(!empty($policy['filetype'])){
            $file_validate['ext'] = $policy['filetype'];
        }

        $info = $files->validate($file_validate)->move($paths['path'],$paths['name']);

        // 保存结果
        if($info){

            // 加入数据库
            $data = [
                'uid' => $this->userInfo['id'],
                'origin_name' => $info->getInfo('name'),
                'file_name' => $paths['file'] . $info->getFilename(),
                'size' => $info->getInfo('size'),
                'meta' => '',
                'mime_type' => $info->getMime(),
                'ext' => $info->getExtension(),
                'parent_folder' => $folder_id,
                'policy_id' => $policy['id'],
                'dir' => '',
                'create_time' => time(),
                'update_time' => time()
            ];

            $file_id = (new Stores)->insertGetId($data);

            $share_id = Shares::addShare($this->userInfo['id'],$file_id,0);

            Stores::where('id',$file_id)->update(['shares_id' => $share_id]);

            return json(['code' => 1,'msg' => '文件上传成功']);

        }else{
            return json(['code' => 0,'msg' => $files->getError()]);
        }

    }

    protected function getUploadPaths($policy): array
    {
        // 目录分割
        $ds = DIRECTORY_SEPARATOR;

        // 配置目录
        $save_dir = str_replace('/',$ds,$policy['config']['save_dir']);

        // 根目录
        $root_path = realpath(env('root_path') . './public') . $save_dir;

        // 当前保存目录
        $file_path = date('Ymd') . $ds . $this->userInfo['id'] . $ds;

        //文件名
        $file_name = uniqid( "file_") . time();

        return [
            'root' => $root_path,
            'file' => $file_path,
            'path' => $root_path . $file_path,
            'name' => $file_name
        ];

    }
}