<?php

namespace app\common\model;

use app\common\model\driver\AliyunOss;
use app\common\model\driver\Local;
use app\common\model\driver\TxyunOss;
use think\Exception;

class FileUpload
{

    /**
     * @var int 储存策略
     */
    protected $policy;

    /**
     * @var int 上传用户
     */
    protected $uid;

    /**
     * @var array 上传数据
     */
    protected $info;


    /**
     * 设置来源信息
     * @param $uid
     * @param $policy
     * @return FileUpload
     */
    public function source($uid,$policy): FileUpload
    {
        $this->uid = $uid;
        $this->policy = $policy;
        return $this;
    }

    /**
     * 检查文件信息
     * @throws Exception
     */
    protected function check(){

        if(empty($this->info['file']['folder'])){
            throw new Exception('上传目标文件夹不存在');
        }

        if(empty($this->info['file']['data'])){
            throw new Exception('请选择上传的文件');
        }

        if(empty($this->info['file']['size'])){
            throw new Exception('文件大小异常');
        }

        if(empty($this->info['file']['name'])){
            throw new Exception('上传文件名异常');
        }

        if($this->policy['type'] == 'remote'){
            throw new Exception('您不可以使用该存储策略');
        }

        if(!empty($this->policy['max_size'])){
            if($this->info['file']['size'] > $this->policy['max_size']){
                throw new Exception('单文件最大上传大小'.countSize($this->policy['max_size']));
            }
        }

        if(!empty($this->policy['filetype'])){
            $file_ext = $this->getFileExt($this->info['file']['name']);
            $allow_ext = explode(',',$this->policy['filetype']);
            if(!in_array($file_ext,$allow_ext)){
                throw new Exception('不允许上传'.$file_ext.'类型的文件');
            }
        }

    }

    /**
     * 获取文件后缀名
     * @param $filename
     * @return string
     */
    protected function getFileExt($filename): string
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }



    public function upload(){
        // 超时时间5分钟
        @set_time_limit(5 * 60);

        // 获取有效的目录ID
        $this->info['file']['folder'] = FileManage::getFolderAllowPid(input('post.folder_id',0),$this->uid);

        // 获取上传文件大小
        $this->info['file']['size'] = input('post.size',0);

        // 获取上传文件名
        $this->info['file']['name'] = input('post.name','');

        // 上传文件对象获取
        $this->info['file']['data'] = request()->file('file');

        // 分片上传
        $this->info['chunk']['chunk'] = input('post.chunk',0);
        $this->info['chunk']['chunks'] = input('post.chunks',0);

        // 参数校验
        $this->check();

        // 上传驱动
        switch ($this->policy['type']){
            // 阿里云
            case 'aliyunoss':
                $file = (new AliyunOss)->upload($this->info,$this->policy);
                break;
            // 腾讯云
            case 'txyunoss':
                $file = (new TxyunOss)->upload($this->info,$this->policy);
                break;
            // 本地
            case 'local':
                $file = (new Local)->upload($this->info,$this->policy);
                break;
        }


    }

}