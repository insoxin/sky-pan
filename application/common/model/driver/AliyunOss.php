<?php

namespace app\common\model\driver;

use OSS\Core\OssException;
use OSS\OssClient;
use think\Exception;
use think\facade\Cache;

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

            // 删除临时文件
            @unlink($temp_file);

            // 返回文件存储地址
            return '/'. $object;

        } catch(OssException $e) {
            throw new Exception('FAILED：'.$e->getMessage());
        }
    }

    public function uploadPart()
    {

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

        // 缓存分片上传地址
        $chunks_object_keys = 'aliyun_oss_chunks_'.$this->info['chunk']['key'];
        $multi_upload_object = Cache::get($chunks_object_keys);


        try {
            $client = new OssClient($accessKeyId,$accessKeySecret,$endpoint);

            // 缓存防止重复文件地址
            if(empty($multi_upload_object)){
                $upload_object_path = getDiyDirSeparator('/',$policy_save_dir. $this->path['file'].$this->path['filename']);

                $uploadId = $client->initiateMultipartUpload($bucket,$upload_object_path);

                $multi_upload_object = [
                    'object' => $upload_object_path,
                    'upload_id' => $uploadId
                ];

                Cache::set($chunks_object_keys,$multi_upload_object);
            }

            // 分片上传配置
            $options = [
                // 上传文件地址
                OssClient::OSS_FILE_UPLOAD => $temp_file,
                // 分片号
                OssClient::OSS_PART_NUM => $this->info['chunk']['chunk'] + 1,
            ];

            $client->uploadPart($bucket, $multi_upload_object['object'], $multi_upload_object['upload_id'], $options);

            // 组合文件
            if(($this->info['chunk']['chunk'] + 1) == $this->info['chunk']['chunks']){

                // 获取所有分片信息
                $listPartsInfo = $client->listParts($bucket, $multi_upload_object['object'], $multi_upload_object['upload_id']);

                $uploadParts = [];

                foreach ($listPartsInfo->getListPart() as $partInfo) {

                    $uploadParts[] = [
                        'PartNumber' => $partInfo->getPartNumber(),
                        'ETag' => $partInfo->getETag()
                    ];

                }

                // 合并分片
                $client->completeMultipartUpload($bucket, $multi_upload_object['object'], $multi_upload_object['upload_id'], $uploadParts);

                // 删除分片缓存信息
                Cache::rm($chunks_object_keys);
                // 删除临时文件
                @unlink($temp_file);

                // 返回结果
                return '/' . $multi_upload_object['object'];
            }

            // 删除临时文件
            @unlink($temp_file);

            return 'chunk_file';

        }catch (OssException $e){
            throw new Exception('FAILED：'.$e->getMessage());
        }
    }

    public function download()
    {
        // TODO: Implement download() method.
    }


}