<?php

namespace app\common\model\driver;

use OSS\Core\OssException;
use OSS\OssClient;
use think\Exception;

class AliyunOss extends PolicyStore
{

    public function uploadSimple(){

        // 临时文件地址
        $temp_file = $this->path['temp_path'] . $this->path['filename'];

        // 保存临时文件
        $file_info = $this->info['file']['data']->move($this->path['temp_path'],$this->path['filename']);

        if(!$file_info){
            throw new Exception($this->info['file']['data']->getError());
        }

        $accessKeyId = $this->policy['config']['access_key'];
        $accessKeySecret = $this->policy['config']['access_secret'];
        $endpoint = $this->policy['config']['endpoint'];
        $bucket = $this->policy['config']['bucket'];

        $policy_save_dir = ltrim($this->policy['config']['save_dir'],'/');

        $object = getDiyDirSeparator('/',$policy_save_dir. $this->path['file'].$this->path['filename']);

        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $ossClient->uploadFile($bucket,$object,$temp_file);

            // 返回文件存储地址
            return '/'. $object;

        } catch(OssException $e) {
            throw new Exception('FAILED：'.$e->getMessage());
        }
    }

    public function uploadPart()
    {

    }

    public function download()
    {
        // TODO: Implement download() method.
    }


}