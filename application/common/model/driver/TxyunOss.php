<?php

namespace app\common\model\driver;

use Qcloud\Cos\Client;
use Qcloud\Cos\Exception\ServiceResponseException;
use think\Exception;
use think\facade\Cache;

class TxyunOss extends PolicyStore
{

    public function uploadSimple(){

        $secretId = $this->policy['config']['secret_id'];
        $secretKey = $this->policy['config']['secret_key'];
        $region = $this->policy['config']['region'];
        $bucket = $this->policy['config']['bucket'];

        // 文件路径
        $file_path = $this->info['file']['data']->getInfo('tmp_name');
        $file = fopen($file_path, 'rb');

        // 存储路径
        $policy_save_dir = ltrim($this->policy['config']['save_dir'],'/');
        $object = getDiyDirSeparator('/',$policy_save_dir. $this->path['file'].$this->path['filename']);

        try {
            // COS对象
            $client = new Client([
                'region' => $region,
                'schema' => 'http',
                'credentials' => [
                    'secretId' => $secretId,
                    'secretKey' => $secretKey
                ]
            ]);

            if ($file) {
                // 上传文件
                $client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $object,
                    'Body' => $file
                ]);

                // 返回文件存储地址
                return '/'. $object;
            }else{
                throw new Exception('上传文件路径错误');
            }

        }catch (ServiceResponseException $e){
            throw new Exception($e->getMessage());
        }catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function uploadPart()
    {

        // 配置信息
        $secretId = $this->policy['config']['secret_id'];
        $secretKey = $this->policy['config']['secret_key'];
        $region = $this->policy['config']['region'];
        $bucket = $this->policy['config']['bucket'];

        // 分片数据存储名称
        $chunks_saveKey = 'txyunoss_chunks_'.$this->info['uid'].'_'.$this->info['chunk']['key'];

        // 文件名称
        $temp_filename = 'chunks_'.md5($this->info['chunk']['key']).'_'.$this->getRandomKey(4) . '_part_'.$this->info['chunk']['chunk'].'.chunk';
        // 临时文件地址
        $temp_file = $this->path['temp_path'] . $temp_filename;

        // 保存临时文件
        $file_info = $this->info['file']['data']->move($this->path['temp_path'],$temp_filename);

        if(!$file_info){
            throw new Exception('分片创建错误：'.$this->info['file']['data']->getError());
        }

        // 分片存储列表
        $chunk_list = Cache::get($chunks_saveKey) ?? [];

        // 加入分片文件
        $chunk_list[] = $temp_file;

        // 保存分片临时存储列表
        Cache::set($chunks_saveKey,$chunk_list);

        // 分片上传成功
        if($this->info['chunk']['chunk'] == ($this->info['chunk']['chunks'] - 1)){

            // COS对象
            $client = new Client([
                'region' => $region,
                'schema' => 'http',
                'credentials' => [
                    'secretId' => $secretId,
                    'secretKey' => $secretKey
                ]
            ]);

            $save_file = $this->path['temp_path'] . 'cos_cob_'.substr(md5($this->info['chunk']['key']),0,8).'_t_'.time().'.bak';

            // 融合文件路径
            $fileObj = fopen($save_file,"a+");

            // 融合文件
            foreach ($chunk_list as $value) {
                $chunkObj = fopen($value, "rb");

                if(!($fileObj && $chunkObj)){
                    throw new Exception('分片融合文件创建失败');
                }

                $content = fread($chunkObj, (2 * 1024 * 1024));
                fwrite($fileObj, $content, (2 * 1024 * 1024));
                unset($content);
                fclose($chunkObj);
                // 删除分片文件
                unlink($value);
            }

            // 清空分片临时存储列表
            Cache::rm($chunks_saveKey);

            try {
                $policy_save_dir = $this->policy['config']['save_dir'];
                $upload_object_path = getDiyDirSeparator('/',$policy_save_dir. $this->path['file'].$this->path['filename']);

                if ($fileObj) {
                    // 上传文件
                    $client->Upload($bucket,$upload_object_path,$fileObj);

                    // 删除临时组合文件
                    @unlink($save_file);

                    return $upload_object_path;
                }else{
                    throw new Exception('上传文件路径错误');
                }

            }catch (ServiceResponseException $e){
                throw new Exception($e->getMessage());
            }catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        return 'chunk_file';
    }

    protected function getRandomKey($length = 16){
        $charTable = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = "";
        for ( $i = 0; $i < $length; $i++ ){
            $result .= $charTable[ mt_rand(0, strlen($charTable) - 1) ];
        }
        return $result;
    }

    public function download($stores, $speed, $policy)
    {
        return parent::download($stores, $speed, $policy); // TODO: Change the autogenerated stub
    }
}