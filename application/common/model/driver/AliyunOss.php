<?php

namespace app\common\model\driver;

use OSS\Core\OssException;
use OSS\OssClient;
use think\Exception;

class AliyunOss implements PolicyStore
{

    public function upload($info,$policy,$path){

        // 临时文件地址
        $temp_file = $path['temp_path'] . $path['filename'];

        // 保存临时文件
        $file_info = $info['file']['data']->move($path['temp_path'],$path['filename']);

        if(!$file_info){
            throw new Exception($info['file']['data']->getError());
        }

        $accessKeyId = $policy['config']['access_key'];
        $accessKeySecret = $policy['config']['access_secret'];
        $endpoint = $policy['config']['endpoint'];
        $bucket = $policy['config']['bucket'];

        $policy['config']['save_dir'] = ltrim($policy['config']['save_dir'],'/');

        $object = getDiyDirSeparator('/',$policy['config']['save_dir']. $path['file'].$path['filename']);

        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $data = $ossClient->uploadFile($bucket,$object,$temp_file);

            var_dump($data);


        } catch(OssException $e) {
            throw new Exception('FAILED：'.$e->getMessage());
        }

        return 0;
    }

    public function download()
    {
        // TODO: Implement download() method.
    }


}