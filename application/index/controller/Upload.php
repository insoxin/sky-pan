<?php

namespace app\index\controller;

use app\common\controller\Home;

class Upload extends Home
{

    public function index(){
        return $this->fetch();
    }

    public function file(){
        // 超时时间5分钟
        @set_time_limit(5 * 60);

        // 存储策略
        $policy = $this->getPolicy();

        // 判断存储类型
        if($policy['type'] != 'local'){
            return json(['code' => 0,'msg' => '您不可以使用该存储策略']);
        }

        //判断存储类型
        $paths = $this->getUploadPaths($policy);

        $files = request()->file('file');

        if(empty($files)){
            return json(['code' => 0,'msg' => '请选择上传的文件']);
        }

        $file_validate = [];

        if(!empty($policy['max_size'])){
            $file_validate['size'] = $policy['max_size'];
        }

        if(!empty($policy['filetype'])){
            $file_validate['ext'] = $policy['filetype'];
        }

        $info = $files->validate($file_validate)->move($paths['path'],$paths['name']);

        if($info){
            echo $info->getExtension();
            echo $info->getSaveName();
            echo $info->getFilename();

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